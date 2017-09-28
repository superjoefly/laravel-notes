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

  <h2>Running Mix</h2>

  <p>Mix is a configuration layer on top of Webpack, so to run the Mix tasks, we can execute the NPM scripts included with the default Laravel package.json file:</p>

  <pre><code class="language-php">
    // Run all Mix tasks
    npm run dev

    // Run all Mix tasks and minify output
    npm run production
  </code></pre>

  <h4>Watching Assets for Changes</h4>

  <p>The "npm run watch" command will continue running in the terminal and watch all relevant files for changes. Webpack will then automatically recompile the assets when it detects changes:</p>

  <pre><code class="language-php">
    npm run watch
  </code></pre>

  <p>In certain environments, Webpack may not update when the files change. If this is the case, try running the following:</p>

  <pre><code class="language-php">
    npm run watch-poll
  </code></pre>

  <h2>Working with Stylesheets</h2>

  <p>The webpack.mix.js file is the entry point for all asset compilation. Mix tasks can be chained together to define exactly how assets should be compiled.</p>

  <h3>Less</h3>

  <p>The less() method may be used to compile Less into CSS. Following is an example of compiling a primary app.less file to public/css/app.css:</p>

  <pre><code class="language-php">
    mix.less('resources/assets/less/app.less', 'public/css');
  </code></pre>

  <p>Multiple calls to the less() method can be used to compile multiple files:</p>

  <pre><code class="language-php">
    mix.less('resources/assets/less/app.less', 'public/css')
   .less('resources/assets/less/admin.less', 'public/css');
  </code></pre>

  <p>To customize the file name of the compiled CSS, we can pass a full file-path as the second argument to the less() method:</p>

  <pre><code class="language-php">
    mix.less('resources/assets/less/app.less', 'public/stylesheets/styles.css');
  </code></pre>

  <p>To override the underlying Less plug-in option, we can pass an object as the third argument to mix.less():</p>

  <pre><code class="language-php">
    mix.less('resources/assets/less/app.less', 'public/css', {
        strictMath: true
    });
  </code></pre>

  <h3>Sass</h3>

  <p>We can use the sass() method to compile Sass into CSS:</p>

  <pre><code class="language-php">
    mix.sass('resources/assets/sass/app.scss', 'public/css');
  </code></pre>

  <p>We can compile multiple Sass files into their own respective CSS files and even customize the output directory of the resulting CSS:</p>

  <pre><code class="language-php">
    mix.sass('resources/assets/sass/app.sass', 'public/css')
   .sass('resources/assets/sass/admin.sass', 'public/css/admin');
  </code></pre>

  <p>Additional Node-Sass plug-in options can be provided as the third argument:</p>

  <pre><code class="language-php">
    mix.sass('resources/assets/sass/app.sass', 'public/css', {
        precision: 5
    });
  </code></pre>

  <h3>Stylus</h3>

  <p>Similar to Less and Sass, the stylus() method allows us to compile Stylus into CSS:</p>

  <pre><code class="language-php">
    mix.stylus('resources/assets/stylus/app.styl', 'public/css');
  </code></pre>

  <p>We can install additional Stylus plug-ins by first installing the plug-in through NPM (npm install plug-in), and then requiring it in the call to mix.stylus():</p>

  <pre><code class="language-php">
    mix.stylus('resources/assets/stylus/app.styl', 'public/css', {
        use: [
            require('rupture')()
        ]
    });
  </code></pre>

  <h3>PostCSS</h3>

  <p>PostCSS, a powerful tool for transforming CSS, is included with Laravel Mix out of the box. By default, Mix leverages the popular Autoprefixer plug-in to automatically apply all necessary CSS3 vendor prefixes. We can also add any plug-ins that are appropriate for our application. To do this, we would first install the desired plug-in throug NPM, and then reference it in the webpack.mix.js file:</p>

  <pre><code class="language-php">
    mix.sass('resources/assets/sass/app.scss', 'public/css')
       .options({
            postCss: [
                require('postcss-css-variables')()
            ]
       });
  </code></pre>

  <h3>Plain CSS</h3>

  <p>To concatenate some plain CSS stylesheets into a single file, we can use the styles() method:</p>

  <pre><code class="language-php">
    mix.styles([
        'public/css/vendor/normalize.css',
        'public/css/vendor/videojs.css'
    ], 'public/css/all.css');
  </code></pre>

  <h3>URL Processing</h3>

  <p>For CSS compilation, Webpack will rewrite and optimize any url() calls within our stylesheets:</p>

  <pre><code class="language-php">
    example {
        background: url('../images/example.png');
    }
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Absolute paths for any given url() will be excluded from URL-rewriting. For example, url('/images/thing.png') or url('http://example.com/images/thing.png') won't be modified.</p>
  </div>

  <p>By default, Laravel Mix and Webpack will find example.png, copy it to the public/images folder, and then re-write the url() within the generated stylesheet. The compiled CSS will be:</p>

  <pre><code class="language-php">
    .example {
      background: url(/images/example.png?d41d8cd98f00b204e9800998ecf8427e);
    }
  </code></pre>

  <p>If we don't want our existing file structure to change, we can disable url() re-writing like this:</p>

  <pre><code class="language-php">
    mix.sass('resources/assets/app/app.scss', 'public/css')
       .options({
          processCssUrls: false
       });
  </code></pre>

  <p>With this addition to our webpack.mix.js file, Mix will no longer match any url() or copy assets to the public directory. In other words, the compiled CSS file look exactly the same way as we typed it:</p>

  <pre><code class="language-php">
    .example {
        background: url("../images/thing.png");
    }
  </code></pre>

  <h3>Source Maps</h3>

  <p>Source maps are disabled by default, but can be activated by calling the mix.sourceMaps() method in the webpack.mix.js file. This comes with a compile/performance cost, but extra debugging information will be provided to our browser's developer tools when compiling assets:</p>

  <pre><code class="language-php">
    mix.js('resources/assets/js/app.js', 'public/js')
       .sourceMaps();
  </code></pre>

  <h2>Working with JavaScript</h2>

  <p></p>
@endsection
