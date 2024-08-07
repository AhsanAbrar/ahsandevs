<?php

namespace AhsanDevs\Console;

class ResourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:controller {name : The resource name} {package : The span package dir name}';

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
        $this->call('ahsandevs:controller', [
            'name' => $this->argument('name'),
        ]);

        $this->call('ahsandevs:model', [
            'name' => $this->argument('name'),
            '--migration' => true,
        ]);

        $this->info('Resource generated successfully.');
    }
}
