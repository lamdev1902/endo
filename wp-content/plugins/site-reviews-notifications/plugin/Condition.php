<?php

namespace GeminiLabs\SiteReviews\Addon\Notifications;

use GeminiLabs\SiteReviews\Addon\Notifications\Defaults\ConditionDefaults;
use GeminiLabs\SiteReviews\Arguments;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Review;

/**
 * @property string $field
 * @property string $operator
 * @property mixed $value
 */
class Condition extends Arguments
{
    /** @var Notification */
    public $notification;

    /** @var Review */
    public $review;

    public function __construct($values, Notification $notification)
    {
        $this->notification = $notification;
        $this->review = $notification->review;
        $conditions = glsr(ConditionDefaults::class)->restrict($values);
        parent::__construct($conditions);
    }

    public function isValid(array $data = []): bool
    {
        $method = Helper::buildMethodName('validate', $this->get('field'));
        $result = false;
        if (method_exists($this, $method)) {
            $result = call_user_func([$this, $method], $data);
        }
        return glsr(Application::class)->filterBool('condition/is-valid', $result, $this, $data);
    }

    protected function isArrayValid(array $values): bool
    {
        $value = $this->cast('value', 'string');
        $values = array_map(fn ($val) => Cast::toString($val), $values);
        $values = array_values(array_unique($values));
        switch ($this->get('operator')) {
            case 'contains':
                return !empty(array_filter($values, fn ($val) => str_contains($val, $value)));
            case 'equals':
                return in_array($value, $values);
            case 'not':
                return !in_array($value, $values);
        }
        return false;
    }

    protected function isIntArrayValid(array $values): bool
    {
        $values = Arr::uniqueInt($values);
        switch ($this->get('operator')) {
            case 'contains':
                return 0 === count(array_diff(Arr::uniqueInt($this->get('value')), $values));
            case 'equals':
                return Arr::compare(Arr::uniqueInt($this->get('value')), $values);
            case 'not':
                return 0 === count(array_intersect(Arr::uniqueInt($this->get('value')), $values));
        }
        return false;
    }

    protected function validateAssignedPost(): bool
    {
        return $this->isIntArrayValid($this->review->assigned_posts);
    }

    protected function validateAssignedPostAuthor(): bool
    {
        return $this->isIntArrayValid(
            wp_list_pluck($this->review->assignedPosts(), 'post_author')
        );
    }

    protected function validateAssignedPostType(): bool
    {
        $postTypes = wp_list_pluck($this->review->assignedPosts(), 'post_type');
        $postTypes = array_unique($postTypes);
        return $this->isArrayValid($postTypes);
    }

    protected function validateAssignedUser(): bool
    {
        return $this->isIntArrayValid($this->review->assigned_users);
    }

    protected function validateAssignedTerm(): bool
    {
        return $this->isIntArrayValid($this->review->assigned_terms);
    }

    protected function validateRating(): bool
    {
        $value = $this->get('value');
        if (in_array($this->get('operator'), ['equals', 'greater', 'less']) && !is_numeric($value)) {
            $value = -1;
        }
        switch ($this->get('operator')) {
            case 'contains':
                return in_array($this->review->rating, Arr::uniqueInt($value));
            case 'equals':
                return $this->review->rating === Cast::toInt($value);
            case 'greater':
                return $this->review->rating > Cast::toInt($value);
            case 'less':
                return $this->review->rating < Cast::toInt($value);
            case 'not':
                return !in_array($this->review->rating, Arr::uniqueInt($value));
        }
        return false;
    }

    protected function validateReview(array $data = []): bool
    {
        if ('approved' === $this->get('value') && 'publish' === Arr::get($data, 'status')) {
            return $this->review->is_approved;
        }
        if ('unapproved' === $this->get('value') && 'pending' === Arr::get($data, 'status')) {
            return !$this->review->is_approved;
        }
        if ('responded' === $this->get('value')) {
            $hasResponse = !empty($this->review->response);
            $hasResponseBy = !empty(glsr(Database::class)->meta($this->review->ID, 'response_by'));
            return $hasResponse && !$hasResponseBy;
        }
        if ('verified' === $this->get('value')) {
            $isVerified = $this->review->is_verified;
            $hasVerifiedOn = !empty(glsr(Database::class)->meta($this->review->ID, 'verified_on'));
            return $isVerified && $hasVerifiedOn;
        }
        return false;
    }

    protected function validateUser(): bool
    {
        if ('is_guest' === $this->get('value')) {
            return !is_user_logged_in();
        }
        if ('is_user' === $this->get('value')) {
            return is_user_logged_in();
        }
        return false;
    }
}
