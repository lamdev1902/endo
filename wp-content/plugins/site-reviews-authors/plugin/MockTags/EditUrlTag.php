<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\MockTags;

use GeminiLabs\SiteReviews\Modules\Html\Tags\Tag;

class EditUrlTag extends Tag
{
    public function isHidden(string $path = ''): bool
    {
        return false;
    }

    protected function handle(): string
    {
        return sprintf('<a href="javascript:void(0)">%s</a>', $this->value());
    }

    protected function value(): string
    {
        return 'Edit';
    }
}
