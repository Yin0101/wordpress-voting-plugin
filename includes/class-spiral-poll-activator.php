<?php

/**
 * Fired during plugin activation
 *
 * @link       
 * @since      1.0.0
 *
 * @package    Spiral_Poll
 * @subpackage Spiral_Poll/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Spiral_Poll
 * @subpackage Spiral_Poll/includes
 * @author     
 */
class Spiral_Poll_Activator
{
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		if (!current_user_can('activate_plugins'))
			return;

		// Create page with shortcode
		if (!self::is_post_exists_by_slug('poll-activity', 'page')) {
			wp_insert_post([
				'post_title'	=>	'Poll Activity',
				'post_name'		=>	'poll-activity',
				'post_type'		=>	'page',
				'post_status'	=>	'publish',
				'post_content'	=>	'[poll_activity]',
			]);
		}

		// Create page with shortcode
		if (!self::is_post_exists_by_slug('poll-result', 'page')) {
			wp_insert_post([
				'post_title'	=>	'Poll Result',
				'post_name'		=>	'poll-result',
				'post_type'		=>	'page',
				'post_status'	=>	'publish',
				'post_content'	=>	'[poll_result]',
			]);
		}

		// Create page with shortcode
		if (!self::is_post_exists_by_slug('poll-login', 'page')) {
			wp_insert_post([
				'post_title'	=>	'Poll Login',
				'post_name'		=>	'poll-login',
				'post_type'		=>	'page',
				'post_status'	=>	'publish',
				'post_content'	=>	'[poll_login]',
			]);
		}

		// Register Custom Post Type
		self::poll_register_post_type();

		// Register Custom Taxonomy
		self::award_register_taxonomy();

		// Flush rewrite rules to add "poll" as a permalink slug
		flush_rewrite_rules();

		// Create a table for storing poll results
		self::poll_table_create();

