@extends('master')

@section('content')
  <h1>CSRF Protection</h1>

  <h2>Introduction</h2>

  <p>Laravel automatically generates a CSRF 'token' for each active user session managed by the application. This token is used to verify that the authenticated user is the only one actually making the request to the application.</p>

  <p>Anytime we define a HTML form in the application, we should include a hidden CSRF token field in the form so that the CSRF protection middleware can validate the request. We can use the csrf-field helper to generate the token field like so:</p>

  <pre><code class="language-php">
    { {csrf_field()} }
  </code></pre>

  <p>The VerifyCsrfToken middleware is included in the web middleware group, and will automatically verify that the token in the request input matches the token stored in the session.</p>

  <h3>CSRF Tokens and JavaScript</h3>

  <p>When building JavaScript driven applications, it is convenient to have your JS HTTP Library automatically attach the CSRF token to every outgoing request. By default, the resources/assets/js/bootstrap.js file registers the value of the csrf-token meta tag with the Axios library. If you are not using this library, you will need to manually configure this behavior.</p>

  <h2>Excluding URIs From CSRF Protection</h2>

  <p>You may wish to exclude a set of URIs from CSRF protection. Typically these routes should be placed outside the web middleware group that the RouteServiceProvider applies to all routes in the routes/web.php file. However, you may also exclude the routes by adding their URIs to the $except property of the VerifyCsrfToken middleware:</p>

  <pre><code class="language-php">
    class VerifyCsrfToken extends Middleware
    {
        /**
         * The URIs that should be excluded from CSRF verification.
         *
         * @ var array
         */
        protected $except = [
            //
        ];
    }
  </code></pre>

  <h2>X-CSRF-TOKEN</h2>

  <p>In addition to checking for the CSRF token as a POST parameter, the VerifyCsrfToken middleware will also check for the X-CSRF-TOKEN request header. We can, for example, store the token in a HTML meta tag:</p>

  <pre><code class="language-php">
    meta name="csrf-token" content="{ {csrf_token} }"
  </code></pre>

  <p>Once we have created the meta tag, we can instruct a library, like jQuery, to automatically add the token to all request headers. This will provide for simple convenient CSRF protection for AJAX based applications:</p>

  <pre><code class="language-php">
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  </code></pre>

  <h2>X-XSRF-TOKEN</h2>

  <p>Laravel stores the current CSRF token in a XSRF-TOKEN cookie that is included with each response generated by the framework. We can use the cookie value to set the X-XSRF-TOKEN request header.</p>

  <p>This cookie is primarily sent as a convenience since some JavaScript frameworks and libraries, like Angular and Axios, automatically place its value in the X-XSRF-TOKEN header.</p>
@endsection
