<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\StubHelpers;
use AhsanDevs\Console\Concerns\StubReplaceHelpers;
use AhsanDevs\ModifyFile;
use AhsanDevs\ModifyItems;
use Illuminate\Support\Facades\File;

class ResourceCommand extends Command
{
    use StubReplaceHelpers, StubHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:resource {name : The resource name} {package : The span package dir name}
                            {--l|laravel : Generate back-end laravel resource only}
                            {--f|vue : Generate front-end vue resource only}
                            {--i|invokable : Generate invokable resource only}
                            {--s|save : Generate save resource only}';

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
        if ($this->option('save')) {
            $this->generateSaveResource();
        } elseif ($this->option('invokable')) {
            $this->generateInvokableResource();
        } else {
            $this->generateResource();
        }
    }

    /**
     * Add the controller import to the api.php file.
     */
    protected function generateResource(): void
    {
        if (!$this->option('vue')) {
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

            $this->addImportToApiRoutes();
            $this->addRouteToApiRoutes();

            $this->addPermissionsToSeeder();
            $this->addTranslationsToYaml();
            $this->addTranslationsToJson();
        }

        if (!$this->option('laravel')) {
            $this->call('ahsandevs:vue-view', [
                'name' => 'Index',
                'package' => $this->argument('package'),
                '--dir' => $this->pluralName(),
                '--resource' => $this->argument('name'),
            ]);

            $this->call('ahsandevs:vue-view', [
                'name' => 'Form',
                'package' => $this->argument('package'),
                '--dir' => $this->pluralName(),
                '--resource' => $this->argument('name'),
            ]);

            $this->addImportToVueRoutes();
            $this->addRouteToVueRoutes();
            $this->addSidebarFolderIconToVue();
            $this->addSidebarItemToVue();
        }

        $this->info('Resource generated successfully.');
    }

    /**
     * Add the controller import to the api.php file.
     */
    protected function generateSaveResource(): void
    {
        if (!$this->option('vue')) {
            $this->call('ahsandevs:controller', [
                'name' => $this->argument('name').'Controller',
                'package' => $this->argument('package'),
                '--save' => true,
            ]);

            $this->call('ahsandevs:request', [
                'name' => $this->argument('name').'Request',
                'package' => $this->argument('package'),
            ]);

            $this->addImportToApiRoutes();
            $this->addSaveRouteToApiRoutes();
        }

        if (!$this->option('laravel')) {
            $this->call('ahsandevs:vue-view', [
                'name' => 'Form',
                'package' => $this->argument('package'),
                '--resource' => $this->argument('name'),
            ]);
        }

        $this->info('Save resource generated successfully.');
    }

    /**
     * Add the controller import to the api.php file.
     */
    protected function generateInvokableResource(): void
    {
        $this->call('ahsandevs:controller', [
            'name' => $this->argument('name').'Controller',
            'package' => $this->argument('package'),
            '--invokable' => true,
        ]);

        $this->addImportToApiRoutes();
        $this->addInvokableRouteToApiRoutes();

        $this->info('Invokable resource generated successfully.');
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
     * Add the route line to the api.php file.
     */
    protected function addSaveRouteToApiRoutes(): void
    {
        new ModifyFile(
            pattern: "Route::resource\('.*',\s.*::class\)->only\(\['create', 'store'\]\);",
            add: "Route::resource('{$this->pluralName()}', {$this->pascalName()}Controller::class)->only(['create', 'store']);",
            file: $this->packagePath('routes/api.php'),
            sort: true,
        );
    }

    /**
     * Add the route line to the api.php file.
     */
    protected function addInvokableRouteToApiRoutes(): void
    {
        new ModifyFile(
            pattern: "Route::get\('.*',\s.*::class\);",
            add: "Route::get('{$this->pluralName()}', {$this->pascalName()}Controller::class);",
            file: $this->packagePath('routes/api.php'),
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

    /**
     * Add the route line to the api.php file.
     */
    protected function addSidebarFolderIconToVue(): void
    {
        $this->replace(
            "import { HomeIcon, UserIcon } from '@heroicons/vue/24/outline'",
            "import { FolderIcon, HomeIcon, UserIcon } from '@heroicons/vue/24/outline'",
            $this->packagePath('resources/js/composables/sidebar-nav.ts'),
        );
    }

    /**
     * Add the permissions to the seeder.
     */
    protected function addPermissionsToSeeder(): void
    {
        $filePath = base_path('database/seeders/RequiredSeeder.php');

        $newPermissions = [
            "'{$this->name()}:create',",
            "'{$this->name()}:delete',",
            "'{$this->name()}:update',",
            "'{$this->name()}:view',",
        ];

        new ModifyItems(
            start: '$permissions = [',
            add: $newPermissions,
            file: $filePath,
            end: '];',
            sort: true,
        );
    }

    /**
     * Add the translations to the yaml file.
     */
    protected function addTranslationsToYaml(): void
    {
        $filePath = $this->packagePath('lang/en.yaml');

        $add = [
            "{$this->pluralPascalName()}: {$this->pluralPascalName()}",
            "Create {$this->pascalName()}: Create {$this->pascalName()}",
            "Edit {$this->pascalName()}: Edit {$this->pascalName()}",
            "Update {$this->pascalName()}: Update {$this->pascalName()}",
        ];

        // Check if already exists
        $content = file_get_contents($filePath);

        foreach ($add as $item) {
            if (str_contains($content, $item)) {
                unset($add[array_search($item, $add)]);
            }
        }

        $add = array_values($add);

        new ModifyItems(
            start: '# App',
            add: $add,
            file: $filePath,
            end: '',
            emptyLineOnEnd: true,
            sort: true,
            spaces: 0,
        );
    }

    /**
     * Add the translations to the json file.
     */
    protected function addTranslationsToJson(): void
    {
        $filePath = $this->packagePath('lang/en.json');

        $translations = json_decode(File::get($filePath), true);

        $newTranslations = [
            "{$this->pluralPascalName()}" => "{$this->pluralPascalName()}",
            "Create {$this->pascalName()}" => "Create {$this->pascalName()}",
            "Edit {$this->pascalName()}" => "Edit {$this->pascalName()}",
            "Update {$this->pascalName()}" => "Update {$this->pascalName()}",
        ];

        $translations = array_merge($translations, $newTranslations);
        $translations = array_unique($translations);

        ksort($translations);

        File::put($filePath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
