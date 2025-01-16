<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Defaults;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\ReviewsDefaults;
use GeminiLabs\SiteReviews\Modules\Multilingual;

class QueryFieldDefaults extends ReviewsDefaults
{
    /**
     * The values that should be constrained after sanitization is run.
     * This is done after $casts and $sanitize.
     */
    public array $enums = [
        'status' => ['all', 'approved', 'pending', 'publish', 'unapproved'],
        'terms' => ['0', 'false', '1', 'true'],
    ];

    /**
     * The keys that should be mapped to other keys.
     * Keys are mapped before the values are normalized and sanitized.
     * Note: Mapped keys should not be included in the defaults!
     */
    public array $mapped = [
        'exclude' => 'post__not_in',
        'include' => 'post__in',
    ];

    /**
     * The values that should be sanitized.
     * This is done after $casts and before $enums.
     */
    public array $sanitize = [
        'assigned_posts' => 'post-ids',
        'assigned_posts_types' => 'array-string',
        'assigned_terms' => 'term-ids',
        'assigned_users' => 'user-ids',
        'post__in' => 'array-int',
        'post__not_in' => 'array-int',
        'field' => 'name',
        'form' => 'form-id',
        'status' => 'name',
        'type' => 'slug',
        'user__in' => 'user-ids',
        'user__not_in' => 'user-ids',
    ];

    protected function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'assigned_posts' => '',
            'assigned_posts_types' => [],
            'assigned_terms' => '',
            'assigned_users' => '',
            'post__in' => [],
            'post__not_in' => [],
            'field' => 'unknown',
            'form' => 0,
            'status' => 'approved',
            'terms' => '',
            'type' => '',
            'user__in' => [],
            'user__not_in' => [],
        ];
    }

    /**
     * Finalize provided values, this always runs last.
     */
    protected function finalize(array $values = []): array
    {
        $values['assigned_posts'] = glsr(Multilingual::class)->getPostIdsForAllLanguages($values['assigned_posts']);
        $values['assigned_terms'] = glsr(Multilingual::class)->getTermIdsForAllLanguages($values['assigned_terms']);
        $values['form'] = $values['form'] ?: 0;
        $values['field'] = $values['field'] ?: 'unknown';
        $values['status'] = $this->finalizeStatus($values['status']);
        $values['terms'] = $this->finalizeTerms($values['terms']);
        return $values;
    }
}
