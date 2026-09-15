<?php
/**
 * 会員登録状況更新 API v1
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

// バリデート
$validateResult = validation_api($params);

// メールアドレスチェック
if ($params['web_member_email'] !== $params['old_web_member_email']) {
    if (empty($params['web_member_email'])) {
        $validateResult['web_member_email'] = 'メールアドレスは必ずご入力ください。';
    } else {
        if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD", $params['web_member_email'])) {
            $validateResult['web_member_email'] = 'メールアドレスの形式が違います。';
        }
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

// メールアドレス
$result['web_member_email'] = is_wp_error(
    wp_update_user(
        [
            'ID' => $params['wp_user_id'],
            'user_email' => $params['web_member_email']
        ]
    )
);

// 各会員ID
$old_happy_id = get_user_meta($params['wp_user_id'], 'happy_id', true);
$old_excellent_id = get_user_meta($params['wp_user_id'], 'excellent_id', true);
$old_is_valid = get_user_meta($params['wp_user_id'], 'is_valid', true);
$old_updated_at = get_user_meta($params['wp_user_id'], 'updated_at', true);

// 対象メタデータ
$metadata = [];
$metadata['happy_id'] = [
    'new' => $params['happy_id'],
    'old' => $old_happy_id
];
$metadata['excellent_id'] = [
    'new' => $params['excellent_id'],
    'old' => $old_excellent_id
];
$metadata['is_valid'] = [
    'new' => $params['is_valid'],
    'old' => $old_is_valid
];
$metadata['updated_at'] = [
    'new' => date_i18n('Y-m-d H:i:s'),
    'old' => $old_updated_at
];
if (isset($metadata)) {
    foreach ($metadata as $key => $value) {
        $result[$key] = update_user_meta($params['wp_user_id'], $key, $value['new'], $value['old']);
    }
}

echo json_encode($result);
