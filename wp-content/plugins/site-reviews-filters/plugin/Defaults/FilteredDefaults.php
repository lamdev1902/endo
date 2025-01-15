<?php

namespace GeminiLabs\SiteReviews\Addon\Filters\Defaults;

use GeminiLabs\SiteReviews\Addon\Filters\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Helpers\Url;

class FilteredDefaults extends DefaultsAbstract
{
    /**
     * The values that should be cast before sanitization is run.
     * This is done before $sanitize and $enums.
     */
    public array $casts = [
        'filter_by_term' => 'int',
    ];

    /**
     * The values that should be sanitized.
     * This is done after $casts and before $enums.
     */
    public array $sanitize = [
        'filter_by_rating' => 'text',
        'search_for' => 'text',
        'sort_by' => 'text',
    ];

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'filter_by_rating' => '',
            'filter_by_term' => '',
            'search_for' => '',
            'sort_by' => '',
        ];
    }

    /**
     * Normalize provided values, this always runs first.
     */
    protected function normalize(array $values = []): array
    {
        $args = array_fill_keys(array_keys($this->defaults), FILTER_SANITIZE_FULL_SPECIAL_CHARS); // restrict input keys to defaults
        $parameters = Cast::toArray(filter_input_array(INPUT_GET, $args, false));
        $request = glsr()->args(glsr()->retrieve(glsr()->paged_handle));
        if (!$request->isEmpty()) { // check if this is a pagination request
            $urlParameters = filter_var_array(Url::queries($request->url), $args, false);
            $parameters = wp_parse_args($urlParameters, $parameters);
        }
        $values = wp_parse_args($parameters, $values);
        $values = array_map('strtolower', $values); // cast to lowercase!
        return $values;
    }
}
