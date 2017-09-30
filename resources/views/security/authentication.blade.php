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

  <p>Laravel makes implementing authentication very simple. The authentication configuration file is located at config/auth.php. This file contains several well documented options for tweaking the behavior of the authentication services.</p>

  <p>Laravel's authentication facilities are made up of "guards" and "providers". Guards define how users are authenticated for each request. For example, Laravel ships with a "session" guard which maintains state using session storage and cookies.</p>

  <p>Providers define how users are retrieved from the persistent storage. Laravel ships with support for retrieving users using Eloquent and the database query builder. However, we can define additional providers as needed by the application.</p>

  <p>Most applications will never need to modify the default authentication configuration.</p>

  <h3>Database Considerations</h3>

  <p>Laravel includes an App\User Eloquent model in the app directory. This model can be used with the default Eloquent authentication driver. If the application is not using Eloquent, we can use the database authentication driver which uses the Laravel query builder.</p>

  <p>When building the database schema for the App\User model, make sure the password column is at least 60 characters in length. Maintaining the default string column length of 255 characters would be a good choice.</p>

  <p>We should also verify that the users (or equivalent) table contains a nullable, string "remember_tokens" column of 100 characters. This column will be used to store a token for users that select the "remember me" option when logging into the application.</p>

  <h2>Authentication Quickstart</h2>
@endsection
