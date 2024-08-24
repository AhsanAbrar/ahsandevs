<?php

namespace AhsanDevs\Console;

use Illuminate\Support\Facades\Storage;

class DefaultPackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:default-package';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get default package name for the current project.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = 'ahsandevs-default-package';

        if (Storage::exists($filePath)) {
            $name = trim(strtok(Storage::get($filePath), "\n"));

            return $this->info("Default package name is: {$name}");
        }

        $this->info('There is no default package name.');
    }
}
