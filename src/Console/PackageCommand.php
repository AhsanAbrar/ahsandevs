<?php

namespace AhsanDevs\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class PackageCommand extends Command
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:package {package : The span package name}
                            {--web-routes : Add web routes}
                            {--namespace= : The root namespace of the package if it is different from package name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new span package';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ( is_dir($this->packagePath()) ) {
            $this->error('Package already exists!');

            return false;
        }

        (new Filesystem)->copyDirectory(
            __DIR__ . '/../../stubs/package',
            $this->packagePath()
        );

        $this->updateFiles();

        if ($this->option('web-routes')) {
            $this->addWebRoutes();
        }

        // Register the package...
        if ($this->confirm('Would you like to update your composer package?', true)) {
            $this->addPackageToAutoload();

            $this->composerDump();
        }

        $this->info('Span package generated successfully.');
    }

    /**
     * Update Stubs.
     */
    protected function updateFiles(): void
    {
        // Package Name - name (my-admin-blog)
        // Package Name - title (My Admin Blog)
        // Package Name - pascleName (MyAdminBlog)
        // Root Namespace - rootNamespace (Laranext\Span\Admin\Blog)
        // Root Namespace - rootNamespaceComposer (Laranext\\Span\\Admin\\Blog)

        // composer.json replacements...
        $this->replace('{{ name }}', $this->argument('package'), $this->packagePath('composer.json'));
        $this->replace('{{ rootNamespaceComposer }}', $this->rootNamespaceComposer(), $this->packagePath('composer.json'));

        // rename service provider and replacements...
        $this->replace('{{ rootNamespace }}', $this->rootNamespace(), $this->packagePath('src/ServiceProvider.stub'));
        $this->replace('{{ pascleName }}', $this->pascleName(), $this->packagePath('src/ServiceProvider.stub'));
        (new Filesystem)->move(
            $this->packagePath('src/ServiceProvider.stub'),
            $this->packagePath( 'src/' . $this->pascleName() . 'ServiceProvider.php' )
        );

        // rename .gitignore.stub to .gitignore
        (new Filesystem)->move(
            $this->packagePath('.gitignore.stub'),
            $this->packagePath('.gitignore')
        );
    }

    /**
     * Add Web Routes.
     */
    protected function addWebRoutes(): void
    {
        (new Filesystem)->copy(
            __DIR__ . '/../../stubs/package/src/WebRoutesServiceProvider.stub',
            $this->packagePath('src/ServiceProvider.stub')
        );

        // rename service provider and replacements...
        $this->replace('{{ rootNamespace }}', $this->rootNamespace(), $this->packagePath('src/ServiceProvider.stub'));
        $this->replace('{{ pascleName }}', $this->pascleName(), $this->packagePath('src/ServiceProvider.stub'));
        $this->replace('{{ name }}', $this->argument('package'), $this->packagePath('src/ServiceProvider.stub'));
        (new Filesystem)->move(
            $this->packagePath('src/ServiceProvider.stub'),
            $this->packagePath( 'src/' . $this->pascleName() . 'ServiceProvider.php' )
        );
    }

    /**
     * Add a package entry for the package to the application's composer.json file.
     */
    protected function addPackageToAutoload(): void
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        $namespace = $this->rootNamespace().'\\';

        $composer['autoload']['psr-4'][(string) $namespace] = "packages/{$this->argument('package')}/src/";

        $composer['autoload']['psr-4'] = collect($composer['autoload']['psr-4'])->sortKeysUsing('strcasecmp')->toArray();

        file_put_contents(
            base_path('composer.json'),
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Update the project's composer dependencies.
     */
    protected function composerDump(): void
    {
        $this->executeCommand(['composer', 'dump']);
    }

    /**
     * Run the given command as a process.
     */
    protected function executeCommand(array $command): void
    {
        $process = (new Process($command))->setTimeout(null);

        // $process->setTty(Process::isTtySupported());

        $process->run(function ($type, $buffer) {
            $type;
            echo $buffer;
        });
    }
}
