<?php

namespace AhsanDevs\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class YesCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:yes {name : The request name} {package : The span package dir name}';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $package = $this->argument('package');

        $this->info('Name: ' . $name . ' | Package: ' . $package);
    }
}
