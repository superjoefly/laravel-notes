<?php

namespace App\Repositories;

use App\User;

class UserRepository
{
    // Store all calls to database for User info

    public function count()
    {
        // dd(User::all());
        return User::all();
    }
}
