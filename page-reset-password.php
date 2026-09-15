<div id="page-login" class="page-web-registration">
<?php
/*
 * Template Post Type: page
 * Template Name: パスワード再設定
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

get_header(); ?>
    <div class="mod-body">
        <div class="page-title">
            <h1 class="page-header_pageTitle"><?php the_title(); ?></h1>
            <div class="content">
                <div class="page-note">
                    <p>以下よりパスワードの再設定を行なってください。</p>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="form-content">
                <div>
                    <p class="form-item"><label>パスワード</label></p>
                    <div class="form-input">
                        <input type="text" name="pass" value="" size="60" placeholder="8~16字の英数字・記号で入力">
                    </div>
                </div>
                <div class="btn-list">
                    <a href="<?php echo site_url(); ?>/reset-password/completion/">
                        <div class="black-btn">
                            送信
                        </div>
                    </a>
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
