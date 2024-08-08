<?php

namespace AhsanDevs\Console\Concerns;

trait StubHelpers
{
    /**
     * Generate given stubs.
     */
    protected function generateStubs(array $stubs): void
    {
        foreach ($stubs as $from => $to) {
            $this->generateStub($from, $to);
        }
    }

    /**
     * Generate a single stub.
     */
    protected function generateStub(string $from, string $to): void
    {
        $this->failWhenPackageDoesNotExist();

        $source = __DIR__ . '/../../../stubs/' . $from;
        $destination = $this->packagePath($to);

        if (!$this->filesystem->exists($source)) {
            $this->fail("Stub file does not exist: {$source}");
        }

        if ($this->filesystem->exists($destination)) {
            $this->fail("File already exists: {$destination}");
        }

        $this->ensureDirectoryExists(dirname($destination));

        $this->filesystem->copy($source, $destination);

        $this->replacePlaceholdersInFile($destination);

        $this->components->info(sprintf('%s [%s] created successfully.', '', $destination));
    }

    /**
     * Replace placeholders in the stub file.
     */
    protected function replacePlaceholdersInFile(string $filePath): void
    {
        $stub = $this->filesystem->get($filePath);

        $replacements = [
            '[[name]]' => $this->name(),
            '[[pluralName]]' => $this->pluralName(),
            '[[rootNamespace]]' => $this->rootNamespace(),
            '[[pascalName]]' => $this->pascalName(),
            '[[pluralPascalName]]' => $this->pluralPascalName(),
            '[[title]]' => $this->title(),
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $stub);

        $this->filesystem->put($filePath, $content);
    }
}
