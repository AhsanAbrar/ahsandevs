<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\CommandHelpers;
use Illuminate\Console\Command as BaseCommand;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;

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
}
