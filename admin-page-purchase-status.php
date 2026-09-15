<?php

/**
 * 管理画面内
 * 購入状況照会ページ
 */

// クエリなしアクセスを拒否
if (!$_GET['access_from_admin'] && !$_GET['wp_content_url']) {
    die;
}
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
<script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/ja.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- ローダー -->
<div id="loader" class="position-fixed w-100 h-100 d-none" style="z-index: 999;">
  <div class="d-flex justify-content-center align-items-center h-100">
    <div class="p-4 rounded bg-info text-center">
      <div class="mt-2 spinner-border text-light" role="status">
        <span class="sr-only">Loading...</span>
      </div>
      <p class="mt-3 text-light text-small mb-0">取得中...</p>
    </div>
  </div>
</div>

<!-- モーダル -->
<div id="order-detail-modal-block">
  <button type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#order-detail-modal">Launch</button>

  <div class="modal fade" id="order-detail-modal" tabindex="-1" role="dialog" aria-labelledby="order-detail-modal-title" aria-hidden="true">
    <div class="modal-dialog" style="min-width: 700px" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="order-detail-modal-title">注文詳細</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="admin-data-inquiry p-3">
  <div class="wrap">
    <p>購入状況照会</p>
  </div>

  <div id="search-box">
    <div class="card">
      <div class="card-header">
        検索照会
      </div>
      <div class="card-body">
        <div id="validate-error" class="alert alert-danger d-none" role="alert"></div>
        <form id="API_SerchWebOrder">
          <div class="row mb-2">
            <div class="col-2">発送状況</div>
            <div class="col-10">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="syuka_stat" id="syuka_stat--1" value="-1">
                <label class="form-check-label" for="syuka_stat--1">
                  全て
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="syuka_stat" id="syuka_stat-0" value="0" checked="true">
                <label class="form-check-label" for="syuka_stat-0">
                  受付
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="syuka_stat" id="syuka_stat-1" value="1">
                <label class="form-check-label" for="syuka_stat-1">
                  未出荷
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="syuka_stat" id="syuka_stat-2" value="2">
                <label class="form-check-label" for="syuka_stat-2">
                  出荷済
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="syuka_stat" id="syuka_stat-9" value="9">
                <label class="form-check-label" for="syuka_stat-9">
                  取り消し
                </label>
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-2">注文日</div>
            <div class="col-10 d-inline-flex">
              <div class="form-group">
                <input type="date" id="order_no_st" name="order_date_st" min="2021-10-01">
              </div>
              <span class="px-3">〜</span>
              <div class="form-group">
                <input type="date" id="order_no_ed" name="order_date_ed" min="2021-10-01">
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-2">注文番号</div>
            <div class="col-10 d-inline-flex">
              <div class="input-group mb-3 w-25">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="order_no_st">WB</span>
                </div>
                <input type="text" class="form-control" name="order_no_st" aria-label="00000000" aria-describedby="order_no_st">
              </div>
              <span class="px-3">〜</span>
              <div class="input-group mb-3 w-25">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="order_no_ed">WB</span>
                </div>
                <input type="text" class="form-control" name="order_no_ed" aria-label="00000000" aria-describedby="order_no_ed">
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-2">氏名</div>
            <div class="col-10">
              <input class="form-control w-25" type="text" name="mbr_nm">
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-2">フリガナ</div>
            <div class="col-10">
              <input class="form-control w-25" type="text" name="mbr_knm">
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-2">ハッピー会員ID</div>
            <div class="col-10">
              <input class="form-control w-25" type="text" name="mbr_id_hp">
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-2">エクセレント会員ID</div>
            <div class="col-10">
              <input class="form-control w-25" type="text" name="mbr_id_ex">
            </div>
          </div>
          <div class="float-right">
            <a href="javascript:void(0);" onclick="onSearch(false)" class="btn btn-primary"><i class="fas fa-search mr-1"></i>検索</a>
          </div>
          <div class="mr-2 float-right">
            <a href="javascript:void(0);" onclick="onSearch(true)" class="btn btn-primary"><i class="fas fa-download mr-1"></i>CSV出力</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="result-box">
    <table class="mt-3 table table-hover bg-light">
      <thead>
        <tr>
          <th scope="col">ハッピーID</th>
          <th scope="col">エクセレントID</th>
          <th scope="col">氏名</th>
          <th scope="col">フリガナ</th>
          <th scope="col">注文日</th>
          <th scope="col">発送日</th>
          <th scope="col">状況</th>
          <th scope="col">注文番号</th>
        </tr>
      </thead>
      <tbody id="data-tbody">
      </tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
  // 取得データ
  var fetchDataJson = []
  // 出荷ステータスconfig
  var syukaSt = [
      '受付', // syuka_st: 0
      '未出荷', // syuka_st: 1
      '出荷済', // syuka_st: 2
      '-', // syuka_st: 3
      '-', // syuka_st: 4
      '-', // syuka_st: 5
      '-', // syuka_st: 6
      '-', // syuka_st: 7
      '-', // syuka_st: 8
      '取り消し', // syuka_st: 9
    ]

  // $(function() {
  //   onSearch();
  // });

  /**
   * 半角カタカナ変換
   */
  function hanKanaConvert(str){
    // カタカナの場合、文字コードの演算ではうまくできない部分があるので、マッピングを作成
    var kanaMap = {
          "ガ": "ｶﾞ", "ギ": "ｷﾞ", "グ": "ｸﾞ", "ゲ": "ｹﾞ", "ゴ": "ｺﾞ",
          "ザ": "ｻﾞ", "ジ": "ｼﾞ", "ズ": "ｽﾞ", "ゼ": "ｾﾞ", "ゾ": "ｿﾞ",
          "ダ": "ﾀﾞ", "ヂ": "ﾁﾞ", "ヅ": "ﾂﾞ", "デ": "ﾃﾞ", "ド": "ﾄﾞ",
          "バ": "ﾊﾞ", "ビ": "ﾋﾞ", "ブ": "ﾌﾞ", "ベ": "ﾍﾞ", "ボ": "ﾎﾞ",
          "パ": "ﾊﾟ", "ピ": "ﾋﾟ", "プ": "ﾌﾟ", "ペ": "ﾍﾟ", "ポ": "ﾎﾟ",
          "ヴ": "ｳﾞ", "ヷ": "ﾜﾞ", "ヺ": "ｦﾞ",
          "ア": "ｱ", "イ": "ｲ", "ウ": "ｳ", "エ": "ｴ", "オ": "ｵ",
          "カ": "ｶ", "キ": "ｷ", "ク": "ｸ", "ケ": "ｹ", "コ": "ｺ",
          "サ": "ｻ", "シ": "ｼ", "ス": "ｽ", "セ": "ｾ", "ソ": "ｿ",
          "タ": "ﾀ", "チ": "ﾁ", "ツ": "ﾂ", "テ": "ﾃ", "ト": "ﾄ",
          "ナ": "ﾅ", "ニ": "ﾆ", "ヌ": "ﾇ", "ネ": "ﾈ", "ノ": "ﾉ",
          "ハ": "ﾊ", "ヒ": "ﾋ", "フ": "ﾌ", "ヘ": "ﾍ", "ホ": "ﾎ",
          "マ": "ﾏ", "ミ": "ﾐ", "ム": "ﾑ", "メ": "ﾒ", "モ": "ﾓ",
          "ヤ": "ﾔ", "ユ": "ﾕ", "ヨ": "ﾖ",
          "ラ": "ﾗ", "リ": "ﾘ", "ル": "ﾙ", "レ": "ﾚ", "ロ": "ﾛ",
          "ワ": "ﾜ", "ヲ": "ｦ", "ン": "ﾝ",
          "ァ": "ｧ", "ィ": "ｨ", "ゥ": "ｩ", "ェ": "ｪ", "ォ": "ｫ",
          "ッ": "ｯ", "ャ": "ｬ", "ュ": "ｭ", "ョ": "ｮ",
          "。": "｡", "、": "､", "ー": "ｰ", "「": "｢", "」": "｣", "・": "･"
    }
    var reg = new RegExp('(' + Object.keys(kanaMap).join('|') + ')', 'g');
    var replaceResult = str
            .replace(reg, function (match) {
                return kanaMap[match];
            })
            .replace(/゛/g, 'ﾞ')
            .replace(/゜/g, 'ﾟ');

    return replaceResult;
  }

  /**
   * バリデート
   */
  function validateCheck(formJsonData) {
    $('#validate-error')
      .empty()
      .addClass('d-none')

    var errorText = []

    // フリガナ
    $('input[name=mbr_knm]').val(hanKanaConvert(formJsonData.find(val => val.name === 'mbr_knm').value))
    formJsonData.find(val => val.name === 'mbr_knm').value = hanKanaConvert(formJsonData.find(val => val.name === 'mbr_knm').value)
    var checkHanKana = new RegExp(/^[ｦ-ﾟ]*$/);
    if (!checkHanKana.test(formJsonData.find(val => val.name === 'mbr_knm').value)) {
      errorText.push('フリガナはカタカナで入力してください')
    }

    // TODO: 他の項目のチェック

    if (errorText.length) {
      errorText.forEach(val => {
        $('#validate-error').append('<p class="m-0">' + val + '</p>')
      })

      $('#validate-error').removeClass('d-none')

      return false
    }

    return true
  }

  /**
   * 検索
   */
  function onSearch(isCsv) {
    $('#loader')
      .removeClass('d-none')
      .addClass('d-block')

    // バリデート
    var validateResult = validateCheck($('#API_SerchWebOrder').serializeArray())
    if (!validateResult) {
      $('#loader')
        .removeClass('d-block')
        .addClass('d-none')

      return false
    }

    fetchData(isCsv)
  }

  /**
   * データ取得
   */
  function fetchData(isCsv) {
    var formData = $('#API_SerchWebOrder').serializeArray()

    var conditions = {
      type: 'post',
      data: JSON.stringify({
          "key": "<?php echo esc_js($KEY); ?>",
          "params": [
              formData.find(v => v.name === 'mbr_id_hp').value,
              formData.find(v => v.name === 'mbr_id_ex').value,
              formData.find(v => v.name === 'mbr_nm').value,
              formData.find(v => v.name === 'mbr_knm').value,
              Number(formData.find(v => v.name === 'syuka_stat').value),
              formData.find(v => v.name === 'order_no_st').value !== '' ?
                'WB' + formData.find(v => v.name === 'order_no_st').value :
                '',
              formData.find(v => v.name === 'order_no_ed').value !== '' ?
                'WB' + formData.find(v => v.name === 'order_no_ed').value :
                '',
              formData.find(v => v.name === 'order_date_st').value.replace(/-/g, ''),
              formData.find(v => v.name === 'order_date_ed').value.replace(/-/g, '')
          ],
          "outputCsv": isCsv
      }),
      contentType: 'application/json'
    }

    // CSV取得の場合
    if (isCsv) {
      conditions.xhrFields = {
          responseType: 'blob'
      }
      conditions.success = function(blob, status, xhr) {
        // check for a filename
        var filename = "order.csv";
        var disposition = xhr.getResponseHeader('Content-Disposition');
        if (disposition && disposition.indexOf('attachment') !== -1) {
            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
            var matches = filenameRegex.exec(disposition);
            if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
        }

        if (typeof window.navigator.msSaveBlob !== 'undefined') {
            // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
            window.navigator.msSaveBlob(blob, filename);
        } else {
            var URL = window.URL || window.webkitURL;
            var downloadUrl = URL.createObjectURL(blob);

            if (filename) {
                // use HTML5 a[download] attribute to specify filename
                var a = document.createElement("a");
                // safari doesn't support this yet
                if (typeof a.download === 'undefined') {
                    window.location.href = downloadUrl;
                } else {
                    a.href = downloadUrl;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                }
            } else {
                window.location.href = downloadUrl;
            }

            setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
        }
      }
    }

    // API接続
    $.ajax(
      '<?php echo urldecode($_GET['wp_content_url']); ?>/themes/happy-members/assets/api/v1/controller/order_status.php',
      conditions
    ).done(function (data) {
      $('#loader')
        .removeClass('d-block')
        .addClass('d-none')

      if ((data instanceof Blob) === false) {
        fetchDataJson = data
        displayTable()
      }
    })
  }

  /**
   * 表を表示
   */
  function displayTable() {
    if (fetchDataJson !== []) {
      // groupByメソッド
      var groupBy = function(xs, key) {
        return xs.reduce(function(rv, x) {
          (rv[x[key]] = rv[x[key]] || []).push(x);
            return rv;
        }, {})
      }

      var dataByOrderCode = groupBy(fetchDataJson, 'order_code')

      // order_codeでグルーピングしorder_codeでソート
      fetchDataJson = Object.keys(dataByOrderCode)
        .map(function(k) {
            return {
                order_code: k,
                happy_id: dataByOrderCode[k][0].happy_id ? dataByOrderCode[k][0].happy_id : '-',
                excellent_id: dataByOrderCode[k][0].excellent_id ? dataByOrderCode[k][0].excellent_id : '-',
                mbr_nm: dataByOrderCode[k][0].mbr_nm ? dataByOrderCode[k][0].mbr_nm : '-',
                mbr_knm: dataByOrderCode[k][0].mbr_knm ? dataByOrderCode[k][0].mbr_knm : '-',
                mbr_grd: dataByOrderCode[k][0].mbr_grd ? `${dataByOrderCode[k][0].mbr_grd}%` : '-',
                mbr_kata: dataByOrderCode[k][0].mbr_kata ? dataByOrderCode[k][0].mbr_kata : '-',
                co_nm: dataByOrderCode[k][0].co_nm ? dataByOrderCode[k][0].co_nm : '-',
                order_created_at: dataByOrderCode[k][0].order_created_at ? dataByOrderCode[k][0].order_created_at : '-',
                syuka_date: dataByOrderCode[k][0].syuka_date ? `${String(dataByOrderCode[k][0].syuka_date).slice(0,4)}-${String(dataByOrderCode[k][0].syuka_date).slice(4,6)}-${String(dataByOrderCode[k][0].syuka_date).slice(6,8)}` : '-',
                syuka_st: dataByOrderCode[k][0].syuka_st ? dataByOrderCode[k][0].syuka_st : 0,
                pos: dataByOrderCode[k][0].pos ? dataByOrderCode[k][0].pos : '-',
                time_type: dataByOrderCode[k][0].time_type ? dataByOrderCode[k][0].time_type : '-',
                pcd: dataByOrderCode[k][0].pcd ? dataByOrderCode[k][0].pcd : '-',
                add1: dataByOrderCode[k][0].add1 ? dataByOrderCode[k][0].add1 : '-',
                add2: dataByOrderCode[k][0].add2 ? dataByOrderCode[k][0].add2 : '-',
                order_sum_total: dataByOrderCode[k][0].order_sum_total ? dataByOrderCode[k][0].order_sum_total : '-',
                order_products: dataByOrderCode[k]
            }
        })
        .sort(function(a,b){
          if(a.order_code < b.order_code) return 1;
          if(a.order_code > b.order_code) return -1;
          return 0;
        })

      $('#data-tbody').empty()
      fetchDataJson.forEach(val => {
        $('#data-tbody').append(`
          <tr onclick="onShowDetail('${val.order_code}')">
            <td>${val.happy_id}</td>
            <td>${val.excellent_id}</td>
            <td>${val.mbr_nm}</td>
            <td>${val.mbr_knm}</td>
            <td>${val.order_created_at}</td>
            <td>${val.syuka_date}</td>
            <td>${syukaSt[val.syuka_st]}</td>
            <td>${val.order_code}</td>
          </tr>
        `);
      })

      window.location.href = '#result-box'
    }
  }

  /**
   * 詳細モーダル表示
   */
  function onShowDetail(orderCode) {
    var detailData = fetchDataJson.find(v => v.order_code === orderCode);
    $('#order-detail-modal-block .modal-body').empty();

    var orderProductTrHTML = '';

    // フォールバック関数：同期で元の表示を行う（ajax を使わない）
    function renderSyncFallback() {
      detailData.order_products.forEach(function(val) {
        var code = val.order_product_product_code;
        var title = val.product_name ? val.product_name : '(非公開または存在しない商品)';
        orderProductTrHTML += `
          <tr>
            <td>${code}</td>
            <td>${title}</td>
            <td>${Number(val.order_product_purchase_price).toLocaleString()}円</td>
            <td>${Math.abs(val.order_product_quantity)}</td>
            <td>${Number(val.order_product_subtotal).toLocaleString()}円</td>
          </tr>
        `;
      });

      // 元のテンプレートに差し替え（あなたの元のテンプレートと同じ）
      $('#order-detail-modal-block .modal-body').append(`
        <table class="table">
          <tbody>
            <tr><td>注文日</td><td>${detailData.order_created_at}</td></tr>
            <tr><td>注文番号</td><td>${detailData.order_code}</td></tr>
            <tr><td>ハッピー会員ID</td><td>${detailData.happy_id}</td></tr>
            <tr><td>エクセレント会員ID</td><td>${detailData.excellent_id}</td></tr>
            <tr><td>氏名</td><td>${detailData.mbr_nm}</td></tr>
            <tr><td>フリガナ</td><td>${detailData.mbr_knm}</td></tr>
            <tr><td>発送日</td><td>${detailData.syuka_date}</td></tr>
            <tr><td>時間指定</td><td>${detailData.time_type}</td></tr>
            <tr><td>発送状況</td><td>${syukaSt[detailData.syuka_st]}</td></tr>
            <tr><td>ポジション</td><td>${detailData.pos}</td></tr>
            <tr><td>取引区分</td><td>${detailData.mbr_grd}</td></tr>
            <tr>
              <td>発送住所</td>
              <td>
                〒${String(detailData.pcd).substr(0,3)}-${String(detailData.pcd).substr(3)}<br>
                ${detailData.add1}<br>
                ${detailData.add2}<br>
                <span class="badge badge-info mr-2">法人名</span>${detailData.co_nm}<br>
                <span class="badge badge-info mr-2">肩書</span>${detailData.mbr_kata}<br>
              </td>
            </tr>
            <tr><td>支払方法</td><td>コレクト（ゆうパック）</td></tr>
            <tr><td>注文合計金額</td><td>${Number(detailData.order_sum_total).toLocaleString()}円</td></tr>
          </tbody>
        </table>
        <span class="badge badge-success mb-2">注文商品</span>
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">商品コード</th>
              <th scope="col">商品名</th>
              <th scope="col">価格</th>
              <th scope="col">数量</th>
              <th scope="col">金額</th>
            </tr>
          </thead>
          <tbody>
            ${orderProductTrHTML}
          </tbody>
        </table>
      `);

      $('#order-detail-modal-block button[data-target="#order-detail-modal"]').trigger('click');
    }

    // 非同期版（ajaxでタイトルを拾いに行く）を試すが、何か問題が発生したら同期フォールバックを使う
    try {
      if (!detailData || !Array.isArray(detailData.order_products)) {
        // 予期しないデータなら同期フォールバック
        renderSyncFallback();
        return;
      }

      // ajaxUrl が存在しない場合は同期フォールバック
      if (typeof ajaxUrl === 'undefined' || !ajaxUrl) {
        renderSyncFallback();
        return;
      }

      var productPromises = detailData.order_products.map(function(val) {
        return new Promise(function(resolve) {
          try {
            var code = val.order_product_product_code ? String(val.order_product_product_code) : '';
            // ★ ここが重要 ★
            // 既に order_products のレスポンスに product_name があればそれを最優先で使う（DB変更不要）
            if (val.product_name && String(val.product_name).trim() !== '') {
              resolve({
                code: code,
                title: val.product_name,
                purchase_price: val.order_product_purchase_price,
                quantity: val.order_product_quantity,
                subtotal: val.order_product_subtotal
              });
              return;
            }

            // product_name が無ければ従来どおり AJAX で取得（フォールバック）
            $.ajax({
              url: ajaxUrl,
              method: 'GET',
              data: {
                action: 'get_product_title_by_code',
                product_code: code
              },
              dataType: 'json',
              timeout: 5000
            }).done(function(res) {
              var title = (res && res.found) ? res.title : (val.product_name ? val.product_name : '(非公開または存在しない商品)');
              resolve({
                code: code,
                title: title,
                purchase_price: val.order_product_purchase_price,
                quantity: val.order_product_quantity,
                subtotal: val.order_product_subtotal
              });
            }).fail(function() {
              var fallback = val.product_name ? val.product_name : '(商品名取得エラー)';
              resolve({
                code: code,
                title: fallback,
                purchase_price: val.order_product_purchase_price,
                quantity: val.order_product_quantity,
                subtotal: val.order_product_subtotal
              });
            });
          } catch (e) {
            var fallback = val.product_name ? val.product_name : '(商品名取得エラー)';
            resolve({
              code: (val.order_product_product_code ? String(val.order_product_product_code) : ''),
              title: fallback,
              purchase_price: val.order_product_purchase_price,
              quantity: val.order_product_quantity,
              subtotal: val.order_product_subtotal
            });
          }
        });
      });

      // タイムアウト用フォールバック：Promise.all が長時間終わらない場合、5秒後に同期フォールバック
      var timeoutPromise = new Promise(function(resolve) {
        setTimeout(function() { resolve(null); }, 5000);
      });

      // Promise.race を使して、5秒以内に全部解決しなければ同期フォールバックする
      Promise.race([ Promise.all(productPromises), timeoutPromise ]).then(function(result) {
        if (!result) {
          // タイムアウト発生 → 同期フォールバック
          renderSyncFallback();
          return;
        }
        // 正常に取得できた場合 result は productRows
        var productRows = result;
        productRows.forEach(function(p) {
          orderProductTrHTML += `
            <tr>
              <td>${p.code}</td>
              <td>${p.title}</td>
              <td>${Number(p.purchase_price).toLocaleString()}円</td>
              <td>${Math.abs(p.quantity)}</td>
              <td>${Number(p.subtotal).toLocaleString()}円</td>
            </tr>
          `;
        });

        // 既存テンプレートに差し替え（同じ構造）
        $('#order-detail-modal-block .modal-body').append(`
          <table class="table">
            <tbody>
              <tr><td>注文日</td><td>${detailData.order_created_at}</td></tr>
              <tr><td>注文番号</td><td>${detailData.order_code}</td></tr>
              <tr><td>ハッピー会員ID</td><td>${detailData.happy_id}</td></tr>
              <tr><td>エクセレント会員ID</td><td>${detailData.excellent_id}</td></tr>
              <tr><td>氏名</td><td>${detailData.mbr_nm}</td></tr>
              <tr><td>フリガナ</td><td>${detailData.mbr_knm}</td></tr>
              <tr><td>発送日</td><td>${detailData.syuka_date}</td></tr>
              <tr><td>時間指定</td><td>${detailData.time_type}</td></tr>
              <tr><td>発送状況</td><td>${syukaSt[detailData.syuka_st]}</td></tr>
              <tr><td>ポジション</td><td>${detailData.pos}</td></tr>
              <tr><td>取引区分</td><td>${detailData.mbr_grd}</td></tr>
              <tr>
                <td>発送住所</td>
                <td>
                  〒${String(detailData.pcd).substr(0,3)}-${String(detailData.pcd).substr(3)}<br>
                  ${detailData.add1}<br>
                  ${detailData.add2}<br>
                  <span class="badge badge-info mr-2">法人名</span>${detailData.co_nm}<br>
                  <span class="badge badge-info mr-2">肩書</span>${detailData.mbr_kata}<br>
                </td>
              </tr>
              <tr><td>支払方法</td><td>コレクト（ゆうパック）</td></tr>
              <tr><td>注文合計金額</td><td>${Number(detailData.order_sum_total).toLocaleString()}円</td></tr>
            </tbody>
          </table>
          <span class="badge badge-success mb-2">注文商品</span>
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">商品コード</th>
                <th scope="col">商品名</th>
                <th scope="col">価格</th>
                <th scope="col">数量</th>
                <th scope="col">金額</th>
              </tr>
            </thead>
            <tbody>
              ${orderProductTrHTML}
            </tbody>
          </table>
        `);

        $('#order-detail-modal-block button[data-target="#order-detail-modal"]').trigger('click');
      }).catch(function(e) {
        // 何か例外が起きたら同期フォールバック
        console.error('onShowDetail async error', e);
        renderSyncFallback();
      });

    } catch (e) {
      console.error('onShowDetail error', e);
      renderSyncFallback();
    }
  }

  //
</script>

<?php
// TODO: SCSS
?>
<style>
  html{
    scroll-behavior: smooth;
  }
  body {
    background-color: transparent;
  }
  #data-tbody tr {
    cursor: pointer;
  }
</style>