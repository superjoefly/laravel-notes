@extends('master')

@section('content')
  <h1>Validation</h1>

  <h2>Introduction</h2>

  <p>Laravel provides several different approaches to validate an application's incoming data. By default, Laravel's base controller class uses a ValidatesRequests trait which provides a convenient mehod to validate incoming HTTP requests with a variety of validation rules.</p>

  <h2>Validation Quickstart</h2>

  <p>To learn about Laravel's powerful validation features, let's look at a complete example of validating a form and displaying the error messages back to the user.</p>

  <h3>Defining the Routes</h3>

  <p>First, let's assume we have the following routes defined in our routes/web.php file:</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>We can make a model, migration and resourceful controller with the following command: php artisan make:model Post -mcr</p>
  </div>

  <pre><code class="language-php">
    Route::get('post/create', 'PostController@create');

    Route::post('post', 'PostController@store');
  </code></pre>

  <p>The 'get' route will display a form for the user to create a new post, and the 'post' route will store the new blog post in the database.</p>

  <h3>Creating the Controller</h3>

  <p>Following is a simple controller that will handle these routes:</p>

  <pre><code class="language-php">
    class PostController extends Controller
    {
        public function create()
        {
            return view('post.create');
        }

        public function store(Request $request)
        {
            // Validate and store the blog post...
        }
    }
  </code></pre>

  <h3>Writing the Validation Logic</h3>

  <p>Now we need to fill in our store() method with the validation logic to validate the new blog post. To do this, we will need the validate() method provided by the Illuminate\Http\Request object. If the validation rules pass, our code will keep executing normally. If validation fails, an exception will be thrown and the proper error response will automatically be sent back to the user. In the case of a traditional HTTP request, a redirect response will be generated, while a JSON response will be sent for AJAX request.</p>

  <p>Following is an example of a simple store() validation method:</p>

  <pre><code class="language-php">
    public function store(Request $request) {
      $request->validate([
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
      ]);

      // The blog post is valid, store in the database
    }
  </code></pre>

  <p>We simply pass the desired validation rules into the validate() method. If the validation fails, the proper response will be automatically generated. If the validation passes, our controller will continue to execute normally.</p>

  <h4>Stopping on First Validation Failure</h4>

  <p>Sometimes we may want to stop running validation rules on an attribute after the first validation failure. To do this, we can assign the bail rule to the attribute:</p>

  <pre><code class="language-php">
    $request->validate([
    'title' => 'bail|required|unique:posts|max:255',
    'body' => 'required',
    ]);
  </code></pre>

  <p>In the above example, if the unique rule on the title attribute fails, the max rule will not be checked. Rules are validated in the order they are assigned.</p>

  <h4>A Note on Nested Attributes</h4>

  <p>If the HTTP request contains "nested" parameters, we can specify them in our validation rules using "dot" syntax:</p>

  <pre><code class="language-php">
    $request->validate([
        'title' => 'required|unique:posts|max:255',
        'author.name' => 'required',
        'author.description' => 'required',
    ]);
  </code></pre>

  <h3>Displaying Validation Errors</h3>

  <p>Laravel automatically redirects the user back to their previous location if validation does not pass. All validation errors will be automaitcally flashed to the session.</p>

  <p>We do not have to explicitly bind the error message to the view in our GET route. Laravel will check for errors in the validation data, and automatically bind them to the view if they are available. The $errors variable is an instance of Illuminate\Support\MessageBag.</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>The errors variable is bound to the view by the Illuminate\View\Middleware\ShareErrorsFromSession middleware, which is provided by the web middleware group. When this is applied a $errors variable will always be available in your views, allowing for safe assumption of availability.</p>
  </div>

  <p>In our example, the user will be directed to our controller's create() method when validation fails. allowing us to display the error message in the view:</p>

  <pre><code class="language-php">
    // Create Posts Form
    @ if ($errors-&gt;any())<br />
        &lt;div class="alert alert-danger"&gt;<br />
            &lt;ul&gt;<br />
                @ foreach ($errors-&gt;all() as $error)<br />
                    &lt;li&gt;{ { $error } }&lt;/li&gt;<br />
                @ endforeach<br />
            &lt;/ul&gt;<br />
        &lt;/div&gt;<br />
    @ endif
  </code></pre>

  <h3>A Note on Optional Fields</h3>

  <p>By default, Laravel includes the TrimStrings and ConvertEmptyStringsToNull middleware in the application's global middleware stack. These middleware are listed in the stack by the App\Http\Kernel class. Because of this, we will often need to mark the "optional" request fields as nullable if we don't want the validator to consider null values as invalid. For example:</p>

  <pre><code class="language-php">
    $request->validate([
    'title' => 'required|unique:posts|max:255',
    'body' => 'required',
    'publish_at' => 'nullable|data'
    ]);
  </code></pre>

  <p>In the above example, we are specifying that the publish_at field may be either null or a valid date representation. If the nullable modifier is not added to the rule definition, the validator would consider null an invalid date.</p>

  <h4>AJAX Requests and Validation</h4>

  <p>In the above example we used a traditional form to send data to the application. However, many applications use AJAX requests. When using the validate() method during an AJAX request, Laravel will not generate a redirect response. Instead, a JSON response will be generated containing all the validation errors. This JSON response will be sent with a 422 HTTP status code.</p>

  <h2>Form Request Validation</h2>

  <p></p>

@endsection
