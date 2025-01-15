<?php defined('ABSPATH') || exit; ?>

<div class="notice notice-warning inline">
    <p>
        <?php
            printf(_x('%sEnable product reviews%s in the WooCommerce settings to use the Review Reminder email notification.', '<a>Enable product reviews</a> (admin-text)', 'site-reviews-notifications'),
                sprintf('<a href="%s">', admin_url('admin.php?page=wc-settings&tab=products')), '</a>'
            );
        ?>
    </p>
</div>
