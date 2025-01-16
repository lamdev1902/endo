<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Defaults;

use GeminiLabs\SiteReviews\Addon\Authors\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class UpdateReviewDefaults extends DefaultsAbstract
{
    /**
     * The values that should be sanitized.
     * This is done after $casts and before $enums.
     */
    public array $sanitize = [
        'assigned_posts' => 'post-ids',
        'assigned_terms' => 'term-ids',
        'assigned_users' => 'user-ids',
        'content' => 'text-multiline',
        'email' => 'email', // because we are updating an existing review
        'form_id' => 'id',
        'name' => 'text', // because we are updating an existing review
        'rating' => 'rating',
        'title' => 'text',
        'type' => 'slug',
        'url' => 'url',
    ];

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'assigned_posts' => '',
            'assigned_terms' => '',
            'assigned_users' => '',
            'content' => '',
            'email' => '',
            'form_id' => '',
            'name' => '',
            'rating' => '',
            'title' => '',
            'type' => '',
            'url' => '',
        ];
    }

    /**
     * Finalize provided values, this always runs last.
     */
    protected function finalize(array $values = []): array
    {
        $types = glsr()->retrieveAs('array', 'review_types');
        if (!array_key_exists($values['type'], $types)) {
            $values['type'] = 'local';
        }
        return $values;
    }
}
