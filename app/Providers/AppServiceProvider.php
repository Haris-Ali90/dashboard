<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         View::composer('*', function($view){
            $userPermissoins = [];

            /*checking user is login or not */
            if(Auth::check())
            {
                $auth_user = Auth::user();
                $userPermissoins = $auth_user->getPermissions();

            }
            view()->share('userPermissoins', $userPermissoins);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
