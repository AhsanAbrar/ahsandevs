<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\StubHelpers;
use AhsanDevs\Console\Concerns\StubReplaceHelpers;

class RequestCommand extends Command
{
    use StubReplaceHelpers, StubHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:request {name : The request name} {package : The span package dir name}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate request class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->generateStub('Request.stub', 'src/Http/Requests/'.$this->pascalName().'.php');
    }
}
