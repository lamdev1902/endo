<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Controllers;

use GeminiLabs\SiteReviews\HookProxy;

class RestApiController
{
    use HookProxy;

    /**
     * @filter site-reviews/rest-api/reviews/parameters
     */
    public function filterReviewsParameters(array $parameters): array
    {
        $parameters['theme'] = [
            'description' => _x('Render the review with a specific custom review theme (ID); only works with the rendered parameter.', 'admin-text', 'site-reviews-themes'),
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
        $parameters['theme'] = [
            'description' => _x('Render the summary with a specific custom review theme (ID); only works with the rendered parameter.', 'admin-text', 'site-reviews-themes'),
            'sanitize_callback' => 'absint',
            'type' => 'integer',
        ];
        return $parameters;
    }
}
