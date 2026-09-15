/**********************************************
*  イベント
***********************************************/
/*-------------------------------------------*/
/*  ロード時
/*-------------------------------------------*/
jQuery(function() {
    // ページ読み込んだ時にクエリパラメータ取得する
    if (location.search) {
        var query = location.search;
        var value = decodeURIComponent(query).split('=');
        searchCriteria = value[1].split(',');
    }

    $('.product-purchase').each(function(index, element){
        var inputQuantity = $(this).find('input.quantity').val();
        if (inputQuantity === '0') {
            $(this).find('.btn-add-cart').removeClass('cart-button');
            $(this).find('.btn-add-cart').addClass('cart-button-off');
        }
    })
})
/*-------------------------------------------*/
/*  商品数量 直接入力した場合
/*-------------------------------------------*/
$(document).on('input', 'input.quantity', function() {
    var index = $(this).parents('.order-field').data('index');
    var productCode = $(this).parents('.product-purchase').data('product-code');
    var inputQuantity = $(this).parent().find('input.quantity');
    var inputQuantityVal = $(this).parents('.product-purchase').find('input.quantity').val();
    // ひらがな,3桁以上の数字が入力された場合：2桁未満の半角数字での入力を促すエラーを表示する
    if (!inputQuantityVal.match( /^([0-9]{0,2})$/)) {
        // エラー表示
        $(`#article-${index}`).find('.product-detail__error').text("99以下の半角数字をご入力ください。");
        $(`#article-${index}`).find('.product-detail__error').removeClass('display-none');
        // カートに追加不可
        $(this).parents('.product-purchase').find('.btn-add-cart').removeClass('cart-button');
        $(this).parents('.product-purchase').find('.btn-add-cart').addClass('cart-button-off');
        // 半角数字
        if (!inputQuantityVal || inputQuantityVal.match(/^\d+$/)) {
            // 「+」「-」ボタンをactive
            $(this).parents('.product-purchase').find('.button').removeClass('count-button-off');
        }
        // 半角数字ではない
        if (!inputQuantityVal.match(/^\d+$/)) {
            // 「+」「-」ボタンをoff
            $(this).parents('.product-purchase').find('.button').addClass('count-button-off');
        }
        return;
    }
    // 2桁未満の半角数字が入力された場合
    if  (inputQuantityVal.match(/^([0-9]{0,2})$/)) {
        // エラー非表示
        $(`#article-${index}`).find('.product-detail__error').text("");
        $(`#article-${index}`).find('.product-detail__error').addClass('display-none');
        // 「+」「-」ボタンをactive
        $(this).parents('.product-purchase').find('.button').removeClass('count-button-off');
        // 入力された数字が0ならカートに追加ボタンは押せない
        if (inputQuantityVal == 0) {
            $(this).parents('.product-purchase').find('.btn-add-cart').removeClass('cart-button');
            $(this).parents('.product-purchase').find('.btn-add-cart').addClass('cart-button-off');
            return
        }
        // 0以外の2桁以下の数字が入力されていればカートに追加ボタンを押せる
        // エラー文非表示
        $(`#article-${index}`).find('.product-detail__error').text("");
        $(`#article-${index}`).find('.product-detail__error').addClass('display-none');
        // カートに追加ボタンを活性化
        $(this).parents('.product-purchase').find('.btn-add-cart').addClass('cart-button');
        $(this).parents('.product-purchase').find('.btn-add-cart').removeClass('cart-button-off');
    }
})

/*-------------------------------------------*/
/*  商品数量 [-]をクリックした時
/*-------------------------------------------*/
$(document).on('click', '.button.btn-down', function() {
    var index = $(this).data('index');
    var productCode = $(this).parents('.product-purchase').data('product-code');
    var inputQuantity = $(this).parent().find('input.quantity');
    var thisQuantityVal = Number($(inputQuantity).val());
    var quantity = 0;

    // 現在の値が0の時はマイナスできないので0をinputに入れる。
    if (thisQuantityVal === 0) {
        nextQuantityVal = 0
        $(this).parents('.product-purchase').find('.btn-add-cart').removeClass('cart-button');
        $(this).parents('.product-purchase').find('.btn-add-cart').addClass('cart-button-off');
    }
    // 現在の値が1以上の時はマイナスする。
    if (thisQuantityVal >= 1){
        nextQuantityVal = thisQuantityVal -1;
        if (nextQuantityVal === 0) {
            $(this).parents('.product-purchase').find('.btn-add-cart').removeClass('cart-button');
            $(this).parents('.product-purchase').find('.btn-add-cart').addClass('cart-button-off');
        }
        if (nextQuantityVal > 99) {
            $(this).parents('.product-purchase').find('.btn-add-cart').removeClass('cart-button');
            $(this).parents('.product-purchase').find('.btn-add-cart').addClass('cart-button-off');
        }
        if (nextQuantityVal <= 99 && nextQuantityVal > 0) {
            $(this).parents('.product-purchase').find('.btn-add-cart').addClass('cart-button');
            $(this).parents('.product-purchase').find('.btn-add-cart').removeClass('cart-button-off');
            $(`#article-${index}`).find('.product-detail__error').addClass('display-none');
        }
    }
    $(inputQuantity).val(nextQuantityVal);
})

