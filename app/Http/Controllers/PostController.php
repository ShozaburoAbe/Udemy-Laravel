<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    //
    public function index()
    {
        $posts = auth()->user()->posts()->paginate(5);



        return view('admin.posts.index', ['posts' => $posts]);
    }

    public function show(Post $post)
    {
        return view('blog-post', ['post' => $post]);
    }

    public function create()
    {
        $this->authorize('create', Post::class);

        return view('admin.posts.create');
    }

    public function store()
    {
        $this->authorize('create', Post::class);

        $inputs = request()->validate([
            'title' => 'required | min:8 | max:225',
            'image' => 'file',
            'body' => 'required'
        ]);

        if (request('image')) {
            $inputs['image'] = request('image')->store('images');
        }

        auth()->user()->posts()->create($inputs);

        session()->flash('post-created-message', 'Post with title was created ' . $inputs['title']);

        return redirect()->route('post.index');
    }

    public function edit(Post $post)
    {
        $this->authorize('view', $post);

        // if (auth()->user()->can('view', $post)) {
        // }

        return view('admin.posts.edit', ['post' => $post]);
    }

    public function destroy(Post $post, Request $request)
    {
        $this->authorize('delete', $post);

        $post->delete();

        $request->session()->flash('message', 'Post was deleted');

        return back();
    }

    public function update(Post $post)
    {

        $inputs = request()->validate([
            'title' => 'required | min:8 | max:225',
            'image' => 'file',
            'body' => 'required'
        ]);

        if (request('image')) {
            $inputs['image'] = request('image')->store('images');
            $post->image = $inputs['image'];
        }

        $post->title = $inputs['title'];
        $post->body = $inputs['body'];

        $this->authorize('update', $post);



        $post->update();

        session()->flash('post-updated-message', 'Post with title was updated ' . $inputs['title']);

        return redirect()->route('post.index');
    }
}
