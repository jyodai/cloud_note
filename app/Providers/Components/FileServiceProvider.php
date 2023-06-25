<?php

namespace App\Providers\Components;

use Illuminate\Support\ServiceProvider;

class Comp_FileServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'comp_file',
            'App\Http\Components\Comp_File'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
