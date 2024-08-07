<?php

namespace AhsanDevs\Console\Concerns;

use Illuminate\Filesystem\Filesystem;

trait CommandHelpers
{
    /**
     * The filesystem instance.
     */
    protected Filesystem $filesystem;

    /**
     * Create a new instance of the trait.
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem;
    }

    /**
     * Ensure the directory exists.
     */
    protected function ensureDirectoryExists(string $directory): void
    {
        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->makeDirectory($directory, 0755, true);
        }
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($path)
    {
        return (new Filesystem)->exists($path);
    }

    /**
     * Replace the given string in the given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replace($search, $replace, $path)
    {
        if ((new Filesystem)->exists($path)) {
            file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
        }
    }

    /**
     * Build the directory if not exists.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDir($path)
    {
        if (! is_dir($directory = $this->packagePath($path))) {
            mkdir($directory, 0755, true);
        }
    }

    /**
     * Rename the stub with PHP file extensions.
     *
     * @return void
     */
    protected function renameStub($stub)
    {
        (new Filesystem)->move($stub, str_replace('.stub', '.php', $stub));
    }

    /**
     * Rename the stubs with PHP file extensions.
     *
     * @return void
     */
    protected function renameStubs()
    {
        foreach ($this->stubsToRename() as $stub) {
            (new Filesystem)->move($stub, str_replace('.stub', '.php', $stub));
        }
    }

    /**
     * Get the path to the package.
     */
    protected function packagePath($path = null): string
    {
        return base_path('packages/' . $this->argument('package') . '/' . $path);
    }

    /**
     * Check if the given string is in kebab-case.
     */
    protected function isKebabCase(string $string): bool
    {
        return preg_match('/^[a-z]+(-[a-z]+)*$/', $string);
    }
}
