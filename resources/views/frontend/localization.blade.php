@extends('master')

@section('content')
  <h1>Localization</h1>

  <h2>Introduction</h2>

  <p>Laravel's localization features provide a convenient way to retrieve strings in various languages, allowing us to easily support multiple languages within the application. Language strings are stored in files within the resources/lang directory. Within this directory there should be a sub directory for each language supported by the application:</p>

  <pre><code class="language-php">
    /resources
    /lang
        /en
            messages.php
        /es
            messages.php
  </code></pre>

  <p>All language files simply return an array of keyed strings. For example:</p>

  <pre><code class="language-php">
    return [
    'welcome' => 'Welcome to our application'
    ];
  </code></pre>

  <h3>Configuring the Locale</h3>

  <p>The default language for the application is stored in the config/app.php config file. Of course, we can modify this value to suit the needs of the application. We can also change the active language at runtime using the setLocale() method on the App facade:</p>

  <pre><code class="language-php">
    Route::get('welcome/{locale}' function($locale) {
      App::setLocale($locale);
      // ...
    });
  </code></pre>

  <p>We can configure a "fallback" language, which will be used when the active language does not contain a given translation string. The "fallback" language is also configured in the config/app.php config file:</p>

  <pre><code class="language-php">
    'fallback_locale' => 'en',
  </code></pre>

  <h4>Determining the Current Locale</h4>

  <p>We can use the getLocale() and isLocale() methods on the App facade to determine the current locale or check if the locale is a given value:</p>

  <pre><code class="language-php">
    $locale = App::getLocale();

    if (App::isLocale('en')) {
      // ...
    }
  </code></pre>

  <h2>Defining Translation Strings</h2>

  <h3>Using Short Keys</h3>

  <p>All language files in the resources/lang directory return an array of keyed strings:</p>

  <pre><code class="language-php">
    // resources/lang/en/messages.php

    return [
    'welcome' => 'Welcome to our application'
    ];
  </code></pre>

  <h3>Using Translation Keys as Strings</h3>

  <p>For applications with heavy translation requirements, defining every string with a "short key" can become quickly confusing when referencing them in our views. For this reason, Laravel also provides support for defining translation strings using the "default" translation of the string as the key.</p>

  <p>Translation files that use translation strings as keys are stored as JSON files in the resources/lang directory. For example, an application with a Spanish translation will need a resources/lang/es.json file:</p>

  <pre><code class="language-php">
    {
      "I love programming." : "Me encanta programar."
    }
  </code></pre>

  <h2>Retrieving Translation Strinigs</h2>

  <p></p>

@endsection
