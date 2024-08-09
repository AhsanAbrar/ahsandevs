<?php

namespace AhsanDevs;

/**
 * Class ModifyFile
 *
 * Handles modifications to a file based on a pattern, allowing content to be added, sorted, and appended.
 */
class ModifyFile
{
    public function __construct(
        protected string $pattern,
        protected string $add,
        protected string $file,
        protected ?string $fullPattern = null,
        protected bool $sort = false,
        protected bool $last = false
    ) {
        $this->handle();
    }

    /**
     * Main method to handle file modification based on the provided pattern and options.
     */
    protected function handle(): void
    {
        $this->content = $this->readFileContent();

        if (!$this->patternExists()) {
            $this->appendContent();
            return;
        }

        $group = $this->getMatchedGroup();
        $lines = $this->processLines($group);
        $newContent = $this->replaceContent($group, $lines);

        $this->writeFileContent($newContent);
    }

    /**
     * Checks if the pattern exists in the file content.
     */
    protected function patternExists(): bool
    {
        return preg_match_all($this->getPattern(), $this->content, $matches) > 0;
    }

    /**
     * Gets the matched group based on the 'last' flag.
     */
    protected function getMatchedGroup(): string
    {
        preg_match_all($this->getPattern(), $this->content, $matches);

        return $this->last ? end($matches[0]) : $matches[0][0];
    }

    /**
     * Processes the lines from the matched group.
     */
    protected function processLines(string $group): array
    {
        $lines = array_unique(array_filter(array_merge(explode("\n", $group), [$this->add])));

        return $this->sort ? $this->sortLines($lines) : $lines;
    }

    /**
     * Sorts the lines if sorting is enabled.
     */
    protected function sortLines(array $lines): array
    {
        sort($lines);

        return $lines;
    }

    /**
     * Replaces the old content with the new processed content.
     */
    protected function replaceContent(string $group, array $lines): string
    {
        $replace = implode("\n", $lines);

        return $this->adjustReplaceContent($group, $replace);
    }

    /**
     * Adjusts the replacement content to maintain the original formatting.
     */
    protected function adjustReplaceContent(string $group, string $replace): string
    {
        if (substr($group, -1) === "\n" && substr($replace, -1) !== "\n") {
            $replace .= "\n";
        }

        return str_replace($group, $replace, $this->content);
    }

    /**
     * Reads the content of the file.
     */
    protected function readFileContent(): string
    {
        return file_get_contents($this->file);
    }

    /**
     * Writes the content to the file.
     */
    protected function writeFileContent(string $content): void
    {
        file_put_contents($this->file, $content);
    }

    /**
     * Appends content to the end of the file if the pattern is not found.
     */
    protected function appendContent(): void
    {
        $this->writeFileContent($this->content . $this->add . "\n");
    }

    /**
     * Retrieves the regex pattern to search for in the file content.
     */
    protected function getPattern(): string
    {
        return $this->fullPattern ?? "/(?:^{$this->pattern}(?:\n|$))+(?=\n\n|$)/m";
    }
}
