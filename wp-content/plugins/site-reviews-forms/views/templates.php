<?php defined('WPINC') || die; ?>

<script type="text/html" id="tmpl-glsrf-criteria">
    <div class="glsr-criteria-option">
        <select id="{{ data.key }}-{{ data.cid }}">
            <# _.each(data.conditions, function (name, key) { #>
                <option value="{{ key }}" <# if (key === data.selected) { print('selected') } #>>{{{ name }}}</option>
            <# }); #>
        </select>
        <button type="button" class="button dashicons-before dashicons-plus-alt2 glsr-add-condition" aria-label="<?= _x('Add a new condition', 'admin-text', 'site-reviews-forms'); ?>"></button>
    </div>
    <div class="glsr-criteria-conditions"></div>
</script>

<script type="text/html" id="tmpl-glsrf-criteria-condition">
    <div data-tippy-content="<?= _x('Select a field name.', 'admin-text', 'site-reviews-forms'); ?>">
        <select data-condition="fieldname">
            <# _.each(data.fields, function (name, key) { #>
                <option value="{{ key }}" <# if (key === data.fieldname) { print('selected') } #>>{{{ key }}}</option>
            <# }); #>
        </select>
    </div>
    <# if (!_.isEmpty(data.operators)) { #>
    <div data-tippy-content="<?= _x('Select a field condition.', 'admin-text', 'site-reviews-forms'); ?>">
        <select data-condition="operator">
            <# _.each(data.operators, function (name, key) { #>
                <option value="{{ key }}" <# if (key === data.operator) { print('selected') } #>>{{{ name }}}</option>
            <# }); #>
        </select>
    </div>
    <# } #>
    <# if (!_.isEmpty(data.values)) { #>
    <div data-tippy-content="<?= _x('Select a conditional value.', 'admin-text', 'site-reviews-forms'); ?>">
        <select data-condition="value">
            <# _.each(data.values, function (name, key) { #>
                <option value="{{ key }}" <# if (key === data.value) { print('selected') } #>>{{{ name }}}</option>
            <# }); #>
        </select>
    </div>
    <# } #>
    <# if (!_.isEmpty(data.operators) && _.isEmpty(data.values)) { #>
    <div data-tippy-content="<?= _x('Enter a conditional value.', 'admin-text', 'site-reviews-forms'); ?>">
        <input data-condition="value" type="text" class="glsr-input-value" value="{{ data.value }}">
    </div>
    <# } #>
    <button type="button" class="button button-link-delete dashicons-before dashicons-minus glsr-remove-condition" aria-label="<?= _x('Remove this condition', 'admin-text', 'site-reviews-forms'); ?>"></button>
</script>

<script type="text/html" id="tmpl-glsrf-field">
    <div class="glsrf-field-handle">
        <div class="glsrf-td glsrf-td-type sortable-drag">
            <span class="glsrf-field-type type-{{ data.type }}"></span>
        </div>
        <div class="glsrf-td glsrf-td-label sortable-drag">
            <span>
                <strong><a class="glsrf-toggle-field toggle-field" href="#" tabindex="0" title="<?= _x('Edit field', 'admin-text', 'site-reviews-forms'); ?>">{{{ data.label ? jQuery('<div/>').html(data.label).text() : data.handle }}}</a></strong>
            </span>
        </div>
        <div class="glsrf-td glsrf-td-tag sortable-drag"></div>
        <div class="glsrf-td glsrf-td-action">
            <button type="button" class="button-link glsrf-remove-field" 
                data-tippy-allowHTML="true"
                data-tippy-content='<?= _x('Remove this field?', 'admin-text', 'site-reviews-forms'); ?> <a href="#" class="button remove-field"><?= _x('Yes', 'admin-text', 'site-reviews-forms'); ?></a>'
                data-tippy-delay="0"
                data-tippy-followCursor="false"
                data-tippy-interactive="true"
                data-tippy-offset="[0,0]"
                data-tippy-placement="top"
                data-tippy-theme="danger"
                data-tippy-trigger="click"
            >
                <span class="dashicons-before dashicons-trash"></span>
            </button>
        </div>
    </div>
    <div class="glsrf-field-inner" style="{{ !data.expanded && 'display:none;' }}">
        <div class="glsrf-field-menu">
            <div class="components-panel__header edit-post-sidebar__panel-tabs" tabindex="-1">
                <div class="glsr-panel-tabs" role="tablist" aria-orientation="horizontal">
                    <button type="button" data-panel="general" class="components-button edit-post-sidebar__panel-tab">
                        <?= _x('General', 'admin-text', 'site-reviews-forms'); ?>
                    </button>
                    <button type="button" data-panel="presentation" class="components-button edit-post-sidebar__panel-tab ">
                        <?= _x('Presentation', 'admin-text', 'site-reviews-forms'); ?>
                    </button>
                    <button type="button" data-panel="validation" class="components-button edit-post-sidebar__panel-tab ">
                        <?= _x('Validation', 'admin-text', 'site-reviews-forms'); ?>
                    </button>
                    <button type="button" data-panel="conditions" class="components-button edit-post-sidebar__panel-tab ">
                        <?= _x('Conditions', 'admin-text', 'site-reviews-forms'); ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="glsrf-field-settings"></div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-conditions">
    <div class="glsr-metabox-field" data-option="conditions">
        <div class="glsr-label">
            <label for="conditions-{{ data.cid }}">
                <?= _x('Display Field', 'admin-text', 'site-reviews-forms'); ?>
            </label>
        </div>
        <div class="glsr-input">
            <div data-field="conditions" class="glsr-criteria"></div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-error">
    <span class="glsrf-field-error">{{{ data.error }}}</span>
