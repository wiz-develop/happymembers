<?php
/**
 * 会員登録状況照会 API v1
 *
 * 基幹システム連携
 */
require("../common/config.php");
require_once($CMS_PATH . '/wp-load.php'); // functionを読んでることになる
// require("assets/api/v1/common/autoload.php"); // functionで読んでる

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

// 名前が条件ある場合先に検索しハッピーID、エクセレントIDを取得
$memberAllByName = [];
if (($params['mbr_nm'] ?? "") !== "" || ($params['mbr_knm'] ?? "") !== "") {
    $memberAllByNameParams = [
        $params['mbr_id_hp'],
        $params['mbr_id_ex'],
        $params['mbr_nm'],
        $params['mbr_knm'],
    ];
    $memberAllByName = tryCatch('API_SerchMember_AllByName', $memberAllByNameParams, false);
    $memberAllByName = null;
}

// 各対象会員ID
$targetHappyMemberIds = ($params['mbr_id_hp'] ?? "") !== "" ? [$params['mbr_id_hp']] : [];
$targetExcellentMemberIds = ($params['mbr_id_ex'] ?? "") !== "" ? [$params['mbr_id_ex']] : [];
if (!empty($memberAllByName) && $memberAllByName->code === 0) {
    foreach ($memberAllByName->result as $memberAllByNameValue) {
        // ハッピー会員ID
        if ($memberAllByNameValue->c_kd === $MEM_TYPE_HAPPY) {
            $targetHappyMemberIds[] = $memberAllByNameValue->c_id;
        }

        // エクセレント会員ID
        if ($memberAllByNameValue->c_kd === $MEM_TYPE_EXCELLENT) {
            $targetExcellentMemberIds[] = $memberAllByNameValue->c_id;
        }
    }
}

// WP.wp_users+ハッピーID、エクセレントIDを取得
$query = "SELECT
    wp_users.ID AS wp_user_id,
    wp_users.user_email AS web_member_email,
    wp_users.user_login AS login_id,
    happy_id_keys.meta_value AS happy_id,
    excellent_id_keys.meta_value AS excellent_id,
    is_valid_keys.meta_value AS is_valid,
    created_at_keys.meta_value AS created_at,
    updated_at_keys.meta_value AS updated_at
    FROM
    (
    SELECT
        *
    FROM
        `wp_users`
    ) AS wp_users
    LEFT JOIN(
    SELECT
        wp_users.ID,
        wp_usermeta.meta_key,
        wp_usermeta.meta_value
    FROM
        (
        SELECT
            *
        FROM
            wp_usermeta
        WHERE
            wp_usermeta.meta_key = 'happy_id'
        ) AS wp_usermeta -- ハッピーID
        LEFT JOIN wp_users ON wp_usermeta.user_id = wp_users.ID
    ) AS happy_id_keys ON happy_id_keys.ID = wp_users.ID
    LEFT JOIN(
    SELECT
        wp_users.ID,
        wp_usermeta.meta_key,
        wp_usermeta.meta_value
    FROM
        (
        SELECT
            *
        FROM
            wp_usermeta
        WHERE
            wp_usermeta.meta_key = 'excellent_id'
        ) AS wp_usermeta -- エクセレントID
        LEFT JOIN wp_users ON wp_usermeta.user_id = wp_users.ID
    ) AS excellent_id_keys ON excellent_id_keys.ID = wp_users.ID
    LEFT JOIN(
    SELECT
        wp_users.ID,
        wp_usermeta.meta_key,
        wp_usermeta.meta_value
    FROM
        (
        SELECT
            *
        FROM
            wp_usermeta
        WHERE
            wp_usermeta.meta_key = 'is_valid'
        ) AS wp_usermeta -- 状況
        LEFT JOIN wp_users ON wp_usermeta.user_id = wp_users.ID
    ) AS is_valid_keys ON is_valid_keys.ID = wp_users.ID
    LEFT JOIN(
    SELECT
        wp_users.ID,
        wp_usermeta.meta_key,
        wp_usermeta.meta_value
    FROM
        (
        SELECT
            *
        FROM
            wp_usermeta
        WHERE
            wp_usermeta.meta_key = 'create_at'
        ) AS wp_usermeta -- 登録日
        LEFT JOIN wp_users ON wp_usermeta.user_id = wp_users.ID
    ) AS created_at_keys ON created_at_keys.ID = wp_users.ID
    LEFT JOIN(
    SELECT
        wp_users.ID,
        wp_usermeta.meta_key,
        wp_usermeta.meta_value
    FROM
        (
        SELECT
            *
        FROM
            wp_usermeta
        WHERE
            wp_usermeta.meta_key = 'updated_at'
        ) AS wp_usermeta -- 更新日
        LEFT JOIN wp_users ON wp_usermeta.user_id = wp_users.ID
    ) AS updated_at_keys ON updated_at_keys.ID = wp_users.ID
    LEFT JOIN(
    SELECT
        wp_users.ID,
        wp_usermeta.meta_key,
        wp_usermeta.meta_value
    FROM
        (
        SELECT
            *
        FROM
            wp_usermeta
        WHERE
            wp_usermeta.meta_key = 'wp_user_level'
        ) AS wp_usermeta -- ユーザーレベル
        LEFT JOIN wp_users ON wp_usermeta.user_id = wp_users.ID
    ) AS wp_user_level_keys ON wp_user_level_keys.ID = wp_users.ID
