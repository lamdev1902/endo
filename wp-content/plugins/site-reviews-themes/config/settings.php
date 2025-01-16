<?php

return [
    'settings.addons.themes.swiper_library' => [
        'class' => 'regular-text',
        'default' => 'swiper',
        'label' => _x('Swiper Library', 'setting label (admin-text)', 'site-reviews-themes'),
        'options' => [
            'splide' => _x('Use Splide (experimental)', 'setting option (admin-text)', 'site-reviews-themes'),
            'swiper' => _x('Use Swiper (default)', 'setting option (admin-text)', 'site-reviews-themes'),
        ],
        'sanitizer' => 'text',
        'tooltip' => sprintf('%s<ul><li>%s</li><li>%s</li></ul>',
            _x('Select the swiper library that you would like to use for the carousel.', 'setting tooltip (admin-text)', 'site-reviews-themes'),
            _x('<a href="https://splidejs.com" target="_blank">Splide</a> is a lightweight, flexible and accessible slider; it is also used by the Review Images add-on.', 'setting tooltip (admin-text)', 'site-reviews-themes'),
            _x('<a href="https://swiperjs.com" target="_blank">Swiper</a> is a more popular slider, but not as lightweight as Splide.', 'setting tooltip (admin-text)', 'site-reviews-themes')
        ),
        'type' => 'select',
    ],
    'settings.addons.themes.swiper_version' => [
        'class' => 'regular-text',
        'default' => '8',
        'depends_on' => [
            'settings.addons.themes.swiper_library' => 'swiper',
        ],
        'label' => _x('Swiper Version', 'setting label (admin-text)', 'site-reviews-themes'),
        'options' => [
            '6' => 'v6.x.x',
            '7' => 'v7.x.x',
            '8' => 'v8.x.x',
        ],
        'sanitizer' => 'text',
        'tooltip' => _x('If your theme is already using the library, you may need to change the version to match the one in use.', 'setting tooltip (admin-text)', 'site-reviews-themes'),
        'type' => 'select',
    ],
    'settings.addons.themes.swiper_assets' => [
        'default' => 'yes',
        'label' => _x('Load Swiper Assets?', 'setting label (admin-text)', 'site-reviews-themes'),
        'sanitizer' => 'text',
        'tooltip' => _x('Would you like to load the javascript and CSS of the selected library? If your theme is already using the library, you may want to disable this.', 'setting tooltip (admin-text)', 'site-reviews-themes'),
        'type' => 'yes_no',
    ],
];
