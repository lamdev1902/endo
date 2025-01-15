<?php

namespace GeminiLabs\SiteReviews\Addon\Notifications\Integrations\WooCommerce;

use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;

class Option
{
    /**
     * @param mixed $fallback
     * @return mixed
     */
    public static function get(string $path, $fallback = '', string $cast = 'string')
    {
        static $settings;
        if (empty($settings)) {
            $optionKey = str_replace('wc_email_', '', strtolower(ReminderEmail::ID));
            $optionKey = "woocommerce_{$optionKey}_settings";
            $settings = Cast::toArray(get_option($optionKey));
        }
        return Cast::to($cast, Arr::get($settings, $path, $fallback));
    }
}
