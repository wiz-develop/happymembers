<?php
/**
 * Support custom post type with bogo.
 *
 * @param array $localizable Supported post types.
 * @return array
 */

/*-------------------------------------------*/
/*  注文確認メール送付
/*-------------------------------------------*/
function make_order_email($order_product_list)
{
    $mail_raw->body = '
    <!DOCTYPE html>
        <html lang="ja" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
            <head>
                <title></title>
                <meta charset="utf-8"/>
                <meta content="width=device-width,initial-scale=1" name="viewport"/>
                <!--[if mso]>
                <xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
            </head>
            <body style="background-color:#fff;margin:0;padding:0;-webkit-text-size-adjust:none;text-size-adjust:none">
                <div>ご注文番号：</div>
                <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff" width="100%">
                    <thead>
                        <th>商品コード</th>
                        <th>商品名</th>
                        <th>購入単価</th>
                        <th>個数</th>
                        <th>小計</th>
                    </thead>
                    <tbody>
                        '.$order_product_list.'
                    </tbody>
                </table>
                <!-- End -->
            </body>
        </html>
    '; // 本文を変更
    return $Mail_raw;
}
