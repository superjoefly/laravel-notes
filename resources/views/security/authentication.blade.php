@extends('master')

@section('content')
  <h1>Authentication</h1>

  <h2>Introduction</h2>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Rapid Authentication Setup: run <b>php artisan make:auth</b> and <b>php artisan migrate</b> in a fresh Laravel application. These two commands will set up the scaffolding for the entire authentication system.</p>
  </div>

  <pre><code class="language-php">
    php artisan make:auth

    php artisan migrate
  </code></pre>

  <p>Laravel makes implementing authentication very simple. The authentication configuration file is located at config/auth.php. This file contains several well documented options for tweaking the behavior of the authentication services.</p>

  <p>Laravel's authentication facilities are made up of "guards" and "providers". Guards define how users are authenticated for each request. For example, Laravel ships with a "session" guard which maintains state using session storage and cookies.</p>

  <p>Providers define how users are retrieved from the persistent storage. Laravel ships with support for retrieving users using Eloquent and the database query builder. However, we can define additional providers as needed by the application.</p>

  <p>Most applications will never need to modify the default authentication configuration.</p>

  <h3>Database Considerations</h3>

  <p>Laravel includes an App\User Eloquent model in the app directory. This model can be used with the default Eloquent authentication driver. If the application is not using Eloquent, we can use the database authentication driver which uses the Laravel query builder.</p>

  <p>When building the database schema for the App\User model, make sure the password column is at least 60 characters in length. Maintaining the default string column length of 255 characters would be a good choice.</p>

  <p>We should also verify that the users (or equivalent) table contains a nullable, string "remember_tokens" column of 100 characters. This column will be used to store a token for users that select the "remember me" option when logging into the application.</p>

  <h2>Authentication Quickstart</h2>

  <p>Laravel ships with several pre-built authentication controllers that are located in the App\Http\Controllers\Auth namespace. The RegisterController handles new user registration, the LoginController handles authentication, the ForgotPasswordController handles e-mailing links for resetting passwords, and the ResetPasswordController contains the logic to reset passwords. Each of these controllers uses a trait to include their necessary methods. For most applications, we will not need to modify these controllers at all.</p>

  <h3>Routing</h3>

  <p>Laravel provides a quick way to scaffold all the routes and views needed for authentication by using the following the command:</p>

  <pre><code class="language-php">
    php artisan make:auth
  </code></pre>

  <p>This command should be used on a fresh installation and will install a layout view, registration view, login views, as well as routes for all authentication end-points. A HomeController will also be generated to handle post-login requests to the application's dashboard.</p>

  <h3>Views</h3>

  <p>All views generated with the "php artisan make:auth" command will be placed in the resources/views/auth directory. This command will also create a resources/views/layouts directory containing a base layout for the application. All of these views use the Bootstrap CSS framework, be we can customize them however we want.</p>

  <h3>Authenticating</h3>

  <p>Once we have routes and views set up for the included authentication controllers, we can register and authenticate new users for the application. To do this we can simply access the application in a browser since the authentication controllers already contain the logic to authenticate existing users and store new users in the database.</p>

  <h4>Path Customization</h4>

  <p>When a user is successfully authenticated, they will be redirected to the /home URI. We can customize the post-authentication redirect location by defining a redirectTo property on the LoginController, RegisterController and ResetPasswordController:</p>

  <pre><code class="language-php">
    protected $redirectTo = '/';
  </code></pre>

  <p>In the case that the redirect path needs custom generation logic, we can use a redirectTo() method instead of a redirectTo property:</p>

  <pre><code class="language-php">
    protected function redirectTo()
    {
      return '/path';
    }
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>The redirectTo() method will take precedence over the redirectTo attribute</p>
  </div>

  <h4>Username Customization</h4>

  <p>By default, Laravel uses the email field for authentication. To customize this, we can define a username() method on the LoginController:</p>

  <pre><code class="language-php">
    public function username()
    {
        return 'username';
    }
  </code></pre>

  <h4>Guard Customization</h4>

  <p>We can also customize the "guard" that is used to authenticate and register users. To get started, define a guard() method on the LoginController, RegisterController, and ResetPasswordController. This method should return a guard instance:</p>

  <pre><code class="language-php">
    use Illuminate\Support\Facades\Auth;

    protected function guard()
    {
        return Auth::guard('guard-name');
    }
  </code></pre>

  <h4>Validation Storage Customization</h4>

  <p>To modify the form fields required when a new user registers with the application, or to customize how new users are stored in the database, we can modify the RegisterController class. This class is responsible for validating and creating new users for the application.</p>

  <p>The validator() method of the RegisterController contains the validation rules for new users of the application. This method can be modified to the needs of the application.</p>

  <p>The create() method of the RegisterController is responsible for creating new Ap\Use records in the database using the Eloquent ORM. We can modify this method according to the needs of the database.</p>

  <h3>Retrieving the Authenticated User</h3>

  <p>We can access the authenticated user via the Auth facade:</p>

  <pre><code class="language-php">
    use Illuminate\Support\Facades\Auth;

    // Get the currently authenticated user...
    $user = Auth::user();

    // Get the currently authenticated user's ID...
    $id = Auth::id();
  </code></pre>

  <p>Alternatively, once a user is authenticated, we can access the authenticated user via an Illuminate\Http\Request instance. Remember, type-hinted classes will automatically be injected into the controller methods:</p>

  <pre><code class="language-php">

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    class ProfileController extends Controller
    {
        // Update the user's profile
        public function update(Request $request)
        {
            // $request->user() returns an instance of the authenticated user...
        }
    }
  </code></pre>

  <h4>Determining if the Current User is Authenticated</h4>

  <p>To determine if the user is already logged into the application, we can use the check() method on the Auth facade. This will return true if the user is authenticated:</p>

  <pre><code class="language-php">
    use Illuminate\Support\Facades\Auth;

    if (Auth::check()) {
        // The user is logged in...
    }
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Though it is possible to use the check() method to determine if the user is authenticated, we will typically use a middleware to protect routes.</p>
  </div>

  <h3>Protecting Routes</h3>

  <p>Route middleware can be used to only allow authenticated users to access a given route. Laravel ships with an auth middleware defined at Illuminate\Auth\Middleware\Authenticate. Since this middleware is already defined in the HTTP kernel, all we need to do is attach the middleware to a route definition:</p>

  <pre><code class="language-php">
    Route::get('profile', function() {
      // Only authenticated users may enter
    })->middleware('auth');
  </code></pre>

  <p>When using a crontroller, we can call the middleware() method from the controller's constructor instead of attaching it in the route definition directly:</p>

  <pre><code class="language-php">
    public function __construct()
    {
        $this->middleware('auth');
    }
  </code></pre>

  <h4>Specifying a Guard</h4>

  <p>When attaching the auth middleware to a route, we can also specify which guard should be used to authenticate the user. The guard specified should correspond to one of the keys in the guards array of the auth.php config file:</p>

  <pre><code class="language-php">
    public function __construct()
    {
        $this->middleware('auth:api');
    }
  </code></pre>

  <h3>Login Throttling</h3>

  <p>When using Laravel's built-in loginController class, the Illuminate\Foundation\Auth\ThrottlesLogins trait will already be included in the controller. By default, the user will not be able to login for one minute if they fail to provide the correct credentials after several attempts. The throttling is unique to the user's username / e-mail address and their IP address.</p>

  <h2>Manually Authenticating Users</h2>

  <p></p>




@endsection
