<?php defined('WPINC') || die; ?>

<div id="glsrt-metabox-tags">
    <div id="glsrt-tags" style="display:none;"></div>
    <div id="glsrt-tags-hidden" style="display:none;">
        <button type="button" class="button button-primary button-large">Open the builder</button>
    </div>
    <div class="loading-content">
        <div class="spinner"></div>
        <?= _x('Loading tags...', 'admin-text', 'site-reviews-themes'); ?>
    </div>
    <div id="glsrt-form" style="display:none;">
        <select id="form_ID" name="form">
                data-tippy-allowHTML="true"
                data-tippy-content='<?= $tooltip; ?>'
                data-tippy-interactive="1">
            <?php foreach ($forms as $id => $label): ?>
                <option value="<?= $id; ?>" <?php selected($id, $form_id); ?>>
                    <?= $label; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
