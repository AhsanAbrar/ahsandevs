<?php

namespace AhsanDevs;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AhsanDevsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        // This is temporary, we will move this to support.
        Blade::directive('viteTags', function (string $expression) {
            return "<?php echo app(AhsanDev\Support\ViteNew::class)($expression); ?>";
        });

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Console\InstallCommand::class,
            Console\PackageCommand::class,
            Console\BaseCommand::class,
        ]);
    }
}
