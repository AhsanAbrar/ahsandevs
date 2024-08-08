<?php

namespace AhsanDevs\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\select;
use function Laravel\Prompts\multiselect;

class YesCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:yes {name : The request name} {package : The span package dir name}
                            {--queue : Queue the mail}';

    /**
     * Perform actions after the user was prompted for missing arguments.
     */
    protected function promptForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        $role = select(
    label: 'What role should the user have?',
    options: ['Member', 'Contributor', 'Owner']
);

//         $permissions = multiselect(
//     label: 'What permissions should be assigned?',
//     options: ['Read', 'Create', 'Update', 'Delete']
// );

        // $input->setArgument('package', $this->choice('Please select a package choice:', ['First', 'Second', 'Third'], 0));
        $input->setArgument('package', $role);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $package = $this->argument('package');
        $option = $this->option('queue');

        $this->info('Name: ' . $name . ' | Package: ' . $package . ' | Option: ' . $option);
    }
}
