
// フルパスで書かないとつながらない
// require("functions.php");
// var ajaxUrl = '/cms/wp-admin/admin-ajax.php';

// WordPressでAjaxを使用する場合、urlにはadmin-ajax.phpの絶対パスを指定
// let ajaxUrlA = '<?php echo admin_url('admin-ajax.php'); ?>';
// /*-------------------------------------------*/
// /*  総計計算
// /*-------------------------------------------*/
// function calculateSumTotal(usePoint) {
//     $.ajax({
//         type:"GET",
//         url: ajaxUrl,
//         cache: false,
//         data: {
//             'action' : 'calculate_sum_total',
//             'use_point' : usePoint,
//         },
//     }).done(function(data) {
//         // json = JSON.parse(data);
//         console.log(data);
//         reflectToOrderTotal(JSON.parse(data));
//     }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
//         console.log("function       : calculate_sum_total");
//         console.log("XMLHttpRequest : " + XMLHttpRequest.status);
//         console.log("textStatus     : " + textStatus);
//         console.log("errorThrown    : " + errorThrown.message);
//     });
// }
// /* --- 金額を[order-total]へ反映する ---  */
// function reflectToOrderTotal(data) {
//     totalYen = new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(data.total);
//     sumTotalYen = new Intl.NumberFormat().format(data.sum_total);
//     sumQuantity = data.sum_quantity;
//     if (data.fee) {
//         fee =  data.fee;
//     } else {
//         fee =  0
//     }

//     $('#sub-total').html(totalYen);
//     $('#sum-quantity').html(sumQuantity + '点');
//     $('#sum-total').html('¥' + sumTotalYen);
//     $('#fee').html('¥' + fee);
// }
/*-------------------------------------------*/
/*  カートの数量カウントする
/*-------------------------------------------*/
// function cartCount() {
//     $.ajax({
//         type: 'GET',
//         url: ajaxUrl,
//         cache: false,
//         data: {
//             'action' : 'fetch_cart_list_by_user_id',
//             'communication_type': 1,
//         },
//     }).done(function(data){
//         productNumDom = $('span').hasClass('number-cart_product');
//         if (productNumDom) {
//             $('span.number-cart_product').remove();
//         }
//         if (data !== '0') {
//             $('span.menu-cart').after('<span class="number-cart_product">'+data+'</span>');
//         }
//     }).fail(function (XMLHttpRequest, textStatus, errorThrown){
//         console.log("function       : fetch_cart_list_by_user_id" );
//         console.log("XMLHttpRequest : " + XMLHttpRequest.status);
//         console.log("textStatus     : " + textStatus);
//         console.log("errorThrown    : " + errorThrown.message);
//     });
// }