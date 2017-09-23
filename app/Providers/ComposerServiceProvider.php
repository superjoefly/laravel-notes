<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Store all view composers

        // Class based composer
        View::composer(
          'user.profile', 'App\Http\ViewComposers\ProfileComposer'
        );

        // // Closure based composer
        // View::composer('dashboard', function ($view) {
        //     // do something...
        // });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
