<?php defined('WPINC') || exit; ?>

<div class="wp-block-button">
    <button type="button" 
        aria-label="<?php echo esc_attr__('Previous Page', 'site-reviews-images'); ?>"
        class="<?php echo esc_attr($button_class); ?>"
        data-page="<?php echo $page > 1 ? $page - 1 : 1; ?>"
        data-prev
        <?php echo 1 === $page ? 'disabled' : ''; ?>
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
            <path fill="currentColor" d="M7.828 11H20v2H7.828l5.364 5.364-1.414 1.414L4 12l7.778-7.778 1.414 1.414z"/>
        </svg>
    </button>
</div>
<div class="glsr-modal-pagination">
    <label class="screen-reader-text" for="glsri-pagination"><?php echo __('Go to page', 'site-reviews-images'); ?></label>
    <input id="glsri-pagination" type="number" min="1" max="<?php echo esc_attr($max_pages); ?>" value="<?php echo $page; ?>" aria-describedby="glsri-pagination-inputhint"<?php echo 1 === $max_pages ? ' disabled' : ''; ?>>
    <span id="glsri-pagination-inputhint" class="screen-reader-text" aria-hidden="true"><?php echo __('Press Return/Enter key to go to the page', 'site-reviews-images'); ?></span>
    <span class="screen-reader-text"><?php echo sprintf(__('Page %d', 'site-reviews-images'), $page); ?></span>
    <span><?php echo __('of', 'site-reviews-images'); ?></span>
    <span><?php echo $max_pages; ?></span>
</div>
<div class="wp-block-button">
    <button type="button"
        aria-label="<?php echo esc_attr__('Next Page', 'site-reviews-images'); ?>"
        class="<?php echo esc_attr($button_class); ?>"
        data-next
        data-page="<?php echo $page < $max_pages ? $page + 1 : $page; ?>"
        <?php echo $page === $max_pages ? 'disabled' : ''; ?>
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
            <path fill="currentColor" d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"/>
        </svg>
    </button>
</div>
