<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;

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
        Paginator::defaultView('pagination::default');
        Paginator::defaultSimpleView('pagination::simple-default');

        Blade::directive('price', function ($expression) {
            return "<?php echo number_format(($expression), 2, ',', '.') . config('shop.currency_sign'); ?>";
        });

        Blade::directive('markdown', function ($expression) {
            return "<?php echo GrahamCampbell\Markdown\Facades\Markdown::convertToHtml($expression); ?>";
        });
    }
}
