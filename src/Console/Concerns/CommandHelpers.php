<?php

namespace AhsanDevs\Console\Concerns;

trait CommandHelpers
{
    /**
     * Fail when the package does not exist.
     */
    protected function failWhenPackageDoesNotExist(): void
    {
        if (! $this->filesystem->exists($this->packagePath())) {
            $this->fail("'" . $this->argument('package') . "'" . ' package does not exist.');
        }
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
     * Replace the given string in the given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replace($search, $replace, $path)
    {
        if ($this->filesystem->exists($path)) {
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
        $this->filesystem->move($stub, str_replace('.stub', '.php', $stub));
    }

    /**
     * Rename the stubs with PHP file extensions.
     *
     * @return void
     */
    protected function renameStubs()
    {
        foreach ($this->stubsToRename() as $stub) {
            $this->filesystem->move($stub, str_replace('.stub', '.php', $stub));
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
