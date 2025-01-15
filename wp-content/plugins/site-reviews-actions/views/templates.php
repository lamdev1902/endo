<?php defined('WPINC') || die; 

use GeminiLabs\SiteReviews\Addon\Actions\Application;
?>

<script type="text/html" id="tmpl-glsrf-field-translatable">
    <div class="glsr-metabox-field" data-option="translatable">
        <div class="glsr-label">
            <label><?= _x('Translatable', 'admin-text', 'site-reviews-actions'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <div class="glsr-toggle-field">
                <span class="glsr-toggle" data-tippy-allowhtml="1" data-tippy-interactive="1" data-tippy-content="<?= esc_html(sprintf(_x('This allows the %s addon to translate the custom field.', 'Link to Review Actions addon (admin-text)', 'site-reviews-actions'), '<a href="'.glsr(Application::class)->uri.'" target="_blank">Review Actions</a>')); ?>">
                    <input data-field="translatable" type="checkbox" class="glsr-toggle__input" <# if (data.translatable) print('checked') #> value="1">
                    <span class="glsr-toggle__track"></span>
                    <span class="glsr-toggle__thumb"></span>
                </span>
            </div>
        </div>
    </div>
</script>