		// Insert dummy data for testing
		self::poll_insert_data();
	}

	// Register Custom Post Type
	public static function poll_register_post_type()
	{
		$args = [
			'label'  => esc_html__('Polls', 'spiral-poll'),
			'labels' => [
				'menu_name'          => esc_html__('Polls', 'spiral-poll'),
				'name_admin_bar'     => esc_html__('Poll', 'spiral-poll'),
				'add_new'            => esc_html__('Add Poll', 'spiral-poll'),
				'add_new_item'       => esc_html__('Add new Poll', 'spiral-poll'),
				'new_item'           => esc_html__('New Poll', 'spiral-poll'),
				'edit_item'          => esc_html__('Edit Poll', 'spiral-poll'),
				'view_item'          => esc_html__('View Poll', 'spiral-poll'),
				'update_item'        => esc_html__('View Poll', 'spiral-poll'),
				'all_items'          => esc_html__('All Polls', 'spiral-poll'),
				'search_items'       => esc_html__('Search Polls', 'spiral-poll'),
				'parent_item_colon'  => esc_html__('Parent Poll', 'spiral-poll'),
				'not_found'          => esc_html__('No Polls found', 'spiral-poll'),
				'not_found_in_trash' => esc_html__('No Polls found in Trash', 'spiral-poll'),
				'name'               => esc_html__('Polls', 'spiral-poll'),
				'singular_name'      => esc_html__('Poll', 'spiral-poll'),
			],
			'public'              => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true,
			'taxonomies'          => ['award'],
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'show_in_menu'        => true,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-awards',
			'supports' => [
				'title',
				'editor',
				'thumbnail',
			],
			'rewrite' => [
				'slug'       => 'poll',
			],
		];

		register_post_type('poll', $args);
	}

	// Register Custom Taxonomy
	public static function award_register_taxonomy()
	{
		$args = [
			'label'  => esc_html__('Awards', 'spiral-poll'),
			'labels' => [
				'menu_name'                  => esc_html__('Awards', 'spiral-poll'),
				'all_items'                  => esc_html__('All Awards', 'spiral-poll'),
				'edit_item'                  => esc_html__('Edit Award', 'spiral-poll'),
				'view_item'                  => esc_html__('View Award', 'spiral-poll'),
				'update_item'                => esc_html__('Update Award', 'spiral-poll'),
				'add_new_item'               => esc_html__('Add new Award', 'spiral-poll'),
				'new_item'                   => esc_html__('New Award', 'spiral-poll'),
				'parent_item'                => esc_html__('Parent Award', 'spiral-poll'),
				'parent_item_colon'          => esc_html__('Parent Award', 'spiral-poll'),
				'search_items'               => esc_html__('Search Awards', 'spiral-poll'),
				'popular_items'              => esc_html__('Popular Awards', 'spiral-poll'),
				'separate_items_with_commas' => esc_html__('Separate Awards with commas', 'spiral-poll'),
				'add_or_remove_items'        => esc_html__('Add or remove Awards', 'spiral-poll'),
				'choose_from_most_used'      => esc_html__('Choose most used Awards', 'spiral-poll'),
				'not_found'                  => esc_html__('No Awards found', 'spiral-poll'),
				'name'                       => esc_html__('Awards', 'spiral-poll'),
				'singular_name'              => esc_html__('Award', 'spiral-poll'),
			],
			'public'               => true,
			'show_ui'              => true,
			'show_in_menu'         => true,
			'show_in_nav_menus'    => true,
			'show_tagcloud'        => true,
			'show_in_quick_edit'   => true,
			'show_admin_column'    => true,
			'show_in_rest'         => true,
			'hierarchical'         => true,
			'query_var'            => true,
			'sort'                 => false,
			'rewrite_hierarchical' => false,
			'rewrite' => [
				'slug'       => 'award',
			],
		];

		register_taxonomy('award', ['poll'], $args);
	}

	// Create table
	public static function poll_table_create()
	{
		global $poll_db_version;

		$poll_db_version = '1.0';

		global $wpdb;

		$table_name = $wpdb->prefix . 'polls';

		$charset_collate = $wpdb->get_charset_collate();

		// $sql = "CREATE TABLE $table_name (
		// 	id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		// 	user_id bigint(20) UNSIGNED NOT NULL default '0',
		// 	post_id bigint(20) UNSIGNED NOT NULL default '0',
		// 	award_id bigint(20) UNSIGNED NOT NULL default '0',
		// 	created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		// 	updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		// 	PRIMARY KEY  (id),
		// 	KEY user_id (user_id),
		// 	KEY post_id (post_id),
		// 	KEY award_id (award_id)
		// ) $charset_collate;";

		$sql = "CREATE TABLE $table_name ( 
			id INT NOT NULL AUTO_INCREMENT , 
			user_id VARCHAR(255) NULL , 
			post_id VARCHAR(255) NULL , 
			award_id VARCHAR(255) NULL , 
			created_at DATETIME NULL , 
			updated_at DATETIME NULL , 
			PRIMARY KEY (`id`)) 
			$charset_collate";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta($sql);

		add_option('poll_db_version', $poll_db_version);
	}

	// Insert dummy data for testing
	public static function poll_insert_data()
	{
		if (!term_exists('best-mens-watch', 'award')) {
			wp_insert_term('最佳男裝表', 'award', array(
				'description' => "Best Men's Watch",
				'slug' => 'best-mens-watch',
			));
		}

		if (!term_exists('best-ladies-watch', 'award')) {
			wp_insert_term('最佳女裝表', 'award', array(
				'description' => "Best Ladies' Watch",
				'slug' => 'best-ladies-watch',
			));
		}

		for ($i = 1; $i <= 10; $i += 2) {
			if (!post_exists('Watch ' . $i, '', '', 'poll')) {
				$post_id = wp_insert_post([
					'post_title' => 'Watch ' . $i,
					'post_content' => 'Watch ' . $i . ' content',
					'post_status' => 'publish',
					'post_type' => 'poll',
				]);

				set_post_thumbnail($post_id, 778); // hardcode image id

				wp_set_object_terms($post_id, 'best-mens-watch', 'award');
			}
		}

		for ($i = 2; $i <= 10; $i += 2) {
			if (!post_exists('Watch ' . $i, '', '', 'poll')) {
				$post_id = wp_insert_post([
					'post_title' => 'Watch ' . $i,
					'post_content' => 'Post ' . $i . ' content',
					'post_status' => 'publish',
					'post_type' => 'poll',
				]);

				set_post_thumbnail($post_id, 778); // hardcode image id

				wp_set_object_terms($post_id, 'best-ladies-watch', 'award');
			}
		}

		$mens_award = get_term_by('slug', 'best-mens-watch', 'award');
		$ladies_award = get_term_by('slug', 'best-ladies-watch', 'award');

		$mens_watch = new WP_Query([
			'post_type' => 'poll',
			'posts_per_page' => -1,
			'tax_query' => [
				[
					'taxonomy' => 'award',
					'field' => 'slug',
					'terms' => 'best-mens-watch',
				],
			],
		]);

		$ladies_watch = new WP_Query([
			'post_type' => 'poll',
			'posts_per_page' => -1,
			'tax_query' => [
				[
					'taxonomy' => 'award',
					'field' => 'slug',
					'terms' => 'best-ladies-watch',
				],
			],
		]);

		$mens_watch_ids = wp_list_pluck( $mens_watch->posts, 'ID' );
		$ladies_watch_ids = wp_list_pluck( $ladies_watch->posts, 'ID' );

		global $wpdb;

		$table_name = $wpdb->prefix . 'polls';

		$wpdb->query("TRUNCATE TABLE $table_name");

		for ($i = 1; $i <= 100; $i++) {
			$wpdb->insert(
				$table_name,
				[
					'user_id' => $i,
					'post_id' => $mens_watch_ids[array_rand($mens_watch_ids)],
					'award_id' => $mens_award->term_id,
					'created_at' => current_time('mysql'),
					'updated_at' => current_time('mysql'),
				]
			);
		}

		for ($i = 1; $i <= 100; $i++) {
			$wpdb->insert(
				$table_name,
				[
					'user_id' => $i,
					'post_id' => $ladies_watch_ids[array_rand($ladies_watch_ids)],
					'award_id' => $ladies_award->term_id,
					'created_at' => current_time('mysql'),
					'updated_at' => current_time('mysql'),
				]
			);
		}
	}

	public static function is_post_exists_by_slug($post_slug, $post_type)
	{
		$loop = new WP_Query([
			'post_type'      => $post_type,
			'name'           => $post_slug,
			'posts_per_page' => 1,
		]);

		return $loop->have_posts();
	}
}

