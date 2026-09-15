<?php
/**
 * ハッピーファミリー会員サイト API v1
 *
 * ボーナス詳細取得
 */

/*-------------------------------------------*/
/*  ボーナス明細　情報取得 ハッピー会員 　＊＊＊使用箇所ボーナス詳細
/*-------------------------------------------*/
function get_bonus_hp($happy_id)
{
    // 15ヶ月分取得する
    $start_month = (int)date("Ym", strtotime("-15 month")); // TODO: -15にする
    $end_month = (int)date("Ym");

    $data_bonus = tryCatch('API_GetBonusInf_hp', [(string)$happy_id, $start_month, $end_month], true);
    $data_bonus_ar = json_decode($data_bonus, true);

    // echo "API => API_GetBonusInf_hp<br>パラメーター=> <br>ハッピーID：".$happy_id."<br>開始対象年月：".$start_month."<br>終了対象年月：".$end_month."<br>";
    // echo "戻り値<br>";
    // var_dump($data_bonus);

    // APIでエラーが帰ってきたらエラーをフロントに返す。 // TODO
    if ($data_bonus_ar['code'] === 1) {
        return $data_bonus_ar;
    }

    // 年月日順で配列をソートする
    $yyyymm = array_column($data_bonus_ar['result'], 'yyyymm');
    array_multisort($yyyymm, SORT_DESC, $data_bonus_ar['result']);

    // 西暦年毎にネストしている配列を作成
    $the_year = -1;
    $bonus_info['bonuses'] = [];
    foreach ($data_bonus_ar['result'] as $key => $data) {
        $year = substr((string)$data['yyyymm'], 0, 4);
        $month = ltrim(substr((string)$data['yyyymm'], 4, 6), 0);
        if ($the_year !== $year) {
            if ($key !== 0) {
                array_push($bonus_info['bonuses'], $bonus);
                $bonus = [];
            }
            $bonus['year'] = $year;
        }
        $detail['month'] = $month;
        $detail['bonus'] = $data['bonus'];

        $bonus['detail'][$key] = $detail;
        $the_year = $year;
    }
    array_push($bonus_info['bonuses'], $bonus);
    $bonus_info['code'] = 0;

    return $bonus_info;
}

/*-------------------------------------------*/
/*  ボーナス明細　情報取得 エクセレント会員 　＊＊＊使用箇所ボーナス詳細
/*-------------------------------------------*/
function get_bonus_ex($excellent_id)
{
    // 15ヶ月分取得する
    $end_month = (int)date("Ym");
    $start_month = (int)date("Ym", strtotime("-15 month")); // TODO: -15にする

    $data_bonus = tryCatch('API_GetBonusInf_ex', [(string)$excellent_id, $start_month, $end_month], true);
    $data_bonus_ar = json_decode($data_bonus, true);

    // var_dump($data_bonus);

    // echo "API => API_GetBonusInf_ex<br>パラメーター=> <br>エクセレントID：".$excellent_id."<br>開始対象年月：".$start_month."<br>終了対象年月：".$end_month."<br>";
    // echo "戻り値<br>";
    // var_dump($data_bonus);

    // APIでエラーが帰ってきたらエラーをフロントに返す。
    if ($data_bonus_ar['code'] === 1) {
        return $data_bonus_ar;
    }


    // 年月日順で配列をソートする
    $yyyymm = array_column($data_bonus_ar['result'], 'yyyymm');
    array_multisort($yyyymm, SORT_DESC, $data_bonus_ar['result']);

    // var_dump($data_bonus_ar['result']);

    // // 西暦年毎にネストしている配列を作成
    $bonuses = [];
    foreach ($data_bonus_ar['result'] as $key => $data) {
        $year = substr((string)$data['yyyymm'], 0, 4);
        $month = ltrim(substr((string)$data['yyyymm'], 4, 6), 0);
        $br_id = $data['br_id'];
        if ($data['br_id'] === '000') {
            $bonuses['bonuses']['001'][$year][$month]['bonus_total'] = $data['bonus'];
        } else {
            $bonuses['bonuses'][$br_id][$year][$month]['month'] = $month;
            $bonuses['bonuses'][$br_id][$year][$month]['bonus'] = $data['bonus'];
        }
    }
    $bonuses['code'] = 0;

    return $bonuses;
}
