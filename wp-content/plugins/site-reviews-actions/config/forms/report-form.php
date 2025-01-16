<?php

return [
    'reason' => [
        'group' => 'step_1',
        'options' => [ // order is intentional
            __('Fake', 'site-reviews-actions') => [
                __('Fake', 'site-reviews-actions'),
                __('Paid for, inauthentic, spam', 'site-reviews-actions'),
            ],
            __('Inappropriate', 'site-reviews-actions') => [
                __('Inappropriate', 'site-reviews-actions'),
                __('Disrespectful, hateful, obscene', 'site-reviews-actions'),
            ],
            __('Personal information', 'site-reviews-actions') => [
                __('Personal information', 'site-reviews-actions'),
                __('Text that breaches privacy laws', 'site-reviews-actions'),
            ],
            __('Off topic', 'site-reviews-actions') => [
                __('Off topic', 'site-reviews-actions'),
                __('Not about the product', 'site-reviews-actions'),
            ],
            __('Other', 'site-reviews-actions') => [
                __('Other', 'site-reviews-actions'),
                __('Something else', 'site-reviews-actions'),
            ],
        ],
        'required' => true,
        'type' => 'radio',
    ],
    'message' => [
        'group' => 'step_2',
        'label' => __('Please explain why', 'site-reviews-actions'),
        'minlength' => 10,
        'required' => true,
        'type' => 'textarea',
    ],
    'email' => [
        'group' => 'step_3',
        'label' => __('My Email Address', 'site-reviews-actions'),
        'required' => true,
        'type' => 'email',
    ],
    'confirm' => [
        'conditions' => 'all|message:not:',
        'group' => 'step_3',
        'label' => __('I confirm that the information I’ve provided here is true and correct.', 'site-reviews-actions'),
        'required' => true,
        'type' => 'toggle',
    ],
];
