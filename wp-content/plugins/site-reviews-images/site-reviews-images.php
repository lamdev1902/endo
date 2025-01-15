<?php
/**
 * ╔═╗╔═╗╔╦╗╦╔╗╔╦  ╦  ╔═╗╔╗ ╔═╗
 * ║ ╦║╣ ║║║║║║║║  ║  ╠═╣╠╩╗╚═╗
 * ╚═╝╚═╝╩ ╩╩╝╚╝╩  ╩═╝╩ ╩╚═╝╚═╝
 * 
 * @copyright  Gemini Labs Limited
 *
 * Plugin Name:       Site Reviews: Review Images
 * Plugin URI:        https://niftyplugins.com/plugins/site-reviews-images
 * Description:       Add images to reviews
 * Version:           4.1.2
 * Author:            Paul Ryley
 * Author URI:        https://niftyplugins.com
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Text Domain:       site-reviews-images
 * Domain Path:       languages
 * Update URI:        https://niftyplugins.com
 */
defined('WPINC') || exit;

require_once __DIR__.'/autoload.php';

$gatekeeper = new GeminiLabs\SiteReviews\Addon\Images\Gatekeeper(__FILE__, '7.2', '8');

add_action('site-reviews/addon/register', function ($app) use ($gatekeeper) {
    $app->register(GeminiLabs\SiteReviews\Addon\Images\Application::class,
        $gatekeeper->authorize()
    );
});
