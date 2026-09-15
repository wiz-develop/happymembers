<?php
/*
 * Template Post Type: page
 * Template Name: web会員ご登録情報変更内容確認画面
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
get_header();
session_check();?>

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
                <div class="web-member">
                    <div class="item-content">
                        <table>
                            <tr>
                                <td>会員ID</td>
                                <td>
                                    <div class="id-namber">
                                        <label>ハッピー会員</label>
                                        <span><?php echo $_SESSION['temp']['happy_id']; ?></span>
                                    </div>
                                    <div class="id-namber">
                                        <label>エクセレント</label>
                                        <span><?php echo $_SESSION['temp']['excellent_id']; ?></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>パスワード</td>
                                <td>●●●●●●●●●</td>
                            </tr>
                            <tr>
                                <td class="pb-3">メールアドレス</td>
                                <td class="pb-3"><?php echo $_SESSION['temp']['mail']; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="link-list">
                    <div class="link-bnr">
                        <button class="black-btn" id="btn-update">
                            送信
                        </button>
                    </div>
                    <div class="link-bnr">
                        <a href="<?php echo get_home_url(); ?>/member-info/edit">
                            <div class="white-btn">
                                修正する
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>

<script>
    jQuery(function($){
        const ajaxUrl = '<?php echo admin_url('admin-ajax.php');?>';
        $('button#btn-update').on('click', function(){
            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                cache: false,
                data: {
                    action : 'update_user',
                },
                // dataType:'json',
            }).done(function(data) {
                console.log(data);
                window.location.href = '<?php echo get_home_url();?>/member-info/edit/completion/?result='+data.code;
            }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("function       : update_user");
                console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                console.log("textStatus     : " + textStatus);
                console.log("errorThrown    : " + errorThrown.message);
            });
        });
    });
</script>