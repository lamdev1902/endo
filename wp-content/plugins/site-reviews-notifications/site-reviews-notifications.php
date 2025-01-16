<?php
/**
 * ╔═╗╔═╗╔╦╗╦╔╗╔╦  ╦  ╔═╗╔╗ ╔═╗
 * ║ ╦║╣ ║║║║║║║║  ║  ╠═╣╠╩╗╚═╗
 * ╚═╝╚═╝╩ ╩╩╝╚╝╩  ╩═╝╩ ╩╚═╝╚═╝
 *
 * @copyright  Gemini Labs Limited
 *
 * Plugin Name:       Site Reviews: Review Notifications
 * Plugin URI:        https://niftyplugins.com/plugins/site-reviews-notifications
 * Description:       Create custom notifications when reviews are submitted
 * Version:           2.1.1
 * Author:            Paul Ryley
 * Author URI:        https://niftyplugins.com
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Text Domain:       site-reviews-notifications
 * Domain Path:       languages
 * Update URI:        https://niftyplugins.com
 */
defined('WPINC') || exit;

require_once __DIR__.'/autoload.php';

$gatekeeper = new GeminiLabs\SiteReviews\Addon\Notifications\Gatekeeper(__FILE__, '7.2', '8');

add_action('site-reviews/addon/register', function ($app) use ($gatekeeper) {
    $app->register(GeminiLabs\SiteReviews\Addon\Notifications\Application::class,
        $gatekeeper->authorize()
    );
});
