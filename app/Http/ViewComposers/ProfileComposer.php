<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\UserRepository;

class ProfileComposer
{
    protected $users;

    // Refers to user class in User Repository
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    // Compose the view using View Facade
    public function compose(View $view)
    {
        $view->with('count', $this->users->count());
    }
}
