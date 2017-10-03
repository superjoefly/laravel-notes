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

  <p></p>


@endsection
