<?php defined('ABSPATH') || exit; ?>

<?php if (!empty($reports)) { ?>
    <ul class="glsr-action-logs">
        <?php foreach ($reports as $report) { ?>
        <li class="glsr-action-log" data-id="<?php echo absint($report['ID']); ?>">
            <div class="log-entry">
                <?php echo wpautop(wptexturize(esc_html($report['data']->reason))); ?>
                <?php echo wpautop(wptexturize(esc_html($report['data']->message))); ?>
            </div>
            <div class="log-meta">
                <div>
                    <abbr title="<?php echo esc_attr($report['date']); ?>">
                        <?php echo esc_html(sprintf(_x('%1$s at %2$s', '%1$s: report date %2$s: report time', 'site-reviews-actions'),
                            date_i18n(get_option('date_format') ?: 'F j, Y'),
                            date_i18n(get_option('time_format') ?: 'g:i a')
                        )); ?>
                    </abbr>
                    <?php echo sprintf(_x('by %s', '%s: link to user', 'site-reviews-actions'), $report['data']->user_url); ?>
                </div>
                <div>
                    <button type="button" class="button-link button-link-delete" data-nonce="<?php echo wp_create_nonce('delete-report'); ?>"><?php echo __('Delete Report', 'site-reviews-actions'); ?></button>
                </div>
            </div>
        </li>
        <?php } ?>
    </ul>
<?php } else { ?>
    <p class="glsr-action-logs">
        <button type="button" class="button button-secondary" data-nonce="<?php echo wp_create_nonce('delete-report'); ?>"><?php echo __('Unflag review', 'site-reviews-actions'); ?></button>
    </p>
<?php } ?>

<div class="glsr-metabox-footer">
    <p>
        <?php echo _x('Delete all reports to unflag this review.', 'admin-text', 'site-reviews-actions'); ?>
    </p>
</div>
