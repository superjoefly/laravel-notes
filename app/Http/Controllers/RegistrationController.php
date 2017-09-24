<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function create()
    {
        return view('registration.create');
    }

    public function store(Request $request)
    {
        // $input = $request->all();
        // return $input;

        // $name = $request->input('name');
        // return $name

        // Set default value:
        // $lastname = $request->input('lastname', 'Atwood');
        // return $lastname;

        // // Array Inputs
        // $toys = $request->input('toys');
        // return $toys;

        // // Query String
        // $name = $request->query('name', 'Hellen');
        // return $name;

        // // Dynamic Properties
        // $name = $request->name;
        // return $name;

        // // Only and Except
        // // $input = $request->only('name', 'password');
        // // return $input;
        //
        // $input = $request->except('password');
        // return $input;

        // // Flashing Input to the Session
        // $request->flash();
        // $request->flashOnly(['username', 'email']);
        // $request->flashExcept('password');
        //
        // // Flashing Input then Redirecting
        // return redirect('form')->withInput();
        // return redirect('form')->withInput(
        //   $request->except('password')
        // );
        //
        // // Old Input
        // $username = $request->old('username');

        // // Cookies
        // $value= $request->cookie('name');
        // dd($value);

        return redirect()->home();
    }
}
