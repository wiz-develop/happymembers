jQuery(function($){
    /**********************************************
    *  イベント
    ***********************************************/
    /*-------------------------------------------*/
    /* ロード時
    /*-------------------------------------------*/
    // $.ajax({
    //     type: 'POST',
    //     url: ajaxUrl,
    //     cache: false,
    //     data: {
    //         'action' : 'fetch_cart_list_by_user_id',
    //         'communication_type' : 1, // カートからの処理か一覧からの処理か
    //     },
    // }).done(function(data) {
    //     // 商品が存在していなければボタンを非活性に
    //     // console.log('帰ってきてる');
    //     // console.log($.type(data));
    //     if (data === '0') {
    //         if ($('#btn-order_confirmation').hasClass('order-procedure')) {
    //             $('#btn-order_confirmation').removeClass('order-procedure');
    //             $('#btn-order_confirmation').addClass('order-procedure_off');
    //         }
    //     }
    // }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
    //     console.log("function       : fetch_cart_list_by_user_id")
    //     console.log("XMLHttpRequest : " + XMLHttpRequest.status);
    //     console.log("textStatus     : " + textStatus);
    //     console.log("errorThrown    : " + errorThrown.message);
    // });

    // 0: ヘッダーに数章セット 1: ご注文手続きのボタン可否確認 2: 0.1両方実行
    cartCount(1);

    // input要素へ手入力できないようにする
    $('.inputtext.quantity').attr('readonly',true);

    /*-------------------------------------------*/
    /* - をクリックした時、マイナス後の数値を取得してcartsの更新を行う。
    /*-------------------------------------------*/
    $('.button.btn-down-cart').on('click', function() {
        var inputQuantity = $(this).parent().find('input.quantity');
        num = Number($(inputQuantity).val());
        if (num >= 2) {
            quantity  = num - 1;
            $(inputQuantity).val(quantity);
        } else {
            quantity = 1;
            $(inputQuantity).val(1);
        }

        productCode = $(this).parents('.product-purchase').data('product-code');
        // regularPrice = $(this).parents('.product-purchase').data('regular-price');
        dataType = $(this).parents('.product-purchase').data('type'); // カートからの処理か一覧からの処理か

        updateCart(productCode, quantity, dataType)

    })

    /*-------------------------------------------*/
    /* + をクリックした時、マイナス後の数値を取得してcartsの更新を行う。
    /*-------------------------------------------*/
    $('.button.btn-up-cart').on('click', function() {
        var inputQuantity = $(this).parent().find('input.quantity');
        num = Number($(inputQuantity).val());

        quantity = num + 1;
        $(inputQuantity).val(quantity);

        productCode = $(this).parents('.product-purchase').data('product-code');
        // regularPrice = $(this).parents('.product-purchase').data('regular-price');
        dataType = $(this).parents('.product-purchase').data('type');

        updateCart(productCode, quantity, dataType);
    })

    /*-------------------------------------------*/
    /* ご注文手続きへクリック時
    /*-------------------------------------------*/
    $('.order_confirmation').on('click', function() {
        window.location.href = homeUrl+"/cart/confirmation";
    })

    /*-------------------------------------------*/
    /* カートから削除をクリック
    /*-------------------------------------------*/
    $('.btn-remove-cart').on('click', function() {
        productCode = $(this).parents('.product-purchase').data('product-code');
        deleteCart(productCode)
    })

    /**********************************************
    *  function
    ***********************************************/
    /*-------------------------------------------*/
    /*  カートテーブル更新処理
    /*-------------------------------------------*/
    function updateCart(productCode, quantity, requestType) {
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            cache: false,
            data: {
                'action' : 'create_or_update_cart',
                'code' : productCode,
                'quantity' : quantity,
                'request_type' : requestType, // カートからの処理か一覧からの処理か
            },
        }).done(function(data) {
            usePoint = 0;
            calculateSumTotal(usePoint);
            cartCount(0);
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            console.log("function       : create_or_update_cart")
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
        });
    }

    /*-------------------------------------------*/
    /*  カートから削除処理
    /*-------------------------------------------*/
    function deleteCart(productCode) {
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            cache: false,
            data: {
                'action' : 'delete_cart',
                'code' : productCode,
            },
        }).done(function(data) {
            obj= JSON.parse(data);
            console.log(obj.product_exist);
            productCode = obj.product_code;

            calculateSumTotal(0);
            cartCount(10); // 0: ヘッダーに数章セット 1: ご注文手続きのボタン可否確認 2: 0.1両方実行
            $(`#product-${productCode}`).hide('slow');
            // 商品が存在していなければボタンを非活性に
            if (!obj.product_exist) {
                if ($('#btn-order_confirmation').hasClass('order-procedure')) {
                    $('#btn-order_confirmation').addClass('order-procedure_off');
                    $('#btn-order_confirmation').removeClass('order-procedure');
                }
            }

        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            console.log("function       : delete_cart");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
        });
    }
})

/*-------------------------------------------*/
/*  カートの数量カウントする
/*-------------------------------------------*/
function cartCount(actionType) {
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
