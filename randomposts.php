<?php
/*  Copyright 2006  ADY ROMANTIKA  (email : ady@romantika.name)

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
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
Plugin Name: Random Posts widget
Plugin URI: http://www.romantika.name/v2/2007/05/02/wordpress-plugin-random-posts-widget/
Description: Display Random Posts Widget. Based on <a href="http://www.screenflicker.com/blog/web-development/wordpress-plugin-random-categories-with-random-posts/">Random categories with random posts</a> by Mike Stickel.
Author: Ady Romantika
Version: 1.2
Author URI: http://www.romantika.name/v2/
*/

function ara_random_posts()
{
	global $wpdb;
	$options = (array) get_option('widget_ara_randomposts');
	$title = $options['title'];
	$list_type = $options['type'] ? $options['type'] : 'ul';
	$before_title = $options['before'] ? $options['before'] : '<h2>';
	$after_title = $options['after'] ? $options['after'] : '</h2>';
	$numPosts = $options['count'];

	# Articles from database
	$rand_articles	=	ara_get_random_posts($numPosts);

	# Header
	$string_to_echo  =  ($before_title.$title.$after_title."\n");

	switch($list_type)
	{
		case "br":
			$string_to_echo	.=	"<p>";
			$line_end	=	"<br />\n";
			$closing	=	"</p>\n";
			break;
		case "p":
			$opening	=	"<p>";
			$line_end	=	"</p>\n";
			break;
		case "ul":
		default:
			$string_to_echo	.=	"<ul>\n";
			$opening	=	"<li>";
			$line_end	=	"</li>\n";
			$closing	=	"</ul>\n";
	}

	for ($x=0;$x<count($rand_articles);$x++ )
	{
		if (strlen($opening) > 0 ) $string_to_echo .= $opening;
		$string_to_echo	.= '<a href="'.$rand_articles[$x]['permalink'].'">'.$rand_articles[$x]['title'].'</a>';
		if (strlen($line_end) > 0) $string_to_echo .= $line_end;
	}
	if (strlen($closing) > 0) $string_to_echo .= $closing;
	return $string_to_echo;
}

function ara_get_random_posts($numPosts = '5') {
	global $wpdb;

	$sql = "SELECT $wpdb->post2cat.post_id, $wpdb->post2cat.category_id, $wpdb->posts.ID, $wpdb->posts.post_title";
	$sql .=	" FROM $wpdb->post2cat, $wpdb->posts";
	$sql .=	" WHERE $wpdb->posts.post_status = 'publish'";
	$sql .= " AND $wpdb->posts.post_type = 'post'";
	$sql .= " ORDER BY RAND() LIMIT $numPosts";

	$rand_articles = $wpdb->get_results($sql);

	if ($rand_articles)
	{
		foreach ($rand_articles as $item)
		{
			$posts_results[] = array('title'=>str_replace('"','',stripslashes($item->post_title)),
			 					'permalink'=>post_permalink($item->ID)
								);
		}
		return $posts_results;
	}
	else
	{
		return false;
	}
}

function widget_ara_randomposts_control() {
	$options = $newoptions = get_option('widget_ara_randomposts');
	if ( $_POST['randomposts-submit'] ) {
		$newoptions['title'] = strip_tags(stripslashes($_POST['randomposts-title']));
		$newoptions['type'] = $_POST['randomposts-type'];
		$newoptions['before'] = $_POST['randomposts-before'];
		$newoptions['after'] = $_POST['randomposts-after'];
		$newoptions['count'] = (int) $_POST['randomposts-count'];
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_ara_randomposts', $options);
	}
	$list_type = $options['type'] ? $options['type'] : '<ul>';
	$before_title = $options['before'] ? $options['before'] : '<h2>';
	$after_title = $options['after'] ? $options['after'] : '</h2>';
?>
			<div style="text-align:right">
			<label for="randomposts-title" style="line-height:25px;display:block;"><?php _e('Widget title:', 'widgets'); ?> <input style="width: 200px;" type="text" id="randomposts-title" name="randomposts-title" value="<?php echo ($options['title'] ? wp_specialchars($options['title'], true) : 'Random Posts'); ?>" /></label>
			<label for="randomposts-type" style="line-height:25px;display:block;">
				<?php _e('List Type:', 'widgets'); ?>
					<select style="width: 200px;" id="randomposts-type" name="randomposts-type">
						<option value="ul"<?php if ($options['type'] == 'ul') echo ' selected' ?>>&lt;ul&gt;</option>
						<option value="br"<?php if ($options['type'] == 'br') echo ' selected' ?>>&lt;br/&gt;</option>
						<option value="p"<?php if ($options['type'] == 'p') echo ' selected' ?>>&lt;p&gt;</option>
					</select>
			</label>
			<label for="randomposts-before" style="line-height:25px;display:block;"><?php _e('Before Title:', 'widgets'); ?> <input style="width: 200px;" type="text" id="randomposts-before" name="randomposts-before" value="<?php echo ($options['before'] ? wp_specialchars($options['before'], true) : '<h2>'); ?>" /></label>
			<label for="randomposts-after" style="line-height:25px;display:block;"><?php _e('After Title:', 'widgets'); ?> <input style="width: 200px;" type="text" id="randomposts-after" name="randomposts-after" value="<?php echo ($options['after'] ? wp_specialchars($options['after'], true) : '</h2>'); ?>" /></label>
			<label for="randomposts-count" style="line-height:25px;display:block;">
				<?php _e('Post count:', 'widgets'); ?>
					<select style="width: 200px;" id="randomposts-count" name="randomposts-count"/>
						<?php for($cnt=1;$cnt<=10;$cnt++): ?>
							<option value="<?php echo $cnt ?>"<?php if($options['count'] == $cnt) echo ' selected' ?>><?php echo $cnt ?></option>
						<?php endfor; ?>
					</select>
			</label>
			<input type="hidden" name="randomposts-submit" id="randomposts-submit" value="1" />
			</div>
<?php
}

function widget_ara_randomposts_init() {

	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	// This prints the widget
	function widget_ara_randomposts($args) {
		extract($args);
		?>
		<?php echo $before_widget; ?>
		<?php echo ara_random_posts(); ?>
		<?php echo $after_widget; ?>
<?php
	}

	// Tell Dynamic Sidebar about our new widget and its control
	register_sidebar_widget(array('Random Posts Widget', 'widgets'), 'widget_ara_randomposts');
	register_widget_control(array('Random Posts Widget', 'widgets'), 'widget_ara_randomposts_control');
}

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
add_action('widgets_init', 'widget_ara_randomposts_init');

?>
