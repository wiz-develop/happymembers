<?php
/*
 * Template Post Type: page
 * Template Name: ボーナス明細
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
require("assets/api/v1/common/bonus.php");

$user = get_member_info();
// var_dump($user['hp']);
$happy_id = '';
$excellent_id = '';
// ハッピー会員のボーナス情報取得
if ($user['happy_id']) {
    $mbr_type = 0;
    $happy_id = $user['hp']['mbr_id'];
    $bonus_hp = get_bonus_hp($happy_id);
}
// エクセレント会員のボーナス情報取得
if ($user['excellent_id']) {
    $mbr_type = 1;
    $excellent_id = $user['ex']['mbr_id'];
    $bonus_ex = get_bonus_ex($excellent_id);
}?>
<div id="page-member-info" class="page-product-archive page-member-info page-bonus">
    <div class="mod-body">
        <div class="content flex-side">
            <div class="flame-side">
                <?php get_template_part('/assets/template/member-nav');?>
            </div>
            <div class="flame-body">
                <div class="page-title">
                    <h1 class="page-title__name"><?php the_title(); ?></h1>
                </div>
                <div class="accordion">
                    <?php if ($happy_id) : ?>
                        <p class="menu accordion-menu d-flex">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/happy-logo.png" alt="ハッピー会員">
                            <span>ハッピーファミリー</span>
                        </p>
                        <div class="accordion-content">
                            <?php if ($bonus_hp['code'] === 0) : ?>
                                <?php foreach ($bonus_hp['bonuses'] as $hp_bonus) : ?>
                                    <div class="bonus-content">
                                        <div class="bonus-content__date">
                                            <div class="year"><?php echo $hp_bonus['year']; ?>年</div>
                                        </div>
                                        <table>
                                            <?php foreach ($hp_bonus['detail'] as $detail) : ?>
                                                <tr>
                                                    <td><?php echo $detail['month']; ?>月</td>
                                                    <td>
                                                        <?php echo $detail['bonus']; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach ; ?>
                                        </table>
                                    </div>
                                <?php endforeach ; ?>
                            <?php else : ?>
                                <div>ボーナス情報を取得できませんでした。</div>
                            <?php endif ;?>
                        </div>
                    <?php endif ; ?>
                    <?php if ($excellent_id) : ?>
                        <p class="menu accordion-menu d-flex">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/excellent-logo.png" alt="エクセレント会員">
                            <span>エクセレント</span>
                        </p>
                        <div class="accordion-content excellent">
                            <?php if ($bonus_ex['code'] === 0) : ?>
                                <?php foreach ($bonus_ex['bonuses'] as $br_id => $ex_bonus_by_br_id) : ?>
                                    <div class="bonus-content">
                                        <p class="menu accordion-menu">
                                            発生手当金 ID:<?php echo $user['ex']['mbr_id']; ?> Br:<?php echo $br_id; ?>
                                        </p>
                                        <?php foreach ($ex_bonus_by_br_id as $year => $ex_bonus_by_year) : ?>
                                            <div class="accordion-content">
                                                <div class="bonus-content__date">
                                                    <div class="year"><?php echo $year; ?>年</div>
                                                    <table><!-- PC用 -->
                                                        <tr class="item">
                                                            <td></td>
                                                            <td>Br<?php echo $br_id; ?></td>
                                                            <td>全ﾌﾞﾗﾝﾁ集計合計</td>
                                                        </tr>
                                                        <?php foreach ($ex_bonus_by_year as $month => $ex_bonus_by_month) : ?>
                                                            <tr>
                                                                <td><?php echo $month; ?>月</td>
                                                                <td>
                                                                    <?php
                                                                    if (isset($ex_bonus_by_month['bonus'])) {
                                                                        echo number_format($ex_bonus_by_month['bonus']);
                                                                    } ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if (isset($ex_bonus_by_month['bonus_total']) && $ex_bonus_by_month['bonus_total']) {
                                                                        echo number_format($ex_bonus_by_month['bonus_total']);
                                                                    } else {
                                                                        echo '-';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach ; ?>
                                                    </table>
                                                    <div class="mobile-nav-toggle">
                                                        <!-- スマホ用 -->
                                                        <div class="branch">
                                                            <div class="branch__namber">
                                                                Br<?php echo $br_id; ?>
                                                            </div>
                                                            <?php foreach ($ex_bonus_by_year as $month => $ex_bonus_by_month) : ?>
                                                                <div class="branch__content">
                                                                    <div class="branch__content__time">
                                                                        <div class="branch__content__time__date">
                                                                            <?php echo $month; ?>月
                                                                        </div>
                                                                        <div class="branch__content__time__bonus">
                                                                        <?php
                                                                        if (isset($ex_bonus_by_month['bonus'])) {
                                                                            echo number_format($ex_bonus_by_month['bonus']);
                                                                        } ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="branch__content__detail">
                                                                        <div class="branch__content__detail__item">
                                                                            全ﾌﾞﾗﾝﾁ集計合計
                                                                        </div>
                                                                        <div class="all-bonus">
                                                                        <?php
                                                                        if (isset($ex_bonus_by_month['bonus_total']) && $ex_bonus_by_month['bonus_total']) {
                                                                            echo number_format($ex_bonus_by_month['bonus_total']);
                                                                        } else {
                                                                            echo '-';
                                                                        }
                                                                        ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach ; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach ; ?>
                                    </div>
                                <?php endforeach ; ?>
                            <?php else : ?>
                                    <div>ボーナス情報を取得できませんでした。</div>
                            <?php endif ;?>
                        </div>
                    <?php endif ;?>
                </div>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>
