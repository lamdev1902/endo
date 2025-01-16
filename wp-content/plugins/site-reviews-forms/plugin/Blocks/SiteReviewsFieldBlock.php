<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Blocks;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\FormFields;
use GeminiLabs\SiteReviews\Addon\Forms\Shortcodes\SiteReviewsFieldShortcode;
use GeminiLabs\SiteReviews\Blocks\SiteReviewsSummaryBlock;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Html\Builder;

class SiteReviewsFieldBlock extends SiteReviewsSummaryBlock
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    public function attributes(): array
    {
        return [
            'assigned_post' => [
                'default' => '',
                'type' => 'string',
            ],
            'assigned_posts' => [
                'default' => '',
                'type' => 'string',
            ],
            'assigned_term' => [
                'default' => '',
                'type' => 'string',
            ],
            'assigned_terms' => [
                'default' => '',
                'type' => 'string',
            ],
            'assigned_user' => [
                'default' => '',
                'type' => 'string',
            ],
            'assigned_users' => [
                'default' => '',
                'type' => 'string',
            ],
            'className' => [
                'default' => '',
                'type' => 'string',
            ],
            'field' => [
                'default' => '',
                'type' => 'string',
            ],
            'form' => [
                'default' => '',
                'type' => 'string',
            ],
            'hide' => [
                'default' => '',
                'type' => 'string',
            ],
            'id' => [
                'default' => '',
                'type' => 'string',
            ],
            'terms' => [
                'default' => '',
                'type' => 'string',
            ],
            'type' => [
                'default' => 'local',
                'type' => 'string',
            ],
        ];
    }

    public function render(array $attributes): string
    {
        $attributes['class'] = $attributes['className'];
        $shortcode = glsr(SiteReviewsFieldShortcode::class);
        if ('edit' === filter_input(INPUT_GET, 'context')) {
            $attributes = $this->normalize($attributes);
            if (0 === Cast::toInt($attributes['form'])) {
                return $this->buildEmptyBlock(
                    _x('Select a custom review form in the block settings.', 'admin-text', 'site-reviews-forms')
                );
            } elseif (empty($attributes['field'])) {
                $formId = Cast::toInt($attributes['form'] ?? 0);
                $fields = glsr(FormFields::class)->normalizedFieldsIndexed($formId);
                $fields = array_filter($fields,
                    fn ($field) => in_array($field['type'], ['range','rating']) && 'rating' !== $field['tag']
                );
                if (empty($fields)) {
                    $text = _x('The form you selected has no custom rating or range fields.', 'admin-text', 'site-reviews-forms');
                } else {
                    $text = _x('Select the custom field you want to use.', 'admin-text', 'site-reviews-forms');
                }
                return $this->buildEmptyBlock($text);
            }
        }
        return $shortcode->buildBlock($attributes);
    }
}
