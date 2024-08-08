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
    protected $signature = 'ahsandevs:you {name : The request name} {package : The span package dir name}';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Name: ' . $this->argument('name') . ' | Package: ' . $this->argument('package'));
    }
}
