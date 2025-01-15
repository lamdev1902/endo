<?php defined('WPINC') || die; ?>

<div class="glsr-notice-inline components-notice is-info">
    <p class="components-notice__content">
        <?= _x('If you are using the Blocks or Elementor Widgets then select the Review Theme (and Review Form if used) in the block/widget options.', 'admin-text', 'site-reviews-themes'); ?>
    </p>
</div>

<p><?= _x('Display reviews with this Theme:', 'admin-text', 'site-reviews-themes'); ?></p>
<div style="display:inline-flex;"
    data-tippy-content="Use the <code>theme</code> option to display your reviews using this theme."
    data-tippy-allowhtml="1"
    data-tippy-delay="[150,null]"
    data-tippy-placement="top-start"
    data-tippy-followCursor="horizontal"
    data-tippy-offset="[-50, 10]"
>
    <?= $site_reviews; ?>
</div>

<p><?= _x('Display the rating summary using the rating image and colors of this Theme:', 'admin-text', 'site-reviews-themes'); ?></p>
<div style="display:inline-flex;"
    data-tippy-content="Use the <code>theme</code> option with the summary shortcode to use the theme's Rating Image and Rating Colours." 
    data-tippy-allowhtml="1" 
    data-tippy-delay="[150,null]" 
    data-tippy-placement="top-start" 
    data-tippy-followCursor="horizontal" 
    data-tippy-offset="[-50, 10]"
>
    <?= $site_reviews_summary; ?>
</div>

<p><?= _x('Display the review form using the rating image and colors of this Theme:', 'admin-text', 'site-reviews-themes'); ?></p>
<div style="display:inline-flex;"
    data-tippy-content="Use the <code>theme</code> option with the form shortcode to use the theme's Rating Image and Rating Colours." 
    data-tippy-allowhtml="1" 
    data-tippy-delay="[150,null]" 
    data-tippy-placement="top-start" 
    data-tippy-followCursor="horizontal" 
    data-tippy-offset="[-50, 10]"
>
    <?= $site_reviews_form; ?>
</div>
