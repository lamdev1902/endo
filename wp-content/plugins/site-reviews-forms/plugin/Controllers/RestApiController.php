<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Controllers;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\FormFields;
use GeminiLabs\SiteReviews\HookProxy;

class RestApiController
{
    use HookProxy;

    /**
     * @filter site-reviews/rest-api/reviews/parameters
     */
    public function filterReviewsParameters(array $parameters): array
    {
        $parameters['form'] = [
            'description' => _x('Render the review with a specific custom form (ID) review template; only works with the rendered parameter.', 'admin-text', 'site-reviews-forms'),
            'sanitize_callback' => 'absint',
            'type' => 'integer',
        ];
        return $parameters;
    }

    /**
     * @filter site-reviews/rest-api/summary/parameters
     */
    public function filterSummaryParameters(array $parameters): array
    {
        $parameters['rating_field'] = [
            'description' => _x('Use rating values of a custom rating field; use the custom rating Field Name as the value. ', 'admin-text', 'site-reviews-forms'),
            'type' => 'string',
        ];
        return $parameters;
    }

    public function registerRestFields(): void
    {
        register_rest_field(Application::POST_TYPE, 'fields', [
            'get_callback' => function ($object) {
                $postId = $object['id'] ?? 0;
                return glsr(FormFields::class)->normalizedFieldsIndexed($postId);
            },
            // 'schema' => [
            // ],
        ]);
    }
}
