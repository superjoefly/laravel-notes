@extends('master')

@section('content')
  <h1>Posts</h1>

  <div class="w3-container w3-border-blue">
    @foreach ($posts as $post)
      {{ $post }}
    @endforeach
  </div>

@endsection
