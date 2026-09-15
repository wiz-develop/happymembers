<?php
/*
 * Template Post Type: page
 * Template Name: ご注文手続き
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
// 商品が空の場合商品一覧へリダイレクトをする。
$cart_product_list = delete_not_purchase_cart_product();

if ($cart_product_list) {
    foreach ($cart_product_list as $key => $cart_product) {
        $prod_detail = show_product($cart_product->product_code);
        $post_id = $prod_detail['post_id'];
        if (get_post_status($post_id) !== 'publish') {
            unset($cart_product_list[$key]);
        }
    }
}

if (!$cart_product_list) {
    wp_redirect(get_home_url().'/product');
}

get_header();

session_check();
global $MEM_COMBINE_STATUS_HPA;
global $MEM_COMBINE_STATUS_EXA;
global $MEM_COMBINE_STATUS_EXD;
global $MEM_COMBINE_STATUS_HPA_EXA;
global $MEM_COMBINE_STATUS_HPA_EXD;

$user = get_member_info();
console_log($user);
?>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/order.js"></script>

<!-- <script src="<?php // echo get_stylesheet_directory_uri();?>/assets/js/order-total.js"></script> -->
<div id="page-product" class="page-cart__confirmation" data-mbr-type="<?php echo $mbr_type; ?>" data-mbr_combine_status="<?php echo $user['mbr_combine_stat']; ?>">
    <div class="mod-header">
        <div class="content">
            <div class="page-title">
                <h1 class="page-header_pageTitle"><?php the_title(); ?></h1>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/buy-step/order-procedure.png">
            </div>
        </div>
    </div>
    <div class="mod-body">
        <div class="content flex-side">
            <div class="flame-body">
                <div class="user-info">
                    <div class="user-info__payment">
                        <div class="item">
                            <div class="item__name">
                                お支払い方法
                            </div>
                            <div class="item__detail">
                                コレクト（代金引換）
                            </div>
                        </div>
                        <div class="item check">
                            <div class="item__name">
                                お届け時間
                            </div>
                            <div class="item__detail">
                                <div class="change" id="delivery-request">
                                    <div class="change__detail">指定しない</div>
                                    <div class="edit">
                                        <div class="black-btn" id="btn-change_day">
                                            変更する
                                        </div>
                                    </div>
                                </div>
                                <div class="change-info display-none" id="change_delivery-request">
                                    <div class="change-info__content pl-0">
                                        <select name="time" >
                                            <option value="指定しない">指定しない</option>
                                            <option value="午前中">午前中</option>
                                            <option value="12時 ~ 14時">12時 ~ 14時</option>
                                            <option value="14時 ~ 16時">14時 ~ 16時</option>
                                            <option value="16時 ~ 18時">16時 ~ 18時</option>
                                            <option value="18時 ~ 20時">18時 ~ 20時</option>
                                            <option value="19時 ~ 21時">19時 ~ 21時</option>
                                        </select>
                                    </div>
                                    <div class="btn-link">
                                        <div class="black-btn" id="btn-confirm_day">確定</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="user-info__address">
                        <div class="item check">
                            <div class="item__name">
                                お届け・ご連絡先
                            </div>
                            <div class="item__detail">
                                <?php
                                    if ($_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXD) {
                                        // ハッピー： Active のときハッピーアカウントの送り先情報を参照
                                        $user_data = $user['hp'];
                                    } elseif ($_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_EXA) {
                                        // ハッピー： 退会中(登録なし) / エクセレント: Active のときエクセレントアカウントから送り先情報を参照
                                        $user_data = $user['ex'];
                                    }
                                    $ke_pcd = $user_data['ke_pcd'];
                                    $ke_add1 = $user_data['ke_add1'];
                                    $ke_add2 = $user_data['ke_add2'];
                                    $ke_tel = $user_data['ke_tel'];
                                    $ke_mob = $user_data['ke_mob'];
                                    $re_pcd = $user_data['re_pcd'];
                                    $re_add1 = $user_data['re_add1'];
                                    $re_add2 = $user_data['re_add2'];
                                    $re_tel = $user_data['re_tel'];
                                    $re_mob = $user_data['re_mob'];
                                    $syodlv_tel = $user['syodlv_tel'];
                                ?>
                                <div class="item__detail__content">
                                    <p class="item__detail__content__attention">
                                        商品お届け先は
                                        <span class="ke-address strong <?php if ($user['syodlv'] !== 0) echo 'display-none'; ?>">契約者住所</span>
                                        <span class="re-address strong <?php if ($user['syodlv'] !== 1) echo 'display-none'; ?>">別送住所</span>
                                        になっています。
                                    </p>
                                </div>
                                <div class="item__detail__content">
                                    <div class="item__detail__content__title">
                                        ご住所
                                    </div>
                                    <div class="item__detail__content__txt ke-address <?php if ($user['syodlv'] !== 0) echo 'display-none'; ?>">
                                        <?php echo $ke_pcd.'<br>'.$ke_add1.'<br>'.$ke_add2; ?>
                                    </div>
                                    <div class="item__detail__content__txt re-address <?php if ($user['syodlv'] !== 1) echo 'display-none'; ?>">
                                        <?php echo $re_pcd.'<br>'.$re_add1.'<br>'.$re_add2; ?>
                                    </div>
                                </div>
                                <div class="item__detail__content">
                                    <div class="item__detail__content__title">
                                        電話番号
                                    </div>
                                    <div class="item__detail__content__txt ke-address <?php if ($user['syodlv'] !== 0) echo 'display-none'; ?>">
                                        <?php
                                            if ($user['syodlv'] == 0) {
                                                echo $syodlv_tel;
                                            } elseif ($ke_mob) {
                                                echo $ke_mob;
                                            } else {
                                                echo $ke_tel;
                                            }
                                        ?>
                                    </div>
                                    <div class="item__detail__content__txt re-address <?php if ($user['syodlv'] !== 1) echo 'display-none'; ?>">
                                        <?php
                                            if ($user['syodlv'] == 1) {
                                                echo $syodlv_tel;
                                            } elseif ($re_mob) {
                                                echo $re_mob;
                                            } else {
                                                echo $re_tel;
                                            }
                                        ?>
                                    </div>
                                </div>
                                <?php if ($user['co_nm']) : ?>
                                    <div class="item__detail__content ke-address <?php if ($user['syodlv'] !== 0) echo 'display-none'; ?>">
                                        <div class="item__detail__content__title">
                                            法人名
                                        </div>
                                        <div class="item__detail__content__txt">
                                            <?php echo $user['co_nm']; ?>
                                        </div>
                                    </div>
                                    <div class="item__detail__content ke-address <?php if ($user['syodlv'] !== 0) echo 'display-none'; ?>">
                                        <div class="item__detail__content__title">
                                            肩書き
                                        </div>
                                        <div class="item__detail__content__txt">
                                            <?php echo $user['mbr_kata']; ?>
                                        </div>
                                    </div>
                                <?php endif ; ?>
                                <?php if ($user_data['re_add1']) : ?>
                                    <div class="change" id="delivery-address">
                                        <div class="change__detail">送り先の変更</div>
                                        <div class="edit">
                                            <div class="black-btn" id="btn-change_address">
                                                変更する
                                            </div>
                                        </div>
                                    </div>
                                    <div class="change-info display-none" id="change_delivery-address">
                                        <div class="change-info__content pl-0">
                                            <select name="address">
                                                <option value="0" <?php if ($user['syodlv'] === 0) echo 'selected'; ?>><?php echo '契約者住所'; ?></option>
                                                <option value="1" <?php if ($user['syodlv'] === 1) echo 'selected'; ?>><?php echo '別送住所'; ?></option>
                                            </select>
                                        </div>
                                        <div class="btn-link">
                                            <div class="black-btn" id="btn-confirm_address">確定</div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="product-list">
                <!----------------- 商品一覧を取得/変数整形/表示 ----------------->
                <?php
                    $args = array(
                        'post_type' => 'product',
                        'orderby' => 'date',
                        'posts_per_page' => '-1'
                    );
                    $query = new WP_Query($args); ?>
                <!-- カート商品のproduct_codeと商品一覧のproduct_codeが一致する商品の詳細を取得 -->
                <?php
                    $user = wp_get_current_user();
                    $user_id = $user->ID;
                    // 数量が0の商品はカートから削除する
                    // delete_empty_order_product();
                    // $cart_product_list = $wpdb->get_results("SELECT * FROM carts WHERE user_id = $user_id");
                ?>
                <?php $family_products = []; ?> <!--ファミリー割引商品の小計 -->
                <?php if ($cart_product_list) : ?>
                    <?php foreach ($cart_product_list as $key => $cart_product) :
                        $flag_family = false; // ファミリー割引商品ののフラグ
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
                                // ＊＊＊ファミリー商品の合計金額を出すためのフラグ
                                if ($term->slug === 'family_discount') {
                                    $flag_family = true;
                                }
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
                        $product_price = get_product_price($product_type, $product_code, $cat_product, $regular_price);
                        if ($flag_family === true) {
                            $family_prod_sub_total = $product_price * $quantity;
                            array_push($family_products, $family_prod_sub_total);
                        }?>
                        <!------------------ 商品詳細表示判断終了 ------------------>
                        <article>
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
                                        <?php echo $name; ?>
                                    </div>
                                    <div class="product-price">
                                        ¥<?php echo number_format($product_price); ?><span>(税込)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="product-purchase">
                                <div class="order-field">
                                    数量  <?php echo $quantity ?>  点
                                </div>
                            </div>
                        </article>
                        <?php if (!$posts) : ?>
                            <div class="no-prooduct">
                                <p>商品はございません。</p>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php
                    $family_prod_total = array_sum($family_products);
                ?>
                </div>
                <div class="mobile-nav-toggle">
                    <div class="order_completion order-procedure">
                        ご注文内容確定
                    </div>
                    <div class="back-link">
                        <a href="<?php echo home_url(); ?>/cart/">
                            <div class="black-btn">
                                ご注文内容確認へ戻る
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="flame-side" id="for-mile-discount" data-fam-prod="<?php echo $family_prod_total ?>">
                <div class="order">
                    <?php get_template_part('/assets/template/order-total');?>
                    <div class="back-link">
                        <a href="<?php echo site_url(); ?>/cart/">
                            <div class="black-btn">
                                ご注文内容確認へ戻る
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php get_footer(); ?>

<style>
.item__detail .display-none {
    display: none !important;
}
</style>
