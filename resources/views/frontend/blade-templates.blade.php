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

  <p>The above example contains typical HTML mark-up. The @ section directive defines a section of the content, and the @ yield directive is used to display the contents of a given section.</p>

  <p>Next, we will define a childpage that inherits the layout.</p>

  <h3>Extending a Layout</h3>

  <p>When defining a child-view, we can use the @ extends directive to specify which layout the child-view should inherit. Views which extend a layout can inject content into the layout's sections using the @ section directive. The content of these sections will be displayed in the layout using the @ yield directive:</p>

  <pre><code class="language-php">
    &lt;!-- Stored in resources/views/child.blade.php --&gt;<br />
    <br />
    @ extends('layouts.app')<br />
    <br />
    @ section('title', 'Page Title')<br />
    <br />
    @ section('sidebar')<br />
        @ parent<br />
    <br />
        &lt;p&gt;This is appended to the master sidebar.&lt;/p&gt;<br />
    @ endsection<br />
    <br />
    @ section('content')<br />
        &lt;p&gt;This is my body content.&lt;/p&gt;<br />
    @ endsection
  </code></pre>

  <p>In the example above, the sidebar is using the @ parent directive to append (rather than overwrite) content to the layout's sidebar. The @ parent directive will be replaced by the content of the layout when the view is rendered.</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Unlike the previous example, the sidebar section ends with @ section instead of @ show. The @ endsection directive will only define a section while @ show will define and immediately yield the section.</p>
  </div>

  <p>Blade views can be returned from routes using the global view() helper:</p>

  <pre><code class="language-php">
    Route::get('blade', function () {
        return view('child');
    });
  </code></pre>

  <h2>Components and Slots</h2>

  <p></p>














@endsection
