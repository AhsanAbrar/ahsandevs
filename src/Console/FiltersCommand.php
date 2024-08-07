<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\StubHelpers;
use AhsanDevs\Console\Concerns\StubReplaceHelpers;

class FiltersCommand extends Command
{
    use StubReplaceHelpers, StubHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:filters {name : The filters name} {package : The span package dir name}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate filters class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->generateStub('Filters.stub', 'src/Http/Filters/'.$this->pascalName().'Filters.php');
    }
}
