<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\CommandHelpers;
use AhsanDevs\Console\Concerns\StubHelpers;
use AhsanDevs\Console\Concerns\StubReplaceHelpers;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class RequestCommand extends Command implements PromptsForMissingInput
{
    use CommandHelpers, StubReplaceHelpers, StubHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:request {name : The request name} {package : The span package dir name}
                            {--r|root : Create in the app root}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate request';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->generateStubs([
            'Model.stub' => 'src/Models/'.$this->pascalName().'.php'
        ]);
    }
}
