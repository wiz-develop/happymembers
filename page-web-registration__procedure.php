<div id="page-login" class="page-procedure">
<?php
/*
 * Template Post Type: page
 * Template Name: WEB会員登録手順
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
        </div>
        <div class="content">
            <div class="input-explanation">
                <h2>ログインIDについて</h2>
                <div class="input-explanation__detail">
                    <p>英字を含む6桁以上30桁までの英語小文字・半角数字をご入力ください。英字は小文字での登録が可能で、<u>大文字は使用することができません。</u></p>
                    <p class="item-name">例）happy8888</p>
                </div>
            </div>
            <div class="input-explanation">
                <h2>パスワードについて</h2>
                <div class="input-explanation__detail">
                    <p>8桁以上16桁までの英語小文字・半角数字・記号をご入力ください。</p>
                    <p class="item-name">例）happy3366##88</p>
                </div>
            </div>
            <div class="input-explanation">
                <h2>会員IDについて</h2>
                <div class="input-explanation__detail">
                    <p>ハッピー商品で会員登録を行った方はハッピーファミリーの会員IDに、エクセレント商品で会員登録を行った方はエクセレントの会員IDに入力を行ってください。両方に会員登録をされている方は両方の会員IDに入力してください。<br>
                        ※ 後からIDを追加することも可能です。
                    </p>
                    <div class="input-explanation__detail__example">
                        <p class="item-name">例）</p>
                        <div class="input-explanation__detail__example__content">
                            <p class="item-name"><label>会員ID</label></p>
                            <div class="input-explanation__detail__example__content__detail">
                                <div class="item-content">
                                    <label>ハッピー会員</label>
                                    <div>989898</div>
                                </div>
                                <div class="item-content" style="margin-bottom: 0;">
                                    <label>エクセレント会員</label>
                                    <div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="order-note">
                        <p>会員IDをお忘れの方はお電話ください。</p>
                        <div class="tel">
                            <div class="tel-icon">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/tel-icon.png">
                            </div>
                            <span>0120-198-141</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="input-explanation">
                <h2>氏名・フリガナ・生年月日について</h2>
                <div class="input-explanation__detail">
                    <p>ご登録済みの会員情報と同じ情報をご入力ください。異なる場合はエラーとなります。</p>
                </div>
            </div>
            <div class="white-btn">
                <a href="<?php echo site_url(); ?>/web-registration/">
                    <span>WEB会員登録へ戻る</span>
                </a>
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
.input-explanation__detail p span {

}
</style>

<script>
    jQuery(function($){
        const $errorLogin = document.getElementById('error-login');
        $('input#login').on('input', function(){
            const $login = $("#login").val();
            console.log($login);
            // if (!$login.match(/[^A-Za-z0-9]+/)
        });


        // バリデーション
        // $('#btn-submit').on('click', function() {
        //     alert("クリックされました");
        //     const $login = $("#login").val();
        // });
    })
</script>