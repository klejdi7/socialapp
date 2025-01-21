<div class="comments-section mt-2">
	<h3>Comments ({{ $post->comments->count() }})</h3>
	
	@auth
		<form action="{{ route('comments.store', $post) }}" method="POST" class="mb-4">
			@csrf
			<div class="mb-3">
				<textarea class="form-control @error('text') is-invalid @enderror" name="text" rows="3" placeholder="Write a comment..." required></textarea>
				@error('text')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>
			<button type="submit" class="btn btn-primary">Post Comment</button>
		</form>
	@endauth

	@guest
		<p class="text-muted">You must be logged in to post a comment.</p>
	@endguest

	<ul class="list-group">
		@foreach($post->comments as $comment)
			<li class="list-group-item">
				<div>
					<strong>{{ $comment->user->name }}</strong>
					<small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
					@if($comment->edited_at)
						<small class="text-muted">(Edited)</small>
					@endif
				</div>
				<p>{{ $comment->text }}</p>

				@if (auth()->id() === $comment->user_id)
					<div class="d-flex">

						<button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editCommentModal-{{ $comment->id }}">Edit</button>

						<form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?');">
							@csrf
							@method('DELETE')
							<button type="submit" class="btn btn-danger btn-sm">Delete</button>
						</form>
					</div>

					<div class="modal fade" id="editCommentModal-{{ $comment->id }}" tabindex="-1" aria-labelledby="editCommentModalLabel-{{ $comment->id }}" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="editCommentModalLabel-{{ $comment->id }}">Edit Comment</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<form id="editCommentForm-{{ $comment->id }}" method="POST" action="{{ route('comments.update', $comment) }}">
										@csrf
										@method('PUT')
				
										<div class="mb-3">
											<label for="text-{{ $comment->id }}" class="form-label">Comment</label>
											<textarea name="text" id="text-{{ $comment->id }}" class="form-control" rows="5" required>{{ old('text', $comment->text) }}</textarea>
										</div>
				
										<button type="submit" class="btn btn-primary">Update Comment</button>
									</form>
								</div>
							</div>
						</div>
				@endif
			</li>
		@endforeach
	</ul>
</div>
