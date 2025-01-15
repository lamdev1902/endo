<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Validators;

use GeminiLabs\SiteReviews\Modules\Validator\HoneypotValidator as Validator;

class HoneypotValidator extends Validator
{
    public function performValidation(): void
    {
        if (!$this->isValid()) {
            $this->fail(
                __('The review could not be reported. Please refresh the page and try again.', 'site-reviews-actions'),
                'The Honeypot caught a bad submission.'
            );
        }
    }
}
