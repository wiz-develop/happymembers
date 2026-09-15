<?php
    require("config.php");
    require_once($CMS_PATH."/wp-load.php");

    $now_post_num = $_POST['now_post_num']; // 現時点で表示されている投稿数
    $get_post_num = $_POST['get_post_num']; // 取得する投稿数
    $html = '';

    $today_date = date( "Y-m-d" );

    // 最も古い投稿のidを取得
    $old_args = array(
        'post_status' => 'publish',
        'post_type' => 'event',
        'posts_per_page' => 1,
        'meta_key' => 'event_date',
        'meta_type' => 'DATE',
        'orderby' => 'meta_value',
        'order' => 'DESC',
        'meta_value' => $today_date,
	    'meta_compare' => '>=',
    );

    $old_posts = get_posts( $old_args );
    foreach ( $old_posts as $old_post ) {
        setup_postdata( $old_post );
        $old_post_id = $old_post->ID;
    }
    wp_reset_postdata();
    $old_has_post = false;

    $days = 7; // New を表示させたい期間の日数
    $today = date_i18n('U'); // 現在の日付を取得

    $args = array(
        'post_status' => 'publish',
        'post_type' => 'event',
        'posts_per_page' => $get_post_num,
        'offset' => $now_post_num,
        'meta_key' => 'event_date',
        'meta_type' => 'DATE',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_value' => $today_date,
        'meta_compare' => '>=',
    );
    $the_query = new WP_Query($args);


if ($the_query -> have_posts()) :
    while ($the_query -> have_posts()) : $the_query -> the_post();

    // $html .= 投稿記事の内容を記述
    // 新着記事に New マークを表示
    $entry = get_the_time('U'); // 現在の投稿の時刻を取得
    $total = date('U', ($today - $entry)) / 86400; // 秒数指定 86400 は1日

    $postid = get_the_ID();
    $terms = get_the_terms($postid, 'event');
    $event_time = $cfs->get('event_time', $postid);
    $event_date = $cfs->get('event_date', $postid);
    $event_status = $cfs->get('event_status', $postid);
    $meeting_division = $cfs->get('meeting_division', $postid);
    $prefectures = $cfs->get('prefectures', $postid);
    $municipalities = $cfs->get('municipalities', $postid);
    $event_place = $cfs->get('event_place', $postid);
    $event_speaker = $cfs->get('event_speaker', $postid);
    $meeting_name = $cfs->get('meeting_name', $postid);
    $event_note = $cfs->get('event_note', $postid);

    if ($old_post_id == $postid) {
        $old_has_post = true;
    }

    $html .= '<article class="modal_trigger">';
    if (has_post_thumbnail()) { // 投稿にアイキャッチ画像が割り当てられていたら表示
        $url = get_the_post_thumbnail_url($postid, 'full');
        $html .= '<div class="article-image"><img src="'.$url.'" /></div>';
    }
    $html .= '
        <div class="article-content">
            <div class="article-detail">
                <div class="date">';

                    if ($days > $total) :
                        $html .= '<div class="new"><p>NEW</p></div>';
                    endif;

                $week = array( "日", "月", "火", "水", "木", "金", "土" );
                $event_date_week = date('Y/m/d', strtotime($event_date)) . '(' . $week[date('w', strtotime($event_date))] . ')';
                $html .= '
                    <div class="article-detail__postday">
                        【開催日】<time datetime="'.$event_date.'">'.$event_date_week.'</time>
                    </div>
                </div>
                <div class="category-list">';

                    if ($terms) :
                    foreach ($terms as $term) :
                        $html .= '
                            <div class="article-detail__category '.$term->slug.'">
                                <span>'.$term->name.'</span>
                            </div>';
                    endforeach;
                    endif;

        $html .= '
                </div>
            </div>
            <div class="article-title">
                <p>'.get_the_title().'</p>
            </div>
        </div>
    </article>';

    $html .= '
    <div class="modal_box">
        <div class="modal_bg"></div>
        <div class="modal_inner">
            <div class="modal_block">
                <div class="event-content">
                    <div class="event-content__detail">
                        <div class="event-content__detail__header">
                            <div class="category-list">';
                            if ($terms) :
                                foreach ($terms as $term) :
                                    $html .= '
                                        <div class="article-detail__category '.$term->slug.'">
                                            <span>'.$term->name.'</span>
                                        </div>';
                                endforeach;
                            endif;

                        $html .= '
                            </div>
                            <div class="article-title">
                                <p>'.get_the_title().'</p>
                            </div>
                        </div>
                        <table>';
                        if ($event_date) {
                            $html .= '
                            <tr>
                                <td>開催日</td>
                                <td>'.$event_date_week.'</td>
                            </tr>';
                        }
                        if ($event_time) {
                            $html .= '
                            <tr>
                                <td>開催時間</td>
                                <td>'.$event_time.'</td>
                            </tr>';
                        }
                        if ($event_status) {
                            $html .= '
                            <tr>
                                <td>開催時間</td>
                                <td>';
                            foreach ($event_status as $key => $event) {
                                $html .= '<span class="'.$key.'">'.$event.'</span>';
                            }
                            $html .=
                                '</td>
                            </tr>';
                        }
                        if ($meeting_division) {
                            $html .= '
                            <tr>
                                <td>会合区分</td>
                                <td>'.$meeting_division.'</td>
                            </tr>';
                        }
                        if ($meeting_division) {
                            $html .= '
                            <tr>
                                <td>都道府県</td>
                                <td>'.$meeting_division.'</td>
                            </tr>';
                        }
                        if ($municipalities) {
                            $html .= '
                            <tr>
                                <td>市町村</td>
                                <td>'.$municipalities.'</td>
                            </tr>';
                        }
                        if ($event_place) {
                            $html .= '
                            <tr>
                                <td>開催場所</td>
                                <td>'.$event_place.'</td>
                            </tr>';
                        }
                        if ($event_speaker) {
                            $html .= '
                            <tr>
                                <td>スピーカー</td>
                                <td>'.$event_speaker.'</td>
                            </tr>';
                        }
                        if ($meeting_name) {
                            $html .= '
                            <tr>
                                <td>会合名</td>
                                <td>'.$meeting_name.'</td>
                            </tr>';
                        }
                        if ($event_note) {
                            $html .= '
                            <tr>
                                <td>備考</td>
                                <td>'.$event_note.'</td>
                            </tr>';
                        }
                        $html .= '
                        </table>
                    </div>
                </div>
                <div class="event-image">';

                if (has_post_thumbnail()) { // 投稿にアイキャッチ画像が割り当てられていたら表示
                    $url = get_the_post_thumbnail_url($postid, 'full');
                    $html .= '<div class="article-image"><img src="'.$url.'" /></div>';
                }

                $html .= '
                </div>
                <div class="release-day">掲載日：<time datetime="'.get_the_time('Y-n-d').'">'.get_the_time('Y.m.d').'</time></div>
            </div>
            <div class="modal_close">
                <div class="black-btn">
                    閉じる<span>×</span>
                </div>
            </div>
        </div>
    </div>';

    endwhile;
endif; wp_reset_postdata();

// 最も古い投稿がループに存在するかどうかを判定し、ボタンを非表示にする
if ($old_has_post) {
    $html .= '
        <style>
            .event_more_disp {
                display: none;
            }
        </style>';
}
echo $html;
