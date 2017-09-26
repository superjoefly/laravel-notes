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




















@endsection
