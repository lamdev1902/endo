<?php defined('WPINC') || die; ?>

<h2 class="title">{{ title }}</h2>

<div class="components-notice is-info" style="margin-left:0;">
    <p class="components-notice__content">
    <?php
        if (glsr_get_option('integrations.woocommerce.enabled', false, 'bool')) {
            printf(_x('Schedule review reminders for product orders on the WooCommerce %sEmails%s settings page.' ,'admin-text', 'site-reviews-notifications'),
                sprintf('<a href="%s">', admin_url('admin.php?page=wc-settings&tab=email')),
                '</a>'
            );
        } else {
            printf(_x('If you %senable%s the WooCommerce Reviews integration, you will be able to schedule review reminders for product orders.' ,'admin-text', 'site-reviews-notifications'),
                sprintf('<a href="%s">', glsr_admin_url('settings', 'integrations', 'woocommerce')),
                '</a>'
            );
        }
    ?>
    </p>
</div>

<div id="glsrn" class="gl-table">
    <input type="hidden" id="glsrn-data" name="{{ dataKey }}" value=''>
    <div class="gl-thead">
        <div class="gl-col gl-col-primary">
            <?= _x('Notification', 'admin-text', 'site-reviews-notifications'); ?>
        </div>
        <div class="gl-col gl-col-schedule">
            <?= _x('Schedule', 'admin-text', 'site-reviews-notifications'); ?>
        </div>
        <div class="gl-col gl-col-recipient">
            <?= _x('Recipient', 'admin-text', 'site-reviews-notifications'); ?>
        </div>
    </div>
    <div class="gl-tbody"></div>
    <div class="gl-tfoot">
        <button class="button button-primary button-large add-notification" type="button">
            <?= _x('Add Notification', 'admin-text', 'site-reviews-notifications'); ?>
        </button>
    </div>
</div>

<table class="form-table">
    <tbody>
        {{ rows }}
    </tbody>
</table>
