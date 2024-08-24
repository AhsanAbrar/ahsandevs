<?php

namespace AhsanDevs\Console;

use Illuminate\Support\Facades\Storage;

class SetDefaultPackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:set-default-package {name : The package name}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set default package for the current project.';

    /**
     * Prompt for missing input arguments using the returned questions.
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => fn () => $this->choice('Please select a package or write package name', $this->packageOptions()),
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        Storage::put('ahsandevs-default-package', $name);

        $this->info("Default package name '{$name}' has been set successfully!");
    }
}
