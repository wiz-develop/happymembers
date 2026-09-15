<script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/order.js"></script>
<?php if (is_page('product')): ?>
<div class="mobile-nav-toggle sp-order">
    <div class="order-step confirm">
        <a href="<?php echo get_home_url(); ?>/cart/">
            <div class="order-procedure">
                ご注文内容確認
            </div>
        </a>
    </div>
    <div id = "btn-clear-conditions" class="white-btn">
        検索条件をクリア
    </div>
</div>
<?php endif; ?>
<?php if (is_page('cart')): ?>
<div class="mobile-nav-toggle sp-order">
    <div class="order-step confirmation">
        <a href="<?php echo get_home_url(); ?>/cart/confirmation/">
            <div id="btn-order_confirmation-sp" class="order_confirmation order-procedure">
                ご注文手続きへ
            </div>
        </a>
    </div>
</div>
<?php endif; ?>
<?php if (is_page('confirmation')): ?>
<div class="mobile-nav-toggle sp-order">
    <div class="order-step completion">
        <div id="btn-order_completion-sp" class="order_completion order-procedure">
            ご注文内容確定
        </div>
        <div class="back-link">
            <a href="<?php echo get_home_url(); ?>/cart/">
                <div id="btn-order_completion-sp" class="black-btn">
                    ご注文内容確認へ戻る
                </div>
            </a>
        </div>
    </div>
</div>
<?php endif; ?>
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
</style>