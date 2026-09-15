<?php
/**
 * 会員登録状況削除 API v1
 *
 */
require("../common/config.php");
require_once($CMS_PATH . '/wp-load.php');
require_once($CMS_PATH . '/wp-admin/includes/user.php');
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

// カート削除
$cartsConditions = [
    'user_id' => intval($params['wp_user_id'])
];
$wpdb->delete('carts', $cartsConditions);

// mile削除
$milesConditions = [
    'user_id' => intval($params['wp_user_id'])
];
$wpdb->delete('miles', $milesConditions);

$deleteResult = wp_delete_user(intval($params['wp_user_id']));
echo json_encode($deleteResult);
