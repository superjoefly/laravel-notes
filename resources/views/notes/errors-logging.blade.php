@extends('master')

@section('content')
  <h1>Errors and Logging</h1>

  <h2>Introduction</h2>

  <p>When starting a new Laravel project, error and exception handling is already configured for us. The App\Exceptions\Handler class is where all exceptions triggered for the application are logged and then rendered back to the user.</p>

  <p>Laravel uses the Monolog library for logging. This library provides support for a variety of powerful log handlers. Laravel configures several of these handlers for us, allowing us to choose between a single log file, rotating log files, or writing error information to the system log.</p>

  <h2>Configuration</h2>

  <h3>Error Detail</h3>

  <p>The debug option in the config/app.php configuration file determines how much information about an error is actually displayed to the user. By default, this option is set to respect the value of the APP_DEBUG environment variable, which is stored in the .env file.</p>

  <p>For local development, we can set the APP_DEBUG environment variable to true. In a production environment, this value should always be false. If the value is set to true in production, we risk exposing sensitive configuration values to the application's end user.</p>

  <h3>Log Storage</h3>

  <p>Out of the box, Laravel supports writing log information to single files, daily files, the syslog, and the errorlog. To configure which storage mechanism Laravel uses, we can modify the log option in config/app.php. For example, to use daily log files, we can set the log value in our app configuration file to daily:</p>

  <pre><code class="language-php">
    'log' => 'daily'
  </code></pre>

  <h4>Maximum Daily Log Files</h4>

  <p>When using the 'daily' log mode, Laravel retains five days of log files by default. To adjust the number of retained files, we can add a log_max_files config value to the app config file:</p>

  <pre><code class="language-php">
    'log_max_files' => 30
  </code></pre>

  <h3>Log Security Levels</h3>

  <p>When using Monolog, log messages can have different levels of security. By default, Laravel writes all logs to storage. In a production environment, we may want to configure the minimum severity that should be logged by adding the log_level option to the app.php config file.</p>

  <p>Once configured, Laravel will log all levels greater than or equal to the specified severity. For example, a default log level of error will log error, critical, alert and emergency messages:</p>

  <pre><code class="language-php">
    'log_level' => env('APP_LOG_LEVEL', 'error'),
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Monolog recognizes the following severity levels - from least severe to most severe: debug, info, notice, warning, error, critical, alert, emergency.</p>
  </div>

  <h3>Custom Monolog Configuration</h3>

  <p>We can use the configureMonologUsing() method to customize the Monolog configuration. To do this, we can place a call to this method in the bootstrap/app.php file right before the $app variable is returned by the file:</p>

  <pre><code class="language-php">
    $app->configureMonologUsing(function($monolog) {
      $monolog->pushHandler(...);
    });

    return $app;
  </code></pre>

  <h4>Customizing the Channel Name</h4>

  <p>By default, Monolog is instantiated with a name that matches the current environment, such as production or local. To change this value, we can add the log_channel option to the app.php config file:</p>

  <pre><code class="language-php">
    'log_channel' => env('APP_LOG_CHANNEL', 'my-app-name'),
  </code></pre>

  <h2>The Exception Handler</h2>

  <h3>The Report Method</h3>

  <p></p>

@endsection
