<?php

namespace GeminiLabs\SiteReviews\Addon\Actions;

use GeminiLabs\SiteReviews\Contracts\FieldContract;
use GeminiLabs\SiteReviews\Modules\Html\Form;
use GeminiLabs\SiteReviews\Modules\Honeypot;
use GeminiLabs\SiteReviews\Modules\Sanitizer;
use GeminiLabs\SiteReviews\Review;

class ReportReviewForm extends Form
{
    public function __construct(Review $review, array $values = [])
    {
        $args = [
            'button_text' => __('Report review', 'site-reviews-actions'),
            'class' => 'glsr-report-form glsr-review-form',
            'review_id' => $review->ID,
        ];
        parent::__construct($args, $values);
    }

    public function build(): string
    {
        $honeypot = glsr(Honeypot::class)->build($this->args->id);
        $step1 = $this->fieldsFor('step_1');
        $step2 = $this->fieldsFor('step_2');
        $step3 = $this->fieldsFor('step_3');
        $visibleFields = $this->visible();
        return glsr(Template::class)->build('templates/report-form', [
            'context' => [
                'class' => $this->classAttrForm(),
                'hidden_fields' => array_reduce($this->hidden(), fn ($carry, $field) => $carry.$field->build(), ''),
                'step_1_fields' => array_reduce($step1, fn ($carry, $field) => $carry.$field->build(), $honeypot),
                'step_2_fields' => array_reduce($step2, fn ($carry, $field) => $carry.$field->build(), ''),
                'step_3_fields' => array_reduce($step3, fn ($carry, $field) => $carry.$field->build(), ''),
                'response' => $this->buildResponse(),
                'submit_button' => $this->buildSubmitButton(),
            ],
            'form' => $this,
        ]);
    }

    public function config(): array
    {
        $config = glsr(Application::class)->config('forms/report-form');
        if (!is_user_logged_in()) {
            return $config;
        }
        $user = wp_get_current_user();
        $email = sanitize_email($user->user_email);
        if (!empty($email)) {
            $config['email'] = [
                'type' => 'hidden',
                'value' => $email,
            ];
        }
        return $config;
    }

    public function field(string $name, array $args): FieldContract
    {
        $field = new ReportField(wp_parse_args($args, compact('name')));
        $this->normalizeField($field);
        return $field;
    }

    /**
     * @return FieldContract[]
     */
    protected function fieldsHidden(): array
    {
        do_action('litespeed_nonce', 'report-review'); // @litespeedcache
        $config = [
            '_action' => 'report-review',
            '_nonce' => wp_create_nonce('report-review'),
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
