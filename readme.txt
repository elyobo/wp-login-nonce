=== Login Nonce ===
Contributors: elyobo
Donate link: 
Tags: security, authentication
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a nonce to the login form, making it more difficult to apply a brute force attack to the login process.

== Description ==

WordPress supports a limited variety of "nonce", but doesn't use them on the login screen. This plugin adds a nonce here to make automated brute force attempts slower and marginally more difficult for an attacker.

A true nonce would be more secure, but wordpress nonces can be reused, so an attacker can still make a request, get the nonce, then make multiple attempts with that nonce for as long as it remains valid. By default, WP nonces have a very long life (12 - 24 hrs), but this plugin reduces the nonce lifetime to 30 seconds on the login page to reduce this attack window.

A more secure implementation would use one of nonces and the WP transients API to store them, removing the need for a timeout/refresh system and making the system more secure as well.

== Installation ==

1. Upload `wp-login-nonce.php` to the `/wp-content/plugins/` directory or the `/wp-content/mu-plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress if uploaded to `/wp-content/plugins/`.

== Frequently asked questions ==



== Screenshots ==



== Changelog ==



== Upgrade notice ==



