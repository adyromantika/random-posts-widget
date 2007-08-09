=== Random Posts Widget ===
Contributors: adywarna
Donate link: http://www.romantika.name/v2/
Tags: random, posts, random posts, widget
Requires at least: 2.1
Tested up to: 2.2.2
Stable tag: trunk

This simple plugin is a widget that displays a list of random posts on your widgetized sidebar.

== Description ==

This simple plugin is a widget that displays a list of random posts on your widgetized sidebar.

== Installation ==

1. Upload `randomposts.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use your 'Presentation'/'Sidebar Widgets' settings to drag and configure

== Configuration ==

* Widget title: the title of the widget
* List types: ul for bulleted list, p for paragraph, br for paragraph with line breaks
* Post count: Number of random posts you would like to be displayed
* Category: Only this category will be selected as the source

== Change Log ==

* 07-May-2007: Fixed a bug of displaying widget before and after tag
* 14-Jun-2007: Fixed a bug where pages are also selected. Now it only displays posts.
* 26-Jun-2007: Removed before and after configuration options, now taken from theme settings
* 03-Aug-2007: Overhauled entire engine to optimize queries. This has been tested on a test blog with 50,000 posts on an old machine (Pentium III 600MHz) in maximum 3 seconds. Also removed duplicated results and added category to choose.
* 04-Aug-2007: Fixed a bug where wrong permalink was displayed
* 10-Aug-2007: Fixed a bug where if 1 post count is selected an error occured
