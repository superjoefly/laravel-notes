{{-- extend layouts --}}
@extends('layouts.app')

{{-- yield sections --}}
@section('title', 'Page Title')

{{-- yield sections --}}
@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

{{-- yield sections --}}
@section('content')
    <p>This is my body content.</p>
@endsection
