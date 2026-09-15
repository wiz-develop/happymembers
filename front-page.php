<?php
/**
 * The main template file
 * Template Name: トップページ
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */
get_header();
session_check();
global $MEM_COMBINE_STATUS_HPA;?>

<div id="front-page" class="page">
    <div class="mod-bnr">
        <div class="bnr-list">
            <?php
                $fields = $cfs->get('bnr_list');
                if ($fields) :
                    foreach ($fields as $field) :
            ?>
                <!-- 記事の数だけ繰り返し表示される部分 -->
                <div class="bnr-list__detail">
                    <a href="<?php echo $field['bnr_rink']; ?>" target="_blank" rel="noopener">
                        <div class="bnr-list__detail__image">
                            <img src="<?php echo $field['image']; ?>">
                        </div>
                    </a>
                </div>
            <?php
                    endforeach;
                endif;
            ?>
        </div>
    </div>
    <div class="mod-body">
        <div id="infomation" class="content infomation">
            <h2 class="d-flex align-items-center">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/info-icon.png" class="pr-2">
                <span>インフォメーション</span>
            </h2>
            <div id="infomations" class="info-list">
                <?php
                    // ループを変更したら/cms/wp-content/themes/happy-members/assets/api/v1/common/info-readmore.phpも変更する
                    $days = 7; // New を表示させたい期間の日数
                    $today = date_i18n('U'); // 現在の日付を取得

                    $posts_per_page = 3;
                    $args = array(
                        'post_status' => 'publish',
                        'posts_per_page' => $posts_per_page,
                        'post_type' => 'information',
                        'orderby' => 'date',
                        'order' => 'DESC',
                        // 'date_query' => array(
                        //     array(
                        //         'after' => '1 month ago',
                        //         'inclusive' => true,
                        //     ),
                        // ),
                    );

                    $query = new WP_Query($args);
                    if ($query->have_posts()) :

                        while ($query->have_posts()) :
                            $query->the_post();

                            // 新着記事に New マークを表示
                            $entry = get_the_time('U'); // 現在の投稿の時刻を取得
                            $total = date('U', ($today - $entry)) / 86400; // 秒数指定 86400 は1日

                            $postid = get_the_ID();
                            $terms = get_the_terms($postid, 'information');
                ?>
                    <article class="modal_trigger">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="article-image">
                                <?php
                                    if (has_post_thumbnail()) { // 投稿にアイキャッチ画像が割り当てられているかチェックします。
                                        $url = get_the_post_thumbnail_url($postid, 'full');
                                        echo "<img src='{$url}' />";
                                    }
                                ?>
                            </div>
                        <?php endif; ?>
                        <div class="article-content">
                            <div class="article-detail">
                                <div class="date">
                                    <?php if ($days > $total) : ?>
                                        <div class="new">
                                            <p>NEW</p>
                                        </div>
                                    <?php endif; ?>
                                    <div class="article-detail__postday">
                                        <time datetime="<?php the_time('Y-n-d'); ?>"><?php the_time('Y.m.d') ?></time>
                                    </div>
                                </div>
                                <div class="category-list">
                                    <?php
                                        if ($terms) :
                                            foreach ($terms as $term) :
                                    ?>
                                        <div class="article-detail__category term-<?php echo $term->slug; ?>">
                                            <span><?php echo $term->name; ?></span>
                                        </div>
                                    <?php
                                            endforeach;
                                        endif;
                                    ?>
                                </div>
                            </div>
                            <div class="article-title">
                                <p><?php the_title(); ?></p>
                            </div>
                        </div>
                    </article>
                    <!-- モーダル --------------------------->
                    <div class="modal_box">
                        <div class="modal_bg"></div>
                        <div class="modal_inner">
                            <div class="modal_block">
                                <article class="modal_trigger">
                                    <div class="article-content">
                                        <div class="article-detail">
                                            <div class="article-detail__header">
                                                <div class="date">
                                                    <?php if ($days > $total) : ?>
                                                        <div class="new">
                                                            <p>NEW</p>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="article-detail__header__postday">
                                                        <?php the_time('Y.m.d') ?>
                                                    </div>
                                                </div>
                                                <div class="category-list">
                                                    <?php
                                                        if ($terms) :
                                                            foreach ($terms as $term) :
                                                    ?>
                                                        <div class="article-detail__header__category term-<?php echo $term->slug; ?>">
                                                            <span><?php echo $term->name; ?></span>
                                                        </div>
                                                    <?php
                                                            endforeach;
                                                        endif;
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="article-title">
                                            <p><?php the_title(); ?></p>
                                        </div>
                                        <div class="article-sentence">
                                            <?php the_content(); ?>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div class="modal_close">
                                <div class="black-btn">
                                    閉じる<span>×</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- モーダル --------------------------->
                <?php
                        endwhile;
                        wp_reset_postdata();

                            $publish_posts = $query->found_posts;
                            if ($publish_posts > $posts_per_page) :
                ?>
                    <div class="more_disp info_more_disp">
                        <button data-post="information">もっと見る</button>
                    </div>
                    <div class="more_disp info_more_disp all-article">
                        <button data-post="information" data-postnum="<?php echo $publish_posts; ?>">直近の情報 <?php echo $publish_posts; ?>件 すべて表示</button>
                    </div>
                <?php
                            endif;
                        else :
                            wp_reset_postdata();
                            echo "<p>最新の情報はありません</p>";
                    endif;
                ?>
            </div>
        </div>
        <div class="content link-item">
            <h2 class="d-flex align-items-center">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/purchase-icon.png" class="pr-2">
                <span>商品購入</span>
            </h2>
            <div class="link-item__list">
                <div class="link-item__list__content">
                    <a href="<?php echo get_home_url(); ?>/product/">
                        <div class="link-item__list__content__detail">
                            <div class="link-item__list__content__detail__image">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/top/product-icon.png">
                            </div>
                            <div class="link-item__list__content__detail__name">
                                <span>商品一覧</span>
                            </div>
                            <div class="link-item__list__content__detail__txt">
                                <p>商品のご購入はこちらから</p>
                            </div>
                            <div class="link-item__list__content__detail__btn">
                                詳細へ<i class="fas fa-angle-right pl-3"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="link-item__list__content">
                    <a href="<?php echo get_home_url(); ?>/cart/">
                        <div class="link-item__list__content__detail">
                            <div class="link-item__list__content__detail__image">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/top/order-icon.png">
                            </div>
                            <div class="link-item__list__content__detail__name">
                                <span>カート一覧</span>
                            </div>
                            <div class="link-item__list__content__detail__txt">
                                <p>ご注文状況をご確認いただけます</p>
                            </div>
                            <div class="link-item__list__content__detail__btn">
                                詳細へ<i class="fas fa-angle-right pl-3"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="link-item__list__content">
                    <a href="<?php echo get_home_url(); ?>/product-archive/">
                        <div class="link-item__list__content__detail">
                            <div class="link-item__list__content__detail__image">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/top/history-icon.png">
                            </div>
                            <div class="link-item__list__content__detail__name">
                                <span>商品購入履歴</span>
                            </div>
                            <div class="link-item__list__content__detail__txt">
                                <p>購入した商品の履歴を閲覧できます</p>
                            </div>
                            <div class="link-item__list__content__detail__btn">
                                詳細へ<i class="fas fa-angle-right pl-3"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="content link-item">
            <h2 class="d-flex align-items-center">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon.png" class="pr-2">
                <span>会員情報</span>
            </h2>
            <div class="link-item__list">
                <div class="link-item__list__content">
                    <a href="<?php echo get_home_url(); ?>/member-info/">
                        <div class="link-item__list__content__detail">
                            <div class="link-item__list__content__detail__image">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/top/member-icon.png">
                            </div>
                            <div class="link-item__list__content__detail__name">
                                <span>登録情報</span>
                            </div>
                            <div class="link-item__list__content__detail__txt">
                                <p>ご登録いただいている情報の確認・<span>編集はこちらから</span></p>
                            </div>
                            <div class="link-item__list__content__detail__btn">
                                詳細へ<i class="fas fa-angle-right pl-3"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="link-item__list__content">
                    <a href="<?php echo get_home_url(); ?>/member-info/chart/">
                        <div class="link-item__list__content__detail">
                            <div class="link-item__list__content__detail__image">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/top/chart-icon.png">
                            </div>
                            <div class="link-item__list__content__detail__name">
                                <span>組織図</span>
                            </div>
                            <div class="link-item__list__content__detail__txt">
                                <p>ハッピーファミリーとエクセレントの<span>組織図が見れます</span></p>
                            </div>
                            <div class="link-item__list__content__detail__btn">
                                詳細へ<i class="fas fa-angle-right pl-3"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="link-item__list__content">
                    <a href="<?php echo get_home_url(); ?>/member-info/bonus/">
                        <div class="link-item__list__content__detail">
                            <div class="link-item__list__content__detail__image">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/top/bonus-icon.png">
                            </div>
                            <div class="link-item__list__content__detail__name">
                                <span>ボーナス明細</span>
                            </div>
                            <div class="link-item__list__content__detail__txt">
                                <p>ハッピーファミリーとエクセレントの<span>ボーナス発生履歴が見れます</span></p>
                            </div>
                            <div class="link-item__list__content__detail__btn">
                                詳細へ<i class="fas fa-angle-right pl-3"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="link-item__list__content off">
                    <!-- <a href="/new-member/"> -->
                        <div class="link-item__list__content__detail">
                            <div class="link-item__list__content__detail__image">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/top/register-icon_off.png">
                            </div>
                            <div class="link-item__list__content__detail__name">
                                <span style="color: #acacac;">新規会員紹介</span>
                            </div>
                            <!-- <div class="link-item__list__content__detail__txt">
                                <p>xxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>
                            </div> -->
                            <div class="link-item__list__content__detail__btn" style="background-color: #acacac;">
                                ただいま準備中です
                            </div>
                        </div>
                    <!-- </a> -->
                </div>
            </div>
        </div>
        <div id="document" class="content document">
            <h2 class="d-flex align-items-center">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/document-icon.png" class="pr-2">
                <span>各種申請書ダウンロード</span>
            </h2>
            <div class="document-content">
                <div class="document-content__image">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/dl-icon.png">
                </div>
                <div class="document-content__detail">
                    <p>各種申請に必要な情報をダウンロードすることができます</p>
                    <a href="<?php echo get_home_url(); ?>/document/">
                        <div class="link-item__list__content__detail__btn">
                            詳細へ<i class="fas fa-angle-right pl-3"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div id="event" class="content event">
            <h2 class="d-flex align-items-center">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/event-icon.png" class="pr-2">
                <span>スケジュール案内</span>
            </h2>
            <div class="event-content">
                <input type="radio" name="tab_name" id="list" checked>
                <label class="tab_class list-tab" for="list">最新情報</label>
                <div id="events" class="content_class">
                    <?php
                        $today_date = date( "Y-m-d" );
                        $week = array( "日", "月", "火", "水", "木", "金", "土" );
                        // ループを変更したら/cms/wp-content/themes/happy-members/assets/api/v1/common/event-readmore.phpも変更する
                        $posts_per_page = 3;
                        $args = array(
                            'post_status' => 'publish',
                            'posts_per_page' => $posts_per_page,
                            'post_type' => 'event',
                            'meta_key' => 'event_date',
                            'meta_type' => 'DATE',
                            'orderby' => 'meta_value',
                            'order' => 'ASC',
                            'meta_value' => $today_date,
	                        'meta_compare' => '>=',
                        );

                        $query = new WP_Query($args);
                        if ($query->have_posts()):
                            $count = 0;

                            while ($query->have_posts()):
                                $query->the_post();

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
                    ?>
                        <article class="modal_trigger">
                            <?php
                                if (has_post_thumbnail()) { // 投稿にアイキャッチ画像が割り当てられているかチェックします。
                                    $url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                    echo '<div class="article-image"><img src="'.$url.'" /></div>';
                                }
                            ?>
                            <div class="article-content">
                                <div class="article-detail">
                                    <div class="date">
                                        <?php if ($days > $total) : ?>
                                            <div class="new">
                                                <p>NEW</p>
                                            </div>
                                        <?php endif; ?>
                                        <div class="article-detail__postday">
                                            <?php $event_date_week =  date('Y/m/d', strtotime($event_date)) . "(" . $week[date('w', strtotime($event_date))] . ")"; ?>
                                            【開催日】<time datetime="<?php echo $event_date; ?>"><?php echo $event_date_week; ?></time>
                                        </div>
                                    </div>
                                    <div class="category-list">
                                        <?php
                                            if ($terms) :
                                                foreach ($terms as $term) :
                                        ?>
                                            <div class="article-detail__category <?php echo $term->slug; ?>">
                                                <span>
                                                    <?php echo $term->name; ?>
                                                </span>
                                            </div>
                                        <?php
                                                endforeach;
                                            endif;
                                        ?>
                                    </div>
                                </div>
                                <div class="article-title">
                                    <p><?php the_title(); ?></p>
                                </div>
                            </div>
                        </article>
                        <!-- モーダル --------------------------->
                        <div class="modal_box">
                            <div class="modal_bg"></div>
                            <div class="modal_inner">
                                <div class="modal_block">
                                    <div class="event-content">
                                        <div class="event-content__detail">
                                            <div class="event-content__detail__header">
                                                <div class="category-list">
                                                    <?php
                                                        if ($terms) :
                                                            foreach ($terms as $term) :
                                                    ?>
                                                        <div class="article-detail__category <?php echo $term->slug; ?>">
                                                            <span>
                                                                <?php echo $term->name; ?>
                                                            </span>
                                                        </div>
                                                    <?php
                                                            endforeach;
                                                        endif;
                                                    ?>
                                                </div>
                                                <div class="article-title">
                                                    <p><?php the_title(); ?></p>
                                                </div>
                                            </div>
                                            <table>
                                                <?php if ($event_date) { ?>
                                                <tr>
                                                    <td>開催日</td>
                                                    <td><?php echo $event_date_week; ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                    if ($event_time) {
                                                        ?>
                                                <tr>
                                                    <td>開催時間</td>
                                                    <td><?php echo $event_time; ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                    if ($event_status) {
                                                        ?>
                                                <tr>
                                                    <td>開催状況</td>
                                                    <td>
                                                        <?php
                                                            foreach ($event_status as $key => $event) {
                                                                echo '<span class="'.$key.'">'.$event.'</span>';
                                                            } ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                    if ($meeting_division) {
                                                        ?>
                                                <tr>
                                                    <td>会合区分</td>
                                                    <td><?php echo $meeting_division; ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                    if ($prefectures) {
                                                        ?>
                                                <tr>
                                                    <td>都道府県</td>
                                                    <td><?php echo $prefectures; ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                    if ($municipalities) {
                                                        ?>
                                                <tr>
                                                    <td>市町村</td>
                                                    <td><?php echo $municipalities; ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                    if ($event_place) {
                                                        ?>
                                                <tr>
                                                    <td>開催場所</td>
                                                    <td><?php echo $event_place; ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                    if ($event_speaker) {
                                                        ?>
                                                <tr>
                                                    <td>スピーカー</td>
                                                    <td><?php echo $event_speaker; ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                    if ($meeting_name) {
                                                        ?>
                                                <tr>
                                                    <td>会合名</td>
                                                    <td><?php echo $meeting_name; ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                    if ($event_note) {
                                                        ?>
                                                <tr class="remarks">
                                                    <td>備考</td>
                                                    <td><?php echo $event_note; ?></td>
                                                </tr>
                                                <?php
                                                    } ?>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="event-image">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="article-image">
                                                <?php
                                                    if (has_post_thumbnail()) { // 投稿にアイキャッチ画像が割り当てられているかチェックします。
                                                        $url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                                        echo "<img src='{$url}' />";
                                                    }
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="release-day">掲載日：<time datetime="<?php the_time('Y-n-d'); ?>"><?php the_time('Y.m.d') ?></time></div>
                                </div>
                                <div class="modal_close">
                                    <div class="black-btn">
                                        閉じる<span>×</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- モーダル --------------------------->
                    <?php
                        $count++;
                        endwhile;
                        wp_reset_postdata();

                            $publish_posts = $query->found_posts;
                            if ($publish_posts > $posts_per_page) :
                    ?>
                        <div class="more_disp event_more_disp">
                            <button data-post="event">もっと見る</button>
                        </div>
                        <div class="more_disp event_more_disp all-article">
                            <button data-post="event" data-postnum="<?php echo $publish_posts; ?>">直近の情報 <?php echo $publish_posts; ?>件 すべて表示する</button>
                        </div>
                    <?php
                            endif;
                        else :
                            wp_reset_postdata();
                            echo "<p>最新の記事はありません</p>";
                    endif;
                    ?>
                    <?php //if ($query->have_posts()):?>

                    <?php //endif;?>
                </div>
                <input type="radio" name="tab_name" id="calendar" >
                <label class="tab_class calendar-tab" for="calendar">カレンダー表示</label>
                <div class="content_class calendar_content">
                    <div class="calendar_event">
                        <div class="calendar">
                            <div class="calendar-show px-0 px-3"></div>
                        </div>
                        <div class="info-list">
                            <div class="article-date" id="calender-date">
                                <?php
                                    $this_year = date("Y");
                                    $month = date("n");
                                    $today = date("j");
                                    $week = ['日', '月', '火', '水', '木', '金', '土'];
                                    $day_of_week = date('w');
                                    echo $month.'<span>月</span>'.$today.'<span>日</span><span>（'.$week[$day_of_week].'）</span>'
                                ?>
                            </div>
                            <div id="detail-box"><!-- 記事データを表示 --></div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div id="happy-news" class="content happy-news">
                <h2>
                    HAPPY NEWS<span>バックナンバー</span>
                </h2>
                <div class="bnr-list">
                    <?php
                        $args = array(
                            'posts_per_page' => 10 ,
                            'post_type' => 'happy-news',
                            'meta_key'  => 'order', // カスタムフィールドのキー
                            'orderby' => 'meta_value_num', // 上で指定したカスタムフィールドの値の数値で並び替え
                            'order' => 'ASC', // 昇順で並べる
                        );

                        $query = new WP_Query($args);
                        if ($query->have_posts()) :

                            while ($query->have_posts()) :
                                $query->the_post();
                                $postid = get_the_ID();

                    ?>
                        <!-- 記事の数だけ繰り返し表示される部分 -->
                        <div class="bnr-list__detail">
                            <a href="<?php echo $cfs->get('pdf'); ?>" target="_blank" rel="noopener">
                                <div class="article-image">
                                    <?php
                                        if (has_post_thumbnail()) { // 投稿にアイキャッチ画像が割り当てられているかチェックします。
                                            $url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                            echo "<img src='{$url}' />";
                                        }
                                    ?>
                                </div>
                                <div class="article__detail">
                                    <div class="article__detail__postday">
                                        <p><?php echo $cfs->get('date'); ?> 発刊</p>
                                    </div>
                                    <div class="article__detail__title">
                                        <p><?php the_title(); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php
                            endwhile;
                            wp_reset_postdata();
                            else :
                                wp_reset_postdata();
                                echo "<p>最新の記事はありません</p>";
                        endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_template_part('template-parts/footer-menus-widgets'); ?>

<?php
get_footer();
?>
