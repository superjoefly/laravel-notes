@extends('master')

@section('content')

      <h1>Middleware</h1>

      <h2>Intro</h2>
      <p>Middleware provide a convenient mechanism for filtering HTTP requests entering the application. For example, the auth-middleware verifies that the user is authenticated and redirects the user to a login screen if they are not, or allows going further into the application if they are.</p>

      <p>Additional middleware can be written to perform a variety of tasks. For example, a CORS middleware might be responsible for adding proper headers to all responses leaving the application... and a logging middleware might log all incoming requests to the applications etc...</p>

      <p>There are several middleware included in the Laravel framework, including middleware for CSRF protection. All middleware are located in the app/Http/Middleware directory.</p>

      <h2>Defining Middleware</h2>
      <p>To create a new middleware, use the make:middleware Artisan command:</p>

      <pre><code class="language-php">
        php artisan make:middleware CheckAge
      </code></pre>

      <p>This command will place a new CheckAge class within the app/Http/Middleware directory. In this middleware, we only allow access to the route if the supplied age is greater than 200. Otherwise we redirect the users back to the home URI.</p>

      <pre><code class="language-php">
        if ($request->age <= 200) {
          return redirect('home');
        }

        return $next($request);
      </code></pre>

      <p>If the given age is less than or equal to 200, the middleware will return an HTTP redirect to the client. Otherwise, the request will be passed futher into the application. To pass the request deeper into the application, simply call the $next() callback with the $request.</p>

      <p>Middleware are basically a series of 'layers' HTTP requests must pass through before they hit the application. Each layer can examine the request and even reject it entirely.</p>

      <h3>Before and After Middleware</h3>

      <p>Whether a middleware runs before or after a request depends on the middleware itself. The following is an example of performing a redirect <b>before</b> and <b>after</b> a request:</p>

      <pre><code class="language-php">
        // Before
        {
          // perform action

          return $next($request)
        }

        // After
        {
          $response = $next($request)

          // perform action

          return $response
        }
      </code></pre>

      <h2>Registering Middleware</h2>

      <h3>Global Middleware</h3>

      <p>If you want a middleware to run durring every HTTP request to the application, simply list the middleware class in the $middleware property of the app/HTTP/Kernel.php class.</p>

      <h3>Assigning Middleware to Routes</h3>

      <p>If we want to assign the middleware to specific routes, we should first assign the middleware a key in the app/Http/Kernel.php file. By default, the $routeMiddleware property of this class contains entries for the middleware included with Laravel. To add our own we can simply append it to this list and assign it a key of our choosing. Example:</p>

      <pre><code class="language-php">
        // Within the App\Http\Kernel Class...

        protected $routeMiddleware = [
          'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
          ...,
          ...,
        ];
      </code></pre>

      <p>Once the middleware has been defined in the HTTP kernel, we can use the middleware() method to assign the middleware to a route:</p>

      <pre><code class="language-php">
        Route::get('admin/profile', function() {
          //...
        })->middleware('auth');
      </code></pre>

      <p>We can also assign multiple middleware to a route:</p>

      <pre><code class="language-php">
        Route::get('/', function() {
          //...
        })->middleware('first', 'second');
      </code></pre>

      <p>When assigning middleware, we can also pass the fully qualified class name:</p>

      <pre><code class="language-php">
        use App\Http\Middleware\Checkage;

        Route::get('admin/profile', function() {
          //...
        })->middleware(CheckAge::class);
      </code></pre>

      <aside>
        <h3>Steps to reproduce AgeCheck Middleware for Route</h3>
        <pre><code class="language-php">
          // Use php artisan to make a middleware:

          php artisan make:middleware CheckAge

          // Add the following to the CheckAge Middleware Class:
          // (age) refers to the age input field in the form

          if ($request->age <= 200) {
              return redirect('/home');
          }

          return $next($request);

          // Register the CheckAge Middleware in the
          // $routeMiddleware array of the App/Http/Kernel.php
          // file

          'age' => \App\Http\Middleware\CheckAge::class,

          // Use the middleware() method to attach the
          // middleware to the route:

          // If age <=200
          Route::view('/home', 'home');

          // If age >200
          Route::post('/agecheck', function () {
              return view('/agecheck');
          })->middleware('age');

          // Create home view for redirect if age is less than
          // 200

          // Create form to submit request (I did this in
          // welcome.view.php)

        </code></pre>
      </aside>

      <h3>Middleware Groups</h3>

      <p>We can group several middleware under a single key to make them easier to assign to routes using the $middlewareGroups property of the HTTP kernel (see middlewareGroups in App\Http\Kernel.php)</p>

      <p>Middleware groups can be assigned to routes and controller actions using the same syntax as individual middleware. Middleware groups simply make it more covenient to assign many middleware to a route at onece:</p>

      <pre><code class="language-php">
        Route::get('/', function() {
          //...
        })->middleware('web');

        Route::group(['middleware'=>['web']], function() {
          //...
        });
      </code></pre>

      <h2>Middleware Parameters</h2>

      <p>Middleware can also receive additional parameters. Example: if our app needs to verify that the authenticated user has a given 'role' before performing a given action, we could crate a CheckRole middleware that receives a role name as an additional argument.</p>

      <p>Additional middleware parameters will be passed to the middleware after the $next argument:</p>

      <pre><code class="language-php">
        // CheckRole.php

        public function handle($request, Closure $next, $role)
        {
            if (! $request->user()->hasRole($role)) {
                // redirect
            }
            // follow-through
            return $next($request);
        }
      </code></pre>

      <p>Middleware parameters may be specified when defining the route by separating the middleware name and parameters with a ':'. Multiple parameters should be delimited by commas:</p>

      <pre><code class="language-php">
        Route::put('post/{id}', function($id) {
          //...
        })->middleware('role:editor');
      </code></pre>

      <h2>Terminable Middleware</h2>

      <p>Sometimes a middleware may need to do some work after the HTTP response has been sent to the browser. If we define a terminate() method on the middleware, it will automatically be called after the response is sent to the browser:</p>

      <pre><code class="language-php">
        class StartSession
        {
            public function handle($request, Closure $next)
            {
                return $next($request);
            }

            public function terminate($request, $response)
            {
                // Store the session data...
            }
        }
      </code></pre>

      <p>The terminate() method should receive both the request and the response. Once you have defined a terminable middleware, it should be added to the list of route or global middleware in the app/Http/Kernel.php file.</p>

      <p>When calling the terminate() method on the middleware, Laravel will resolve a fresh instance of the middleware from the service container. If you woule like to use the same middleware instance, register the middleware with the container using the container's singleton method.</p>


@endsection
