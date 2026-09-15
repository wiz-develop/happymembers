<div id="page-login" class="page-web-registration">
<?php
/*
 * Template Post Type: page
 * Template Name: パスワード再発行依頼（入力内容確認）
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
// セッションがからの場合ログイン画面へリダイレクトをする。
session_check();
if (!$_SESSION['temp']) {
    wp_redirect(get_home_url().'/login');
}
get_header(); ?>
    <div class="mod-body">
        <div class="page-title">
            <h1 class="page-header_pageTitle"><?php the_title(); ?></h1>
            <div class="content">
                <div class="page-note">
                    <p>入力いただいた内容は以下の通りです。誤りがないかご確認ください。</p>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="form-content">
                <div>
                    <p class="form-item"><label>ログインID</label></p>
                    <div class="form-input">
                        <div class="input-answer"><?php echo $_SESSION['temp']['login_id'];?></div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>氏名</label></p>
                    <div class="form-input">
                        <div class="input-answer"><?php echo $_SESSION['temp']['mbr_nm']; ?></div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>フリガナ</label></p>
                    <div class="form-input">
                        <div class="input-answer"><?php echo $_SESSION['temp']['mbr_knm'];?></div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>生年月日</label></p>
                    <div class="form-input">
                        <div class="birthday">
                            <div class="birthday__year">
                                <?php echo $_SESSION['temp']['year_birth']; ?>
                                <span>年<span>
                            </div>
                            <div class="birthday__day">
                                <?php echo $_SESSION['temp']['month_birth'];?>
                                <span>月<span>
                            </div>
                            <div class="birthday__day">
                                <?php echo $_SESSION['temp']['day_birth'];?>
                                <span>日<span>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>メールアドレス</label></p>
                    <div class="form-input">
                        <div class="input-answer"><?php echo $_SESSION['temp']['mail'];?></div>
                    </div>
                </div>
                <div class="btn-list">
                    <button class="black-btn" id="btn-reissue_pass">
                        送信
                    </button>
                    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
                        <div class="white-btn">
                            入力内容を修正する
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
.sp-order {
    display: none;
}
</style>

<script>
    jQuery(function($){
        // WordPressでAjaxを使用する場合、urlにはadmin-ajax.phpの絶対パスを指定
        // const ajaxUrl = '<?php // echo admin_url('admin-ajax.php');?>';
        // const ajaxUrl = '/html/cms/wp-admin/admin-ajax.php';
        $('button#btn-reissue_pass').on('click', function(){
            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                cache: false,
                data: {
                    action : 'reissue_pass',
                },
            }).done(function(data) {
                // console.log(data);
                window.location.href = '<?php echo home_url();?>/pass-reissue/completion/?result='+data.code;
                // window.location.href = '<?php // echo home_url();?>/pass-reissue/completion/?result=1';
            }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("function       : reissue_pass");
                console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                console.log("textStatus     : " + textStatus);
                console.log("errorThrown    : " + errorThrown.message);
            });
        });
    });
</script>