<?php
/**
 * The template for displaying single posts and pages.
 *
 * Template Name: 商品購入履歴詳細
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */
require_once("cms/wp-content/themes/happy-members/functions.php");
get_header();
?>
<?php
// id受取
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
}
// 注文商品を取得
$order_products = fetch_order_products($order_id);?>

<div id="page-product" class="single-product product-archive__detail">
    <div class="mod-body">
        <div class="content flex-side">
            <div class="flame-side">
                <?php get_template_part('/assets/template/product-nav');?>
            </div>
            <div class="flame-body">
                <?php foreach ($order_products as $order_product) : ?>
                    <?php $image = $cfs->get('image', $order_product['post_id']);?>
                    <div class="product">
                        <div class="product__image">
                            <div class="product-category <?php echo $order_product['slug']; ?>">
                                <?php echo $order_product['category']; ?>
                            </div>
                            <div class="image">
                                <?php if ($image) : ?>
                                    <img src="<?php echo $image; ?>" alt="<?php the_title(); ?>">
                                <?php else : ?>
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/product/no-image.png" alt="no image">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="product__detail">
                            <div class="product__detail__namber">
                                商品番号：<?php echo $order_product['product_code']; ?>
                            </div>
                            <div class="product__detail__name">
                                <h1><?php echo $order_product['product_name']; ?></h1>
                            </div>
                            <div class="product__detail__txt">
                                <table>
                                    <tr>
                                        <td class="item-name">購入金額</td>
                                        <td class="item-detail price">¥ <?php echo $order_product['subtotal']; ?>
                                        <span>(税込)</span></td>
                                    </tr>
                                    <tr>
                                        <td class="item-name">価格</td>
                                        <td class="item-detail">¥ <?php echo $order_product['purchase_price']; ?>
                                        <span>(税込)</span></td>
                                    </tr>
                                    <tr>
                                        <td class="item-name">数量</td>
                                        <td class="item-detail"><?php echo $order_product['quantity']; ?>
                                        <span>個</span></td>
                                    </tr>
                                    <tr>
                                        <td class="item-name">取引区分</td>
                                        <td class="item-detail"><?php echo $order_product['transaction']; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach ;?>
                <div class="order-btnlist">
                    <a href="<?php echo site_url(); ?>/product-archive/">
                        <div class="black-btn">
                            商品購入一覧へ戻る
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
