<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Defaults;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class TagDefaults extends DefaultsAbstract
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * These tags are always available in a review template
     */
    protected function defaults(): array
    {
        return [
            'author' => 'review_author',
            'assigned_links' => 'review_assigned_links',
            'assigned_posts' => 'review_assigned_posts',
            'assigned_terms' => 'review_assigned_terms',
            'assigned_users' => 'review_assigned_users',
            'avatar' => 'review_avatar',
            'date' => 'review_date',
            'rating' => 'review_rating',
            'response' => 'review_response',
            'verified' => 'review_verified',
        ];
    }
}
