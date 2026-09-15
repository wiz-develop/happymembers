<?php
    require("config.php");
    require_once($CMS_PATH."/wp-load.php");

    $now_post_num = $_POST['now_post_num'];
    $get_post_num = $_POST['get_post_num'];
    $html = '';

    // 最も古い投稿のidを取得
    $old_args = array(
        'post_status' => 'publish',
        'post_type' => 'product',
        'posts_per_page' => 1,
        'meta_key'  => 'product_code', // 商品idで並び替え
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
    );
    $old_posts = get_posts($old_args);
    $old_post_id = $old_posts[0]->ID;
    $old_has_post = false;


    $user_info = get_member_info();

    // 商品一覧
    $args = array(
        'post_status' => 'publish',
        'post_type' => 'product',
        'meta_key'  => 'product_code', // 商品idで並び替え
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
        'posts_per_page' => $get_post_num,
        'offset' => $now_post_num,
    );
    $posts = new WP_Query($args);

    if ($posts -> have_posts()) :
        while ($posts -> have_posts()) : $posts -> the_post();

            $post_id = get_the_ID();
            $image = $cfs->get('image', $post_id);
            $regular_price_colon = $cfs->get('regular_price', $post_id);
            $regular_price = intval(str_replace(',', '', $regular_price_colon));
            $code = $cfs->get('product_code', $post_id);
            $terms = get_the_terms($post_id, 'product');
            $trans_check = [];

            foreach ($terms as $term) :
                 // 取引区分
                if ($term->parent === 12) {
                    $transaction = $term->name;   // 取引区分
                    array_push($trans_check, $term->name);
                // 商品分類
                } elseif ($term->parent === 15) {
                    $cat_product = $term->name;
                }
                // 購入権限判断
                if ($term->term_id === 13) {              // 13: ハッピー商品
                    $product_type = $PRODUCT_TYPE_HAPPY;  // 商品タイプ 0:ハッピー 1:エクセレント
                    $mbr_type = $MEM_TYPE_HAPPY;          // 会員タイプ 0:ハッピー 1:エクセレント
                    $cant_buy_mbr_type = 1;            // 購入不可  0:ハッピー 1:エクセレント
                } elseif ($term->term_id === 14) {        // 14: エクセレント商品
                    $product_type = $PRODUCT_TYPE_EXCELLENT;
                    $mbr_type = $MEM_TYPE_EXCELLENT;
                    $cant_buy_mbr_type = 0;
                }
            endforeach;
            if (count($trans_check) === 2) {
                $transaction = '共通';
                $mbr_type = $MEM_TYPE_HAPPY;
                $product_type = 2;
            }
            $product_price = get_product_price($mbr_type, $code, $cat_product, $regular_price);

            if ($old_post_id == $post_id) {
                $old_has_post = true;
            }

            $html .= '
                <article>
                    <div class="product-category '.$term->slug.'">';
            $html .= $cat_product;
            $html .= '
                    </div>
                    <div class="product-detail">
                        <div class="product-detail__image">';
                        if ($image) :
                            $html .= '<img src="'.$image.'" alt="'.get_the_title().'">';
                        else :
                            $html .= '<img src="'.get_stylesheet_directory_uri().'/assets/images/product/no-image.png" alt="no image">';
                        endif;

            $html .= '
                    </div>
                    <div class="product-detail__txt">
                        <div class="classification">取引区分：'.$transaction.'</div>
                        <div class="product-name">'.get_the_title().'</div>
                        <div class="product-price">
                            ¥'.number_format($product_price).'<span>(税込)</span>
                        </div>
                    </div>
                </div>';
            // ハッピー会員アカウント：Active
            if ($product_type === 0) :
                if ($user_info['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA || $user_info['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA) :
                    $html .= '
                        <div class="product-purchase flag-'.$cant_buy_mbr_type.'" data-product-code="'.$code.'" data-regular-price="'.$regular_price.'" data-type="add">
                            <div class="order-field flag-'.$cant_buy_mbr_type.'">
                                <span>数量</span>
                                <div class="button btn-down">
                                    －
                                </div>
                                <input type="number" value="1" class="inputtext quantity">
                                <div class="button btn-up">
                                    ＋
                                </div>
                            </div>
                            <div class="btn-list">
                                <div class="cart-button btn-add-cart">
                                    カートに追加<span>+</span>
                                </div>
                            </div>
                        </div>';
                else :
                    $html .= '<div class="not-product">商品をご購入いただけません。</div>';
                endif;
            endif;
            // エクセレント商品だった場合
            if ($product_type === 1) :
                if ($user_info['mbr_combine_stat'] === $MEM_COMBINE_STATUS_EXA || $user_info['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA) :
                    $html .= '
                        <div class="product-purchase flag-'.$cant_buy_mbr_type.'" data-product-code="'.$code.'" data-regular-price="'.$regular_price.'" data-type="add">
                            <div class="order-field flag-'.$cant_buy_mbr_type.'">
                            <span>数量</span>
                            <div class="button btn-down">
                                －
                            </div>
                            <input type="number" value="1" class="inputtext quantity">
                            <div class="button btn-up">
                                ＋
                            </div>
                        </div>
                        <div class="btn-list">
                            <div class="cart-button btn-add-cart">
                                カートに追加<span>+</span>
                            </div>
                        </div>
                    </div>';
                else :
                    $html .= '<div class="not-product">商品をご購入いただけません。</div>';
                endif;
            endif;
            // ログインできているアカウント全て購入可能（退会：退会のみ購入NG）
            if ($product_type === 2) :
                $html .= '
                    <div class="product-purchase flag-'.$cant_buy_mbr_type.'" data-product-code="'.$code.'" data-regular-price="'.$regular_price.'" data-type="add">
                        <div class="order-field flag-'.$cant_buy_mbr_type.'">
                            <span>数量</span>
                            <div class="button btn-down">
                                －
                            </div>
                            <input type="number" value="1" class="inputtext quantity">
                            <div class="button btn-up">
                                ＋
                            </div>
                        </div>
                        <div class="btn-list">
                            <div class="cart-button btn-add-cart">
                                カートに追加<span>+</span>
                            </div>
                        </div>
                    </div>';
            endif;
        $html .= '</article>';
        endwhile;
    endif; wp_reset_postdata();

    // 最も古い投稿がループに存在するかどうかを判定し、ボタンを非表示にする
    if ($old_has_post) {
        $html .= '
            <style>
                .product_more_disp {
                    display: none;
                }
            </style>';
    }
echo $html;