/*-------------------------------------------*/
/*  商品数量 [＋]をクリックした時
/*-------------------------------------------*/
$(document).on('click', '.button.btn-up', function() {
    var index = $(this).data('index');
    var productCode = $(this).parents('.product-purchase').data('product-code');
    var inputQuantity = $(this).parent().find('input.quantity');
    var thisQuantityVal = Number($(inputQuantity).val());

    // 現在の値が99もしくは99以上の場合
    if (thisQuantityVal >= 99) {
        nextQuantityVal = 99;
        // エラー表示
        $(`#article-${index}`).find('.product-detail__error').text("99個以上一度にご注文いただけません。");
        $(`#article-${index}`).find('.product-detail__error').removeClass('display-none');
    }
    // 現在の値が99未満の場合
    if (thisQuantityVal < 99) {
        nextQuantityVal = thisQuantityVal +1;
        // エラー文非表示
        $(`#article-${index}`).find('.product-detail__error').text("");
        $(`#article-${index}`).find('.product-detail__error').addClass('display-none');
        // カートに追加ボタンを活性化
        $(this).parents('.product-purchase').find('.btn-add-cart').addClass('cart-button');
        $(this).parents('.product-purchase').find('.btn-add-cart').removeClass('cart-button-off');
    }
    $(inputQuantity).val(nextQuantityVal);
})

/*-------------------------------------------*/
/*  [カートに追加]を押したとき
/*-------------------------------------------*/
$(document).on('click', '.btn-add-cart', function(){
    usePoint = 0;
    const index = $(this).data('index');
    const productCode = $(this).parents('.product-purchase').data('product-code');
    const inputQuantityVal = $(this).parents('.product-purchase').find('input.quantity').val();
    const requestType = $(this).parents('.product-purchase').data('type'); // カートからの処理か一覧からの処理か
    const inputQuantity = $(this).parents('.product-purchase').find('input.quantity')
    const cartButton = $(this)

    // 万が一0でカートに追加できてしまった時リターンする
    if (inputQuantityVal == 0) {
        $(`#article-${index}`).find('.product-detail__error').text('0以上の数字でなければカートに追加いただけません。');
        $(`#article-${index}`).find('.product-detail__error').removeClass('display-none');
        return
    }

    $.when(
        ajxCreateOrUpdateCart(productCode, inputQuantityVal, requestType)
    ).then(function (res){
        if (res.result === 'over') {
            const limitAdd = 99 - Number(res.count)
            // エラー表示
            if (limitAdd > 0) {
                $(`#article-${index}`).find('.product-detail__error').text(`すでにカートに${res.count}個保存されているため、${limitAdd}個までであれば追加できます。`);
                $(`#article-${index}`).find('.product-detail__error').removeClass('display-none');
            }
            if (limitAdd <= 0) {
                $(`#article-${index}`).find('.product-detail__error').text(`カートに${res.count}個保存されています。99個以上はカートに追加いただけません。`);
                $(`#article-${index}`).find('.product-detail__error').removeClass('display-none');
            }
            return
        }
        inputQuantity.val(0);
        calculateSumTotal(usePoint);
        cartCount(0);  // 0: ヘッダーに数章セット 1: ご注文手続きのボタン可否確認 2: 0.1両方実行
        addedCart();
        cartButton.removeClass('cart-button');
        cartButton.addClass('cart-button-off');
        console.log('エラー消えるはず');
        $(`#article-${index}`).find('.product-detail__error').addClass('display-none');
    }).catch(function (err) {
        console.log('err');
    })
});

/**********************************************
*  FUNCTION
***********************************************/
/*-------------------------------------------*/
/*  カートに追加直後「カートに追加しました」表示を出す
/*-------------------------------------------*/
function addedCart() {
    $('.add-cart').fadeIn();
    setTimeout( function() {
        $('.add-cart').fadeOut();
    } , 1500 );
}
