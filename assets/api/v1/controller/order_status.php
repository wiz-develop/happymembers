<?php
/**
 * 購入状況照会 API v1
 *
 * 基幹システム連携
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

/**
 * 引数詳細
 *   "params": [
 *      "", // [0]ハッピー会員ID string
 *      "", // [1]エクセレント会員ID string
 *      "", // [2]氏名 string
 *      "", // [3]ﾌﾘｶﾞﾅ(半角) string
 *      0,  // [4]発送状況 integer
 *      "", // [5]WEB注文番号開始WBXXYYYYYY string
 *      "", // [6]WEB注文番号終了WBXXYYYYYY string
 *      20210610, // [7]注文日開始YYYYMMDD integer
 *      20211001  // [8]注文日終了YYYYMMDD integer
 *  ]
 */
$params = $contents['params'] ?? [];
if (empty($params)) {
    throw new Exception('Params Error');
}

// 注文日開始, 注文日終了 必ず入れる
$params[7] = $params[7] !== '' ? $params[7] : '20211001';
$params[8] = $params[8] !== '' ? $params[8] : '29991231';

// 名前が条件ある場合先に検索しハッピーID、エクセレントIDを取得
$memberAllByName = [];
if (($params[2] ?? "") !== "" || ($params[3] ?? "") !== "") {
    $memberAllByNameParams = [
        $params[0],
        $params[1],
        $params[2],
        $params[3]
    ];
    $memberAllByName = tryCatch('API_SerchMember_AllByName', $memberAllByNameParams, false);
}

// 各対象ID
$targetHappyMemberIds = ($params[0] ?? "") !== "" ? [$params[0]] : [];
$targetExcellentMemberIds = ($params[1] ?? "") !== "" ? [$params[1]] : [];
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

// WP.orders+ハッピーID、エクセレントIDを取得
$query = "SELECT
    orders.id AS order_id,
    orders.order_code AS order_code,
    orders.user_id AS order_user_id,
    orders.status AS order_status,
    orders.sum_total AS order_sum_total,
    orders.time_type AS time_type,
    orders.zip AS zip,
    orders.address_1 AS address_1,
    orders.address_2 AS address_2,
    orders.syodlv AS syodlv,
    orders.created_at AS order_created_at,
    wp_users.user_login AS web_member_id,
    wp_users.user_email AS web_member_email,
    happy_id_keys.meta_value AS happy_id,
    excellent_id_keys.meta_value AS excellent_id,
    order_products.id AS order_product_id,
    order_products.product_code AS order_product_product_code,
    products.product_name AS product_name,
    order_products.quantity AS order_product_quantity,
    order_products.purchase_price AS order_product_purchase_price,
    order_products.subtotal AS order_product_subtotal,
    order_products.regular_price AS order_product_regular_price
    FROM
    (
    SELECT
        *
    FROM
        orders
    ) AS orders
    LEFT JOIN order_products ON orders.id = order_products.order_id
    LEFT JOIN wp_users ON orders.user_id = wp_users.ID
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
    ) AS happy_id_keys ON happy_id_keys.ID = orders.user_id
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
    ) AS excellent_id_keys ON excellent_id_keys.ID = orders.user_id
    LEFT JOIN(
    SELECT
        wp_postmeta.meta_value AS product_code,
        wp_posts.post_title AS product_name
    FROM
        wp_postmeta
        LEFT JOIN wp_posts ON wp_posts.ID = wp_postmeta.post_id
    WHERE
        wp_postmeta.meta_key = 'product_code' -- 商品マスタ
    ) AS products ON products.product_code = order_products.product_code
";

