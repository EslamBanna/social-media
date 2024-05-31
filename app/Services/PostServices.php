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
            'data' => $posts
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
                'error' => $validator->errors()
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
            'data' => 'success'
        ];
        return $response;
    }
}
