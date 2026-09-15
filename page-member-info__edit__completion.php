<?php
/*
 * Template Post Type: page
 * Template Name: web会員ご登録情報変更完了画面
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
session_check();
$_SESSION['user'] = [];
if (isset($_GET['result'])) {
    $result_update = intval($_GET['result']);
}
get_member_info();?>

<div id="page-member-info" class="page-product-archive page-member-info member-info-edit">
    <div class="mod-body">
        <div class="content flex-side">
            <div class="flame-side">
                <?php get_template_part('/assets/template/member-nav');?>
            </div>
            <div class="flame-body">
                <div class="page-title">
                    <?php if ($result_update === 0) : ?>
                        <h1 class="page-title__name"><?php the_title(); ?></h1>
                    <?php else : ?>
                        <h1 class="page-header_pageTitle">WEB会員情報変更失敗</h1>
                    <?php endif ; ?>
                </div>
                <div class="web-member">
                    <div class="item-content">
                        <?php if ($result_update === 0) : ?>
                            <p>web会員ご登録情報の変更が完了しました。</p>
                        <?php else : ?>
                            <p>WEB会員情報変更が正常に完了しませんでした。<br>再度WEB会員情報変更ページからやり直してください。</p>
                        <?php endif ; ?>
                    </div>
                </div>
                <div class="link-list">
                    <div class="link-bnr">
                        <a href="<?php echo get_home_url(); ?>/member-info/">
                            <div class="black-btn">
                                会員登録情報へ
                            </div>
                        </a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>
