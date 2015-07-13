=== tinySocial ===
Contributors: ideag
Donate link: http://arunas.co/#coffee
Tags: social sharing, share links, share, facebook, twitter, google plus, social networks
Requires at least: 3.0.1
Tested up to: 4.2.2
Stable tag: 1.1.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy way to insert social sharing links into your WordPress posts/pages via shortcodes. 

== Description ==

A tiny and simple hackable plugin to insert lightweight social sharing links into your post/page content via shortcodes. You can use `[tinysocial_all]` shortcode to add links to all the networks at once, or individual codes, like `[facebook]`, `[twitter]`, etc. to insert links to individual networks.

Currently supported networks are:
* Facebook
* Twitter
* Google+
* Pinterest
* Linked In
* Buffer
* Digg
* StumbleUpon
* Tumblr
* Reddit
* Delicious

An enormous amount of coffee was consumed while developing these plugins, so if you like what you get, please consider treating me to a [cup](http://arunas.co/#coffee). Or two. Or ten.

Also, try out my other plugins:

* [Gust](http://tiny.lt/gust) - a Ghost-like admin panel for WordPress, featuring Markdown based split-view editor.
* [tinyCoffee](http://tiny.lt/tinycoffee) - a PayPal donations button with a twist. Ask people to treat you to a coffee/beer/etc. 
* [tinyTOC](http://tiny.lt/tinytoc) - automatic Table of Contents, based on H1-H6 headings in post content.
* [tinyIP](http://tiny.lt/tinyip) - *Premium* - stop WordPress users from sharing login information, force users to be logged in only from one device at a time.

== Installation ==

1. Upload `tinysocial` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Setup your information in `Settings > tinySocial`
1. Add to your website via shortcodes.

== Frequently Asked Questions ==

= Can I add another network? =

Sure, you can use `tinysocial_networks` filter to add more networks.

= What are the shortcodes that I can use? =

* `[tinysocial_all]` displays a list of all active networks. This shortcode accepts two attributes - `separator` and `last`, for example: `[tinysocial_all separator="|" last=" or "]`. Please note spaces around `or`;
* `[facebook]`, `[twitter]` etc. - for every social network. You can change link text by adding content to the shortcode, for example `[facebook]FB[/facebook]`.

== Screenshots ==

1. Plugin settings
2. Shortcodes inside editor screen
3. Plugin output in TwentyFifteen

== Changelog ==

= 1.1.0 =
Add support for Custom Post types

= 1.0.0 =
Initial release to WordPress.org directory

== Upgrade Notice ==

No upgrade notices
