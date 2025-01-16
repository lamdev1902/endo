<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Validators;

use GeminiLabs\SiteReviews\Addon\Authors\Defaults\RespondToReviewDefaults;
use GeminiLabs\SiteReviews\Addon\Authors\Forms\RespondToReviewForm;
use GeminiLabs\SiteReviews\Modules\Validator\DefaultValidator;
use GeminiLabs\SiteReviews\Request;

class RespondToReviewValidator extends DefaultValidator
{
    public function request(): Request
    {
        $values = glsr(RespondToReviewDefaults::class)->unguardedMerge($this->request->toArray());
        return new Request($values);
    }

    public function rules(): array
    {
        $review = glsr_get_review($this->request->review_id);
        $form = new RespondToReviewForm($review, $this->request->toArray());
        $fields = array_filter($form->visible(), fn ($field) => !$field->is_hidden);
        $rules = array_filter(wp_list_pluck($fields, 'validation', 'original_name'));
        return $rules;
    }
}
