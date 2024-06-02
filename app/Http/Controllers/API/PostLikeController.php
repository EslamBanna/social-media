<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PostInteractionServices;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    use GeneralTrait;
    public function like($postId)
    {
        try {
            $postInteractionService = new PostInteractionServices();
            $output = $postInteractionService->like($postId);
            return $this->returnData('data', $output);
        } catch (\Exception $e) {
            return $this->returnError(500, $e->getMessage());
        }
    }

    public function likes($postId){
        try {
            $postInteractionService = new PostInteractionServices();
            $users = $postInteractionService->likes($postId)['data'];
            return $this->returnData('data', $users);
        } catch (\Exception $e) {
            return $this->returnError(500, $e->getMessage());
        }
    }
}
