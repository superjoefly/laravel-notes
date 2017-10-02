@extends('master')

@section('content')
  <h1>API Authentication</h1>

  <h2>Introduction</h2>

  <p>APIs typically use tokens to autheticate users and do not maintain session state between request. Laravel uses Laravel Passport, which provides a full OAuth2 server for Laravel applications.</p>

  <h2>Installation</h2>

  <p>To get started with Passport, install it via the Composer package manager:</p>

  <pre><code class="language-php">
    composer require laravel/passport
  </code></pre>

  <p>The Passport service provider registers its own database migration directory with the framework, so we should migrate the database after registering the provider. The Passport migrations will create the tables needed to store clients and access tokens.</p>

  <pre><code class="language-php">
    php artisan migrate
  </code></pre>

  <p>Next, we need to run passport:install to create encryption keys needed to generate secure access tokens. This command will also create "personal access" and "password grant" clients which will be used to generate access tokens:</p>

  <pre><code class="language-php">
    php artisan passport:install
  </code></pre>

  <p>After running this command, add the Laravel\Passport\HasApiTokens trait to the App\User model. This trait adds methods to the model that will allow you to inspect the authenticated user's token and scopes:</p>

  <pre><code class="language-php">
    namespace App;

    use Laravel\Passport\HasApiTokens;
    use Illuminate\Notifications\Notifiable;
    use Illuminate\Foundation\Auth\User as Authenticatable;

    class User extends Authenticatable
    {
        use HasApiTokens, Notifiable;
    }
  </code></pre>

  <p>Next, we need to call the Passport::routes() method within the boot() method of the AuthServiceProvider. This will register the routes necessary to issue access tokens and revoke access tokens, clients, and personal access tokens:</p>

  <pre><code class="language-php">
    namespace App\Providers;

    use Laravel\Passport\Passport;
    use Illuminate\Support\Facades\Gate;
    use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

    class AuthServiceProvider extends ServiceProvider
    {
        protected $policies = [
            'App\Model' => 'App\Policies\ModelPolicy',
        ];

        public function boot()
        {
            $this->registerPolicies();

            Passport::routes();
        }
    }
  </code></pre>

  <p>Finally, in the config/auth.php config file, we need to set the driver option of the api authentication guard to passport. This will instruct the application to use Passport's TokenGuard when authenticating incoming API requests.</p>

  <pre><code class="language-php">
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
    ],
  </code></pre>

  <h3>Frontend Quickstart</h3>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>In order to use Passport Vue components, we must be using the Vue JavaScript framework. These components also use the Bootstrap CSS framework. Even when not using these components, we can use them as a reference for our own frontend application.</p>
  </div>

  <p>Passport ships with a JSON API that we can use to allow users to create clients and personal access tokens. It can be time-consuming to code a frontend to interact with these APIs, so Passport includes pre-built Vue components we can use as a reference or starting point.</p>

  <p>To publish the Passport Vue components, we can use the vendor:publish Artisan command:</p>

  <pre><code class="language-php">
    php artisan vendor:publish --tag=passport-components
  </code></pre>

  <p>The published components will be placed in the resources/assets/js/components directory. Once published, they need to be registered in the resources/assets/js/app.js file:</p>

  <pre><code class="language-php">
    Vue.component(
        'passport-clients',
        require('./components/passport/Clients.vue')
    );

    Vue.component(
        'passport-authorized-clients',
        require('./components/passport/AuthorizedClients.vue')
    );

    Vue.component(
        'passport-personal-access-tokens',
        require('./components/passport/PersonalAccessTokens.vue')
    );
  </code></pre>

  <p>After registering the components, run "npm run dev" to recompile the assets. Once recompiled, we can drop the components into one of our application templates to get started creating clients and personal access tokens:</p>

  <h3>Deploying Passport</h3>

  <p>When deploying Passport to the production server for the first time, we will most likely need to run the passport:keys command. This will generate the encryption keys needed in order to generate access tokens. The generated keys are not typically kept in source control:</p>

  <pre><code class="language-php">
    php artisan passport:keys
  </code></pre>

  <h2>Configuration</h2>

  <h3>Token Lifetimes</h3>

  <p>By default, Passport issues long-lived access tokens that never need to be refreshed, however we can adjust the token lifetime using the tokensExpireIn() and refreshTokensExpireIn() methods. These methods should be called from the boot() method of the AuthServiceProvider:</p>

  <pre><code class="language-php">
    use Carbon\Carbon;

    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensExpireIn(Carbon::now()->addDays(15));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
  </code></pre>

  <h2>Issuing Access Tokens</h2>

  <p>When using OAuth2 with authorization codes, a client application will redirect the user to our server where they will either approve or deny the request to issue an access token to the client.</p>

  <h3>Managing Clients</h3>

  <p>Developers building applications that need to interact with our application's API will need to register their application with ours by creating a "client". This typically consists of providing the name of their application and a URL that our application can redirect to after users approve their request for authorization.</p>

  <h4>The passport:client Command</h4>

  <p>The simplest way to create a client is using the passport:client Artisan command. This command will be used to create our own clients for testing OAuth2 functionality. When we run the "client" command, Passport will prompt for more information about the client and will provide us with a client ID and secret:</p>

  <pre><code class="language-php">
    php artisan passport:client
  </code></pre>

  <h5>JSON API</h5>

  <p>Since users will not be able to utilize the "client" command, Passport provides a JSON API that we can use to create clients. This saves us the trouble of having to manually code controllers for creating, updating, and deleting clients.</p>

  <p>In order to use this API, we will need to pair Passport's JSON API with our own frontend to provide a dashboard for users to manage their clients. The following examples use Axios to demonstrate making HTTP requests to the endpoints.</p>

  <h4>GET /oauth/clients</h4>

  <p>This route returns all of the clients for the authenticated user. This is primarily useful for listing all of the user's clients so that they can edit or delete them:</p>

  <pre><code class="language-php">
    axios.get('/oauth/clients')
        .then(response => {
            console.log(response.data);
        });
  </code></pre>

  <h4>POST oauth/clients</h4>

  <p>This route is used to create new clients. Two pieces of data are required: the client's name and a redirect URL. The redirect URL is where the user will be redirected after approving or denying the request for authorization.</p>

  <p>When a client is created, it will be issued a client ID and client secret. These values will be used when requesting access tokens from our application. The client creation route will return the new client instance:</p>

  <pre><code class="language-php">
    const data = {
        name: 'Client Name',
        redirect: 'http://example.com/callback'
    };

    axios.post('/oauth/clients', data)
        .then(response => {
            console.log(response.data);
        })
        .catch (response => {
            // List errors on response...
        });
  </code></pre>

  <h4>PUT /oauth/clients/{client-id}</h4>

  <p>This route is used to update clients. Two pieces of data are required: the client's name and a redirect URL. The redirect URL is where the user will be redirected after approving or denying a request for authorization. The route will return the updated instance:</p>

  <pre><code class="language-php">
    const data = {
        name: 'New Client Name',
        redirect: 'http://example.com/callback'
    };

    axios.put('/oauth/clients/' + clientId, data)
        .then(response => {
            console.log(response.data);
        })
        .catch (response => {
            // List errors on response...
        });
  </code></pre>

  <h4>DELETE /oauth/clients/{client-id}</h4>

  <p>This route is used to delete clients:</p>

  <pre><code class="language-php">
    axios.delete('/oauth/clients/' + clientId)
        .then(response => {
            //
        });
  </code></pre>

  <h3>Requesting Tokens</h3>

  <h4>Redirecting for Authorization</h4>

  <p>Once a client has been created, developers can use their client ID and secret to request an authorization code and access token from our application. First, the consuming application should make a redirect request to our application's /oauth/authorize route:</p>

  <pre><code class="language-php">
    Route::get('/redirect', function () {
        $query = http_build_query([
            'client_id' => 'client-id',
            'redirect_uri' => 'http://example.com/callback',
            'response_type' => 'code',
            'scope' => '',
        ]);

        return redirect('http://your-app.com/oauth/authorize?'.$query);
    });
  </code></pre>

  <h4>Approving the Request</h4>

  <p>When receiving authorization requests, Passport will authomatically display a template to the user allowing them to approve or deny the authorization request. If they approve the request, they will be redirected back to the redirect_uri that was specified by the consuming application. The redirect_uri must match the redirect URL that was specified when the client was created.</p>

  <pre><code class="language-php">
    php artisan vendor:publish --tag=passport-views
  </code></pre>

  <h4>Converting Authorization Codes to Access Tokens</h4>

  <p>If the user approves the authorization request, they will be redirected back to the consuming application. The consumer should then issue a POST request to our application to request an access token. The request should include the authorization code that was issued by our application when the user approved the authorization request. Following is an example using the Guzzle HTTP library to make the POST request:</p>

  <pre><code class="language-php">
    Route::get('/callback', function (Request $request) {
        $http = new GuzzleHttp\Client;

        $response = $http->post('http://your-app.com/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => 'client-id',
                'client_secret' => 'client-secret',
                'redirect_uri' => 'http://example.com/callback',
                'code' => $request->code,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    });
  </code></pre>

  <p>This /oauth/token route will return a JSON response containing access_token, refresh_token, and expires_in attributes. The expires_in attribute contains the number of seconds until the access token expires.</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>The /oauth/token route is defined for us by the Passport::routes method.</p>
  </div>

  <h3>Refreshing Tokens</h3>

  <p>When issuing short lived tokens, users will need to refresh their access tokens via the refresh token that was provided to them when the access token was issued. The following example uses the Guzzle HTTP library to refresh the token:</p>

  <pre><code class="language-php">
    $http = new GuzzleHttp\Client;

    $response = $http->post('http://your-app.com/oauth/token', [
        'form_params' => [
            'grant_type' => 'refresh_token',
            'refresh_token' => 'the-refresh-token',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'scope' => '',
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
  </code></pre>

  <p>This /oauth/token route will return a JSON response containing access_token, refresh_token and expires_in attributes. The expires_in attribute contains the number of seconds until the access token expires.</p>

  <h2>Password Grant Tokens</h2>

  <p>The OAuth password grant allows our other first-party clients, such as a mobile application, to obtain an access token using an email address / username and password. This allows us to issue access tokens securely to our first party clients without redirecting our users to go through the entire OAuth2 authorization code redirect flow.</p>

  <h3>Creating a Password Grant Client</h3>

  <p>Before our application can issue tokens via the password grant, we will need to create a password grant client. We can do this using the passport:client command with the --password option. If we have alredy run the passport:install command, we do not need to run this command:</p>

  <pre><code class="language-php">
    php artisan passport:client --password
  </code></pre>

  <h3>Requesting Tokens</h3>

  <p>Once a password grant client has been created, we can request an access token by issuing a POST request to the /oauth/token route with the user's email address and password. This route is already registered by the Passport::routes method. If the request is successful, we will receive an access_token and refresh_token in the JSON response from the server:</p>

  <pre><code class="language-php">
    $http = new GuzzleHttp\Client;

    $response = $http->post('http://your-app.com/oauth/token', [
        'form_params' => [
            'grant_type' => 'password',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'username' => 'taylor@laravel.com',
            'password' => 'my-password',
            'scope' => '',
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
  </code></pre>

  <h3>Requesting All Scopes</h3>

  <p>When using the password grant, we can authorize the token for all of the scopes supported by our application. We can do this using the * scope. When requesting the * scope, the can() method on the token instance will always return true. This scope may only be assigned to a token that is issued using the password grant:</p>

  <pre><code class="language-php">
    $response = $http->post('http://your-app.com/oauth/token', [
        'form_params' => [
            'grant_type' => 'password',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'username' => 'taylor@laravel.com',
            'password' => 'my-password',
            'scope' => '*',
        ],
    ]);
  </code></pre>

  <h2>Implicit Grant Tokens</h2>

  <p>The implicit grant is similar to the authorization code grant, however, the token is returned to the client without exchanging an authorization code. This type of grant is commonly used for JavaScript or mobile applications where the client credentials can't be securely stored. We can enable the grant by calling the enableImplicitGrant() method in the AuthServiceProvider:</p>

  <pre><code class="language-php">
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::enableImplicitGrant();
    }
  </code></pre>

  <p>Once enabled, developers can use their client ID to request an access token from our application. The consuming application should make a redirect request the our application's /oauth/authorize route:</p>

  <pre><code class="language-php">
    Route::get('/redirect', function () {
        $query = http_build_query([
            'client_id' => 'client-id',
            'redirect_uri' => 'http://example.com/callback',
            'response_type' => 'token',
            'scope' => '',
        ]);

        return redirect('http://your-app.com/oauth/authorize?'.$query);
    });
  </code></pre>

  <h2>Client Credentials Grant Tokens</h2>

  <p>The client credentials grant is suitable for machine-to-machine authentication. For example, we could use this grant in a scheduled job which is performing maintenance tasks over an API. To use this method, we first need to add new middleware to the $routeMiddleware in app/Http/Kernel.php:</p>

  <pre><code class="language-php">
    use Laravel\Passport\Http\Middleware\CheckClientCredentials;

    protected $routeMiddleware = [
        'client' => CheckClientCredentials::class,
    ];
  </code></pre>

  <p>Then, we can attach this middleware to a route:</p>

  <pre><code class="language-php">
    Route::get('/user', function(Request $request) {
        ...
    })->middleware('client');
  </code></pre>

  <p>To retrieve a token, make a request to the oauth/token endpoint:</p>

  <pre><code class="language-php">
    $guzzle = new GuzzleHttp\Client;

    $response = $guzzle->post('http://your-app.com/oauth/token', [
        'form_params' => [
            'grant_type' => 'client_credentials',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'scope' => 'your-scope',
        ],
    ]);

    return json_decode((string) $response->getBody(), true)['access_token'];
  </code></pre>

  <h2>Personal Access Token</h2>

  <p>Sometimes, users may want to issue access tokens themselves without going through the typical authorization code redirect flow. This can be useful to allow users to experiment with our API, or may serve as a simpler approach to issuing access tokens in general.</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Personal access tokens are always long lived. They are not modified when using the tokensExpireIn() or refreshTokensExpireIn() methods.</p>
  </div>

  <h3>Creating a Personal Access Client</h3>

  <p>Before our application can issue personal access tokens, we will need to create a personal access client. We can do this using the passport:client command with the --personal option. If we've already ran the passport:install command, we do not need to run this command:</p>

  <pre><code class="language-php">
    php artisan passport:client --personal
  </code></pre>

  <h3>Managing Personal Access Tokens</h3>

  <p>Once we have created a personal access client, we can issue tokens for a given user using the createToken() method on the User model instance. The createToken() method accepts the name of the token as its first argument and an optional array of scopes as its second argument:</p>

  <pre><code class="language-php">
    $user = App\User::find(1);

    // Creating a token without scopes...
    $token = $user->createToken('Token Name')->accessToken;

    // Creating a token with scopes...
    $token = $user->createToken('My Token', ['place-orders'])->accessToken;
  </code></pre>

  <h4>JSON API</h4>

  <p>Passport also includes a JSON API for managing personal access tokens. We can pair this with our own frontend to offer our users a dashboard for managing peronsal access tokens. In the following examples, we'll use Axios to demonstrate making HTTP requests to the endpoints.</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>We can use the frontend quickstart to have a fully functional frontend in a matter of minutes.</p>
  </div>

  <h5>GET /oauth/scopes</h5>

  <p>This route returns all scopes defined for the application. We can use this route to list the scopes a user may assign to a personal access token:</p>

  <pre><code class="language-php">
    axios.get('/oauth/scopes')
        .then(response => {
            console.log(response.data);
        });
  </code></pre>

  <h5>GET /oauth/personal-access-tokens</h5>

  <p>This route returns all personal access tokens that the authenticated user has created. This is primarily useful for listing all access tokens so that the user may edit or delete them:</p>

  <pre><code class="language-php">
    axios.get('/oauth/personal-access-tokens')
        .then(response => {
            console.log(response.data);
        });
  </code></pre>

  <h5>POST /oauth/peronsal-access-tokens</h5>

  <p>This route creates new personal access tokens. It requires two pieces of data: the token's name and the scopes that should be assigned to the token:</p>

  <pre><code class="language-php">
    const data = {
        name: 'Token Name',
        scopes: []
    };

    axios.post('/oauth/personal-access-tokens', data)
        .then(response => {
            console.log(response.data.accessToken);
        })
        .catch (response => {
            // List errors on response...
        });
  </code></pre>

  <h5>DELETE /oauth/personal-access-tokens/{token-id}</h5>

  <p>This route can be used to delete personal access tokens:</p>

  <pre><code class="language-php">
    axios.delete('/oauth/personal-access-tokens/' + tokenId);
  </code></pre>

  <h2>Protecting Routes</h2>

  <h3>Via Middleware</h3>

  <p>Passport includes an authentication guard that will validate access tokens on incoming requests. Once we have configured the api guard to use the passport driver, we only need to specify the auth:api middleware on any routes that require a valid access token:</p>

  <pre><code class="language-php">
    Route::get('/user', function () {
        //
    })->middleware('auth:api');
  </code></pre>

  <h3>Passing the Access Token</h3>

  <p>When calling routes that are protected by Passport, our application's API consumers should specify their access token as a Bearer token in the Authorization header of their request. For example, when using the Guzzle HTTP library:</p>

  <pre><code class="language-php">
    $response = $client->request('GET', '/api/user', [
        'headers' => [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$accessToken,
        ],
    ]);
  </code></pre>

  <h2>Token Scopes</h2>

  <h3>Defining Scopes</h3>

  <p>Scopes allow our API clients to request a specific set of permissions when requesting authorization to access an account. In other words, scopes allow our application's users to limit the actions a third-party application can perform on their behalf.</p>

  <p>We can define our API' scopes using the Passport::tokensCan method in the boot() method of our AuthServiceProvider. The tokensCan() method accepts an array of scope names and descriptions. The scope description can be anything we want, and will be displayed to the users on the authorization approval screen:</p>

  <pre><code class="language-php">
    use Laravel\Passport\Passport;

    Passport::tokensCan([
        'place-orders' => 'Place orders',
        'check-status' => 'Check order status',
    ]);
  </code></pre>

  <h3>Assigning Scopes to Tokens</h3>

  <h4>When Requesting Authorization Codes</h4>

  <p>When requesting an access token using the authorization code grant, consumers should spcify their desired scopes as the scope query string parameter. The scope parameter should be a space-delimited list of scopes:</p>

  <pre><code class="language-php">
    Route::get('/redirect', function () {
        $query = http_build_query([
            'client_id' => 'client-id',
            'redirect_uri' => 'http://example.com/callback',
            'response_type' => 'code',
            'scope' => 'place-orders check-status',
        ]);

        return redirect('http://your-app.com/oauth/authorize?'.$query);
    });
  </code></pre>

  <h4>When Issuing Personal Access Tokens</h4>

  <p>If issuing personal access tokens using the User model's createToken() method, we can pass the array of desired scopes as the second argument to the method:</p>

  <pre><code class="language-php">
    $token = $user->createToken('My Token', ['place-orders'])->accessToken;
  </code></pre>

  <h3>Checking Scopes</h3>

  <p>Passport includes two middleware that can be used to verify that an incoming request is authenticated with a token that has been granted a given scope. To do this, add the following middleware to the $routeMiddleware property of the app/Http/Kernel.php file:</p>

  <pre><code class="language-php">
    'scopes' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
    'scope' => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class,
  </code></pre>

  <h4>Check for All Scopes</h4>

  <p>The scopes middleware can be assigned to a route to verify that the incoming request's access token has all of the listed scopes:</p>

  <pre><code class="language-php">
    Route::get('/orders', function () {
        // Access token has both "check-status" and "place-orders" scopes...
    })->middleware('scopes:check-status,place-orders');
  </code></pre>

  <h4>Check for ANY Scopes</h4>

  <p>The scope middleware can be assigned to a route to verify that the incoming request's access token has at least one of the listed scopes:</p>

  <pre><code class="language-php">
    Route::get('/orders', function () {
        // Access token has either "check-status" or "place-orders" scope...
    })->middleware('scope:check-status,place-orders');
  </code></pre>

  <h4>Checking Scopes on a Token Instance</h4>

  <p>Once an access token authenticated request has entered our application, we can still check if the token has a given scope using the tokenCan() method on the authenticated User instance:</p>

  <pre><code class="language-php">
    use Illuminate\Http\Request;

    Route::get('/orders', function (Request $request) {
        if ($request->user()->tokenCan('place-orders')) {
            //
        }
    });
  </code></pre>

  <h2>Consuming the API with JavaScript</h2>

  <p>To consume your own API from your JavaScript application, we need to manually send an access token to the application and pass it with each request to the application. Passport includes a middleware that can handle this for us. All we need to do is add the CreateFreshApiToken middleware to our web middleware group:</p>

  <pre><code class="language-php">
    'web' => [
        // Other middleware...
        \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class,
    ],
  </code></pre>

  <p>This middlware will attach a laravel_token cookie to the outgoing responses. This cookie contains an encrypted JWT that Passport will use to authenticate API requests from the JavaScript application. Then we can make our requests to the application's API without explicitly passing an access token:</p>

  <pre><code class="language-php">
    axios.get('/api/user')
        .then(response => {
            console.log(response.data);
        });
  </code></pre>

  <p>When using this method of authentication, Axios will automatically send the X-CSRF-TOKEN header. In addition, the default Laravel JavaScript scaffolding instructs Axios to send the X-Requested-With header:</p>

  <pre><code class="language-php">
    window.axios.defaults.headers.common = {
        'X-Requested-With': 'XMLHttpRequest',
    };
  </code></pre>

  <h2>Events</h2>

  <p></p>
@endsection
