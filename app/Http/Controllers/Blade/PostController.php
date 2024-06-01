<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostComment;
use App\Services\PostServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $postService = new PostServices();
            $output = $postService->storePost($request);
            DB::commit();
            if (!$output['status']) {
                return redirect()->back()->withInput()->withErrors($output['error']);
            }
            return redirect()->route('home');
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function edit($postId)
    {
        try {
            $post = Post::find($postId);
            if (!$post) {
                return redirect()->back();
            }
            return view('posts.edit', compact('post'));
        } catch (\Exception $e) {
            return $e->getMessage();
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
                return redirect()->back()->withInput()->withErrors($output['error']);
            }
            return redirect()->route('home');
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function delete($postId){
        try{
            DB::beginTransaction();
            $postService = new PostServices();
            $output = $postService->deletePost($postId);
            DB::commit();
            if (!$output['status']) {
                return redirect()->back();
            }
            return redirect()->route('home');
        }catch(\Exception $e){
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function show($id){
        try{
            $post = Post::withCount(['likes', 'comments'])->find($id);
            $comments = PostComment::where('post_id', $id)->get();
            if(! $post){
                return redirect()->back();
            }
            return view('posts.show', compact('post', 'comments'));
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
