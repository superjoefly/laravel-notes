@extends('master')

@section('content')
  <h1>URL Generation</h1>

  <h2>Introduction</h2>

  <p>Laravel provides several helpers to assist in generating URLs for an application. These are helpful when building links in templates and API responses, or when generating direct responses to another part of the application.</p>

  <h2>The Basics</h2>

  <h3>Generating Basic URLs</h3>

  <p>The url() helper may be used to create arbitrary URLs for the application. The generated URL will automatically use the scheme (HTTP or HTTPS) and host from the current request:</p>

  <pre><code class="language-php">
    $post = App\Post::find(1);

    echo url('/posts/{$post->id}');

    // http://example.come/posts/1
  </code></pre>

  <h3>Accessing the Current URL</h3>

  <p>If no path is provided to the url() helper, an Illuminate\Routing\UrlGenerator instance is returned, allowing access to information about the current URL:</p>

  <pre><code class="language-php">
    // Get current URL without query string
    echo url()->current();

    // Get current URL with query string
    echo url()->full();

    // Get full url for previous request
    echo url()->previous();
  </code></pre>

  <p>We also access these methods via the URL Facade:</p>

  <pre><code class="language-php">
    use Illuminate\Support\Facades\URL;

    echo URL::current();
  </code></pre>

  <h2>URLs for Named Routes</h2>

  <p>The route() helper may be used to generate URLs to named routes. Named routes allow you to generate URLs without being coupled to the actual URL defined on the route. Therefore, if the route changes, no changes need to be made in the route function calls. For example:</p>

  <pre><code class="language-php">
    Route::get('/post/{post}', function() {
      // do something...
    })->name('post.show');
  </code></pre>

  <p>To generate a URL to this route, we can use the route() helper like this:</p>

  <pre><code class="language-php">
    echo route('post.show', ['post' => 1]);

    // http://example.com/post/1
  </code></pre>

  <p>We will often generate URLs using the primary key of Eloquent Models. For this reason, we can pass Eloquent models as parameter values. The route() helper will automatically extract the model's primary key:</p>

  <pre><code class="language-php">
    echo route('post.show', ['post' => $post]);
  </code></pre>

  <h2>URLs for Controller Actions</h2>

  <p>The action() function generates a URL for the given controller action. We don't need to pass the full namespace of the controller. Instead, we can pass the controller class name relative to the App\Http\Controllers namespace:</p>

  <pre><code class="language-php">
    $url = action('HomeController@index');
  </code></pre>

  <p>If the controller method accepts route parameters, we can pass them as the second argument to the function:</p>

  <pre><code class="language-php">
    $url = action('UserController@profile', ['id' => 1]);
  </code></pre>

  <h2>Default Values</h2>

  <p>For some applications, we may want to specify request wide default values for certain URL parameters. For example:</p>

  <pre><code class="language-php">
    Route::get('/{locale}/posts', function() {
      // do something...
    })->name('post.index');
  </code></pre>

  <p>It would be cumbersome to always pass the locale every time we call the route() helper. Instead we can use the URL::defaults() method to define a default value for this parameter that will always be applied during the request. We can call this method from a route middleware so that we have access to the current request:</p>

  <pre><code class="language-php">
    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Support\Facades\URL;

    class SetDefaultLocaleForUrls
    {
        public function handle($request, Closure $next)
        {
            URL::defaults(['locale' => $request->user()->locale]);

            return $next($request);
        }
    }
  </code></pre>

  <p>Once the default value for the locale parameter has been set, we are no longer required to pass it value when generating URLs via the route() helper.</p>
@endsection
