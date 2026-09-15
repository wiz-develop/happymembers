<?php
require("config.php");
require_once($CMS_PATH."/wp-load.php");
global $wpdb;


//表示させる年月を設定
$anu = date('Y');
$mont = date('m');
if ($_GET['anu'] && $_GET['mont']) {
    $anu = $_GET['anu'];
    $mont = $_GET['mont'];
}
$c_date = $anu.'-'.$mont;

// 月初日を設定
$first_date = date('Y-m-d', strtotime('first day of ' . $c_date));
//月末日を取得
$last_date = date('Y-m-d', strtotime('last day of ' . $c_date));


$arg=[];
// 投稿の表示条件設定
$arg = array(
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'post_type'      => 'event',
    'meta_query'=> array(
        'relation' => 'AND',
        // 指定日より前
        array(
            'key' => 'event_date',
            'value' => $first_date,
            'compare' => '>=',
            'type' => 'DATE'
        ),
        // 指定日よりあと
        array(
            'key'     =>'event_date',
            'value' => $last_date,
            'compare' => '<=',
            'type' => 'DATE'
        ),
    ),
);

$posts = get_posts($arg);
// $the_query = new WP_Query($arg);
// $posts = $the_query->the_post();

foreach ($posts as $i => $p) {
    $c_post[$i]->publish_date = mysql2date('Y.m.d', $p->post_date);
    
    // カスタムフィールド取得
    $e_date = $cfs->get('event_date', $p->ID);
    $c_post[$i]->post_date = $e_date;

    $e_time = $cfs->get('event_time', $p->ID);
    $c_post[$i]->event_time = $e_time;

    $e_status = $cfs->get('event_status', $p->ID);
    $c_post[$i]->event_status = $e_status;

    $e_meeting_division = $cfs->get('meeting_division', $p->ID);
    $c_post[$i]->meeting_division = $e_meeting_division;

    $e_prefectures = $cfs->get('prefectures', $p->ID);
    $c_post[$i]->prefectures = $e_prefectures;

    $e_municipalities = $cfs->get('municipalities', $p->ID);
    $c_post[$i]->municipalities = $e_municipalities;

    $e_place = $cfs->get('event_place', $p->ID);
    $c_post[$i]->event_place = $e_place;

    $e_speaker = $cfs->get('event_speaker', $p->ID);
    $c_post[$i]->event_speaker = $e_speaker;

    $e_meeting_name = $cfs->get('meeting_name', $p->ID);
    $c_post[$i]->meeting_name = $e_meeting_name;

    $e_note = $cfs->get('event_note', $p->ID);
    $c_post[$i]->event_note = $e_note;

    $c_post[$i]->post_title = $posts[$i]->post_title;

    $e_terms = get_the_terms($p->ID, 'event');
    $c_post[$i]->post_terms = $e_terms;

    $thumbnail = get_the_post_thumbnail($p->ID);
    $c_post[$i]->post_thumbnail = $thumbnail;
}
echo json_encode($c_post, true);
