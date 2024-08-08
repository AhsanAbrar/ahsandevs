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
    protected $signature = 'ahsandevs:vue-view {name : The view name} {package : The span package dir name} {dir? : The views dir}
                            {--d|detail : Generate a detail view}
                            {--f|form : Generate a form view}
                            {--i|index : Generate an index view}';


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

        if (($this->argument('name') === 'Index' && $this->hasNotAnyOption()) || $this->option('index')) {
            $this->generateStub('VueViewIndex.stub', $to);
        } elseif (($this->argument('name') === 'Form' && $this->hasNotAnyOption()) || $this->option('form')) {
            $this->generateStub('VueViewForm.stub', $to);
        } elseif (($this->argument('name') === 'Detail' && $this->hasNotAnyOption()) || $this->option('detail')) {
            $this->generateStub('VueViewDetail.stub', $to);
        } else {
            $this->generateStub('VueView.stub', $to);
        }
    }

    protected function hasNotAnyOption()
    {
        $definedOptions = ['detail', 'index', 'form'];
        $options = $this->options();

        // Check if any of the defined options is set to true
        $hasTrueOption = array_filter($definedOptions, function ($option) use ($options) {
            return !empty($options[$option]);
        });

        // Return true if any of the defined options is true
        return empty($hasTrueOption);
    }
}
