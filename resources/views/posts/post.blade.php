@extends('layouts.app')

@section('content')
	<div class="container">
		@if(session('error'))
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				{{ session('error') }}
			</div>
		@endif
		<h1>{{ $post->title }}</h1>
		<p class="text-muted">By {{ $post->user->name }} on {{ $post->created_at->format('M d, Y') }}</p>
		<div class="post-content mb-4">
			{{ $post->description }}
		</div>
		@include('posts.actionButtons', ['post' => $post])
		@include('posts.commentSection', ['post' => $post])
	</div>
@endsection
