@extends('master')

@section('content')
  <h1>JavaScript and CSS Scaffolding</h1>

  <h2>Introduction</h2>

  <p>While Laravel does not dictate which JavaScript of CSS pre-processors we use, it does provide a basic starting point using Bootstrap and Vue that can be helpful for many applications. By default, Laravel uses NPM to install both these frontend packages.</p>

  <h4>CSS</h4>

  <p>Laravel Mix provides a clean, expressive API over compliling SASS or LESS, which are extesions of plain CSS that add variables, mixins, and other powerful features that make working with CSS more enjoyable.</p>

  <h4>JavaScript</h4>

  <p>Laravel does not require the use of a JavaScript framework or library to built applications. We really don't have to use JavaScript at all, however, Laravel does provide some basic scaffolding to make it easier to get started writing modern JavaScript using the Vue library. Vue provides an expressive API for building robust JavaScript applications using components. As with CSS, we can use Laravel Mix to compile JavaScript components into a single, browser-ready JavaScript file.</p>

  <h4>Removing the Frontend Scaffolding</h4>

  <p>To remove the frontend scaffolding from our project we can use the preset Artisan command. This command, when combined with the 'none' option, will remove the Bootstrap and Vue scaffolding from our application, leaving only a blank SASS file and a few common JavaScript utility libraries:</p>

  <pre><code class="language-php">
    php artisan preset none
  </code></pre>

  <h2>Writing CSS</h2>

  <p>Laravel's package.json file includes the bootstrap-sass package to help us get started prototyping the application's frontend using Bootstrap. However, we can add and remove packages from the package.json file as needed for our own application. We are not required to use the Bootstrap framework to build our Laravel application - it is simply provided as a starting point.</p>

  <p>Before compiling the CSS, we can install the project's frontend dependencies using NPM.</p>

  <pre><code class="language-php">
    npm install
  </code></pre>

  <p>Once the dependencies have been installed using npm, we can compile our SASS files to plain CSS using Laravel Mix. The 'npm run dev' command will process the instructions in our webpack.mix.js file. Typically, the compiled CSS will be placed in the public/css directory:</p>

  <pre><code class="language-php">
    npm run dev
  </code></pre>

  <p>The default webpack.mix.js included with Laravel will compile the resources/assets/sass/app.scss SASS file. This app.scss file imports a file of SASS variables and loads Bootstrap, which provides a good starting point for most applications. We can customize the app.scss file however we want, or even use an entirely different pre-processor by configuring Laravel Mix.</p>

  



@endsection
