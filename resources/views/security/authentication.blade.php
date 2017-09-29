@extends('master')

@section('content')
  <h1>Authentication</h1>

  <h2>Introduction</h2>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Rapid Authentication Setup: run <b>php artisan make:auth</b> and <b>php artisan migrate</b> in a fresh Laravel application. These two commands will set up the scaffolding for the entire authentication system.</p>
  </div>

  <pre><code class="language-php">
    php artisan make:auth

    php artisan migrate
  </code></pre>
@endsection