";

$queryConditions = [];
// ログインID 絞り込み
if (($params['mbr_id_login'] ?? "") !== "") {
    $queryConditions[] = 'wp_users.user_login = "'.$params['mbr_id_login'].'"';
}

// TODO: ハッピーIDは必ず存在する前提となっている。
// ハッピー会員ID,エクセレント会員IDが入力されていない状態で氏名フリガナを入力されている場合は会員絞り込みのみOR検索とする
if (($params['mbr_id_hp'] ?? "") === "" && ($params['mbr_id_ex'] ?? "") === "") {
    // 氏名もしくはフリガナで検索されていて対象ユーザーがいない場合
    if ((($params['mbr_nm'] ?? "") !== "" || ($params['mbr_knm'] ?? "") !== "") &&
        empty($targetHappyMemberIds) &&
        empty($targetExcellentMemberIds)
    ) {
        $queryConditions[] = '0';
    }

    // ハッピー会員ID絞り込み
    if (!empty($targetHappyMemberIds)) {
        $memberIdsOrQuery = '';
        if (!empty($targetExcellentMemberIds)) {
            $memberIdsOrQuery .= '(';
        }
        $memberIdsOrQuery .= 'happy_id_keys.meta_value in ("' . implode('","', $targetHappyMemberIds) . '")';

        // エクセレント会員ID絞り込み
        if (!empty($targetExcellentMemberIds)) {
            $memberIdsOrQuery
                .= ' or excellent_id_keys.meta_value in ("' . implode('","', $targetExcellentMemberIds) . '"))';
        }

        $queryConditions[] = $memberIdsOrQuery;
    }
} else {
    // ハッピー会員ID絞り込み
    if (!empty($targetHappyMemberIds) && $params['mbr_id_hp']) {
        $queryConditions[] = 'happy_id_keys.meta_value in ("' . implode('","', $targetHappyMemberIds) . '")';
    }

    // エクセレント会員ID絞り込み
    if (!empty($targetExcellentMemberIds) && $params['mbr_id_ex']) {
        $queryConditions[] = 'excellent_id_keys.meta_value in ("' . implode('","', $targetExcellentMemberIds) . '")';
    }
}

// 条件絞り込み
$queryConditions[] = 'wp_user_level_keys.meta_value = 0'; // 購読者のみ
if (isset($queryConditions)) {
    foreach ($queryConditions as $i => $queryCondition) {
        if ($i === 0) {
            $query .= ' WHERE ';
        }

        $query .= $queryCondition;

        if (isset($queryConditions[$i + 1])) {
            $query .= ' AND ';
        }
    }
}

// データ取得
// WPから会員情報取得
$memberDataFromWp = $wpdb->get_results($query);

// var_dump($query); // ネットワークで見れる

// 対象ハッピーID pluck
$targetHappyIds = array_filter(
    array_unique(
        array_column(
            json_decode(
                json_encode($memberDataFromWp),
                true
            ),
            'happy_id'
        )
    )
);

// 対象エクセレントID pluck
$targetExcellentIds = array_filter(
    array_unique(
        array_column(
            json_decode(
                json_encode($memberDataFromWp),
                true
            ),
            'excellent_id'
        )
    )
);

// エクセレントID ブランチID
$brIds = [];
for ($i = 0; $i < count($targetExcellentIds); $i++) {
    $brIds[] = '001';
}

// 対象ハッピーIDから会員情報を一括で取得
$happyMemberDetailIndex = tryCatch('API_GetMemberDetail_M_hp', [array_values($targetHappyIds)], false);
$happyMemberDetailIndex = null;

// 対象エクセレントIDから会員情報を一括で取得
$excellentMemberDetailIndex = tryCatch('API_GetMemberDetail_M_ex', [array_values($targetExcellentIds), $brIds], false);
$excellentMemberDetailIndex = null;

