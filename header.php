<?php
/**
 * Header file for the Twenty Twenty WordPress default theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */
require("assets/api/v1/common/config.php");

global $MEM_COMBINE_STATUS_HPA;
global $MEM_COMBINE_STATUS_EXA;
global $MEM_COMBINE_STATUS_EXD;
global $MEM_COMBINE_STATUS_HPA_EXA;
global $MEM_COMBINE_STATUS_HPA_EXD;

global $MEM_STATUS_ACTIVE;     // 稼働中
global $MEM_STATUS_DORMANT;    // 休眠中
global $MEM_STATUS_WITHDRAWAL; // 退会中

// if (!is_page('会員ログイン') && !is_page('WEB会員登録') && !is_page('WEB会員登録手順') && !is_page('入力内容確認') && !is_page('WEB会員登録完了') && !is_page('パスワード再発行のご依頼') && !is_page('仮登録完了')) {
//     $user = get_member_info();
//     $happy_id = '';
//     $excellent_id = '';
// }
// if (is_user_logged_in()) {
//     $user = get_member_info();
//     $happy_id = '';
//     $excellent_id = '';
// }
session_check();
if (isset($_SESSION['user']['mbr_combine_stat'])) {
    $mbr_combine_stat = $_SESSION['user']['mbr_combine_stat'];
}?>

