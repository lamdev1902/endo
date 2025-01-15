<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class ReviewImages extends Field
{
    public string $name = 'images';
    public string $tag = 'images';

    public function isActive(): bool
    {
        return class_exists('GeminiLabs\SiteReviews\Addon\Images\FieldElements\Dropzone');
    }

    protected function handle(): string
    {
        return _x('Review: Images', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'conditions',
            'description',
            'label',
            'required',
            'responsive_width',
            'tag_label',
            'type',
        ];
    }

    protected function type(): string
    {
        return 'review_dropzone';
    }

    protected function validation(): array
    {
        return [
            'name' => 'required|slug|unique',
            'type' => 'unique',
        ];
    }
}
