<?php

namespace AhsanDevs\Console;

use AhsanDevs\Console\Concerns\StubHelpers;
use AhsanDevs\Console\Concerns\StubReplaceHelpers;

class VueViewCommand extends Command
{
    use StubReplaceHelpers, StubHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:view {name : The view name} {dir? : The views dir} {package : The span package dir name}
                            {--i|index : Generate an index view}
                            {--f|form : Generate a form view}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate vue view';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $to = 'resources/js/views/'.$this->argument('name').'.vue';

        if ($this->argument('dir')) {
            $to = 'resources/js/views/'.$this->argument('dir').'/'.$this->argument('name').'.vue';
        }

        if ($this->option('index')) {
            $this->generateStub('VueViewIndex.stub', $to);
        } elseif ($this->option('form')) {
            $this->generateStub('VueViewForm.stub', $to);
        }
    }
}
