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


// NOTES NOTES NOTES


// BASICS Notes

Route::get('basics/routing', function () {
    return view('basics.routing');
});

Route::get('basics/middleware', function () {
    return view('basics.middleware');
});

Route::get('basics/csrf', function () {
    return view('basics.csrf');
});

Route::get('basics/controllers', function () {
    return view('basics.controllers');
});

Route::get('basics/requests', function () {
    return view('basics.requests');
});

Route::get('basics/responses', function () {
    return view('basics.responses');
});

Route::get('basics/views', function () {
    return view('basics.views');
});

Route::get('basics/urls', function () {
    return view('basics.urls');
});

Route::get('basics/sessions', function () {
    return view('basics.sessions');
});

Route::get('basics/validation', function () {
    return view('basics.validation');
});

Route::get('basics/errors-logging', function () {
    return view('basics.errors-logging');
});


// FRONTEND Notes

Route::get('frontend/blade-templates', function () {
    return view('frontend.blade-templates');
});

Route::get('frontend/localization', function () {
    return view('frontend.localization');
});

Route::get('frontend/scaffolding', function () {
    return view('frontend.scaffolding');
});

Route::get('frontend/compiling', function () {
    return view('frontend.compiling');
});


// SECURITY notes

Route::get('security/authentication', function () {
    return view('security.authentication');
});

Route::get('security/api-auth', function () {
    return view('security.api-auth');
});

Route::get('security/authorization', function () {
    return view('security.authorization');
});

Route::get('security/encryption', function () {
    return view('security.encryption');
});

Route::get('security/hashing', function () {
    return view('security.hashing');
});

Route::get('security/passwords', function () {
    return view('security.passwords');
});


// Advanced notes

Route::get('advanced/artisan', function () {
    return view('advanced.artisan');
});










// SERVICE PROVIDERS
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
    return view('welcome', compact('greeting', 'message'));
});

// If only returning a view
Route::view('/welcome', 'welcome');


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




// TESTING (see unit test)
// Route::get('/cache', function () {
//     return Cache::get('key');
// });




// TESTING MIDDLEWARE (CheckAge)

// If age <=200
Route::view('/home', 'home')->name('home');

// If age >200 ('age' is the key for the middleware)
Route::post('/agecheck', function () {
    return view('/agecheck');
})->middleware('age');




// CONTROLLERS
Route::get('user/{id}', 'UsersController@show');

// RESOURCEFUL ROUTE
Route::resource('photo', 'PhotoController');


// REQUESTS (register)
Route::get('/register', 'RegistrationController@create');
Route::post('/register', 'RegistrationController@store');

// VALIDATION (post)
Route::get('post/create', 'PostController@create');
Route::post('/post', 'PostController@store');
Route::get('/posts', 'PostController@index');


// BLADE TEMPLATES
Route::get('blade', function () {
    return view('child', ['name' => 'Joey']);
});










// ADMINER
Route::any('adminer', '\Miroc\LaravelAdminer\AdminerAutologinController@index');
