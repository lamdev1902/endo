<?php defined('WPINC') || die; ?>

<div id="glsrt-metabox-theme">
    <div class="edit-post-sidebar">
        <div class="loading-content">
            <div class="spinner"></div>
            <?= _x('Loading theme...', 'admin-text', 'site-reviews-themes'); ?>
        </div>
    </div>
    <div id="glsrt-builder" class="glsrt-themebox" style="display:none;">
        <input type="hidden" name="theme_builder" data-builder>
        <div id="glsrt-toolbar"></div>
        <div id="glsrt-fields" class="glsr" data-theme>
            <div class="glsr-review"></div>
        </div>
        <div class="glsr-metabox-footer">
            <p>
                <?php echo _X('Click the review to activate the builder.', 'admin-text', 'site-reviews-themes'); ?>
                <?php echo _X('Drag and drop or click a template tag to add it to the review.', 'admin-text', 'site-reviews-themes'); ?>
            </p>
        </div>
    </div>
    <div id="glsrt-preview" class="glsrt-themebox" style="display:none;"></div>
</div>
