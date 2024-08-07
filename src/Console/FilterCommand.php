<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\StubHelpers;
use AhsanDevs\Console\Concerns\StubReplaceHelpers;

class FilterCommand extends Command
{
    use StubReplaceHelpers, StubHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:filter {name : The filter name} {package : The span package dir name}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate filter class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->generateStub('Filter.stub', 'src/Http/Filters/'.$this->pascalName().'.php');
    }
}
