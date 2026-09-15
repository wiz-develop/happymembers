<?php
    require("config.php");
    require_once($CMS_PATH."/wp-load.php");

    $now_post_num = $_POST['now_post_num'];
    $get_post_num = $_POST['get_post_num'];
    $html = '';

    // 最も古い投稿のidを取得
    $old_args = array(
        'post_status' => 'publish',
        'post_type' => 'information',
        'orderby' => 'date',
        'order' => 'ASC',
        'posts_per_page' => 1,
        // 'date_query' => array(
        //     array(
        //         'after' => '1 month ago',
        //         'inclusive' => true,
        //     ),
        // ),
    );
    $old_posts = get_posts($old_args);
    $old_post_id = $old_posts[0]->ID;
    $old_has_post = false;

    $days = 7; // New を表示させたい期間の日数
    $today = date_i18n('U'); // 現在の日付を取得

    $args = array(
        'post_status' => 'publish',
        'post_type' => 'information',
        'orderby' => 'post_date',
        'order' => 'DESC',
        'posts_per_page' => $get_post_num,
        'offset' => $now_post_num,
        // 'date_query' => array(
        //     array(
        //         'after' => '1 month ago',
        //         'inclusive' => true,
        //     ),
        // ),
    );
    $posts = new WP_Query($args);

if ($posts -> have_posts()) :
    while ($posts -> have_posts()) : $posts -> the_post();

    // $html .= 投稿記事の内容を記述
    // 新着記事に New マークを表示
    $entry = get_the_time('U'); // 現在の投稿の時刻を取得
    $total = date('U', ($today - $entry)) / 86400; // 秒数指定 86400 は1日

    $postid = get_the_ID();
    $terms = get_the_terms($postid, 'information');

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

                $html .= '
                    <div class="article-detail__postday">
                        <time datetime="'.get_the_time('Y-n-d').'">'.get_the_time('Y.m.d').'</time>
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
                <article class="modal_trigger">
                    <div class="article-content">
                        <div class="article-detail">
                            <div class="article-detail__header">
                                <div class="date">';
                                    if ($days > $total) :
                                        $html .= '<div class="new"><p>NEW</p></div>';
                                    endif;

                                $html .= '
                                    <div class="article-detail__header__postday">
                                        <time datetime="'.get_the_time('Y-n-d').'">'.get_the_time('Y.m.d').'</time>
                                    </div>
                                </div>
                                <div class="category-list">';

                                    if ($terms) :
                                        foreach ($terms as $term) :
                                            $html .= '
                                                <div class="article-detail__header__category term-'.$term->slug.'">
                                                    <span>'.$term->name.'</span>
                                                </div>';
                                        endforeach;
                                    endif;

                            $html .= '
                                </div>
                            </div>
                        </div>
                        <div class="article-title">
                            <p>'.get_the_title().'</p>
                        </div>
                        <div class="article-sentence">
                            '.get_the_content().'
                        </div>
                    </div>';
            $html .= '
                </article>
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
            .info_more_disp {
                display: none;
            }
        </style>';
}
echo $html;
