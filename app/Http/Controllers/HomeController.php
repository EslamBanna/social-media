<?php

namespace App\Http\Controllers;

use App\Services\PostServices;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $postService = new PostServices();
        $posts = $postService->frindesPosts()['data'];
        return view('home', compact('posts'));
    }
}
