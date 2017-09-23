@extends('master')

@section('content')
  <h1>HTTP Requests</h1>

  <h2>Accessing The Request</h2>

  <p>To obtain an instance of the current HTTP request via dependency injection, we can type-hint the Illuminate\Http\Request class on the controller method. The incoming request instance will automatically be injected by the service container.</p>

  <pre><code class="language-php">
    use Illuminate\Http\Request;

    class UserController extends Controller
    {
        /**
         * Store a new user.
         *
         * @param  Request  $request
         * @return Response
         */
        public function store(Request $request)
        {
            $name = $request->input('name');
            // do something...
        }
    }
  </code></pre>

  <h4>Dependency Injection and Route Parameters</h4>

  <p>If your controller method is also expecting input from a route parameter, we can list the route parameters after the other dependencies. For example, if the route is defined like so:</p>

  <pre><code class="language-php">
    Route::put('user/{id}', 'UserController@update');
  </code></pre>

  <p>We can still type-hint the Illuminate\Http\Request and also access the route parameter 'id' by defining the controller method as follows:</p>

  <pre><code class="language-php">
    public function update(Request $request, $id)
    {
        // do something...
    }
  </code></pre>

  <h4>Accessing the Request via Route Closures</h4>

  <p>We can also type-hint the Illuminate\Http\Request class on a route Closure. The service container will automatically inject the incoming request into the Closure when it is executed:</p>

  <pre><code class="language-php">
    use Illuminate\Http\Request;

    Route::get('/', function (Request $request) {
        // do something...
    });
  </code></pre>

  <h3>Request and Path Method</h3>

  <p>The Illuminate\Http\Request instance provides a variety of methods for examining the HTTP request for your application and extends the Symfony\Component\HttpFoundation\Request class. A few of the most important methods are discussed below:</p>

  <h4>Retrieving the Request Path</h4>

  <p>The path() method returns the request's path information. So, if the incoming request is targeted at http://domain.com/foo/bar, the path method will return foo/bar:</p>

  <pre><code class="language-php">
    $uri = $request->path();
  </code></pre>

  <p>The is() method allows us to verify that the incoming request path mathches a given pattern. We can use the * character as a wildcard when utilizing this method:</p>

  <pre><code class="language-php">
    if ($request->is('admin/*')) {
      // do something...
    }
  </code></pre>

  <h4>Retrieving the Request URL</h4>

  <p>To retrieve the full URL for the incoming request, we can use the url() or fullUrl() methods. The url() method will return the url without the query string, while the fullUrl() method includes the query string:</p>

  <pre><code class="language-php">
    // Without query string
    $url = $request->url();

    With query string
    $url = $request->fullUrl();
  </code></pre>

  <h4>Retrieving the Request Method</h4>

  <p>The method() method will return the HTTP verb for the request. You may use the isMethod() method to verify that the HTTP verb matches a given string.</p>

  <pre><code class="language-php">
    $method = $request->method();

    if ($request->isMethod('post')) {
      // do something...
    }
  </code></pre>

  <h3>PSR-7 Requests</h3>

  <p>The PSR-7 standard specifies interfaces for HTTP messages, including request and responses. If we want to obtain an instance of a PSR-7 request instead of a Laravel request, we first need to install a few libraries. Laravel uses the Symfony HTTP Message Bridge component to convert typical Laravel requests and responses into PSR-7 compatible implementations:</p>

  <pre><code class="language-php">
    composer require symfony/psr-http-message-bridge
    composer require zendframework/zend-diactoros
  </code></pre>

  <p>Once these libraries are installed, we can obtain a PSR-7 request by type-hinting the request interface on your route Closure or controller method:</p>

  <pre><code class="language-php">
    use Pse\Http\Message\ServerRequestInterface;

    Route::get('/', function (ServerRequestInterface $request) {
      // do something...
    })
  </code></pre>

  <p class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">Note: if you return a PSR-7 response instance from a route or controller, it will automatically be converted back to a Laravel response instance and be displayed by the framework.</p>

  <h2>Input Trimming and Normalization</h2>

  <p>By default, Laravel includes the TrimStrings and ConvertEmptyStringsToNull middleware in your application's global middleware stack. These middleware are listed in the stack by the App\Http\Kernel class. These middleware will automatically trim all incoming string fields on the request, as well as convert any empty string fields to null. This means we do not have to worry about these normalization concerns in the route controllers.</p>

  <p>If we want to disable this behavior, we can disable these middleware by removing them from the $middleware property of the App\Http\Kernel class.</p>

  <h2>Retrieving Input</h2>

  <h4>Retrieving All Input Data</h4>

  <p>We can retrieve all input data as an array using the all() method:</p>

  <pre><code class="language-php">
    $input = $request->all();
  </code></pre>

  <h4>Retrieving and Input Value</h4>

  <p>Using a few simple methods, we can access all of the user input from the Illuminate\Http\Request instance without worrying about which HTTP verb was used for the request. Regardless of the verb, the input() method may be used to retrieve user input:</p>

  <pre><code class="language-php">
    $name = $request->input('name');
  </code></pre>

  <p>We can pass a default value as the second argument to the input() method. This value will be returned if the requested input value is not present on the request:</p>

  <pre><code class="language-php">
    $name = $request->input('name', 'Sally');
  </code></pre>

  <p>When working with forms that contain array inputs, use 'dot' notation to access the arrays:</p>

  <pre><code class="language-php">
    $name = $request->input('products.0.name');

    $names = $request->input('products.*.name');
  </code></pre>

  <h4>Retrieving Input from the Query String</h4>

  <p>While the input() method retrieves values from entire request payload (including the query string), the query() method will only retrieve values from the query string:</p>

  <pre><code class="language-php">
    $name = $request->query('name');
  </code></pre>

  <p>If the requested query string data is not present, the second argument to this method will be returned:</p>

  <pre><code class="language-php">
    $name = $request->query('name', 'Helen');
  </code></pre>

  <p>You may call the query() method without any arguments in order to retrieve all of the query string values as an associative array:</p>

  <pre><code class="language-php">
    $request = $request->query();
  </code></pre>

  <h4>Retrieving Input via Dynamic Values</h4>

  <p>We can access user input using dynamic properties on the Illuminate\Http\Request instance. For example, if one of the application's forms contains a 'name' field, we can access the value of the field like this:</p>

  <pre><code class="language-php">
    $name = $request->name;
  </code></pre>

  <p>When using dynamic properties, Laravel will first look for the parameter's value in the request payload. If it is not present, Laravel will search for the field in the route parameters.</p>

  <h4>Retrieving JSON Input Values</h4>

  <p>When sending JSON requests to the application, we can access the JSON data via the input() method as long as the Content-Type header of the request is properly set to application/json. We can even use 'dot' syntax to dig into JSON arrays:</p>

  <pre><code class="language-php">
    $name = $request->input('user.name');
  </code></pre>

  <h4>Retrieving a Portion of the Input Data</h4>

  <p>To retrieve a subset of the input data, we can use the only() and except() methods. Both of these methods accept a single array or a dynamic list of arguments:</p>

  <pre><code class="language-php">
    $input = $request->only(['userame', 'password']);

    $input = $request->only('username', 'password');

    $input = $request->except(['credit_card']);

    $input = $request->except('credit_card');
  </code></pre>

  <p class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">Note: the only() method returns all of the key/value pairs that your request, however, it will not return key/value pairs that are not present on the request.</p>

  <h4>Determining if an Input Value is Present</h4>

  <p>We can use the has() method to determine if a value is present on the request. The has() method returns true if the value is present on the request:</p>

  <pre><code class="language-php">
    if ($request->has('name')) {
      // do something...
    }
  </code></pre>

  <p>When given an array, the has method will determine if all of the specified values are present:</p>

  <pre><code class="language-php">
    if ($request->has(['name', 'email'])) {
      // do something...
    }
  </code></pre>

  <p>To determine if a value is present on the request and is not empty, we can use the filled() method:</p>

  <pre><code class="language-php">
    if ($request->filled('name')) {
      // do something...
    }
  </code></pre>

  <h3>Old Input</h3>

  <p>Laravel allows us to keep input from one request during the next request. This feature is particularly useful for re-populating forms after detecting validation errors. However, if we are using Laravel's included validation features, it is unlikely we will need to manually use these methods, as some of Laravel's built in validation facilities will call them automatically.</p>

  <h4>Flashing Input to the Session</h4>

  <p>The flash() method on the Illuminate\Http\Request class will flash the current input to the session so that it is available during the user's next request to the application.</p>

  <pre><code class="language-php">
    $request->flash();
  </code></pre>

  <p>We can also use the flashOnly() and flashExcept() methods to flash a subset of the request data to the session. These methods are useful for keeping sensitive information such as passwords out of the session:</p>

  <pre><code class="language-php">
    $request->flashOnly(['username', 'email']);

    $request->flashExcept('password');
  </code></pre>

  <h4>Flashing Input then Redirecting</h4>

  <p>Since we will often want to flash input to the session and then redirect to the previous page, we can easily chain input flashing onto a redirect using the withInput() method:</p>

  <pre><code class="language-php">
    return redirect('form')->withInput();

    return redirect('form')->withInput(
    $request->except('password')
    );
  </code></pre>

  <h4>Retrieving Old Input</h4>

  <p>To retrieve flashed input from the previous request, we can use the old() method on the Request instance. The old() method will pull the previously flashed input data from the session:</p>

  <pre><code class="language-php">
    $username = $request->old('username');
  </code></pre>

  <p>Laravel also provides a global old() helper. If we are using old input within a Blade template, it is more convenient to use the old() helper. If no old input exists for the given field, null will be returned:</p>

  <pre><code class="language-php">
    input type="text" name="username" value="{{ old('username') }}"
  </code></pre>

  <h3>Cookies</h3>

  <h4>Retrieving Cookies from Requests</h4>

  <p>All cookies created by the Laravel framework are encrypted and signed with an authentication code, meaning they will be considered invalid if they have been changed by the client. To retrieve a cooke value from the request, use the cookie() method on an Illuminate\Http\Request instance:</p>

  <pre><code class="language-php">
    $value = $request->cookie('name');
  </code></pre>

  <h4>Attaching Cookies to Responses</h4>

  <p>We can attach a cookie to an outgoing Illuminate\Http\Request instance using the cookie() method. To do this, we should pass the name, value and number of minutes the cookie should be considered valid to this method:</p>

  <pre><code class="language-php">
    return response('Hello World')->cookie(
    'name', 'value', $minutes
    );
  </code></pre>

  <p>The cookie() method also accepts a few more arguments which are used less frequently. Generally these arguments have the same purpose and meaning as the arguments that would be given to PHP's native setcookie() method:</p>

  <pre><code class="language-php">
    return response('Hello World')->cookie(
    'name', 'value', $minutes, $path, $domain, $secure, $httpOnly
    );
  </code></pre>

  <h4>Generating Cookie Instances</h4>

  <p>To generate a Symphony\Component\HttpFoundation\Cookie instance that can be given to a response instance at a later time, we can use the global cookie() helper. This cookie will not be sent back to the client unless it is attached to a response instance:</p>

  <pre><code class="language-php">
    $cookie = cookie('name', 'value', $minutes);

    return response('Hello World')->cookie($cookie);
  </code></pre>

  <h2>Files</h2>

  <h3>Retrieving Uploaded Files</h3>

  <p>We can access uploaded files from a Illumintate\Http\Request instance using the file() method or using dynamic properties. The file() method returns an instance of the Illuminate\Http\UploadedFile class, which extends the PHP SplFileInfo class and provides a variety of methods for interacting with the file:</p>

  <pre><code class="language-php">
    $file = $request->file('photo');

    $file = $request->photo;
  </code></pre>

  <p>We can determine if a file is present on the request using the hasFile() method:</p>

  <pre><code class="language-php">
    if ($request->hasFile('photo')) {
      // do something...
    }
  </code></pre>

  <h4>Validating Successful Uploads</h4>

  <p>In addition to checking if the file is present, we can verify that there were no problems uploading the file via the isValid() method:</p>

  <pre><code class="language-php">
    if ($request->file('photo')->isValid()) {
      // do something...
    }
  </code></pre>

  <h4>File Paths and Extesions</h4>

  <p>The UploadedFile class also contains methods for accessing the file's fully-qualified path and its extension. The extension() method will attempt to guess the file's extension based on its contents. This extension may be different from the extension that was supplied by the client:</p>

  <pre><code class="language-php">
    $path = $request->photo->path();

    $extension = $request->photo->extension();
  </code></pre>

  <h4>Other File Methods</h4>

  <p>There are a variety of other methods available on UploadedFile instances.</p>

  <h3>Storing Uploaded Files</h3>

  <p>To store an uploaded file, we will typically use one of our configured filesystems. The UploadedFile class has a store method which will move an uploaded file to one of our disks, which may be a location on the filesystem or even a cloud storage location like Amazon S3.</p>

  <p>The store method accepts the path where the file should be stored relative to the filesystem's configured root directory. This path should not contain a file-name, since a unique ID will automatically be generated to serve as the file name.</p>

  <p>The store method also accepts an optional second argument for the name of the disk that should be used to store the file. The method will return the path of the file relative to the disk's root:</p>

  <pre><code class="language-php">
    $path = $request->photo->store('images');

    $path = $request->photo->store('images', 's3');
  </code></pre>

  <p>To prevent the file name from being automatically generated, we can use the storeAs() method, which accepts the path, file name, and disk name as its arguments:</p>

  <pre><code class="language-php">
    $path = $request->photo->storeAs('images', 'filename.jpg');

    $path = $request->photo->storeAs('images', 'filename.jpg', 's3');
  </code></pre>

  <h2>Configuring Trusted Proxies</h2>

  <p>When running applications behind a load-balancer that terminates TLS/SSL certificates, we may notice the application sometimes does not generate HTTPS links. Typically this is because the application is being forwarded traffic from the load-balancer on port 80 and does not know it should generate secure links.</p>

  <p>To solve this, we can use the App\Http\Middleware\TrustProxies middleware that is included in the Laravel Application, which allows you to quickly customize the load-balancers or proxies that should be trusted by the application. Your trusted proxies should be listed as an array on the $proxies property of this middleware. In addition to configuring the trusted proxies, we can configure the headers that are being sent by your proxy with information about the original request:</p>

  <pre><code class="language-php">
    class TrustProxies extends Middleware
    {
        /**
         * The trusted proxies for this application.
         *
         * @var array
         */
        protected $proxies = [
            '192.168.1.1',
            '192.168.1.2',
        ];

        /**
         * The current proxy header mappings.
         *
         * @var array
         */
        protected $headers = [
            Request::HEADER_FORWARDED => 'FORWARDED',
            Request::HEADER_X_FORWARDED_FOR => 'X_FORWARDED_FOR',
            Request::HEADER_X_FORWARDED_HOST => 'X_FORWARDED_HOST',
            Request::HEADER_X_FORWARDED_PORT => 'X_FORWARDED_PORT',
            Request::HEADER_X_FORWARDED_PROTO => 'X_FORWARDED_PROTO',
        ];
    }
  </code></pre>

  <h4>Trusing All Proxies</h4>

  <p>If using Amazon AWS or another "cloud" load-balancer provider, we may not know the address of the actual balancers. In this case, we can use ** to trust all proxies:</p>

  <pre><code class="language-php">
    /**
     * The trusted proxies for this application.
     *
     * @var array
     */
    protected $proxies = '**';
  </code></pre>
@endsection
