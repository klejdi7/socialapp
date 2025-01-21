@if(auth()->id() === $post->user_id && $post->comments->count() === 0)
	<div class="mt-2">
		<a href="{{ route('posts.edit', $post) }}" class="btn btn-success btn-sm">Edit</a>

		<form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline-block;">
			@csrf
			@method('DELETE')
			<button type="submit" class="btn btn-danger btn-sm" 
					onclick="return confirm('Are you sure you want to delete this post?');">
				Delete
			</button>
		</form>
	</div>
@elseif(auth()->id() === $post->user_id)
	<p class="text-muted mt-2"><em>You cannot edit or delete this post because it has comments.</em></p>
@endif