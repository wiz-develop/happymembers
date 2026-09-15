<div class="product-nav">
    <div class="product-menu">
        <ul class="ml-0">
            <li class="link-item d-flex align-items-center ml-0">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/purchase-icon_w.png">
                <span class="pl-2">商品購入</span>
            </li>
            <a href="<?php echo get_home_url(); ?>/product/">
                <li>商品購入</li>
            </a>
            <a href="<?php echo get_home_url(); ?>/cart/">
                <li>カート一覧</li>
            </a>
            <a href="<?php echo get_home_url(); ?>/product-archive/">
                <li>商品購入履歴</li>
            </a>
        </ul>
    </div>
    <?php if (is_page('product')): ?>
    <div class="product-search">
        <div class="search-title d-flex align-items-center">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/search-icon.png">
            <span class="pl-2">商品検索</span>
        </div>
        <?php
            // $args = array(
            //     'post_type' => 'product',
            //     'post_status' => 'publish',
            //     'posts_per_page' => -1,
            //     'orderby' => 'date',
            //     'order' => 'DESC'
            // );

            // if (!empty($_POST['search_product_category'])) {
            //     foreach ($_POST['search_product_category'] as $value) {
            //         $search_area[] = htmlspecialchars($value, ENT_QUOTES);
            //     }
            //     $tax_query_args[] = array(
            //                         'taxonomy' => 'product_category',
            //                         'terms' => $search_area,
            //                         'field' => 'slug',
            //                         'operator' => 'IN'
            //                         );
            // }

            // if (!empty($_POST['search_product'])) {
            //     foreach ($_POST['search_product'] as $value) {
            //         $search_price[] = htmlspecialchars($value, ENT_QUOTES);
            //     }

            //     $tax_query_args[] = array(
            //                         'taxonomy' => 'product',
            //                         'terms' => $search_product,
            //                         'field' => 'slug',
            //                         'operator' => 'IN'
            //                         );
            // }

            // if (!empty($_POST['search_product_category']) || !empty($_POST['search_product'])) {
            //     $args += array('tax_query' => array($tax_query_args));
            // }
        ?>
        <div class="condition-title">商品選択</div>
        <div class="condition member_category mb-5">
            <?php
                // $product_categorys = get_terms('product_category', Array('hide_empty' => false));
                // foreach($product_categorys as $product_category):
                // $checked = "";
                // if(in_array($product_category->slug, $search_product_category)) $checked = "　checked";
            ?>
            <div class="category-btn">
                <input type="checkbox" name="category" id="all-member-category" class="prod_search" value="all_member">
                <label for="all-member-category">全て</label>
            </div>
                <!-- <input type="checkbox" name="product_category[]" value="<?php //echo esc_attr($product_category->slug);?>"<?php //echo $checked;?>> -->
                <?php //echo esc_html($product_category->name);?>
            <div class="category-btn">
                <input type="checkbox" name="category" id="happy-category" class="prod_search" value="happy_product">
                <label for="happy-category">ハッピー商品</label>
            </div>
            <div class="category-btn">
                <input type="checkbox" name="category" id="excellent-category" class="prod_search" value="excellent_product">
                <label for="excellent-category">エクセレント商品</label>
            </div>
            <?php //endforeach;?>
        </div>

        <div class="condition-title">商品カテゴリー</div>
        <div class="condition product_category">
            <?php
                // $products = get_terms('product', Array('hide_empty' => false, 'orderby' => 'slug'));
                // foreach($products as $product):
                // $checked = "";
                // if(in_array($product->slug, $search_product)) $checked = " checked";
            ?>
                <div class="category-btn">
                    <input type="checkbox" name="category" id="all-product-category" class="prod_search" value="all_product">
                    <label for="all-product-category">全て</label>
                </div>
                <div class="category-btn">
                    <input type="checkbox" name="category" id="food-category" class="prod_search" value="healthy_food">
                    <label for="food-category">健康食品</label>
                </div>
                <div class="category-btn">
                    <input type="checkbox" name="category" id="cosme-category" class="prod_search" value="cosmetics">
                    <label for="cosme-category">化粧品</label>
                </div>
                <div class="category-btn">
                    <input type="checkbox" name="category" id="soap-category" class="prod_search" value="detergent">
                    <label for="soap-category">洗剤</label>
                </div>
                <div class="category-btn">
                    <input type="checkbox" name="category" id="device-category" class="prod_search" value="machine">
                    <label for="device-category">機器販促品</label>
                </div>
                <div class="category-btn">
                    <input type="checkbox" name="category" id="cartridge-category" class="prod_search" value="cartridge">
                    <label for="cartridge-category">カートリッジ</label>
                </div>
                <div class="category-btn">
                    <input type="checkbox" name="category" id="family-category" class="prod_search" value="family_discount">
                    <label for="family-category">エクセレント商品</label>
                </div>
                <div class="category-btn">
                    <input type="checkbox" name="category" id="promotional-category" class="prod_search" value="promotional_items">
                    <label for="promotional-category">販促品</label>
                </div>
                <!-- <label>
                    <input type="checkbox" name="category" value="miles">
                    マイル
                </label>
                <label>
                    <input type="checkbox" name="category" value="fee">
                    出荷事務手数料
                </label> -->
            <?php //endforeach;?>
        </div>
        <div class="search-button">
            <form method="get" action="<?php echo esc_url(get_the_permalink()); ?>">
        </div>
        <div class="search-button-list">
            <div id="btn-search" class="black-btn btn-search">
                検索する
            </div>
            <div id="btn-clear-conditions" class="white-btn">
                検索条件をクリア
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>