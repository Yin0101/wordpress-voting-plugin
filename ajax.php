<?php
require __DIR__ . '/wp-load.php';
    // var_dump("999");
    // check_ajax_referer('ajax_poll');

    // $user_id = $_POST['user_id'];
    // $post_id = $_POST['post_id'];
    // $award_id = $_POST['award_id'];
    // $user_id = $_POST['user_id'];
    // $post_id = $_POST['post_id'];
    // $award_id = $_POST['award_id'];
    echo "<pre>";
    // print_r($user_id);
    // print_r($post_id);
    print_r($_POST);
    echo "</pre>";

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
    for($i=0;$i<sizeof($_POST['voted_object']);$i++){
        $user_id = "";
        $post_id = array();
        $award_id = "";
        $user_id = $_POST['voted_object'][$i][0];
        $award_id = $_POST['voted_object'][$i][1];
        for($a=2;$a<sizeof($_POST['voted_object'][$i]);$a++){
            array_push($post_id,$_POST['voted_object'][$i][$a]);
        }
        // echo "<pre>";
        // var_dump($user_id);
        // var_dump($post_id);
        // print_r($post_id);
        // echo "</pre>";

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

        echo "<pre>";
        // print_r($exists_poll);
        // print_r(implode(",", $award_id));
        $post_string = implode(",", $post_id);
        print_r($post_string);
        echo "</pre>";

        if ($exists_poll) {
            $table_name = 'LdstvrO_polls';
            $data_update = array('voted' => $post_string);
            $data_where = array('id' => $exists_poll->id);
            $wpdb->update($table_name , $data_update, $data_where);
            // $wpdb->update(
            //     $table_name,
            //     array(
            //         'user_id' => $user_id,
            //         'post_id' => "ab,sd",
            //         'award_id' => $award_id,
            //         'updated_at' => current_time('mysql'),
            //     ),
            //     array('id' => $exists_poll->id),
            // );
        } else {
            $wpdb->insert(
                $table_name,
                array(
                    'user_id' => $user_id,
                    'voted' => $post_string,
                    'award_id' => $award_id,
                    'created_at' => current_time('mysql'),
                )
            );
        }
    
        echo 'Voted successfully 1';
    }


    // echo "<pre>";
    // print_r($exists_poll);
    // var_dump($exists_poll);
    // echo "</pre>";

    // wp_die();


?>