// WP+基幹システムデータを合成
foreach ($memberDataFromWp as $memberDataFromWpIndex => &$memberDataFromWpValue) {
    // 氏名
    $memberDataFromWpValue->mbr_nm = '';
    // ﾌﾘｶﾞﾅ
    $memberDataFromWpValue->mbr_knm = '';
    // 生年月日
    $memberDataFromWpValue->mbr_bth = '';

    // エクセレント会員IDで氏名を取得
    if ($memberDataFromWpValue->excellent_id !== "") {
        $memberDataFromWpValue->mbr_nm = current(
            array_filter(
                $excellentMemberDetailIndex->result,
                function ($val) use ($memberDataFromWpValue) {
                    return $val->mbr_id === $memberDataFromWpValue->excellent_id;
                }
            )
        )
        ->mbr_nm ?? '';

        $memberDataFromWpValue->mbr_knm = current(
            array_filter(
                $excellentMemberDetailIndex->result,
                function ($val) use ($memberDataFromWpValue) {
                    return $val->mbr_id === $memberDataFromWpValue->excellent_id;
                }
            )
        )
        ->mbr_knm ?? '';

        $memberDataFromWpValue->mbr_bth = current(
            array_filter(
                $excellentMemberDetailIndex->result,
                function ($val) use ($memberDataFromWpValue) {
                    return $val->mbr_id === $memberDataFromWpValue->excellent_id;
                }
            )
        )
        ->mbr_bth ?? '';

        // 名前が取得できない場合
        if ($memberDataFromWpValue->mbr_nm === '') {
            $fetchMemberInfo = tryCatch('API_GetMemberDetail_ex', [$memberDataFromWpValue->excellent_id], false);
            $fetchMemberInfo = null;
            if (isset($fetchMemberInfo)) {
                $memberDataFromWpValue->mbr_nm = $fetchMemberInfo->mbr_nm;
                $memberDataFromWpValue->mbr_knm = $fetchMemberInfo->mbr_knm;
                $memberDataFromWpValue->mbr_bth = $fetchMemberInfo->mbr_bth;
            }
        }
    }

    // ハッピー会員IDで氏名を取得
    if ($memberDataFromWpValue->happy_id !== "") {
        $memberDataFromWpValue->mbr_nm = current(
            array_filter(
                $happyMemberDetailIndex->result,
                function ($val) use ($memberDataFromWpValue) {
                    return $val->mbr_id === $memberDataFromWpValue->happy_id;
                }
            )
        )
        ->mbr_nm ?? '';

        $memberDataFromWpValue->mbr_knm = current(
            array_filter(
                $happyMemberDetailIndex->result,
                function ($val) use ($memberDataFromWpValue) {
                    return $val->mbr_id === $memberDataFromWpValue->happy_id;
                }
            )
        )
        ->mbr_knm ?? '';

        $memberDataFromWpValue->mbr_bth = current(
            array_filter(
                $happyMemberDetailIndex->result,
                function ($val) use ($memberDataFromWpValue) {
                    return $val->mbr_id === $memberDataFromWpValue->happy_id;
                }
            )
        )
        ->mbr_bth ?? '';
    }

    // // 状況
    // $isValidStatusConf = [
    //     0 => '仮登録',
    //     1 => '確定'
    // ];
    // $memberDataFromWpValue->is_valid =
    //     $memberDataFromWpValue->is_valid !== '' ?
    //         $isValidStatusConf[$memberDataFromWpValue->is_valid] :
    //         $isValidStatusConf[0];
}

$returnMemberData = json_decode(
    json_encode(
        array_values(
            $memberDataFromWp
        )
    ),
    true
);

// 出力方法
switch ($params['orderby']) {
    // 登録日順
    case '0':
        $sortKey = [];
        foreach ($returnMemberData as $index => $val) {
            $sortKey[$index] = $val['created_at'];
        }
        array_multisort($sortKey, SORT_DESC, $returnMemberData);
        break;
    // フリガナ順
    case '1':
        $sortKey = [];
        foreach ($returnMemberData as $index => $val) {
            $sortKey[$index] = $val['mbr_knm'];
        }
        array_multisort($sortKey, SORT_ASC, $returnMemberData);
        break;
    // ハッピーID順
    case '2':
        $sortKey = [];
        foreach ($returnMemberData as $index => $val) {
            $sortKey[$index] = $val['happy_id'] !== "" ? $val['happy_id'] : "9999999$index";
        }
        array_multisort($sortKey, SORT_ASC, $returnMemberData);
        break;
    // エクセレントID順
    case '3':
        $sortKey = [];
        foreach ($returnMemberData as $index => $val) {
            $sortKey[$index] = $val['excellent_id'] !== "" ? $val['excellent_id'] : "9999999$index";
        }
        array_multisort($sortKey, SORT_ASC, $returnMemberData);
        break;
}

// jsonを返却
if ($contents['outputCsv'] !== true) {
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($returnMemberData);
    exit;
}

// CSVを返却、対象のカラム名
$csvTarget = [
    'happy_id',
    'excellent_id',
    'mbr_nm',
    'mbr_knm',
    'mbr_bth',
    'login_id',
    'web_member_email',
    'is_valid',
    'created_at',
    'updated_at',
];

// 項目名
$csvHeader = [
    'ハッピー会員ID',
    'エクセレント会員ID',
    '氏名',
    'フリガナ',
    '生年月日',
    'ログインID',
    'メールアドレス',
    '状況',
    '登録日',
    '更新日',
];

$csvData = [];
foreach ($returnMemberData as $index => $memberDataFromWpValue) {
    foreach ($csvTarget as $csvTargetValue) {
        $csvData[$index][] = strval($memberDataFromWpValue[$csvTargetValue]);
    }
}
putCsv($csvHeader, $csvData);
