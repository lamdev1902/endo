<?php

$categories = get_terms([
    'fields' => 'id=>name',
    'hide_empty' => 1,
    'order' => 'ASC',
    'orderby' => 'name',
    'taxonomy' => 'product_cat',
]);

return [
    'reminder_trigger' => [
        'class' => 'wc-enhanced-select',
        'default' => 'completed',
        'desc_tip' => true,
        'description' => _x('The condition to use in order to schedule the review reminder', 'admin-text', 'site-reviews-notifications'),
        'options' => [
            'completed' => _x('Order is Completed', 'admin-text', 'site-reviews-notifications'),
            'processing' => _x('Order is Processing', 'admin-text', 'site-reviews-notifications'),
        ],
        'title' => _x('Trigger when', 'admin-text', 'site-reviews-notifications'),
        'type' => 'select',
    ],
    'reminder_delay' => [
        'after' => _x('day(s) after trigger', 'admin-text', 'site-reviews-notifications'),
        'custom_attributes' => [
            'min' => 1,
        ],
        'default' => 7,
        'desc_tip' => true,
        'description' => _x('The period of days in the future to schedule the review reminder', 'admin-text', 'site-reviews-notifications'),
        'title' => _x('Send the email', 'admin-text', 'site-reviews-notifications'),
        'type' => 'days_after',
    ],
    'reminder_categories' => [
        'class' => 'wc-enhanced-select',
        'custom_attributes' => [
            'data-placeholder' => _x('Select product categories', 'admin-text', 'site-reviews-notifications'),
        ],
        'default' => '',
        'desc_tip' => true,
        'description' => _x('Restrict review reminders to products in these categories.', 'admin-text', 'site-reviews-notifications'),
        'options' => $categories,
        'select_buttons' => true,
        'title' => _x('Restrict emails to', 'admin-text', 'site-reviews-notifications'),
        'type' => 'multiselect',
    ],
    'reminder_guests' => [
        'default' => '',
        'desc_tip' => true,
        'description' => _x('Send a reminder to customers who place orders using guest checkout.', 'admin-text', 'site-reviews-notifications'),
        'label' => _x('Send reminders to customers who used the guest checkout?', 'admin-text', 'site-reviews-notifications'),
        'title' => _x('Send to guests', 'admin-text', 'site-reviews-notifications'),
        'type' => 'checkbox',
    ],
    'reminder_recheck' => [
        'default' => '',
        'desc_tip' => true,
        'description' => _x("This is useful for reminders as it ensures the status of the order hasn't changed since the initial trigger.", 'admin-text', 'site-reviews-notifications'),
        'label' => _x('Recheck order status before the email is sent?', 'admin-text', 'site-reviews-notifications'),
        'title' => _x('Recheck order status', 'admin-text', 'site-reviews-notifications'),
        'type' => 'checkbox',
    ],
];
