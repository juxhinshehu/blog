<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Post;

class PostController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->creator == 0) { 
            $posts = Post::all();
        } else { 
            $posts = Post::where('creator', '=', Auth::user()->id)->get();
        }

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
        ]);
        
        $post = new Post();
        $data = $request->all();

        $post->title = $request->get('title');
        $post->body = $request->get('body');
        $post->creator = Auth::user()->id;

        $post->save();

        return redirect()->route('posts.index')->with('success','Post created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        return view('posts.edit',compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);

        $post = Post::find($id);

        // if authenticated user is the creator or the admin only then allowed 
        if (Auth::user()->id == $post->creator || Auth::user()->creator == 0) {
            $post->title = $request->get('title');
            $post->body = $request->get('body');
            $post->save();
            
            return redirect('/posts')->with('success', 'Post has been updated');
        } 
        
        return response("Unauthorized", 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        // if authenticated user is the creator or the admin only then allowed 
        if (Auth::user()->id == $post->creator || Auth::user()->creator == 0) {
            $post->delete();

            return redirect('/posts')->with('success', 'Post has been deleted');
        }

        return response("Unauthorized", 401);

    }
}
