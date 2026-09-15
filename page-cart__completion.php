<?php
/*
 * Template Post Type: page
 * Template Name: ご注文完了
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

<div id="page-product" class="page-cart__confirmation order-completion">
    <div class="mod-header">
        <div class="content">
            <div class="page-title">
                <h1 class="page-header_pageTitle"><?php the_title(); ?></h1>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/buy-step/order-completion.png">
            </div>
        </div>
    </div>
    <div class="mod-body">
        <div class="content">
            <div class="comment">
                <p>ご注文ありがとうございました。<br>ご注文内容は自動返信メールにてお送りいたします。</p>
            </div>
            <div class="back-link">
                <a href="<?php echo site_url(); ?>/product/">
                    <div class="black-btn">
                        商品一覧へ
                    </div>
                </a>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>
