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

        $this->call('ahsandevs:vue-view', [
            'name' => 'Index',
            'package' => $this->argument('package'),
            'dir' => $this->pluralName(),
        ]);

        $this->call('ahsandevs:vue-view', [
            'name' => 'Form',
            'package' => $this->argument('package'),
            'dir' => $this->pluralName(),
        ]);

        $this->addImportToApiRoutes();
        $this->addRouteToApiRoutes();

        $this->addImportToVueRoutes();
        $this->addRouteToVueRoutes();
        $this->addSidebarItemToVue();

        $this->info('Resource generated successfully.');
    }

    /**
     * Add the controller import to the api.php file.
     */
    protected function addImportToApiRoutes(): void
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
    protected function addRouteToApiRoutes(): void
    {
        new ModifyFile(
            pattern: "Route::resource\('.*',\s.*::class\);",
            add: "Route::resource('{$this->pluralName()}', {$this->pascalName()}Controller::class);",
            file: $this->packagePath('routes/api.php'),
            sort: true,
        );
    }

    /**
     * Add the controller import to the api.php file.
     */
    protected function addImportToVueRoutes(): void
    {
        new ModifyFile(
            pattern: "import [a-zA-Z0-9]+ from 'View\/.*.vue'",
            add: "import {$this->pascalName()}Index from 'View/{$this->pluralName()}/Index.vue'",
            file: $this->packagePath('resources/js/router/routes.ts'),
            sort: true,
        );
    }

    /**
     * Add the route line to the api.php file.
     */
    protected function addRouteToVueRoutes(): void
    {
        new ModifyFile(
            pattern: "\s{2}{ path: '\/[a-z-]+', name: '[a-zA-Z0-9]+Index', component: [a-zA-Z0-9]+Index },",
            add: "  { path: '/{$this->pluralName()}', name: '{$this->pascalName()}Index', component: {$this->pascalName()}Index },",
            file: $this->packagePath('resources/js/router/routes.ts'),
            sort: true,
        );
    }

    /**
     * Add the route line to the api.php file.
     */
    protected function addSidebarItemToVue(): void
    {
        new ModifyFile(
            pattern: "\s{2}{ label: '[a-zA-Z0-9-_]+', uri: '\/[a-z0-9-]+', icon: [a-zA-Z0-9-_]+Icon },",
            add: "  { label: '{$this->pluralPascalName()}', uri: '/{$this->pluralName()}', icon: FolderIcon },",
            file: $this->packagePath('resources/js/composables/sidebar-nav.ts'),
        );
    }
}
