=== Site Reviews: Review Forms ===
Contributors: pryley, geminilabs
Donate link: https://ko-fi.com/pryley
Tags: Site Reviews
Tested up to: 6.7
Stable tag: 2.2.1

Create review forms with custom fields and review templates.

== Description ==

Create review forms with custom fields and review templates.

= Minimum Plugin Requirements =

- WordPress 6.1
- PHP 7.4

== Installation ==

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 2.2.1 (2024-10-24) =

- Added responsive width options to custom fields
- Added the [site_reviews_field] shortcode which can be used to display a custom rating or range summary.
- Added the Field Summary block, Elementor widget, and Fusion widget which can be used to display a custom rating or range summary.
- Added the Range custom field
- Fixed field conditions
- Fixed field controls when field type is changed and the hidden option is enabled
- Fixed field focus when pressing tab from a multiselect field
- Fixed keyboard accessibility of toggle options
- Fixed searching by Post ID on the All Forms admin page
- Fixed tooltip visibility after the field type has changed
- Fixed user names when display name is not set

= 2.1.0 (2024-09-24) =

- Fixed addon updater
- Fixed compatibility with Site Reviews v7.1
- Fixed compatibility with the Review Authors addon
- Fixed Custom Textarea "display value as" option

= 2.0.5 (2024-07-30) =

- Fixed the "required" validation in custom fields

= 2.0.4 (2024-06-08) =

- Fixed hidden option of Assigned Posts/Terms/Users fields

= 2.0.3 (2024-05-29) =

- Fixed Field Label settings for assigned posts/terms/users dropdown fields

= 2.0.2 (2024-05-06) =

- Fixed Assigned Post/User/Category fields

= 2.0.1 (2024-05-04) =

- Fixed Export

= 2.0.0 (2024-05-03) =

- 🚨 Requires at least Site Reviews v7.0.0
- Added Avada Fusion Builder support
- Added Conditional Logic field options
- Added Import/Export to forms
- Added Link Text option to Email/Tel/URL fields
- Added min/max validation field options
- Fixed: hide options are now removed in the Review Form block after selecting a custom form

= 1.15.1 (2024-03-26) =

- ‼️ The minimum plugin requirements are changing. The next update will require PHP v7.4, WordPress v6.1, and Site Reviews v7.0.
- Fixed compatibility with WordPress 6.5

= 1.15.1 (2023-08-05) =

- Fixed admin template loading
- Fixed shortcode examples in the documentation
- Fixed textarea autosize

= 1.15.0 (2023-05-22) =

- Added Field Description option
- Fixed compatibility with Site Reviews v6.9.0
- Fixed Date fields
- Fixed setting sanitization
- Updated Review Forms UI in preparation for Conditional Logic field options

= 1.14.3 (2023-04-22) =

- Fixed field expanding
- Fixed field sorting

= 1.14.1 (2023-04-20) =

- Fixed broken wp-admin menu toggle on Review Forms pages
- Fixed PHP 8.0 notice
- Fixed plain-text format of shortcode examples and template tags when copied
- Fixed restricted usage of _custom_* and custom_* field names

= 1.14.0 (2023-03-17) =

- Added compatibility for the Review Authors addon
- Fixed notice visibility in admin
- Requires at least Site Reviews v6.7.0
- Updated addon loading method

= 1.13.0 (2023-03-10) =

- Fixed sanitization
- Fixed shortcode documentation
- Requires at least Site Reviews v6.6.0

= 1.12.0 (2023-02-21) =

- Added support for [site_review] shortcode
- Added support for Single Review block, widget, and Elementor widget
- Fixed Category field options
- Fixed Custom Textarea field when paragraphs are displayed as a list
- Fixed Elementor integration
- Fixed Email field tags to use antispambot HTML encoding
- Fixed missing strings
- Fixed output of fields with multi-values
- Fixed REST API integration

= 1.11.0 (2022-12-27) =

- Fixed Category dropdown when optgroups are enabled and options contain a single child category
- Fixed notice which is displayed when Site Reviews is not activated
- Fixed rating label position in reviews (change it back using the CSS code snippet provided on the "Help & Support > Addons" page)

= 1.10.2 (2022-09-15) =

- Fixed capabilities on plugin activation
- Fixed margin spacing in custom form templates

