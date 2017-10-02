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

  <p></p>
@endsection
