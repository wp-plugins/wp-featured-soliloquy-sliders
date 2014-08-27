=== WP Featured Soliloquy Sliders ===
Contributors: topher1kenobe
Tags: posts, pages, soliloquy, sliders, featured
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides a metabox on posts and pages listing existing Soliloquy Sliders.

== Description ==

This plugin provides a metabox on posts and pages listing existing Soliloquy Sliders.  The end user is allowed to choose one and make it associated with the post or page via meta data.

Practically speaking, Featured Sliders work exactly like Featured Images.  The Post or Page and Featured Sliders are merely attached, and you must use a template tag or WordPress functions to render the Slider.

Please see <a href="http://wordpress.org/plugins/wp-featured-soliloquy-sliders/other_notes/">Other Notes</a> for examples.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the `wp-featured-soliloquy-sliders` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create new or Edit a Post.

== Usage ==

Page or Post meta has a key called `_t1k_featured_slider`.	A very simple way to render the slider is like this:

`<?php
	$meta = get_post_custom();
	if ( isset( $meta['_t1k_featured_slider'][0] ) && is_numeric( $meta['_t1k_featured_slider'][0] ) && function_exists( 'soliloquy' ) ) {
		soliloquy( absint( $meta['_t1k_featured_slider'][0] ) );
	}
?>`

== Frequently Asked Questions ==

= Why don't you have more questions here? =

I haven't been asked any yet.  :)

== Screenshots ==

1. The Featured Sliders meta box when you *do not* have any Sliders created in Soliloquy.

2. The Featured Sliders meta box when you *do* have Sliders created in Soliloquy.

== Changelog ==

= 1.1 =
Check for WP 4.0

= 1.0 =
* Initial release.