add_filter('manage_poll_posts_columns','poll_custom_columns');

function poll_custom_columns( $columns ) {

    $columns['result'] = 'result';
	
    return $columns;
	
}

add_action( 'manage_posts_custom_column','poll_custom_columns_content', 10, 2 );

function poll_custom_columns_content ( $column_name, $post_id ) {
    //run a switch statement for all of the custom columns created
    switch( $column_name ) { 
        case 'result':
			
			global $wpdb;
			$table_name = $wpdb->prefix . 'polls';
			$poll_count = $wpdb->get_results("
			SELECT voted FROM $table_name; 
			");
			$total = 0;
			// echo "<pre>";
			// print_r($poll_count);
			// echo "</pre>";
			for($i=0;$i<sizeof($poll_count);$i++){
				$poll_count[$i]->voted = explode(",",$poll_count[$i]->voted);
				// echo $poll_count[$i]->voted;
				for($a=0;$a<sizeof($poll_count[$i]->voted);$a++){
					if($poll_count[$i]->voted[$a] == $post_id){
						$total +=1;
					}
				}
			}
			// echo "<pre>";
			// print_r($poll_count);
			// echo "</pre>";
			// var_dump($poll_count);
			// $result = $poll_count[0]->result;
			// $result = (int)$result;
            // echo "SELECT voted FROM $table_name where post_id = $post_id";
			echo $total;
			
        break;

   }
}