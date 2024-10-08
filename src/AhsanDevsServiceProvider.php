<?php

namespace AhsanDevs;

use Illuminate\Database\Eloquent\Model;
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

        Blade::directive('appData', function () {
            return "<?php echo app(AhsanDev\Support\AppDataDirective::class)(); ?>";
        });

        Model::unguard();

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Console\InstallCommand::class,
            Console\BaseCommand::class,

            Console\PackageCommand::class,
            Console\SetDefaultPackageCommand::class,
            Console\DefaultPackageCommand::class,

            Console\ResourceCommand::class,
            Console\ResourceLaravelCommand::class,

            Console\ControllerCommand::class,
            Console\FilterCommand::class,
            Console\FiltersCommand::class,
            Console\MigrationCommand::class,
            Console\ModelCommand::class,
            Console\RequestCommand::class,

            Console\VueViewCommand::class,
        ]);
    }
}
