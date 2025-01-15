<?php

namespace GeminiLabs\SiteReviews\Addon\Notifications\Integrations\WooCommerce;

use GeminiLabs\SiteReviews\Addon\Notifications\Application;

/*
 * Include dependencies.
 */
if (!class_exists('WC_Email', false)) {
    include_once WC_ABSPATH.'includes/emails/class-wc-email.php'; // @phpstan-ignore-line
}

class ReminderEmail extends \WC_Email
{
    public const ID = 'WC_Email_GLSR_Customer_Review_Reminder';

    public function __construct()
    {
        $this->customer_email = true;
        $this->description = _x('Review reminder emails are sent to the customer to remind them to rate their order.', 'admin-text', 'site-reviews-notifications');
        $this->id = str_replace('wc_email_', '', strtolower(static::ID));
        $this->placeholders = [
            '{customer_name}' => '',
            '{order_date}' => '',
            '{order_number}' => '',
        ];
        $this->template_base = glsr(Application::class)->path().'templates/';
        $this->template_html = 'woocommerce/review-reminder-html.php';
        $this->template_plain = 'woocommerce/review-reminder-plain.php';
        $this->title = _x('Review Reminder (Site Reviews)', 'admin-text', 'site-reviews-notifications');
        parent::__construct();
    }

    public function admin_options()
    {
        add_filter('woocommerce_template_directory', [$this, 'filterTemplateDirectory']);
        parent::admin_options();
        remove_filter('woocommerce_template_directory', [$this, 'filterTemplateDirectory']);
    }

    public function filterTemplateDirectory(): string
    {
        return Application::ID;
    }

    public function generate_days_after_html(string $key, array $data = []): string
    {
        $field_key = $this->get_field_key($key);
        $defaults = [
            'after' => '',
            'class' => '',
            'css' => '',
            'custom_attributes' => [],
            'desc_tip' => false,
            'description' => '',
            'disabled' => false,
            'placeholder' => '',
            'title' => '',
            'type' => 'text',
        ];
        $data = wp_parse_args($data, $defaults);
        ob_start();
        ?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="<?php echo esc_attr($field_key); ?>"><?php echo wp_kses_post($data['title']); ?> <?php echo $this->get_tooltip_html($data); // WPCS: XSS ok.?></label>
            </th>
            <td class="forminp">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php echo wp_kses_post($data['title']); ?></span></legend>
                    <input class="input-text regular-input <?php echo esc_attr($data['class']); ?>" type="number" name="<?php echo esc_attr($field_key); ?>" id="<?php echo esc_attr($field_key); ?>" style="width:75px;<?php echo esc_attr($data['css']); ?>" value="<?php echo esc_attr($this->get_option($key)); ?>" placeholder="<?php echo esc_attr($data['placeholder']); ?>" <?php disabled($data['disabled'], true); ?> <?php echo $this->get_custom_attribute_html($data); // WPCS: XSS ok.?> />
                    <p style="display:inline-flex;margin-left:.25em;margin-right:.25em;" class="description"><?php echo wp_kses_post($data['after']); ?></p>
                    <?php echo $this->get_description_html($data); // WPCS: XSS ok.?>
                </fieldset>
            </td>
        </tr>
        <?php
        return ob_get_clean();
    }

    /**
     * @return string
     */
    public function get_additional_content()
    {
        $content = $this->get_option('additional_content', $this->get_default_additional_content());
        return apply_filters('woocommerce_email_additional_content_'.$this->id, $this->format_string($content), $this->object, $this);
    }

    /**
     * @return string
     */
    public function get_content_html()
    {
        $items = is_a($this->object, 'WC_Order')
            ? $this->object->get_items()
            : [];
        return wc_get_template_html($this->template_html, [
            'content' => $this->get_additional_content(),
            'email' => $this,
            'email_heading' => $this->get_heading(),
            'image_size' => [32, 32],
            'items' => $items,
            'margin_side' => is_rtl() ? 'left' : 'right',
            'order' => $this->object,
            'plain_text' => false,
            'sent_to_admin' => false,
            'text_align' => is_rtl() ? 'right' : 'left',
        ]);
    }

    /**
     * @return string
     */
    public function get_content_plain()
    {
        $items = is_a($this->object, 'WC_Order')
            ? $this->object->get_items()
            : [];
        return wc_get_template_html($this->template_plain, [
            'content' => $this->get_additional_content(),
            'email' => $this,
            'email_heading' => $this->get_heading(),
            'items' => $items,
            'order' => $this->object,
            'plain_text' => true,
            'sent_to_admin' => false,
        ]);
    }

    /**
     * @return string
     */
    public function get_default_additional_content()
    {
        $greeting = __('Hi {customer_name},', 'site-reviews-notifications');
        $content = __('Thanks for shopping with us! What do you think of your purchase? We\'d love to hear your feedback.', 'site-reviews-notifications');
        return $greeting.PHP_EOL.PHP_EOL.$content;
    }

    /**
     * @return string
     */
    public function get_default_heading()
    {
        return __('Share your thoughts! 📣', 'site-reviews-notifications');
    }

    /**
     * @return string
     */
    public function get_default_subject()
    {
        return __('{site_title}: Rate Your Purchase', 'site-reviews-notifications');
    }

    public function init_form_fields()
    {
        parent::init_form_fields();
        $order = array_flip([ // order is intentional
            'enabled',
            'reminder_trigger',
            'reminder_delay',
            'subject',
            'heading',
            'additional_content',
            'email_type',
            'reminder_categories',
            'reminder_guests',
            'reminder_recheck',
        ]);
        $fields = array_merge($this->form_fields, glsr(Application::ID)->config('woocommerce'));
        $fields = array_replace($order, $fields);
        $fields['additional_content']['css'] = 'width:100%; max-width:800px; height:200px;';
        $fields['additional_content']['default'] = '';
        $fields['additional_content']['description'] = $fields['heading']['description'];
        $fields['additional_content']['placeholder'] = $this->get_default_additional_content();
        $fields['additional_content']['title'] = _x('Email content', 'admin-text', 'site-reviews-notifications');
        $fields['enabled']['default'] = 'no';
        $fields['enabled']['description'] = _x("If you enable this notification, it's very important that you allow customers to give their consent during the checkout process. You will also need to update your Terms and Conditions page (in some countries this is a legal requirement).", 'admin-text', 'site-reviews-notifications');
        $this->form_fields = $fields;
    }

    /**
     * @param int $orderId
     * @return bool
     */
    public function trigger($orderId)
    {
        $result = false;
        $order = wc_get_order($orderId);
        if (!is_a($order, 'WC_Order')) {
            return $result;
        }
        $this->setup_locale();
        $user = $order->get_user();
        $this->object = $order;
        $this->recipient = $order->get_billing_email();
        $this->placeholders['{customer_name}'] = esc_html($order->get_billing_first_name());
        $this->placeholders['{order_date}'] = wc_format_datetime($order->get_date_created());
        $this->placeholders['{order_number}'] = $order->get_order_number();
        if ($this->is_enabled() && $this->get_recipient()) {
            $result = $this->send(
                $this->get_recipient(),
                $this->get_subject(),
                $this->get_content(),
                $this->get_headers(),
                $this->get_attachments()
            );
        }
        $this->restore_locale();
        return $result;
    }
}
