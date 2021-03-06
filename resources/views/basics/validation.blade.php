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

  <h3>Creating Form Requests</h3>

  <p>For more complex validation scenarios, we may wish to create a "form request". Form requests are custom request classes that contain validation logic. To create a form request, we can use the make:request Artisan command:</p>

  <pre><code class="language-php">
    php artisan make:request StoreBlogPost
  </code></pre>

  <p>The generated class is placed in the app/Http/Requests directory. If the directory does not exist, it will be created for us. Next, let's add a few validation rules to the rules method:</p>

  <pre><code class="language-php">
    public function rules()
    {
      return [
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
      ];
    }
  </code></pre>

  <p>To include the validation form, all we need to do is type-hint the request on the controller method. The incomming form request is validated before the controller method is called, meaning that we won't have to clutter our controller with any validation logic:</p>

  <pre><code class="language-php">
    public function authorize()
      {
          return true; // Set to true for example
      }

    public function store(StoreBlogPost $request)
      {
          // The incoming request is valid...
      }
  </code></pre>

  <p>If validation fails, a redirect response will be generated to send the user back to their previous location. The errors will be flashed to the session so they are available for display. If the request was an AJAX request, an HTTP response with code 422 will be returned to the user including a JSON representation of the validation errors.</p>

  <h4>Adding After Hooks to Form Requests</h4>

  <p>To add an "after" hook to a form request, we can use the withValidator() method. This method receives the fully constructed validator, allowing us to call any of its methods before the validation rules are actually evaluated:</p>

  <pre><code class="language-php">
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->somethingElseIsInvalid()) {
                $validator->errors()->add('field', 'Something is wrong with this field!');
            }
        });
    }
  </code></pre>

  <h2>Authorizing Form Requests</h2>

  <p>The form request class also contains an authorize() method. Within this method, we can check if the authenticated user actually has the authority to update a given resource. For example, we can determine if a user actualy owns a blog comment they are attempting to update:</p>

  <pre><code class="language-php">
    public function authorize()
    {
        $comment = Comment::find($this->route('comment'));

        return $comment && $this->user()->can('update', $comment);
    }
  </code></pre>

  <p>Since all form requests extend the base Laravel request class, we can use the user() method to access the currently authenticated user. Also, note the call to the route() method in the above example. This method grants access to the URI parameters defined on the route being called, such as the {comment} parameter in the example below:</p>

  <pre><code class="language-php">
    Route::post('comment/{comment}');
  </code></pre>

  <p>If the authorize method returns false, a HTTP response with code 403 will be automatically returned and the controller method will not execute.</p>

  <p>If planning to have authorization logic in another part of the application, we can simply return true from the authorize method:</p>

  <pre><code class="language-php">
    public function authorize()
    {
        return true;
    }
  </code></pre>

  <h3>Customizing the Error Messages</h3>

  <p>We can customize the error messages used by the form request by overriding the messages() method. This method should return an array of attribute / rule pairs and their corresponding error messages:</p>

  <pre><code class="language-php">
    public function messages()
    {
        return [
            'title.required' => 'A title is required',
            'body.required'  => 'A message is required',
        ];
    }
  </code></pre>

  <h2>Manually Creating Validators</h2>

  <p>If we don't want to use the validate method on the request, we can create a validator instance manually using the Validator facade. The make() method on the facade generates a new validator instance:</p>

  <pre><code class="language-php">
    namespace App\Http\Controllers;

    use Validator;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;

    class PostController extends Controller
    {
        public function store(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:posts|max:255',
                'body' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect('post/create')
                            ->withErrors($validator)
                            ->withInput();
            }

            // Store the blog post...
        }
    }
  </code></pre>

  <p>The first argument passed to the make() method is the data under validation. The second argument is the validation rules that should be applied to the data.</p>

  <p>After checking if the request validation failed, we can use the withErrors() method to flash the error messages to the session. When using this method, the $errors variable will automatically be shared with the view after redirection, allowing us to easily display them back to the user. The withErrors() method accepts a validator, a MessageBag, or a PHP array.</p>

  <h3>Automatic Redirection</h3>

  <p>To create a validator instance manually, and still take advantage of the automatic redirection offered by the request's validate() method, we can call the validate() method on an existing validator instance. If validation fails, the user will automatically be redirected or, in the case of AJAX requests, a JSON response will be returned:</p>

  <pre><code class="language-php">
    Validator::make($request->all(), [
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
    ])->validate();
  </code></pre>

  <h3>Named Error Bags</h3>

  <p>If we have multiple forms on a single page, we can name the MessageBag of errors, allowing us to retrieve the error messages for a specific form. To do this, we can simply pass a name as the second argument to the withErrors() method:</p>

  <pre><code class="language-php">
    return redirect('register')
                ->withErrors($validator, 'login');
  </code></pre>

  <p>Then, we can access the named MessageBag instance from the $errors variable:</p>

  <pre><code class="language-php">
    { { $errors->login->first('email') } }
  </code></pre>

  <h3>After Validation Hooks</h3>

  <p>The validator allows us to attach callbacks to be run after validation is completed. This allows us to easily perform further validation and even add more error messages to the message collection. To get started, use the after() method on a validator instance:</p>

  <pre><code class="language-php">
    $validator = Validator::make(...);

    $validator->after(function ($validator) {
        if ($this->somethingElseIsInvalid()) {
            $validator->errors()->add('field', 'Something is wrong with this field!');
        }
    });

    if ($validator->fails()) {
        //
    }
  </code></pre>

  <h2>Working with Error Messages</h2>

  <p>After calling the errors() method on a Validator instance, we will receive an Illuminate\Support\MessageBag instance, which has a variety of convenient methods for working with error messages. The $errors variable that is automatically made available to all views is also an instance of the MessageBag class.</p>

  <h4>Retrieving the First Error Message for a Field</h4>

  <p>To retrieve the first error message for a given field, we can use the first() method:</p>

  <pre><code class="language-php">
    $errors = $validator->errors();

    echo $errors->first('email');
  </code></pre>

  <h4>Retrieving All Error Messages for a Field</h4>

  <p>To retrieve an array of all the messages for a given field, we can use the get() method:</p>

  <pre><code class="language-php">
    foreach ($errors->get('email') as $message) {
      // do something...
    }
  </code></pre>

  <p>If validating an array form field, we can retrieve all of the messages for each of the array elements using the * character:</p>

  <pre><code class="language-php">
    foreach ($errors->all() as $message) {
      // do something...
    }
  </code></pre>

  <h4>Determining if a Message Exists for a Field</h4>

  <p>The has() method can be used to determine if any error messages exist for a given field:</p>

  <pre><code class="language-php">
    if ($errors->has('email')) {
      // do something...
    }
  </code></pre>

  <h3>Custom Error Messages</h3>

  <p>We can also use custom error messages for validation instead of the defaults. There are several ways to specify custom messages. First, we can pass the custom messages as the third argument to the Validator:make method:</p>

  <pre><code class="language-php">
    $messages = [
      'required' => 'The :attribute field is required.',
    ];

    $validator = Validator::make($input, $rules, $messages);
  </code></pre>

  <p>In this example, the :attribute place-holder will be replaced by the actual name of the field under validation. We can also utilize other place-holders in validation messages. For example:</p>

  <pre><code class="language-php">
    $messages = [
        'same'    => 'The :attribute and :other must match.',
        'size'    => 'The :attribute must be exactly :size.',
        'between' => 'The :attribute value :input is not between :min - :max.',
        'in'      => 'The :attribute must be one of the following types: :values',
    ];
  </code></pre>

  <h4>Specifying a Custom Message for a Given Attribute</h4>

  <p>We can specify a custom error message only for a specific field using "dot" notation. First specify the attribute's name, followed by the rule:</p>

  <pre><code class="language-php">
    $messages = [
    'email.required' => 'We need to know your email address...',
    ];
  </code></pre>

  <h4>Specify Custom Messages in Language Files</h4>

  <p>Often, we will specify custom messages in a language file instead of passing them directly to the Validator. To do this, we can add our messages to custom array in the resources/lang/xx/validation.php language file:</p>

  <pre><code class="language-php">
    'custom' => [
        'email' => [
            'required' => 'We need to know your e-mail address!',
        ],
    ],
  </code></pre>

  <h4>Specifying Custom Attributes in the Language Files</h4>

  <p>If we want the :attribute portion of the validation message to be replaced with a custom attribute name, we can specify the custom name in the attributes array of the resources/lang/xx/validation.php language file:</p>

  <pre><code class="language-php">
    'attributes' => [
    'email' => 'email address',
    ],
  </code></pre>

  <h2>Available Validation Rules</h2>

  <p>For a list of Available Validation Rules, please see Laravel's <a href="https://laravel.com/docs/5.5/validation#available-validation-rules" style="color: green; text-decoration: none;">Available Validation Rules</a> section.</p>

  <h2>Conditionally Adding Rules</h2>

  <h4>Validating When Present</h4>

  <p>In some situations we may wish to run validation checks against a field only if that field is present in the input array. To quickly accomplish this, we can add the sometimes rule to the rule list:</p>

  <pre><code class="language-php">
    $v = Validator::make($data, [
    'email' => 'sometimes|required|email',
    ]);
  </code></pre>

  <p>In the example above the email field will only be validated if it is present in the $data array.</p>

  <h4>Complex Conditional Validation</h4>

  <p>Sometimes we may want to add validation rules based on more complex conditional logic. To do this, we can create a Validator instance with staic rules that never change:</p>

  <pre><code class="language-php">
    $v = Validator::make($data, [
    'email' => 'required|email',
    'games' => 'required|numeric',
    ]);
  </code></pre>

  <p>Let's assume that, in our application, if a game collecter registers with the application and they own more than 100 games, we want them to explain why they own so many games. To conditionally add this requirement, we can use the sometimes() method on the Validator instance.</p>

  <pre><code class="language-php">
    $v->sometimes('reason', 'required|max:500', function($input) {
      return $input->games >= 100;
    });
  </code></pre>

  <p>The first argument passed to the sometimes() method is the name of the field we are conditionally validating. The second argument is the rules we want to add. If the closure passed as the third argument returns true, the rules will be added. This method makes it easy to build complex conditional validations. We can even add conditional validations for several fields at once:</p>

  <pre><code class="language-php">
    $v->sometimes(['reason', 'cost'], 'required', function($input) {
      return $input->games >= 100;
    });
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>The $input parameter passed to the closure will be an instance of Illuminate\Support\Fluent and may be used to access your input and file.</p>
  </div>

  <h2>Validating Arrays</h2>

  <p>Validating array based form input fields can be easy. We can use "dot" notation to validate attributes within an array. For example, if the incoming HTTP request contains a photos[profile] field, we can validate it like this:</p>

  <pre><code class="language-php">
    $validator = Validator::make($request->all(), [
    'photos.profile' => 'required|image',
    ]);
  </code></pre>

  <p>We can also validate each element of an array. For example, to validate that each email in a given array input field is unique, we can do the following:</p>

  <pre><code class="language-php">
    $validator = Validator::make($request->all(), [
        'person.*.email' => 'email|unique:users',
        'person.*.first_name' => 'required_with:person.*.last_name',
    ])
  </code></pre>

  <p>We can also use the * character when specifying our validation messages in the language files:</p>

  <pre><code class="language-php">
    'custom' => [
        'person.*.email' => [
            'unique' => 'Each person must have a unique e-mail address',
        ]
    ],
  </code></pre>

  <h2>Custom Validation Rules</h2>

  <h3>Using Rule Objects</h3>

  <p>Laravel provides a variety of helpful validation rules, however, we may want to specify some of our own. One method of registering custom validation rules is using rule objects. To generate a new rule object, we can use the make:rule Artisan command. Here, we will generate a rule that verifies a string is uppercase. Laravel will automatically place the new rule in the app/Rules directory:</p>

  <pre><code class="language-php">
    php artisan make:rule Uppercase
  </code></pre>

  <p>Once the rule has been created, we are ready to define its behavior. A rule object contains two methods: passes and message. The passes() method receives the attribute value and name, and should return either true or false depending on whether the attribute value is valid or not. The message() method should return the validation error message that should be used when the validation fails:</p>

  <pre><code class="language-php">
    namespace App\Rules;

    use Illuminate\Contracts\Validation\Rule;

    class Uppercase implements Rule
    {
        public function passes($attribute, $value)
        {
            return strtoupper($value) === $value;
        }

        public function message()
        {
            return 'The :attribute must be uppercase.';
        }
    }
  </code></pre>

  <p>Of course, we can call the trans() helper from the message() method if we want to return an error message from the translation files:</p>

  <pre><code class="language-php">
    public function message()
    {
        return trans('validation.uppercase');
    }
  </code></pre>

  <p>Once the rule has been defined, we can attach it to a validator by passing an instance of the rule object with the other validation rules:</p>

  <pre><code class="language-php">
    use App\Rules\Uppercase;

    $request->validate([
        'name' => ['required', new Uppercase],
    ]);
  </code></pre>

  <h3>Using Extensions</h3>

  <p>Another method of registering custom validation rules is using the extend() method on the Validator facade. In the following example, we use this method within a service provider to register a custom validation rule:</p>

  <pre><code class="language-php">
    namespace App\Providers;

    use Illuminate\Support\ServiceProvider;
    use Illuminate\Support\Facades\Validator;

    class AppServiceProvider extends ServiceProvider
    {
        public function boot()
        {
            Validator::extend('foo', function ($attribute, $value, $parameters, $validator) {
                return $value == 'foo';
            });
        }

        public function register()
        {
            //
        }
    }
  </code></pre>

  <p>The custom validator closure receives four arguments: the name of the $attribute being validated, the $value of the attribute, an array of $parameters passed to the rule, and the Validator instance.</p>

  <p>We can also pass a class and extend() method instead of a closure:</p>

  <pre><code class="language-php">
    Validator::extend('foo', 'FooValidator@validate');
  </code></pre>

  <h4>Defining the Error Message</h4>

  <p>We will also need to define an error message for the custom rule. We can do so either using an inline custom message array, or by adding an entry in the validation language file. This message should be placed in the first level of the array, not within the custom array, which is only for attribute-specific error messages:</p>

  <pre><code class="language-php">
    "foo" => "Your input was invalid!",

    "accepted" => "The :attribute must be accepted.",

    // The rest of the validation error messages...
  </code></pre>

  <p>When creating a custom validation rule, we may sometimes want to define custom place-holder replacements for error messages. We can do this by creating a custom Validator as described above, then making a call to the replacer() method on the Validator facade. We can do this with the boot() method of a service provider:</p>

  <pre><code class="language-php">
    public function boot()
    {
        Validator::extend(...);

        Validator::replacer('foo', function ($message, $attribute, $rule, $parameters) {
            return str_replace(...);
        });
    }
  </code></pre>

  <h4>Implicit Extensions</h4>

  <p>By default, when an attribute being validated is not present or contains an empty value as defined by the required rule, normal validation rules, including custom extensions, are not run. For example, the unique rule will not be run against a null value:</p>

  <pre><code class="language-php">
    $rules = ['name' => 'unique'];

    $input = ['name' => null];

    Validator::make($input, $rules)->passes(); // true
  </code></pre>

  <p>For a rule to run even when an attribute is empty, the rule must imply that the attribute is required. To create such an "implicit" extension, we can use the Validator::extendImplicit() method:</p>

  <pre><code class="language-php">
    Validator::extendImplicit('foo', function($attribute, $value, $parameters, $validator) {
      return $value == 'foo';
    });
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>An "implicit" extension only implies that the attribute is required. Whether it actually invalidates a missing or empty attribute is up to us.</p>
  </div>

@endsection
