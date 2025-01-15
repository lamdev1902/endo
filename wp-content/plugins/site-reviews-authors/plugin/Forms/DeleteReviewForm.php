<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Forms;

use GeminiLabs\SiteReviews\Addon\Authors\Application;
use GeminiLabs\SiteReviews\Contracts\FieldContract;
use GeminiLabs\SiteReviews\Modules\Html\Form;
use GeminiLabs\SiteReviews\Modules\Html\ReviewField;
use GeminiLabs\SiteReviews\Review;

class DeleteReviewForm extends Form
{
    public function __construct(Review $review, array $values = [])
    {
        $args = [
            'button_text' => __('Delete review', 'site-reviews-authors'),
            'button_text_loading' => __('Deleting, please wait...', 'site-reviews-authors'),
            'class' => 'glsr-review-form',
            'review_id' => $review->ID,
        ];
        parent::__construct($args, $values);
    }

    public function config(): array
    {
        return glsr(Application::class)->config('forms/delete-form');
    }

    public function field(string $name, array $args): FieldContract
    {
        $field = new ReviewField(wp_parse_args($args, compact('name')));
        $this->normalizeField($field);
        return $field;
    }

    /**
     * @return FieldContract[]
     */
    protected function fieldsHidden(): array
    {
        do_action('litespeed_nonce', 'delete-review'); // @litespeedcache
        $config = [
            '_action' => 'delete-review',
            '_nonce' => wp_create_nonce('delete-review'),
            'form_id' => $this->args->id,
            'review_id' => $this->args->review_id,
        ];
        $fields = [];
        foreach ($config as $name => $value) {
            $field = $this->field($name, [
                'type' => 'hidden',
                'value' => $value,
            ]);
            if ($field->isValid()) {
                $fields[$name] = $field;
            }
        }
        return $fields;
    }
}
