@extends('master')

@section('content')
  <h1>Controllers</h1>

  <h2>Introduction</h2>

  <p>Instead of defining all request handling logic in closures in routes files, we can organize this behavior using Controller Classes. Controllers can group related handling logic into a single class. Controllers are stored in the app/Http/Controllers directory.</p>

  <h2>Basic Controllers</h2>

  <h3>Defining Controllers</h3>
  <p>Below is an example of a basic controller class. Note that the controller extends the base controller class included with Laravel. The base class contains convenience methods such as the middleware method, which may be used to attach middleware to controller actions:</p>

  <pre><code class="language-php">
    namespace App\Http\Controllers;

    use App\User;
    use App\Http\Controllers\Controller;

    class UsersController extends Controller
    {
        public function show($id)
        {
            return view('user.profile', ['user' => User::findOrFail($id)]);
        }
    }
  </code></pre>

  <p>We can define a route to this controller action like so:</p>

  <pre><code class="language-php">
    Route::get('user/{id}', 'UsersController@show');
  </code></pre>

  <p>When a request matches the specified route URI, the show method on the UsersController class will be executed. Alos, the route paramters will also be passed to the method.</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Controllers are not required to extend a base class, however, you will not have access to convenience features such as the middleware, validate and dispatch methods.</p>
  </div>

  <h3>Controllers and Namespaces</h3>

  <p>It is important to note that we did not need to specify the full controller namespace when defining the controller route. Since the RouteServiceProvider loads the route files within a route group that contains the namespace, we only specify the portion of the class name that comes after the App\Http\Controllers portion of the namespace.</p>

  <p>If we nest the controllers deeper into the App\Http\Controllers directory, simply use the class name relative to the App\Http\Controllers root namespace. So if the full controller class is App\Http\Controllers\Photos\AdminController, we should register routes to the controller like so:</p>

  <pre><code class="language-php">
    Route::get('foo', 'Photos\AdminController@method');
  </code></pre>

  <h2>Single Action Controllers</h2>
  <p>If you would like to define a controller that only handles a single action, you may place a single __invoke method on the controller:</p>

  <pre><code class="language-php">
    namespace App\Http\Controllers;

    use App\User;
    use App\Http\Controllers\Controller;

    class ShowProfile extends Controller
    {
        public function __invoke($id)
        {
            return view('user.profile', ['user' => User::findOrFail($id)]);
        }
    }
  </code></pre>

  <p>When registering routes for single action controllers, you do not need to specify a method:</p>

  <pre><code class="language-php">
    Route::get('user/{id}', 'ShowProfile');
  </code></pre>

  <h2>Controller Middleware</h2>

  <p>Middleware may be assigned to the controller's routes in the route files:</p>

  <pre><code class="language-php">
    Route::get('profile', 'UsersController@show')->middleware('auth');
  </code></pre>

  <p>However, it is more convenient to specify middleware within the controller's constructor. Using the middleware method from the controller's constructor, you may easily assign middleware to the controller's action. You may even restrict the middleware to only certain methods on the controller class:</p>

  <pre><code class="language-php">
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('log')->only('index');

        $this->middleware('subscribed')->except('store');
    }
  </code></pre>

  <p>Controller's also allow you to register middleware using a Closure. This provides a convenient way to define a middleware for a single controller without defining an entire middleware class:</p>

  <pre><code class="language-php">
    $this->middleware(function ($request, $next) {
      //...
      return $next($request);
    });
  </code></pre>

  <h2>Resource Controllers</h2>

  <p>Laravel resource routing assigns the typical 'CRUD' routes to a controller with a single line of code. For example, you may wish to create a controller that handles all HTTP requests for 'photos' stored by your application. Using the make:controller Artisan command, we can quickly create such a controller:</p>

  <pre><code class="language-php">
    php artisan make:controller PhotoController --resource
  </code></pre>

  <p>This command will generate a controller at app/Http/Controllers/PhotoController.php. The controller will contain a method for each of the available resource operations.</p>

  <p>Next, you may register a resourceful route to the controller:</p>

  <pre><code class="language-php">
    Route::resource('photos', 'PhotoController');
  </code></pre>

  <p>This single route declaration creates multiple routes to handle a variety of actions on the resource. The generated controller will already have methods stubbed for each of these actions, including notes informing you of the HTTP verbs and URIs they handle.</p>

  <h4>Actions Handled By Resource Controller</h4>

  <table class="w3-table-all">
    <tr>
      <th>Verb</th>
      <th>URI</th>
      <th>Action</th>
      <th>Route Name</th>
    </tr>
    <tr>
      <td>GET</td>
      <td>/photos</td>
      <td>index</td>
      <td>photos.index</td>
    </tr>
    <tr>
      <td>GET</td>
      <td>/photos/create</td>
      <td>create</td>
      <td>photos.create</td>
    </tr>
    <tr>
      <td>POST</td>
      <td>/photos</td>
      <td>store</td>
      <td>photos.store</td>
    </tr>
    <tr>
      <td>GET</td>
      <td>/photos/{photo}</td>
      <td>show</td>
      <td>photos.show</td>
    </tr>
    <tr>
      <td>GET</td>
      <td>/photos/{photo}/edit</td>
      <td>edit</td>
      <td>photos.edit</td>
    </tr>
    <tr>
      <td>PUT/PATCH</td>
      <td>/photos/{photo}</td>
      <td>update</td>
      <td>photos.update</td>
    </tr>
    <tr>
      <td>DELETE</td>
      <td>/photos/{photo}</td>
      <td>destroy</td>
      <td>photos.destroy</td>
    </tr>
  </table>

  <h4>Specifying the Resource Model</h4>

  <p>If you are using route-model-binding and you would like the resource controller's methods to type-hint a model instance, you may use the --model option when generating the controller:</p>

  <pre><code class="language-php">
    php artisan make:controller PhotoController --resource --model=Photo
  </code></pre>

  <h4>Spoofing Form Methods</h4>

  <p>Since HTML forms can't make PUT, PATCH, or DELETE requests, we will need to add a hidden _method field to spoof these HTTP verbs. The method_field helper can create this field for you:</p>

  <pre><code class="language-php">
    { { method_field('PUT') } }
  </code></pre>

  <h3>Partial Resource Routes</h3>

  <p>When declaring a resource route, you may specify a subset of actions the controller should handle instead of the full set of default actions:</p>

  <pre><code class="language-php">
    Route::resource('photo', 'PhotoController', ['only' => [
    'index', 'show'
    ]]);
  </code></pre>

  <pre><code class="language-php">
    Route::resource('photo', 'PhotoController', ['except' => [
    'create', 'store', 'update', 'destroy'
    ]]);
  </code></pre>

  <h3>Naming Resource Routes</h3>

  <p>By default, all resource controller actions have a route name, however, we can override these names by passing a names array with your options:</p>

  <pre><code class="language-php">
    Route::resource('photo', 'PhotoController', ['names' => [
    'create' => 'photo.build'
    ]]);
  </code></pre>

  <h3>Naming Resource Route Parameters</h3>

  <p>By default, Route::resource will create the route parameters for your resource routes based on the 'singularized' version of the resource name. We can override this on a per resource basis by passing parameters in the options array. The parameters array should be an associative array of resource names and parameter names:</p>

  <pre><code class="language-php">
    Route::resource('user', 'AdminUserController', ['parameters' => [
    'user' => 'admin_user'
    ]]);
  </code></pre>

  <p>The example above generates the following URIs for the resources 'show' route:</p>

  <pre><code class="language-php">
    /user/{admin_user}
  </code></pre>

  <h3>Localizing Resource URIs</h3>

  <p>By default, Route::resource will create resource URIs using English verbs. If you need to localize the create and edit action verbs, you may use the Route::resourceVerbs method. This may be done in the boot method of the AppServiceProvider:</p>

  <pre><code class="language-php">
    public function boot()
    {
      Route::resourceVerbs([
      'create' => 'crear',
      'edit' => 'editar',
      ]);
    }
  </code></pre>

  <p>Once the verbs have been customized, a resource route registration such as Route::resource('fotos', 'PhotoController') will produce the following URIs:</p>

  <pre><code class="language-php">
    /fotos/crear
    /fotos/{foto}/editar
  </code></pre>

  <h3>Supplementing Resource Controllers</h3>

  <p>If you need to add additional routes to a resource controller beyond the default set of resource routes, you should define those routes before your call to Route::resource; otherwise, the routes defined by the resource method may unintentionally take precedence over your supplemental routes:</p>

  <pre><code class="language-php">
    Route::get('photos/popular', 'PhotoController@method');

    Route::resource('photos', 'PhotoController');
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Remember to keep your controllers focused. If you find yourself routinely needing methods outside of the typical resource actions, consider splitting your controller into two, smaller controllers.</p>
  </div>

  <h2>Dependency Injection and Controllers</h2>

  <h4>Constructor Injection</h4>

  <p>The Laravel service container is used to resolve all Laravel controllers. As a result you are able to type-hint any dependencies your controller may need in its contructor. The resolved dependencies will automatically be resolved and injected into the controller instance:</p>

  <pre><code class="language-php">
    namespace App\Http\Controllers;

    use App\Repositories\UserRepository;

    class UserController extends Controller
    {
        public function __construct(UserRepository $users)
        {
            $this->users = $users;
        }
    }
  </code></pre>

  <p>Of course you may also type-hint any Laravel contract. If the container can resolve it, you can type-hint it. Depending on your application, injecting your dependencies into your controller may provide better testability.</p>

  <h4>Method Injection</h4>

  <p>In addition to contructor injection, you may also type-hint dependencies on your controller's methods. A common use-case for method injection is injecting the Illuminate\Http\Request instance into your controller's methods:</p>

  <pre><code class="language-php">
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    class UserController extends Controller
    {
        public function store(Request $request)
        {
            $name = $request->name;

            //
        }
    }
  </code></pre>

  <p>If your controller method is also expecting input from a route parameter, simply list your route arguments after your other dependencies. For example, if your route is defined like so:</p>

  <pre><code class="language-php">
    Route::post('user/{id}', 'UserController@update');
  </code></pre>

  <p>You may still type-hint the Illuminate\Http\Request and access your id parameter by defining your controller method as follows:</p>

  <pre><code class="language-php">
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    class UserController extends Controller
    {
        public function update(Request $request, $id)
        {
            //...
        }
    }
  </code></pre>

  <h2>Route Caching</h2>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Closure based routes cannot be cached. To use route-caching, you must convert any closure routes to controller classes.</p>
  </div>

  <p>If you application is exclusively using controller based routes, you should take advantage of Laravel's route cache. Using the route cache will drastically decrease the amount of time it takes to register all of your application's routes. In some cases, your route registration may even be up to 100x faster. To generate a route cache, just execute the route:cache Artisan command:</p>

  <pre><code class="language-php">
    php artisan route:cache
  </code></pre>

  <p>After running this command, you cached route file will be loaded on every request. Remember, if you add any new routes, you will need to generate a fresh route cache. Because of this, you should only run the route:cache command during your projects deployment.</p>

  <p>We can use the route:clear command to clear the route cache:</p>

  <pre><code class="language-php">
    php artisan route:clear
  </code></pre>

@endsection
