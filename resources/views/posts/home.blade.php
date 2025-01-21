@extends('layouts.app')

@section('content')
	<div class="container">
		<h1>Posts</h1>

		@if(session('error'))
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				{{ session('error') }}
			</div>
		@endif

		<form method="GET" action="{{ route('posts.index') }}">
			<div class="input-group mb-3">
				<input type="text" name="query" class="form-control" placeholder="Search posts..." value="{{ request('query') }}">
				<button class="btn btn-outline-secondary" type="submit">Search</button>
			</div>
		</form>

		<a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Create Post</a>

		@forelse ($posts as $post)
			<div class="card mb-3">
				<div class="card-header">
					<strong>{{ $post->title }}</strong>
					<span class="text-muted">by {{ $post->user->name }} on {{ $post->created_at->format('Y-m-d H:i') }}</span>
				</div>
				<div class="card-body">
					<p>{{ \Str::limit($post->description, 150) }}</p>
					<a href="{{ route('posts.show', $post) }}" class="btn btn-link">Read More</a>
				</div>
			</div>
		@empty
			<p>No posts found.</p>
		@endforelse

	</div>
@endsection
