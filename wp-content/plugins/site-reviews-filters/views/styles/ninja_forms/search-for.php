<?php defined('WPINC') || die; ?>

<div class="wp-block-search__button-outside wp-block-search__text-button wp-block-search {{ class }}">
    <div class="wp-block-search__inside-wrapper">
        {{ search }}
        <button type="submit" class="wp-block-search__button {{ button_class }}">
            <span>{{ submit_text }}</span>
        </button>
    </div>
</div>
