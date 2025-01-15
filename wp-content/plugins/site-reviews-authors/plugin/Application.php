<?php

namespace GeminiLabs\SiteReviews\Addon\Authors;

use GeminiLabs\SiteReviews\Addons\Addon;
use GeminiLabs\SiteReviews\Review;

final class Application extends Addon
{
    public const ID = 'site-reviews-authors';
    public const LICENSED = true;
    public const NAME = 'Review Authors';
    public const SLUG = 'authors';

    public function canDeleteOnFrontend(Review $review): bool
    {
        if (!$review->isValid()) {
            return false; // invalid review
        }
        if (!$this->option('delete_reviews', false, 'bool')) {
            return false; // deleting is disabled
        }
        $user = wp_get_current_user();
        $roles = $this->option('delete_roles', [], 'array');
        $hasRole = !empty(array_intersect($roles, (array) $user->roles));
        if (glsr(static::ID)->filterBool('can/delete', false, $review, $hasRole)) {
            return true;
        }
        if (!$hasRole) {
            return false; // user role not allowed
        }
        if ($user->ID === $review->author_id) {
            return true; // is review author
        }
        if (glsr()->can('delete_post', $review->ID)) {
            return true; // has "delete_site-reviews" capability
        }
        return false;
    }

    public function canEditOnFrontend(Review $review): bool
    {
        if (!$review->isValid()) {
            return false; // invalid review
        }
        if (!$this->option('edit_reviews', false, 'bool')) {
            return false; // editing is disabled
        }
        $user = wp_get_current_user();
        $roles = $this->option('roles', [], 'array');
        $hasRole = !empty(array_intersect($roles, (array) $user->roles));
        if (glsr(static::ID)->filterBool('can/edit', false, $review, $hasRole)) {
            return true;
        }
        if (!$hasRole) {
            return false; // user role not allowed
        }
        if ($user->ID === $review->author_id) {
            return true; // is review author
        }
        if (glsr()->can('edit_post', $review->ID)) {
            return true; // has "edit_site-reviews" capability
        }
        return false;
    }

    public function canRespondOnFrontend(Review $review): bool
    {
        if (!$review->isValid()) {
            return false; // invalid review
        }
        if (!$this->option('respond_to_reviews', false, 'bool')) {
            return false; // responding is disabled
        }
        $user = wp_get_current_user();
        $roles = $this->option('respond_to_roles', [], 'array');
        $hasRole = !empty(array_intersect($roles, (array) $user->roles));
        if (glsr(static::ID)->filterBool('can/respond', false, $review, $hasRole)) {
            return true;
        }
        if (!$hasRole) {
            return false; // user role not allowed
        }
        if ($user->ID === $review->author_id && !in_array('administrator', (array) $user->roles)) {
            return false; // only admin can respond to own reviews
        }
        if (glsr()->can('respond_to_post', $review->ID)) {
            return true; // has "respond_to_site-reviews" capability
        }
        return false;
    }
}
