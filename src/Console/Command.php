<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\CommandHelpers;
use Illuminate\Console\Command as BaseCommand;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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
            'package' => fn () => $this->choice(
                'Please select a package or write package name',
                $this->packageOptions(),
                $this->getDefaultPackage()
            ),
            // 'package' => fn () => select('Please select a package or write package name', $this->packageOptions()),
        ];
    }

    /**
     * Get the package options.
     */
    protected function packageOptions(): array
    {
        return array_map('basename', File::directories(base_path('packages')));
    }

    /**
     * Get the default package name from storage.
     */
    protected function getDefaultPackage(): ?string
    {
        $filePath = 'ahsandevs-default-package';

        if (!Storage::exists($filePath)) {
            return null;
        }

        return trim(strtok(Storage::get($filePath), "\n")) ?: null;
    }
}
