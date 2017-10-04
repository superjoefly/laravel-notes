@extends('master')

@section('content')
  <h1>Encryption</h1>

  <h2>Introduction</h2>

  <p>Laravel's encrypter uses OpenSSL to provide AES-256 and AES-128 encryption. It is strongly recommended to use Laravel's built-in encryption facilities.</p>

  <h2>Configuration</h2>

  <p>Before we can use Laravel's encrypter, we need to set a key option in the config/app.php file. We can use the php artisan key:generate command to generate this key since this Artisan command will use PHP's secure random bytes generator to build the key. If this value is not properly set, all values encrypted by Laravel will be insecure.</p>

  <h2>Using the Encrypter</h2>

  <h4>Encrypting a Value</h4>

  <p>We can encrypt a value using the encrypt() helper. All encrypted values are encrypted using OpenSSL and the AES-256-CBC cipher, and are signed with a message authentication code to detect any modifications to the encrypted string.</p>

  <pre><code class="language-php">
    namespace App\Http\Controllers;

    use App\User;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;

    class UserController extends Controller
    {
        public function storeSecret(Request $request, $id)
        {
            $user = User::findOrFail($id);

            $user->fill([
                'secret' => encrypt($request->secret)
            ])->save();
        }
    }
  </code></pre>

  <h4>Encrypting Without Serialization</h4>

  <p>Encrypted values are sent through serialize() during encryption, which allows for encryption of objects and arrays. Non-PHP clients receiving encrypted values will need to unserialize() the data. To encrypt and decrypt values without serialization, we can use the encryptString() and decryptString() methods of the Crypt facade:</p>

  <pre><code class="language-php">
    use Illuminate\Support\Facades\Crypt;

    $encrypted = Crypt::encryptString('Hello world.');

    $decrypted = Crypt::decryptString($encrypted);
  </code></pre>

  <h4>Decrypting a Value</h4>

  <p>We can decrypt values using the decrypt() helper. If the value can't be propery decrypted, such as when a MAC is invalid, an Illuminate\Contracts\Encryption\DecryptException will be thrown.</p>

  <pre><code class="language-php">
    use Illuminate\Contracts\Encryption\DecryptException;

    try {
        $decrypted = decrypt($encryptedValue);
    } catch (DecryptException $e) {
        //
    }
  </code></pre>
@endsection
