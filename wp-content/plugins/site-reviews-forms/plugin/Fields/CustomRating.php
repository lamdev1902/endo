<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Rating;

class CustomRating extends Field
{
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
        return _x('Custom: Rating', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'conditions',
            'description',
            'label',
            'name',
            'required',
            'responsive_width',
            'tag',
            'tag_label',
            'type',
            'value',
        ];
    }

    protected function type(): string
    {
        return 'rating';
    }

    protected function validation(): array
    {
        $maxRating = Cast::toInt(glsr()->constant('MAX_RATING', Rating::class));
        return [
            'conditions' => 'criteria',
            'value' => 'number|between:0,'.$maxRating,
        ];
    }
}
