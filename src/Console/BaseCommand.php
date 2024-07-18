<?php

namespace AhsanDevs\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;

class BaseCommand extends Command implements PromptsForMissingInput
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:base
                            {--options : Add options migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update laravel base things';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('options')) {
            $this->addOptions();
        }

        $this->info('Laravel base updated successfully.');
    }

    /**
     * Add Options.
     */
    protected function addOptions(): void
    {
        $filesystem = new Filesystem;
        $source = __DIR__ . '/../../base/database/migrations/0002_02_02_000001_create_options_table.php';
        $destination = base_path('database/migrations/0002_02_02_000001_create_options_table.php');

        if (!$filesystem->exists($destination)) {
            $filesystem->copy($source, $destination);
        }
    }
}
