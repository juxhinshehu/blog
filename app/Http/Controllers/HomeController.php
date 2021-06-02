<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class HomeController extends Controller
{
    
    /**
     * All Posts publicly displayed
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        return view('home', compact('posts'));
    }

    /**
     * Display 1 specific post publicly
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function post($id)
    {
        $post = Post::find($id);
        return view('post-details', compact('post'));
    }
}
