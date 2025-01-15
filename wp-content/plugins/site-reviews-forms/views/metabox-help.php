<?php defined('WPINC') || die; ?>

<div class="glsr-notice-inline components-notice is-info">
    <p class="components-notice__content">
        <?= _x('If you are using the Blocks or Elementor Widgets then select the Review Form in the block/widget options.', 'admin-text', 'site-reviews-forms'); ?>
    </p>
</div>

<p><?= _x('Display this Review Form:', 'admin-text', 'site-reviews-forms'); ?></p>
<div style="display:inline-flex;" 
    data-tippy-content="<?= _x('Use the <code>form</code> option with the [site_reviews_form] shortcode to use this custom form.', 'admin-text', 'site-reviews-forms'); ?>" 
    data-tippy-allowhtml="1" 
    data-tippy-delay="[150,null]" 
    data-tippy-placement="top-start"
>
    <?= $site_reviews_form; ?>
</div>

<p><?= _x('Display reviews with this Review Template:', 'admin-text', 'site-reviews-forms'); ?></p>
<div style="display:inline-flex;" 
    data-tippy-content="<?= _x('Use the <code>form</code> option with the [site_reviews] shortcode to display your reviews using the Review Template of this custom form.', 'admin-text', 'site-reviews-forms'); ?>" 
    data-tippy-allowhtml="1" 
    data-tippy-delay="[150,null]" 
    data-tippy-placement="top-start"
>
    <?= $site_reviews; ?>
</div>
