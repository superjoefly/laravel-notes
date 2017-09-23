@extends('master')

@section('content')
    <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
            <div class="top-right links">
                @auth
                    <a href="{{ url('/home') }}">Home</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        @endif

        <div class="content">
            <h1>Welcome!</h1>
            <p>Please enter your age (>200) to continue...</p>

        <form method="POST" action="/agecheck">
            {{csrf_field()}}
            <input type="number" name="age" id="age" />
            <input type="submit" value="Submit" />
        </form>

        </div>
    </div>
@endsection
