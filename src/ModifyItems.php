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
        $indentation = "";

        // Extract the indentation
        // $indentation = $matches[4];

        // Extract existing items and format them
        $existingItems = $this->matches[2];
        // dd($existingItems);

        $existingItemsArray = $this->parseItems($existingItems);
        // dd($existingItemsArray);

        // Ensure $this->add is an array
        $addArray = is_array($this->add) ? $this->add : [$this->add];

        $combinedItems = array_merge($existingItemsArray, $addArray);
        // dd($combinedItems);

        // Remove duplicates and sort items
        $combinedItems = array_unique($combinedItems);
        // dd($combinedItems);

        if ($this->sort) {
            sort($combinedItems);
        }

        // dd($combinedItems);

        // Format the updated items with proper indentation
        $formattedItems = $this->formatItems($combinedItems, $indentation);
        // dd($formattedItems);

        // Prepare the new content with correct formatting
        $replacement = $this->matches[1] . $formattedItems . "\n" . $indentation . $this->end;

        $newContent = str_replace($this->matches[0], $replacement, $this->content);

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
