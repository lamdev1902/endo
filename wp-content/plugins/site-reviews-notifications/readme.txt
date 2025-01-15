=== Site Reviews: Review Notifications ===
Contributors: pryley, geminilabs
Donate link: https://ko-fi.com/pryley
Tags: Site Reviews
Tested up to: 6.7
Stable tag: 2.1.1

Create custom notifications when reviews are submitted

== Description ==

= Minimum Plugin Requirements =

- WordPress 6.1
- PHP 7.4

== Installation ==

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 2.1.1 (2024-10-24) =

- Fixed WooCommerce integration

= 2.1.0 (2024-09-24) =

- Added "site-reviews-notifications/woocommerce/order/reminder_delay" filter hook
- Fixed addon updater
- Fixed compatibility with Site Reviews v7.1
- Fixed links in notifications

= 2.0.1 (2024-06-07) =

- Fixed test email recipient (now only sends to admin)

= 2.0.0 (2024-05-03) =

- 🚨 Requires Site Reviews v7.0.0
- Added Assigned Post Type condition
- Added Reviewer (is guest/is logged in) condition
- Added UIDs to notifications which can be used in filter hooks to target specific notifications
- Fixed export/import of notifications in settings
- Fixed Is Verified condition
- Fixed WPML-translated product links in Review Reminder emails

= 1.4.1 (2024-02-16) =

- Fixed Review Reminder Email options

= 1.4.0 (2023-10-20) =

- Added "Send Test Email" button
- Added "site-reviews-notifications/condition/is-valid" filter hook
- Added "site-reviews-notifications/notification/is-valid" filter hook
- Added "was verified" notification condition
- Added compatibility with the [WooCommerce Multilingual & Multicurrency with WPML](https://wordpress.org/plugins/woocommerce-multilingual/) plugin
- Fixed "Body Link Colour" setting in notification preview
- Fixed HTML links in notifications

= 1.3.0 (2023-08-05) =

- Added multilingual support for the WooCommerce Email template
- Fixed PHP 8.1 warnings
- Updated pelago/emogrifier to v5.0.1
- Updated symfony/css-selector to v4.4.44

= 1.2.0 (2023-05-22) =

- Added support for notification string translation with WPML and Polylang
- Fixed setting sanitization

= 1.1.0 (2023-03-17) =

- Fixed notice visibility in admin
- Requires at least Site Reviews v6.7.0
- Updated addon loading method

= 1.0.0 (2023-02-21) =

- Initial release
