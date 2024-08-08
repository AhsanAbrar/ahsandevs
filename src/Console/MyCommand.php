<?php

namespace AhsanDevs\Console;

class MyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:my {name : The request name} {package : The span package dir name}';

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
