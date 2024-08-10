<?php

namespace AhsanDevs\Console;

use AhsanDevs\ModifyMethod;

class BaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahsandevs:base
                            {--required-seeder : Add required seeder}
                            {--authorization : Add authorization support}
                            {--options : Add options migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update laravel base things';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('options')) {
            $this->addOptions();
        }

        if ($this->option('authorization')) {
            $this->addAuthorization();
        }

        if ($this->option('required-seeder')) {
            $this->addRequiredSeeder();
        }

        $this->info('Laravel base updated successfully.');
    }

    /**
     * Add Options.
     */
    protected function addOptions(): void
    {
        $source = __DIR__ . '/../../base/database/migrations/0002_02_02_000001_create_options_table.php';
        $destination = base_path('database/migrations/0002_02_02_000001_create_options_table.php');

        if (!$this->filesystem->exists($destination)) {
            $this->filesystem->copy($source, $destination);
        }
    }

    /**
     * Add Authorization.
     */
    protected function addAuthorization(): void
    {
        $source = __DIR__ . '/../../base/database/migrations/0002_02_02_000002_create_authorizations_table.php';
        $destination = base_path('database/migrations/0002_02_02_000002_create_authorizations_table.php');

        if (!$this->filesystem->exists($destination)) {
            $this->filesystem->copy($source, $destination);

            $source = __DIR__ . '/../../base/app/Models/User.php';
            $destination = base_path('app/Models/User.php');
            $this->filesystem->copy($source, $destination);

            $source = __DIR__ . '/../../base/app/Models/Permission.php';
            $destination = base_path('app/Models/Permission.php');
            $this->filesystem->copy($source, $destination);

            $source = __DIR__ . '/../../base/app/Models/Role.php';
            $destination = base_path('app/Models/Role.php');
            $this->filesystem->copy($source, $destination);
        }
    }

    /**
     * Add Required Seeder.
     */
    protected function addRequiredSeeder(): void
    {
        $source = __DIR__ . '/../../base/database/seeders/RequiredSeeder.php';
        $destination = base_path('database/seeders/RequiredSeeder.php');

        if (!$this->filesystem->exists($destination)) {
            $this->filesystem->copy($source, $destination);

            new ModifyMethod(
                'public function run(): void',
                "\n" . str_repeat(' ', 8) . '$this->call(RequiredSeeder::class);',
                base_path('database/seeders/DatabaseSeeder.php')
            );
        }
    }
}
