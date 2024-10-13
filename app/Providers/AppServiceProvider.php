<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use DB;
use Illuminate\Support\Facades\Auth;

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

            Blade::directive('formatdate', function ($date) {
                return "<?php echo Carbon\Carbon::parse($date)->format(get_option('app_date_format')); ?>";
            });
        } catch (\Exception $e) {
            //
        }
    }
}
