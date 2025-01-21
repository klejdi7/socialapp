<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

	public function index(Request $request)
	{
		$query = $request->input('query');

		$posts = Post::with('user')
			->when($query, function ($q) use ($query) {
				$q->where('title', 'like', "%{$query}%")
					->orWhere('description', 'like', "%{$query}%")
					->orWhereHas('comments', function ($subQuery) use ($query) {
						$subQuery->where('text', 'like', "%{$query}%");
					});
			})
			->orderBy('created_at', 'desc')
			->paginate(10);

		return view('posts.index', compact('posts'));
	}

	public function create()
	{
		return view('posts.form');
	}


	public function store(Request $request)
	{
		$request->validate([
			'title' => 'required|string|max:255',
			'description' => 'required|string',
		]);

		$post = Auth::user()->posts()->create($request->only('title', 'description'));

		return redirect()->route('posts.index')->with('success', 'Post created successfully.');
	}


	public function edit(Post $post)
	{
		$this->authorize('update', $post);

		return view('posts.form', compact('post'));
	}

	public function update(Request $request, Post $post)
	{
		$this->authorize('update', $post);

		$request->validate([
			'title' => 'required|string|max:255',
			'description' => 'required|string',
		]);

		$post->update($request->only('title', 'description'));

		return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
	}


	public function destroy(Post $post)
	{
		$this->authorize('delete', $post);

		if ($post->comments()->exists()) {
			return redirect()->route('posts.index')->with('error', 'Post cannot be deleted because it has comments.');
		}

		$post->delete();

		return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
	}
}
