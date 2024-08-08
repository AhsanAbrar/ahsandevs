<?php

namespace AhsanDevs\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class YouCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:you {name : The request name} {package? : The span package dir name}';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $package = $this->argument('package');

        if (!$package) {
            // Define the options
            $options = ['First', 'Second', 'Third'];

            // Prompt the user to select an option
            $package = $this->choice('Please select a package:', $options);
        }

        $this->info('Name: ' . $name . ' | Package: ' . $package);
    }
}
