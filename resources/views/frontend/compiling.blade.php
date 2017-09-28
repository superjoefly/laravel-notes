@extends('master')

@section('content')
  <h1>Compling Assets (Laravel Mix)</h1>

  <h2>Introduction</h2>

  <p>Laravel Mix provides a fluent API for defining Webpack build steps for Laravel applications using several common CSS and JavaScript pre-processors. Through simple method chaining, we can fluently define our asset pipeline. For example:</p>

  <pre><code class="language-php">
    mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');
  </code></pre>

  <h2>Installation and Setup</h2>

  <h4>Installing Node</h4>

  <p>Before triggering Mix, we first need to make sure Node.js and NPM are installed on our system:</p>

  <pre><code class="language-php">
    node -v

    npm -v
  </code></pre>

  <p>By default, Laravel Homestead includes everything we need, however, if we're not using Vagrant, we can easily install the latest version of Node and NPM using simple graphical installers from their respective websites.</p>

  <h4>Laravel Mix</h4>

  <p>The only remaining step is to install Laravel Mix. In fresh installations of Laravel, we'll find a package.json file in the root of the directory structure. The default package.json file includes everything we need to get started. This file is similar to a composer.json file, except it defines dependencies for Node instead of PHP. We can install the dependencies it references by running the following:</p>

  <pre><code class="language-php">
    npm install
  </code></pre>

  <h2></h2>
@endsection
