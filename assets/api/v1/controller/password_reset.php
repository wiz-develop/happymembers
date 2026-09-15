<?php
/**
 * パスワード再設定 API v1
 * Reissue:再発行 Reset:再設定
 *
 */
require("../common/config.php");
require_once($CMS_PATH . '/wp-load.php');
global $wpdb;


$json = file_get_contents("php://input");
$contents = json_decode($json, true);

// 認証キー
$postKey = $contents['key'];
// 認証キーが合致しているか
if (!$postKey || $postKey != $KEY) {
    throw new Exception('Key is invalid. Cannot Access.');
}

$params = $contents['params'] ?? [];
if (empty($params)) {
    throw new Exception('Params Error');
}

// パスワードバリデーション
if (empty($params['web_pass'])) {
    $validateResult['web_pass'] = 'パスワードは必ずご入力ください。';
} else {
    if (!preg_match("<^[a-z0-9!-/:-@¥[-`{-~]{8,16}+$>", $params['web_pass'])) {
        $validateResult['web_pass'] = '半角英数字記号(英語は小文字のみ利用可能)を含む8文字~16文字でご入力ください。';
    }
    if ($params['web_pass'] !== $params['web_confirmation_pass']) {
        $validateResult['web_confirmation_pass'] = '確認用のパスワードが異なります。';
    }
}

if (!empty($validateResult)) {
    echo json_encode(['error' => $validateResult]);
    die;
}

/********************
 * 下記より更新処理
 ********************/

// アップデート
$result = [];

 // パスワード
$result['web_pass'] = is_wp_error(
    wp_update_user(
        [
            'ID' => $params['wp_user_id'],
            'user_pass' => $params['web_pass']
        ]
    )
);
echo json_encode($result);
