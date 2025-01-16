<?php defined('WPINC') || exit; ?>

<div class="wrap">
    <h1>
        <?php printf(esc_html_x('Import %s', 'Review Forms (admin-text)', 'site-reviews-forms'), $app->name); ?>
    </h1>
    <hr class="wp-header-end" />
    <div id="glsr-notices">
        <?php echo $notices; ?>
    </div>
    <p>
        <?php echo esc_html_x('Upload an export (.json) file to import review forms into this site.', 'admin-text', 'site-reviews-forms'); ?>
    </p>
    <form method="post" enctype="multipart/form-data" class="wp-upload-form" onsubmit="submit.classList.add('is-busy'); submit.disabled = true;">
        <?php wp_nonce_field('import-'.$app->post_type); ?>
        <input type="hidden" name="<?php echo glsr()->id; ?>[_action]" value="import-<?php echo $app->post_type; ?>">
        <input type="hidden" name="<?php echo glsr()->id; ?>[max_file_size]" value="<?php echo esc_attr($max_size_bytes); ?>">
        <h2>
            <?php echo esc_html_x('Duplicate Forms', 'admin-text', 'site-reviews-forms'); ?>
        </h2>
        <p class="description">
            <?php echo esc_html_x('What should happen if an existing form is found with an identical slug to an imported form?', 'admin-text', 'site-reviews-forms'); ?>
        </p>
        <fieldset>
            <p>
                <label>
                    <input type="radio" name="<?php echo glsr()->id; ?>[duplicate_action]" value="ignore" checked="checked">
                    <?php echo esc_html_x('Ignore duplicate form: import as a new form and leave the existing form unchanged.', 'admin-text', 'site-reviews-forms'); ?>
                </label>
            </p>
            <p>
                <label>
                    <input type="radio" name="<?php echo glsr()->id; ?>[duplicate_action]" value="replace">
                    <?php echo esc_html_x('Replace existing form: overwrite the existing form with the imported form.', 'admin-text', 'site-reviews-forms'); ?>
                </label>
            </p>
            <p>
                <label>
                    <input type="radio" name="<?php echo glsr()->id; ?>[duplicate_action]" value="skip">
                    <?php echo esc_html_x('Do not import any duplicate forms.', 'admin-text', 'site-reviews-forms'); ?>
                </label>
            </p>
        </fieldset>
        <h2>
            <?php echo esc_html_x('Upload File', 'admin-text', 'site-reviews-forms'); ?>
        </h2>
        <p class="description">
            <?php printf(esc_html_x('Choose an export (.json) file to upload (maximum size: %s).', '%s: size in bytes (admin-text)', 'site-reviews-forms'), esc_html(size_format($max_size_bytes))); ?>
        </p>
        <fieldset>
            <p>
                <input type="file" id="import-files" name="import-files" size="25" accept="application/json">
            </p>
        </fieldset>
        <?php submit_button(_x('Upload file and import', 'admin-text', 'site-reviews-forms')); ?>
    </form>
</div>
