<?php

namespace AhsanDevs;

use Exception;

class ModifyItems
{
    /**
     * The content of the file being processed.
     *
     * @var string
     */
    protected string $content;

    /**
     * The content of the file being processed.
     *
     * @var array
     */
    protected array $matches;

    /**
     * Constructs a new ModifyItems instance.
     */
    public function __construct(
        protected string $start,
        protected string|array $add,
        protected string $file,
        protected string $end = '];',
        protected bool $prepend = false,
        protected bool $sort = false,
        protected int $spaces = 4,
    ) {
        $this->handle();
    }

    protected function handle()
    {
        $this->content = $this->readFileContent();
        $this->matches = $this->getMatches();
        // Extract the indentation
        $indentation = $matches[4];

        // Extract existing items and format them
        $existingItems = $matches[2];
        $existingItemsArray = $this->parseItems($existingItems);
        $combinedItems = array_merge($existingItemsArray, $this->add);

        // Remove duplicates and sort items
        $combinedItems = array_unique($combinedItems);
        sort($combinedItems);

        // Format the updated items with proper indentation
        $formattedItems = $this->formatItems($combinedItems, $indentation);

        // Prepare the new content with correct formatting
        $replacement = $matches[1] . $formattedItems . "\n" . $indentation . '];';
        $newContent = str_replace($matches[0], $replacement, $fileContent);

        $this->writeFileContent($newContent);
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
     * Checks if the pattern exists in the file content.
     */
    protected function getMatches(): array
    {
        if (!preg_match($this->getPattern(), $this->content, $matches)) {
            throw new Exception(sprintf(
                'The code "%s" was not found in the file "%s".',
                $this->start,
                $this->file
            ));
        }

        return $matches;
    }

    /**
     * Retrieves the regex pattern to search for in the file content.
     */
    protected function getPattern(): string
    {
        return sprintf('/(%s\n)([\s\S]*?)(\n\s*%s)/', preg_quote($this->start), preg_quote($this->end));
    }

    protected function formatItems(array $items, string $indentation): string
    {
        // Format each item with proper indentation and align them correctly
        return implode("\n", array_map(function ($item) use ($indentation) {
            return sprintf("%s'%s',", $indentation . '    ', trim($item)); // Adjust indentation
        }, $items));
    }

    protected function parseItems(string $items): array
    {
        preg_match_all('/^\s*\'(.*?)\',\s*$/m', $items, $matches);
        return $matches[1];
    }
}
