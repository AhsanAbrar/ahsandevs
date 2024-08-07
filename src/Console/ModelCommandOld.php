<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\CommandHelpers;
use AhsanDevs\Console\Concerns\StubReplaceHelpers;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;

class ModelCommandOld extends Command implements PromptsForMissingInput
{
    use CommandHelpers, StubReplaceHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:model {name : The model name} {package : The span package dir name}
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
        $this->generateModel();

        if ($this->option('migration')) {
            $this->generateMigration();
        }
    }

    /**
     * Generate Model.
     */
    protected function generateModel(): void
    {
        $filesystem = new Filesystem;
        $source = __DIR__ . '/../../stubs/Model.stub';
        $destination = $this->packagePath('src/Models/'.$this->pascalName().'.php');

        $destinationDir = dirname($destination);

        if (!$filesystem->exists($destinationDir)) {
            $filesystem->makeDirectory($destinationDir, 0755, true);
        }

        if ($filesystem->exists($destination)) {
            $this->error('Model already exists.');
            return;
        }

        $filesystem->copy($source, $destination);

        $stub = $filesystem->get($destination);

        $replacements = [
            '[[name]]' => $this->name(),
            '[[pluralName]]' => $this->pluralName(),
            '[[rootNamespace]]' => $this->rootNamespace(),
            '[[pascalName]]' => $this->pascalName(),
            '[[pluralPascalName]]' => $this->pluralPascalName(),
            '[[title]]' => $this->title(),
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $stub);

        $filesystem->put($destination, $content);

        $this->info('Model generated successfully.');
    }

    /**
     * Generate the migration file.
     */
    protected function generateMigration(): void
    {
        $name = 'create_' . $this->pluralName() . '_table';

        $packageOption = $this->option('package');

        if ($packageOption) {
            $packagePath = 'packages/'.$this->argument('package').'/database/migrations';

            $this->call('make:migration', [
                'name' => $name,
                '--path' => $packagePath,
            ]);
        } else {
            $this->call('make:migration', [
                'name' => $name,
            ]);
        }
    }
}
