<?php
/*
 * Template Post Type: page
 * Template Name: カート
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
get_header();
$cart_product_list = delete_not_purchase_cart_product();

?>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/cart.js"></script>

<div id="page-product" class="page-cart">
    <div class="mod-body">
        <div class="content flex-side">
            <div class="flame-side">
                <?php get_template_part('/assets/template/product-nav');?>
            </div>
            <div class="flame-body">
                <div class="page-title">
                    <h1 class="page-header_pageTitle"><?php the_title(); ?></h1>
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/buy-step/order-confirm.png">
                </div>
                <?php if (!empty($_SESSION['cart_removed_names'])) : ?>
                    <div class="cart-status-alert fw-bold rounded" style="background: #fff3cd; padding: 15px; margin-bottom: 20px; color: #856404;">
                        <p class="fw-bold">以下の商品は販売が終了したか、現在ご購入いただけないためカートから削除されました。</p>
                        <ul class="m-0">
                            <?php foreach ($_SESSION['cart_removed_names'] as $name) : ?>
                                <li><?php echo esc_html($name); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php unset($_SESSION['cart_removed_names']); ?>
                    </div>
                <?php endif; ?>
                <div class="product-list">
                <!-- 商品一覧を取得 -->
                <?php
                    $args = array(
                        'post_type' => 'product',
                        'orderby' => 'date',
                        'posts_per_page' => '-1'
                    );
                    $query = new WP_Query($args); ?>
                <!-- カート商品のproduct_codeと商品一覧のproduct_codeが一致する商品の詳細を表示する -->
                <?php
                    $user = wp_get_current_user();
                    $user_id = $user->ID;
                    // $cart_product_list = $wpdb->get_results("select * from carts WHERE user_id = $user_id");
                ?>

                <?php if ($cart_product_list) : ?>
                    <?php foreach ($cart_product_list as $key => $cart_product) :
                        $product_code = $cart_product->product_code;
                        $quantity = $cart_product->quantity;
                        $prod_detail = show_product($product_code);
                        $post_id = $prod_detail['post_id'];
                        $name = get_the_title($post_id, 'product');
                        $image = $cfs->get('image', $post_id);
                        $terms = get_the_terms($post_id, 'product');
                        $regular_price_colon = $cfs->get('regular_price', $post_id);
                        $regular_price = intval(str_replace(',', '', $regular_price_colon));
                        ?>
                        <?php $trans_check = []; ?>
                        <?php foreach ($terms as $term) : ?>
                            <?php
                            // 取引区分
                            if ($term->parent === 12) {
                                $transaction = $term->name;   // 取引区分
                                array_push($trans_check, $term->name);
                            // 商品分類
                            } elseif ($term->parent === 15) {
                                $cat_product = $term->name;
                            }
                            // 購入権限判断
                            if ($term->term_id === 13) {        // 13: ハッピー商品
                                $product_type = 0;              // 商品タイプ 0:ハッピー 1:エクセレント
                                $cant_buy_mbr_type = 1;         // 購入不可　 0:ハッピー 1:エクセレント 2:どちらも購入できる
                            } elseif ($term->term_id === 14) {  // 14: エクセレント商品
                                $product_type = 1;
                                $cant_buy_mbr_type = 0;
                            }
                            ?>
                        <?php endforeach ; ?>
                        <?php
                        if (count($trans_check) === 2) {
                            $transaction = '共通';
                        }
                        $product_price = get_product_price($product_type, $product_code, $cat_product, $regular_price);?>

                        <article id="product-<?php echo $product_code ?>" class="article-cart">
                            <div class="product-category <?php echo $term->slug; ?>">
                                <?php  echo $cat_product ; ?>
                            </div>
                            <div class="product-detail">
                                <div class="product-detail__image">
                                    <?php if ($image) : ?>
                                        <img src="<?php echo $image; ?>" alt="<?php the_title(); ?>">
                                    <?php else : ?>
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/product/no-image.png" alt="no image">
                                    <?php endif; ?>
                                </div>
                                <div class="product-detail__txt">
                                    <div class="classification">
                                        <?php  echo '取引区分：'.$transaction; ?>
                                    </div>
                                    <div class="product-name">
                                        <?php echo $name; ?>
                                    </div>
                                    <div class="product-price">
                                        ¥<?php  echo number_format($product_price) ?><span>(税込)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="product-purchase" data-product-code="<?php echo $product_code ?>" data-regular-price="<?php echo $regular_price ?>" data-type="update">
                                <div class="order-field" data-product-code="<?php echo $product_code; ?>">
                                    <span>数量</span>
                                    <div class="button btn-down-cart">
                                        －
                                    </div>
                                    <input type="number" value="<?php echo $quantity ?>" class="inputtext cart-quantity">
                                    <div class="button btn-up-cart">
                                        ＋
                                    </div>
                                </div>
                                <div class="btn-list">
                                    <div class="white-btn cart-remove btn-remove-cart">
                                        カートから削除
                                    </div>
                                </div>
                            </div>
                            <div class="display-none product-detail__error"></div>
                        </article>
                        <?php if (!$posts) : ?>
                            <div class="no-prooduct">
                                <p>商品はございません。</p>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="m-5">カートの商品はありません</div>
                <?php endif; ?>
                </div>
                <div class="back-link">
                    <a href="<?php echo site_url(); ?>/product/">
                        <div class="black-btn">
                            商品一覧へ戻る
                        </div>
                    </a>
                </div>
                <div class="order">
                    <?php get_template_part('/assets/template/order-total');?>
                </div>
            </div>
        </div>
    </div>

</div>
<?php get_footer(); ?>
<style>
.article-cart {
    position: relative;
}
.btn-list .white-btn {
    cursor: pointer;
}
.product-detail__error {
    position: absolute;
    bottom: 30%;
    padding: 10px;
    margin: 0 5px;
    width: 95%;
    font-size: 12px;
    font-weight: bold;
    background-color: white;
    box-shadow: 0 0 8px grey;
    border-radius: 10px;
}
</style>