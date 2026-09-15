/**********************************************
*  イベント
***********************************************/
/*-------------------------------------------*/
/*  ロード時
/*-------------------------------------------*/
jQuery(function() {
    console.log('p_common');
    // 0: ヘッダーに数章セット 1: ご注文手続きのボタン可否確認 2: 0.1両方実行
    cartCount(10);
})
/**********************************************
*  COMMON AJAX
***********************************************/
/*-------------------------------------------*/
/*  AJAX: カート 更新/新規登録
/*-------------------------------------------*/
// cartに追加できるのは1商品につき99個まで
function ajxCreateOrUpdateCart(productCode, quantity, requestType) {
    return $.ajax({
        type: 'POST',
        url: ajaxUrl,
        cache: false,
        data: {
            'action' : 'create_or_update_cart',
            'code' : productCode,
            'quantity' : quantity,
            'request_type' : requestType, // カートからの処理か一覧からの処理か
        },
        dataType: 'json',
    })
  // $result['result'] success or over
  // $result['count']  現在cartにある数量
}
/*-------------------------------------------*/
/*  AJAX: カート 更新 / 1つづつ追加or減少
/*-------------------------------------------*/
function ajxUpdateCartByOne(productCode, quantity, requestType) {
    return $.ajax({
        type: 'POST',
        url: ajaxUrl,
        cache: false,
        data: {
            'action' : 'update_cart_by_one',
            'code' : productCode,
            'quantity' : quantity,
            'request_type' : requestType, // カートからの処理か一覧からの処理か
        },
        dataType: 'json',
    })
  // $result['result'] success or error
}
/*-------------------------------------------*/
/*  AJAX: カート 数量取得
/*-------------------------------------------*/
function ajxFetchQuantityByUserId() {
    return $.ajax({
        type: 'POST',
        url: ajaxUrl,
        cache: false,
        data: {
            'action' : 'fetch_quantity_by_user_id',
        },
        dataType : 'json',
    })
  // $result['count']
}
/*-------------------------------------------*/
/*  AJAX: カート 削除
/*-------------------------------------------*/
function ajxDeleteCart(productCode) {
    return $.ajax({
        type: 'POST',
        url: ajaxUrl,
        cache: false,
        data: {
            'action' : 'delete_cart',
            'code' : productCode,
        },
    })
  // $result['product_code']
  // $result['product_exist'] true false
}

/*-------------------------------------------*/
/*  カートの数量をヘッダーに描画する
/*-------------------------------------------*/
function cartNumberSetting(cartNum) {
    productNumDom = $('span').hasClass('number-cart_product');
    if (productNumDom) {
        $('span.number-cart_product').remove();
    }
    if (cartNum !== 0) {
        $('span.menu-cart').after('<span class="number-cart_product">'+cartNum+'</span>');
    }
}
/*-------------------------------------------*/
/*  カートの数量カウントする
/*-------------------------------------------*/
function cartCount(actionType, productTotalObj = null) {
    $.ajax({
        type: 'POST',
        url: ajaxUrl,
        cache: false,
        data: {
            'action' : 'count_cart_product_by_user_id',
        },
        dataType : 'json',
    }).done(function(data){
        if (actionType === 0) {
            cartNumberSetting(data.count);
        }
        if (actionType === 1) {
            checkOrderBtnEffective(data.count);
        }
        if (actionType === 10) {
            cartNumberSetting(data.count);
            checkOrderBtnEffective(data.count);
        }
    }).fail(function (XMLHttpRequest, textStatus, errorThrown){
        console.log("function       : count_cart_product_by_user_id" );
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    });
}
/*-------------------------------------------*/
/*  ご注文手続きへのボタンの可否
/*-------------------------------------------*/
function checkOrderBtnEffective(data) {
  // 商品が存在していなければボタンを非活性に
    if (data === 0) {
        $('.order_confirmation').each(function(index, element){
            $(this).addClass('order-procedure_off');
            $(this).removeClass('order-procedure');
        })
    }

    if (data > 0) {
        $('.order_confirmation').each(function(index, element){
            $(this).addClass('order-procedure');
            $(this).removeClass('order-procedure_off');
        })
    }
}