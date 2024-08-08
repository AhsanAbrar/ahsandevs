<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\StubHelpers;
use AhsanDevs\Console\Concerns\StubReplaceHelpers;
use AhsanDevs\ModifyFile;

class ResourceCommand extends Command
{
    use StubReplaceHelpers, StubHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:resource {name : The resource name} {package : The span package dir name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Resource';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('ahsandevs:controller', [
            'name' => $this->argument('name').'Controller',
            'package' => $this->argument('package'),
        ]);

        $this->call('ahsandevs:filters', [
            'name' => $this->argument('name').'Filters',
            'package' => $this->argument('package'),
        ]);

        $this->call('ahsandevs:request', [
            'name' => $this->argument('name').'Request',
            'package' => $this->argument('package'),
        ]);

        $this->call('ahsandevs:model', [
            'name' => $this->argument('name'),
            'package' => $this->argument('package'),
            '--migration' => true,
        ]);

        // $this->addImportToApiRoutes();
        // $this->addRouteToApiRoutes();

        $this->info('Resource generated successfully.');
    }

    /**
     * Modify package api.php file.
     */
    protected function modifyApiRoutes()
    {
        $this->addImport();
        $this->addRoute();
    }

    /**
     * Add the controller import to the api.php file.
     */
    protected function addImport(): void
    {
        new ModifyFile(
            pattern: 'use\s.*;',
            add: "use {$this->rootNamespace()}\\Http\\Controllers\\Api\\{$this->pascalName()}Controller;",
            file: $this->packagePath('routes/api.php'),
            sort: true,
        );
    }

    /**
     * Add the route line to the api.php file.
     */
    protected function addRoute(): void
    {
        new ModifyFile(
            pattern: "Route::resource\('.*',\s.*::class\);",
            add: "Route::resource('{$this->pluralName()}', {$this->pascalName()}Controller::class);",
            file: $this->packagePath('routes/api.php'),
            sort: true,
        );
    }
}
