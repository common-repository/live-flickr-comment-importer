<?php
/*
Plugin Name: Live Flickr comment Importer
Plugin URI: http://sanisoft.com/blog/wordpress-plugin-live-flickr-comment-importer/
Description: Add Flickr comments to your blog posts which have embeded flickr photos.
Author: Tarique Sani
Version: 1.8
Author URI: http://tariquesani.net/blog/
*/

/*  Copyright 2009  Tarique Sani  (email : tarique@sanisoft.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

# To debug
# Uncomment the next two lines to see error messages if things fail...
# error_reporting(E_ALL);
# ini_set("display_errors", 1);



function lfci_load_scripts() {

		$lfciJs = WP_PLUGIN_URL . '/live-flickr-comment-importer/js/lfci.js';

		wp_enqueue_script( 'lfciJs', $lfciJs, array('jquery'));

}

add_action('wp_print_scripts','lfci_load_scripts');

add_action('admin_menu', 'lfci_add_config_page');

function lfci_add_config_page() {
		add_options_page('Live Flickr Comments', 'Live Flickr Comments', 'manage_options', __FILE__, 'lfci_config_page');
}

// function to display the plugin options page
function lfci_config_page() {
?>
<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Live Flickr Comments Importer - By <a href="http://www.sanisoft.com" target="_blank">SANIsoft</a></h2>
		<form action="options.php" method="post">
		<?php settings_fields('lfci_options'); ?>
		<?php do_settings_sections('lfci'); ?>

		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
		</form>
		<?php if ( !is_writeable(ABSPATH . 'wp-content/plugins/live-flickr-comment-importer/phpflickr/cache/') ) { ?>
			<p style="padding: .5em; background-color: #f33; color: #fff; font-weight: bold;"><?php _e('WARNING: Your cache path '.ABSPATH . 'wp-content/plugins/live-flickr-comment-importer/phpflickr/cache/'.' is not writeable by the webserver. Please change permissions to make it writeable'); ?></p>
		<?php } ?>
			<p><?php _e( "<h3>Usage</h3><ul><li>The Flickr image(s) must be embedded in your post <strong>OR</strong></li> <li> Your posts must have a custom field called 'flickr_photo_id' with the value as ID of the photo on Flickr whose comments you want to import in the post. Use comma to separate multiple values.</li><li>Flickr Photo ID is usually the last number in the URL of the photo page eg: In <a href='http://www.flickr.com/photos/tariquesani/4175454833/'>http://www.flickr.com/photos/tariquesani/4175454833/</a> - 4175454833 is the photo ID</li><li>See how to <a href='http://www.boosten.org/using-custom-fields-in-wordpress-posts/' >include a custom field in your post</a>.</li>
<li>Use custom field called 'flickr_dont_import' to prevent comment import</li>
<li>Use custom field called 'flickr_show_thumbnail' to show a small thumnail of the photo on which comment is made - useful in cases of multiple photos</li>
<li>Use custom field called 'flickr_exclude_id' to exclude comments from certain photos</li>
<li>Comments will be imported whenever someone visits the post</li></ul>" );?></p>

<p><?php _e( "Don't forget to visit my blog at <a href='http://tariquesani.net/blog/'>http://tariquesani.net/blog/</a>." ); ?></p>
</div>
<?php
}

add_action('admin_init', 'lfci_admin_init');

function lfci_admin_init(){
	register_setting( 'lfci_options', 'lfci_options', 'lfci_options_validate' );
	add_settings_section('lfci_main', 'Settings', 'lfci_section_text_fn', 'lfci');
	add_settings_field('lfci_API_key', 'Flickr API Key', 'lfci_API_key_fn', 'lfci', 'lfci_main');
	add_settings_field('lfci_allow_html', 'Allow HTML in comments', 'lfci_allow_html_fn', 'lfci', 'lfci_main');
	add_settings_field('lfci_show_thumbnail', 'Show thumbnails in comments', 'lfci_show_thumbnail_fn', 'lfci', 'lfci_main');
	add_settings_field('lfci_get_fav', 'Get favourites as comments', 'lfci_get_fav_fn', 'lfci', 'lfci_main');
	add_settings_field('lfci_prefix', 'Comment prefix', 'lfci_prefix_fn', 'lfci', 'lfci_main');
}


function lfci_section_text_fn () {
	echo 'Enter the Flickr API key which you can get from <a href="http://www.flickr.com/services/apps/create/apply/"" >http://www.flickr.com/services/apps/create/apply/</a> in the field below. <br>If you had created a key earlier you can retrieve it from <a href=http://www.flickr.com/services/apps/by/ >http://www.flickr.com/services/apps/by/</a>';
}

function lfci_API_key_fn() {
	$options = get_option('lfci_options');
	echo "<input id='lfci_API_key' name='lfci_options[flickr_API_key]' size='40' type='text' value='{$options['flickr_API_key']}' />";
}

function lfci_allow_html_fn() {
	$options = get_option('lfci_options');
?>
	<select name="lfci_options[allow_html]" id="lfci_allow_html">
		    <option value="0">No</option>
		    <option value="1" <?php print(empty($options['allow_html'])? "" : "selected='selected'") ?> >Yes</option>
	</select>

<?php
}

function lfci_show_thumbnail_fn() {
	$options = get_option('lfci_options');
?>
	<select name="lfci_options[show_thumbnail]" id="lfci_show_thumbnail">
		    <option value="0">No</option>
		    <option value="1" <?php print(empty($options['show_thumbnail'])? "" : "selected='selected'") ?> >Yes</option>
	</select>

<?php
}

function lfci_get_fav_fn() {
	$options = get_option('lfci_options');
?>
	<select name="lfci_options[get_fav]" id="lfci_get_fav">
		    <option value="0">No</option>
		    <option value="1" <?php print(empty($options['get_fav'])? "" : "selected='selected'") ?> >Yes</option>
	</select>

<?php
}


function lfci_prefix_fn() {
	$options = get_option('lfci_options');
	echo "<input id='lfci_prefix' name='lfci_options[prefix]' size='40' type='text' value='{$options['prefix']}' />";
}

require_once ('phpflickr/phpFlickr.php');

function lfci_options_validate($input) {
		//Verify API key
		$f = new t_phpFlickr( $input[ 'flickr_API_key' ] );

		if($f->test_echo() === false) {
			add_settings_error('lfci_API_key', 'lfci_API_key_error', "Oops! your Flickr API key seems to be invalid", 'error');
			$input['flickr_API_key'] = "";
		}

		return $input;
}

//Main function hooked to the filter the_content
function live_flickr_comment_importer($content) {
	global $wpdb, $post;

	$options = get_option('lfci_options');

	// Return if it is not a single post
	if(!is_single()) {
		return $content;
	}

	// Return if there is no Flickr API key
	if( !$options['flickr_API_key'] ){
		return $content;
	}

	$flickr_photo_id_array = extract_flickr_id();

	//Return if there is no flickr photos
	if(!$flickr_photo_id_array){
		return $content;
	}

	$flickr_show_thumbnail = (int)get_post_meta($post->ID, 'flickr_show_thumbnail', true);

	$f = new t_phpFlickr($options['flickr_API_key']);

	$f->enableCache('fs', ABSPATH . 'wp-content/plugins/live-flickr-comment-importer/phpflickr/cache/');

	// Loop over the photos found
	foreach($flickr_photo_id_array as $flickr_photo_id){
		$comments   = $f->photos_comments_getList($flickr_photo_id);

		//If the favs are to be got
		if(!empty($options['get_fav'])) {
			$favorites	= $f->photos_getFavorites($flickr_photo_id, 1, 50);
		}

		// Get the square thumbnail
		if(!empty($flickr_show_thumbnail)){
			$photo_sizes = $f->photos_getSizes($flickr_photo_id);
			$square_thumb = $photo_sizes[0]['source'];

			$photo_info = $f->photos_getInfo($flickr_photo_id);

			$title = $photo_info['title'];
		}
		$i = 0;

		//OK if there are comments then loop over them
		if(isset($comments['comments']['comment'])) {
			foreach( $comments['comments']['comment'] as $comment ) {
				// Is the comment already in the database?
				$test_dupes = $wpdb->get_results( "SELECT comment_ID FROM {$wpdb->comments} WHERE comment_author_url='{$comment['permalink']}'", ARRAY_N);

				$has_dupes = count($test_dupes);
				// No! Add the damn thing!!
				if(empty($has_dupes)) {
					// Get Author info so that you can build an Avatar URL later
					$author = $f->people_getInfo($comment['author']);

					if ($author['iconserver'] > 0){
						// This is cheating - there should be a different field for it
						$commentdata['comment_author_IP'] 	= $author['iconserver'] ."++". $author['id'];
					} else {
						$commentdata['comment_author_IP'] 	= " ";
					}

					$commentdata['comment_author']			= $options['prefix']." ".esc_html($comment['authorname']);
					$commentdata['comment_author_email']	= 'nobody@flickr.com';
					$commentdata['comment_author_url'] 		= esc_html($comment['permalink']);

					if(!empty($flickr_show_thumbnail)){
						$commentdata['comment_content']		= "<img class='comment-thumb' src='".$square_thumb."' alt='".$title."' title='".$title."' width=30 >";
					} else {
						$commentdata['comment_content']		= "";
					}

					$lfci_allow_html = (int)$options['allow_html'];

					switch($lfci_allow_html) {
						case 0:
							$comment_content = addslashes( strip_tags($comment['_content']) );
							break;

						case 1:
							$comment_content = addslashes( $comment['_content'] );
							break;
					}

					if(isset($favorites['person'])) {
						foreach( $favorites['person'] as $person ) {
							if($person['nsid'] == $author['id']) {
							  $comment_content .= "<p>They also added this photo to their favourites <img src=".WP_PLUGIN_URL."/live-flickr-comment-importer/img/icon_fav.gif ></p>";

							}
						}
					}

					$commentdata['comment_content']			.= $comment_content;

					$commentdata['comment_type']			= 'comment';
					$commentdata['comment_date']			= date("Y-m-d H:i:s", $comment['datecreate'] ) ;
					$commentdata['comment_date_gmt'] 		= date("Y-m-d H:i:s", $comment['datecreate'] );
					$commentdata['comment_post_ID'] 		= $post->ID;
					$commentdata['user_ID']					= 0;
					$commentdata['comment_agent']     		= "Live Flickr Add Comment Agent";
					$commentdata['comment_approved'] 		= 1;

					$comment_id = wp_insert_comment($commentdata);
					if(isset($favorites['person'])) {
						add_comment_meta($comment_id, 'flickr_photo_id', $flickr_photo_id);
						add_comment_meta($comment_id, 'nsid', $author['id']);
					}

				} else if($has_dupes > 1){
						for ($i = 1; $i < $has_dupes; $i++) {
    						$wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_id = '{$test_dupes[$i][0]}'");
						}
				}
			}
		}

		//Process the favorites here
		//There are favorites, loop over them too
		if(isset($favorites['person'])){
			foreach( $favorites['person'] as $person ) {
				// Is the favorite already in the database?
				$test_dupes = $wpdb->get_results( "SELECT `comment_id` FROM {$wpdb->commentmeta}
														  WHERE
														  	`meta_key` = 'flickr_photo_id'
														  	AND
														  	`meta_value` = '{$flickr_photo_id}'
															AND
															`comment_id`
															IN (
															SELECT comment_id FROM {$wpdb->commentmeta} WHERE `meta_key` = 'nsid' AND `meta_value` = '{$person['nsid']}'
															)
													", ARRAY_N);
				$has_dupes = count($test_dupes);
				if(empty($has_dupes)) {
					// Get Author info so that you can build an Avatar URL later
					$author = $f->people_getInfo($person['nsid']);

					if ((int)$author['iconserver'] > 0){
						// This is cheating - there should be a different field for it
						$commentdata['comment_author_IP'] 	= $author['iconserver'] ."++". $author['id'];
					} else {
					    $commentdata['comment_author_IP'] 	= " ";
					}

					$commentdata['comment_author']			= $options['prefix']." ".esc_html($person['username']);
					$commentdata['comment_author_email']	= 'nobody@flickr.com';
					$commentdata['comment_author_url'] 		= '';

					if(!empty($flickr_show_thumbnail)){
						$commentdata['comment_content']		= "<img class='comment-thumb' src='".$square_thumb."' alt='".$title."' title='".$title."' width=30 >";
					} else {
						$commentdata['comment_content']		= "";
					}

					$commentdata['comment_content']			.= "Added this photo to their favorites <img src=".WP_PLUGIN_URL."/live-flickr-comment-importer/img/icon_fav.gif >";

					$commentdata['comment_type']			= 'comment';
					$commentdata['comment_date']			= date("Y-m-d H:i:s", $person['favedate'] ) ;
					$commentdata['comment_date_gmt'] 		= date("Y-m-d H:i:s", $person['favedate'] );
					$commentdata['comment_post_ID'] 		= $post->ID;
					$commentdata['user_ID']					= 0;
					$commentdata['comment_agent']     		= "Live Flickr Add Comment Agent";
					$commentdata['comment_approved'] 		= 1;

					$comment_id = wp_insert_comment($commentdata);
					add_comment_meta($comment_id, 'flickr_photo_id', $flickr_photo_id);
					add_comment_meta($comment_id, 'nsid', $person['nsid']);
				}
			}

		}
	}
	return $content;
}

// Helper function to extract the flickr_photo_id from the content
// Will return an array of id or false if none found
function extract_flickr_id(){
	global $wpdb, $post;

	$flickr_dont_import    = get_post_meta($post->ID, 'flickr_dont_import', true);

	// Return if don't import meta field is set
	if($flickr_dont_import) {
		return false;
	}

	$flickr_photo_id_array	 = explode(",", get_post_meta($post->ID, 'flickr_photo_id', true));
	$flickr_exclude_id_array = explode(",", get_post_meta($post->ID, 'flickr_exclude_id', true));

	//The post metadata does not have a flickr_photo_id - Try scanning the post for flickr image
	if(empty($flickr_photo_id_array[0])){
		$output = preg_match_all('/<img.+src=[\'"].*flickr.com\/.*\/([^\'"]+)_.*\.jpg[\'"].*>/i', $post->post_content, $matches);
		$flickr_photo_id_array = $matches [1];
	}

	// Lets exclude the ids to be excluded
	if(!empty($flickr_photo_id_array[0]) && !empty($flickr_exclude_id_array[0])){

		$flickr_photo_id_array = array_diff($flickr_photo_id_array, $flickr_exclude_id_array);

	}

	// return if the post has no Flickr photo ID
	if(empty($flickr_photo_id_array[0])) {
		return false;
	}

	return $flickr_photo_id_array;
}

// If the comment is from Flickr build the avatar from Flickr data
// Second param is not reliable when wanting to get the comment object so
// calling it $blah
function get_flickr_avatar($avatar, $blah, $size) {
	global $comment;
		if(is_object($comment) && $comment->comment_author_email == 'nobody@flickr.com'){
			$tmp = explode("++",$comment->comment_author_IP);
			if($tmp[0]===" ") {
				$avatar = "<img alt='' src=".WP_PLUGIN_URL."/live-flickr-comment-importer/img/buddyicon.jpg class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
			} else {
				$avatar = "<img alt='' src='http://static.flickr.com/{$tmp[0]}/buddyicons/{$tmp[1]}.jpg' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
			}
		}
		return $avatar;
}

// Add custom field for showing or not showing thumbnail in post based
// on the global setting if there isn't one already
function lfci_save_post($post_id){
	global $wpdb, $post;

	$flickr_show_thumbnail = get_post_meta($post->ID, 'flickr_show_thumbnail', true);

	if($flickr_show_thumbnail !== "") {
		return;
	}

	$flickr_photo_id_array = extract_flickr_id();
	if(!$flickr_photo_id_array){
		return;
	}else{
		$options = get_option('lfci_options');
		$lfci_show_thumbnail = (int)$options['show_thumbnail'];
		update_post_meta($post->ID, 'flickr_show_thumbnail', $lfci_show_thumbnail);
	}

}

add_filter( 'plugin_action_links', 'lfci_plugin_action_links', 10, 2 );
// Display a Settings link on the main Plugins page
function lfci_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$lfci_links = '<a href="'.get_admin_url().'options-general.php?page=live-flickr-comment-importer/live-flickr-comment-importer.php">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $lfci_links );
	}

	return $links;
}

add_action('admin_footer', 'lfci_warning' );
// Display a warning on each page if the flickr API key is not set
function lfci_warning() {
	$options = get_option('lfci_options');
	if(!isset($options['flickr_API_key'])) {
	    $lfci_link = '<a href="'.get_admin_url().'options-general.php?page=live-flickr-comment-importer/live-flickr-comment-importer.php">'.__('Settings').'</a>';
		echo "<div id='message' class='error'><p><strong>Live Flickr Comment Importer setup not complete.</strong> You must add a Flickr API key before it can work. Go to $lfci_link and fix it :-)</p></div>";
	}
}

add_filter('get_avatar','get_flickr_avatar', 1, 3 );
add_filter('the_content', 'live_flickr_comment_importer');
add_action('publish_post', 'lfci_save_post');
add_action('save_post', 'lfci_save_post');
?>
