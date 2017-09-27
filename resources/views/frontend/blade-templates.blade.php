@extends('master')

@section('content')
  <h1>Blade Templates</h1>

  <h2>Introduction</h2>

  <p>Blade is a simple yet powerful templating engine provided with Laravel. Blade does not restrict us from using plain PHP code in our views. All blade views are compiled into plain PHP code and cached until they are modified, meaning Blade adds essentially zero overhead to the application. Blade view files use the .blade.php file extension and are typically stored in the resources/views directory.</p>

  <h2>Template Inheritance</h2>

  <h3>Defining a Layout</h3>

  <p>Two primary benefits of using Blade are template inheritance and sections. Following is an example of a "master" page layout. Since most apps maintain the same general layout accross pages, it is convenient to define this layout as a single Blade view:</p>

  <pre><code class="language-php">
    &lt;!-- Stored in resources/views/layouts/app.blade.php --&gt;<br />
    <br />
    &lt;html&gt;<br />
        &lt;head&gt;<br />
            &lt;title&gt;App Name - &#64yield('title')&lt;/title&gt;<br />
        &lt;/head&gt;<br />
        &lt;body&gt;<br />
            &#64section('sidebar')<br />
                This is the master sidebar.<br />
            &#64show<br />
    <br />
            &lt;div class="container"&gt;<br />
                &#64yield('content')<br />
            &lt;/div&gt;<br />
        &lt;/body&gt;<br />
    &lt;/html&gt;
  </code></pre>

  <p>The above example contains typical HTML mark-up. The &#64section directive defines a section of the content, and the &#64yield directive is used to display the contents of a given section.</p>

  <p>Next, we will define a childpage that inherits the layout.</p>

  <h3>Extending a Layout</h3>

  <p>When defining a child-view, we can use the &#64extends directive to specify which layout the child-view should inherit. Views which extend a layout can inject content into the layout's sections using the &#64section directive. The content of these sections will be displayed in the layout using the &#64yield directive:</p>

  <pre><code class="language-php">
    &lt;!-- Stored in resources/views/child.blade.php --&gt;<br />
    <br />
    &#64extends('layouts.app')<br />
    <br />
    &#64section('title', 'Page Title')<br />
    <br />
    &#64section('sidebar')<br />
        &#64parent<br />
    <br />
        &lt;p&gt;This is appended to the master sidebar.&lt;/p&gt;<br />
    &#64endsection<br />
    <br />
    &#64section('content')<br />
        &lt;p&gt;This is my body content.&lt;/p&gt;<br />
    &#64endsection
  </code></pre>

  <p>In the example above, the sidebar is using the &#64parent directive to append (rather than overwrite) content to the layout's sidebar. The &#64parent directive will be replaced by the content of the layout when the view is rendered.</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Unlike the previous example, the sidebar section ends with &#64section instead of &#64show. The &#64endsection directive will only define a section while &#64show will define and immediately yield the section.</p>
  </div>

  <p>Blade views can be returned from routes using the global view() helper:</p>

  <pre><code class="language-php">
    Route::get('blade', function () {
        return view('child');
    });
  </code></pre>

  <h2>Components and Slots</h2>

  <p>Components and slots provide a similar benefit to sections and layouts. Some may find the mental model of components and slots easier to understand. Here, we will imagine a re-useable "alert" component we would like to use throught our application:</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Note: in order to render the code snippets without actually echoing out variables, I placed a space between the curly braces. Be sure to remove the space in actual PHP code.</p>
  </div>

  <pre><code class="language-php">
    &lt;!-- /resources/views/alert.blade.php --&gt;<br />
    <br />
    &lt;div class="alert alert-danger"&gt;<br />
        &lt;div class="alert-title"&gt;{ { $title } }&lt;/div&gt;<br />
    <br />
        { { $slot } }<br />
    &lt;/div&gt;
  </code></pre>

  <p>The slot variable will contain the content we want to inject into the component. To construct this component, we can use the &#64component directive:</p>

  <pre><code class="language-php">
    &#64component('alert')
        <strong>Whoops!</strong> Something went wrong!
    &#64endcomponent
  </code></pre>

  <p>Now we can inject content into the named slot using the &#64slot directive. Any content not within a &#64slot directive will be passed to the component in the &#64slot variable:</p>

  <pre><code class="language-php">
    &#64component('alert')
        &#64slot('title')
            Alert Title
        &#64endslot

        Message for alert goes here
    &#64endcomponent
  </code></pre>

  <h4>Passing Additional Data to Components</h4>

  <p>We can pass an array of data as the second argument to the &#64component directive. All the data will be made available to the component template as variables:</p>

  <pre><code class="language-php">
    &#64component('alert', ['foo' => 'bar'])
        ...
    &#64endcomponent
  </code></pre>

  <h2>Displaying Data</h2>

  <p>We can display data passed to the Blade views by wrapping the variable in curly braces. Given the following route:</p>

  <pre><code class="language-php">
    Route::get('greeting', function () {
        return view('welcome', ['name' => 'Samantha']);
    });
  </code></pre>

  <p>We can display the contents of the name variable like this:</p>

  <pre><code class="language-php">
    Hello, { { name } }
  </code></pre>

  <p>We are not limited to displaying the contents of the variable passed to the view. We can also echo the results of any PHP function. Actually, we can put any PHP code we want inside the Blade echo statement:</p>

  <pre><code class="language-php">
    The current UNIX timestamp is { { time() } }
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    Blade curly brace statements are automatically sent through PHP's htmlspecialchars() function to prevent XSS attacks.
  </div>

  <h4>Displaying Unescaped Data</h4>

  <p>By default, Blade curly brace statements are automatically sent through PHP's htmlspecialchars() function to prevent XSS attacks. If we don't want our data to be escaped, we can use the following syntax:</p>

  <pre><code class="language-php">
    Hello, { !! $name !! }
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Always use the escaped syntax unless you have a really good reason not to.</p>
  </div>

  <h4>Rendering JSON</h4>

  <p>To pass an array to a view and render it as JSON in order to initialize a JavaScript variable, we can use the &#64json Blade directive:</p>

  <pre><code class="language-php">
    &lt;script&gt;<br />
        var app = &#64json($array)<br />
    &lt;/script&gt;
  </code></pre>

  <h3>Blade and JavaScript Frameworks</h3>

  <p>Since many JavaScript frameworks also use curly braces to indicate a given expression should be displayed in the browser, we can use the @ symbol to inform the Blade rendering engine an expression should be ignored. For example:</p>

  <pre><code class="language-php">
    &lt;h1&gt;Laravel&lt;/h1&gt;<br />
    <br />
    Hello, &#64{ { name } }.
  </code></pre>

  <p>In the example above, the @ symbol will be removed by Blade. The { { name } } expression will remain untouched by the Blade engine, allowing it to instead be rendered by the JavaScript framework.</p>

  <h4>The &#64verbatim Directive</h4>

  <p>If displaying JavaScript variables in a large portion of the template, we can wrap the HTML in the &#64verbatim directive so that we don't have to prefix each Blade echo statement with an &#64symbol:</p>

  <pre><code class="language-php">
    &#64verbatim<br />
        &lt;div class="container"&gt;<br />
            Hello, { { name } }.<br />
        &lt;/div&gt;<br />
    &#64endverbatim
  </code></pre>

  <h2>Control Structures</h2>

  <p>Blade also provides convenient shortcuts for common PHP control structures, such as conditional statements and loops. These shortcuts provide clean and terse syntax for working with PHP control structures, while also remaining familiar to their PHP counterparts.</p>

  <p>Following are some examples of Blade control structure shortcuts:</p>

  <h4>If Statments</h4>

  <pre><code class="language-php">
    &#64if (count($records) === 1)
        I have one record!
    &#64elseif (count($records) > 1)
        I have multiple records!
    &#64else
        I don't have any records!
    &#64endif
  </code></pre>

  <h4>Unless</h4>

  <pre><code class="language-php">
    &#64unless (Auth::check())
        You are not signed in.
    &#64endunless
  </code></pre>

  <h4>Isset and Empty</h4>

  <pre><code class="language-php">
    &#64isset($records)
        // $records is defined and is not null...
    &#64endisset

    &#64empty($records)
        // $records is "empty"...
    &#64endempty
  </code></pre>

  <h4>Authentication Shortcuts</h4>

  <pre><code class="language-php">
    &#64auth
        // The user is authenticated...
    &#64endauth

    &#64guest
        // The user is not authenticated...
    &#64endguest
  </code></pre>

  <p>To specify the authentication guard:</p>

  <pre><code class="language-php">
    &#64auth('admin')
        // The user is authenticated...
    &#64endauth

    &#64guest('admin')
        // The user is not authenticated...
    &#64endguest
  </code></pre>

  <h3>Switch Statements</h3>

  <p>Switch statements can be constructed using the &#64switch, &#64case, &#64break, &#64default and &#64endswitch directives:</p>

  <pre><code class="language-php">
    &#64switch($i)
        &#64case(1)
            First case...
            &#64break

        &#64case(2)
            Second case...
            &#64break

        &#64default
            Default case...
    &#64endswitch
  </code></pre>

  <h3>Loops</h3>

  <p>Blade provides simple directives for working with PHP's loop structures. Each of these directives functions identically to their PHP counterparts:</p>

  <pre><code class="language-php">
    &#64for ($i = 0; $i < 10; $i++)
        The current value is { { $i } }
    &#64endfor

    &#64foreach ($users as $user)
        <p>This is user { { $user->id } }</p>
    &#64endforeach

    &#64forelse ($users as $user)
        <li>{ { $user->name } }</li>
    &#64empty
        <p>No users</p>
    &#64endforelse

    &#64while (true)
        <p>I'm looping forever.</p>
    &#64endwhile
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>When looping we can use the $loop variable to gain valuable information about the loop, such as whether you are in the first or last iteration of the loop:</p>
  </div>

  <p>When looping we can end the loop or skip the current iteration:</p>

  <pre><code class="language-php">
    &#64foreach ($users as $user)
        &#64if ($user->type == 1)
            &#64continue
        &#64endif

        <li>{ { $user->name } }</li>

        &#64if ($user->number == 5)
            &#64break
        &#64endif
    &#64endforeach
  </code></pre>

  <p>We can also include the condition with the directive declaration in one line:</p>

  <pre><code class="language-php">
    &#64foreach ($users as $user)
        &#64continue($user->type == 1)

        <li>{ { $user->name } }</li>

        &#64break($user->number == 5)
    &#64endforeach
  </code></pre>

  <h3>The Loop Variable</h3>

  <p>When looping, a $loop variable will be available inside of the loop. This variable provides access to some useful information such as the current loop index and whether this is the first or last iteration of the loop:</p>

  <pre><code class="language-php">
    &#64foreach ($users as $user)
        &#64if ($loop->first)
            This is the first iteration.
        &#64endif

        &#64if ($loop->last)
            This is the last iteration.
        &#64endif

        <p>This is user { { $user->id } }</p>
    &#64endforeach
  </code></pre>

  <p>If we are in a nested loop, we can access the parent loop's $loop variable via the parent property:</p>

  <pre><code class="language-php">
    &#64foreach ($users as $user)
        &#64foreach ($user->posts as $post)
            &#64if ($loop->parent->first)
                This is first iteration of the parent loop.
            &#64endif
        &#64endforeach
    &#64endforeach
  </code></pre>

  <p>The $loop variable contains a variety of useful information:</p>

  <table class="w3-table-all">
    <tr>
      <th>Property</th>
      <th>Description</th>
    </tr>
    <tr>
      <td>$loop->index</td>
      <td>index of the current loop iteration (starting at 0)</td>
    </tr>
    <tr>
      <td>$loop->iteration</td>
      <td>current iteration (starting at 1)</td>
    </tr>
    <tr>
      <td>$loop->remaining</td>
      <td>iterations remaining in the loop</td>
    </tr>
    <tr>
      <td>$loop->count</td>
      <td>total number of items in array being iterated</td>
    </tr>
    <tr>
      <td>$loop->first</td>
      <td>whether this is the first iteration of the loop</td>
    </tr>
    <tr>
      <td>$loop->last</td>
      <td>whether this is the last iteration of the loop</td>
    </tr>
    <tr>
      <td>$loop->depth</td>
      <td>nesting level of current loop</td>
    </tr>
    <tr>
      <td>$loop->parent</td>
      <td>when in nested loop, the parent's $loop variable</td>
    </tr>
  </table>

  <h3>Comments</h3>

  <p>Blade allows us to define comments in our views. Blade comments are not included in the HTML returned by the application:</p>

  <pre><code class="language-php">
    { {-- This comment will not be present in the rendered HTML --} }
  </code></pre>

  <h3>PHP</h3>

  <p>Sometimes it is useful to embed PHP code into the views. We can use the Blade &#64php directive to execute a block of plain PHP within the template:</p>

  <pre><code class="language-php">
    &#64php
        //...
    &#64endphp
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Using this directive frequently may be a sign that we have too much logic embedded within the template.</p>
  </div>

  <h2>Including Sub-Views</h2>

  <p>Blade's &#64include directive allows us to include a Blade view from within another view. All variables that are available to the parent view will be made available to the included view:</p>

  <pre><code class="language-php">
    &lt;div&gt;<br />
        &#64include('shared.errors')<br />
    <br />
        &lt;form&gt;<br />
            &lt;!-- Form Contents --&gt;<br />
        &lt;/form&gt;<br />
    &lt;/div&gt;
  </code></pre>

  <p>Even though the included view will inherit all data made available to the parent view, we can still pass an array of data to the included view:</p>

  <pre><code class="language-php">
    &#64include('view.name', ['some' => 'data'])
  </code></pre>

  <p>If we try to include a view that does not exist, Laravel will throw an error. To include a view that may or may not be present, we can use the &#64includeIf directive:</p>

  <pre><code class="language-php">
    &#64includeIf('view.name', ['some' => 'data'])
  </code></pre>

  <p>To include a view depending on a given boolean condition, we can use the &#64includeWhen directive:</p>

  <pre><code class="language-php">
    &#64includeWhen($boolean, 'view.name', ['some' => 'data'])
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Avoid using __DIR__ and __FILE__ constants in Blade views, since they will refer to the location of the cached, compiled view.</p>
  </div>

  <h3>Rendering View for Collections</h3>

  <p>We can combine loops and includes into one line with Blade's &#64each directive:</p>

  <pre><code class="language-php">
    &#64each('view.name', $jobs, 'job')
  </code></pre>

  <p>The first element is the view partial to render for each element in the array or collection. The second argument is the array or collection we are iterating over. The third argument is the variable name that will be assigned to the current iteration in the current view. We can also pass a fourth argument that will determine the view that will be rendered if the given array is empty:</p>

  <pre><code class="language-php">
    &#64each('view.name', $jobs, 'job', 'view.empty')
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Views rendered via &#64each will not inherit variables from the parent view. If the child view requires these variables, we can use &#64foreach and &#64include instead.</p>
  </div>

  <h2>Stacks</h2>

  <p>Blade allows pushing to named stacks which can be rendered somewhere else in another view or layout. This is particularly useful for specifying any JavaScript libraries required by the child views:</p>

  <pre><code class="language-php">
    &#64push('scripts')
        <script src="/example.js"></script>
    &#64endpush
  </code></pre>

  <p>We can push a stack as many times as needed. To render the complete stack content, we can pass the name of the stack to the &#64stack directive:</p>

  <pre><code class="language-php">
    &lt;head&gt;<br />
        &lt;!-- Head Contents --&gt;<br />
    <br />
        &#64stack('scripts')<br />
    &lt;/head&gt;
  </code></pre>

  <h2>Service Injection</h2>

  <p>Blade allows us to define our own custom directives using the directive() method. When a Blade compiler encounters the custom directive, it will call the provided callback with the expression that the directive contains.</p>

  <p>Following is an example of a &#64datetime($var) directive which formats a given $var, which should be an instance of DateTime():</p>

  <pre><code class="language-php">
    namespace App\Providers;

    use Illuminate\Support\Facades\Blade;
    use Illuminate\Support\ServiceProvider;

    class AppServiceProvider extends ServiceProvider
    {
        public function boot()
        {
          Blade::directive('datetime', function ($expression) {
              return "< ?php echo ($expression)->format('m/d/Y H:i'); ? >";
          });
        }

        public function register()
        {
            // ...
        }
    }
  </code></pre>

  <p>In the above example, we chain the format method onto whatever expression is passed into the directive. The final PHP generated by this directive will be:</p>

  <pre><code class="language-php">
    < ?php echo ($var)->format('m/d/Y H:i'); ? >
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>After updating the logic of a Blade directive, we will need to delete all of the cached Blade views. To do this we can use the view:clear Artisan command.</p>
  </div>

  <h3>Custom If Statements</h3>

  <p>Programming custom directives is sometimes more complex that necessary when defining simple, custom conditional statements. For this reason, Blade provides the Blade::if method which allows us to quickly define custom conditional directives using closures. In the following example, we define a custom conditional that checks the current application environment. We can do this in the boot() method of our AppServiceProvider:</p>

  <pre><code class="language-php">
    use Illuminate\Support\Facades\Blade;

    public function boot()
    {
        Blade::if('env', function ($environment) {
            return app()->environment($environment);
        });
    }
  </code></pre>

  <p>Once the condition is defined, we can easily use it in our templates:</p>

  <pre><code class="language-php">
    &#64env('local')
        // The application is in the local environment...
    &#64else
        // The application is not in the local environment...
    &#64endenv
  </code></pre>



















@endsection
