=== WP Database Session Handler ===
Contributors: stefanocanziani
Donate link: https://www.paypal.me/StefanoCanziani
Tags: session, database
Requires at least: 4.0
Tested up to: 5.4.2
Stable tag: 1.0.1
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add handling of user session inside SQL Database. This session manager is useful if you have an environment with more than one frontend server and a load balancer who switch the web traffic dinamically between frontend hosts.

== Description ==

Add handling of user session inside SQL Database. This session manager is useful if you have an environment with more than one frontend server and a load balancer who switch the web traffic dinamically between frontend hosts.
Mysqli extension required.


== Installation ==

1. Upload the plugin to the '/wp-content/plugins/' directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Use $_SESSION in your code.
1. Use the 'Settings->DB Session Handler' to configure the plugin


== Frequently Asked Questions ==

= There are special needs to use session variables? =

No. You can use global variable $_SESSION as you normally do.

= How long do session variables live? =

This depends on your PHP installation's configuration. Please read the [PHP manual](http://php.net/manual/en/session.configuration.php)
for more details on configuration.


== Screenshots ==

1. Settings page

== Changelog ==

= 1.0.1 =
* Fixed compatibility with PHP > 7.1

= 1.0.0 =
* First release.

== Upgrade Notice ==

= 1.0.1 =
Upgrade if you use PHP 7.1 or higher

= 1.0 =
First release.