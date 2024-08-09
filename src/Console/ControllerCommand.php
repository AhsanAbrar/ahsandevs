<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\StubHelpers;
use AhsanDevs\Console\Concerns\StubReplaceHelpers;
use Illuminate\Support\Str;

class ControllerCommand extends Command
{
    use StubReplaceHelpers, StubHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:controller {name : The controller name} {package : The span package dir name}
                            {--s|save : Generate a create and store methods controller class}
                            {--i|invokable : Generate a single method, invokable controller class}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate controller class';

    /**
     * Get the name argument.
     */
    protected function getNameArgument(): string
    {
        return Str::before($this->argument('name'), 'Controller');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('invokable')) {
            $this->generateStub('ControllerInvokable.stub', 'src/Http/Controllers/Api/'.$this->pascalName().'.php');
        } elseif ($this->option('save')) {
            $this->generateStub('ControllerSave.stub', 'src/Http/Controllers/Api/'.$this->pascalName().'.php');
        } else {
            $this->generateStub('Controller.stub', 'src/Http/Controllers/Api/'.$this->pascalName().'.php');
        }
    }
}
