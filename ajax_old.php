<?php
require __DIR__ . '/wp-load.php';
    // var_dump("999");
    // check_ajax_referer('ajax_poll');

    $user_id = $_POST['user_id'];
    $post_id = $_POST['post_id'];
    $award_id = $_POST['award_id'];

    global $wpdb;

    $table_name = $wpdb->prefix . 'polls';

    // $sql = "CREATE TABLE $table_name (
    //     id mediumint(9) NOT NULL AUTO_INCREMENT,
    //     PRIMARY KEY  (id)
    //   )";

    // var_dump("123");
    // require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    // dbDelta( $sql );
    // var_dump("1234");

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

    // echo "<pre>";
    // print_r($exists_poll);
    // var_dump($exists_poll);
    // echo "</pre>";

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

    // wp_die();


?>