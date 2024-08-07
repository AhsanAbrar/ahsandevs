<?php

namespace AhsanDevs\Console;

class ResourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:resource {name : The resource name} {package : The span package dir name}';

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
            'name' => $this->argument('name').'Controller',
            'package' => $this->argument('package'),
        ]);

        $this->call('ahsandevs:model', [
            'name' => $this->argument('name'),
            'package' => $this->argument('package'),
            '--migration' => true,
        ]);

        $this->info('Resource generated successfully.');
    }
}
