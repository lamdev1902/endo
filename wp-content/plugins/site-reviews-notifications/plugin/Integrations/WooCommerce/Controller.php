<?php

namespace GeminiLabs\SiteReviews\Addon\Notifications\Integrations\WooCommerce;

use GeminiLabs\SiteReviews\Addon\Notifications\Application;
use GeminiLabs\SiteReviews\Addon\Notifications\Integrations\WooCommerce\ReminderEmail;
use GeminiLabs\SiteReviews\Addon\Notifications\Queue;
use GeminiLabs\SiteReviews\Addon\Notifications\Template;
use GeminiLabs\SiteReviews\Addons\Controller as AddonController;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\HookProxy;

class Controller
{
    use HookProxy;

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * @param array $emails
     * @filter woocommerce_email_classes
     */
    public function filterEmailClasses($emails): array
    {
        $emails = Arr::consolidate($emails);
        $emails[ReminderEmail::ID] = glsr(ReminderEmail::class);
        return $emails;
    }

    /**
     * @param string $template
     * @param string $templateName
     * @filter wc_get_template
     */
    public function filterTemplate($template, $templateName): string
    {
        $templateName = Cast::toString($templateName);
        $templateNames = [
            'woocommerce/review-reminder-html.php',
            'woocommerce/review-reminder-plain.php',
        ];
        if (!in_array($templateName, $templateNames)) {
            return Cast::toString($template);
        }
        return $this->app()->path(sprintf('templates/%s', $templateName));
    }

    /**
     * @param array $emails
     * @filter wcml_emails_options_to_translate
     */
    public function filterTranslatableEmails($emails): array
    {
        $email = str_replace('wc_email_', 'woocommerce_', strtolower(ReminderEmail::ID)).'_settings';
        $emails = Arr::consolidate($emails);
        $emails[] = $email;
        return $emails;
    }

    /**
     * @action woocommerce_admin_field_email_notification
     */
    public function renderTemplateNotice(): void
    {
        glsr(Template::class)->render('views/integrations/woocommerce/notices/enable-reviews');
    }

    /**
     * @param int $orderId
     * @action woocommerce_order_status_<completed|processing>
     */
    public function scheduleReminderEmail($orderId): void
    {
        $order = wc_get_order(Cast::toInt($orderId));
        if (!is_a($order, 'WC_Order')) {
            return; // this is not an order
        }
        if (!$this->isQueueable($order)) {
            return;
        }
        if ($this->app()->filterBool('product/reminder/skip', false, $order)) {
            $order->add_order_note(
                _x('A review reminder was not scheduled due to a custom filter hook.', 'admin-text', 'site-reviews-notifications')
            );
            return;
        }
        $timestamp = $this->timestamp($order);
        $scheduled = glsr(Queue::class)->once($timestamp, 'product/reminder', ['order' => $order->get_id()]);
        if (0 === $scheduled) {
            $order->add_order_note(
                _x('A review reminder could not be scheduled.', 'admin-text', 'site-reviews-notifications')
            );
        } else {
            $order->add_order_note(
                sprintf(_x('A review reminder was scheduled for %s at %s.', 'admin-text', 'site-reviews-notifications'),
                    date_i18n('F j, Y', $timestamp),
                    date_i18n('g:i A', $timestamp)
                )
            );
        }
    }

    /**
     * @param int $orderId
     * @action site-reviews-notifications/product/reminder
     */
    public function sendReminderEmail($orderId): void
    {
        $email = WC()->mailer()->emails[ReminderEmail::ID];
        $orderId = Cast::toInt($orderId);
        if ($email->trigger($orderId)) { // @phpstan-ignore-line
            update_post_meta($orderId, '_review_reminder_sent', 1);
        }
    }

    /**
     * @action action_scheduler_before_execute
     */
    public function verifyReminderEmail($actionId): void
    {
        $action = glsr(Queue::class)->fetchAction($actionId);
        if ($this->app()->id.'/product/reminder' !== $action->get_hook()) {
            return;
        }
        $order = wc_get_order(Arr::get($action->get_args(), 'order'));
        if (!is_a($order, 'WC_Order')) {
            return; // not a valid order ID
        }
        if (Option::get('reminder_recheck', false, 'bool') && $order->get_status() !== Option::get('reminder_trigger', 'completed')) {
            glsr(Queue::class)->cancelAction($actionId);
        }
    }

    protected function isCategoryInOrder(\WC_Order $order): bool
    {
        $termIds = Arr::uniqueInt(Option::get('reminder_categories', [], 'array'));
        if (empty($termIds)) {
            return true;
        }
        $items = $order->get_items();
        foreach ($items as $itemId => $item) {
            if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
                continue;
            }
            $categories = get_the_terms($item['product_id'], 'product_cat');
            $categories = wp_list_pluck($categories, 'term_id');
            if (!empty(array_intersect($categories, $termIds))) {
                return true;
            }
        }
        return false;
    }

    protected function isQueueable(\WC_Order $order): bool
    {
        if (!Option::get('enabled', false, 'bool')) {
            glsr_log('WC Email skipped: reminders are disabled');
            return false;
        }
        if (function_exists('wcs_order_contains_renewal') && wcs_order_contains_renewal($order->get_id())) {
            glsr_log('WC Email skipped: this is a Stripe renewal');
            return false;
        }
        if (!$this->isCategoryInOrder($order)) {
            glsr_log('WC Email skipped: required category missing');
            return false;
        }
        if (!$order->get_user() && !Option::get('reminder_guests', false, 'bool')) {
            glsr_log('WC Email skipped: reminders disabled for guest users');
            return false;
        }
        if (glsr(Queue::class)->isPending('product/reminder', ['order' => $order->get_id()])) {
            glsr_log('WC Email skipped: this is a duplicate');
            return false;
        }
        if (!empty(get_post_meta($order->get_id(), '_review_reminder_sent', true))) {
            glsr_log('WC Email skipped: already sent for this order');
            return false;
        }
        return true;
    }

    protected function timestamp(\WC_Order $order): int
    {
        $days = Option::get('reminder_delay', 7, 'int'); // 7 is the default
        $days = $this->app()->filterInt('woocommerce/order/reminder_delay', $days, $order);
        return time() + ($days * DAY_IN_SECONDS);
    }
}
