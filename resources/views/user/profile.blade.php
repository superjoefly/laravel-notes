@extends('master')

@section('content')
  <h1>User Profile Page</h1>

  <ul style="list-style-type: none;">
    <li>ID: {{$user->id}}</li>
    <li>Name: {{$user->name}}</li>
    <li>Email: {{$user->email}}</li>
  </ul>

@endsection
