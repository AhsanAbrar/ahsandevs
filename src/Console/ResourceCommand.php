<?php

namespace AhsanDevs\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class ResourceCommand extends Command implements PromptsForMissingInput
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:resource {name : The span package name} {resource : The resource name}
                            {--m|model : Add model}';

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
        // Generate Model
        $this->call('ahsandevs:model', [
            'name' => $this->argument('name'),
            'model' => $this->argument('resource'),
        ]);

        $this->info('Resource generated successfully.');
    }
}
