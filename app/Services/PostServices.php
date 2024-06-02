<?php

namespace App\Services;

use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PostServices
{
    use GeneralTrait;

    public function frindesPosts()
    {
        $userId = Auth::user()->id;
        $friendService = new FriendServices();
        $users = $friendService->frindesIds($userId);
        $users = array_merge([$userId], $users);
        $posts = Post::withCount(['likes', 'comments'])->whereIn('user_id', $users)->orderBy('id', 'DESC')->paginate(15);
        $response = [
            'status' => true,
            'data' => $posts,
            'code' => 200,
            'error' => '',
            'message' => ''
        ];
        return $response;
    }

    public function storePost(Request $request)
    {
        $response = [];
        $validator = Validator::make($request->all(), [
            'content' => 'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'error' => $validator->errors(),
                'data' => '',
                'code' => 500,
                'message' => ''
            ];
            return $response;
        }

        $post = Post::create([
            'user_id' => Auth::user()->id,
            'content' => $request->content
        ]);
        if ($request->has('images')) {
            foreach ($request->images as $image) {
                $img = $this->saveImage($image, 'posts');
                PostImage::create([
                    'post_id' => $post->id,
                    'image' => $img
                ]);
            }
        }
        $response = [
            'status' => true,
            'data' => 'success',
            'code' => 200,
            'error' => '',
            'message' => 'success'
        ];
        return $response;
    }

    public function updatePost(Request $request, $postId)
    {
        $response = [];
        $post = Post::find($postId);
        if (!$post) {
            $response = [
                'status' => false,
                'code' => 404,
                'error' => 'not found',
                'data' => '',
                'message' => 'success'
            ];
            return $response;
        }
        $validator = Validator::make($request->all(), [
            'content' => 'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'error' => $validator->errors(),
                'data' => '',
                'code' => 200,
                'message' => ''
            ];
            return $response;
        }

        $post->update([
            'content' => $request->content
        ]);
        if ($request->has('images')) {
            $post->images()->delete();
            foreach ($request->images as $image) {
                $img = $this->saveImage($image, 'posts');
                PostImage::create([
                    'post_id' => $post->id,
                    'image' => $img
                ]);
            }
        }
        $response = [
            'status' => true,
            'data' => '',
            'code' => 200,
            'error' => '',
            'message' => 'success'
        ];
        return $response;
    }

    public function deletePost($postId)
    {
        $response = [];
        $post = Post::find($postId);
        if (!$post) {
            $response = [
                'status' => false,
                'code' => 404,
                'error' => 'not found',
                'data' => '',
                'message' => ''
            ];
            return $response;
        }
        $post->images()->delete();
        $post->delete();
        $response = [
            'status' => true,
            'data' => '',
            'code' => 200,
            'error' => '',
            'message' => 'success'
        ];
        return $response;
    }
}
