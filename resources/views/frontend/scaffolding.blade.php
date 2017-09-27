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

  <h2>Writing JavaScript</h2>

  <p>All JavaScript dependencies required by the application can be found in the package.json file in the project's root directory. This file is similar to a composer.json file except it specifies JavaScript dependencies instead of PHP dependencies. We can install these dependencies using NPM:</p>

  <pre><code class="language-php">
    npm install
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>By default, the Laravel package.json file includes a few packages such as vue and axios to help get started building JavaScript applications. We can add or remove the package.json file as needed for our own application.</p>
  </div>

  <p>Once the packages are installed, we can use 'npm run dev' to compile the assets. Webpack is a module bundler for modern JavaScript applications. When we execute 'npm run dev', Webpack will execute the instructions in the webpack.mix.js file:</p>

  <pre><code class="language-php">
    npm run dev
  </code></pre>

  <p>By default, the Laravel webpack.mix.js file compiles the SASS and resources/assets/js/app.js file. Within the app.js file, we can register our Vue components, if using a different JavaScript library, configure our own JavaScript application. Our compiled JavaScript will typically be placed in the public/js directory.</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>The app.js file loads the resources/assets/js/bootstrap.js file which bootstraps and configures Vue, Axios, jQuery, and all other JavaScript dependencies. If we have additional dependencies to configure, we can do it in this file.</p>
  </div>

  <h3>Writing Vue Components</h3>

  <p>By default, fresh Laravel applications contain an Example.vue Vue component located in the resources/assets/js/components directory. The Example.vue file is an example of a 'single file component' which defines its JavaScript and HTML template in the same file. Single file components provide a convenient approach to building JavaScript driven applications. The example component is registered in the app.js file:</p>

  <pre><code class="language-php">
    Vue.component('example', require('./components/Example.vue'));
  </code></pre>

  <p>To use the component in the application, we can simply drop it into one of our HTML templates. For example, after running the make::auth Artisan command to scaffold the application's authentication and registration screens, we can drop the component into the home.blade.php Blade template:</p>

  <pre><code class="language-php">
    &#64extends('layouts.app')

    &#64section('content')
        <example></example>
    &#64endsection
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>We should run the 'npm run dev' command each time we change a Vue component. We can also run the 'npm run watch' command which will automatically recompile the components each time they are modified.</p>
  </div>

  <h3>Using React</h3>

  <p>On any fresh Laravel application, we can easily swap the Vue scaffolding with React scaffolding using the "preset" command with the "react" option:</p>

  <pre><code class="language-php">
    php artisan preset react
  </code></pre>

  <p>This single command will remove the Vue scaffolding and replace it with React scaffolding, including an example component.</p>

@endsection
