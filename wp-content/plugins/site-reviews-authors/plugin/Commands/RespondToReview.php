<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Commands;

use GeminiLabs\SiteReviews\Addon\Authors\Validators\HoneypotValidator;
use GeminiLabs\SiteReviews\Addon\Authors\Validators\PermissionValidator;
use GeminiLabs\SiteReviews\Addon\Authors\Validators\RespondToReviewValidator;
use GeminiLabs\SiteReviews\Addon\Authors\Validators\ReviewValidator;
use GeminiLabs\SiteReviews\Arguments;
use GeminiLabs\SiteReviews\Commands\AbstractCommand;
use GeminiLabs\SiteReviews\Database\ReviewManager;
use GeminiLabs\SiteReviews\Modules\Sanitizer;
use GeminiLabs\SiteReviews\Modules\Validator\FriendlycaptchaValidator;
use GeminiLabs\SiteReviews\Modules\Validator\HcaptchaValidator;
use GeminiLabs\SiteReviews\Modules\Validator\ProcaptchaValidator;
use GeminiLabs\SiteReviews\Modules\Validator\RecaptchaV2InvisibleValidator;
use GeminiLabs\SiteReviews\Modules\Validator\RecaptchaV3Validator;
use GeminiLabs\SiteReviews\Modules\Validator\TurnstileValidator;
use GeminiLabs\SiteReviews\Modules\Validator\ValidateForm;
use GeminiLabs\SiteReviews\Request;

class RespondToReview extends AbstractCommand
{
    protected array $attributes;
    protected Request $request;
    protected Arguments $validation;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->validation = new Arguments();
        $this->setAttributes();
    }

    public function __get($property)
    {
        if (in_array($property, ['attributes', 'request'])) {
            return $this->$property;
        }
    }

    public function handle(): void
    {
        if ($this->validate()) {
            $this->respondToReview();
        }
    }

    public function response(): array
    {
        $review = glsr_get_review($this->request->review_id);
        $rendered = [];
        foreach ($this->attributes as $args) {
            $rendered[] = (string) $review->build($args);
        }
        return [
            'errors' => $this->validation->array('errors'),
            'message' => $this->validation->cast('message', 'string'),
            'rendered' => $rendered,
            'review' => $review->toArray(['email', 'ip_address']),
            'success' => $this->successful(),
            'title' => '',
        ];
    }

    public function successful(): bool
    {
        return false === $this->validation->failed;
    }

    public function validate(): bool
    {
        $validator = glsr(ValidateForm::class)->validate($this->request, [ // order is intentional
            ReviewValidator::class,
            PermissionValidator::class,
            RespondToReviewValidator::class,
            HoneypotValidator::class,
            FriendlycaptchaValidator::class,
            HcaptchaValidator::class,
            ProcaptchaValidator::class,
            RecaptchaV2InvisibleValidator::class,
            RecaptchaV3Validator::class,
            TurnstileValidator::class,
        ]);
        $this->validation = $validator->result();
        return $validator->isValid();
    }

    protected function respondToReview(): bool
    {
        $postId = $this->request->cast('review_id', 'int');
        if (glsr(ReviewManager::class)->updateResponse($postId, $this->request->toArray())) {
            return true;
        }
        $this->validation->set('message', __('Your response could not be saved and the error has been logged. Please notify the site administrator.', 'site-reviews-authors'));
        return false;
    }

    protected function setAttributes(): void
    {
        $this->attributes = [];
        $defaults = array_fill_keys(['form', 'theme'], '');
        $values = glsr(Sanitizer::class)->sanitizeJson($this->request->attributes);
        foreach ($values as $value) {
            $this->attributes[] = is_array($value)
                ? shortcode_atts($defaults, $value)
                : $defaults;
        }
        if (empty($this->attributes)) {
            $this->attributes[] = $defaults;
        }
    }
}
