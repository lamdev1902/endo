<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Commands;

use GeminiLabs\SiteReviews\Addon\Authors\Validators\DeleteReviewValidator;
use GeminiLabs\SiteReviews\Addon\Authors\Validators\HoneypotValidator;
use GeminiLabs\SiteReviews\Addon\Authors\Validators\PermissionValidator;
use GeminiLabs\SiteReviews\Addon\Authors\Validators\ReviewValidator;
use GeminiLabs\SiteReviews\Arguments;
use GeminiLabs\SiteReviews\Commands\AbstractCommand;
use GeminiLabs\SiteReviews\Modules\Validator\FriendlycaptchaValidator;
use GeminiLabs\SiteReviews\Modules\Validator\HcaptchaValidator;
use GeminiLabs\SiteReviews\Modules\Validator\ProcaptchaValidator;
use GeminiLabs\SiteReviews\Modules\Validator\RecaptchaV2InvisibleValidator;
use GeminiLabs\SiteReviews\Modules\Validator\RecaptchaV3Validator;
use GeminiLabs\SiteReviews\Modules\Validator\TurnstileValidator;
use GeminiLabs\SiteReviews\Modules\Validator\ValidateForm;
use GeminiLabs\SiteReviews\Request;

class DeleteReview extends AbstractCommand
{
    protected Request $request;
    protected Arguments $validation;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->validation = new Arguments();
    }

    public function __get($property)
    {
        if (in_array($property, ['request'])) {
            return $this->$property;
        }
    }

    public function handle(): void
    {
        if ($this->validate()) {
            $this->deleteReview();
        }
    }

    public function response(): array
    {
        $title = $this->successful()
            ? __('Success', 'site-reviews-authors')
            : '';
        return [
            'errors' => $this->validation->array('errors'),
            'message' => $this->validation->cast('message', 'string') ?: __('The review was deleted.', 'site-reviews-authors'),
            'success' => $this->successful(),
            'title' => $title,
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
            DeleteReviewValidator::class,
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

    protected function deleteReview(): bool
    {
        $postId = $this->request->cast('review_id', 'int');
        if (wp_trash_post($postId)) {
            update_post_meta($postId, '_wp_trash_meta_reason', $this->request->sanitize('reason', 'text'));
            update_post_meta($postId, '_wp_trash_meta_user', get_current_user_id());
            return true;
        }
        $this->validation->set('message', __('The review could not be deleted and the error has been logged. Please notify the site administrator.', 'site-reviews-authors'));
        return false;
    }
}
