=== Fontself plugin ===
Author: Claude Vedovini
Contributors: cvedovini
Donate link: http://vedovini.net/plugins/?utm_source=wordpress&utm_medium=plugin&utm_campaign=fontself
Tags: Fontself,font,fonts,typeface,handwritten,personalize
Requires at least: 2.7
Tested up to: 2.9.2
Stable tag: 1.0.6


== Description ==

The Fontself service has been discontinued and this plugin is no more maintained.

This plugin lets you to use [Fontself fonts](http://www.fontself.com/) in your Wordpress blog.


== Installation ==

This plugin follows the [standard WordPress installation method](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins):

1. Upload the `fontself` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

Visitors can now post comments using [Fontself fonts](http://www.fontself.com/) and you can write posts using [Fontself fonts](http://www.fontself.com/). 

To use [Fontself fonts](http://www.fontself.com/) in your posts select a bloc of text while editing your 
post (it is recommended to select full paragraphs) and select a font in the Fontself fonts selection box.
A shortcode will then be added around your text. Optionally you can specify the font size by adding a `size` attribute to the shortcode.

To use [Fontself fonts](http://www.fontself.com/) in your theme, for example to have all your posts titles use a specific font, you can use the `fontself` template tag:

`<?php fontself($text, $font, $size); ?>`

* **$text** *(string)* the text to display
* **$font** *(string)* the key of the font to use (the key is the identifier that goes into the `font` attribute of the shortcode)
* **$size** *(integer) (optional)* The font size.

== Screenshots ==

1. A post using Fontself fonts
2. Editing a post 
3. Posting a comment using Fontself


== Changelog ==

= version 1.0.4 =
- removing normalization of text prior to filtering

= version 1.0.3 =
- Fixing syntax issue in readme.txt

= version 1.0.2 =
- Alternate json functions when php < 5.2

= version 1.0.1 =
- Fix in the readme.txt

= version 1.0.0 =
- Initial release
