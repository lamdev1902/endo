<?php defined('WPINC') || die; ?>

<script type="text/html" id="tmpl-glsrn-criteria">
    <div class="glsr-criteria-option">
        <select id="{{ data.key }}-{{ data.cid }}">
            <# _.each(data.conditions, function (name, key) { #>
                <option value="{{ key }}" <# if (key === data.selected) { print('selected') } #>>{{ name }}</option>
            <# }); #>
        </select>
        <button type="button" class="button dashicons-before dashicons-plus-alt2 glsr-add-condition" aria-label="<?= _x('Add a new condition', 'admin-text', 'site-reviews-notifications'); ?>"></button>
    </div>
    <div class="glsr-criteria-conditions"></div>
</script>

<script type="text/html" id="tmpl-glsrn-criteria-condition">
    <select data-condition="field">
        <# _.each(data.fields, function (name, key) { #>
            <option value="{{ key }}" <# if (key === data.field) { print('selected') } #>>{{ name }}</option>
        <# }); #>
    </select>
    <# if (!_.isEmpty(data.operators)) { #>
        <select data-condition="operator">
            <# _.each(data.operators, function (name, key) { #>
                <option value="{{ key }}" <# if (key === data.operator) { print('selected') } #>>{{ name }}</option>
            <# }); #>
        </select>
    <# } #>
    <# if (!_.isEmpty(data.values)) { #>
        <select data-condition="value">
            <# _.each(data.values, function (name, key) { #>
                <option value="{{ key }}" <# if (key === data.value) { print('selected') } #>>{{ name }}</option>
            <# }); #>
        </select>
    <# } #>
    <# if (!_.isEmpty(data.operators) && _.isEmpty(data.values)) { #>
        <input data-condition="value" type="text" class="glsr-input-value" value="{{ data.value }}">
    <# } #>
    <button type="button" class="button button-link-delete dashicons-before dashicons-minus glsr-remove-condition" aria-label="<?= _x('Remove this condition', 'admin-text', 'site-reviews-notifications'); ?>"></button>
</script>

