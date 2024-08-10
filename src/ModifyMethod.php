<?php

namespace AhsanDevs;

/**
 * Class ModifyMethod
 *
 * Handles modifications to a file based on a pattern, allowing content to be added, sorted, and appended.
 */
class ModifyMethod
{
    /**
     * The content of the file being processed.
     *
     * @var string
     */
    protected string $content;

    /**
     * Constructs a new ModifyMethod instance.
     */
    public function __construct(
        protected string $method,
        protected string $add,
        protected string $file,
        protected bool $prepend = false,
        protected int $spaces = 4
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
            throw new \Exception('Method not found');
        }

        $newContent = $this->generateNewContent();

        $this->writeFileContent($newContent);
    }

    /**
     * Checks if the pattern exists in the file content.
     */
    protected function patternExists(): bool
    {
        return preg_match($this->getPattern(), $this->content) > 0;
    }

    /**
     * Generates the new content by modifying the matched pattern.
     */
    protected function generateNewContent(): string
    {
        $replacement = $this->prepend
            ? "$1$2{$this->addSpaces()}{$this->add}\n$3$4"
            : "$1$2$3{$this->addSpaces()}{$this->add}\n$4";

        return preg_replace($this->getPattern(), $replacement, $this->content);
    }

    /**
     * Creates a string of spaces for indentation.
     */
    protected function addSpaces(): string
    {
        return str_repeat(' ', max($this->spaces, 2) * 2);
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
     * Retrieves the regex pattern to search for in the file content.
     */
    protected function getPattern(): string
    {
        return "/(\b{$this->getEscapedParenthesesMethod()})(\s*\{)([\s\S]*?)(\s*})/m";
    }

    /**
     * Escapes parentheses by adding backslashes before them.
     */
    protected function getEscapedParenthesesMethod(): string
    {
        return str_replace(['(', ')'], ['\\(', '\\)'], $this->method);
    }
}
