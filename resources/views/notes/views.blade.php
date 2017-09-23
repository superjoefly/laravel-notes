@extends('master')

@section('content')
  <h1>Views</h1>

  <h2>Creating Views</h2>

  <p>Views contain the HTML served by the application and seperate the controller / application logic, from the presentation logic. Views are stored in resources/views directory. Here is an example of a simple view:</p>

  <pre><code class="language-php">
    &lt;html&gt;<br />
        &lt;body&gt;<br />
            &lt;h1&gt;Hello, { { $name } }&lt;/h1&gt;<br />
        &lt;/body&gt;<br />
    &lt;/html&gt;
  </code></pre>

  <p>Since this view is stored at resources/views/greeting.blade.php, we can return it using the global view() helper like this:</p>

  <pre><code class="language-php">
    Route::get('/', function() {
      return view('greeting', ['name' => 'James']);
    });
  </code></pre>


  <p>The first argument passed to the view helper cooresponds to the name of the view file in the resources/views directory. The second argument is an array of data that should be made available to the view. In the above case, we are passing the name variable, which is displayed in the view using blade syntax.</p>

  <p>Views may also be nested within sub-directories of the resources/views directory. "Dot" notation may be used to reference nested views. For example, if the view is stored at resources/views/admin/profile.blade.php, we can reference it like this:</p>

  <pre><code class="language-php">
    return view('admin.profile', $data);
  </code></pre>

  <h4>Determining if a View Exists</h4>

  <p>We can use the View facade to determine if a view exists. The exists() method will return true if the view exists:</p>

  <pre><code class="language-php">
    use Illuminate\Support\Facades\View;

    if (View::exists('emails.customer')) {
        // do something...
    }
  </code></pre>

  <h4>Creating the First Available View</h4>

  <p>Using the first() method, we can create the first view that exists in a given array of views. This is useful if the application or package allows views to be customized or overwritten:</p>

  <pre><code class="language-php">
    return view()->first(['custom.admin', 'admin'], $data);
  </code></pre>

  <p>We can also call this method via the View facade:</p>

  <pre><code class="language-php">
    use Illuminate\Support\Facades\View;

    return View::first(['custom.admin', 'admin'], $data);
  </code></pre>

  <h2>Passing Data to Views</h2>

  <p>We can pass an array of data to views like this:</p>

  <pre><code class="language-php">
    return view('welcome', ['name' => 'James']);
  </code></pre>

  <p>When passing information in the way, the data should be an array of key => value pairs. Inside the view, we can then access each value using its corresponding key:</p>

  <pre><code class="language-php">
    &lt;?php echo $key; ?&gt;
  </code></pre>

  <p>As an alternative to passing an array of data to the view() helper function, we can use the with() method to add individual pieces of data to the view:</p>

  <pre><code class="language-php">
    return view('greeting')->with('name', 'James');
  </code></pre>

  <h4>Sharing Data with All Views</h4>

  <p>To share data with all views, we can use the view facade's share() method. Typically, we should place calls to share() within a service provider's boot() method. We can add them to the AppServiceProvider or generate a separate service provider to house them:</p>

  <pre><code class="language-php">
    class AppServiceProvider extends ServiceProvider

    public function boot()
    {
        View::share('key', 'value');
    }
  </code></pre>

  <h2>View Composers</h2>

  <p>View composers are callbacks or class methods that are called when a view is rendered. If you have data that you want to be bound to a view each time that view is rendered, a view composer can help to organize that logic into a single location.</p>

  <p>In the following example, we will register the view composers within a service provider. We'll then use the View facade to access the underlying Illuminate\Contracts\View\Factory contract implementation. Remember, Laravel does not include a default directory for view composers. We can organize them however we'd like. For example, we could create an app/Http/ViewComposers directory:</p>

  <pre><code class="language-php">
    namespace App\Providers;

    use Illuminate\Support\Facades\View;
    use Illuminate\Support\ServiceProvider;

    class ComposerServiceProvider extends ServiceProvider
    {
        public function boot()
        {
            // Using class based composers...
            View::composer(
                'profile', 'App\Http\ViewComposers\ProfileComposer'
            );

            // Using Closure based composers...
            View::composer('dashboard', function ($view) {
                //
            });
        }
    }
  </code></pre>

  <p class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">Note: if we create a new service provider to contain the view composer registrations, we must add the service provider to the providers array in the config/app.php configuration file.</p>

  <p>Once the composer is registered, the ProfileComposer@compose method will be executed each time the profile view is being rendered. Here is the example composer class:</p>

  <pre><code class="language-php">
    namespace App\Http\ViewComposers;

    use Illuminate\View\View;
    use App\Repositories\UserRepository;

    class ProfileComposer
    {
        protected $users;

        public function __construct(UserRepository $users)
        {
            $this->users = $users;
        }

        public function compose(View $view)
        {
            $view->with('count', $this->users->count());
        }
    }
  </code></pre>

  <p>Right before the view is rendered, the composer's compose method is called with the Illuminate\View\View instance. We can use the with() method to bind data to the view.</p>

  <p class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">Note: All view composers are resolved via the service container, so we can type-hint any dependencies needed within a composer's constructor.</p>

  <h4>Attaching a Composer to Multiple Views</h4>

  <p>We can attach a view composer to multiple views at once by passing an array of views as the first argument to the composer() method:</p>

  <pre><code class="language-php">
    View::composer(
    ['profile', 'dashboard'],
    'App\Http\ViewComposers\MyViewComposer'
    );
  </code></pre>

  <p>The composer() method also accepts the * character as a wildcard, allowing us to attach a composer to all views:</p>

  <pre><code class="language-php">
    View::composer('*', function($view) {
      // do something...
    });
  </code></pre>

  <h4>View Creators</h4>

  <p>View creators are very similar to view composers, however, they are executed immediately after the view is instantiated instead of waiting until the view is about to be rendered. To register a view creator, use the creator() method:</p>

  <pre><code class="language-php">
    View::creator('profile', 'App\Http\ViewCreators\ProfileCreator');
  </code></pre>

@endsection
