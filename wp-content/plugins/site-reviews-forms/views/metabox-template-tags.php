<?php defined('WPINC') || die; ?>

<div class="glsr-notice-inline components-notice is-warning">
    <p class="components-notice__content">
        <?= _x('These template tags are reserved by Site Reviews. You can add them to the Review Template, but they cannot be used in your custom fields.', 'admin-text', 'site-reviews-forms'); ?>
    </p>
</div>

<div class="glsrf-reserved-tags">
    <?php foreach ($tags as $tag): ?>
        <code data-select-text class="glsrf-template-tag template-tag">{{ <?= $tag; ?> }}</code>
    <?php endforeach; ?>
</div>
