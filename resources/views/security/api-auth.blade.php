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
@endsection
