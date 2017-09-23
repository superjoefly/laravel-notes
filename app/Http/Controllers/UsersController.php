<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\User;

class UsersController extends Controller
{
    public $user = 'Joey';
    public $age = 37;

    public function index(Request $request)
    {
        // // Retrieve request path
        // $uri = $request->path();
        // dd($uri);



        

        // // Verify incoming request path
        // if ($request->is('user')) {
        //     echo 'Path is a match';
        //     die();
        // }




        // $route = Route::current();
        // dd($route);
        // $name = Route::currentRouteName();
        // $action = Route::currentRouteAction();

        return $this->user;
    }

    public function showProfile()
    {
        return $this->age;
    }

    public function show($id)
    {
        return view('user.profile', ['user' => User::findOrFail($id)]);
    }
}
