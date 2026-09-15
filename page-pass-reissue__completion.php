<div id="page-login" class="page-web-registration">
<?php
/*
 * Template Post Type: page
 * Template Name: パスワード再発行依頼（送信完了）
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
session_check();
get_header();
if (isset($_GET['result'])) {
    $result_update = intval($_GET['result']);
}?>

    <div class="mod-body">
        <div class="page-title">
            <?php if ($result_update === 0) : ?>
                <h1 class="page-header_pageTitle"><?php the_title(); ?></h1>
            <?php else : ?>
                <h1 class="page-header_pageTitle">仮パスワード送信失敗</h1>
            <?php endif ; ?>
            <div class="content">
                <div class="page-note">
                    <?php if ($result_update === 0) : ?>
                        <p>パスワード再発行依頼を受け付けました。<br>URLをお送りしますのでメールが届きましたら<br>パスワード再設定を行なってください。</p>
                    <?php else : ?>
                        <p>パスワード再発行が正常に完了できませんでした。<br>もう一度再設定画面よりパスワード再設定を行なってください。</p>
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