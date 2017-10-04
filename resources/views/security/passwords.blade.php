@extends('master')

@section('content')
  <h1>Resetting Passwords</h1>

  <h2>Introduction</h2>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>We can use the php artisan make:auth command to quickly scaffold our entire authentication system, including resetting passwords!</p>
  </div>

  <p>Laravel provides convenient methods for sending password reminders and performing password resets.</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Before using Laravel's password reset features, our user must use the Illuminate\Notifications\Notifiable trait.</p>
  </div>

  <h2>Database Considerations</h2>

  <p>To get started, we must first verify that our App\User model implements the Illuminate\Contracts\Auth\CanResetPassword contract. The App\User model included with the Laravel framework already implements this feature, and uses the Illuminate\Auth\Passwords\CanResetPassword trait to include the methods needed to implement the interface.</p>

  <h4>Generating the Reset Token Table Migration</h4>

  <p>Next, a table must be created to store the password reset tokens. This migration table is included with Laravel out of the box, and is located in the database/migrations directory. All we need to do to use it is run our migrations:</p>

  <pre><code class="language-php">
    php artisan migrate
  </code></pre>

  <h2>Routing</h2>

  <p>Laravel includes Auth\ForgotPasswordController and Auth\ResetPasswordController classes that contain the logic necessary to email password reset links and reset user passwords. All of the routes needed to reset passwords can be generated usint the make:auth Artisan command:</p>

  <pre><code class="language-php">
    php artisan make:auth
  </code></pre>

  <h2>Views</h2>

  <p>Laravel generates all of the necessary views for password resets when the make:auth command is executed. These views are placed in the resources/views/auth/passwords directory. We can customize these views in any way we'd like.</p>

  <h2>After Resetting Passwords</h2>

  <p>The ForgotPasswordController included with the framework already includes the logic to send the password reset link emails, while the ResetPasswordController includes the logic to reset user passwords. Once a user's password is reset, the user will automatically be logged into the application and redirected to '/home'. We can customize the redirect location by defining a redirectTo property on the ResetPasswordController:</p>

  <pre><code class="language-php">
    protected $redirectTo = '/dashboard';
  </code></pre>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>By default, password reset tokens expire after one hour. We can change this using the password reset 'expire' option in our config/auth.php file.</p>
  </div>

  <h2>Customization</h2>

  <h4>Authentication Guard Customization</h4>

  <p>We can use our auth.php file to configure multiple "guards" which will be used to define authentication behavior for multiple user tables. We can customize the included ResetPasswordController to use the guard of our choice by overriding the guard() method on the controller. The method should return a guard instance:</p>

  <pre><code class="language-php">
    use Illuminate\Support\Facades\Auth;

    protected function guard()
    {
        return Auth::guard('guard-name');
    }
  </code></pre>

  <h4>Password Broker Customization</h4>

  <p>We can use our auth.php file to configure multiple password "brokers" which can be used to reset passwords on multiple user tables. We can customize the included ForgotPasswordController and ResetPasswordController to use the broker of our choice by overriding the broker() method:</p>

  <pre><code class="language-php">
    use Illuminate\Support\Facades\Password;

    protected function broker()
    {
        return Password::broker('name');
    }
  </code></pre>

  <h4>Reset Email Customization</h4>

  <p>We can easily modify the notification class used to sent the password reset link to the user. To do this, we can override the sendPasswordResetNotification method on our User model. Within this method, we can send the notification using any notification class we choose. The password reset $token is the first argument received by the method:</p>

  <pre><code class="language-php">
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
  </code></pre>
@endsection
