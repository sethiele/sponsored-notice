=== Sponsored notice ===
Contributors: sebat
Tags: sponsored, Adwords, notice, german, law
Requires at least: 4.0
Tested up to: 4.4
Stable tag: master
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a "Sponsored" notice to your posts simply as selecting a category.

== Description ==
If you write sponsored posts you have to add a clear notice to the post to show that this is not a normal post. This is the law (in Germany).
With this plugin you can create different types of notifications. Like "Sponsored Post",  "Advertisement" or what ever you like. At the moment you create your post you can easily choose what kind of commercial this post is and select one of your pre set notices.

After publishing the notice will ad automatically where you want to place it.

You have the option to add a short notice automatically in the title of the post (before or after the actual title). Ot you can place the notice by adding a theme function in your theme. On the same way you can add a longer description automatically before or after the content or you can place this notice with a theme function in template.

If you have Feedback, please drop me a tweet at [@sebat](https://twitter.com/sebat) or create an [issue at github](https://github.com/sethiele/sponsored-notice)

This Plugin is available in:
* English
* German

*Note: This plugin is what it is. I don\'t warranty any legal security*

== Installation ==
1. Upload the `add-sponsored-notice` folder to the `/wp-content/plugins/` directory
1. Activate the *Add sponsored notice* plugin through the 'Plugins' menu in WordPress
1. Configure the plugin by going to the `Sponsored` menu that appears in your admin menu

== Frequently Asked Questions ==
= How can I place the short notice in my Theme? =

You can add the theme function `asp_get_notice($before, $after)` in your theme.
You can add Text of HTML before (`$before`) or after (`$after`) the text. Default is a open and closed `p`-Tag.
If you want a `div` with the class `notice` the code can look like this:

`<?php if(function_exists('asp_get_notice')){
  echo asp_get_notice('<div class="notice">', '</div>');
}?>`

= Can I style the notice in the title? =
Yes. The title notice came with a css class `asn-title-notice`. You can style it like:
`.asn-title-notice{font-size: 0.8em; color: #ccc;}`

= Can I style the description in the content? =
Yes. The title notice came with a css class `asn-description-notice`. You can style it like:
`.asn-description-notice{font-style: italic;}`

== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png


== Changelog ==
= 0.9.0 =
First released Version
