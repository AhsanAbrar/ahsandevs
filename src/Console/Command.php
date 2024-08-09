<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\CommandHelpers;
use Illuminate\Console\Command as BaseCommand;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
// use function Laravel\Prompts\select;

class Command extends BaseCommand implements PromptsForMissingInput
{
    use CommandHelpers;

    /**
     * Create a new instance of the command.
     */
    public function __construct(protected Filesystem $filesystem)
    {
        parent::__construct();
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'package' => fn () => $this->choice('Please select a package or write package name', $this->packageOptions()),
            // 'package' => fn () => select('Please select a package or write package name', $this->packageOptions()),
        ];
    }

    /**
     * Get the package options.
     */
    protected function packageOptions(): array
    {
        $directories = File::directories(base_path('packages'));

        $options = [];

        foreach ($directories as $directory) {
            $options[] = basename($directory);
        }

        return $options;
    }
}
