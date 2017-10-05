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

  <p>Laravel makes it convenient to define the input we expect from the user with the 'signature' property on our commands. The signature property allows us to define the name, arguments and options for the command in a single, expressive, route-like syntax.</p>

  <h3>Arguments</h3>

  <p>All user supplied arguments and options are wrapped in curly braces. In the following example, the command defines one required argment: user:</p>

  <pre><code class="language-php">
    protected $signature = 'email:send {user}';
  </code></pre>

  <p>We can also make arguments optional, and define default values for the arguments:</p>

  <pre><code class="language-php">
    // Optional argument...
    email:send {user?}

    // Optional argument with default value...
    email:send {user=foo}
  </code></pre>

  <h3>Options</h3>

  <p>Options are another form of user input. Options are prefixed by two hyphens (--) when they are specified on the command line. There are two types of options: those that receive a value, and those that do not. Options that don't receive a value serve as a boolean "switch". Following is an example of this type of option:</p>

  <pre><code class="language-php">
    protected $signature = 'email:send {user} {--queue}';
  </code></pre>

  <p>In the above example, the --queue switch can be specified when calling the Artisan command. If the queue switch is passed, the value of the option will be true, otherwise, it will be false:</p>

  <pre><code class="language-php">
    php artisan email:send 1 --queue
  </code></pre>

  <h4>Options with Values</h4>

  <p>Next, we'll look at an option that expects a value. If the user must specify a value for the option, suffix the option name with an = sign:</p>

  <pre><code class="language-php">
    protected $signature = 'email:send {user} {--queue=}';
  </code></pre>

  <p>In this example, a user can pass a value for the option as follows:</p>

  <pre><code class="language-php">
    php artisan email:send 1 --queue=default
  </code></pre>

  <p>We can define default values to options by specifying the default value after the option name:</p>

  <pre><code class="language-php">
    email:send {user} {--queue=default}
  </code></pre>

  <h4>Option Shortcuts</h4>

  <p>To define shortcuts when defining options, we can specify it before the option name and use a | delimiter to seperate the shortcut from the full option name:</p>

  <pre><code class="language-php">
    email:send {user} {--Q|queue}
  </code></pre>

  <h3>Input Arrays</h3>

  <p>To define arguments or options that expect array inputs, we can use the * character:</p>

  <pre><code class="language-php">
    email:send {user*}
  </code></pre>

  <p>The 'user' arguments can be passed, in order, to the command line. The following example will set the value of 'user' to ['foo', 'bar']:</p>

  <pre><code class="language-php">
    php artisan email:send foo bar
  </code></pre>

  <p>When defining an option that expects an array input, each option value passed to the command should be prefixed with the option name:</p>

  <pre><code class="language-php">
    email:send {user} {--id=*}

    php artisan email:send --id=1 --id=2
  </code></pre>

  <h3>Input Descriptions</h3>

  <p>We can assign descriptions to input arguments and options by separating the parameter from the description using a colon. If we need more room for the description, we can spread the definition accross multiple lines:</p>

  <pre><code class="language-php">
    protected $signature = 'email:send
                            {user : The ID of the user}
                            {--queue= : Whether the job should be queued}';
  </code></pre>

  <h2>Command I/O</h2>

  <h3>Retrieving Input</h3>

  <p>We can use the argument() and option() method to access the values for the arguments and options accepted by the command:</p>

  <pre><code class="language-php">
    public function handle()
    {
        $userId = $this->argument('user');

        //
    }
  </code></pre>

  <p>We can use the arguments() method to retrieve all of the arguments as an array:</p>

  <pre><code class="language-php">
    $arguments = $this->arguments();
  </code></pre>

  <p>We can retrieve options using the option() method. To retrieve all of the options as an array, we can use the options() method:</p>

  <pre><code class="language-php">
    // Retrieve a specific option...
    $queueName = $this->option('queue');

    // Retrieve all options...
    $options = $this->options();
  </code></pre>

  <p>If the argument or option doesn't exist, null will be returned.</p>

  <h3>Prompting for Input</h3>

  <p>We can also ask a user to provide input during the execution of a command using the ask() method. This will prompt the user with the given question, accept their input, and then return the user's input back to the command:</p>

  <pre><code class="language-php">
    public function handle()
    {
        $name = $this->ask('What is your name?');
    }
  </code></pre>

  <p>The secret() method is similar to the ask() method, except the user's input will not be visible to them as they type in the console. This method is useful when asking for sensitive information like a password:</p>

  <pre><code class="language-php">
    $password = $this->secret('What is the password?');
  </code></pre>

  <h4>Asking for Confirmation</h4>

  <p>We can use the confirm() method to ask for a simple confirmation. By default, this method returns false, however, if the user enters 'y' or 'yes' in response to the prompt, the method will return true:</p>

  <pre><code class="language-php">
    if ($this->confirm('Do you wish to continue?')) {
        //
    }
  </code></pre>

  <h4>Auto-Completion</h4>

  <p>We can use the anticipate() method to provide auto-completion for possible choices. The user will be able to choose any answer regardless of the auto-completion hints:</p>

  <pre><code class="language-php">
    $name = $this->anticipate('What is your name?', ['Taylor', 'Dayle']);
  </code></pre>

  <h4>Multiple Choice Questions</h4>

  <p>In order to give the user a predefined set of choices, we can use the choice() method. We can set the default value to be returned if no option is chosen:</p>

  <pre><code class="language-php">
    $name = $this->choice('What is your name?', ['Taylor', 'Dayle'], $default);
  </code></pre>

  <h3>Writing Output</h3>

  <p>To send output to the console, we can use the line(), info(), comment(), question(), and error() methods. Each of these methods will use appropriate ANSI colors for their purpose. In the following example, we will display some general information to the user...typically, the info() method will display in the console as green text:</p>

  <pre><code class="language-php">
    public function handle()
    {
        $this->info('Display this on the screen');
    }
  </code></pre>

  <p>To display an error message, we can use the error() method, which is typically displayed in red:</p>

  <pre><code class="language-php">
    $this->error('Something went wrong!');
  </code></pre>

  <p>We can use the line() method to display plain, uncolored text:</p>

  <pre><code class="language-php">
    $this->line('Display this on the screen');
  </code></pre>

  <h4>Table Layouts</h4>

  <p>We can use the table() method to correctly format multiple rows/columns of data. Simply pass in the headers and rows to the method. The width and height is dynamically calculated based on the given data:</p>

  <pre><code class="language-php">
    $headers = ['Name', 'Email'];

    $users = App\User::all(['name', 'email'])->toArray();

    $this->table($headers, $users);
  </code></pre>

  <h4>Progress Bar</h4>

  <p>It can be helpful to display a progress bar for long-running tasks. To do this, first define the total number of steps the process will iterate through. Then, advance the progress bar after processing each item:</p>

  <pre><code class="language-php">
    $users = App\User::all();

    $bar = $this->output->createProgressBar(count($users));

    foreach ($users as $user) {
        $this->performTask($user);

        $bar->advance();
    }

    $bar->finish();
  </code></pre>

  <h2>Registering Commands</h2>

  <p></p>
@endsection
