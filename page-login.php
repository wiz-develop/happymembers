<div id="page-login">
<?php
/*
 * Template Post Type: page
 * Template Name: ログイン
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
// require_once("cms/wp-content/themes/happy-members/functions.php");
if (isset($_POST['my_sign_in'])) {
    $res = my_user_login();
}
if (!isset($_SESSION)) {
    session_start();
}

get_header(); ?>
    <div class="mod-body">
        <div class="page-title">
            <h1 class="page-header_pageTitle"><?php the_title(); ?></h1>
        </div>
        <div class="content">
            <form class="my_form" name="my_login_form" id="my_login_form" action="" method="post" novalidate="novalidate">
                <?php wp_nonce_field('login', 'login_nonce') ?>
                <div class="col-11 col-md-6 d-flex flex-column align-items-center ml-auto mr-auto">
                    <div class="login-item">
                        <div class="login-item__icon">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/login-icon.png" alt="ログインID">
                        </div>
                        <input id="login_user" class="" name="user_login" type="text" placeholder="ログインID" required
                            <?php if (isset($_SESSION['temp']['user_login'])) :?>
                                value="<?php echo $_SESSION['temp']['user_login'] ?>"
                            <?php endif;?>
                        >
                    </div>
                    <div class="error-message">
                        <?php if (!empty($res['error']['user_login'])): ?>
                            <?php echo $res['error']['user_login']; ?>
                        <?php endif; ?>
                    </div>
                    <div class="login-item">
                        <div class="login-item__icon">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/lock-icon.png" alt="パスワード">
                        </div>
                        <input id="login_password" class="" name="user_pass" id="user_pass" type="password" value="" placeholder="パスワード" required>
                        <input type="hidden" name="referer"
                            <?php if (isset($_SESSION['temp']['user_pass'])) :?>
                                value="<?php echo $_SESSION['temp']['user_pass'] ?>"
                            <?php endif;?>
                        >
                        <p id="visible-button">表示</p>
                    </div>
                    <div class="error-message">
                        <?php if (!empty($res['error']['user_pass'])): ?>
                            <?php echo $res['error']['user_pass']; ?>
                        <?php endif; ?>
                    </div>
                    <u class="my_forgot_pass">
                        <a href="<?php echo site_url(); ?>/pass-reissue/">
                            <span class="text-danger">パスワードをお忘れですか？</span>
                        </a>
                    </u>
                </div>
                <div class="btn-list text-center">
                    <div class="black-btn mb-4">
                        <button type="submit" name="my_sign_in" class="my_submit_btn" value="login" class="btn mt-2 login">ログイン</button>
                    </div>
                    <div class="white-btn">
                        <a href="<?php echo site_url(); ?>/web-registration/">
                            <div class="my_submit_btn web-registration">WEB会員登録</div>
                        </a>
                    </div>
                </div>
        </form>
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