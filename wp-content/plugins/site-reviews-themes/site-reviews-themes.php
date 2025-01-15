<?php
/**
 * ╔═╗╔═╗╔╦╗╦╔╗╔╦  ╦  ╔═╗╔╗ ╔═╗
 * ║ ╦║╣ ║║║║║║║║  ║  ╠═╣╠╩╗╚═╗
 * ╚═╝╚═╝╩ ╩╩╝╚╝╩  ╩═╝╩ ╩╚═╝╚═╝
 * 
 * @copyright  Gemini Labs Limited
 *
 * Plugin Name:       Site Reviews: Review Themes
 * Plugin URI:        https://niftyplugins.com/plugins/site-reviews-themes
 * Description:       Themes for your reviews
 * Version:           1.0.0-beta49
 * Author:            Paul Ryley
 * Author URI:        https://niftyplugins.com
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Text Domain:       site-reviews-themes
 * Domain Path:       languages
 * Update URI:        https://niftyplugins.com
 */
defined('WPINC') || exit;

require_once __DIR__.'/autoload.php';
require_once __DIR__.'/compatibility.php';

$gatekeeper = new GeminiLabs\SiteReviews\Addon\Themes\Gatekeeper(__FILE__, '7.2', '7.3');

add_action('site-reviews/addon/register', function ($app) use ($gatekeeper) {
    $app->register(GeminiLabs\SiteReviews\Addon\Themes\Application::class,
        $gatekeeper->authorize()
    );
});
