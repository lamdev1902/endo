<?php defined('ABSPATH') || exit; ?>

<div id="misc-pub-language" class="misc-pub-section" data-action="change-language">
    <?php echo esc_html_x('Language', 'admin-text', 'site-reviews-actions'); ?>:
    <span id="language-text" class="misc-pub-text">
        <?php echo $languages[$language] ?? esc_html_x('English', 'admin-text', 'site-reviews-actions'); ?>
    </span>
    <a href="#language" data-click="edit" class="hide-if-no-js edit-language" role="button">
        <span aria-hidden="true"><?php echo esc_html_x('Edit', 'admin-text', 'site-reviews-actions'); ?></span>
        <span class="screen-reader-text"><?php echo esc_html_x('Edit review language', 'admin-text', 'site-reviews-actions'); ?></span>
    </a>
    <div id="language-select" class="misc-pub-select hide-if-js">
        <input type="hidden" name="<?php echo glsr()->id; ?>[language]" value="<?php echo $language; ?>" />
        <label for="language" class="screen-reader-text">
            <?php echo esc_html_x('Set language', 'admin-text', 'site-reviews-actions'); ?>
        </label>
        <select id="language">
        <?php foreach ($languages as $key => $value) { ?>
            <option value="<?php echo esc_attr($key); ?>"<?php selected($language, $key); ?>><?php echo $value; ?></option>
        <?php } ?>
        </select>
        <a href="#language" data-click="save" class="button hide-if-no-js" role="button">
            <?php echo esc_html_x('OK', 'admin-text', 'site-reviews-actions'); ?>
        </a>
        <a href="#language" data-click="cancel" class="button-cancel hide-if-no-js" role="button">
            <?php echo esc_html_x('Cancel', 'admin-text', 'site-reviews-actions'); ?>
        </a>
        <div class="glsr-notice-inline components-notice is-info">
            <p class="components-notice__content"><?php echo esc_html_x('The Review Actions addon uses this as the source language when translating the review to English with the DeepL API.', 'admin-text', 'site-reviews-actions'); ?></p>
        </div>
    </div>
</div>
