<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Controllers;

use GeminiLabs\SiteReviews\Controllers\Api\Version1\Response\Prepare;
use GeminiLabs\SiteReviews\HookProxy;

class RestApiController
{
    use HookProxy;

    /**
     * @param mixed $value
     * @filter site-reviews/rest-api/reviews/prepare/images
     */
    public function filterReviewsPrepareImages($value, Prepare $prepare): array
    {
        $images = $prepare->review->images();
        array_walk($images, function (&$image) {
            $image = $image->toArray();
            $image = array_change_key_case($image, CASE_LOWER);
        });
        return $images;
    }

    /**
     * @filter site-reviews/rest-api/reviews/schema/properties
     */
    public function filterReviewsSchemaProperties(array $properties): array
    {
        $properties['images'] = [
            'context' => ['edit', 'view'],
            'description' => _x('The images attached to the review.', 'admin-text', 'site-reviews-images'),
            'items' => [
                'properties' => [
                    'caption' => [
                        'type' => 'string',
                    ],
                    'height' => [
                        'type' => 'integer',
                    ],
                    'id' => [
                        'type' => 'integer',
                    ],
                    'large_height' => [
                        'type' => 'integer',
                    ],
                    'large_src' => [
                        'type' => 'string',
                    ],
                    'large_width' => [
                        'type' => 'integer',
                    ],
                    'src' => [
                        'type' => 'string',
                    ],
                    'width' => [
                        'type' => 'integer',
                    ],
                ],
                'type' => 'object',
            ],
            'type' => 'array',
        ];
        return $properties;
    }
}
