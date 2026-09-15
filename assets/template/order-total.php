<!-- <script src="<?php // echo get_stylesheet_directory_uri(); ?>/assets/js/order.js"></script> -->
<!-- ご注文確認、ご注文手続きへ、ご注文内容確定ボタンは複数箇所にあるが、合計金額などの金額表示はここだけ -->
<div class="order-total">
    <table>
        <tr>
            <td class="item-name">商品小計(税込)</td>
            <td class="item-detail" id="sub-total">¥ 0</td>
        </tr>
        <tr>
            <td class="item-name">合計点数</td>
            <td class="item-detail" id="sum-quantity">0 点</td>
        </tr>
        <tr>
            <td class="item-name">出荷事務手数料</td>
            <td class="item-detail" id="fee">¥ 0</td>
        </tr>
        <!-- エクセレントActiveの時しか表示しない -->
        <!-- 金額パネルはget_infoを呼び出していないので、header_infoに設定しているCOMBINE_STATUSでJSで判別 ※ order_total,js -->
        <tr>
            <td class="item-name">ご注文合計</td>
            <td class="item-detail price" id="discounted-price">¥ 0<span>(税込)</span></td>
        </tr>
    </table>
    <?php if (is_page('product')): ?>
    <div class="order-step confirm">
        <a href="<?php echo get_home_url(); ?>/cart/">
            <div class="order-procedure">
                ご注文内容確認
            </div>
        </a>
    </div>
    <?php endif; ?>
    <?php if (is_page('cart')): ?>
        <div class="order-step confirmation">
            <div class="order_confirmation order-procedure order-procedure_of">
                ご注文手続きへ
            </div>
        </div>
    <?php endif; ?>
    <?php if (is_page('confirmation')): ?>
        <div class="order-step completion">
            <div class="order_completion order-procedure order-procedure_off">
                ご注文内容確定
            </div>
        </div>
        <div class="alert-limit_price error-message">注文合計金額が50万円以上のため購入できません。</div>
    <?php endif; ?>
</div>
<style>
.order-procedure {
    cursor: pointer;
}
.order-procedure_off {
    color: white;
    padding: .5rem;
    background-color: #d3cdc8;
    pointer-events: none;
}
.display-none {
    display: none;
}
</style>
