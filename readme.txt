=== Live Flickr Comment Importer ===
Contributors: tariquesani
Tags: comments, flickr, import
Requires at least: 2.8
Tested up to: 3.9.1
Stable tag: 1.9

This plugin uses Flickr API to import comments from you Flickr photostream
photos to a post of your choice in your Wordpress Blog.

== Description ==
This plugin uses Flickr API to import comments from you Flickr photostream
photos to a post of your choice in your Wordpress Blog. It additionally
imports the Flickr Avatar Icons and displays them as appropriate.

Download the latest version from here http://downloads.wordpress.org/plugin/live-flickr-comment-importer.zip

The Live Flickr Comment Importer Plugin features

* Uses the Flickr API via the excellent phpFlickr class
* Can automatically detect Flickr photos embedded in the post.
* Can also use a custom field in the post to import comments thus your Photos and posts need not have the same title
* Comments on multiple photos can be imported in a single post
* Also imports the flickr user icons / avatars
* The comments are imported whenever anyone sees a post where comments are to be imported
* Allows import of even the old comments
* Can cache the results of flickr API
* Show thumbnails in comments
* Allow disallow HTML in comments
* Exclude certain pictures using custom field
* Favorites can also be imported as comments optionally

== Installation ==

   1.  Upload the folder ‘live-flickr-comment-importer’ into your to the
‘/wp-content/plugins/’ folder
   1.  Activate the plugin through the ‘Plugins’ menu in WordPress
   1.  To configure plugin options start by clicking on ‘Configuration Page’
link near plugin name or ‘Settings’ -> ‘Live Flickr Comment Importer’
navigation link.
   1.  You will have to enter and your flickr API key for this plugin to work.
Flickr API key can be got from http://www.flickr.com/services/apps/create/apply/
   1.  For caching of Flickr API results and thus in turn a better performance
ensure that your plugin directory must be writable by webserver.
   1.  Follow the instructions given on the configuration page on how to
import comments into a post

== Changelog ==

= 1.9 =
* Added https to the phpflickr class, the plugin should work now.


= 1.8 =
* Added the latest phpflickr class
* Recoded the options page according to Settings API, with some small tweaks to the UI
* Added option for importing favorites as comments, This option intelligently tries to merge favs and comments by the same person into a single comment
* Added option for Comment Prefix


= 1.7 =
* Added setting to allow or disallow HTML in imported comments
* Added setting to allow or disallow thumbnails in imported comments. Can be overridden by custom field
* Added new custom field flickr_exclude_id to exclude comments from certain pictures
All above added on suggestions from Tom Allen of http://tom.ride-earth.org.uk/

= 1.6 =
* Work around for some themes not passing the comment object to get_avatar, should work in all themes irrespective

= 1.5 =
* Bug fix for comments not in correct order
* Display of comment thumbnail configurable by post custom field 'flickr_show_thumnail'. Set to 1 to show thumbnails. Off by default
* Added custom field to prevent comments from being imported 'flickr_dont_import'. Set to 1 to prevent comment importing
* Workaround code for comments getting duplicated

= 1.4 =
* A couple of bug fixes
* Multiple comma separated ids can be put in custom field
* Small thumnail of the picture added to comments

= 1.3.1 =
* Fixed a bug with custom field not working.

= 1.3 =
* Fixed a bug in API key saving routine.
* Added code to allow importing of comments from multiple photos in a single post
* Imported comments show a small thumbnail of the photo for which the comment is

= 1.2 =
* Changed the name of phpflickr class to t_phpflickr so that it plays well with other plugins using the phpflickr class

= 1.1 =
* Added code to try and extract Flickr Id from the URL of photos embedded

= 1.0 =
* First release

