<?php defined('WPINC') || die; ?>

<h2 class="title">{{ title }}</h2>

<div class="glsr-notice components-notice is-warning" style="background-color:#fff;margin-left:0;">
    <p class="components-notice__content">
        <span class="dashicons-before dashicons-warning"></span>
        This is a beta version of Review Actions, please use the <a href="https://niftyplugins.com/account/support/" target="_blank">Support Form</a> on your Nifty Plugins account to report any bugs.
    </p>
</div>

<table class="form-table">
    <tbody>
        {{ rows }}
    </tbody>
</table>
