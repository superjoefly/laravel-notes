{{-- extend layouts --}}
@extends('layouts.app')

@section('title', 'Page Title')


@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection


@section('content')
    <p>This is my body content.</p>

    {{-- Echo out variable from route --}}
    {{$name}}

    <br />

    {{-- Echo out php code --}}
    The current UNIX Timestamp is {{ time() }}
@endsection




{{-- Construct the component --}}
@component('alert')
    {{-- Named slot --}}
    @slot('title')
        Alert Title
    @endslot
    <strong>Hello</strong> I am the slotted content!
@endcomponent
