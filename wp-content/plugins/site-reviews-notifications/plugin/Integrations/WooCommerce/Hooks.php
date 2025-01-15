<?php

namespace GeminiLabs\SiteReviews\Addon\Notifications\Integrations\WooCommerce;

use GeminiLabs\SiteReviews\Hooks\AbstractHooks;

class Hooks extends AbstractHooks
{
    public function run(): void
    {
        if ($this->isIntegrationEnabled()) {
            $this->hook(Controller::class, $this->wooHooks());
        }
    }

    protected function isIntegrationEnabled(): bool
    {
        return 'yes' === $this->option('integrations.woocommerce.enabled')
            && class_exists('WooCommerce')
            && function_exists('WC');
    }

    protected function isWooEnabled(): bool
    {
        return 'yes' === get_option('woocommerce_enable_reviews', 'yes');
    }

    protected function wooHooks(): array
    {
        if (!$this->isWooEnabled()) {
            return [
                ['renderTemplateNotice', 'woocommerce_admin_field_email_notification', 5],
            ];
        }
        $status = Option::get('reminder_trigger', 'completed');
        return [
            ['filterEmailClasses', 'woocommerce_email_classes'],
            ['filterTemplate', 'wc_get_template', 10, 2],
            ['filterTranslatableEmails', 'wcml_emails_options_to_translate'], // WPML
            ['scheduleReminderEmail', 'woocommerce_order_status_'.$status, 20],
            ['sendReminderEmail', 'site-reviews-notifications/product/reminder'],
            ['verifyReminderEmail', 'action_scheduler_before_execute'],
        ];
    }
}
