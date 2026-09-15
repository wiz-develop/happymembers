<div id="page-login" class="page-web-registration">
<?php
/*
 * Template Post Type: page
 * Template Name: WEB会員登録（本登録完了）
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

if (isset($_GET['_wpnonce'])) {
    $wpnonce = $_GET['_wpnonce'];
    $result = main_member_registration($wpnonce);
}?>
    <div class="mod-body">
        <div class="page-title">
            <h1 class="page-header_pageTitle"><?php the_title(); ?></h1>
            <div class="content">
                <div class="page-note">
                    <?php if ($result['message']) :?>
                        <p><?php echo $result['message']; ?></p>
                    <?php else : ?>
                        <p>予期せぬエラーです<br>管理者へお問い合わせください。</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="form-content">
                <div class="btn-list">
                    <?php if ($result['code'] === 0) :?>
                        <a href="<?php echo site_url(); ?>/login/">
                            <div class="black-btn">
                                ログイン画面へ
                            </div>
                        </a>
                    <?php elseif ($result['code'] === 1) :?>
                        <a href="<?php echo site_url(); ?>/web-registration/">
                            <div class="white-btn">
                                WEB会員登録
                            </div>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="before-login">
    <?php get_footer(); ?>
</div>

<style>
html {
    padding-bottom: 0 !important;
}
body {
    margin: 0 !important;
}
</style>