<script type="text/html" id="tmpl-glsrn-field-actions">
    <div class="glsr-metabox-field">
        <div class="glsr-label"></div>
        <div class="glsr-input wp-clearfix">
            <div style="display:flex;justify-content:space-between">
                <div>
                    <button type="button" class="components-button is-secondary save"><?= _x('Save', 'admin-text', 'site-reviews-notifications'); ?></button>
                    <button type="button" class="components-button is-destructive delete"><?= _x('Delete', 'admin-text', 'site-reviews-notifications'); ?></button>
                </div>
                <div>
                    <button type="button" class="glsr-button components-button is-secondary test"
                        data-loading="<?= esc_attr_x('Sending, please wait...', 'admin-text', 'site-reviews-notifications'); ?>"
                    ><?= _x('Send Test Email', 'admin-text', 'site-reviews-notifications'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrn-field-conditions">
    <div class="glsr-metabox-field" data-option="conditions">
        <div class="glsr-label">
            <label for="conditions-{{ data.cid }}">
                <?= _x('Send Conditions', 'admin-text', 'site-reviews-notifications'); ?>
            </label>
        </div>
        <div class="glsr-input">
            <div data-field="conditions" class="glsr-criteria"></div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrn-field-enabled">
    <div class="glsr-metabox-field" data-option="enabled">
        <div class="glsr-label">
            <label for="enabled-{{ data.cid }}"><?= _x('Enabled', 'admin-text', 'site-reviews-notifications'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <div class="glsr-toggle-field">
                <span class="glsr-toggle">
                    <input data-field="enabled" id="enabled-{{ data.cid }}" type="checkbox" class="glsr-toggle__input" <# if (data.enabled) print('checked') #> value="1">
                    <span class="glsr-toggle__track"></span>
                    <span class="glsr-toggle__thumb"></span>
                </span>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrn-field-error">
    <span class="glsrn-field-error">{{ data.error }}</span>
</script>

<script type="text/html" id="tmpl-glsrn-field-heading">
    <div class="glsr-metabox-field" data-option="heading">
        <div class="glsr-label">
            <label for="heading-{{ data.cid }}">
                <i class="dashicons-before dashicons-editor-help" data-tippy-allowhtml="1" data-tippy-content="<?= sprintf(_x('The available placeholder tags are: %s', 'admin-text', 'site-reviews-notifications'), $tags); ?>" data-tippy-delay="[200,null]" data-tippy-interactive="1"  data-tippy-placement="top-start"></i>
                <?= _x('Email Heading', 'admin-text', 'site-reviews-notifications'); ?>
            </label>
        </div>
        <div class="glsr-input wp-clearfix">
            <input data-field="heading" type="text" id="heading-{{ data.cid }}" class="glsr-input-value" value="{{{ data.heading }}}">
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrn-field-message">
    <div class="glsr-metabox-field" data-option="message">
        <div class="glsr-label">
            <label for="message-{{ data.cid }}">
                <i class="dashicons-before dashicons-editor-help" data-tippy-allowhtml="1" data-tippy-content="<?= sprintf(_x('The available placeholder tags are: %s', 'admin-text', 'site-reviews-notifications'), $tags); ?>" data-tippy-delay="[200,null]" data-tippy-interactive="1"  data-tippy-placement="top-start"></i>
                <?= _x('Email Message', 'admin-text', 'site-reviews-notifications'); ?>
            </label>
        </div>
        <div class="glsr-input wp-clearfix">
            <textarea data-field="message" id="message-{{ data.cid }}" rows="6" class="wp-editor-area glsr-input-value">{{{ data.message }}}</textarea>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrn-field-recipients">
    <div class="glsr-metabox-field" data-option="recipients">
        <div class="glsr-label">
            <label for="recipients-{{ data.cid }}"><?= _x('Recipients', 'admin-text', 'site-reviews-notifications'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <div data-field="recipients" class="glsr-search-multibox"></div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrn-field-schedule">
    <div class="glsr-metabox-field" data-option="schedule">
        <div class="glsr-label">
            <label for="schedule-{{ data.cid }}"><?= _x('Send Schedule', 'admin-text', 'site-reviews-notifications'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <select data-field="schedule" id="schedule-{{ data.cid }}">
                <# _.each(data.addon.schedule, function (name, num) { #>
                    <option value="{{ num }}" <# if (+num === +data.schedule) { print('selected')} #>>{{ name }}</option>
                <# }) #>
            </select>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrn-field-subject">
    <div class="glsr-metabox-field" data-option="subject">
        <div class="glsr-label">
            <label for="subject-{{ data.cid }}">
                <i class="dashicons-before dashicons-editor-help" data-tippy-allowhtml="1" data-tippy-content="<?= sprintf(_x('The available placeholder tags are: %s', 'admin-text', 'site-reviews-notifications'), $tags); ?>" data-tippy-delay="[200,null]" data-tippy-interactive="1"  data-tippy-placement="top-start"></i>
                <?= _x('Email Subject', 'admin-text', 'site-reviews-notifications'); ?>
            </label>
        </div>
        <div class="glsr-input wp-clearfix">
            <input data-field="subject" type="text" id="subject-{{ data.cid }}" class="glsr-input-value" value="{{{ data.subject }}}">
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrn-field-uid">
    <div class="glsr-metabox-field" data-option="uid">
        <div class="glsr-label">
            <label for="uid-{{ data.cid }}">
                <i class="dashicons-before dashicons-editor-help" data-tippy-allowhtml="1" data-tippy-content="<?= _x('Each notification has a Unique ID (UID), this UID can be used in filter hooks to target specific notifications.', 'admin-text', 'site-reviews-notifications'); ?>" data-tippy-delay="[200,null]" data-tippy-interactive="1"  data-tippy-placement="top-start"></i>
                <?= _x('Notification ID', 'admin-text', 'site-reviews-notifications'); ?>
            </label>
        </div>
        <div class="glsr-input wp-clearfix">
            <input type="text" id="uid-{{ data.cid }}" class="glsr-input-value" value="{{ data.uid }}" readonly/>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrn-multibox">
    <div class="glsr-search-multibox-entries">
        <div class="glsr-selected-entries"></div>
        <input id="{{ data.key }}-{{ data.cid }}" class="glsr-search-input" type="search" autocomplete="off" placeholder="<?= esc_attr_x('Select a recipient or type an email and press ENTER.', 'admin-text', 'site-reviews-notifications'); ?>">
    </div>
    <div class="glsr-search-results">
        <# _.each(data.options, function (option) { #>
            <span class="glsr-search-result" tabindex="0" data-slug="{{ option.slug }}">{{{ option.name }}}</span>
        <# }) #>
    </div>
</script>

<script type="text/html" id="tmpl-glsrn-multibox-entry">
    <span class="glsr-multibox-entry">
        <button type="button" data-slug="{{ data.slug }}" class="glsr-remove-button glsr-remove-icon">
            <span class="screen-reader-text"><?= _x('Remove entry', 'admin-text', 'site-reviews-notifications'); ?></span>
        </button>
        <span data-slug="{{ data.slug }}">{{{ data.name }}}</span>
    </span>
</script>

<script type="text/html" id="tmpl-glsrn-notification">
    <div class="glsrn-notification {{ data.enabled ? 'is-active' : '' }}">
        <div class="gl-header">
            <div class="gl-col gl-col-primary">
                <i class="dashicons-before dashicons-yes-alt"></i>
                <span>
                    <a class="toggle-field" href="#" tabindex="0" title="<?= _x('Edit notification', 'admin-text', 'site-reviews-notifications'); ?>">
                        {{ data.subject || '<?= _x('No Subject', 'admin-text', 'site-reviews-notifications'); ?>' }}
                    </a>
                </span>
            </div>
            <div class="gl-col gl-col-schedule">
                <span>{{ data.selected_schedule }}</span>
            </div>
            <div class="gl-col gl-col-recipient">
                <span>{{ data.selected_recipients }}</span>
            </div>
        </div>
        <div class="gl-settings"></div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrn-notification-empty">
    <div class="glsrn-notification glsrn-placeholder">
        <div class="gl-header-instruct">
            <div class="gl-col gl-col-primary">
                <span><?= _x('Click the <strong>Add Notification</strong> button to add a new notification.', 'admin-text', 'site-reviews-notifications'); ?></span>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrn-preview">
    <style>
        <?= $style; ?>
    </style>
    <div class="preview-email">
        <?= $preview; ?>
    </div>
</script>
