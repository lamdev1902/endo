<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Tags;

use GeminiLabs\SiteReviews\Modules\Html\Tags\ReviewTag;

class ReviewAuthorActionsTag extends ReviewTag
{
    protected function handle(): string
    {
        return $this->wrap($this->value());
    }

    protected function value(): string
    {
        $tags = [ // order is intentional
            'respond_url' => ReviewRespondUrlTag::class,
            'edit_url' => ReviewEditUrlTag::class,
            'delete_url' => ReviewDeleteUrlTag::class,
        ];
        foreach ($tags as $tag => $className) {
            $tags[$tag] = glsr($className, ['tag' => $tag, 'args' => $this->args->toArray()])->handleFor(
                'review',
                $this->review->get($tag),
                $this->review
            );
        }
        return array_reduce($tags, fn ($carry, $value) => $carry.$value);
    }
}
