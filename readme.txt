=== Plausible Connector ===

Contributors: alyxx
Plugin Name: Plausible Connector
Plugin URI: https://eq3.net/plausible-wp
Tags: analytics, plausible
Author URI: https://eq3.net
Author: alyx
Requires PHP: 5.6
Requires at least: 4.1
Tested up to: 5.4
Stable tag: 1.2
Version: 1.2
License: GPL v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Connect your WordPress instance to Plausible Analytics, a simple and privacy-friendly analytics platform.

== Description ==

Connect your WordPress instance to [Plausible Analytics](https://plausible.io), a simple and privacy-friendly analytics platform.

== Installation ==

After installing this plugin, make sure to visit the Settings -> Plausible page
to customize your settings.

At minimum, you will net to set the _Plausible Domain ID_ value. If you have
configured any other customization with your Plausible script (e.g., using your
own domain or running a self-hosted instance), you may need to configure the
other values. If not, you should be able to leave the _Plausible Instance URL_
and _Plausible Tracker_ values at their default.

**Pageview Events**

Pageview events are sent automatically. They will not be sent if a user is logged in and you have
_Exclude Logged-In Users_ enabled.

**Custom Events**

Support for custom events is enabled by default with this plugin. You will need to configure
[custom event goals](https://docs.plausible.io/custom-event-goals/) before these events will show
up on your Plausible dashboard. You will also currently need to configure any events manually.

== Changelog ==

**v1.2**

- Support for [custom event goals](https://docs.plausible.io/custom-event-goals/) added.
- Additional code cleanups.

**v1.1**

- Code and packaging cleanups
- Updates to Settings page functionality and descriptions

**v1.0**

Initial release. In need of polishing but base functionality (connecting a
Plausible script to WordPress) works correctly.
