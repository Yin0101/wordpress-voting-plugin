<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              
 * @since             1.0.0
 * @package           Spiral_Poll
 *
 * @wordpress-plugin
 * Plugin Name:       Multi Poll
 * Plugin URI:        
 * Description:       This is a simple poll for multiple and login
 * Version:           2.0.0
 * Author:            Yin
 * Author URI:        
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('SPIRAL_POLL_VERSION', '1.0.0');

// Register Custom Post Type
require_once plugin_dir_path(__FILE__) . 'includes/class-spiral-poll-activator.php';
add_action('init', 'Spiral_Poll_Activator::poll_register_post_type');

// Register Custom Taxonomy
require_once plugin_dir_path(__FILE__) . 'includes/class-spiral-poll-activator.php';
add_action('init', 'Spiral_Poll_Activator::award_register_taxonomy');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-spiral-poll-activator.php
 */
function activate_spiral_poll()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-spiral-poll-activator.php';
	Spiral_Poll_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-spiral-poll-deactivator.php
 */
function deactivate_spiral_poll()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-spiral-poll-deactivator.php';
	Spiral_Poll_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_spiral_poll');
register_deactivation_hook(__FILE__, 'deactivate_spiral_poll');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-spiral-poll.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_spiral_poll()
{

	$plugin = new Spiral_Poll();
	$plugin->run();
}
run_spiral_poll();

// The example below will load the template file "archive-poll.php" located in your plugins folder for any archive page that has the type of "poll"; otherwise, uses default template.
function get_custom_post_type_template($archive_template)
{
	if (is_post_type_archive('poll')) {
		$archive_template = dirname(__FILE__) . '/pages/archive-poll.php';
	}
	return $archive_template;
}
add_filter('archive_template', 'get_custom_post_type_template');

// Create a shortcode to display the poll activity
function poll_activity_shortcode()
{
	ob_start();
	include dirname(__FILE__) . '/pages/poll-activity.php';
	return ob_get_clean();
}
add_shortcode('poll_activity', 'poll_activity_shortcode');

// Create a shortcode to display the poll results
function poll_result_shortcode()
{
	ob_start();
	include dirname(__FILE__) . '/pages/poll-result.php';
	return ob_get_clean();
}
add_shortcode('poll_result', 'poll_result_shortcode');

// Create a shortcode to display the poll login form
function poll_login_shortcode()
{
	ob_start();
	include dirname(__FILE__) . '/pages/poll-login.php';
	return ob_get_clean();
}
add_shortcode('poll_login', 'poll_login_shortcode');

// Check if user is logged in and redirect
function poll_login_check()
{
	if (is_user_logged_in() && is_page('poll-login')) {
		wp_redirect(get_permalink(get_page_by_path('poll-activity')));
		exit;
	}

	if (!is_user_logged_in() && is_page('poll-activity')) {
		wp_redirect(get_permalink(get_page_by_path('poll-login')));
		exit;
	}

	if (!is_user_logged_in() && is_page('poll-result')) {
		wp_redirect(get_permalink(get_page_by_path('poll-login')));
		exit;
	}
}
add_action('wp', 'poll_login_check');

// Filters the path of the queried template by type
function poll_single_template($single_template)
{
	global $post;

	if ($post->post_type === 'poll') {
		$single_template = dirname(__FILE__) . '/pages/poll-handler.php';
	}

	return $single_template;
}
add_filter('single_template', 'poll_single_template');

require_once plugin_dir_path(__FILE__) . 'pages/poll-ajax.php';
