<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

	public function store(Request $request, Post $post)
	{
		
		$this->authorize('store', $post);

		$request->validate([
			'text' => 'required|string',
		]);

		$comment = $post->comments()->create([
			'text' => $request->text,
			'user_id' => Auth::id(),
		]);

		return back()->with('success', 'Comment added successfully.');
	}


	public function update(Request $request, Comment $comment)
	{
		$this->authorize('update', $comment);

		$request->validate([
			'text' => 'required|string',
		]);

		$comment->update([
			'text' => $request->text,
			'edited_at' => now(),
		]);

		return back()->with('success', 'Comment updated successfully.');
	}


	public function destroy(Comment $comment)
	{
		$this->authorize('delete', $comment);

		$comment->delete();

		return back()->with('success', 'Comment deleted successfully.');
	}
}
