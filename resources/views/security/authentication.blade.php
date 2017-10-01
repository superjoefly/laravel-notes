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

  <p>We are not required to use the authentication controllers included with Laravel. If we remove these controllers, however, we will need to manage the authentication using the Laravel authentication classes directly. To do this, we will access the authentication services using the Auth facade, and then use the attempt() method:</p>

  <pre><code class="language-php">
    namespace App\Http\Controllers;

    use Illuminate\Support\Facades\Auth;

    class LoginController extends Controller
    {
        public function authenticate()
        {
            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                // Authentication passed...
                return redirect()->intended('dashboard');
            }
        }
    }
  </code></pre>

  <p>The attempt() method accepts an array of key/value pairs as its first argument. The values in the array will be used to find the user in the database table. In the example above, the user will be retrieved using the value of the email column. If the user is found, the hashed password stored in the database will be compared to the hashed password value passed to the method via the array. If the two hashed passwords match, an authenticated session will be started for the user.</p>

  <p>The attempt() method will return true if authentication was successful. Otherwise, false will be returned.</p>

  <p>The intended method on the redirector will redirect the user to the URL they were attempting to access before being intercepted by the authentication middleware. A fallback URI can be given to this method in case the intended destination is not available.</p>

  <h4>Specifying Additional Conditions</h4>

  <p>We can add extra conditions to the authentication query in addition to the user's email and password. For example, we can verify that the user is maked as "active":</p>

  <pre><code class="language-php">
    if (Auth::attempt(['email' => $email, 'password' => $password, 'active' => 1])) {
        // The user is active, not suspended, and exists.
    }
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>In the examples, email is not a required option, it is merely used as an example. Just make sure to use a column that corresponds to a "username" in the database.</p>
  </div>

  <h4>Accessing Spcific Guard Instances</h4>

  <p>We can specify which guard instance to utilize using the guard() method on the Auth facade. This allows us to manage authentication for separate parts of the application using entirely separate authenticatable models or user tables.</p>

  <p>The guard name passed to the guard() method should correspond to one of the guards configured in the auth.php config file:</p>

  <pre><code class="language-php">
    if (Auth::guard('admin')->attempt($credentials)) {
        //
    }
  </code></pre>

  <h4>Logging Out</h4>

  <p>To log users out of the application, we can use the logout() method on the Auth facade. This will clear the authentication information in the user's session:</p>

  <pre><code class="language-php">
    Auth::logout();
  </code></pre>

  <h3>Remembering Users</h3>

  <p>To provide "remember me" functionality in the application, we can pass a boolean value as the second argument to the attempt() method, which will keep the user authenticated indefinitely, or until they manually logout. The user table must include the string remember_token column, which will be used to store the "remember me" token:</p>

  <pre><code class="language-php">
    if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
        // The user is being remembered...
    }
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>If using the built-in LoginController that ships with Laravel, the proper logic to "remember" users is already implemented by the traits used by the controller.</p>
  </div>

  <p>When "remembering" users, we can use the viaRemember() method to determine if the user was authenticated using the "remember me" cookie:</p>

  <pre><code class="language-php">
    if (Auth::viaRemember()) {
        //
    }
  </code></pre>

  <h3>Other Authentication Methods</h3>

  <h4>Authenticate a User Instance</h4>

  <p>To log an existing user instance into the application, we can call the login() method with the user instance. The given object must be an implementation of the Illuminate\Contracts\Auth\Authenticatable contract. The App\User model included with Laravel already implements this interface:</p>

  <pre><code class="language-php">
    Auth::login($user);

    // Login and "remember" the given user...
    Auth::login($user, true);
  </code></pre>

  <p>We can also specify the guard instance we would like to use:</p>

  <pre><code class="language-php">
    Auth::guard('admin')->login($user);
  </code></pre>

  <h4>Authenticate a User by ID</h4>

  <p>To log a user into the application using an ID, we can use the loginUsingId() method. This method accepts the primary key of the user we want to authenticate:</p>

  <pre><code class="language-php">
    Auth::loginUsingId(1);

    // Login and "remember" the given user...
    Auth::loginUsingId(1, true);
  </code></pre>

  <h4>Authenticate a User Once</h4>

  <p>We can use the once() method to log a user into the application for a single request. No session or cookies will be utilized, meaning this method may be helpful when building a stateless API:</p>

  <pre><code class="language-php">
    if (Auth::once($credentials)) {
        //
    }
  </code></pre>

  <h2>HTTP Basic Authentication</h2>

  <p>HTTP Basic Authentication provides a quick way to authenticate users of the application without setting up a dedicated "login" page. To do this, we will need to attach the auth.basic middleware to the route. The auth.basic middleware is included with the Laravel framework.</p>

  <pre><code class="language-php">
    Route::get('profile', function () {
        // Only authenticated users may enter...
    })->middleware('auth.basic');
  </code></pre>

  <p>Once the middleware has been attached to the route, users will automatically be prompted for credentials when accessing the route in the browser. By default, the auth.basic middleware uses the email column on the user record as the "username".</p>

  <h4>A Note on FastCGI</h4>

  <p>When using PHP FastCGI,  HTTP basic authentication may not work correctly out of the box. To remedy this, add the following lines to the .htaccess file:</p>

  <pre><code class="language-php">
    RewriteCond %{HTTP:Authorization} ^(.+)$
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
  </code></pre>

  <h3>Stateless HTTP Basic Authentication</h3>

  <p>We can also use HTTP Basic Authentication without setting a user identifier cookie in the session. This is particularly useful for API authentication. To do this, first define a middleware that calls the onceBasic() method. If no reponse is returned by the onceBasic() method, the request can be passed further into the application:</p>

  <pre><code class="language-php">
    namespace Illuminate\Auth\Middleware;

    use Illuminate\Support\Facades\Auth;

    class AuthenticateOnceWithBasicAuth
    {
        public function handle($request, $next)
        {
            return Auth::onceBasic() ?: $next($request);
        }

    }
  </code></pre>

  <p>Next, register the route middleware and attach it to a route:</p>

  <pre><code class="language-php">
    Route::get('api/user', function () {
        // Only authenticated users may enter...
    })->middleware('auth.basic.once');
  </code></pre>

  <h2>Adding Custom Guards</h2>

  <p>We can define our own custom authentication guards using the extend() method on the Auth facade. We can place this call to provider within a service provider. Since Laravel ships with an AuthServiceProvider, we can place the code in that provider:</p>

  <pre><code class="language-php">
    namespace App\Providers;

    use App\Services\Auth\JwtGuard;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

    class AuthServiceProvider extends ServiceProvider
    {
        public function boot()
        {
            $this->registerPolicies();

            Auth::extend('jwt', function ($app, $name, array $config) {
                // Return an instance of Illuminate\Contracts\Auth\Guard...

                return new JwtGuard(Auth::createUserProvider($config['provider']));
            });
        }
    }
  </code></pre>

  <p>In the example above, the callback passed to the extend() method should return an implementation of Illuminate\Contracts\Auth\Guard. This interface contains a few methods we will need to implement to define a custom guard. Once the guard has been defined, we can use it in the guards configuration of the auth.php config file:</p>

  <pre><code class="language-php">
    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],
  </code></pre>

  <h3>Adding Custom User Providers</h3>

  <p>If we are not using a traditional relational database to store users, we will need to extend Laravel with our own auth user provider. To do this we can use the provider method on the Auth facade to define a custom user provider:</p>

  <pre><code class="language-php">
    namespace App\Providers;

    use Illuminate\Support\Facades\Auth;
    use App\Extensions\RiakUserProvider;
    use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

    class AuthServiceProvider extends ServiceProvider
    {
        public function boot()
        {
            $this->registerPolicies();

            Auth::provider('riak', function ($app, array $config) {
                // Return an instance of Illuminate\Contracts\Auth\UserProvider...

                return new RiakUserProvider($app->make('riak.connection'));
            });
        }
    }
  </code></pre>

  <p>After registring the provider using the provider() method, we can switch to the new user provider in the auth.php config file. To do this, first define a provider that uses the new driver:</p>

  <pre><code class="language-php">
    'providers' => [
        'users' => [
            'driver' => 'riak',
        ],
    ],
  </code></pre>

  <p>Finally, we can use this provider n the guards configuration:</p>

  <pre><code class="language-php">
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],
  </code></pre>

  <h3>The User Provider Contract</h3>

  <p>The Illuminate\Contracts\Auth\UserProvider implementations are only responsible for fetching a Illuminate\Contracts\Auth\Authenticatable implementation out of a persistent storage system such as MySQL, Riak, etc. These two interfaces allow the Laravel authentication mechanisms to continue functioning regardless of how the data is stored or what type of class is used to represent it.</p>

  <p>Following is the Illuminate\Contracts\Auth\UserProvider contract:</p>

  <pre><code class="language-php">
    namespace Illuminate\Contracts\Auth;

    interface UserProvider {

        public function retrieveById($identifier);
        public function retrieveByToken($identifier, $token);
        public function updateRememberToken(Authenticatable $user, $token);
        public function retrieveByCredentials(array $credentials);
        public function validateCredentials(Authenticatable $user, array $credentials);

    }
  </code></pre>

  <p>The retrieveById() function typically receives a key representing the user. The Authenticatable implementation matching the ID should be retrieved and returned by the method.</p>

  <p>The retrieveByToken() function retrieves a user by their unique $identifier and "remember me" $token, stored in a remember_token field. As with the previous method, the Authenticatable implementation should be returned.</p>

  <p>The updateRememberToken() method updates the $user field remember_token with the new $token. The new token can be either a fresh token, assigned on a successful "remember me" login attemp, or when the user is logging out.</p>

  <p>The retrieveByCredentials() method receives the array of credentials passed to the Auth::attempt() method when attempting to sign into the application. The method should then query the underlying persistent storage for the user matching those credentials. Typically, this method will run a query with a "where" condition on $credentials['username']. The method should then return an implementation of Authenticatable.</p>

  <p>The validateCredentials() method should compare the given $user with the $credentials to authenticate the user. This method should return true or false indicating on whether the password is valid.</p>

  <h3>The Authenticatable Contract</h3>

  <pre><code class="language-php">
    namespace Illuminate\Contracts\Auth;

    interface Authenticatable {

        public function getAuthIdentifierName();
        public function getAuthIdentifier();
        public function getAuthPassword();
        public function getRememberToken();
        public function setRememberToken($value);
        public function getRememberTokenName();

    }
  </code></pre>

  <p>The getAuthIdentifierName() method should return the name of the "primary key" field of the user and the getAuthIdentifier() method should return the "primary key" of the user. In a MySQL back-end, this would be the auto-incrementing primary key. The getAuthPassword() method should return the user's hashed password. This interface allows the authentication system to work with any User class, regardless of what ORM or storage abstraction layer is being used. By default, Laravel includes a User class in the app directory which implements this interface.</p>

  <h2>Events</h2>

  <p></p>




@endsection
