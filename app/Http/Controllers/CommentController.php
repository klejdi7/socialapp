<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewCommentNotification;

class CommentController extends Controller
{

	public function store(Request $request, Post $post)
	{
		$request->validate([
			'text' => 'required|string',
		]);

		$comment = $post->comments()->create([
			'text' => $request->text,
			'user_id' => Auth::id(),
		]);

		if ($post->user_id !== Auth::id()) {
			$post->user->notify(new NewCommentNotification($comment));
		}

		return back()->with('success', 'Comment added successfully.');
	}


	public function update(Request $request, Comment $comment)
	{
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
		$comment->delete();

		return back()->with('success', 'Comment deleted successfully.');
	}
}
