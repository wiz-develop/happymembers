<?php
/**
 * ハッピーファミリー会員サイト API v1
 *
 * データ操作共通ロジック
 */

if (! function_exists('tryCatch')) {
    /**
     * トライキャッチでjsonもしくはarrayを返す
     *
     * @param string $funcName
     * @param array $params
     * @param bool $isReturnJson
     *
     * @return string|array|object
     */
    function tryCatch($funcName, $params, $isReturnJson = false)
    {
        $result = json_encode([]);

        try {
            // 関数実行
            $result = $funcName(...$params);
        } catch (Exception $e) {
            // print($e->getMessage()."\n");
            $result = $e;
        } finally {
            if (!$isReturnJson) {
                return json_decode($result);
            }

            return $result;
        }
    }
}

if (! function_exists('putCsv')) {
    /**
     * csv出力およびダウンロード処理
     *
     * @param array $header
     * @param array $data
     *
     * @return throw|false|int
     */
    function putCsv($header, $data)
    {
        try {
            //CSV形式で情報をファイルに出力のための準備
            $csvFileName = '/tmp/' . time() . rand() . '.csv';
            $fileName = time() . rand() . '.csv';
            $res = fopen($csvFileName, 'w');
            if ($res === false) {
                throw new Exception('ファイルの書き込みに失敗しました。');
            }

            // 項目名先に出力
            mb_convert_variables('SJIS-win', 'UTF-8', $header);
            fputcsv($res, $header);

            // ループしながら出力
            foreach ($data as $index => $dataInfo) {
                // ファイルに書き出しをする
                mb_convert_variables('SJIS-win', 'UTF-8', $dataInfo);
                fputcsv($res, $dataInfo);
            }

            // ファイルを閉じる
            fclose($res);

            // ダウンロード開始

            // ファイルタイプ（csv）
            header('Content-Type: application/octet-stream');

            // ファイル名
            header('Content-Disposition: attachment; filename=' . $fileName);
            // ファイルのサイズ　ダウンロードの進捗状況が表示
            header('Content-Length: ' . filesize($csvFileName));
            header('Content-Transfer-Encoding: binary');
            // ファイルを出力する
            readfile($csvFileName);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
