<?php
    require("config.php");
    // require_once($_SERVER['DOCUMENT_ROOT']. '/happymembers/cms/wp-load.php');
    require_once($CMS_PATH."/wp-load.php");
    // require_once('/usr/home/ai141209md/html/cms/wp-load.php');
    header('Content-Type: application/json; charset=UTF-8');

    // 取得したAPIキー
    $api_key = $GOOGLE_CALENDAR_API_KEY;
    // カレンダーID
    $calendar_id = urlencode('japanese__ja@holiday.calendar.google.com');  // Googleの提供する日本の祝日カレンダー
    // 取得する期間
    $start = date("Y-m-1\T00:00:00\Z");
    $end = date('Y-m-1\T00:00:00\Z', strtotime('+3 month -1 day')); // 3ヶ月後の末日

    $url = "https://www.googleapis.com/calendar/v3/calendars/" . $calendar_id . "/events?";
    $query = [
        'key' => $api_key,
        'timeMin' => $start,
        'timeMax' => $end,
        'maxResults' => 50,
        'orderBy' => 'startTime',
        'singleEvents' => 'true'
    ];

    $results = [];
    if ($data = file_get_contents($url. http_build_query($query), true)) {
        $data = json_decode($data);
        // $data->itemには日本の祝日カレンダーの"予定"が入ってきます
        foreach ($data->items as $row) {
            // [予定の日付 => 予定のタイトル]
            $results[$row->start->date] = $row->summary;
        }
    }
    echo json_encode($results, true);
