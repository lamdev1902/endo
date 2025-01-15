<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Validators;

use GeminiLabs\SiteReviews\Addon\Actions\Defaults\ReportReviewDefaults;
use GeminiLabs\SiteReviews\Addon\Actions\ReportReviewForm;
use GeminiLabs\SiteReviews\Modules\Validator\DefaultValidator;
use GeminiLabs\SiteReviews\Request;

class ReportReviewValidator extends DefaultValidator
{
    public function request(): Request
    {
        $values = glsr(ReportReviewDefaults::class)->unguardedMerge($this->request->toArray());
        return new Request($values);
    }

    public function rules(): array
    {
        $review = glsr_get_review($this->request->review_id);
        $form = new ReportReviewForm($review, $this->request->toArray());
        $fields = array_filter($form->visible(), fn ($field) => !$field->is_hidden);
        $rules = array_filter(wp_list_pluck($fields, 'validation', 'original_name'));
        if (!is_user_logged_in()) {
            return $rules;
        }
        $user = wp_get_current_user();
        if (!empty(sanitize_email($user->user_email))) {
            unset($rules['email']);
        }
        return $rules;
    }
}
