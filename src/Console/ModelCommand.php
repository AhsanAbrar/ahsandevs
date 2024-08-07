<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\CommandHelpers;
use AhsanDevs\Console\Concerns\StubReplaceHelpers;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;

class ModelCommand extends Command implements PromptsForMissingInput
{
    use CommandHelpers, StubReplaceHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:model {package : The span package name} {name : The model name}
                            {--m|migration : Create a new migration file for the model}
                            {--p|package : Create migration file in the package dir}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate model and migration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->modelExists()) {
            $this->error('Model already exists.');
            return;
        }

        $this->generateModel();

        if ($this->option('migration')) {
            $this->generateMigration();
        }
    }

    /**
     * Check if the model already exists.
     *
     * @return bool
     */
    protected function modelExists(): bool
    {
        $filesystem = new Filesystem;
        $destination = $this->packagePath('src/Models/'.$this->pascalName().'.php');

        return $filesystem->exists($destination);
    }

    /**
     * Generate the model file.
     */
    protected function generateModel(): void
    {
        $filesystem = new Filesystem;
        $source = __DIR__ . '/../../stubs/Model.stub';
        $destination = $this->packagePath('src/Models/'.$this->pascalName().'.php');

        $this->ensureDirectoryExists(dirname($destination));

        $filesystem->copy($source, $destination);

        $stub = $filesystem->get($destination);

        $content = $this->replacePlaceholders($stub);

        $filesystem->put($destination, $content);

        $this->info('Model generated successfully.');
    }

    /**
     * Ensure the directory exists.
     *
     * @param string $directory
     */
    protected function ensureDirectoryExists(string $directory): void
    {
        $filesystem = new Filesystem;

        if (!$filesystem->exists($directory)) {
            $filesystem->makeDirectory($directory, 0755, true);
        }
    }

    /**
     * Replace placeholders in the stub file.
     *
     * @param string $stub
     * @return string
     */
    protected function replacePlaceholders(string $stub): string
    {
        $replacements = [
            '[[name]]' => $this->name(),
            '[[pluralName]]' => $this->pluralName(),
            '[[rootNamespace]]' => $this->rootNamespace(),
            '[[pascalName]]' => $this->pascalName(),
            '[[pluralPascalName]]' => $this->pluralPascalName(),
            '[[title]]' => $this->title(),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $stub);
    }

    /**
     * Generate the migration file.
     */
    protected function generateMigration(): void
    {
        $name = 'create_' . $this->pluralName() . '_table';
        $packagePath = $this->option('package') ? 'packages/'.$this->argument('package').'/database/migrations' : null;

        $this->call('make:migration', [
            'name' => $name,
            '--path' => $packagePath,
        ]);
    }
}
