<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Defaults;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;
use GeminiLabs\SiteReviews\Helpers\Arr;

class FieldDefaults extends DefaultsAbstract
{
    /**
     * The values that should be cast before sanitization is run.
     * This is done before $sanitize and $enums.
     */
    public array $casts = [
        'hidden' => 'bool',
        'required' => 'bool',
    ];

    /**
     * The values that should be sanitized.
     * This is done after $casts and before $enums.
     */
    public array $sanitize = [
        'after' => 'text',
        'class' => 'attr-class',
        'description' => 'text-html',
        'format' => 'text',
        'handle' => 'text',
        'id' => 'id',
        'label' => 'text-html',
        'labels' => 'array-string',
        'maxlength' => 'min:0',
        'minlength' => 'min:0',
        'name' => 'name',
        'options' => 'array-consolidate',
        'placeholder' => 'text',
        'responsive_width' => 'array-consolidate',
        'tag' => 'key',
        'tag_label' => 'text',
        'text' => 'text',
        'type' => 'name',
        // 'value' => 'string', // disabled because checkbox field value can be an array
    ];

    protected function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'class' => '',
            'format' => '',
            'label' => '',
            'name' => '',
            'options' => [],
            'responsive_width' => [],
            'type' => '',
            'value' => '',
        ];
    }

    /**
     * Finalize provided values, this always runs last.
     */
    protected function finalize(array $values = []): array
    {
        $widths = array_fill_keys(['sm', 'md', 'lg', 'xl'], 'gl-col-100'); // order is intentional
        $values['responsive_width'] = wp_parse_args(array_filter($values['responsive_width']), $widths);
        $values['responsive_width'] = Arr::restrictKeys($values['responsive_width'], array_keys($widths));
        return $values;
    }

    /**
     * Normalize provided values, this always runs first.
     */
    protected function normalize(array $values = []): array
    {
        unset($values['expanded']);
        return $values;
    }
}
