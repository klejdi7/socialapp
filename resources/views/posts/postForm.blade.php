@extends('layouts.app')

@section('content')
	<div class="container">
		<h1>{{ isset($post) ? 'Edit Post' : 'Create Post' }}</h1>

		<form method="POST" action="{{ isset($post) ? route('posts.update', $post) : route('posts.store') }}">
			@csrf
			@if (isset($post))
				@method('PUT')
			@endif

			<div class="mb-3">
				<label for="title" class="form-label">Title</label>
				<input type="text" name="title" class="form-control" id="title" value="{{ old('title', $post->title ?? '') }}" required>
			</div>

			<div class="mb-3">
				<label for="text" class="form-label">Description</label>
				<textarea name="description" class="form-control" id="description" rows="5" required>{{ old('description', $post->description ?? '') }}</textarea>
			</div>

			<button type="submit" class="btn btn-primary">{{ isset($post) ? 'Update Post' : 'Create Post' }}</button>
		</form>
	</div>
@endsection
