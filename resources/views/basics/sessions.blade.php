@extends('master')

@section('content')
  <h1>HTTP Sessions</h1>

  <h2>Introduction</h2>

  <p>Since HTTP driven applications are stateless, sessions provide a way to store information about the user accross multiple requests. Laravel ships with a variety of session backends that can be accessed through an expressive, unified API. Support for popular backends like Memcached, Redis and databases is included out of the box.</p>

  <h3>Configuration</h3>

  <p>The session configuration file is stored at config/session.php. It's a good idea to view the options available in this file. By default, Laravel is configured to use the file session driver, which will work well for many applications. In production applications, we should consider using the memcached or redis drivers for faster session performance.</p>

  <p>The session driver configuration option defines where session data will be stored for each request. Laravel ships with several great drivers out of the box:</p>

  <ul>
    <li>file - sessions are stored in storage/framework/sessions</li>
    <li>cookie - sessions are stored in secure, encrypted cookies</li>
    <li>database - sessions are stored in a relational database</li>
    <li>memcached / redis - sessions are stored in one of these fast, cache based stores</li>
    <li>array - sessions are stored in a PHP array and will not be persisted</li>
  </ul>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>The array driver is used during testing and prevents the data stored in the session from being persisted.</p>
  </div>

  <h3>Driver Prerequisites</h3>

  <h4>Database</h4>

  <p>When using the database session driver, we will need to create a table to contain the items. Below is an example Schema declaration for the table:</p>

  <pre><code class="language-php">
    Schema::create('sessions', function ($table) {
        $table->string('id')->unique();
        $table->unsignedInteger('user_id')->nullable();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->text('payload');
        $table->integer('last_activity');
    });
  </code></pre>

  <p>We can use the session:table Artisan command to generate this migration:</p>

  <pre><code class="language-php">
    php artisan session:table

    php artisan migrate
  </code></pre>

  <h4>Redis</h4>

  <p>Before using Redis sessions with Laravel, we will need to install the predis/predis package (~1.0) via Composer. We can configure the Redis connections in the database configuration file. In the session configuration file, the connection option may be used to specify which Redis connection is used by the session.</p>

  <h2>Using the Session</h2>

  <h4>Retrieving Data</h4>

  <p>There are two primary was of working with session data in Laravel: the global() session helper, and the Request instance. First we'll look at accessing the session via a Request instance, which can be type-hinted on a controller method. Remember, controller method dependencies are automatically injected via the Laravel service container:</p>

  <pre><code class="language-php">
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;

    class UserController extends Controller
    {
        public function show(Request $request, $id)
        {
            $value = $request->session()->get('key');

            // ...
        }
    }
  </code></pre>

  <p>When we retrieve a value from the session, we can also pass a default value as the second argument to the get() method. This default value will be returned if the specific key does not exist in the session. If we pass a Closure as the default value to the get() method and the request key does not exist, the closure will be executed and its result returned:</p>

  <pre><code class="language-php">
    $value = $request->session()->get('key', 'default');

    $value = $request->session()->get('key', function() {
      return 'default';
    });
  </code></pre>

  <h4>The Global Session Helper</h4>

  <p>We can also use the global session PHP function to retrieve and store data in the session. When the session() helper is called with a single, string argument, it will return the value of that session key. When the helper is called with an array of key/value pairs, those values will be stored in the session:</p>

  <pre><code class="language-php">
    Route::get('home', function() {
      // Retrieve a piece of data from the session
      $value = session('key');

      // Specifying a default value
      $value = session('key', 'default');

      // Store a piece of data in the session
      session(['key' => 'value']);
    });
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>There is little practical difference between using the session via an HTTP request instance versus using the global session() helper. Both methods are testable via the assertSessionHas method which is available in all test cases.</p>
  </div>

  <h4>Retrieving All Session Data</h4>

  <p>To retrieve all the data in the session, we can use the all() method:</p>

  <pre><code class="language-php">
    $data = $request->session()->all();
  </code></pre>

  <h4>Determining if Item Exists in Session</h4>

  <p>To determine if a value is present in the session, we can use the has() method. The has() method returns true if the value is present and is not null:</p>

  <pre><code class="language-php">
    if ($request->session()->has('users')) {
      // do something...
    };
  </code></pre>

  <p>To determine if a value is present in the session, even if it is null, we can use the exists() method. The exists() method returns true if the value is present:</p>

  <pre><code class="language-php">
    if ($request->session()->exists('users')) {
      // do something...
    };
  </code></pre>

  <h3>Storing Data</h3>

  <p>To store data in the session, we can use the put() method of the session() helper:</p>

  <pre><code class="language-php">
    // Via request instance
    $request->session()->put('key', 'value');

    // Via the global helper
    session(['key' => 'value']);
  </code></pre>

  <h4>Pushing to Array Session Values</h4>

  <p>The push() method may be used to push a new value onto a session value that is an array. For example, if the user.teams key contains an array of team names, we can push a new value onto the array like this:</p>

  <pre><code class="language-php">
    $request->session()->push('user.teams', 'developers');
  </code></pre>

  <h4>Retrieving and Deleting an Item</h4>

  <p>The pull() method will retrieve and delete an item from the session in a single statement:</p>

  <pre><code class="language-php">
    $value = $request->session()->pull('key', 'default');
  </code></pre>

  <h3>Flash Data</h3>

  <p>Sometimes we may want to store items in a session only for the next request. We can do this using the flash() method. Data stored in the session using the method will only be available during the subsequent HTTP request, and then will be deleted. Flash data is primarily useful for short-lived status messages:</p>

  <pre><code class="language-php">
    $request->session()->flash('status', 'Task was successfull!');
  </code></pre>

  <p>To keep the flash data around for several requests, we can use the reflash() method, which will keep all the flash data for an additional request. To only keep specific flash data, we can use the keep() method:</p>

  <pre><code class="language-php">
    $request->session()->reflash();

    $request->session()->keep(['username', 'email']);
  </code></pre>

  <h3>Deleting Data</h3>

  <p>The forget() method will remove a piece of data from the session. To remove all data from the session, we can use the flush() method:</p>

  <pre><code class="language-php">
    $request->session()->forget('key');

    $request->session()->flush();
  </code></pre>

  <h3>Regenerating the Session ID</h3>

  <p>Regenerating the session id is often done to prevent malicious users from exploiting a session fixation attack on the application.</p>

  <p>Laravel automatically regenerates the session ID during authentication while using the built-in LoginController. To manually regenerate the session ID, we can use the regenerate() method:</p>

  <pre><code class="language-php">
    $request->session()->regenerate();
  </code></pre>

  <h2>Adding Custom Session Drivers</h2>

  <h4>Implementing the Driver</h4>

  <p>A custom session driver should implement the SessionHandlerInterface. This interface contains a few simple methods that we will need to implement. A stubbed MongoDB implementation looks something like this:</p>

  <pre><code class="language-php">
    namespace App\Extensions;

    class MongoHandler implements SessionHandlerInterface
    {
        public function open($savePath, $sessionName) {}
        public function close() {}
        public function read($sessionId) {}
        public function write($sessionId, $data) {}
        public function destroy($sessionId) {}
        public function gc($lifetime) {}
    }
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Laravel does not ship with a directory to contain your extensions. We can place them anywhere we want. In the above example, we have created and Extensions directory to house the MongoHandler.</p>
  </div>

  <p>Since the purpose of these methods is not readily understandable, we'll quickly cover what each of the methods do:</p>

  <ul>
    <li>open - typically used in file-based session store systems. Since Laravel ships with a file session driver, we will almost never need to put anything in this method...we can leave it as an empty stub. It is simply a fact of poor interface design that PHP requires the implementation of this method.</li>

    <li>close - can usually be disregarded; not needed for most drivers.</li>

    <li>read - should return the string version of the session data associated with the given $sessionId. There is no need to do any serialization or other encoding when retrieving or storing session data in the driver, as Laravel will perform the serialization for us.</li>

    <li>write - should write the given $data string associated with the $sessionId to some persistent storage system, such as MongoDB, Dynamo, etc. No serialization is needed - Laravel will handle this for us.</li>

    <li>destroy - should remove the data associated with the $sessionId from persistent storage.</li>

    <li>gc - should destroy all session data that is older than the given $lifetime, which is a UNIX timestamp. For self-expiring systems, this method can be left empty.</li>
  </ul>

  <h4>Registering the Driver</h4>

  <p>Once the driver has been implemented, we are ready to register it with the framework. To add additional drivers to Laravel's backend, we can use the extend() method on the Session facade. We should call the extend() method from the boot method of a service provider. We can do this from the existing AppServiceProvider, or we can create an entirely new provider:</p>

  <pre><code class="language-php">
    namespace App\Providers;

    use App\Extensions\MongoSessionStore;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\ServiceProvider;

    class SessionServiceProvider extends ServiceProvider
    {
        public function boot()
        {
            Session::extend('mongo', function ($app) {
                // Return implementation of SessionHandlerInterface...
                return new MongoSessionStore;
            });
        }

        public function register()
        {
            //
        }
    }
  </code></pre>

  <p>Once the session driver has been registered, we can use the mongo driver in the config/session.php configuration file.</p>
@endsection
