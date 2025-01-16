<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Validators;

use GeminiLabs\SiteReviews\Modules\Validator\HoneypotValidator as Validator;

class HoneypotValidator extends Validator
{
    public function performValidation(): void
    {
        if (!$this->isValid()) {
            if ('update-review' === $this->request->_action) {
                $message = __('The review could not be updated. Please refresh the page and try again.', 'site-reviews-authors');
            } elseif ('delete-review' === $this->request->_action) {
                $message = __('The review could not be deleted. Please refresh the page and try again.', 'site-reviews-authors');
            } elseif ('respond-to-review' === $this->request->_action) {
                $message = __('The review could not be responded to. Please refresh the page and try again.', 'site-reviews-authors');
            } else {
                $message = __('There was a problem. Please refresh the page and try again.', 'site-reviews-authors');
            }
            $this->fail($message, 'The Honeypot caught a bad submission.');
        }
    }
}
