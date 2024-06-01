<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostServices;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        try {
            $postService = new PostServices();
            $output = $postService->storePost($request);
            if (!$output['status']) {
                return redirect()->back()->withInput()->withErrors($output['error']);
            }
            return redirect()->route('home');
        } catch (\Exception $e) {
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
            $postService = new PostServices();
            $output = $postService->updatePost($request, $postId);
            if (!$output['status']) {
                return redirect()->back()->withInput()->withErrors($output['error']);
            }
            return redirect()->route('home');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete($postId){
        try{
            $postService = new PostServices();
            $output = $postService->deletePost($postId);
            if (!$output['status']) {
                return redirect()->back();
            }
            return redirect()->route('home');
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
