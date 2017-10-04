@extends('master')

@section('content')
  <h1>Hashing</h1>

  <h2>Introduction</h2>

  <p>Laravel's Hash facade provides secure Bcrypt hashing for storing user passwords. When using the built in LoginController and RegisterController classes that are included with the Laravel application, they will automatically use Bcrypt for registration and authentication.</p>

  <div class="w3-panel w3-border-blue w3-leftbar w3-pale-blue">
    <p>Bcrypt's "work factor" is adjustable, meaning that the time it takes to generate a hash can be increased as hardware power increases.</p>
  </div>

  <h2>Basic Usage</h2>

  <p>We can hash a password by calling the make() method on the Hash facade. Following is an example of updating a password using the make() method:</p>

  <pre><code class="language-php">
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use App\Http\Controllers\Controller;

    class UpdatePasswordController extends Controller
    {
        public function update(Request $request)
        {
            // Validate the new password length...

            $request->user()->fill([
                'password' => Hash::make($request->newPassword)
            ])->save();
        }
    }
  </code></pre>

  <p>The make() method also allows us to manage the work factor of the bcrypt hashing algorithm using the 'rounds' option, however, the default is acceptable for most applications:</p>

  <pre><code class="language-php">
    $hashed = Hash::make('password', [
        'rounds' => 12
    ]);
  </code></pre>

  <h4>Verifying a Password Against a Hash</h4>

  <p>The check() method allows us to verify that a given plain text string corresponds to a given hash. However, when using the LoginController included with Laravel, we will probably not need to use this directly, because it will be called automatically:</p>

  <pre><code class="language-php">
    if (Hash::check('plain-text', $hashedPassword)) {
        // The passwords match...
    }
  </code></pre>

  <h4>Checking if a Password Needs to be Rehashed</h4>

  <p>The needsRehash() function allows us to determine if the work factor used by the hasher has changed since the password was hashed:</p>

  <pre><code class="language-php">
    if (Hash::needsRehash($hashed)) {
        $hashed = Hash::make('plain-text');
    }
  </code></pre>
@endsection
