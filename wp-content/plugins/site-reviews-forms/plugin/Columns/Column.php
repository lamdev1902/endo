<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Columns;

abstract class Column
{
    public int $postId;

    public function __construct(int $postId)
    {
        $this->postId = $postId;
    }

    abstract public function build(string $value = ''): string;

    public function render(string $value = ''): void
    {
        echo $this->build($value);
    }
}
