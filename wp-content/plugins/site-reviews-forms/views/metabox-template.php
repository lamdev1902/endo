<?php defined('WPINC') || die; ?>

<textarea name="review_template" rows="10" class="glsrf-template"><?= esc_textarea($template); ?></textarea>

<div class="glsr-metabox-footer">
    <p>
        <?= _x('Enter the Template Tags of your fields here.', 'admin-text', 'site-reviews-forms'); ?>
        <?= _x('Saving an empty template will restore the default one used by Site Reviews.', 'admin-text', 'site-reviews-forms'); ?>
    </p>
</div>
