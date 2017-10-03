@extends('master')

@section('content')
  <h1>Authorization</h1>

  <h2>Introduction</h2>

  <p>Laravel provides a simple way to authorize user actions against a given resource. There are two primary ways of authorizing actions: gates and policies.</p>

  <p>Gates and policies are similar to routes and controllers. Gates provide a simple, closure based approach to authorization, while policies, like controllers, group their logic around a particular model or resource.</p>

  <h2>Gates</h2>

  <h3>Writing Gates</h3>

  <p>Gates are closures that determine if a user is authorized to perform a certain action and are typically defined in the App\Providers\AuthServiceProvider class using the Gate facade. Gates always receive a user instance as their first argument, and can optionally receive additional arguments such as a relevant Eloquent model:</p>

  <pre><code class="language-php">
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('update-post', function ($user, $post) {
            return $user->id == $post->user_id;
        });
    }
  </code></pre>

  <p>Gates can also be defined using a Class@method style callback string, like controllers:</p>

  <pre><code class="language-php">
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('update-post', 'PostPolicy@update');
    }
  </code></pre>

  <h4>Resource Gates</h4>

  <p>We can also define multiple Gate abilities at once using the resource() method:</p>

  <pre><code class="language-php">
    Gate::resource('posts', 'PostPolicy');
  </code></pre>

  <p>This is identical to manually defining the following Gate definitions:</p>

  <pre><code class="language-php">
    Gate::define('posts.view', 'PostPolicy@view');
    Gate::define('posts.create', 'PostPolicy@create');
    Gate::define('posts.update', 'PostPolicy@update');
    Gate::define('posts.delete', 'PostPolicy@delete');
  </code></pre>

  <p>By default, the view, create, update and delete abilities will be defined. We can override or add to the default abilities by passing an array as a third argument to the resource() method. The keys of the array define the names of the abilities, while the values define the method names. In the following example, two new Gate definitions are defined:</p>

  <pre><code class="language-php">
    Gate::resource('posts', 'PostPolicy', [
        'image' => 'updateImage',
        'photo' => 'updatePhoto',
    ]);
  </code></pre>

  <h3>Authorizing Actions</h3>

  <p>To authorize an action using gates, we should use the allows() or denies() method. Note that we are not required to pass the currently authenticated user to these methods. Laravel automatically takes care of passing the user into the gate closure:</p>

  <pre><code class="language-php">
    if (Gate::allows('update-post', $post)) {
        // The current user can update the post...
    }

    if (Gate::denies('update-post', $post)) {
        // The current user can't update the post...
    }
  </code></pre>

  <p>To determine if a particular user is authorized to perform an action, we can use the forUser() method on the Gate facade:</p>

  <pre><code class="language-php">
    if (Gate::forUser($user)->allows('update-post', $post)) {
        // The user can update the post...
    }

    if (Gate::forUser($user)->denies('update-post', $post)) {
        // The user can't update the post...
    }
  </code></pre>

  <h2>Creating Policies</h2>

  <h3>Generating Policies</h3>

  <p>Policies are classes that organize authorization logic around a particular model or resource. For example, if creating a blog application, we could have a Post model and a corresponding PostPolicy to authorize user actions such as creating or updating posts.</p>

  <p>We can generate a policy using the make:policy artisan command. The generated policy will be placed in the app/Policies directory:</p>

  <pre><code class="language-php">
    php artisan make:policy PostPolicy
  </code></pre>

  <p>The make:policy command will generate an empty policy class. To create a policy with the basic CRUD policy methods already included in the class, we can specify a --model when executing the command:</p>

  <pre><code class="language-php">
    php artisan make:policy PostPolicy --model=Post
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>All policies are resolved via the Laravel service container, allowing us to type-hint any needed dependencies in the policy's constructor to have them automatically injected.</p>
  </div>

  <h3>Registering Policies</h3>

  <p>Once the policy exists, it needs to be registered. The AuthServiceProvider included with Laravel contains a policies property that maps the Eloquent models to their cooresponding policies. Registering a policy will tell Laravel which policy to use when authorizing actions against a given model.</p>

  <pre><code class="language-php">
    namespace App\Providers;

    use App\Post;
    use App\Policies\PostPolicy;
    use Illuminate\Support\Facades\Gate;
    use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

    class AuthServiceProvider extends ServiceProvider
    {
        protected $policies = [
            Post::class => PostPolicy::class,
        ];

        public function boot()
        {
            $this->registerPolicies();

            //
        }
    }
  </code></pre>

  <h2>Writing Policies</h2>

  <h3>Policy Methods</h3>

  <p>Once the policy has been registered, we can add methods for each action it authorizes. In the next example, we'll define an update() method on our PostPolicy which determines if a given User can update a given Post instance.</p>

  <p>The update() method will receive a User and a Post instance as its arguments, and should return true or false indicating whether a user is authorized to update the given Post. Here, we'll verify that the user's id matches the user_id on the post:</p>

  <pre><code class="language-php">
    namespace App\Policies;

    use App\User;
    use App\Post;

    class PostPolicy
    {
        public function update(User $user, Post $post)
        {
            return $user->id === $post->user_id;
        }
    }
  </code></pre>

  <p>We can continue defining additional methods on the policy as needed for the various actions it authorizes. For example, we can define view() or delete() methods to authorize various Post actions.</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>When using the --model option on the Artisan make:policy command, the generated policy will already contain methods for the view, create, update and delete actions.</p>
  </div>

  <h3>Methods Without Models</h3>

  <p>Some policy methods only receive the currently authenticated user and not an instance of the model they authorize. This is most common when authorizing create actions. For example, when creating a blog, we may want to check if a user is authorized to create any posts at all.</p>

  <p>When defining policy methods that do not receive a model instance, such as the create method, we should define the method as only expecting the authenticated user:</p>

  <pre><code class="language-php">
    public function create(User $user)
    {
        //
    }
  </code></pre>

  <h3>Policy Filters</h3>

  <p>For certain users, we may want to authorize all actions within a given policy. To do this, we can define a before() method on the policy. The before() method will be executed before any other methods on the policy, giving us an opportunity to authorize the action before the intended policy method is actually called. This feature is most commonly used for authorizing admins to perform any action:</p>

  <pre><code class="language-php">
    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }
  </code></pre>

  <p>To deny all actions for a user, we should return false from the before() method. If null is returned, the authorization will fall through to the policy method.</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>The before() method of a policy class will not be called if the class doesn't contain a method with a name matching the name of the ability being checked.</p>
  </div>

  <h2>Authorizing Actions Using Policies</h2>

  <h3>Via the User Model</h3>

  <p>The User model that is included with Laravel includes two helpful methods for authorizing actions: can() and cant(). The can() method receives the action we want to authorize and the relevant model. In the following example, we will determine if a user is authorized to update a given Post model:</p>

  <pre><code class="language-php">
    if ($user->can('update', $post)) {
        //
    }
  </code></pre>

  <p>If a policy is registered for the given model, the can() method will automatically call the appropriate policy and return the boolean result. If no policy is registered for the given model, the can() method will attempt to call the closure based gate matching the given action name.</p>

  <h4>Actions that Don't Require Models</h4>

  <p>Some actions, like create, don't require a model instance. In these situations we can pass a class name to the can() method. The class name will be used to determine which policy to use when authorizing the action:</p>

  <pre><code class="language-php">
    use App\Post;

    if ($user->can('create', Post::class)) {
        // Executes the "create" method on the relevant policy...
    }
  </code></pre>

  <h3>Via Middleware</h3>

  <p>Laravel includes a middleware that can authorize actions before the incoming request even reaches our routes or controllers. By default, the Illuminate\Auth\Middleware\Authorize middleware is assigned the 'can' key in our App\Http\Kernel class. In the following example, we'll use the 'can' middleware to authorize that a user can update a blog post:</p>

  <pre><code class="language-php">
    use App\Post;

    Route::put('/post/{post}', function (Post $post) {
        // The current user may update the post...
    })->middleware('can:update,post');
  </code></pre>

  <p>In the above example, we're passing the 'can' middleware two arguments: the name of the action and the route parameter. Since we are using implicy route model binding, a Post model will be passed to the policy method. If the user is not authorized to perform the given action, a HTTP response, with a 403 status code, will be generated by the middleware.</p>

  <h4>Actions that Don't Require Models</h4>

  <p>Again, some actions, like create, may not require a model instance. In these situations, we can pass a class name to the middleware. This class name will be used to determine which policy to use when authorizing the action:</p>

  <pre><code class="language-php">
    Route::post('/post', function () {
        // The current user may create posts...
    })->middleware('can:create,App\Post');
  </code></pre>

  <h3>Via Controller Helpers</h3>

  <p>Laravel also provides a helpful authorize() method to any of our controllers which extend the App\Http\Controllers\Controller base class. This method accepts the name of the action to authorize along with the relevant model. If the action is not authorized, the authorize() method will throw an Illuminate\Auth\Access\AuthorizationException, which will be converted to an HTTP response with a 403 status code:</p>

  <pre><code class="language-php">
    namespace App\Http\Controllers;

    use App\Post;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;

    class PostController extends Controller
    {
        public function update(Request $request, Post $post)
        {
            $this->authorize('update', $post);

            // The current user can update the blog post...
        }
    }
  </code></pre>

  <h4>Actions that Don't Require Models</h4>

  <p>As previously discussed, some actions, like create, don't require a model instance. In these situations, we can pass a class name to the authorize() method. This class name will be used to determine which policy to use when authorizing the action:</p>

  <pre><code class="language-php">
    public function create(Request $request)
    {
        $this->authorize('create', Post::class);

        // The current user can create blog posts...
    }
  </code></pre>

  <h3>Via Blade Templates</h3>

  <p>When writing Blade templates, we may want to display a portion of the page only if a user is authorized to perform a certain action. In these situations, we can use the &#64can and &#64cannot family of directives:</p>

  <pre><code class="language-php">
    &#64can('update', $post)
        <!-- The Current User Can Update The Post -->
    &#64elsecan('create', $post)
        <!-- The Current User Can Create New Post -->
    &#64endcan

    &#64cannot('update', $post)
        <!-- The Current User Can't Update The Post -->
    &#64elsecannot('create', $post)
        <!-- The Current User Can't Create New Post -->
    &#64endcannot
  </code></pre>

  <p>These directive are convenient shortcuts for writing &#64if and&#64unless statements. The &#64can and &#64cannot statements above respectively translate to the following:</p>

  <pre><code class="language-php">
    &#64if (Auth::user()->can('update', $post))
        <!-- The Current User Can Update The Post -->
    &#64endif

    &#64unless (Auth::user()->can('update', $post))
        <!-- The Current User Can't Update The Post -->
    &#64endunless
  </code></pre>

  <h4>Actions that Don't Require Models</h4>

  <p>Like most of the other authorization methods, we can pass a class name to the &#64can and &#64cannot directives if the action doesn't require a model instance:</p>

  <pre><code class="language-php">
    &#64can('create', App\Post::class)
        <!-- The Current User Can Create Posts -->
    &#64endcan

    &#64cannot('create', App\Post::class)
        <!-- The Current User Can't Create Posts -->
    &#64endcannot
  </code></pre>

@endsection
