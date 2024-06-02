<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostInteractionServices
{
    use GeneralTrait;

    public function like($postId)
    {
        $post = Post::find($postId);
        $response = [];
        if (!$post) {
            $response = [
                'status' => false,
                'code' => 404,
                'error' => 'Not Found',
                'data' => '',
                'message' => 'success'
            ];
            return $response;
        }
        $like = PostLike::where('user_id', Auth::user()->id)->where('post_id', $postId)->first();
        if ($like) {
            $like->delete();
        }else{
            PostLike::create([
                'user_id' => Auth::user()->id,
                'post_id' => $postId
            ]);
            $notification_contetnt = 'New Like On Your Post From User ' . Auth::user()->name;
            addNotification($post->user_id,null,$postId,$notification_contetnt,1);
        }
        $likes_count = $post->likes()->count();
        $response = [
            'status' => true,
            'code' => 200,
            'error' => '',
            'data' => $likes_count,
            'message' => 'success'
        ];
        return $response;
    }

    public function likes($postId)
    {
        $post = Post::find($postId);
        $response = [];
        if (!$post) {
            $response = [
                'status' => false,
                'code' => 404,
                'error' => 'Not Found',
                'data' => '',
                'message' => ''
            ];
            return $response;
        }
        $likes = PostLike::where('post_id', $postId)->pluck('user_id');
        $users = User::whereIn('id', $likes)->paginate(15);
        $response = [
            'status' => true,
            'code' => 200,
            'error' => '',
            'data' => $users,
            'message' => 'success'
        ];
        return $response;
    }

    public function comment($postId, Request $request)
    {
        $post = Post::find($postId);
        $response = [];
        if (!$post) {
            $response = [
                'status' => false,
                'code' => 404,
                'error' => 'Not Found',
                'data' => '',
                'message' => 'success'
            ];
            return $response;
        }
        $validator = Validator::make($request->all(),[
            'comment' => 'required'
        ]);
        if($validator->fails()){
            $response = [
                'status' => false,
                'code' => 400,
                'error' => $validator->errors(),
                'data' => '',
                'message' => ''
            ];
            return $response;
        }
            PostComment::create([
                'user_id' => Auth::user()->id,
                'post_id' => $postId,
                'comment' => $request->comment
            ]);
            $notification_contetnt = 'New Comment On Your Post From User ' . Auth::user()->name;
            addNotification($post->user_id,null,$postId,$notification_contetnt,0);
        $response = [
            'status' => true,
            'code' => 200,
            'error' => '',
            'message' => 'success',
            'data' => ''
        ];
    }
}
