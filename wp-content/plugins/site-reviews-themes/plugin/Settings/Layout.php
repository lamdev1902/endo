<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Settings;

class Layout extends Setting
{
    public $options = [
        'label', 'name', 'placeholder', 'required', 'tag', 'type', 'value'
    ];

    /**
     * {@inheritdoc}
     */
    protected function handle()
    {
        return _x('Custom: Number', 'admin-text', 'site-reviews-themes');
    }

    /**
     * {@inheritdoc}
     */
    protected function type()
    {
        return 'number';
    }

    /**
     * {@inheritdoc}
     */
    protected function validation()
    {
        return [
            'value' => 'number',
        ];
    }
}
