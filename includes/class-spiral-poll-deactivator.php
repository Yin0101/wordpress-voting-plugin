<?php

/**
 * Fired during plugin deactivation
 *
 * @link       
 * @since      1.0.0
 *
 * @package    Spiral_Poll
 * @subpackage Spiral_Poll/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Spiral_Poll
 * @subpackage Spiral_Poll/includes
 * @author     
 */
class Spiral_Poll_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		unregister_post_type( 'poll' );
		flush_rewrite_rules();
	}

}
