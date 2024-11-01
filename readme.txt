=== WP FLASH TITLES PLUGIN ===
Contributors: Sam Burdge
Donate link: http://samburdge.co.uk/
Tags: title, color, flash, font
Requires at least: 1.5
Tested up to: 2.3
Stable tag: trunk

== Description ==

Allows you to display your post titles in any font, colour and size you want. It will 
also retain your original html styling of your titles and display that to users who 
don't have flash installed. It is fully customisable via the Options tab in wordpress.
It is search engine friendly and easy to implement.

---------------------------------------------------------------------------------------

== Installation ==

1. Upload the wp_flash_titles folder to your wp-content/plugins folder

2. Activate the plugin from the Plugins page


---------------------------------------------------------------------------------------

== Usage ==

1. Go to Options -> WP Flash Titles to choose your font, size and colour scheme
   for your titles.

2. You need to modify your template file slightly. First you need to locate the 
   code that relates to the post title. This will typically be something like:

&lt;h3 class="storytitle" id="post-<?php the_ID(); ?>">
&lt;a href="&lt;?php the_permalink() ?>" rel="bookmark">
&lt;?php the_title(); ?>
&lt;/a>
&lt;/h3>

You then need to add two lines of code. First paste this line 
in directly before the page title code:

&lt;?php if(function_exists('wp_flash_titles')){wp_flash_titles($post->ID,'start')} ?>

Then this line directly after:

&lt;?php if(function_exists('wp_flash_titles')){wp_flash_titles($post->ID,'end')} ?>

3. To embed any font you want you must have Flash CS3. Open the source file 
   'wp_flash_title.fla' which is located in the 'Source' folder of the plugin. In flash
   simply change the font of the text area to any font you wish. Do a 'Save As' and then 
   publish the swf. Once you have created the swf you must upload it to the folder:
   wp-content/plugins/wp_flash_titles/flash
   
   It will now appear in the list of fonts in the Options -> WP Flash Titles page!!