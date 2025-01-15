<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Defaults;

use GeminiLabs\SiteReviews\Addon\Images\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Multilingual;
use GeminiLabs\SiteReviews\Modules\Sanitizer;

class GridImagesDefaults extends DefaultsAbstract
{
    /**
     * The values that should be cast before sanitization is run.
     * This is done before $sanitize and $enums.
     */
    public array $casts = [
        'terms' => 'string',
    ];

    /**
     * The values that should be constrained after sanitization is run.
     * This is done after $casts and $sanitize.
     */
    public array $enums = [
        'terms' => ['0', 'false', '1', 'true'],
        'status' => ['all', 'approved', 'pending', 'publish', 'unapproved'],
    ];

    /**
     * The keys that should be mapped to other keys.
     * Keys are mapped before the values are normalized and sanitized.
     * Note: Mapped keys should not be included in the defaults!
     */
    public array $mapped = [
        'display' => 'per_page',
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
        'offset' => 'min:0',
        'page' => 'min:1',
        'per_page' => 'min:0', // allow zero results
        'post__in' => 'array-int',
        'post__not_in' => 'array-int',
        'rating' => 'rating',
        'rating_field' => 'name',
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
            'offset' => 0,
            'page' => 1,
            'per_page' => 12,
            'post__in' => [],
            'post__not_in' => [],
            'rating' => '',
            'rating_field' => 'rating', // used for custom rating fields
            'status' => 'approved',
            'terms' => '',
            'type' => '',
            'user__in' => [],
            'user__not_in' => [],
        ];
    }

    /**
     * Normalize provided values, this always runs first.
     */
    protected function normalize(array $values = []): array
    {
        if ($postIds = Arr::getAs('array', $values, 'assigned_posts')) {
            $values['assigned_posts_types'] = [];
            foreach ($postIds as $postType) {
                if (!is_numeric($postType) && post_type_exists($postType)) {
                    $values['assigned_posts'] = []; // query only by assigned post types!
                    $values['assigned_posts_types'][] = $postType;
                }
            }
        } else {
            $postTypes = glsr(Sanitizer::class)->sanitizeArrayString(Arr::get($values, 'assigned_posts_types'));
            $values['assigned_posts_types'] = array_filter($postTypes, 'post_type_exists');
        }
        return $values;
    }

    /**
     * Finalize provided values, this always runs last.
     */
    protected function finalize(array $values = []): array
    {
        $values['assigned_posts'] = glsr(Multilingual::class)->getPostIds($values['assigned_posts']);
        $values['status'] = $this->finalizeStatus($values['status']);
        $values['terms'] = $this->finalizeTerms($values['terms']);
        return $values;
    }

    protected function finalizeStatus(string $value): int
    {
        $statuses = [
            'all' => -1,
            'approved' => 1,
            'pending' => 0,
            'publish' => 1,
            'unapproved' => 0,
        ];
        return $statuses[$value];
    }

    protected function finalizeTerms(string $value): int
    {
        if (!empty($value)) {
            return Cast::toInt(Cast::toBool($value));
        }
        return -1;
    }
}
