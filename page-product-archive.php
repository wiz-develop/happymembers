<?php
/*
 * Template Post Type: page
 * Template Name: 商品購入履歴
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
get_header(); ?>

<?php
// 購入履歴一覧から商品を取得
$order_list = fetch_orders();?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/common.js?<?php // echo date("ymdHis", filemtime(get_stylesheet_directory_uri()."/assets/js/common.js"));?>" media="all" />
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> -->

<div id="page-product" class="page-product-archive">
    <div class="mod-body">
        <div class="content flex-side">
            <div class="flame-side">
                <?php get_template_part('/assets/template/product-nav');?>
            </div>
            <div class="flame-body">
                <div class="page-title">
                    <h1 class="page-header_pageTitle"><?php the_title(); ?></h1>
                </div>
                <?php if (!isset($order_list['-1'])) : ?>
                    <?php foreach ($order_list as $order) : ?>
                        <div class="product-archive-list" data-order="<?php echo $order['id'] ?>">
                            <div class="product-archive">
                                <div class="product-archive__header">
                                    <div class="product-archive__header__order-detail">
                                        <div class="product-archive__header__order-detail__item">
                                            ご注文日：<?php echo $order['created_at'];?>
                                        </div>
                                        <div class="product-archive__header__order-detail__item">
                                            ご購入金額：¥<?php echo $order['sum_total'];?>
                                        </div>
                                    </div>
                                    <div class="product-archive__header__order-detail">
                                        <div class="product-archive__header__order-detail__item product-namber">
                                            ご注文番号：<?php echo $order['order_code'];?>
                                        </div>
                                        <div class="btn-list">
                                            <div class="black-btn detail-btn">
                                                詳細
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-archive__shipping-status">
                                    出荷状況 /<span class="shipped"><?php echo check_status($order['order_code']); ?></span>
                                </div>
                                <div class="product-archive__body">
                                    <?php foreach ($order['order_products'] as $order_product) : ?>
                                        <div class="product-archive__body__card">
                                            <div class="product-category <?php echo $order_product['slug']; ?>">
                                                <?php echo $order_product['category'];?>
                                            </div>
                                            <div class="product-archive__body__card__detail">
                                                <?php $image = $cfs->get('image', $order_product['post_id']); ?>
                                                <div class="product-archive__body__card__detail__image">
                                                    <?php if ($image) : ?>
                                                        <img src="<?php echo $image;?>" alt="<?php echo $order_product['product_name']; ?>">
                                                    <?php else : ?>
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/product/no-image.png" alt="no image">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="product-archive__body__card__detail__title">
                                                    <?php echo $order_product['product_name']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach ; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ;?>
                <?php else : ?>
                    <div class="m-5">購入済みの商品はありません。</div>
                <?php endif ; ?>
                <!-- <div class="more-btn">
                    もっと見る
                </div> -->
                <div class="order-note">
                    <p>ご注文いただいた商品の変更、キャンセルは午前10:30までの受付けとさせていただきます。<br>変更等がある場合は下記のフリーダイヤルにお電話ください。</p>
                    <div class="tel">
                        <div class="tel-icon">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/tel-icon.png">
                        </div>
                        <span>0120-198-141</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>


<script>
    // WordPressでAjaxを使用する場合、urlにはadmin-ajax.phpの絶対パスを指定
    // const ajaxUrl = '<?php // echo admin_url('admin-ajax.php');?>';

    jQuery(function($) {
        // 詳細ボタンを押したときの挙動
        $('.black-btn.detail-btn').on('click', function() {
            var orderId = $(this).parents('.product-archive-list').data('order');
            location.href = '<?php echo get_home_url();?>/product-archive/detail/?order_id=' +  encodeURIComponent(orderId);
        })
    })
</script>
