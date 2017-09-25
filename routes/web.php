<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Temporary
// Bind to Service Container
// App::singleton('App\Billing\Stripe', f`unction () {
//     return new \App\Billing\Stripe(config('services.stripe.secret'));
// });

// Resolve instance of Stripe class out of Service Container
// $stripe = App::make('App\Billing\Stripe');
// $stripe = resolve('App\Billing\Stripe');

// Die and Dump to page
// dd(resolve('App\Billing\Stripe'));

// use Illuminate\Support\Facades\Cache;

// // Determine if View Exists
// use Illuminate\Support\Facades\View;
//
// if (View::exists('notes.controllers')) {
//     echo 'True';
// };


Route::get('/', function () {
    return view('welcome');
});

// If only returning a view
Route::view('/welcome', 'welcome');





// Testing Middleware (CheckAge)

// If age <=200
Route::view('/home', 'home')->name('home');

// If age >200 ('age' is the key for the middleware)
Route::post('/agecheck', function () {
    return view('/agecheck');
})->middleware('age');





// Routes for Notes

// Route that returns a view
Route::get('notes/routing', function () {
    return view('notes.routing');
});

Route::get('notes/middleware', function () {
    return view('notes.middleware');
});

Route::get('notes/csrf', function () {
    return view('notes.csrf');
});

Route::get('notes/controllers', function () {
    return view('notes.controllers');
});

Route::get('notes/requests', function () {
    return view('notes.requests');
});

Route::get('notes/responses', function () {
    return view('notes.responses');
});

Route::get('notes/views', function () {
    return view('notes.views');
});

Route::get('notes/urls', function () {
    return view('notes.urls');
});

Route::get('notes/sessions', function () {
    return view('notes.sessions');
});

Route::get('notes/validation', function () {
    return view('notes.validation');
});

Route::get('notes/errors-logging', function () {
    return view('notes.errors-logging');
});






// Basic route
Route::get('/foo', function () {
    return 'Hello World!';
});

// Route using controller with index method
Route::get('/user', 'UsersController@index');

// // Route with parameter
// Route::get('/user/{id}', function ($id) {
//     return 'User ' . $id;
// });
//
// // Optional Parameter (include default value)
// Route::get('user/{name?}', function ($name = null) {
//     return $name;
// });
//
// Route::get('user/{name?}', function ($name = "John") {
//     return $name;
// });
//
// // Route with multiple paramters
// Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
//     return 'Post ' . $postId . ' Comment ' . $commentId;
// });

// Route::get('/user/profile', 'UsersController@showProfile')->name('profile');




// // Available Router Methods (HTTP VERBS)
// Route::get($uri, $callback);
// Route::post($uri, $callback);
// Route::put($uri, $callback);
// Route::patch($uri, $callback);
// Route::delete($uri, $callback);
// Route::options($uri, $callback);

// // Register route that responds to multiple HTTP verbs:
// Route::match(['get', 'post'], $uri, $callback);
//
// // Register route that responds to ANY HTTP verb
// Route::any($uri, $callback);




// For testing (see unit test)
// Route::get('/cache', function () {
//     return Cache::get('key');
// });``


// Contollers
Route::get('user/{id}', 'UsersController@show');

// Resourceful Route
Route::resource('photo', 'PhotoController');


// Requests (register)
Route::get('/register', 'RegistrationController@create');
Route::post('/register', 'RegistrationController@store');

// Validation (post)
Route::get('post/create', 'PostController@create');
Route::post('/post', 'PostController@store');

Route::get('/posts', 'PostController@index');


// Adminer
Route::any('adminer', '\Miroc\LaravelAdminer\AdminerAutologinController@index');
