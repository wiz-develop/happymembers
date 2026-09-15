<?php
    require("config.php");
    require_once($CMS_PATH."/wp-load.php");
    
    $from = "happymembers@happyfamily.co.jp";
    $fromtitle = "ハッピーファミリー株式会社";
    $to = $_POST['mail'];
    $totitle = "お客様";
    $subject = "【ハッピーファミリー株式会社】テストメールを送信しました";
    $message = <<< EOD
        ハッピーファミリー会員サイトへ
        WEB会員ご登録手続き中のお客様

        テストメールを送信しました。
        WEB登録フォームへ戻り、引き続き会員登録を行ってください。
        なお、心あたりのないメールの場合は、ご連絡下さい。

        ---------------------------------------------
        ハッピーファミリー株式会社
        〒532-0003
        　大阪市淀川区宮原2-14-14
        　フリーダイヤル  0120-198-141
        ---------------------------------------------
    EOD;

    $mail_result = mail_create_send($to, $from, $fromtitle, $totitle, $subject, $message);
    if ($mail_result) {
        return 'success';
    } else {
        return 'fail';
    }


    function mail_create_send($to, $from, $fromtitle, $totitle, $subject, $message) {
        mb_language("ja");
        mb_internal_encoding('utf-8');
        $mime_type = "application/octet-stream";
        $boundary = '----=_Boundary_' . uniqid(rand(1000,9999) . '_') . '_';
        
        $to = mb_encode_mimeheader(mb_convert_encoding($totitle,"ISO-2022-JP")) . "<" . $to . ">";
        
        $head  = "From: " . mb_encode_mimeheader(mb_convert_encoding($fromtitle,"ISO-2022-JP")) . "<" . $from . "> \n";
        $head .= "MIME-Version: 1.0\n";
        $head .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\n";
        $head .= "Content-Transfer-Encoding: 7bit";
        
        // 本文
        $body = "";
        $body .= "--{$boundary}\n";
        $body .= "Content-Type: text/plain; charset=ISO-2022-JP;" .
            "Content-Transfer-Encoding: 7bit\n";
        $body .= "\n";
        $body .= "{$message}\n";
        $body .= "\n";

        $mail_create_send = mb_send_mail($to, $subject, $body, $head);
        return $mail_create_send;
    }