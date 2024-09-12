<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\StubHelpers;
use AhsanDevs\Console\Concerns\StubReplaceHelpers;

class MigrationCommand extends Command
{
    use StubReplaceHelpers, StubHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:migration {name : The migration name} {package : The span package dir name}
                            {--p|package : Create migration file in the package dir}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate migration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->generateMigration();
    }

    /**
     * Generate the migration file.
     */
    protected function generateMigration(): void
    {
        $name = 'create_' . $this->pluralSnakeName() . '_table';
        $packagePath = $this->option('package') ? 'packages/'.$this->argument('package').'/database/migrations' : null;

        $this->call('make:migration', [
            'name' => $name,
            '--path' => $packagePath,
        ]);
    }
}
