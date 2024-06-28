<?php

namespace AhsanDevs\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the ahsandevs';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('span:install');
    }
}
