<?php

return [
    'search_for' => [
        'class' => 'wp-block-search__input',
        'is_raw' => true,
        'placeholder' => esc_attr_x('Search reviews &hellip;', 'placeholder', 'site-reviews-filters'),
        'type' => 'search',
        'value' => filter_input(INPUT_GET, 'search_for', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
    ],
    'sort_by' => [
        'options' => [
            '' => _x('Most Recent', 'sort by option', 'site-reviews-filters'),
            'rating' => _x('Top Rated', 'sort by option', 'site-reviews-filters'),
        ],
        'type' => 'select',
        'value' => filter_input(INPUT_GET, 'sort_by', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
    ],
    'filter_by_rating' => [
        'options' => [
            '' => _x('All stars', 'filter-by option', 'site-reviews-filters'),
            '5' => _x('5 star only', 'filter-by option', 'site-reviews-filters'),
            '4' => _x('4 star only', 'filter-by option', 'site-reviews-filters'),
            '3' => _x('3 star only', 'filter-by option', 'site-reviews-filters'),
            '2' => _x('2 star only', 'filter-by option', 'site-reviews-filters'),
            '1' => _x('1 star only', 'filter-by option', 'site-reviews-filters'),
            'positive' => _x('All positive', 'filter-by option', 'site-reviews-filters'),
            'critical' => _x('All critical', 'filter-by option', 'site-reviews-filters'),
        ],
        'type' => 'select',
        'value' => filter_input(INPUT_GET, 'filter_by_rating', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
    ],
];
