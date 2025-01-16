<?php defined('WPINC') || die; ?>

<div id="glsrf-metabox-fields">
    <div class="edit-post-sidebar">
        <div class="components-panel__header edit-post-sidebar__panel-tabs" tabindex="-1">
            <div class="glsr-panel-tabs" role="tablist" aria-orientation="horizontal">
                <button type="button" aria-label="<?= _x('Form Fields', 'admin-text', 'site-reviews-forms'); ?>" class="components-button edit-post-sidebar__panel-tab" data-group="fields" aria-selected="true" tabindex="-1">
                    <?= _x('Form Fields', 'admin-text', 'site-reviews-forms'); ?>
                </button>
            </div>
        </div>
    </div>
    <input id="glsrf-data" name="fields" type="hidden" value='<?= $fields; ?>'>
    <div class="glsrf-inner">
        <div class="glsrf-no-fields" style="display: none;">
            <div>
                <?= _x('Click the <strong>Add Field</strong> button to add a field to the form.', 'admin-text', 'site-reviews-forms'); ?>
            </div>
        </div>
        <div class="glsrf-fields"></div>
    </div>
    <div class="glsrf-actions">
        <div>
            <button type="button" class="components-button has-icon is-link collapse-all" data-tippy-content="<?= _x('Collapse All Fields', 'admin-text', 'site-reviews-forms'); ?>">
                <span class="dashicons-before dashicons-list-view"></span>
                <span class="screen-reader-text"><?= _x('Collapse All Fields', 'admin-text', 'site-reviews-forms'); ?></span>
            </button>
            <button type="button" class="components-button has-icon is-link expand-all" data-tippy-content="<?= _x('Expand All Fields', 'admin-text', 'site-reviews-forms'); ?>">
                <span class="dashicons-before dashicons-excerpt-view"></span>
                <span class="screen-reader-text"><?= _x('Expand All Fields', 'admin-text', 'site-reviews-forms'); ?></span>
            </button>
        </div>
        <button type="button" class="button button-primary button-large add-field"><?= _x('Add Field', 'admin-text', 'site-reviews-forms'); ?></button>
    </div>
</div>
