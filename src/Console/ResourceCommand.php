<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\CommandHelpers;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ResourceCommand extends Command
{
    use CommandHelpers;

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
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Your other command logic here...

        $this->modifyApiRoutes();

        $this->info('Resource generated successfully.');
    }

    /**
     * Modify api.php file.
     */
    protected function modifyApiRoutes()
    {
        $resourceName = $this->argument('name');
        $packageName = $this->argument('package');
        $controllerClass = "{$packageName}\\Http\\Controllers\\Api\\{$resourceName}Controller";
        $routeLine = "Route::resource('" . strtolower($resourceName) . "s', {$resourceName}Controller::class);";

        $apiFilePath = $this->packagePath('routes/api.php');

        if (!$this->filesystem->exists($apiFilePath)) {
            $this->error('api.php file not found.');
            return;
        }

        $apiFileContent = $this->filesystem->get($apiFilePath);

        // Add the import at the top
        $apiFileContent = $this->addImport($apiFileContent, $controllerClass);

        // Add the route line in the appropriate place
        $apiFileContent = $this->addRoute($apiFileContent, $routeLine);

        // Save the updated file content
        $this->filesystem->put($apiFilePath, $apiFileContent);
    }

    /**
     * Add the controller import to the api.php file.
     */
    protected function addImport(string $content, string $controllerClass): string
    {
        $lines = explode(PHP_EOL, $content);
        $useStatements = [];
        $otherLines = [];

        // Separate use statements from other lines
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), 'use ')) {
                $useStatements[] = $line;
            } else {
                $otherLines[] = $line;
            }
        }

        // Add the new use statement
        $useStatements[] = "use {$controllerClass};";
        $useStatements = array_unique($useStatements);
        sort($useStatements);

        // Ensure the use statements come first
        return implode(PHP_EOL, array_merge($useStatements, $otherLines));
    }

    /**
     * Add the route line to the api.php file.
     */
    protected function addRoute(string $content, string $routeLine): string
    {
        $lines = explode(PHP_EOL, $content);
        $routeInserted = false;

        // Find the last route declaration
        $lastRouteIndex = -1;
        foreach ($lines as $index => $line) {
            if (str_starts_with(trim($line), 'Route::resource')) {
                $lastRouteIndex = $index;
            }
        }

        // Insert the new route after the last route declaration
        if ($lastRouteIndex >= 0) {
            array_splice($lines, $lastRouteIndex + 1, 0, $routeLine);
            $routeInserted = true;
        }

        // If no route declarations were found, append at the end
        if (!$routeInserted) {
            $lines[] = $routeLine;
        }

        return implode(PHP_EOL, $lines);
    }
}
