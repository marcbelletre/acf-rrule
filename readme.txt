=== ACF RRule Field ===
Contributors: marcbelletre, pedromendonca
Tags: acf, rrule, recurrence, date, calendar
Requires at least: 4.7
Tested up to: 6.1
Requires PHP: 5.6
Stable tag: 1.2.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create recurring rules within a single ACF field and retrieve all the dates.

== Description ==

This plugin allows you to create recurring rules within a single ACF field.

Just add a RRule field in an ACF field group and use the interface to create a period like you would do in any agenda. The field will automatically generate the corresponding RRule string and save it in database. You can then retrieve all the dates for the period with a single call to the `get_field()` function.

== Installation ==

This plugin requires ACF or ACF Pro to work.

1. Upload `acf-rrule` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create a RRule field in an ACF field group
4. Use `get_field('field_name')` to get an array representation of your recurring rule

== Screenshots ==

1. RRule field example

== Changelog ==

= 1.2.5 =
* Bug fix

= 1.2.4 =
* Fix undefined variable

= 1.2.3 =
* Fix checkboxes selection not showing up
* Fix end date not being set when using the count option

= 1.2.2 =
* Improve field validation
* Fix spelling
* Update dependencies

= 1.2.1 =
* Fix a bug when the timezone is not set
* Update dependencies

= 1.2 =
* Enable multi selection for weekdays options

= 1.1.1 =
* Fix plugin textdomain

= 1.1 =
* Add field validation

= 1.0.4 =
* Add a "No end date" option
* Fix a bug when the timezone is not set

= 1.0.3 =
* Update french translation

= 1.0.2 =
* Fix a PHP error when the start date field is not set

= 1.0.1 =
* Fix a PHP warning when using a number of occurrences.

= 1.0 =
* First stable release
