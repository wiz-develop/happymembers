<?php
/**
 * ハッピーファミリー会員サイト API v1
 *
 * 基幹システム連携
 */
header("Content-Type: application/json; charset=utf-8");
require("../common/autoload.php");

$json = file_get_contents("php://input");
$contents = json_decode($json, true);

// 認証キー
$postKey = $contents['key'];
// 関数名
$funcName = $contents['funcName'];
// 引数
$params = $contents['params'] ?? [];

// 認証キーが合致しているか
if (!$postKey || $postKey != $KEY) {
    throw new Exception('Key is invalid. Cannot Access.');
}

print(tryCatch($funcName, $params, true));
