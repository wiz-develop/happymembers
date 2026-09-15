<div id="page-login" class="page-web-registration confirmation">
<?php
/*
 * Template Post Type: page
 * Template Name: WEB会員登録（入力内容確認）
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
// セッションがカラの場合ログイン画面へリダイレクトをする。
session_check();
if (!$_SESSION['sign_up']) {
    wp_redirect(get_home_url().'/login');
}
get_header();

if (isset($singUp)) {
    $result = save_signup();
}?>
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
                        <div class="input-answer"><?php echo $_SESSION['sign_up']['login_id'];?></div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>パスワード</label></p>
                    <div class="form-input">
                        <div class="input-answer"><?php echo $_SESSION['sign_up']['pass'];?></div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>会員ID</label></p>
                    <div class="form-input">
                        <div class="form-input__id">
                            <label>ハッピー会員</label>
                            <div class="input-answer"><?php echo $_SESSION['sign_up']['happy_id'];?></div>
                        </div>
                        <div class="form-input__id">
                            <label>エクセレント会員</label>
                            <div class="input-answer"><?php echo $_SESSION['sign_up']['excellent_id'];?></div>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>氏名</label></p>
                    <div class="form-input">
                        <div class="input-answer"><?php echo $_SESSION['sign_up']['mbr_nm']; ?></div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>フリガナ</label></p>
                    <div class="form-input">
                        <div class="input-answer"><?php echo $_SESSION['sign_up']['mbr_knm'];?></div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>生年月日</label></p>
                    <div class="form-input">
                        <div class="birthday">
                            <div class="birthday__year">
                                <?php echo $_SESSION['sign_up']['year_birth']; ?>
                                <span>年<span>
                            </div>
                            <div class="birthday__day">
                                <?php echo $_SESSION['sign_up']['month_birth'];?>
                                <span>月<span>
                            </div>
                            <div class="birthday__day">
                                <?php echo $_SESSION['sign_up']['day_birth'];?>
                                <span>日<span>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>メールアドレス</label></p>
                    <div class="form-input">
                        <div class="input-answer"><?php echo $_SESSION['sign_up']['mail'];?></div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>プライバシーポリシー</label></p>
                    <div class="form-input">
                        <div class="input-answer">プライバシーポリシーに同意する</div>
                    </div>
                </div>
                <div class="btn-list">
                    <button class="black-btn" id="btn-create">
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
        $('button#btn-create').on('click', function(){
            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                cache: false,
                data: {
                    action : 'save_signup',
                },
                // dataType : 'json',
            }).done(function(data) {
                console.log(data);
                window.location.href = '<?php echo get_home_url();?>/web-registration/completion/?result='+data.code;
            }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("function       : save_signup");
                console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                console.log("textStatus     : " + textStatus);
                console.log("errorThrown    : " + errorThrown.message);
            });
        });
    });
</script>
