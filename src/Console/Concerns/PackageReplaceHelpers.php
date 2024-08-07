<?php

namespace AhsanDevs\Console\Concerns;

use Illuminate\Support\Str;

trait CommandHelpers
{
    /**
     * Get the package name.
     *
     * @return string
     */
    protected function name()
    {
        return $this->argument('name');
    }

    /**
     * Get the root namespace.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->option('namespace') ? str_replace('/', '\\', $this->option('namespace')) : Str::studly($this->argument('name'));
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
}
