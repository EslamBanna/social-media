<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\PostComment;
use App\Services\PostInteractionServices;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    public function comment($postId, Request $request)
    {
        try {
            $postInteractionService = new PostInteractionServices();
            $output = $postInteractionService->comment($postId, $request);
            $comments = PostComment::where('post_id', $postId)->get();
            return [
                'view' => view('posts.comments_section', compact('comments'))->render(),
            ];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
