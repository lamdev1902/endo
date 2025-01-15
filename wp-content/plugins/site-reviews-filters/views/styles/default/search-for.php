<?php defined('WPINC') || die; ?>

<div class="wp-block-search__button-outside wp-block-search__text-button wp-block-search {{ class }}">
    <div class="wp-block-search__inside-wrapper">
        {{ search }}
        <button type="submit" class="wp-block-search__button wp-element-button {{ button_class }}" aria-label="{{ submit_text }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 0 1-10.5 6.062A7 7 0 0 1 10 3a7 7 0 0 1 7 7h0z"/></svg>
        </button>
    </div>
</div>
