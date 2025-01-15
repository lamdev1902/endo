<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Commands;

use GeminiLabs\SiteReviews\Addon\Authors\Application;
use GeminiLabs\SiteReviews\Addon\Authors\Defaults\UpdateReviewDefaults;
use GeminiLabs\SiteReviews\Addon\Authors\Validators\HoneypotValidator;
use GeminiLabs\SiteReviews\Addon\Authors\Validators\PermissionValidator;
use GeminiLabs\SiteReviews\Addon\Authors\Validators\ReviewValidator;
use GeminiLabs\SiteReviews\Arguments;
use GeminiLabs\SiteReviews\Commands\AbstractCommand;
use GeminiLabs\SiteReviews\Database\ReviewManager;
use GeminiLabs\SiteReviews\Defaults\CustomFieldsDefaults;
use GeminiLabs\SiteReviews\Modules\Avatar;
use GeminiLabs\SiteReviews\Modules\Sanitizer;
use GeminiLabs\SiteReviews\Modules\Validator\AkismetValidator;
use GeminiLabs\SiteReviews\Modules\Validator\BlacklistValidator;
use GeminiLabs\SiteReviews\Modules\Validator\CustomValidator;
use GeminiLabs\SiteReviews\Modules\Validator\DefaultValidator;
use GeminiLabs\SiteReviews\Modules\Validator\FriendlycaptchaValidator;
use GeminiLabs\SiteReviews\Modules\Validator\HcaptchaValidator;
use GeminiLabs\SiteReviews\Modules\Validator\ProcaptchaValidator;
use GeminiLabs\SiteReviews\Modules\Validator\RecaptchaV2InvisibleValidator;
use GeminiLabs\SiteReviews\Modules\Validator\RecaptchaV3Validator;
use GeminiLabs\SiteReviews\Modules\Validator\TurnstileValidator;
use GeminiLabs\SiteReviews\Modules\Validator\ValidateForm;
use GeminiLabs\SiteReviews\Request;

class UpdateReview extends AbstractCommand
{
    public $assigned_posts;
    public $assigned_terms;
    public $assigned_users;
    public $content;
    public $email;
    public $name;
    public $rating;
    public $title;
    public $type;
    public $url;

    protected array $attributes;
    protected Request $request;
    protected Arguments $validation;

    public function __construct(Request $request)
    {
        $request = $this->normalize($request);
        $this->request = $request;
        $this->validation = new Arguments();
        $this->setAttributes();
        $this->setProperties();
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
            $this->update();
        }
    }

    public function response(): array
    {
        $review = glsr_get_review($this->request->review_id);
        $rendered = [];
        foreach ($this->attributes as $args) {
            $rendered[] = (string) $review->build($args);
        }
        $title = $this->successful()
            ? __('Thank you!', 'site-reviews-authors')
            : '';
        return [
            'errors' => $this->validation->array('errors'),
            'message' => $this->validation->cast('message', 'string'),
            'rendered' => $rendered,
            'review' => $review->toArray(['email', 'ip_address']),
            'success' => $this->successful(),
            'title' => $title,
        ];
    }

    public function successful(): bool
    {
        return false === $this->validation->failed;
    }

    public function toArray(): array
    {
        $properties = (new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PUBLIC);
        $values = [];
        foreach ($properties as $property) {
            $values[$property->getName()] = $property->getValue($this);
        }
        $values = glsr(Application::class)->filterArray('update/review-values', $values, $this);
        $values = glsr(UpdateReviewDefaults::class)->merge($values);
        $values = array_merge($values, $this->custom());
        return $values;
    }

    public function validate(): bool
    {
        $validator = glsr(ValidateForm::class)->validate($this->request, [ // order is intentional
            ReviewValidator::class,
            PermissionValidator::class,
            DefaultValidator::class,
            CustomValidator::class,
            HoneypotValidator::class,
            BlacklistValidator::class,
            AkismetValidator::class,
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

    protected function custom(): array
    {
        $values = $this->request->toArray();
        unset($values['attributes']);
        return glsr(CustomFieldsDefaults::class)->filter($values);
    }

    protected function normalize(Request $request): Request
    {
        glsr(Application::ID)->action('review/request', $request);
        return $request;
    }

    protected function update(): void
    {
        $message = __('Your review could not be updated and the error has been logged. Please notify the site administrator.', 'site-reviews-authors');
        $review = glsr(ReviewManager::class)->update($this->request->review_id, $this->toArray());
        if ($review) {
            glsr(ReviewManager::class)->updateRating($review->ID, [
                'avatar' => glsr(Avatar::class)->generate($review),
            ]);
            glsr(Application::class)->action('review/updated', $review, $this);
            $message = $review->is_approved
                ? __('The review has been updated!', 'site-reviews-authors')
                : __('The review has been updated and is pending approval.', 'site-reviews-authors');
        }
        $this->validation->set('message', $message);
    }

    protected function setAttributes(): void
    {
        $this->attributes = [];
        $defaults = array_fill_keys(['form', 'hide', 'theme'], '');
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

    protected function setProperties(): void
    {
        $properties = (new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PUBLIC);
        $values = glsr(UpdateReviewDefaults::class)->restrict($this->request->toArray());
        foreach ($properties as $property) {
            $key = $property->getName();
            if (array_key_exists($key, $values)) {
                $property->setValue($this, $values[$key]);
            }
        }
    }
}
