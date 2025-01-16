<?php

return [
    'reason' => [
        'label' => __('Please explain why', 'site-reviews-authors'),
        'required' => true,
        'type' => 'textarea',
    ],
    'confirm' => [
        'conditions' => 'all|reason:not:',
        'label' => __('I confirm that I want to delete the review.', 'site-reviews-authors'),
        'required' => true,
        'type' => 'toggle',
    ],
];
