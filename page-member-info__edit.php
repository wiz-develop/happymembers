<?php
/*
 * Template Post Type: page
 * Template Name: web会員ご登録情報変更画面
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
if (isset($_POST['btn_confirm'])) {
    $error = edit_user_info();
}
get_header();

// ハッピー、エクセレント会員ID
$user = get_member_info();?>

<div id="page-member-info" class="page-product-archive page-member-info member-info-edit">
    <div class="mod-body">
        <div class="content flex-side">
            <div class="flame-side">
                <?php get_template_part('/assets/template/member-nav');?>
            </div>
            <div class="flame-body">
                <div class="page-title">
                    <h1 class="page-title__name"><?php the_title(); ?></h1>
                </div>
                <form class="form-content" method="post">
                    <div class="web-member">
                        <div class="item-content">
                            <table>
                                <tr>
                                    <td>会員ID</td>
                                    <td>
                                        <div class="id-namber">
                                            <label>ハッピー会員ID</label>
                                            <input type="text" name="happy_id" size="60" placeholder="6桁の数字で入力"
                                                    <?php if (isset($_SESSION['temp']['happy_id'])) : ?>
                                                        value="<?php echo $_SESSION['temp']['happy_id']; ?>"
                                                    <?php else : ?>
                                                        value="<?php echo $user['happy_id']; ?>"
                                                    <?php endif ; ?>
                                                >
                                            <div class="error-message">
                                                <span>
                                                    <?php
                                                    if (isset($error['happy_id'])) {
                                                        echo $error['happy_id'].'<br>';
                                                    }
                                                    ?>
                                                </span>
                                                <span>
                                                    <?php
                                                    if (isset($error['registration_info_hp'])) {
                                                        echo $error['registration_info_hp'].'<br>';
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="id-namber">
                                            <label>エクセレント会員ID</label>
                                            <input type="text" name="excellent_id" size="60" placeholder="6桁の数字で入力"
                                                    <?php if (isset($_SESSION['temp']['excellent_id'])) : ?>
                                                        value="<?php echo $_SESSION['temp']['excellent_id']; ?>"
                                                    <?php else : ?>
                                                        value="<?php echo $user['excellent_id']; ?>"
                                                    <?php endif ; ?>
                                                >
                                            <div class="error-message">
                                                <span>
                                                    <?php
                                                    if (isset($error['error_both'])) {
                                                        echo $error['error_both'].'<br>';
                                                    }
                                                    ?>
                                                </span>
                                                <span>
                                                    <?php
                                                    if (isset($error['excellent_id'])) {
                                                        echo $error['excellent_id'].'<br>';
                                                    }
                                                    ?>
                                                </span>
                                                <span>
                                                    <?php
                                                    if (isset($error['registration_info_ex'])) {
                                                        echo $error['registration_info_ex'].'<br>';
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>パスワード</td>
                                    <td>
                                        <div class="change-info">
                                            <p class="note mb-1">パスワードをご変更する場合のみご入力ください。</p>
                                            <div class="change-info__content">
                                                <label for="change_pass">変更後パスワード</label>
                                                <input type="text" name="pass" size="60"
                                                    value="" placeholder="英語小文字・半角数字・記号8~16文字で入力">
                                                <div class="error-message">
                                                    <?php
                                                    if (isset($error['pass'])) {
                                                        echo $error['pass'];
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="change-info__content">
                                                <label for="change_pass_con">確認用（変更後パスワードを再度入力してください。）</label>
                                                <input type="text" name="confirmation_pass" size="60"
                                                    placeholder="英語小文字・半角数字・記号8~16文字で入力">
                                                <div class="error-message">
                                                    <?php
                                                    if (isset($error['confirmation_pass'])) {
                                                        echo $error['confirmation_pass'];
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>メールアドレス</td>
                                    <td>
                                        <div class="change-info">
                                            <p class="note mb-1">ご登録いただいたメールアドレス宛に確認メールが届きます。</p>
                                            <div class="change-info__content">
                                                <label for="change_email">メールアドレス</label>
                                                <input type="text" name="mail" size="60"
                                                    <?php if (isset($_SESSION['temp']['mail'])) : ?>
                                                        value="<?php echo $_SESSION['temp']['mail']; ?>"
                                                    <?php else : ?>
                                                        value="<?php echo $user['email']; ?>"
                                                    <?php endif ; ?>
                                                >
                                                <div class="error-message">
                                                    <?php
                                                    if (isset($error['mail'])) {
                                                        echo $error['mail'];
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="change-info__content">
                                                <label for="change_email_con">メールアドレス（確認用）</label>
                                                <input type="text" name="confirmation_mail" size="60"
                                                    <?php if (isset($_SESSION['temp']['confirmation_mail'])) : ?>
                                                        value="<?php echo $_SESSION['temp']['confirmation_mail']; ?>"
                                                    <?php else : ?>
                                                        value="<?php echo $user['email']; ?>"
                                                    <?php endif ; ?>
                                                >
                                                <div class="error-message">
                                                    <?php
                                                    if (isset($error['confirmation_mail'])) {
                                                        echo $error['confirmation_mail'];
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="link-list">
                        <div class="link-bnr">
                            <button class="black-btn" type="submit" name="btn_confirm" value="change-info">
                                入力内容を確認する
                            </button>
                        </div>
                        <div class="link-bnr">
                            <a href="<?php echo get_home_url(); ?>/member-info/">
                                <div class="white-btn">
                                    会員登録情報へ戻る
                                </div>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>

<script>
    // jQuery(function($){
    //     const $errorLogin = document.getElementById('error-login');
    //     $('input#login').on('input', function(){
    //         const $login = $("#login").val();
    //         console.log($login);
    //         // if (!$login.match(/[^A-Za-z0-9]+/)
    //     });
    // })
</script>