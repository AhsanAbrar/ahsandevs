<?php

namespace AhsanDevs;

class ArrayUpdater
{
    private string $filePath;
    private string $arrayVariableName;

    public function __construct(string $filePath, string $arrayVariableName)
    {
        $this->filePath = $filePath;
        $this->arrayVariableName = $arrayVariableName;
    }

    public function updateArray(array $newItems): void
    {
        $fileContent = file_get_contents($this->filePath);

        if ($fileContent === false) {
            throw new \RuntimeException("Unable to read the file: $this->filePath");
        }

        // Match the array with flexible indentation
        $pattern = sprintf('/(%s\s*=\s*\[\n)([\s\S]*?)(\n(\s*)\];)/', preg_quote($this->arrayVariableName));
        if (!preg_match($pattern, $fileContent, $matches)) {
            throw new \RuntimeException("Unable to match the array in the file.");
        }

        // Extract the indentation
        $indentation = $matches[4];

        // Extract existing items and format them
        $existingItems = $matches[2];
        $existingItemsArray = $this->parseItems($existingItems);
        $combinedItems = array_merge($existingItemsArray, $newItems);

        // Remove duplicates and sort items
        $combinedItems = array_unique($combinedItems);
        sort($combinedItems);

        // Format the updated items with proper indentation
        $formattedItems = $this->formatItems($combinedItems, $indentation);

        // Prepare the new content with correct formatting
        $replacement = $matches[1] . $formattedItems . "\n" . $indentation . '];';
        $newContent = str_replace($matches[0], $replacement, $fileContent);

        if (file_put_contents($this->filePath, $newContent) === false) {
            throw new \RuntimeException("Unable to write to the file: $this->filePath");
        }

        echo "Array updated and sorted successfully.\n";
    }

    private function formatItems(array $items, string $indentation): string
    {
        // Format each item with proper indentation and align them correctly
        return implode("\n", array_map(function ($item) use ($indentation) {
            return sprintf("%s'%s',", $indentation . '    ', trim($item)); // Adjust indentation
        }, $items));
    }

    private function parseItems(string $items): array
    {
        preg_match_all('/^\s*\'(.*?)\',\s*$/m', $items, $matches);
        return $matches[1];
    }
}
