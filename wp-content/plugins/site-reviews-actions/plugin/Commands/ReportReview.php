<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Commands;

use GeminiLabs\SiteReviews\Addon\Actions\Application;
use GeminiLabs\SiteReviews\Addon\Actions\Defaults\ReportReviewDefaults;
use GeminiLabs\SiteReviews\Addon\Actions\Email;
use GeminiLabs\SiteReviews\Addon\Actions\Template;
use GeminiLabs\SiteReviews\Addon\Actions\Validators\HoneypotValidator;
use GeminiLabs\SiteReviews\Addon\Actions\Validators\ReportReviewValidator;
use GeminiLabs\SiteReviews\Arguments;
use GeminiLabs\SiteReviews\Commands\AbstractCommand;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Database\OptionManager;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Modules\Html\TemplateTags;
use GeminiLabs\SiteReviews\Modules\Sanitizer;
use GeminiLabs\SiteReviews\Modules\Validator\FriendlycaptchaValidator;
use GeminiLabs\SiteReviews\Modules\Validator\HcaptchaValidator;
use GeminiLabs\SiteReviews\Modules\Validator\ProcaptchaValidator;
use GeminiLabs\SiteReviews\Modules\Validator\RecaptchaV2InvisibleValidator;
use GeminiLabs\SiteReviews\Modules\Validator\RecaptchaV3Validator;
use GeminiLabs\SiteReviews\Modules\Validator\TurnstileValidator;
use GeminiLabs\SiteReviews\Modules\Validator\ValidateForm;
use GeminiLabs\SiteReviews\Request;
use GeminiLabs\SiteReviews\Review;

class ReportReview extends AbstractCommand
{
    public Request $request;
    public Review $review;

    protected Arguments $validation;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->validation = new Arguments();
        $this->review = glsr_get_review($this->request->review_id);
    }

    public function handle(): void
    {
        if (!$this->validate()) {
            return;
        }
        if ($this->flagReview()) {
            $this->insertRecord();
            $this->validation->set('message', __('Thanks for reporting the review. We’ll check if it meets our guidelines and remove it if it doesn’t.', 'site-reviews-actions'));
            $this->sendNotification();
            $this->sendThankYouEmail();
            return;
        }
        $this->validation->set('message', __('The review could not be reported and the error has been logged. Please notify the site administrator.', 'site-reviews-actions'));
    }

    public function response(): array
    {
        $title = $this->successful()
            ? __('Thank you!', 'site-reviews-actions')
            : '';
        return [
            'errors' => $this->validation->array('errors'),
            'message' => $this->validation->cast('message', 'string'),
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
        $validator = glsr(ValidateForm::class)->validate($this->request, [
            ReportReviewValidator::class,
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

    protected function flagReview(): bool
    {
        $result = glsr(Database::class)->update('ratings',
            ['is_flagged' => true],
            ['ID' => $this->review->rating_id]
        );
        return false !== $result;
    }

    protected function insertRecord(): bool
    {
        $data = glsr(ReportReviewDefaults::class)->restrict($this->request->toArray());
        $result = glsr(Database::class)->insert('actions_log', [
            'action' => 'report',
            'data' => maybe_serialize($data),
            'date' => current_time('mysql'),
            'ip_address' => Helper::getIpAddress(),
            'rating_id' => $this->review->rating_id,
            'user_id' => get_current_user_id(),
        ]);
        return false !== $result;
    }

    protected function recipients(): array
    {
        $emails = [];
        $types = glsr_get_option('general.notifications', [], 'array');
        if (in_array('admin', $types)) {
            $emails[] = glsr(OptionManager::class)->wp('admin_email');
        }
        if (in_array('custom', $types)) {
            $customEmails = glsr_get_option('general.notification_email', '', 'string');
            $customEmails = str_replace([' ', ',', ';'], ',', $customEmails);
            $customEmails = explode(',', $customEmails);
            $emails = array_merge($emails, $customEmails);
        }
        $emails = glsr()->filterArray('notification/emails', $emails, $this->review);
        $emails = array_map([glsr(Sanitizer::class), 'sanitizeEmail'], $emails);
        $emails = Arr::reindex(Arr::unique($emails));
        return $emails;
    }

    protected function sendNotification(): bool
    {
        $data = glsr(ReportReviewDefaults::class)->restrict($this->request->toArray());
        $tags = glsr(TemplateTags::class)->tags($this->review, [
            'include' => [
                'edit_url',
                'review_content',
                'review_id',
                'review_rating',
                'review_stars',
                'review_title',
                'site_title',
                'site_url',
            ],
        ]);
        $message = trim(glsr(Application::class)->option('report_notification', '', 'string'));
        if (empty($message)) {
            glsr_log()->warning("Cannot send an empty report notification email (check the settings).");
            return false;
        }
        $message = glsr(Template::class)->interpolate($message, 'notification/message', [
            'context' => wp_parse_args($tags, [
                'report_email' => $data['email'],
                'report_message' => $data['message'],
                'report_reason' => $data['reason'],
            ]),
        ]);
        $args = [
            'to' => $this->recipients(),
            'message' => $message,
            'subject' => sprintf(__('A review has been flagged: %s', 'site-reviews-actions'), $data['reason']),
        ];
        $email = glsr(Email::class)->compose($args, [
            'review' => $this->review,
        ]);
        return $email->send();
    }

    protected function sendThankYouEmail(): bool
    {
        $recipient = $this->request->sanitize('email', 'email');
        if (empty($recipient)) {
            glsr_log()->warning("Cannot send a report confirmation to an invalid email address: {$this->request->email} (Review ID: {$this->review->ID}).");
            return false;
        }
        $message = trim(glsr(Application::class)->option('report_confirmation', '', 'string'));
        if (empty($message)) {
            glsr_log()->warning("Cannot send an empty report confirmation email (check the settings).");
            return false;
        }
        $args = [
            'to' => $recipient,
            'message' => $message,
            'subject' => __('Thank you for reporting a review.', 'site-reviews-actions'),
            'template-tags' => glsr(TemplateTags::class)->tags($this->review, [
                'include' => [
                    'site_title',
                    'site_url',
                ],
            ]),
        ];
        $email = glsr(Email::class)->compose($args, [
            'review' => $this->review,
        ]);
        return $email->send();
    }
}
