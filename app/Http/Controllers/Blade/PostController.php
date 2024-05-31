<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
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
}
