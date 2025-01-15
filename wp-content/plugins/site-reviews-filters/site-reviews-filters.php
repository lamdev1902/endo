<?php
/**
 * ╔═╗╔═╗╔╦╗╦╔╗╔╦  ╦  ╔═╗╔╗ ╔═╗
 * ║ ╦║╣ ║║║║║║║║  ║  ╠═╣╠╩╗╚═╗
 * ╚═╝╚═╝╩ ╩╩╝╚╝╩  ╩═╝╩ ╩╚═╝╚═╝
 * 
 * @copyright  Gemini Labs Limited
 *
 * Plugin Name:       Site Reviews: Review Filters
 * Plugin URI:        https://niftyplugins.com/plugins/site-reviews-filters
 * Description:       Filter, search, and sort reviews
 * Version:           3.2.1
 * Author:            Paul Ryley
 * Author URI:        https://niftyplugins.com
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Text Domain:       site-reviews-filters
 * Domain Path:       languages
 * Update URI:        https://niftyplugins.com
 */
defined('WPINC') || exit;

require_once __DIR__.'/autoload.php';
require_once __DIR__.'/compatibility.php';

$gatekeeper = new GeminiLabs\SiteReviews\Addon\Filters\Gatekeeper(__FILE__, '7.2', '8');

add_action('site-reviews/addon/register', function ($app) use ($gatekeeper) {
    $app->register(GeminiLabs\SiteReviews\Addon\Filters\Application::class,
        $gatekeeper->authorize()
    );
});
