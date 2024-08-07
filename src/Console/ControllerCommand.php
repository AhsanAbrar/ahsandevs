<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\StubHelpers;
use AhsanDevs\Console\Concerns\StubReplaceHelpers;

class ControllerCommand extends Command
{
    use StubReplaceHelpers, StubHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:controller {name : The controller name} {package : The span package dir name}
                            {--i|invokable : Generate a single method, invokable controller class}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate controller class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('invokable')) {
            $this->generateStub('ControllerInvokable.stub', 'src/Http/Controllers/Api/'.$this->pascalName().'.php');
        } else {
            $this->generateStub('Controller.stub', 'src/Http/Controllers/Api/'.$this->pascalName().'.php');
        }
    }
}
