@extends('master')

@section('content')
  <h1>Artisan Console</h1>

  <h2>Introduction</h2>

  <p>Artisan is a command line interface included with Laravel. It provides helpful commands that can assist in building an application. We can view a list of artisan commands using the 'list' command:</p>

  <pre><code class="language-php">
    php artisan list
  </code></pre>

  <p>Each command also has a help screen that displays and describes the command's available arguments and options. To view the help screen, prepend the cammand with 'help':</p>

  <pre><code class="language-php">
    php artisan help migrate
  </code></pre>

  <h4>Laravel REPL</h4>

  <p>Laravel applications also include Tinker, a REPL powered by the PsySH package. Tinker allows us to interact with our application using the command line, including Eloquent ORM, jobs, events and more. To enter the Tinker environment, run the 'tinker' Artisan command:</p>

  <pre><code class="language-php">
    php artisan tinker
  </code></pre>

  <h2>Writing Commands</h2>

  <p>We can build our own custom Artisan commands. Commands are typically stored in the app/Console/Commands directory, however, we can choose our own location as long as the commands can be loaded by Composer.</p>

  <h3>Generating Commands</h3>

  <p>To create a new command, we can use the make:command Artisan command. This will create a new command class in the app/Console/Commands directory. This directory will be created the first time we run the make:command Artisan command. The generated command will include a the default set of properties and methods that are present on all commands:</p>

  <pre><code class="language-php">
    php artisan make:command SendEmails
  </code></pre>

  <h3>Command Structure</h3>

  <p>After generating a command, we need to fill the signature and description properties of the class. These values will be displayed on the 'list' screen. The handle() method will be called when the command is executed. We can place our command logic in this method.</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>It is a good practice to keep our console commands light, letting them defer to application services to accomplish their tasks.</p>
  </div>

  <p>We can inject any dependencies we need into the commands constructor. The Laravel service container will automatically inject all dependencies type-hinted in the constructor:</p>

  <pre><code class="language-php">
    namespace App\Console\Commands;

    use App\User;
    use App\DripEmailer;
    use Illuminate\Console\Command;

    class SendEmails extends Command
    {

        protected $signature = 'email:send {user}';

        protected $description = 'Send drip e-mails to a user';

        protected $drip;

        public function __construct(DripEmailer $drip)
        {
            parent::__construct();

            $this->drip = $drip;
        }

        public function handle()
        {
            $this->drip->send(User::find($this->argument('user')));
        }
    }
  </code></pre>

  <h3>Closure Commands</h3>

  <p>Closure based commands provide an alternative to defining console commands as classes. Within the commands() method of the app/Console/Kernel.php file, Laravel loads the routes/console.php file:</p>

  <pre><code class="language-php">
    protected function commands()
    {
        require base_path('routes/console.php');
    }
  </code></pre>

  <p>This file defines console based entry points (routes) into the application. Within this file, we can define all of our closure based routes using the Artisan::command method. The command() method accepts two arguments: the command signature and a closure which receives the commands arguments and options:</p>

  <pre><code class="language-php">
    Artisan::command('build {project}', function ($project) {
        $this->info("Building {$project}!");
    });
  </code></pre>

  <p>The closure is bound to the underlying command instance, so we have full access to all of the helper methods we would typically be able to access on a full command class.</p>

  <h4>Type-Hinting Dependencies</h4>

  <p>Command closures can also type-hint additional dependencies that we want to resolve out of the service containter:</p>

  <pre><code class="language-php">
    use App\User;
    use App\DripEmailer;

    Artisan::command('email:send {user}', function (DripEmailer $drip, $user) {
        $drip->send(User::find($user));
    });
  </code></pre>

  <h4>Closure Command Descriptions</h4>

  <p>When defining closure based commands, we can use the describe() method to add a description to the command. This will be displayed when running the 'php artisan list' or 'php artisan help' commands:</p>

  <pre><code class="language-php">
    Artisan::command('build {project}', function ($project) {
        $this->info("Building {$project}!");
    })->describe('Build the project');
  </code></pre>

  <h2>Defining Input Expectations</h2>

  <p></p>
@endsection