$queryConditions = [];
// ハッピー会員ID,エクセレント会員IDが入力されていない状態で氏名フリガナを入力されている場合は会員絞り込みのみOR検索とする
if (($params[0] ?? "") === "" && ($params[1] ?? "") === "") {
    // 氏名もしくはフリガナで検索されていて対象ユーザーがいない場合
    if ((($params[2] ?? "") !== "" || ($params[3] ?? "") !== "") &&
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
    if (!empty($targetHappyMemberIds) && $params[0]) {
        $queryConditions[] = 'happy_id_keys.meta_value in ("' . implode('","', $targetHappyMemberIds) . '")';
    }

    // エクセレント会員ID絞り込み
    if (!empty($targetExcellentMemberIds) && $params[1]) {
        $queryConditions[] = 'excellent_id_keys.meta_value in ("' . implode('","', $targetExcellentMemberIds) . '")';
    }
}

// WEB注文番号開始絞り込み
if (($params[5] ?? "") !== "") {
    $queryConditions[] = 'order_code <> "" AND orders.order_code >= "' . $params[5] .'"';
}

// WEB注文番号終了絞り込み
if (($params[6] ?? "") !== "") {
    $queryConditions[] = 'order_code <> "" AND orders.order_code <= "' . $params[6] .'"';
}

// 注文日開始絞り込み
if (($params[7] ?? "") !== "") {
    $queryConditions[] = 'orders.created_at >= "' . $params[7] . '"';
}

// 注文日終了絞り込み
if (($params[8] ?? "") !== "") {
    $queryConditions[] = 'orders.created_at <= ADDDATE("' . $params[8] . '", INTERVAL 1 DAY)';
}

// 条件絞り込み
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
$query .= ' ORDER BY orders.order_code';
$orderDataFromWp = $wpdb->get_results($query);

$deduped = [];
$seen = [];
foreach ($orderDataFromWp as $row) {
    // キー作成
    if (isset($row->order_product_id) && $row->order_product_id !== null && $row->order_product_id !== '') {
        $key = 'opid_' . $row->order_product_id;
    } else {
        $orderIdPart = isset($row->order_id) ? $row->order_id : 'no_order';
        $prodCodePart = isset($row->order_product_product_code) ? $row->order_product_product_code : 'no_code';
        $key = 'ord_' . $orderIdPart . '_pc_' . $prodCodePart;
    }

    // 既出ならスキップ
    if (isset($seen[$key])) {
        continue;
    }
    $seen[$key] = true;
    $deduped[] = $row;
}
$orderDataFromWp = $deduped;

$productNameCache = [];

// 対象ハッピーID pluck
$targetHappyIds = array_filter(
    array_unique(
        array_column(
            json_decode(
                json_encode($orderDataFromWp),
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
                json_encode($orderDataFromWp),
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

// 対象エクセレントIDから会員情報を一括で取得
$excellentMemberDetailIndex = tryCatch('API_GetMemberDetail_M_ex', [array_values($targetExcellentIds), $brIds], false);

// 基幹システムから取得
$requestParams = $params;
// 全て取得する場合はリクエストでは0を渡す
$requestParams[4] = $params[4] === -1 ? 0 : $params[4];
$orderDataFromCoreData = tryCatch('API_SerchWebOrder', $requestParams, false);

// WP+基幹システムデータを合成
foreach ($orderDataFromWp as $orderDataFromWpIndex => &$orderDataFromWpValue) {
    $code = isset($orderDataFromWpValue->order_product_product_code) ? trim(strval($orderDataFromWpValue->order_product_product_code)) : '';

    if ($code === '') {
        // 商品コードがない場合の文言（必要に応じて変更）
        $orderDataFromWpValue->product_name = '(商品コードなし)';
    } else {
        if (isset($productNameCache[$code])) {
            $orderDataFromWpValue->product_name = $productNameCache[$code];
        } else {
            // show_product の require_published = true を使って公開済みタイトルを取得する
            $detail = show_product($code, true);
            $title = isset($detail['title']) ? $detail['title'] : '(非公開または存在しない商品)';
            $productNameCache[$code] = $title;
            $orderDataFromWpValue->product_name = $title;
        }
    }

    // 氏名
    $orderDataFromWpValue->mbr_nm = '';
    // ﾌﾘｶﾞﾅ
    $orderDataFromWpValue->mbr_knm = '';
    // 郵便番号
    $orderDataFromWpValue->pcd = $orderDataFromWpValue->zip;
    // 住所1
    $orderDataFromWpValue->add1 = $orderDataFromWpValue->address_1;
    // 住所2
    $orderDataFromWpValue->add2 = $orderDataFromWpValue->address_2;
    // 商品発送先
    $orderDataFromWpValue->syodlv = $orderDataFromWpValue->syodlv;
    // TEL
    $orderDataFromWpValue->tel = '';
    // 集荷日
    $orderDataFromWpValue->syuka_date = '';
    // 発送状況
    $orderDataFromWpValue->syuka_st = 0;
    // ポジション
    $orderDataFromWpValue->pos = '';
    // 取引利率
    $orderDataFromWpValue->mbr_grd = '';
    // 肩書
    $orderDataFromWpValue->mbr_kata = '';
    // 法人名
    $orderDataFromWpValue->co_nm = '';

    // WEB注文番号がないレコードは排除
    if ($orderDataFromWpValue->order_code === "") {
        unset($orderDataFromWp[$orderDataFromWpIndex]);
        continue;
    }

    // 発送状況, 集荷日を取得
    if ($orderDataFromWpValue->order_code !== "") {
        $orderDataFromWpValue->syuka_st = current(
            array_filter(
                $orderDataFromCoreData->result,
                function ($val) use ($orderDataFromWpValue) {
                    return $val->order_no === $orderDataFromWpValue->order_code;
                }
            )
        )
        ->syuka_st ?? intval($orderDataFromWpValue->order_status);

        $orderDataFromWpValue->syuka_date = current(
            array_filter(
                $orderDataFromCoreData->result,
                function ($val) use ($orderDataFromWpValue) {
                    return $val->order_no === $orderDataFromWpValue->order_code;
                }
            )
        )
        ->syuka_date ?? '';
    }

    // 発送状況が検索条件と違う場合は排除しスキップ
    if ($params[4] !== -1 && $orderDataFromWpValue->syuka_st !== $params[4]) {
        unset($orderDataFromWp[$orderDataFromWpIndex]);
        continue;
    }

    $res_hp = current(
        array_filter(
            $happyMemberDetailIndex->result,
            function ($val) use ($orderDataFromWpValue) {
                return $val->mbr_id === $orderDataFromWpValue->happy_id;
            }
        )
    );

    $res_ex = current(
        array_filter(
            $excellentMemberDetailIndex->result,
            function ($val) use ($orderDataFromWpValue) {
                return $val->mbr_id === $orderDataFromWpValue->excellent_id;
            }
        )
    );

    // MEMBER STATUS設定 ※ハッピーはメンバー会員でない場合「退会中」のステイタスとする
    $hp_status = $MEM_STATUS_WITHDRAWAL;
    if ($res_hp) {
        if ($res_hp->mbr_syu === $MEM_SYUBETSU_MEMBER) {
            $hp_status = $res_hp->mbr_stat;
        }
    }
    $ex_status = $MEM_STATUS_WITHDRAWAL;
    if ($res_ex) {
        $ex_status = $res_ex->mbr_stat;
    }

    // 配送先住所
    if ($hp_status === $MEM_STATUS_ACTIVE) {
        $orderDataFromWpValue->mbr_nm = $res_hp->mbr_nm;
        $orderDataFromWpValue->mbr_knm = $res_hp->mbr_knm;
        $orderDataFromWpValue->pos = $res_hp->pos;
        $orderDataFromWpValue->mbr_grd = $res_hp->mbr_grd;
        $orderDataFromWpValue->mbr_kata = $res_hp->mbr_kata;
        $orderDataFromWpValue->co_nm = $res_hp->co_nm;
        $syodlv_tel = $res_hp->syodlv_tel;
        $syodlv = $orderDataFromWpValue->syodlv;
        $default_syodlv = $res_hp->syodlv;

        // 発送先がデフォルトのときは、送り先の電話番号には商品お届け電話番号を表示
        if ($default_syodlv == $syodlv) {
            $orderDataFromWpValue->tel = $syodlv_tel;
        } else {
            // 発送先を変更したときは、送り先の電話番号にはその住所の携帯番号を設定。携帯番号がない場合は電話番号を設定
            if ($syodlv == $DELIVERY_ADDRESS_KE) {
                if ($res_hp->ke_mob) {
                    $orderDataFromWpValue->tel = $res_hp->ke_mob;
                } else {
                    $orderDataFromWpValue->tel = $res_hp->ke_tel;
                } 
            } elseif ($syodlv == $DELIVERY_ADDRESS_RE) {
                if ($res_hp->re_mob) {
                    $orderDataFromWpValue->tel = $res_hp->re_mob;
                } else {
                    $orderDataFromWpValue->tel = $res_hp->re_tel;
                }
            }
        }
    }
    if ($hp_status !== $MEM_STATUS_ACTIVE) {
        $orderDataFromWpValue->mbr_nm = $res_ex->mbr_nm;
        $orderDataFromWpValue->mbr_knm = $res_ex->mbr_knm;
        $orderDataFromWpValue->mbr_kata = $res_ex->mbr_kata;
        $orderDataFromWpValue->co_nm = $res_ex->co_nm;
        $syodlv_tel = $res_ex->syodlv_tel;
        $syodlv = $orderDataFromWpValue->syodlv;
        $default_syodlv = $res_ex->syodlv;

        // 発送先がデフォルトのときは、送り先の電話番号には商品お届け電話番号を表示
        if ($default_syodlv == $syodlv) {
            $orderDataFromWpValue->tel = $syodlv_tel;
        } else {
            // 発送先を変更したときは、送り先の電話番号にはその住所の携帯番号を設定。携帯番号がない場合は電話番号を設定
            if ($syodlv == $DELIVERY_ADDRESS_KE) {
                if ($res_ex->ke_mob) {
                    $orderDataFromWpValue->tel = $res_ex->ke_mob;
                } else {
                    $orderDataFromWpValue->tel = $res_ex->ke_tel;
                } 
            } elseif ($syodlv == $DELIVERY_ADDRESS_RE) {
                if ($res_ex->re_mob) {
                    $orderDataFromWpValue->tel = $res_ex->re_mob;
                } else {
                    $orderDataFromWpValue->tel = $res_ex->re_tel;
                }
            }
        }
    }

    // エクセレント会員IDで氏名を取得
    if ($orderDataFromWpValue->excellent_id !== "") {
        // 名前が取得できない場合
        if (!$orderDataFromWpValue->mbr_nm) {
            $fetchMemberInfo = tryCatch('API_GetMemberDetail_ex', [$orderDataFromWpValue->excellent_id], false);
            if (isset($fetchMemberInfo)) {
                $orderDataFromWpValue->mbr_nm = $fetchMemberInfo->mbr_nm;
                $orderDataFromWpValue->mbr_knm = $fetchMemberInfo->mbr_knm;
            }
        }
    }
}

$result = json_encode(
    array_values($orderDataFromWp)
);
// jsonを返却
if ($contents['outputCsv'] !== true) {
    header("Content-Type: application/json; charset=utf-8");
    echo $result;
    exit;
}

// CSVを返却、対象のカラム名
$csvTarget = [
    'order_created_at',
    'syuka_date',
    'syuka_st',
    'order_code',
    'happy_id',
    'pos',
    'mbr_grd',
    'excellent_id',
    'mbr_nm',
    'mbr_knm',
    'time_type',
    'order_product_product_code',
    'product_name',
    'order_product_purchase_price',
    'order_product_quantity',
    'order_product_subtotal',
    'web_member_id',
    'web_member_email',
    'pcd',
    'add1',
    'add2',
    'tel',
    'syodlv',
];

// 項目名
$csvHeader = [
    '注文日',
    '出荷日',
    '出荷状況',
    '注文コード',
    'ハッピーID',
    'ポジション',
    '取引利率',
    'エクセレントID',
    '氏名',
    'フリガナ',
    '発送希望日',
    '商品コード',
    '商品名',
    '購入時金額',
    '購入数量',
    '小計',
    'ログインID',
    'メールアドレス',
    '郵便番号',
    '住所1',
    '住所2',
    '電話番号',
    '商品発送区分',
];

$csvData = [];
foreach (json_decode($result) as $index => $orderDataFromWpValue) {
    foreach ($csvTarget as $csvTargetValue) {
        $csvData[$index][] = strval($orderDataFromWpValue->$csvTargetValue);
    }
}
putCsv($csvHeader, $csvData);
