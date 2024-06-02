<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PostComment;
use App\Services\PostInteractionServices;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    use GeneralTrait;
    public function comment($postId, Request $request)
    {
        try {
            $postInteractionService = new PostInteractionServices();
            $output = $postInteractionService->comment($postId, $request);
            $comments = PostComment::where('post_id', $postId)->get();
            return $this->returnData('data', $comments);
        } catch (\Exception $e) {
            return $this->returnError(500, $e->getMessage());
        }
    }
}
