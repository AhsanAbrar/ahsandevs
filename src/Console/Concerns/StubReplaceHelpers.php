<?php

namespace AhsanDevs\Console\Concerns;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * In the stub helpers we have input like PascalCase, then we have to convert accordingly.
 *
 * [[name]] => $this->name() | kebab-case
 * [[pluralName]] => $this->pluralName() | kebab-case
 * [[rootNamespace]] => $this->rootNamespace() | PascalCase
 * [[pascalName]] => $this->pascalName() | PascalCase
 * [[pluralPascalName]] => $this->pluralPascalName() | PascalCase
 * [[title]] => $this->title() | Title Case
 */

trait StubReplaceHelpers
{
    /**
     * Get the package name.
     *
     * @return string
     */
    protected function name()
    {
        return Str::lower($this->argument('name'));
    }

    /**
     * Get the package root namespace.
     */
    protected function rootNamespace(): string
    {
        $files = new Filesystem;
        $package = $this->argument('package');
        $composerPath = base_path("packages/{$package}/composer.json");

        if (!$this->alreadyExists($composerPath)) {
            $this->error("Composer file not found at {$composerPath}");
            return '';
        }

        $composerContent = $files->get($composerPath);
        $composerJson = json_decode($composerContent, true);

        if (isset($composerJson['autoload']['psr-4'])) {
            $namespaces = array_keys($composerJson['autoload']['psr-4']);
            return rtrim($namespaces[0], '\\');
        }

        $this->error("PSR-4 autoload section not found in composer.json");

        return '';
    }

    /**
     * Get the root namespace for composer.
     *
     * @return string
     */
    protected function rootNamespaceComposer()
    {
        return $this->option('namespace') ? str_replace('/', '\\\\', $this->option('namespace')) : Str::studly($this->argument('name'));
    }

    /**
     * Get the camel case.
     *
     * @return string
     */
    protected function camel()
    {
        return Str::camel($this->argument('name'));
    }

    /**
     * Get the kebab case.
     *
     * @return string
     */
    protected function kebab()
    {
        return Str::kebab($this->argument('name'));
    }

    /**
     * Get the plural kebab case.
     *
     * @return string
     */
    protected function kebabPlural()
    {
        return Str::kebab(Str::plural( $this->argument('name') ));
    }

    /**
     * Get the plural name.
     *
     * @return string
     */
    protected function plural()
    {
        return Str::plural( $this->argument('name') );
    }

    /**
     * Get the title case with space from package name.
     *
     * @return string
     */
    protected function title()
    {
        return Str::of($this->argument('name'))->replace('-', ' ')->title();
    }

    /**
     * Get the pascle case package name.
     *
     * @return string
     */
    protected function pascalName()
    {
        return Str::studly( $this->argument('name') );
    }

    /**
     * Get the plural kebab case name.
     */
    protected function pluralName(): string
    {
        return Str::plural( $this->name() );
    }

    /**
     * Get the plural pascle case name.
     */
    protected function pluralPascalName(): string
    {
        return Str::plural( $this->pascalName() );
    }
}
