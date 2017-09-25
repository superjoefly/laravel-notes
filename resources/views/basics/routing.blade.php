@extends('master')
@section('content')

    <h1>Routing</h1>

      <h2>Basic Routing</h2>

      <p>Most basic routes accept a URI and a Closure:
        <pre><code class="language-php">
          // web.php
          Route::get('/foo', function() {
            return 'Hello World!';
          });
        </code></pre>
      </p>
      <p>We can call a method in a controller using a route:
        <pre><code class="language-php">
          // Create UsersController
          php artisan make:controller UsersController

          // Add index method to controller
          $user = 'Joey';
          public function index() {
            return $user;
          }

          Route::get('/user', 'UsersController@index');
        </code></pre>
      </p>

      <h3>Router Methods</h3>
      <p>Available router methods include:
        <pre><code class="language-php">
          Route::get($uri, $callback);
          Route::post($uri, $callback);
          Route::put($uri, $callback);
          Route::patch($uri, $callback);
          Route::delete($uri, $callback);
          Route::options($uri, $callback);
        </code></pre>
      </p>
      <p>Register route that responds to multiple HTTP verbs:
        <pre><code class="language-php">
          Route::match(['get', 'post'], $uri, $callback);
        </code></pre>
      </p>
      <p>Register route that responds to ANY HTTP verb:
        <pre><code class="language-php">
          Route::any($uri, $callback);
        </code></pre>
      </p>

        <h3>CSRF Protection</h3>
      <p>Any HTML forms pointing to POST, PUT or DELETE routes that are defined inside web.php should include a CSRF token field:
        <pre><code class="language-php">
          { {csrf_field()} }
        </code></pre>
      </p>

      <h2>Redirect Routes</h2>
      <p>We can use the Route::redirect method to redirect routes:
        <pre><code class="language-php">
          Route::redirect('/here', '/there', 301)
        </code></pre>
      </p>

      <h2>View Routes</h2>
      <p>If the route only returns a view, we can use the Route::view method:
        <pre><code class="language-php">
          Route::view('/welcome', 'welcome');

          // Pass array of data to the view:
          Route::view('/welcome', 'welcome', ['name' => 'Joey']);
        </code></pre>
      </p>
      <h2>Route Parameters (required)</h2>
      <p>We can define route parameters in order to 'capture' segments of the URI within the route:
        <pre><code class="language-php">
          Route::get('/user/{id}', function($id) {
            return 'User ' . $id;
          })
        </code></pre>
      </p>
      <p>We can define as many route parameters as required by the route:
        <pre><code class="language-php">
          Route::get('/posts/{post}/comments/{comment}', function($postId, $commentId) {
            return 'Post ' . $postId . 'Comment ' . $commentId;
          });
        </code></pre>
      </p>

      <h2>Route Parameters (optional)</h2>
      <p>We cand define OPTIONAL parameters by placing a '?' after the paramter name...be sure to give the corresponding variable a default value:
        <pre><code class="language-php">
          Route::get('user/{name?}', function($name = null) {
            return $name;
          })

          Route::get('user/{name?}', function($name = "John") {
            return $name;
          })
        </code></pre>
      </p>

      <h2>Regular Expression Constraints</h2>
      <p>We can use the where() method to constrain the format of the route paramter:
        <pre><code class="language-php">
          Route::get('user/{name}', function($name) {
            //...
          })->where('name', '[A-Za-z]+');

          Route::get('user/{id}', function($id) {
            //...
          })->where('id', [0-9]+);

          Route::get('user/{id}/{name}', function($id, $name) {
            //...
          })->where(['id'=>'[0-9]+', 'name'=>'[a-z]+']);
        </code></pre>
      </p>

      <h3>Global Constraints</h3>
      <p>We can use the pattern() method to always constrain a given regular expression. The pattern will be defined in the boot() method of the RouteServiceProvider:
        <pre><code class="language-php">
          public function boot() {
            Route::pattern('id', '[0-9]+');
            parent::boot();
          }

          // The pattern will be automatically applied to all routes using that parameter name:

          Route::get('user/{id}', function($id) {
            // Only executed if {id} is numeric
          });
        </code></pre>
      </p>

      <h2>Named Routes</h2>
      <p>We can use named routes by chaining the name() method onto the route definition:
        <pre><code class="language-php">
          Route::get('user/profile', function() {
            //...
          })->name('profile');
        </code></pre>
      </p>
      <p>We can also specify route names for controller actions:
        <pre><code class="language-php">
          Route::get('user/profile', 'UserController@showProfile')->name('profile');
        </code></pre>
      </p>

      <h3>Generating URLs</h3>
      <p>We can generate URLs to named routes using the global route() function:
        <pre><code class="language-php">
          // Generate URL
          $url = route('profile');

          // Generate Redirect
          return redirect()->route('profile');
        </code></pre>
      </p>
      <p>If the named route defines parameters, we can pass them as the second argument:
        <pre><code class="language-php">
          Route::get('user/{id}/profile', function($id) {
            //...
          })->name('profile');

          $url = route('profile', ['id' => 1]);
        </code></pre>
      </p>

      <h3>Inspecting the Current Route</h3>
      <p>We can check the current route name from a route middleware:
        <pre><code class="language-php">
          See docs...
        </code></pre>
      </p>

      <h2>Route Groups</h2>
      <p>We can share route attributes, such as middleware or namespaces, accross a large number of routes. Shared attributes are specified in an array format as the first parameter to the Route::group method.</p>

      <h3>Middleware</h3>
      <p>To assign middleware to all routes within a group, use the middleware() method before defining the group:
        <pre><code class="language-php">
          Route::middleware(['first', 'second'])->group(function() {
            Route::get('/', function() {
              // Uses first and second middleware
            });

            Route::get('user/profile', function() {
              // Uses first and second middleware
            });
          });
        </code></pre>
      </p>

      <h3>Namespaces</h3>
      <p>We can assign namespaces to a group of controllers using the namespace method:
        <pre><code class="language-php">
          Route::namespace('Admin')->group(function() {
            // Controllers within 'App\Http\Controllers|Admin' namespace
          })
        </code></pre>
      </p>
      <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
        <p>By default, the RouteServiceProvider includes your route files within a namespace group, allowing you to register controller routes without specifying the full App\Http\Controllers namespace prefix. So, you only need to specify the portion of the namespace that comes after the base  App\Http\Controllers namespace.</p>
      </div>

      <h3>Sub-Domain Routing</h3>
      <p>Route groups may also be used to handle sub-domain routing. The sub-domain may be specified by calling the domain method before defining the group:
        <pre><code class="language-php">
          Route::domain('{account.myapp.com}')->group(function() {
            Route::get('user/{id}', function($account, $id) {
              //...
            })
          })
        </code></pre>
      </p>

      <h3>Route Prefixes</h3>
      <p>The prefix() method may be used to prefix each route in a group with a given URI:
        <pre><code class="language-php">
          Route::prefix('admin')->group(function() {
            Route::get('users', function() {
              // Matches the '/admin/users' url
            })
          })
        </code></pre>
      </p>

      <h3>Route Model Binding</h3>
      <p>With route-model-binding, we can automatically inject a model instance directly into our routes. For example, instead of injecting a user's ID, we can inject the entire User model instance that matches the given ID.</p>

      <h3>Implicit Binding</h3>
      <p>Laravel automatically resolves Eloquent models defined in routes or controller actions whos type-hinted variable names match a route segment name:
        <pre><code class="language-php">
          Route::get('api/users/{user}', function(App\User $user) {
            return $user->email;
          });
        </code></pre>
        Since the $user variable is type-hinted as the App\User Eloquent model and the variable name matches the {user} URI segment, Laravel will automatically inject the model instance that has an ID matching the corresponding value from the request URI. If a matching model instance is not found in the database, a 404 HTTP response will automatically be generated.
      </p>

      <h3>Customizing the Key Name</h3>
      <p>If you would like route model binding to use a database column rather than id when retrieving a given model class, we can override the getRouteKeyName method on the Eloquent model:
        <pre><code class="language-php">
          public function getRouteKeyName() {
            return 'slug';
          }
        </code></pre>
      </p>

      <h3>Explicit Binding</h3>
      <p>To register an explicit binding, use the router's model() method to specify the class for a given paramter. Explicit model bindings should be defined in the boot() method of the RouteServiceProvider class:
        <pre><code class="language-php">
          public function boot() {
            parent::boot();

            Route::model('user', App\User::class);
          }
        </code></pre>
        Next define a route that contains a {user} parameter:
        <pre><code class="language-php">
          Route::get('profile/{user}', function(App\User $user) {
            //...
          });
        </code></pre>
        Since we have bound all {user} parameters to the App\User model, a User instance will be injected into the route.
        For example, a request to profile/1 will inject the User instance from the database which has an ID of 1.
      </p>

      <h3>Customizing Resolution Logic</h3>
      <p>To use our own resolution logic, we can use the Route::bind() method. The closure we pass to the bind method will receive the value of the URI segment and should return the instance of the class that should be injected into the route:
        <pre><code class="language-php">
          public function boot() {
            parent::boot();

            Route::bind('user', function($value) {
              return App\User::where('name', $value)->first();
            });
          }
        </code></pre>
      </p>

      <h2>Form Method Spoofing</h2>
      <p>HTML forms do not support PUT, PATCH or DELETE actions. When defining PUT, PATCH or DELETE routes that are called from an HTML form, we need to add a hidden _method field to the form. The value sent with the method field will be used as the HTTP request method:
        <pre><code class="language-php">
          form action="/foo/bar" method="POST">
              input type="hidden" name="_method" value="PUT">
              input type="hidden" name="_token" value="{ { csrf_token() } }">
          /form>

          // We can use the method_filed helper to generate the _method input:

          { { method_field('PUT')} }
        </code></pre>
      </p>
      <h2>Accessing The Current Route</h2>
      <p>We can use the current(), currentRouteName(), and currentRouteAction() methods on the Route facade to access information about the route handling the incoming request:
        <pre><code class="language-php">
          $route = Route::current();
          $name = Route::currentRouteName();
          $action = Route::currentRouteAction();
        </code></pre>
      </p>

@endsection
