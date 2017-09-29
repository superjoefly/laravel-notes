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

    // Run all Mix tasks and minifies output
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

  <p>Mix provides several features to help with JavaScript files including compiling ECMAScript 2015, module bundling, minification and concatenation of plain JavaScript files:</p>

  <pre><code class="language-php">
    mix.js('resources/assets/js/app.js', 'public/js');
  </code></pre>

  <p>With the above code, we can take advantage of the following features:</p>

  <ul>
    <li>ES2015 syntax</li>
    <li>Modules</li>
    <li>Compilation of .vue files</li>
    <li>Minification for production environments</li>
  </ul>

  <h3>Vendor Extraction</h3>

  <p>Bundling all application-specific JavaScript with our vendor libraries can make long-term caching difficult. For example, a single update to the application code will force the browser to re-download all of the vendor libraries, even if they haven't changed.</p>

  <p>If making frequent updates to the application's JavaScript, it would be wise to extract all vendor libraries into their own files. This way, a change to the application code will not affect the caching of our vendor.js file. We can do this easily using Mix's extract() method:</p>

  <pre><code class="language-php">
    mix.js('resources/assets/js/app.js', 'public/js')
       .extract(['vue'])
  </code></pre>

  <p>The extract() method accepts an array of all libraries or modules we wish to extract into a vendor.js file. Using the above snippet as an example, Mix will generate the following files:</p>

  <ul>
    <li>public/js/manifest.js: the webpack manifest runtime</li>
    <li>public/js/vendor.js: the vendor libraries</li>
    <li>public/js/app.js: the application code</li>
  </ul>

  <p>To avoid JavaScript errors, be sure to load the files in the correct order:</p>

  <pre><code class="language-php">
    &lt;script src="/js/manifest.js"&gt;&lt;/script&gt;<br />
    &lt;script src="/js/vendor.js"&gt;&lt;/script&gt;<br />
    &lt;script src="/js/app.js"&gt;&lt;/script&gt;
  </code></pre>

  <h3>React</h3>

  <p>Mix can automatically install the Babel plug-ins necessary for React support. To get started, replace the mix.js() call with mix.react():</p>

  <pre><code class="language-php">
    mix.react('resources/assets/js/app.jsx', 'public/js');
  </code></pre>

  <p>Mix will download and include the appropriate babel-preset-react Babel plugin.</p>

  <h3>Vanilla JS</h3>

  <p>We can combine and minify any number of JavaScript files with the scripts() method:</p>

  <pre><code class="language-php">
    mix.scripts([
        'public/js/admin.js',
        'public/js/dashboard.js'
    ], 'public/js/all.js');
  </code></pre>

  <p>This option is useful for legacy projects where we don't require Webpack compilation for our JavaScript.</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>A slight variation of mix.scripts() is mix.babel(). The concatenated file will receive Babel compilation, which translates any ES2015 code to vanilla JavaScript that all browsers will understand.</p>
  </div>

  <h3>Custom Webpack Configuration</h3>

  <p>Laravel Mix references a pre-configured webpack.config.js file to get us up and running as quickly as possible. To modify this file we have two choices:</p>

  <h4>Merging Custom Configuration</h4>

  <p>We can use Mix's webpackConfig() method to merge any short Webpack configuration overrides. This does not require us to copy and maintain our own copy of the webpack.config.js file. The webpackConfig() method accepts an object, which will contain any Webpack spcific configuration that we want to apply:</p>

  <pre><code class="language-php">
    mix.webpackConfig({
        resolve: {
            modules: [
                path.resolve(__dirname, 'vendor/laravel/spark/resources/assets/js')
            ]
        }
    });
  </code></pre>

  <h4>Custom Configuration Files</h4>

  <p>To completely customize the Webpack configuration, we can copy the node_modules/laravel-mix/setup/webpack.config.js file to our projects root directory. Next, we have to point all of the --config references in our package.json file to the newly copied config file. If we chose this approach, any future upstream updates to Mix's webpack.config.js must be manually merged into the customized file.</p>

  <h2>Copying Files and Directories</h2>

  <p>The copy() method can be used to copy files and directories to new locations. This is useful when a particular asset if the node_modules folder needs to be relocated to the public folder:</p>

  <pre><code class="language-php">
    mix.copy('node_modules/foo/bar.css', 'public/css/bar.css');
  </code></pre>

  <p>When copying a directory, the copy() method will flatten the directories structure. To maintain the directory's original structure, we can use the copyDirectory() method:</p>

  <pre><code class="language-php">
    mix.copyDirectory('assets/img', 'public/img');
  </code></pre>

  <h2>Versioning/Cache Busting</h2>

  <p>Many developers suffix their compiled assets with a timestamp or unique token to force browsers to load the fresh assets instead of serving stale copies of the code. Mix can handle this for us using the version() method.</p>

  <p>The version() method will automatically append a unique hash to the filenames of all compiled files, allowing for more convenient cache busting:</p>

  <pre><code class="language-php">
    mix.js('resources/assets/js/app.js', 'public/js')
   .version();
  </code></pre>

  <p>After generating the versioned file, we won't know the exact file name. We can use Laravel's global mix() function within our views to load the appropriately hashed asset. The mix() function will automatically determine the current name of the hashed file:</p>

  <pre><code class="language-php">
    &lt;link rel="stylesheet" href="{ { mix('/css/app.css') } }"&gt;
  </code></pre>

  <p>Because versioned files are usually unecessary in development, we can instruct the versioning process to only run during 'npm run production':</p>

  <pre><code class="language-php">
    mix.js('resources/assets/js/app.js', 'public/js');

    if (mix.inProduction()) {
        mix.version();
    }
  </code></pre>

  <h2>Browsersync Reloading</h2>

  <p>Browsersync can automatically monitor files for changes, and inject the changes into the browser without requiring a manual refresh. We can enable this support by calling the mix.browserSync() method:</p>

  <pre><code class="language-php">
    mix.browserSync('my-domain.dev');

    // Or...

    // https://browsersync.io/docs/options
    mix.browserSync({
        proxy: 'my-domain.dev'
    });
  </code></pre>

  <h2>Environment Variables</h2>

  <p>We can inject environment variables into Mix by prefixing a key in the .env file with MIX_:</p>

  <pre><code class="language-php">
    MIX_SENTRY_DSN_PUBLIC=http://example.com
  </code></pre>

  <p>After the variable has been defined in the .env file, we can access via the process.env object. If the value changes while running a watch task, we will need to restart the task:</p>

  <pre><code class="language-php">
    process.env.MIX_SENTRY_DSN_PUBLIC
  </code></pre>

  <h2>Notifications</h2>

  <p>When available, Mix will automatically display OS notifications for each bundle. This will give us instant feedback as to whether the compilation was successfull or not. To disable the notifications, we can deactivate using the disableNotifications() method:</p>

  <pre><code class="language-php">
    mix.disableNotifications();
  </code></pre>

@endsection
