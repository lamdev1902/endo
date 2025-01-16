<?php defined('WPINC') || die; ?>

<div id="glsrt">
    <input type="hidden" data-templates value='<?= $templates; ?>'>
    <input type="hidden" name="layout_review" data-layout value='<?= $layout; ?>'>
    <input type="hidden" name="theme_review" data-settings='<?= $data; ?>'>
    <div class="glsrt-inner">
        <div class="glsrt-nav"></div>
        <div class="glsrt-settings"></div>
    </div>
    <div id="glsrt-preview" class="glsrt-preview"></div>
</div>
