<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
			->get();

		return view('posts.home', compact('posts'));
	}

	public function create()
	{
		return view('posts.postForm');
	}


	public function store(Request $request)
	{

		if (Auth::user()->email_verified_at === null) {
			return redirect()->route('posts.index')->with('error', 'You must verify your email before creating a post.');
		}

		$request->validate([
			'title' => 'required|string|max:255',
			'description' => 'required|string',
		]);

		$user_id = Auth::id();
		$user = User::find($user_id);

		$post = $user->posts()->create([
			'title' => $request->title,
			'description' => $request->description,
		]);

		return redirect()->route('posts')->with('success', 'Post created successfully.');

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
