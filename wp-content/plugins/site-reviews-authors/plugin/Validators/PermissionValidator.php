<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Validators;

use GeminiLabs\SiteReviews\Addon\Authors\Application;
use GeminiLabs\SiteReviews\Modules\Validator\ValidatorAbstract;

class PermissionValidator extends ValidatorAbstract
{
    public function isValid(): bool
    {
        $review = glsr_get_review($this->request->review_id);
        if ('delete-review' === $this->request->_action) {
            return glsr(Application::class)->canDeleteOnFrontend($review);
        }
        if ('respond-to-review' === $this->request->_action) {
            return glsr(Application::class)->canRespondOnFrontend($review);
        }
        if ('update-review' === $this->request->_action) {
            return glsr(Application::class)->canEditOnFrontend($review);
        }
        return false;
    }

    public function performValidation(): void
    {
        if ($this->isValid()) {
            return;
        }
        if ('delete-review' === $this->request->_action) {
            $this->fail(__('You do not have permission to delete the review.', 'site-reviews-authors'));
        }
        if ('respond-to-review' === $this->request->_action) {
            $this->fail(__('You do not have permission to respond to the review.', 'site-reviews-authors'));
        }
        if ('update-review' === $this->request->_action) {
            $this->fail(__('You do not have permission to update the review.', 'site-reviews-authors'));
        }
    }
}