<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<?php echo my_require_login(); ?>
<?php date_default_timezone_set('Asia/Tokyo'); ?>
    <head>

        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" >
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Cache-Control" content="no-cache">
        <meta http-equiv="Expires" content="0">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
        <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/slick.css" media="all" />
        <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/slick-theme.css" media="all" />
        <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/style.css?ver=20231004" media="all" />
        <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/common.js" media="all" />
        <link rel="profile" href="https://gmpg.org/xfn/11">
        <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/favicon.ico">
        <link rel="apple-touch-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/apple-touch-icon.png">
        <!-- <link rel="icon" type="image/png" href="<?php // echo get_stylesheet_directory_uri();?>/assets/images/common/android-chrome-192x192.png"> -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
        <script> var urlIcon = "<?php echo get_stylesheet_directory_uri();?>/assets/images/common/member-icon_w.png"; </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>


        <?php wp_head(); ?>

    </head>

    <body <?php body_class(); ?>>

        <?php
        wp_body_open();
        ?>

        <header id="site-header" class="header-footer-group" role="banner">

            <div class="header-inner section-inner pb-0 pc-header"><!-- ここからPC用メニュー -->

                <div class="header-titles-wrapper">

                    <?php

                    // Check whether the header search is activated in the customizer.
                    $enable_header_search = get_theme_mod('enable_header_search', true);

                    if (true === $enable_header_search) {
                        ?>

                        <button class="toggle search-toggle mobile-search-toggle" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false">
                            <span class="toggle-inner">
                                <span class="toggle-icon">
                                    <?php twentytwenty_the_theme_svg('search'); ?>
                                </span>
                                <span class="toggle-text"><?php _e('Search', 'twentytwenty'); ?></span>
                            </span>
                        </button><!-- .search-toggle -->

                    <?php
                    } ?>

                    <div class="header-titles">

                        <?php
                            // Site title or logo.
                            twentytwenty_site_logo();

                            // Site description.
                            twentytwenty_site_description();
                        ?>

                    </div><!-- .header-titles -->

                    <div class="membership d-flex">
                        <?php if (isset($_SESSION['user']['mbr_combine_stat'])) : ?>
                            <?php if ($_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXD) : ?>
                                <div class="membership__icon">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/happy-logo.png" alt="ハッピー会員">
                                </div>
                            <?php endif; ?>
                            <?php if ($_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_EXA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA) : ?>
                                <div class="membership__icon">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/excellent-logo.png" alt="エクセレント会員">
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                </div><!-- .header-titles-wrapper -->

                <div class="header-navigation-wrapper align-items-center">

                    <?php
                    if (has_nav_menu('primary') || ! has_nav_menu('expanded')) {
                        ?>

                            <nav class="primary-menu-wrapper" aria-label="<?php esc_attr_e('Horizontal', 'twentytwenty'); ?>" role="navigation">

                                <ul class="primary-menu reset-list-style">

                                <?php
                                if (has_nav_menu('primary')) {
                                    wp_nav_menu(
                                        array(
                                            'container'  => '',
                                            'items_wrap' => '%3$s',
                                            'theme_location' => 'primary',
                                        )
                                    );
                                } elseif (! has_nav_menu('expanded')) {
                                    wp_list_pages(
                                        array(
                                            'match_menu_classes' => true,
                                            'show_sub_menu_icons' => true,
                                            'title_li' => false,
                                            'walker'   => new TwentyTwenty_Walker_Page(),
                                        )
                                    );
                                } ?>

                                </ul>

                            </nav><!-- .primary-menu-wrapper -->

                        <?php
                    }

                    if (true === $enable_header_search || has_nav_menu('expanded')) {
                        ?>

                        <div class="header-toggles hide-no-js" id="header_info" data-combine_stat="<?php echo $_SESSION['user']['mbr_combine_stat']; ?>">
                            <?php if (isset($_SESSION['user'])) : ?>
                                <div class="member text-right">
                                    <div class="member__name">
                                        ようこそ
                                        <?php if (isset($_SESSION['user']['co_nm'])) : ?>
                                            <?php echo $_SESSION['user']['co_nm']; ?>
                                        <?php endif; ?>
                                        <?php if (isset($_SESSION['user']['mbr_nm'])) : ?>
                                            <?php echo $_SESSION['user']['mbr_nm']; ?> 様
                                        <?php endif; ?>
                                    </div>
                                    <div class="member__info">
                                        <!-- ハッピー会員IDがDBに登録されている場合 -->
                                        <?php if (isset($_SESSION['user']['hp']['mbr_id'])) : ?>
                                            <div class="member__info__detail">
                                                <?php echo '<span>【ハッピー会員】</span>'; ?>
                                                <?php
                                                if ($_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXD) {
                                                    echo '<span>ポジション：'.$_SESSION['user']['hp']['pos'].'</span>';
                                                    echo '<span>購入利率：'.$_SESSION['user']['hp']['mbr_grd'].'%</span>';
                                                } else {
                                                    echo '退会中';
                                                } ?>
                                            </div>
                                        <?php endif; ?>
                                        <!-- エクセレント会員IDががDBに登録されている場合  -->
                                        <?php if (isset($_SESSION['user']['ex']['mbr_id'])) : ?>
                                            <div class="member__info__detail">
                                                <?php echo '<span>【エクセレント会員】</span>'; ?>
                                                <?php
                                                if ($_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA) {
                                                    echo '退会中';
                                                } else {
                                                    if ($_SESSION['user']['ex']['mbr_stat'] === $MEM_STATUS_ACTIVE) {
                                                        $status = 'Active';
                                                    } elseif ($_SESSION['user']['ex']['mbr_stat'] === $MEM_STATUS_DORMANT) {
                                                        $status = '休眠中';
                                                    }
                                                    echo '<span>ステイタス：'.$status.'</span>';
                                                } ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="logout">
                                <div id="btn-logout" class="logout-btn">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/logout-icon.png">
                                    ログアウト
                                </div>
                            </div>
                        <?php
                        if (has_nav_menu('expanded')) {
                            ?>
                            <div class="toggle-wrapper nav-toggle-wrapper has-expanded-menu">

                                <button class="toggle nav-toggle" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".close-nav-toggle">
                                    <span class="toggle-inner">
                                        <span class="toggle-icon">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/menu.png">
                                            <span class="toggle-text"><?php _e('Menu', 'twentytwenty'); ?></span>
                                        </span>
                                    </span>
                                </button><!-- .nav-toggle -->

                            </div><!-- .nav-toggle-wrapper -->
                            <?php
                        }

                        if (true === $enable_header_search) {
                            ?>

                            <div class="toggle-wrapper search-toggle-wrapper">

                                <button class="toggle search-toggle desktop-search-toggle" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false">
                                    <span class="toggle-inner">
                                        <?php twentytwenty_the_theme_svg('search'); ?>
                                        <span class="toggle-text"><?php _e('Search', 'twentytwenty'); ?></span>
                                    </span>
                                </button><!-- .search-toggle -->
                            </div>

                            <?php
                        } ?>

                        </div><!-- .header-toggles -->
                        <?php
                    }
                    ?>

                </div><!-- .header-navigation-wrapper -->

            </div><!-- .header-inner（ここまでPC用メニュー） -->

            <div class="mobile-nav-toggle sp-header"><!-- ここからモバイル用メニュー -->
                <div class="sp-nav d-flex justify-content-between align-items-center">
                    <div class="header-titles">
                        <?php
                            // Site title or logo.
                            twentytwenty_site_logo();

                            // Site description.
                            twentytwenty_site_description();
                        ?>
                    </div><!-- .header-titles -->
                    <div class="link-list d-flex">
                        <div class="link-btn text-center">
                            <a href="<?php echo site_url(); ?>/cart/">
                                <div class="link-icon">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/cart-icon.png">
                                    <span class="cart menu-cart">カート</span>
                                </div>
                            </a>
                        </div>
                        <div class="link-btn text-center">
                            <?php
                                $setting_page = get_page_by_path('setting');
                                $setting_page_id = $setting_page->ID;
                                $help_link = CFS()->get('help_link', $setting_page_id);
                            ?>
                            <a href="<?php echo $help_link; ?>" target="_blank">
                                <div class="link-icon">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/help-icon.png">
                                    <span class="help">ヘルプ</span>
                                </div>
                            </a>
                        </div>
                        <div class="toggle-wrapper nav-toggle-wrapper has-expanded-menu">
                            <button class="toggle" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".close-nav-toggle">
                                <span class="toggle-inner">
                                    <span class="toggle-icon">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/menu.png">
                                        <span class="toggle-text"><?php _e('Menu', 'twentytwenty'); ?></span>
                                    </span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="sp-membership d-flex justify-content-between align-items-center">
                    <div class="membership d-flex">
                    <?php if (isset($_SESSION['user']['mbr_combine_stat'])) : ?>
                            <?php if ($_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXD) : ?>
                                <div class="membership__icon">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/happy-logo.png" alt="ハッピー会員">
                                </div>
                            <?php endif; ?>
                            <?php if ($_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_EXA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA) : ?>
                                <div class="membership__icon">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/excellent-logo.png" alt="エクセレント会員">
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="member-name">
                        ようこそ
                        <?php if (isset($_SESSION['user']['co_nm'])) : ?>
                            <?php echo $_SESSION['user']['co_nm']; ?>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['user']['mbr_nm'])) : ?>
                            <?php echo $_SESSION['user']['mbr_nm']; ?> 様
                        <?php endif; ?>
                    </div>
                </div>
                <?php do_action('lightning_header_append'); ?>
            </div>
            <?php
                if (wp_is_mobile()) :
                    if (is_page('product')) :
            ?>
                        <div class="search-product mod-linklist">
                            <details>
                                <summary class="d-flex justify-content-center align-items-center"><span class="pr-4">カテゴリー絞り込み</span><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/narrowing-down.png"></summary>
                                <?php get_template_part('/assets/template/product-nav');?>
                            </details>
                        </div>
            <?php
                    endif;
                endif;
            ?>
            <!-- ここまでモバイル用メニュー -->

            <div class="sub-header-menu">
                <ul class="d-flex justify-content-center align-items-center">
                    <li class="d-flex align-items-center">
                        <a href="<?php echo get_home_url(); ?>">
                            <div class="link-item d-flex align-items-center">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/top.png">
                                <span class="pr-2">トップページ</span>
                            </div>
                        </a>
                    </li>
                    <li class="d-flex align-items-center">
                        <a href="<?php echo get_home_url(); ?>/product/">
                            <div class="link-item d-flex align-items-center">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/purchase-icon.png">
                                <span class="pr-2">商品一覧</span>
                            </div>
                        </a>
                    </li>
                    <li class="d-flex align-items-center">
                        <a href="<?php echo get_home_url(); ?>/cart/">
                            <div class="link-item d-flex align-items-center">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/cart-icon.png">
                                <span class="pr-2 menu-cart">カート</span>
                            </div>
                        </a>
                    </li>
                    <li class="d-flex align-items-center">
                        <a href="<?php echo get_home_url(); ?>/member-info/">
                            <div class="link-item d-flex align-items-center">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon.png">
                                <span class="pr-2">会員情報</span>
                            </div>
                        </a>
                    </li>
                    <li class="d-flex align-items-center">
                        <a href="<?php echo get_home_url(); ?>/document/">
                            <div class="link-item d-flex align-items-center">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/document-icon.png">
                                <span class="pr-2">各種申請書ダウンロード</span>
                            </div>
                        </a>
                    </li>
                    <li class="d-flex align-items-center">
                        <a href="<?php echo $help_link; ?>" target="_blank">
                            <div class="link-item d-flex align-items-center">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/help-icon.png">
                                <span class="pr-2">My pageマニュアル</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <?php
            // Output the search modal (if it is activated in the customizer).
            if (true === $enable_header_search) {
                get_template_part('template-parts/modal-search');
            }
            ?>

        </header><!-- #site-header -->

        <?php
        // Output the menu modal.
        get_template_part('template-parts/modal-menu');
        ?>
</body>
<script>
    jQuery(function() {
        // カートの数量をカウントして表示
        // cartCount(0); // カートの数量をヘッダーに描画する

        $('.logout-btn#btn-logout').on('click', function() {
            $.ajax({
                type: 'GET',
                url: ajaxUrl,
                cache: false,
                data: {
                    'action' : 'do_logout',
                },
            }).done(function(data){
                window.location.href = '<?php echo get_home_url(null, '/login'); ?>';
            }).fail(function (XMLHttpRequest, textStatus, errorThrown){
                console.log("function       : do_logout" );
                console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                console.log("textStatus     : " + textStatus);
                console.log("errorThrown    : " + errorThrown.message);
            });
        })
    })
</script>

<style>
/* TODO: style */
.logout-btn {
    cursor: pointer;
}
</style>