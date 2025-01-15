<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Rating;

class ReviewRating extends Field
{
    public string $name = 'rating';
    public string $tag = 'rating';

    protected function conditionOperators(): array
    {
        return ['equals', 'greater', 'less', 'not'];
    }

    protected function defaults(): array
    {
        return [
            'value' => 0,
        ];
    }

    protected function handle(): string
    {
        return _x('Review: Rating', 'admin-text', 'site-reviews-forms');
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
            'value',
        ];
    }

    protected function type(): string
    {
        return 'review_rating';
    }

    protected function validation(): array
    {
        $maxRating = Cast::toInt(glsr()->constant('MAX_RATING', Rating::class));
        return [
            'conditions' => 'criteria',
            'name' => 'required|slug|unique',
            'type' => 'unique',
            'value' => 'number|between:0,'.$maxRating,
        ];
    }
}
