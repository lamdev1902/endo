<?php
/**
 * ╔═╗╔═╗╔╦╗╦╔╗╔╦  ╦  ╔═╗╔╗ ╔═╗
 * ║ ╦║╣ ║║║║║║║║  ║  ╠═╣╠╩╗╚═╗
 * ╚═╝╚═╝╩ ╩╩╝╚╝╩  ╩═╝╩ ╩╚═╝╚═╝.
 *
 * @copyright  Gemini Labs Limited
 *
 * Plugin Name:       Site Reviews: Review Authors
 * Plugin URI:        https://niftyplugins.com/plugins/site-reviews-authors
 * Description:       Allow people to update and manage their reviews from the frontend.
 * Version:           1.2.0
 * Author:            Paul Ryley
 * Author URI:        https://niftyplugins.com
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Text Domain:       site-reviews-authors
 * Domain Path:       languages
 * Update URI:        https://niftyplugins.com
 */
defined('WPINC') || exit;

require_once __DIR__.'/autoload.php';

$gatekeeper = new GeminiLabs\SiteReviews\Addon\Authors\Gatekeeper(__FILE__, '7.2', '8');

add_action('site-reviews/addon/register', function ($app) use ($gatekeeper) {
    $app->register(GeminiLabs\SiteReviews\Addon\Authors\Application::class,
        $gatekeeper->authorize()
    );
});
