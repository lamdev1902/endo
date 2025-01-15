<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Defaults;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;
use GeminiLabs\SiteReviews\Helpers\Arr;

class MockFieldDefaults extends DefaultsAbstract
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'custom' => false,
            'name' => '',
            'tag' => '',
            'tag_label' => '',
            'type' => '',
            'value' => '',
        ];
    }

    /**
     * Normalize provided values, this always runs first.
     */
    protected function normalize(array $values = []): array
    {
        $values['custom'] = !str_starts_with(Arr::get($values, 'type'), 'review_');
        return $values;
    }
}
