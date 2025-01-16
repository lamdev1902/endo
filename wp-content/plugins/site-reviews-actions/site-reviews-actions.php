<?php
/**
 * ╔═╗╔═╗╔╦╗╦╔╗╔╦  ╦  ╔═╗╔╗ ╔═╗
 * ║ ╦║╣ ║║║║║║║║  ║  ╠═╣╠╩╗╚═╗
 * ╚═╝╚═╝╩ ╩╩╝╚╝╩  ╩═╝╩ ╩╚═╝╚═╝.
 *
 * @copyright  Gemini Labs Limited
 * 
 * Plugin Name:       Site Reviews: Review Actions
 * Plugin URI:        https://niftyplugins.com/plugins/site-reviews-actions
 * Description:       Allow people to upvote, report, and translate your reviews.
 * Version:           1.0.0-beta6
 * Author:            Paul Ryley
 * Author URI:        https://niftyplugins.com
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Text Domain:       site-reviews-actions
 * Domain Path:       languages
 * Update URI:        https://niftyplugins.com
 */
defined('WPINC') || exit;

require_once __DIR__.'/autoload.php';

$gatekeeper = new GeminiLabs\SiteReviews\Addon\Actions\Gatekeeper(__FILE__, '7.2', '7.3');

add_action('site-reviews/addon/register', function ($app) use ($gatekeeper) {
    $app->register(GeminiLabs\SiteReviews\Addon\Actions\Application::class,
        $gatekeeper->authorize()
    );
});
