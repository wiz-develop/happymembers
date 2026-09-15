<?php
/*
 * Template Name: 組織図（西岡）
 * Template Post Type: page
 *
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
session_check();?>
<?php
$user = wp_get_current_user();
$user_id = $user->ID;
$happy_id = get_user_meta($user_id, 'happy_id', true);
$excellent_id = get_user_meta($user_id, 'excellent_id', true);
?>

<div id="page-product" class="page-member-info__chart"
    data-happy-id="<?php echo $happy_id;?>"
    data-excellent-id="<?php echo $excellent_id;?>"
>
    <div class="mod-body">
        <div class="content flex-side">
            <div class="flame-side">
                <?php get_template_part('/assets/template/member-nav');?>
            </div>
            <div class="flame-body">
                <div class="page-title">
                    <h1 class="page-title__name"><?php the_title(); ?></h1>
                    <div class="label-detail">
                        <div class="label-detail__active">
                            Active
                        </div>
                        <?php if (isset($_SESSION['info']['ex']["mbr_id"])) : ?>
                        <div class="label-detail__dormant">
                            休眠
                        </div>
                        <?php endif; ?>
                        <div class="label-detail__withdrawal">
                            退会
                        </div>
                    </div>
                </div>
                <div class="chart">
                    <div class="chart__search">
                        <div class="chart__search__function">
                            <div class="chart__search__function__back">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chart/you-icon.png">
                                <span>あなたに戻る</span>
                            </div>
                            <div class="chart__search__function__zoom">
                                <span>拡大</span>
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chart/expansion-icon.png">
                            </div>
                            <div class="chart__search__function__zoom">
                                <span>縮小</span>
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chart/shrink-icon.png">
                            </div>
                        </div>
                        <div class="chart__search__input">
                            <input type="search" name="chart-search" placeholder="ID,名前,フリガナで検索" class="input-area">
                            <input type="submit" value="検索" class="search-btn">
                        </div>
                    </div>
                    <div class="chart__content">
                        <?php if (isset($_SESSION['info']['hp']["mbr_id"])) : ?>
                            <input type="radio" name="tab_name" id="happy" checked>
                            <label class="tab_class list-tab happy-tab" for="happy">ハッピーファミリー</label>
                            <div class="content_class happy">
                                <div class="chart-you">
                                    <div class="chart-you__icon">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chart/you-icon.png">
                                    </div>
                                    <span>あなた</span>
                                </div>
                                <div class="chart-branch">
                                    <div class="chart-branch__hierarchy">
                                        <div class="branch-line">
                                            <div class="branch-line__top">
                                                <div class="branch-line__top__left"></div>
                                                <div class="branch-line__top__right"></div>
                                            </div>
                                            <div class="branch-line__bottom">
                                                <div class="branch-line__bottom__left"></div>
                                                <div class="branch-line__bottom__right"></div>
                                            </div>
                                        </div>
                                        <div class="chart-branch__hierarchy__content">
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="active">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="active">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- 次の3階層表示 -->
                                                <!-- <div class="chart-branch__hierarchy__next">
                                                    続きを見る
                                                </div>
                                                <div class="chart-branch__hierarchy__next">
                                                    ここに.chart-branch を入れる
                                                </div> -->
                                            </div>
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="dormant">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="dormant">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="active">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="active">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chart-branch__hierarchy">
                                        <div class="branch-line">
                                            <div class="branch-line__top">
                                                <div class="branch-line__top__left"></div>
                                                <div class="branch-line__top__right"></div>
                                            </div>
                                            <div class="branch-line__bottom">
                                                <div class="branch-line__bottom__left"></div>
                                                <div class="branch-line__bottom__right"></div>
                                            </div>
                                        </div>
                                        <div class="chart-branch__hierarchy__content">
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="active">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="active">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="dormant">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="dormant">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chart-branch__hierarchy">
                                        <div class="branch-line">
                                            <div class="branch-line__top">
                                                <div class="branch-line__top__left"></div>
                                                <div class="branch-line__top__right"></div>
                                            </div>
                                            <div class="branch-line__bottom">
                                                <div class="branch-line__bottom__left"></div>
                                                <div class="branch-line__bottom__right"></div>
                                            </div>
                                        </div>
                                        <div class="chart-branch__hierarchy__content">
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="dormant">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="dormant">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="active">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="active">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="active">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="active">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['info']['ex']["mbr_id"])) : ?>
                            <input type="radio" name="tab_name" id="excellent">
                            <label class="tab_class list-tab excellent-tab" for="excellent">エクセレント</label>
                            <div class="content_class excellent">
                                <div class="chart-you">
                                    <div class="chart-you__icon">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chart/you-icon.png">
                                    </div>
                                    <span>あなた</span>
                                </div>
                                <div class="chart-branch">
                                    <div class="chart-branch__hierarchy">
                                        <div class="chart-branch__hierarchy__content">
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="active">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="active">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="withdrawal">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="withdrawal">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chart-branch__hierarchy">
                                        <div class="chart-branch__hierarchy__content">
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="active">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="active">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="withdrawal">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="withdrawal">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chart-branch__hierarchy">
                                        <div class="chart-branch__hierarchy__content">
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="active">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="active">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="withdrawal">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="withdrawal">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="radio" name="tab_name" id="excellent-introduction">
                            <label class="tab_class list-tab ei-tab" for="excellent-introduction">エクセレント紹介者</label>
                            <div class="content_class introducer">
                                <div class="chart-you">
                                    <div class="chart-you__icon">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chart/you-icon.png">
                                    </div>
                                    <span>あなた</span>
                                </div>
                                <div class="chart-branch">
                                    <div class="chart-branch__hierarchy">
                                        <div class="branch-line">
                                            <div class="branch-line__top">
                                                <div class="branch-line__top__left"></div>
                                                <div class="branch-line__top__right"></div>
                                            </div>
                                            <div class="branch-line__bottom">
                                                <div class="branch-line__bottom__left"></div>
                                                <div class="branch-line__bottom__right"></div>
                                            </div>
                                        </div>
                                        <div class="chart-branch__hierarchy__content">
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="active">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="active">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="dormant">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="dormant">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="active">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="active">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="active">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="active">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chart-branch__hierarchy__content__item">
                                                <div class="modal_trigger">
                                                    <div class="branch-line">
                                                        <div class="branch-line__top">
                                                            <div class="branch-line__top__left"></div>
                                                            <div class="branch-line__top__right"></div>
                                                        </div>
                                                        <div class="branch-line__bottom">
                                                            <div class="branch-line__bottom__left"></div>
                                                            <div class="branch-line__bottom__right"></div>
                                                        </div>
                                                    </div>
                                                    <div class="withdrawal">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                    </div>
                                                    <span>山田　太郎</span>
                                                </div>
                                                <!-- モーダル --------------------------->
                                                <div class="modal_box">
                                                    <div class="modal_bg"></div>
                                                    <div class="modal_inner">
                                                        <div class="modal_block">
                                                            <div class="modal-member-info">
                                                                <div class="withdrawal">
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                                                                </div>
                                                                <span>山田　太郎</span>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td>会員ID</td>
                                                                    <td>123456</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Br</td>
                                                                    <td>001</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>法人名</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>肩書き</td>
                                                                    <td>XXXXXXXXX</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>ポジション</td>
                                                                    <td>営業所</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>取引利率</td>
                                                                    <td>71</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>登録日</td>
                                                                    <td>1998.10.15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>状況</td>
                                                                    <td>退会</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>紹介者名</td>
                                                                    <td>佐々木　花子</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal_close">
                                                            <div class="black-btn">
                                                                閉じる<span>×</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>

<script>
    jQuery(function($){
        // ハッピー紹介者：ページロード時に読み込む
        $happyId = $('#page-product').data('happy-id');
        if ($happyId) {
            getIntroducerHpJs($happyId);
        }

        $excellentId = $('#page-product').data('excellent-id');
        $brId = 0;
        console.log($excellentId);
        if ($excellentId) {
            getIntroducerExJs($excellentId, $brId);
            getBinaryExJs($excellentId, $brId);
        }


        /*-------------------------------------------*/
        /*  組織図(紹介者) hp
        /*-------------------------------------------*/
        function getIntroducerHpJs($targetHpId) {
            $.ajax({
                type: 'GET',
                url: ajaxUrl,
                cache: false,
                data: {
                    'action' : 'get_introducer_hp',
                    'hp_id' : $targetHpId,
                },
            }).done(function(data) {
                console.log(data)
            }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("function       : get_introducer_hp");
                console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                console.log("textStatus     : " + textStatus);
                console.log("errorThrown    : " + errorThrown.message);
            });
        }

        /*-------------------------------------------*/
        /*  組織図(紹介者) ex
        /*-------------------------------------------*/
        function getIntroducerExJs($targetExId, $targetBrId) {
            $.ajax({
                type: 'GET',
                url: ajaxUrl,
                cache: false,
                data: {
                    'action' : 'get_introducer_ex',
                    'ex_id' : $targetExId,
                    'br_id' : $targetBrId,
                },
            }).done(function(data) {
                console.log(data)
            }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("function       : get_introducer_ex");
                console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                console.log("textStatus     : " + textStatus);
                console.log("errorThrown    : " + errorThrown.message);
            });
        }
        /*-------------------------------------------*/
        /*  組織図(バイナリ) bi
        /*-------------------------------------------*/
        function getBinaryExJs($targetExId, $targetBrId) {
            $.ajax({
                type: 'GET',
                url: ajaxUrl,
                cache: false,
                data: {
                    'action' : 'get_binary_ex',
                    'ex_id' : $targetExId,
                    'br_id' : $targetBrId,
                },
            }).done(function(data) {
                console.log(data)
            }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("function       : get_binary_ex");
                console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                console.log("textStatus     : " + textStatus);
                console.log("errorThrown    : " + errorThrown.message);
            });
        }
    })
</script>
