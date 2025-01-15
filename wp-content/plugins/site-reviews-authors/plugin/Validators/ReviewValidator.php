<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Validators;

use GeminiLabs\SiteReviews\Modules\Validator\ValidatorAbstract;

class ReviewValidator extends ValidatorAbstract
{
    public function isValid(): bool
    {
        return glsr_get_review($this->request->review_id)->isValid();
    }

    public function performValidation(): void
    {
        if ($this->isValid()) {
            return;
        }
        if ('delete-review' === $this->request->_action) {
            $this->fail(
                sprintf(__('Cannot find the review to delete (ID: %s).', 'site-reviews-authors'), $this->request->review_id)
            );
        }
        if ('respond-to-review' === $this->request->_action) {
            $this->fail(
                sprintf(__('Cannot find the review to respond to (ID: %s).', 'site-reviews-authors'), $this->request->review_id)
            );
        }
        if ('update-review' === $this->request->_action) {
            $this->fail(
                sprintf(__('Cannot find the review to update (ID: %s).', 'site-reviews-authors'), $this->request->review_id)
            );
        }
    }
}
