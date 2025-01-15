<?php

return [
    'settings.addons.forms.field_description' => [
        'class' => 'regular-text',
        'default' => 'under_label',
        'label' => _x('Field Descriptions', 'setting label (admin-text)', 'site-reviews-forms'),
        'options' => [
            'under_label' => _x('Display under the label', 'setting option (admin-text)', 'site-reviews-forms'),
            'under_field' => _x('Display under the field', 'setting option (admin-text)', 'site-reviews-forms'),
        ],
        'sanitizer' => 'text',
        'tooltip' => _x('Where do you want to display the field descriptions?', 'setting tooltip (admin-text)', 'site-reviews-forms'),
        'type' => 'select',
    ],
    'settings.addons.forms.dropdown_library' => [
        'class' => 'regular-text',
        'default' => '',
        'label' => _x('Select Boxes', 'setting label (admin-text)', 'site-reviews-forms'),
        'options' => [
            '' => _x('Use the native Select Boxes', 'setting option (admin-text)', 'site-reviews-forms'),
            'choices.js' => _x('Use Choices.js (experimental)', 'setting option (admin-text)', 'site-reviews-forms'),
        ],
        'sanitizer' => 'text',
        'tooltip' => _x('Would you like to use a javascript library to style the Select boxes?', 'setting tooltip (admin-text)', 'site-reviews-forms'),
        'type' => 'select',
    ],
    'settings.addons.forms.dropdown_assets' => [
        'default' => 'yes',
        'depends_on' => [
            'settings.addons.forms.dropdown_library' => ['choices.js'],
        ],
        'label' => _x('Load Library Assets?', 'setting label (admin-text)', 'site-reviews-forms'),
        'sanitizer' => 'text',
        'tooltip' => _x('Would you like to load the javascript and CSS of the selected library? If your theme is already using the library, you may want to disable this.', 'setting tooltip (admin-text)', 'site-reviews-forms'),
        'type' => 'yes_no',
    ],
    'settings.addons.forms.responsive_width.xl' => [
        'after' => _x('pixels', 'screen width (admin-text)', 'site-reviews-forms'),
        'default' => 1280,
        'label' => _x('Screen Width: Large', 'admin-text', 'site-reviews-forms'),
        'min' => 320,
        'sanitizer' => 'min:320',
        'tooltip' => _x('Set the minimum width of the large screen size used in the Field Width option of custom fields.', 'admin-text', 'site-reviews-forms'),
        'type' => 'number',
    ],
    'settings.addons.forms.responsive_width.lg' => [
        'after' => _x('pixels', 'screen width (admin-text)', 'site-reviews-forms'),
        'default' => 1024,
        'label' => _x('Screen Width: Medium', 'admin-text', 'site-reviews-forms'),
        'min' => 320,
        'sanitizer' => 'min:320',
        'tooltip' => _x('Set the minimum width of the medium screen size used in the Field Width option of custom fields.', 'admin-text', 'site-reviews-forms'),
        'type' => 'number',
    ],
    'settings.addons.forms.responsive_width.md' => [
        'after' => _x('pixels', 'screen width (admin-text)', 'site-reviews-forms'),
        'default' => 768,
        'label' => _x('Screen Width: Small', 'admin-text', 'site-reviews-forms'),
        'min' => 320,
        'sanitizer' => 'min:320',
        'tooltip' => _x('Set the minimum width of the small size used in the Field Width option of custom fields.', 'admin-text', 'site-reviews-forms'),
        'type' => 'number',
    ],
];
