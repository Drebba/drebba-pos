<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);

        Blade::include('backend.layouts.particles.card-footer-buttons-create', 'create');
        Blade::include('backend.layouts.particles.card-footer-buttons-edit', 'edit');

        try {
            $connection = DB::connection()->getPdo();
            if ($connection){
                $allOptions = [];
                $allOptions['general_settings'] = Settings::all()->pluck('option_value', 'option_key')->toArray();
                config($allOptions);
            }
        } catch (\Exception $e) {
            //
        }
    }
}
