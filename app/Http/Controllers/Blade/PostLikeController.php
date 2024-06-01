<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Services\PostInteractionServices;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function like($postId)
    {
        try {
            $postInteractionService = new PostInteractionServices();
            $output = $postInteractionService->like($postId);
            return $output;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function likes($postId){
        try {
            $postInteractionService = new PostInteractionServices();
            $users = $postInteractionService->likes($postId)['data'];
            return view('posts.likes', compact('users'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