</script>

<script type="text/html" id="tmpl-glsrf-field-format">
    <div class="glsr-metabox-field" data-option="format">
        <div class="glsr-label">
            <label for=""><?= _x('Display Value As', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <# if (_.isEmpty(data.formats)) { #>
                <input data-field="format" type="text" placeholder="F j, Y" class="glsr-input-value" value="{{ data.format }}" 
                    data-tippy-allowHTML="true"
                    data-tippy-content='<?= _x('Enter a custom date format', 'admin-text', 'site-reviews-forms'); ?> <a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank"><?= _x('documentation on date and time formatting', 'admin-text', 'site-reviews-forms'); ?></a>'
                    data-tippy-interactive="true"
                >
            <# } else { #>
                <select data-field="format" data-tippy-content='<?= _x('How the field value is displayed in the review.', 'admin-text', 'site-reviews-forms'); ?>'>
                    <# _.each(data.formats, function (label, val) { #>
                        <option value="{{ val }}" <# if (data.format === val) { print('selected')} #>>
                            {{ label }}
                        </option>
                    <# }) #>
                </select>
            <# } #>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-format_link_text">
    <div class="glsr-metabox-field" data-option="format_link_text">
        <div class="glsr-label">
            <label for=""><?= _x('Link Text', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <input data-field="format_link_text" type="text" placeholder="" class="glsr-input-value" value="{{ data.format_link_text }}"
                data-tippy-allowHTML="true"
                data-tippy-content='<?= _x('The default link text is the URL.', 'admin-text', 'site-reviews-forms'); ?>'
                data-tippy-interactive="true"
            >
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-hidden">
    <div class="glsr-metabox-field" data-option="hidden">
        <div class="glsr-label">
            <label><?= _x('Hidden', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <div class="glsr-toggle-field">
                <span class="glsr-toggle" data-tippy-content="<?= _x('Do you want this field to be hidden?', 'admin-text', 'site-reviews-forms'); ?>">
                    <input data-field="hidden" type="checkbox" class="glsr-toggle__input" <# if (data.hidden) print('checked') #> value="1">
                    <span class="glsr-toggle__track"></span>
                    <span class="glsr-toggle__thumb"></span>
                </span>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-description">
    <div class="glsr-metabox-field" data-option="description">
        <div class="glsr-label">
            <label for=""><?= _x('Field Description', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <textarea data-field="description" rows="1" class="glsr-input-value" data-tippy-allowHTML="true" data-tippy-content='<?= _x('Field descriptions are displayed below the field label.', 'admin-text', 'site-reviews-forms'); ?>'>{{{ data.description }}}</textarea>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-label">
    <div class="glsr-metabox-field" data-option="label">
        <div class="glsr-label">
            <label for=""><?= _x('Field Label', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <input data-field="label" type="text" class="glsr-input-value" value="{{ data.label }}" data-tippy-content="<?= _x('The Field Label is displayed in the form above the field. If this is a hidden field type, then you can use the label to describe the field but it will not be shown in the form.', 'admin-text', 'site-reviews-forms'); ?>">
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-labels">
    <div class="glsr-metabox-field" data-option="labels">
        <div class="glsr-label">
            <label><?= _x('Range Labels', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <div class="glsr-input-group">
                <div data-tippy-content="<?= _x('This label is displayed immediately above the range field on the left.', 'admin-text', 'site-reviews-forms'); ?>">
                    <input data-field="labels" type="text" class="glsr-input-value" value="{{ data.labels[0] ?? '' }}" placeholder="<?= _x('Label 1', 'admin-text', 'site-reviews-forms'); ?>">
                </div>
                <div data-tippy-content="<?= _x('This label is displayed immediately above the range field in the middle.', 'admin-text', 'site-reviews-forms'); ?>">
                    <input data-field="labels" type="text" class="glsr-input-value" value="{{ data.labels[1] ?? '' }}" placeholder="<?= _x('Label 2', 'admin-text', 'site-reviews-forms'); ?>">
                </div>
                <div data-tippy-content="<?= _x('This label is displayed immediately above the range field on the right.', 'admin-text', 'site-reviews-forms'); ?>">
                    <input data-field="labels" type="text" class="glsr-input-value" value="{{ data.labels[2] ?? '' }}" placeholder="<?= _x('Label 3', 'admin-text', 'site-reviews-forms'); ?>">
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-maxlength">
    <div class="glsr-metabox-field" data-option="maxlength">
        <div class="glsr-label">
            <label><?= _x('Maximum Length', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <div class="glsr-number-field">
                <input data-field="maxlength" type="number" class="glsr-input-value small-text" min="0" step="0" value="{{ data.maxlength }}" data-tippy-allowHTML="1" data-tippy-content="<?= _x('The maximum number of required characters. Enter <code>0</code> for no maxium.', 'admin-text', 'site-reviews-forms'); ?>"> <?php echo _x('characters', 'admin-text', 'site-reviews-forms'); ?>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-minlength">
    <div class="glsr-metabox-field" data-option="minlength">
        <div class="glsr-label">
            <label><?= _x('Minimum Length', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <div class="glsr-number-field">
                <input data-field="minlength" type="number" class="glsr-input-value small-text" min="0" step="0" value="{{ data.minlength }}" data-tippy-allowHTML="1" data-tippy-content="<?= _x('The minimum number of required characters. Enter <code>0</code> for no minimum.', 'admin-text', 'site-reviews-forms'); ?>"> <?php echo _x('characters', 'admin-text', 'site-reviews-forms'); ?>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-name">
    <div class="glsr-metabox-field" data-option="name">
        <div class="glsr-label">
            <label for=""><?= _x('Field Name', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <input data-field="name" type="text" class="glsr-input-value" value="{{ data.name }}" data-tippy-content="<?= _x('The Field Name is the custom field key that is used to save the value to the database. It should be a single alphabetic (a-z) lowercase word with no spaces. Underscores are allowed.', 'admin-text', 'site-reviews-forms'); ?>">
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-options">
    <div class="glsr-metabox-field" data-option="options">
        <div class="glsr-label">
            <label for=""><?= _x('Field Options', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
        <# if ('range' === data.type) { #>
            <textarea data-field="options" rows="3" class="glsr-input-value" placeholder="<?= _x('Option 1', 'admin-text', 'site-reviews-forms'); ?>
 &#10;<?= _x('Option 2', 'admin-text', 'site-reviews-forms'); ?>
 &#10;<?= _x('Option 3', 'admin-text', 'site-reviews-forms'); ?>" data-tippy-allowHTML="true" data-tippy-content='<?= _x('Enter each range option on a new line. Each range option you enter will automatically be given a numeric value starting with 1.<br><br>If you want to make a field conditional on the selected value of a range field, make sure to use the numeric value in the condition (i.e. use "1" for the first option, "2" for the second option, "3" for the third option, etc.).', 'admin-text', 'site-reviews-forms'); ?>'>{{{ data.options }}}</textarea>
        <# } else { #>
            <textarea data-field="options" rows="1" class="glsr-input-value" placeholder="<?= _x('value', 'admin-text', 'site-reviews-forms'); ?> : <?= _x('label', 'admin-text', 'site-reviews-forms'); ?>" data-tippy-allowHTML="true" data-tippy-content='<?= _x('Enter each option on a new line. For more control, you can specify a separate value and label like this:<br><br>red : Red<br>green : Green<br>blue : Blue', 'admin-text', 'site-reviews-forms'); ?>'>{{{ data.options }}}</textarea>
        <# } #>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-placeholder">
    <div class="glsr-metabox-field" data-option="placeholder">
        <div class="glsr-label">
            <label for=""><?= _x('Field Placeholder', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <input data-field="placeholder" type="text" class="glsr-input-value" value="{{ data.placeholder }}" data-tippy-content="<?= _x('The placeholder text provides a brief hint to what kind of information is expected in the field.', 'admin-text', 'site-reviews-forms'); ?>">
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-posttypes">
    <div class="glsr-metabox-field" data-option="posttypes">
        <div class="glsr-label">
            <label for=""><?= _x('Post Types', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <div data-field="posttypes" class="glsr-search-multibox"></div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-required">
    <div class="glsr-metabox-field" data-option="required">
        <div class="glsr-label">
            <label><?= _x('Required', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <div class="glsr-toggle-field">
                <span class="glsr-toggle" data-tippy-content="<?= _x('Do you want this field to be required?', 'admin-text', 'site-reviews-forms'); ?>">
                    <input data-field="required" type="checkbox" class="glsr-toggle__input" <# if (data.required) print('checked') #> value="1">
                    <span class="glsr-toggle__track"></span>
                    <span class="glsr-toggle__thumb"></span>
                </span>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-responsive_width">
    <div class="glsr-metabox-field" data-option="responsive_width">
        <div class="glsr-label">
            <label for=""><?= _x('Field Width', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <div class="glsr-input-group">
            <# _.each(data.responsive_width, function (value, size) { #>
                <div <# if ('sm' !== size) { #>class="glsr-icon-select" <# } #>data-tippy-content="
                    <# if ('sm' === size) { #><?= _x('Select the default field width.', 'admin-text', 'site-reviews-forms'); ?><# } #>
                    <# if ('md' === size) { #><?= sprintf(_x('Select the field width for small screen sizes which are at least %spx wide.', 'admin-text', 'site-reviews-forms'), glsr('site-reviews-forms')->option('responsive_width.md')); ?><# } #>
                    <# if ('lg' === size) { #><?= sprintf(_x('Select the field width for medium screen sizes which are at least %spx wide.', 'admin-text', 'site-reviews-forms'), glsr('site-reviews-forms')->option('responsive_width.lg')); ?><# } #>
                    <# if ('xl' === size) { #><?= sprintf(_x('Select the field width for large screen sizes  which are at least %spx wide).', 'admin-text', 'site-reviews-forms'), glsr('site-reviews-forms')->option('responsive_width.xl')); ?><# } #>
                ">
                <# if ('sm' !== size) { #>
                    <i class="dashicons <# if ('xl' === size) { print('dashicons-desktop') } #><# if ('lg' === size) { print('dashicons-laptop') } #><# if ('md' === size) { print('dashicons-tablet') } #><# if ('sm' === size) { print('dashicons-smartphone') } #>"></i>
                <# } #>
                    <select data-field="responsive_width" data-size="{{ size }}">
                        <option value="gl-col-100" <# if ('gl-col-100' === value) { print('selected') } #>>100%</option>
                        <option value="gl-col-75" <# if ('gl-col-75' === value) { print('selected') } #>>75%</option>
                        <option value="gl-col-66" <# if ('gl-col-66' === value) { print('selected') } #>>66%</option>
                        <option value="gl-col-50" <# if ('gl-col-50' === value) { print('selected') } #>>50%</option>
                        <option value="gl-col-33" <# if ('gl-col-33' === value) { print('selected') } #>>33%</option>
                        <option value="gl-col-25" <# if ('gl-col-25' === value) { print('selected') } #>>25%</option>
                    </select>
                </div>
            <# }) #>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-roles">
    <div class="glsr-metabox-field" data-option="roles">
        <div class="glsr-label">
            <label for=""><?= _x('User Roles', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <div data-field="roles" class="glsr-search-multibox"></div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-save">
    <div class="glsr-metabox-field">
        <div class="glsr-label">
        </div>
        <div class="glsr-input wp-clearfix">
            <div>
                <button type="button" class="button glsrf-save-field save-field"><?= _x('Save Field', 'admin-text', 'site-reviews-forms'); ?></button>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-tag">
    <div class="glsr-metabox-field" data-option="tag">
        <div class="glsr-label">
            <label for=""><?= _x('Template Tag', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <input data-field="tag" type="text" class="glsr-input-value" value="{{ data.tag }}" data-tippy-content="<?= _x('The Template Tag is used to display the field value in the review template, it should be a single alphabetic (a-z) lowercase word with no spaces. Underscores are allowed.', 'admin-text', 'site-reviews-forms'); ?>">
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-tag_label">
    <div class="glsr-metabox-field" data-option="tag_label">
        <div class="glsr-label">
            <label for=""><?= _x('Template Tag Label', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <input data-field="tag_label" type="text" class="glsr-input-value" value="{{ data.tag_label }}" data-tippy-content="<?= _x('The Template Tag Label is displayed with the template tag in the Review Template. It can be used to describe the value being displayed in the review', 'admin-text', 'site-reviews-forms'); ?>">
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-terms">
    <div class="glsr-metabox-field" data-option="terms">
        <div class="glsr-label">
            <label for=""><?= _x('Categories', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <div data-field="terms" class="glsr-search-multibox"></div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-type">
    <div class="glsr-metabox-field" data-option="type">
        <div class="glsr-label">
            <label for=""><?= _x('Field Type', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <select data-field="type" data-tippy-content='<?= _x('"Review Fields" can only be used once in the form.', 'admin-text', 'site-reviews-forms'); ?>'>
                <optgroup label="<?= _x('Custom Fields', 'admin-text', 'site-reviews-forms'); ?>">
                    <?php foreach ($customFields as $field): ?>
                    <option value="<?= $field->type; ?>" <# if (data.type === '<?= $field->type; ?>') { print('selected')} #>>
                        <?= $field->handle ?>
                    </option>
                    <?php endforeach; ?>
                </optgroup>
                <optgroup label="<?= _x('Review Fields', 'admin-text', 'site-reviews-forms'); ?>">
                    <?php foreach ($reviewFields as $field): ?>
                    <option value="<?= $field->type; ?>" <# if (data.type === '<?= $field->type; ?>') { print('selected')} #>>
                        <?= $field->handle ?>
                    </option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-users">
    <div class="glsr-metabox-field" data-option="users">
        <div class="glsr-label">
            <label for=""><?= _x('Users', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <div data-field="users" class="glsr-search-multibox"></div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-field-value">
    <div class="glsr-metabox-field" data-option="value">
        <div class="glsr-label">
            <label for=""><?= _x('Default Value', 'admin-text', 'site-reviews-forms'); ?></label>
        </div>
        <div class="glsr-input wp-clearfix">
            <input data-field="value" type="{{ !!~['rating','number'].indexOf(data.type) || !!~['rating'].indexOf(data.name) ? 'number' : 'text' }}" class="glsr-input-value" value="{{ data.value }}" data-tippy-content="<?= _x('Leave this blank if you do not want the field to have a default value.', 'admin-text', 'site-reviews-forms'); ?>">
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-multibox">
    <div class="glsr-search-multibox-entries">
        <div class="glsr-selected-entries"></div>
        <input id="{{ data.key }}-{{ data.cid }}" class="glsr-search-input" type="search" autocomplete="off" placeholder="<?= esc_attr_x('Select a value', 'admin-text', 'site-reviews-forms'); ?>">
    </div>
    <div class="glsr-search-results">
        <# _.each(data.options, function (option) { #>
            <span class="glsr-search-result" tabindex="0" data-slug="{{ option.slug }}">{{{ option.name }}}</span>
        <# }) #>
    </div>
</script>

<script type="text/html" id="tmpl-glsrf-multibox-entry">
    <span class="glsr-multibox-entry">
        <button type="button" data-slug="{{ data.slug }}" class="glsr-remove-button glsr-remove-icon">
            <span class="screen-reader-text"><?= _x('Remove entry', 'admin-text', 'site-reviews-forms'); ?></span>
        </button>
        <span data-slug="{{ data.slug }}">{{{ data.name }}}</span>
    </span>
</script>

<script type="text/html" id="tmpl-glsrf-tag">
    <# if (data.tag.length) { #><code class="glsrf-template-tag template-tag" tabindex="0" data-tippy-content="<?= _x('This is the template tag for the field.', 'admin-text', 'site-reviews-forms'); ?>">&#123;&#123; {{ data.tag }} &#125;&#125;</code><# } #>
</script>
