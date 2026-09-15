/**********************************************
*  イベント
***********************************************/
/*-------------------------------------------*/
/*  ロードイベント
/*-------------------------------------------*/
jQuery(function($){
    let isOrderSubmitting = false;
    // usePoint = 0;
    // calculateSumTotal(usePoint);

    input = $('#change-mile_point').find('input#use_point');
    // mPoint = $('#for-mile-discount').data('m-point');
    mPoint = $('#use-mile_point').data('new_m_point');
    famProdTotal = $('#for-mile-discount').data('fam-prod');
    mbrCombineStatus = $('#page-product').data('mbr_combine_status');
    var deliveryAddress = $('#change_delivery-address').find('[name=address]').val();

    if (mPoint < 500) {
        $('#use-mile_point').find('.change').css({"cssText" : "display: none !important"});
        $('#judge-use').text('所持マイルが500マイル以上であれば、マイルを利用できます。')
        $('#use-mile.item.check').removeClass('cannot-purchase')
    } else if (famProdTotal === 0) {
        $('#use-mile_point').find('.change').css({"cssText" : "display: none !important"});
        $('#judge-use').text('割引対応商品がカートにありません。')
        $('#use-mile.item.check').removeClass('cannot-purchase')
    } else if (famProdTotal < 500) {
        $('#use-mile_point').find('.change').css({"cssText" : "display: none !important"});
        $('#judge-use').text('割引対象商品の合計金額が500円以上あればマイルを利用できます。')
        $('#use-mile.item.check').removeClass('cannot-purchase')
    } else if (mPoint <= famProdTotal) {
        maxDiscount = mPoint;
        error = "所持マイル以上のマイルの利用はできません。"
    } else if (mPoint >= famProdTotal) {
        maxDiscount = famProdTotal;
        error = "ファミリー割引商品の合計金額以上の割引は利用できません。"
    }

    if (mbrCombineStatus === 1 || mbrCombineStatus === 3) {
        if (typeof maxDiscount != 'undefined') {
            $('#judge-use').text('最大ご利用可能マイルは' + maxDiscount + 'です。');
        }
    }

    /*-------------------------------------------*/
    /*  マイルポイント 入力時
    /*-------------------------------------------*/
    input.on('input', function(event) {
        inputPoint = input.val();

        if (inputPoint == 0) {
            $('#error-use_point').text('');
            $("#btn-use_mile").css("pointer-events", "auto");
        } else if(inputPoint > 0 && inputPoint < 500) {
            $('#error-use_point').text('500マイル以上からご利用いただけます。');
            $("#btn-use_mile").css("pointer-events", "none");
        } else if (inputPoint > maxDiscount) {
            $('#error-use_point').text(error);
            $("#btn-use_mile").css("pointer-events", "none");
        } else {
            $('#error-use_point').text('');
            $("#btn-use_mile").css("pointer-events", "auto");
        }
    })

    if ($('#use-mile-point').length) {
        if ($("input#use_point").val().length == 0) {
            $("#btn-use_mile").css("pointer-events", "none");
        }
    }
    /*-------------------------------------------*/
    /*  マイルポイント「確定」クリック時
    /*-------------------------------------------*/
    $('#btn-use_mile').on('click', function(event){
        usePoint = input.val();

        if (!usePoint.match(/^([1-9]\d*|0)$/)) {
            $('.check-input_only_int').css({"cssText" : "display: block !important"});
            return;
        }
        $('.check-input_only_int').css({"cssText" : "display: none !important"});

        if (usePoint == 0) {
            $('#confirm-point_num').html(usePoint);
            $('#use-mile_point').find('.change').removeClass('display-none')
            $('#change-mile_point').addClass('display-none');
        } else if(maxDiscount >= usePoint && usePoint >= 500) {
            $('#confirm-point_num').html(usePoint);
            $('#use-mile_point').find('.change').removeClass('display-none')
            $('#change-mile_point').addClass('display-none');
            // $('#change-mile_point').css({"cssText" : "display: none !important"});
        }

        if ($(this).parents('.item.check').hasClass('cannot-purchase')) {
            $(this).parents('.item.check').removeClass('cannot-purchase')
        }

        // 合計金額パネルから使用マイル分を差し引く
        // マイルポイントとお届け時間両方が「確定」であれば非活性のボタンを活性にする
        calculateSumTotal(usePoint, 'order');
    })

    /*-------------------------------------------*/
    /*  マイルポイント「変更する」クリック時
    /*-------------------------------------------*/
    $('#btn-change_mile').on('click', function(event){
        $('#use-mile_point').find('.change').addClass('display-none')
        $('#change-mile_point').removeClass('display-none');

        // マイルポイント変更中は購入できない。
        if (!$(this).parents('.item.check').hasClass('cannot-purchase')) {
            $(this).parents('.item.check').addClass('cannot-purchase')
        }

        // 「ご注文確定」を非活性にする
        $('.order_completion').each(function(index, element){
            $(this).addClass('order-procedure_off');
            $(this).removeClass('order-procedure');
        })
    })

    /*-------------------------------------------*/
    /*  お届け時間「確定」クリック時
    /*-------------------------------------------*/
    $('#btn-confirm_day').on('click', function(event){
        deliveryDay = $('#change_delivery-request').find('[name=time]').val();
        $('#delivery-request').find('.change__detail').text(deliveryDay);
        $('#delivery-request').removeClass('display-none')
        $('#change_delivery-request').addClass('display-none')

        if ($(this).parents('.item.check').hasClass('cannot-purchase')) {
            $(this).parents('.item.check').removeClass('cannot-purchase')
        }

        // マイルポイント・お届け時間・送り先が「確定」であれば非活性のボタンを活性にする
        if (!$('.item.check').hasClass('cannot-purchase')) {
            $('.order_completion').removeClass('order-procedure_off');
            $('.order_completion').addClass('order-procedure');
        }
    })

    /*-------------------------------------------*/
    /*  お届け時間「変更」クリック時
    /*-------------------------------------------*/
    $('#btn-change_day').on('click', function(event){
        $('#delivery-request').addClass('display-none')
        $('#change_delivery-request').removeClass('display-none')

         // お届け時間変更中は購入できない。
        if (!$(this).parents('.item.check').hasClass('cannot-purchase')) {
            $(this).parents('.item.check').addClass('cannot-purchase')
        }

        // 「ご注文確定」を非活性にする
        $('.order_completion').each(function(index, element){
            $(this).addClass('order-procedure_off');
            $(this).removeClass('order-procedure');
        })
    })

    /*-------------------------------------------*/
    /*  送り先「確定」クリック時
    /*-------------------------------------------*/
    $('#btn-confirm_address').on('click', function(event){
        deliveryAddress = $('#change_delivery-address').find('[name=address]').val();
        if (deliveryAddress == 0) {
            $('.ke-address').removeClass('display-none');
            $('.re-address').addClass('display-none');
        } else if (deliveryAddress == 1) {
            $('.ke-address').addClass('display-none');
            $('.re-address').removeClass('display-none');
        }
        $('#delivery-address').removeClass('display-none');
        $('#change_delivery-address').addClass('display-none');

        if ($(this).parents('.item.check').hasClass('cannot-purchase')) {
            $(this).parents('.item.check').removeClass('cannot-purchase');
        }

        // マイルポイント・お届け時間・送り先が「確定」であれば非活性のボタンを活性にする
        if (!$('.item.check').hasClass('cannot-purchase')) {
            $('.order_completion').removeClass('order-procedure_off');
            $('.order_completion').addClass('order-procedure');
        }
    })

    /*-------------------------------------------*/
    /*  送り先「変更」クリック時
    /*-------------------------------------------*/
    $('#btn-change_address').on('click', function(event){
        $('#delivery-address').addClass('display-none');
        $('#change_delivery-address').removeClass('display-none');

         // 送り先変更中は購入できない。
        if (!$(this).parents('.item.check').hasClass('cannot-purchase')) {
            $(this).parents('.item.check').addClass('cannot-purchase');
        }

        // 「ご注文確定」を非活性にする
        $('.order_completion').each(function(index, element){
            $(this).addClass('order-procedure_off');
            $(this).removeClass('order-procedure');
        })
    })


    /*-------------------------------------------*/
    /*  ご注文内容確定ボタンクリック時
    /*-------------------------------------------*/
    $('.order_completion').on('click', function(event) {

        // 二重クリック防止
        if (isOrderSubmitting) {
            return;
        }
        isOrderSubmitting = true;

        const orderButtons = $('.order_completion');
        const originalButtonText = orderButtons.first().text().trim();
        const orderToken = $('#page-product').data('order-token');
        const orderNonce = $('#page-product').data('order-nonce');
        orderButtons
            .addClass('order-procedure_off')
            .removeClass('order-procedure')
            .text('注文処理中...');
        $('.order-submit-error').remove();

        deliveryRequestTime = $('#delivery-request').find('.change__detail').text();
        usePoint = input.val();
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            cache: false,
            data: {
                'action' : 'send_order_confirmation_mail',
                'delivery_request_time' : deliveryRequestTime,
                'use_point' : usePoint,
                'delivery_address' : deliveryAddress,
                'order_token' : orderToken,
                'security' : orderNonce,
            },
            dataType : "json",
        }).done(function(data) {
            if (data.code !== 0) {
                orderButtons.first().before(
                    $('<div class="order-submit-error error-message"></div>').text(data.message || '注文を処理できませんでした。カートからやり直してください。')
                );
                orderButtons.text(originalButtonText);
                return;
            }
            window.location.href = homeUrl+"/cart/completion";
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            const response = XMLHttpRequest.responseJSON || {};
            orderButtons.first().before(
                $('<div class="order-submit-error error-message"></div>').text(response.message || '通信状況を確認できません。重複注文を防ぐため、カートから注文状況をご確認ください。')
            );
            orderButtons.text(originalButtonText);
            console.log("function       : send_order_confirmation_mail");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
        });
    })
})