= 1.10.0 (2022-09-14) =

- 🚨 Requires at least PHP v7.2
- 🚨 Requires at least Site Reviews v6.0.0
- 🚨 Requires at least WordPress v5.8
- Added custom capabilities for the site-review-form Post Type
- Added support for the "form" parameter in the Site Reviews REST API
- Fixed choices.js field validation
- Fixed server-side field validation

= 1.9.4 (2022-07-18) =

- Added ability to modify choices.js options (see add-on documentation)
- Fixed links to reviews on the "Review Forms" page

= 1.9.3 (2022-06-21) =

- Fixed Form selector when creating a new review in the admin

= 1.9.2 (2022-05-26) =

- Fixed compatibility with Oxygen Builder

= 1.9.1 (2022-04-09) =

- Fixed compatibility with Site Reviews v5.23.0
- Fixed custom field validation

= 1.9.0 (2022-03-25) =

- Added default fields to new forms
- Added help metabox in forms
- Added setting to use choices.js for Select boxes
- Fixed compatibility with Site Reviews v5.22.0
- Fixed dropdown placeholders in the Review Details metabox (when editing a review)
- Fixed telephone field URL values

= 1.8.1 (2022-02-11) =

- Fixed handling of custom fields, it now correctly uses the "required" setting of the custom field instead of the global "required" settings
- Fixed number fields to allow empty values
- Fixed WordPress version requirement
- Requires Site Reviews v5.20.4

= 1.8.0 (2022-01-26) =

- Added a "Display Value As" setting to Textarea fields, you can now display the value as a bulleted/numbered list (item per paragraph), excerpt (expandable paragraph), or as multiple paragraphs.
- Added a "Link (open in new tab/window)" option to the "Display Value As" setting in URL fields
- Added a `rows="5"` attribute/value to custom textarea fields
- Added field-specific sanitization of values (i.e. textarea fields are now saved as muti-line text)
- Updated the WordPress required version to 5.8
- Removed the 1.5em indents in numbered/bulleted lists in favour of inheriting the current WordPress theme style.
- Fixed the forms filter on the All Reviews admin page

= 1.7.0 (2021-12-30) =

- Added Date field
- Added "Display Value As" option to Checkbox, Date, Email, Telephone, and URL fields

= 1.6.2 (2021-11-15) =

- Fixed add-on updater
- Fixed deactivation notice on WordPress settings pages

= 1.6.0 (2021-11-10) =

- Fixed compatibility with Site Reviews v5.17.0
- Requires Site Reviews >= 5.17.0

= 1.5.0 (2021-09-23) =

- Changed minimum required WordPress version to 5.6

= 1.4.0 (2021-09-23) =

- Fixed Shortcode option documentation
- Fixed tooltip delay
- Requires Site Reviews v5.14

= 1.3.3 (2021-07-15) =

- Added a filter hook to modify the argument array used in the `get_post` function to get the assigned_posts dropdown results ("site-reviews-forms/builder/assigned_posts/args")
- Fixed the field template tag label used in the Images field

= 1.3.2 (2021-07-06) =

- Added help text to the Review Template metabox

= 1.3.1 (2021-06-02) =

- Fixed form validation

= 1.3.0 (2021-06-01) =

- Added support for multiple assignment review fields
- Added support for optgroup in category dropdown
- Added support for the Elementor widgets
- Requires Site Reviews v5.11

= 1.1.3 (2021-02-12) =

- Fixed support for HTML `<a>`, `<strong>`, and `<em>` tags in field labels

= 1.1.2 (2021-03-20) =

- Fixed compatibility with the Thrive Architect plugin
- Replace tooltips with Site Reviews tooltips

= 1.1.1 (2021-03-03) =

- Fixed the custom textarea field

= 1.1.0 (2021-02-16) =

- Added Template Tag Labels
- Fixed display of paragraphs in custom textarea fields
- Fixed display of URLs in custom URL fields

= 1.0.3 (2021-01-30) =

- Fixed an issue which broke custom review templates in PHP 7.*

= 1.0.2 (2021-01-29) =

- Fixed an internal bug
- Fixed custom review template tags

= 1.0.1 (2021-01-25) =

- Fixed an issue which could affect auto-updates

= 1.0.0 (2020-12-13) =

- Initial release

- First release
