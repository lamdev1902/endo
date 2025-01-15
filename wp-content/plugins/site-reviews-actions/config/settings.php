<?php

return [
    'settings.addons.actions.buttons' => [
        'class' => 'regular-text',
        'default' => ['upvote', 'report'],
        'label' => _x('Enabled Actions', 'setting label (admin-text)', 'site-reviews-actions'),
        'options' => [ // order is intentional
            'upvote' => _x('Upvote review', 'setting option (admin-text)', 'site-reviews-actions'),
            // 'share' => _x('Share review', 'setting option (admin-text)', 'site-reviews-actions'),
            'translate' => _x('Translate review', 'setting option (admin-text)', 'site-reviews-actions'),
            'report' => _x('Report review', 'setting option (admin-text)', 'site-reviews-actions'),
        ],
        'sanitizer' => 'array-string',
        'tooltip' => _x('Select the action buttons that you want enabled.', 'setting description (admin-text)', 'site-reviews-actions'),
        'type' => 'checkbox',
    ],
    'settings.addons.actions.deepl_api_key' => [
        'default' => '',
        'description' => sprintf(_x('Enter your %s key to enable translation.', 'DeepL API (admin-text)', 'site-reviews-actions'),
            sprintf('<a href="https://www.deepl.com/pro/change-plan#developer" target="_blank">%s</a>', _x('DeepL API', 'admin-text', 'site-reviews-actions'))
        ),
        'depends_on' => [
            'settings.addons.actions.buttons' => ['translate'],
        ],
        'label' => _x('DeepL API Key', 'admin-text', 'site-reviews-actions'),
        'sanitizer' => 'text',
        'tooltip' => sprintf(_x('%s to get a free DeepL API Key. You will be taken to the DeepL website where you can sign up for a DeepL API developer account. On the website, select "DeepL API Free" and then follow the steps until you get your API key.', 'Click here (admin-text)', 'site-reviews-actions'),
            sprintf('<a href="https://www.deepl.com/pro/change-plan#developer" target="_blank">%s</a>', _x('Click here', 'admin-text', 'site-reviews-actions'))
        ),
        'type' => 'secret',
    ],
    'settings.addons.actions.detect_language_api_key' => [
        'default' => '',
        'description' => sprintf(_x('Enter your %s key to accurately detect the language of reviews.', 'Detect Language API (admin-text)', 'site-reviews-actions'),
            sprintf('<a href="https://detectlanguage.com/plans" target="_blank">%s</a>', _x('Detect Language API', 'admin-text', 'site-reviews-actions'))
        ),
        'depends_on' => [
            'settings.addons.actions.buttons' => ['translate'],
        ],
        'label' => _x('Detect Language API Key', 'admin-text', 'site-reviews-actions'),
        'sanitizer' => 'text',
        'tooltip' => sprintf(_x('%s to get a free Detect Language API Key. This is not required for translations to work, but it will be more accurate than the built-in language detection.', 'Click here (admin-text)', 'site-reviews-actions'),
            sprintf('<a href="https://detectlanguage.com/plans" target="_blank">%s</a>', _x('Click here', 'admin-text', 'site-reviews-actions'))
        ),
        'type' => 'secret',
    ],
    'settings.addons.actions.hide_restricted' => [
        'default' => 'no',
        'depends_on' => [
            'settings.addons.actions.buttons' => ['report', 'upvote'],
        ],
        'label' => _x('Hide Restricted Actions', 'admin-text', 'site-reviews-actions'),
        'sanitizer' => 'text',
        'tooltip' => _x('This will hide any actions that are restricted from guest users.', 'admin-text', 'site-reviews-actions'),
        'type' => 'yes_no',
    ],
    'settings.addons.actions.report_restricted' => [
        'default' => '',
        'depends_on' => [
            'settings.addons.actions.buttons' => ['report'],
        ],
        'label' => _x('Restrict Reporting', 'admin-text', 'site-reviews-actions'),
        'options' => [
            '' => _x('No Restrictions', 'admin-text', 'site-reviews-actions'),
            'user' => _x('Logged In Users only', 'admin-text', 'site-reviews-actions'),
        ],
        'sanitizer' => 'text',
        'tooltip' => _x('Select a restriction that you want to apply for reporting a review.', 'admin-text', 'site-reviews-actions'),
        'type' => 'select',
    ],
    'settings.addons.actions.upvote_restricted' => [
        'default' => '',
        'depends_on' => [
            'settings.addons.actions.buttons' => ['upvote'],
        ],
        'label' => _x('Restrict Upvoting', 'admin-text', 'site-reviews-actions'),
        'options' => [
            '' => _x('No Restrictions', 'admin-text', 'site-reviews-actions'),
            'user' => _x('Logged In Users only', 'admin-text', 'site-reviews-actions'),
        ],
        'sanitizer' => 'text',
        'tooltip' => _x('Select a restriction that you want to apply for upvoting a review.', 'admin-text', 'site-reviews-actions'),
        'type' => 'select',
    ],
    'settings.addons.actions.report_confirmation' => [
        'default' => glsr('site-reviews-actions')->build('templates/report-confirmation'),
        'depends_on' => [
            'settings.addons.actions.buttons' => ['report'],
        ],
        'label' => _x('Report Confirmation', 'admin-text', 'site-reviews-actions'),
        'rows' => 6,
        'sanitizer' => 'text-html',
        'tags' => glsr('Modules\Html\TemplateTags')->filteredTags([
            'include' => ['site_title', 'site_url'],
        ]),
        'tooltip' => _x('The confirmation email sent when a review is reported. To restore the default text, save an empty template.', 'admin-text', 'site-reviews-actions'),
        'type' => 'code',
    ],
    'settings.addons.actions.report_notification' => [
        'default' => glsr('site-reviews-actions')->build('templates/report-notification'),
        'depends_on' => [
            'settings.addons.actions.buttons' => ['report'],
        ],
        'label' => _x('Report Notification', 'admin-text', 'site-reviews-actions'),
        'rows' => 6,
        'sanitizer' => 'text-html',
        'tags' => glsr('Modules\Html\TemplateTags')->filteredTags([
            'include' => ['edit_url', 'review_content', 'review_id', 'review_rating', 'review_stars', 'review_title', 'site_title', 'site_url'],
            'insert' => [
                'report_email' => 'report_email',
                'report_message' => 'report_message',
                'report_reason' => 'report_reason',
            ],
        ]),
        'tooltip' => _x('The notification email sent to the admin when a review is reported. To restore the default text, save an empty template.', 'admin-text', 'site-reviews-actions'),
        'type' => 'code',
    ],
];
