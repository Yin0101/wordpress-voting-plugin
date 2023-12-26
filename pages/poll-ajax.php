<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

add_action('wp_ajax_my_ajax_poll', 'my_ajax_poll_handler');

function my_ajax_poll_handler()
{
    check_ajax_referer('ajax_poll');

    $user_id = $_POST['user_id'];
    $post_id = $_POST['post_id'];
    $award_id = $_POST['award_id'];

    global $wpdb;

    $table_name = $wpdb->prefix . 'polls';

    $exists_poll = $wpdb->get_row(
        $wpdb->prepare(
            "
            SELECT * FROM $table_name
            WHERE user_id = %d AND award_id = %d
            ",
            $user_id,
            $award_id
        )
    );

    if ($exists_poll) {
        $wpdb->update(
            $table_name,
            array(
                'user_id' => $user_id,
                'post_id' => $post_id,
                'award_id' => $award_id,
                'updated_at' => current_time('mysql'),
            ),
            array('id' => $exists_poll->id),
        );
    } else {
        $wpdb->insert(
            $table_name,
            array(
                'user_id' => $user_id,
                'post_id' => $post_id,
                'award_id' => $award_id,
                'created_at' => current_time('mysql'),
            )
        );
    }

    echo 'Voted successfully';

    wp_die();
}
