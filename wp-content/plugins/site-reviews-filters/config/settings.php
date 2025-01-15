<?php

return [
    'settings.addons.filters.rating_bars' => [
        'class' => 'regular-text',
        'default' => '',
        'label' => _x('Rating Summary Bars', 'setting label (admin-text)', 'site-reviews-filters'),
        'options' => [
            '' => _x('Filter Using Links', 'setting option (admin-text)', 'site-reviews-filters'),
            'checkbox' => _x('Filter Using Checkboxes', 'setting option (admin-text)', 'site-reviews-filters'),
        ],
        'sanitizer' => 'text',
        'tooltip' => _x('Select whether you want to make the rating bars in the summary clickable or use checkboxes instead.', 'setting description (admin-text)', 'site-reviews-filters'),
        'type' => 'select',
    ],
    'settings.addons.filters.search' => [
        'class' => 'regular-text',
        'default' => '',
        'label' => _x('Search Behaviour', 'setting label (admin-text)', 'site-reviews-filters'),
        'options' => [
            '' => _x('Search Content and Titles', 'setting option (admin-text)', 'site-reviews-filters'),
            'content' => _x('Search Only Content', 'setting option (admin-text)', 'site-reviews-filters'),
            'title' => _x('Search Only Titles', 'setting option (admin-text)', 'site-reviews-filters'),
        ],
        'sanitizer' => 'text',
        'tooltip' => _x('This restricts the review fields which are searched', 'setting description (admin-text)', 'site-reviews-filters'),
        'type' => 'select',
    ],
];
