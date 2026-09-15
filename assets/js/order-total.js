/*-------------------------------------------*/
/*  ロードイベント
/*-------------------------------------------*/
jQuery(function($){
    isClicked = false;

    // 総計計算
    usePoint = 0;
    calculateSumTotal(usePoint);

    // エクセレント会員の場合 order-total.php に使用マイルの項目を追加
    const combineStat = $('#header_info').data('combine_stat');

    if (combineStat === 1 || combineStat === 3) { // 1,3: EXA
        // $('.display_only_EXA').css({"cssText" : "display: block !important"});
        $('.display_only_EXA').removeClass('display-none');
    }
})
/*-------------------------------------------*/
/*  総計計算
/*-------------------------------------------*/
function calculateSumTotal(usePoint, type = null) {
    $.ajax({
        type:"GET",
        url: ajaxUrl,
        cache: false,
        data: {
            'action' : 'calculate_sum_total',
            'use_point' : usePoint,
        },
        dataType : 'json',
    }).done(function(data) {
        console.log(data);
        reflectToOrderTotal(data);
        if (type === 'order') {
            orderLimitCheck(data);
        }
        // reflectToOrderTotal(JSON.parse(data)); // dataType jsonがないと必要みたい。
    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        console.log("function       : calculate_sum_total");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    });
}

/*-------------------------------------------*/
/*  金額を order-total.php へ反映する
/*-------------------------------------------*/
function reflectToOrderTotal(data) {
    productSumTotalYen = new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(data.product_sum_total);
    sumQuantity = data.sum_quantity;
    orderSumTotalYen = new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(data.order_sum_total);
    milePoint = data.mile_point;

    if (data.fee) {
        fee =  new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(data.fee);
    } else {
        fee =  new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(0);
    }

    $('#sub-total').html(productSumTotalYen);
    $('#sum-quantity').html(sumQuantity + '点');
    $('#fee').html(fee);
    $('#mile_point').html('-' + milePoint);
    $('#discounted-price').html(orderSumTotalYen);
    // 【ご注文内容確定】合計金額が50万円以上の場合：購入できないとアラート表示、ボタン押下不可
    if (Number(data.order_sum_total) > 500000) {
        console.log('50万以上');
        // 【ご注文内容確定】
        $('.order_completion').addClass('order-procedure_off');
        $('.order_completion').removeClass('order-procedure');
        // アラート出す
        $('.alert-limit_price').removeClass('display-none');
        return
    }
    // 【ご注文内容確定】合計金額が50万円以下の場合：アラートを削除、ボタン押下可
    $('.order_completion').removeClass('order-procedure_off');
    $('.order_completion').addClass('order-procedure');
    $('.alert-limit_price').addClass('display-none');
}
/*-------------------------------------------*/
/*  マイル割引後の価格が50万円以上であれば購入不可 / 注意書き表示
/*-------------------------------------------*/
function  orderLimitCheck(data) {
    if (data.order_sum_total <= 500000) {
        if (!$('.item.check').hasClass('cannot-purchase')) {
            $('.order_completion').removeClass('order-procedure_off');
            $('.order_completion').addClass('order-procedure');
        }
        $('.alert-limit_price').css({"cssText" : "display: none !important"});
        return;
    }
    $('.alert-limit_price').css({"cssText" : "display: block !important"});
    return;
}
