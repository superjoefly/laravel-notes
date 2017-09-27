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

  <p>We can retrieve lines from translation files using the __() helper function. The __() method accepts the file and key of the translation string as its first argument. In the following example, we will retrieve the "welcome" translation string from the resources/lang/messages.php language file:</p>

  <pre><code class="language-php">
    echo __('messages.welcome');

    echo __('I love programming.');
  </code></pre>

  <p>If using the Blade templating engine, we can use the curly brace syntax to echo out the translation string, or use the &#64lang directive:</p>

  <pre><code class="language-php">
    { { __('messages.welcome') } }

    &#64lang('messages.welcome')
  </code></pre>

  <p>If the specified translation string does not exist, the __function() will simply return the string key. In the example above, 'messages.welcome' would be returned.</p>

  <h3>Replacing Parameters in Translation Strings</h3>

  <p>We can also define place-holders in our translation strings. All place-holders are prefixed with a ":" . For example, to define a welcome message with a placeholder name:</p>

  <pre><code class="language-php">
    'welcome' => 'Welcome, :name',
  </code></pre>

  <p>To replace the place-holders when retrieving a translation string, pass an array of replacements as the second argument to the __function():</p>

  <pre><code class="language-php">
    echo __('messages.welcome', ['name' => 'dayle']);
  </code></pre>

  <p>If the place-holder contains all capital letters, or only has its first letter capitalized, the translated value will be capitalized accordingly:</p>

  <pre><code class="language-php">
    'welcome' => 'Welcome, :NAME', // Welcome, DAYLE
    'goodbye' => 'Goodbye, :Name', // Goodbye, Dayle
  </code></pre>

  <h3>Pluralization</h3>

  <p>Pluralization is a complex problem, as different languages have a variety of complex rules for pluralization. By using a "pipe" character, we can distinguish singular and plural forms of a sting:</p>

  <pre><code class="language-php">
    'apples' => 'There is one apple|There are many apples',
  </code></pre>

  <p>We can create more complex pluralization rules which specify translation strings for multiple number ranges:</p>

  <pre><code class="language-php">
    'apples' => '{0} There are none|[1,19] There are some|[20,*] There are many',
  </code></pre>

  <p>After defining a pluralization string that has pluralization options, we can use the trans_choice() function to retrieve the line for a given "count". In the following example, since the count is greater than one, the plural form of the translation string is returned:</p>

  <pre><code class="language-php">
    echo trans_choice('message.apples', 10);
  </code></pre>

  <h2>Overriding Package Language Files</h2>

  <p></p>

@endsection
