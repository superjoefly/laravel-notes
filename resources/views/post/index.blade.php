@extends('master')

@section('content')
  <h1>Posts</h1>

  <div class="w3-container w3-border-blue">
    @foreach ($posts as $post)
      <p>{{ $post->created_at }}</p>
      <p>{{ $post->title }}</p>
      <p>{{ $post->body }}</p>
      <hr />
    @endforeach
  </div>

@endsection
