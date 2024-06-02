<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostComment;
use App\Services\PostServices;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    use GeneralTrait;

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $postService = new PostServices();
            $output = $postService->storePost($request);
            DB::commit();
            if (!$output['status']) {
                return $this->returnError($output['code'], $output['error']);
            }
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError(500, $e->getMessage());
        }
    }


    public function update(Request $request, $postId)
    {
        try {
            DB::beginTransaction();
            $postService = new PostServices();
            $output = $postService->updatePost($request, $postId);
            DB::commit();
            if (!$output['status']) {
                return $this->returnError($output['code'], $output['error']);
            }
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError(500, $e->getMessage());
        }
    }

    public function delete($postId)
    {
        try {
            DB::beginTransaction();
            $postService = new PostServices();
            $output = $postService->deletePost($postId);
            DB::commit();
            if (!$output['status']) {
                return $this->returnError($output['code'], $output['error']);
            }
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError(500, $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $post = Post::withCount(['likes', 'comments'])->find($id);
            $comments = PostComment::where('post_id', $id)->get();
            if (!$post) {
                return $this->returnError(404, 'Post Not Found');
            }
            $data = [
                'post' => $post,
                'comments' => $comments
            ];
            return $this->returnData('data', $data);
        } catch (\Exception $e) {
            return $this->returnError(500, $e->getMessage());
        }
    }
}
