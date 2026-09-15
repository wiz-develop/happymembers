/**********************************************
*  イベント
***********************************************/
/*-------------------------------------------*/
/* ロード時
/*-------------------------------------------*/
jQuery(function($){
    $(".product-list .quantity").each(function(i, quantity) {
        const inputQuantity = Number($(this).val())
        if (inputQuantity > 99 || inputQuantity === 0) {
            // エラー表示
            $(this).parents('.article-cart').find('.product-detail__error').text("0以上99個以下の数値をご入力ください。");
            $(this).parents('.article-cart').find('.product-detail__error').removeClass('display-none');
            // ご注文手続きへボタンを非活性
            $('.order_confirmation').addClass("order-procedure_off");
            $('.order_confirmation').removeClass('order-procedure');
            return
        }
        // ご注文手続きへボタンを活性
        $('.order_confirmation').removeClass("order-procedure_off");
        $('.order_confirmation').addClass('order-procedure');
    });

    // input要素へ手入力できないようにする
    $('.inputtext.cart-quantity').attr('readonly',true);

    /*-------------------------------------------*/
    /* 商品数量 [-]をクリックした時 / マイナス後の数値を取得してcartsの更新を行う。
    /*-------------------------------------------*/
    $('.button.btn-down-cart').on('click', function() {
        var productCode = $(this).parents('.order-field').data('product-code');
        var inputQuantity = $(this).parent().find('input.cart-quantity');
        var thisQuantityVal = Number($(inputQuantity).val());
        var dataType = $(this).parents('.product-purchase').data('type'); // カートからの処理(update)か一覧からの処理(create)か
        // 現在の値が万が一0になった場合マイナスできないので1をinputに入れる。
        if (thisQuantityVal === 0) {
            nextQuantityVal = 1
            // ご注文手続きへボタンを非活性
            if (!$('.order_confirmation').hasClass(".order-procedure_off")) {
                $('.order_confirmation').addClass("order-procedure_off")
            }
            // エラー表示
            $(`#product-${productCode}`).find('.product-detail__error').text("1以下の数値にはできません。カート数量を0にしたい場合は「カートから削除」ボタンを押してください");
            $(`#product-${productCode}`).find('.product-detail__error').removeClass('display-none');
            // 画面に数量を反映する
            $(inputQuantity).val(nextQuantityVal);
            // 0 -> 1に数量が変更しているので、cartに反映する
            onClickBtnCartUpDown(productCode, nextQuantityVal)
        }
        // 現在の値が1の時はマイナスを行いご注文手続きへのボタンを非活性、エラー表示。
        if (thisQuantityVal === 1) {
            nextQuantityVal  = 1;
            // エラー表示
            $(`#product-${productCode}`).find('.product-detail__error').text("1以下の数値にはできません。カート数量を0にしたい場合は「カートから削除」ボタンを押してください");
            $(`#product-${productCode}`).find('.product-detail__error').removeClass('display-none');
            // 画面に数量を反映する
            $(inputQuantity).val(nextQuantityVal);
        }
        // 現在の値が2以上の時はマイナスする。エラー表示がある場合、エラー表示を消す。
        if (thisQuantityVal >= 2) {
            nextQuantityVal  = thisQuantityVal - 1;
            console.log('エラー消えるはず');
            $(`#product-${productCode}`).find('.product-detail__error').addClass('display-none');
            // 画面に数量を反映する
            $(inputQuantity).val(nextQuantityVal);
            // 数量が変更しているので、cartに反映する
            onClickBtnCartUpDown(productCode, nextQuantityVal)
        }
        // エラーが1つでも出ていれば【ご注文手続きボタン】を非活性
        $(".product-list .quantity").each(function(i, quantity) {
            const inputQuant = Number($(this).val())
            if (inputQuant > 99 || inputQuant === 0) {
                $('.order_confirmation').addClass("order-procedure_off");
                return
            }
            $('.order_confirmation').addClass('order-procedure');
            $('.order_confirmation').removeClass("order-procedure_off");
        });
    })
    /*-------------------------------------------*/
    /* 商品数量 [＋]をクリックした時 / マイナス後の数値を取得してcartsの更新を行う。
    /*-------------------------------------------*/
    $('.button.btn-up-cart').on('click', function() {
        var productCode = $(this).parents('.order-field').data('product-code');
        var inputQuantity = $(this).parent().find('input.cart-quantity');
        var thisQuantityVal = Number($(inputQuantity).val());
        var dataType = $(this).parents('.product-purchase').data('type'); // カートからの処理(update)か一覧からの処理(create)か

        // 現在の値が99またはそれ以上であれば、エラー文を表示する。
        if (thisQuantityVal >= 99) {
            nextQuantityVal = 99;
            // エラー表示
            $(`#product-${productCode}`).find('.product-detail__error').text("99個以上一度にご注文いただけません。");
            $(`#product-${productCode}`).find('.product-detail__error').removeClass('display-none');
        }
        // 現在の値が99未満の場合
        if (thisQuantityVal < 99) {
            nextQuantityVal = thisQuantityVal +1;
            // エラー文非表示
            $(`#product-${productCode}`).find('.product-detail__error').text("");
            $(`#product-${productCode}`).find('.product-detail__error').addClass('display-none');
        }
        $(inputQuantity).val(nextQuantityVal);
        onClickBtnCartUpDown(productCode, nextQuantityVal);
        // エラーが1つでも出ていれば【ご注文手続きボタン】を非活性
        $(".product-list .quantity").each(function(i, quantity) {
            const inputQuant = Number($(this).val())
            if (inputQuant > 99 || inputQuant === 0) {
                $('.order_confirmation').addClass("order-procedure_off");
                return
            }
            $('.order_confirmation').addClass('order-procedure');
            $('.order_confirmation').removeClass("order-procedure_off");
        });
    })
    /*-------------------------------------------*/
    /* [ご注文手続きへ]をクリック時
    /*-------------------------------------------*/
    $('.order_confirmation').on('click', function() {
        window.location.href = homeUrl+"/cart/confirmation";
    })
    /*-------------------------------------------*/
    /* [カートから削除]をクリック時
    /*-------------------------------------------*/
    $('.btn-remove-cart').on('click', function() {
        productCode = $(this).parents('.product-purchase').data('product-code');
        $.when(
            ajxDeleteCart(productCode)
        ).then(function (res){
            obj= JSON.parse(res);
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
        }).catch(function (err) {
            console.log(err);
        })
    })
})

/**********************************************
*  FUNCTION
***********************************************/
/*-------------------------------------------*/
/*  カート 「+ -」ボタンクリック時の処理
/*-------------------------------------------*/
function onClickBtnCartUpDown(productCode, quantity) {
    usePoint = 0;

    $.when(
        ajxUpdateCartByOne(productCode, quantity)
    ).then(function (res){
        calculateSumTotal(usePoint);
        cartCount(10);
    }).catch(function (err) {
        console.log(err);
    })

    // 使えない（メソッドの中でメソッドを呼び出せなかった）
    // ajxCreateOrUpdateCart(productCode, quantity, requestType)
    // .done(function(data, textStatus, jqXHR) {
    //     if (textStatus !== 'success') {
    //         return
    //     }
    //     calculateSumTotal(usePoint);
    //     cartCount(1);
    // }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
    // });
}
