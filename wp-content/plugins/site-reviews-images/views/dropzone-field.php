<?php defined('WPINC') || exit; ?>

<span class="glsr-fallback">
    <input type="file" multiple name="files" accept="<?php echo esc_attr($types); ?>">
</span>
<span class="glsr-dz-message dz-message">
    <button type="button" class="glsr-dz-button dz-button">
        <?php echo __('Drag & Drop your photos or <span>Browse</span>', 'site-reviews-images'); ?>
    </button>
</span>
