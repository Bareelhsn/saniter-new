<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // if (auth()->user()->is_active != 1 ) {
        //     toast('Terjadi kesalahan!','error');
        //     auth()->logout();
        //     redirect()->route('login');
        // }
    }
}
