<div id="page-login" class="page-web-registration">
<?php
/*
 * Template Post Type: page
 * Template Name: WEB会員登録（仮登録完了）
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
if (isset($_GET['result'])) {
    $result_register = intval($_GET['result']);
}?>
    <div class="mod-body">
        <div class="page-title">
            <?php if ($result_register === 0) : ?>
                <h1 class="page-header_pageTitle"><?php the_title(); ?></h1>
            <?php else : ?>
                <h1 class="page-header_pageTitle">仮会員登録失敗</h1>
            <?php endif ; ?>
            <div class="content">
                <div class="page-note">
                    <?php if ($result_register === 0) : ?>
                        <p>WEB会員仮登録が完了しました。<br>本登録用のURLをお送りしますのでメールが届きましたら<br>URLをクリックしてWEB会員の本登録を行なってください。</p>
                    <?php else : ?>
                        <p>WEB会員仮登録が正常に完了しませんでした。<br>再度WEB会員登録からやり直してください。</p>
                        <div class="back-link mt-5">
                            <a href="<?php echo site_url(); ?>/web-registration/">
                                <div class="my_submit_btn web-registration white-btn">WEB会員登録</div>
                            </a>
                        </div>
                    <?php endif ; ?>
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