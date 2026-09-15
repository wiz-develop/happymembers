<?php
/*
 * Template Post Type: page
 * Template Name: 商品一覧
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
get_header();?>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/product.js"></script>
<?php
// 検索条件 （取引区分 と 商品カテゴリ にわける）
$search_criteria_arr_transaction = array();
$search_criteria_arr_category = array();
if (!empty($_GET['search_criteria'])) {
    $search_criteria = $_GET['search_criteria'];
    $search_criteria_arr = explode(",", $_GET['search_criteria']);

    foreach ($search_criteria_arr as $arr_item) :
        if ($arr_item == 'all_member' || $arr_item == 'happy_product' || $arr_item == 'excellent_product') {
            $search_criteria_arr_transaction[] = $arr_item;
        } else {
            $search_criteria_arr_category[] = $arr_item;
        }
    endforeach;
}
$user_info = get_member_info();
?>


<div id="page-product" class="page-product" data-mbr-type="<?php echo $_SESSION['info']['mbr_type']; ?>">
    <div class="mod-body">
        <div class="content flex-side">
            <div class="flame-side">
                <?php
                    if (!wp_is_mobile()) {
                        get_template_part('/assets/template/product-nav');
                    }
                ?>
            </div>
            <div class="flame-body">
                <div class="page-title">
                    <h1 class="page-header_pageTitle"><?php the_title(); ?></h1>
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/buy-step/order.png">
                </div>
                <div class="product-list">
                <?php
                    // 取引区分の検索条件整形
                    $tax_query_ransaction = null;
                    if (count($search_criteria_arr_transaction) > 0 && $search_criteria_arr_transaction[0] != 'all_member') {
                        $tax_query_ransaction =  array(
                                'taxonomy' => 'product',
                                'field' => 'slug',
                                'terms' =>  $search_criteria_arr_transaction,
                                // 'operator' => 'IN'
                            );
                    }
                    // 商品カテゴリの検索条件整形
                    $tax_query_category = null;
                    if (count($search_criteria_arr_category) > 0  && $search_criteria_arr_category[0] != 'all_product') {
                        $tax_query_category =  array(
                                'taxonomy' => 'product',
                                'field' => 'slug',
                                'terms' =>  $search_criteria_arr_category,
                                // 'operator' => 'IN'
                            );
                    }
                    // エクセレント会員でないハッピー会員、および特定のエクセレント会員はファミリー割引商品を表示しない
                    $tax_query_exclude = null;
                    $tax_query_exclude_array = array(
                            'taxonomy' => 'product',
                            'field' => 'slug',
                            'terms' =>  'family_discount',
                            'operator' => 'NOT IN'
                    );
                    if (isset($user_info['hp']['mbr_id']) && !isset($user_info['ex']['mbr_id'])) {
                        $tax_query_exclude = $tax_query_exclude_array;
                    }
                    if (isset($user_info['ex']['mbr_id']) && $user_info['ex']['mbr_id'] >= $MEM_ID_EXCELLENT) {
                        $tax_query_exclude = $tax_query_exclude_array;
                    }
                    $posts_per_page = -1;
                    $args = array(
                        'post_type' => 'product',
                        'posts_per_page' => $posts_per_page,
                        'tax_query' => array(
                            'relation' => 'AND',
                            $tax_query_ransaction,
                            $tax_query_category,
                            $tax_query_exclude,
                        ),
                        'meta_key'  => 'product_code', // カスタムフィールドのキー
                        'orderby' => 'meta_value_num', // 上で指定したカスタムフィールドの値の数値で並び替え
                        'order' => 'ASC', // 昇順で並べる
                    );
                    $my_posts = get_posts($args);?>
                <?php if ($my_posts) : ?>
                    <?php foreach ($my_posts as $i => $post) :
                        setup_postdata($post);
                        $post_id = get_the_ID();
                        $image = $cfs->get('image', $post_id);
                        $regular_price_colon = $cfs->get('regular_price', $post_id);
                        $regular_price = intval(str_replace(',', '', $regular_price_colon));
                        $product_code = $cfs->get('product_code', $post_id);
                        $terms = get_the_terms($post_id, 'product');
                        $trans_check = [];
                        if ($product_code === '3200' || $product_code === '4033') {
                            continue;
                        }
                        ?>
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
                            $cant_buy_mbr_type = "";
                            // 購入権限判断
                            if ($term->term_id === 13) {              // 13: ハッピー商品
                                $product_type = $PRODUCT_TYPE_HAPPY;  // 商品タイプ 0:ハッピー 1:エクセレント
                                $mbr_type = $MEM_TYPE_HAPPY;          // 会員タイプ 0:ハッピー 1:エクセレント
                                // $cant_buy_mbr_type = 1;            // 購入不可  0:ハッピー 1:エクセレント
                            } elseif ($term->term_id === 14) {        // 14: エクセレント商品
                                $product_type = $PRODUCT_TYPE_EXCELLENT;
                                $mbr_type = $MEM_TYPE_EXCELLENT;
                                // $cant_buy_mbr_type = 0;
                            }
                            ?>
                        <?php endforeach ; ?>
                        <?php
                        if (count($trans_check) === 2) {
                            $transaction = '共通';
                            $mbr_type = $MEM_TYPE_HAPPY;
                            $product_type = 2;
                        }
                        $product_price = get_product_price($mbr_type, $product_code, $cat_product, $regular_price);
                        ?>
                        <article id="article-<?php echo $i; ?>" class="article-product">
                            <div class="product-category <?php echo $term->slug; ?>">
                                <?php echo $cat_product ; ?>
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
                                        <?php echo '取引区分：'.$transaction; ?>
                                    </div>
                                    <div class="product-name">
                                        <?php the_title(); ?>
                                    </div>
                                    <div class="product-price">
                                        <?php if (is_numeric($product_price)) : ?>
                                            ¥<?php echo number_format($product_price); ?><span>(税込)</span>
                                        <?php else : ?>
                                            <span class="price-error"><?php echo $product_price; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="display-none product-detail__error"></div>
                            <!-- ハッピー会員アカウント：Active -->
                            <?php if ($product_type === 0) : ?>
                                <?php if ($user_info['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA || $user_info['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA || $user_info['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXD) : ?>
                                    <div class="product-purchase flag-<?php echo $cant_buy_mbr_type; ?>" data-product-code="<?php echo $product_code ?>" data-regular-price="<?php echo $regular_price ?>" data-type="add">
                                        <div class="order-field flag-<?php echo $cant_buy_mbr_type; ?>"
                                                data-index="<?php echo $i; ?>">
                                            <span>数量</span>
                                            <div class="button btn-down" data-index="<?php echo $i; ?>">
                                                －
                                            </div>
                                            <input type="number" value="0" class="inputtext quantity">
                                            <div class="button btn-up" data-index="<?php echo $i; ?>">
                                                ＋
                                            </div>
                                        </div>
                                        <div class="btn-list">
                                            <div class="cart-button-off btn-add-cart" data-index="<?php echo $i; ?>">
                                                カートに入れる<span>+</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="not-product">商品をご購入いただけません。</div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <!-- エクセレント会員アカウント：Active -->
                            <?php if ($product_type === 1) : ?>
                                <?php if ($user_info['mbr_combine_stat'] === $MEM_COMBINE_STATUS_EXA || $user_info['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA) : ?>
                                    <div class="product-purchase flag-<?php echo $cant_buy_mbr_type; ?>" data-product-code="<?php echo $product_code ?>" data-regular-price="<?php echo $regular_price ?>" data-type="add">
                                        <div class="order-field flag-<?php echo $cant_buy_mbr_type; ?>"　
                                            data-index="<?php echo $i; ?>">
                                            <span>数量</span>
                                            <div class="button btn-down" data-index="<?php echo $i; ?>">
                                                －
                                            </div>
                                            <input type="number" value="0" class="inputtext quantity">
                                            <div class="button btn-up" data-index="<?php echo $i; ?>">
                                                ＋
                                            </div>
                                        </div>
                                        <div class="btn-list">
                                            <div class="cart-button-off btn-add-cart" data-index="<?php echo $i; ?>">
                                                カートに入れる<span>+</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="not-product">商品をご購入いただけません。</div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <!-- ログインできているアカウント全て購入可能（退会：退会のみ購入NG） -->
                            <?php if ($product_type === 2) : ?>
                                <div class="product-purchase flag-<?php echo $cant_buy_mbr_type; ?>" data-product-code="<?php echo $product_code ?>" data-regular-price="<?php echo $regular_price ?>" data-type="add">
                                    <div class="order-field flag-<?php echo $cant_buy_mbr_type; ?>"
                                        data-index="<?php echo $i; ?>">
                                        <span>数量</span>
                                        <div class="button btn-down" data-index="<?php echo $i; ?>">
                                            －
                                        </div>
                                        <input type="number" value="0" class="inputtext quantity">
                                        <div class="button btn-up" data-index="<?php echo $i; ?>">
                                            ＋
                                        </div>
                                    </div>
                                    <div class="btn-list">
                                        <div class="cart-button-off btn-add-cart" data-index="<?php echo $i; ?>">
                                            カートに入れる<span>+</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </article>
                        <?php if (!$posts) : ?>
                            <div class="no-product">
                                <p>商品はございません。</p>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                </div>
                <div class="order">
                    <?php get_template_part('/assets/template/order-total');?>
                    <div class="order_btn">
                        <a href="<?php echo get_home_url(); ?>/cart/">
                            <div class="order-procedure">
                                ご注文内容確認
                            </div>
                        </a>
                    </div>
                </div>
                <div class="add-cart">
                    <span>カートに追加しました</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>

<style>
.article-product {
    position: relative;
}
.cart-button-off {
    background-color: #c0d5f5;
    pointer-events: none;
}
.count-button-off {
    background-color: #c0d5f5;
    pointer-events: none;
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
