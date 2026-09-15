<?php

/**
 * 管理画面内
 * WEB会員登録状況ページ
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

<!-- モーダル会員情報詳細/ パスワード再設定 -->
<div id="detail-modal-block">
  <button type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#detail-modal">Launch</button>

  <div class="modal fade" id="detail-modal" tabindex="-1" role="dialog" aria-labelledby="detail-modal-title" aria-hidden="true">
    <div class="modal-dialog" style="min-width: 700px" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detail-modal-title"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>


        <div id="confirm-save" style="z-index: 1; background-color: rgba(255,255,255,0.6); display:none;" class="w-100 h-100 position-absolute rounded">
          <div id="confirm-select">
            <div class="w-100 h-100 d-flex justify-content-center align-items-center flex-column">
              <p id="confirm-title"></p>
              <div id="btn-row" class="row"></div>
            </div>
          </div>

          <div id="db-accessing" style="display: none;">
            <div class="w-100 h-100 d-flex justify-content-center align-items-center">
              <div class="p-4 rounded bg-info text-center">
                <div class="mt-2 spinner-border text-light" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-3 text-light text-small mb-0">処理中...</p>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button id="modal-close-btn" type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="admin-data-inquiry p-3">
  <div class="wrap">
    <p>WEB会員登録状況</p>
  </div>

  <div id="search-box">
    <div class="card">
      <div class="card-header">
        検索照会
      </div>
      <div class="card-body">
        <div id="validate-error" class="alert alert-danger d-none" role="alert"></div>
        <form id="API_SerchWebMember">
          <div class="row mb-2">
            <div class="col-2">ログインID</div>
            <div class="col-10">
              <input class="form-control w-25" type="text" name="mbr_id_login">
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
          <div class="row mb-2">
            <div class="col-2">フリガナ</div>
            <div class="col-10">
              <input class="form-control w-25" type="text" name="mbr_knm">
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-2">氏名</div>
            <div class="col-10">
              <input class="form-control w-25" type="text" name="mbr_nm">
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-2">出力方法</div>
            <div class="col-10">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="orderby" id="orderby-created_at" value="0" checked="true">
                <label class="form-check-label" for="orderby-created_at">
                  登録日順
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="orderby" id="orderby-mbr_knm" value="1">
                <label class="form-check-label" for="orderby-mbr_knm">
                  フリガナ順
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="orderby" id="orderby-mbr_id_hp" value="2">
                <label class="form-check-label" for="orderby-mbr_id_hp">
                  ハッピーID順
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="orderby" id="orderby-mbr_id_ex" value="3">
                <label class="form-check-label" for="orderby-mbr_id_ex">
                  エクセレントID順
                </label>
              </div>
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
    <table class="mt-3 table table-hover table-user-list bg-light">
      <thead>
        <tr>
          <th scope="col">ハッピーID</th>
          <th scope="col">エクセレントID</th>
          <th scope="col">氏名</th>
          <th scope="col">フリガナ</th>
          <th scope="col">ログインID</th>
          <th scope="col">状況</th></th>
          <th scope="col">登録日</th>
          <th scope="col"></th>
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

  var isValidStatusConf = [
        '仮登録', // is_valid: 0
        '本登録', // is_valid: 1
    ];

  $(function() {
    onSearch();
  });

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
    var validateResult = validateCheck($('#API_SerchWebMember').serializeArray())
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
    var formData = $('#API_SerchWebMember').serializeArray()

    var conditions = {
        type: 'post',
        data: JSON.stringify({
            "key": "<?php echo esc_js($KEY); ?>",
            "params": formData.reduce((result, current) => {
                result[current.name] = current.value
                return result;
            }, {}),
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
        var filename = "member.csv";
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
      '<?php echo urldecode($_GET['wp_content_url']); ?>/themes/happy-members/assets/api/v1/controller/member_status.php',
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

      $('#data-tbody').empty()
      fetchDataJson.forEach(val => {
        $('#data-tbody').append(`
          <tr>
            <td onclick="onShowDetail('${val.wp_user_id}')">${val.happy_id}</td>
            <td onclick="onShowDetail('${val.wp_user_id}')">${val.excellent_id}</td>
            <td onclick="onShowDetail('${val.wp_user_id}')">${val.mbr_nm}</td>
            <td onclick="onShowDetail('${val.wp_user_id}')">${val.mbr_knm}</td>
            <td onclick="onShowDetail('${val.wp_user_id}')">${val.login_id}</td>
            <td onclick="onShowDetail('${val.wp_user_id}')">${isValidStatusConf[val.is_valid]}</td>
            <td onclick="onShowDetail('${val.wp_user_id}')">${val.created_at}</td>
            <td onclick="onShowDetail('${val.wp_user_id}')">
              <button style="font-size: 0.8rem; type="button" class="btn btn-secondary">変更</button>
            </td>
            <td onclick="onResetPass('${val.wp_user_id}')">
              <button style="font-size: 0.8rem; type="button" class="btn btn-secondary">パスワード再設定</button>
            </td>
          </tr>
        `);
      })

      window.location.href = '#result-box'
    }
  }

  /**
   * 会員情報詳細モーダル表示
   */
  function onShowDetail(wpUserId) {
    var detailData = fetchDataJson.find(v => v.wp_user_id === wpUserId)
    $('h5.modal-title').text('会員情報')
    $('#detail-modal-block .modal-body').empty()
    hideConfirm()

    $('#detail-modal-block .modal-body').append(`
      <div id="update-validate-error" class="alert alert-danger d-none" role="alert"></div>
      <form id="UpdateWebMember">
        <input class="form-control" type="hidden" name="wp_user_id" value="${detailData.wp_user_id}">
        <input class="form-control" type="hidden" name="mbr_nm" value="${detailData.mbr_nm}">
        <input class="form-control" type="hidden" name="mbr_knm" value="${detailData.mbr_knm}">
        <input class="form-control" type="hidden" name="mbr_bth" value="${detailData.mbr_bth}">
        <input class="form-control" type="hidden" name="old_web_member_email" value="${detailData.web_member_email}">
        <table class="table">
          <tbody>
            <tr>
              <td>ハッピー会員ID</td>
              <td><input class="form-control" type="text" name="happy_id" value="${detailData.happy_id}"></td>
            </tr>
            <tr>
              <td>エクセレント会員ID</td>
              <td><input class="form-control" type="text" name="excellent_id" value="${detailData.excellent_id}"></td>
            </tr>
            <tr>
              <td>フリガナ</td>
              <td>${detailData.mbr_knm}</td>
            </tr>
            <tr>
              <td>氏名</td>
              <td>${detailData.mbr_nm}</td>
            </tr>
            <tr>
              <td>生年月日</td>
              <td>${String(detailData.mbr_bth).slice(0,4)}-${String(detailData.mbr_bth).slice(4,6)}-${String(detailData.mbr_bth).slice(6,8)}</td>
            </tr>
            <tr>
              <td>メールアドレス</td>
              <td><input class="form-control" type="text" name="web_member_email" value="${detailData.web_member_email}"></td>
            </tr>
            <tr>
              <td>ログインID</td>
              <td>${detailData.login_id}</td>
            </tr>
            <tr>
              <td>状況</td>
              <td>
                <div>
                  <input id="is_valid_true"type="radio" name="is_valid" value="0" ${detailData.is_valid == 0 ? 'checked' : ''}>
                  <label for="is_valid_true">${isValidStatusConf[0]}</label>

                </div>
                <div>
                  <input id="is_valid_false"type="radio" name="is_valid" value="1" ${detailData.is_valid == 1 ? 'checked' : ''}>
                  <label for="is_valid_false">${isValidStatusConf[1]}</label>

                </div>
              </td>
            </tr>
            <tr>
              <td>登録日</td>
              <td>${detailData.created_at}</td>
            </tr>
            <tr>
              <td>更新日</td>
              <td>${detailData.updated_at}</td>
            </tr>
          </tbody>
        </table>
        <div class="float-right">
          <a href="javascript:void(0);" class="btn btn-danger" onclick="onConfirm('delete')">削除</a>
        </div>
        <div class="mr-2 float-right">
          <a href="javascript:void(0);" class="btn btn-success" onclick="onConfirm('update')">更新</a>
        </div>
      </form>
    `);

    $('#detail-modal-block button[data-target="#detail-modal"]').trigger('click')
  }

  /**
   * パスワード再発行モーダル表示
   */
  function onResetPass(wpUserId) {
    var detailData = fetchDataJson.find(v => v.wp_user_id === wpUserId)
    $('h5.modal-title').text('パスワード再設定')
    $('#detail-modal-block .modal-body').empty()
    hideConfirm()

    $('#detail-modal-block .modal-body').append(`
      <div id="detail-validate-error" class="alert alert-danger d-none" role="alert"></div>
      <form id="ResetPassword">
      <input class="form-control" type="hidden" name="wp_user_id" value="${detailData.wp_user_id}":>
        <table class="table">
          <tbody>
            <tr>
              <td>パスワード</td>
              <td><input class="form-control" type="text" name="web_pass" value=""></td>
            </tr>
            <tr>
              <td>パスワード（確認用）</td>
              <td><input class="form-control" type="text" name="web_confirmation_pass" value=""></td>
            </tr>
          </tbody>
        </table>
        <div class="mr-2 float-right">
          <a href="javascript:void(0);" class="btn btn-success" onclick="onConfirm('resetPassword')">再設定</a>
        </div>
      </form>
    `);

    // bootstrapの機能でモーダルを開く場所（らしい）
    $('#detail-modal-block button[data-target="#detail-modal"]').trigger('click')
  }

  /**
   * 確認画面表示 (モーダル内)
   */
  function onConfirm(type) {
    $('#db-accessing').hide()
    $('#btn-row').empty()

    if (!type) {
      return
    }

    if (type === 'delete') {
      $('#confirm-title').text('本当に削除しますか?')
      $('#btn-row').append(`
        <button type="button" class="btn btn-success" onclick="onDelete()">はい</button>
        <button type="button" class="ml-4 btn btn-secondary" onclick="hideConfirm()">いいえ</button>
      `)
    }
    if (type === 'update') {
      $('#confirm-title').text('内容を更新しますか?')
      $('#btn-row').append(`
        <button type="button" class="btn btn-success" onclick="onSave()">はい</button>
        <button type="button" class="ml-4 btn btn-secondary" onclick="hideConfirm()">いいえ</button>
      `)
    }
    if (type === 'resetPassword') {
      $('#confirm-title').text('本当にパスワードの再設定を行いますか?')
      $('#btn-row').append(`
        <button type="button" class="btn btn-success" onclick="onReset()">はい</button>
        <button type="button" class="ml-4 btn btn-secondary" onclick="hideConfirm()">いいえ</button>
      `)
    }

    $('#confirm-save').show()
    $('#confirm-select').show()
  }

  function hideConfirm() {
    $('#confirm-save').hide()
    $('#db-accessing').hide()
    $('#update-validate-error')
      .empty()
      .addClass('d-none')
  }

  /**
   * 更新
   */
  function onSave() {
    $('#confirm-select').hide()
    $('#update-validate-error')
      .empty()
      .addClass('d-none')
    $('#db-accessing').show()

    var formData = $('#UpdateWebMember').serializeArray()

    console.log(formData);

    var conditions = {
        type: 'post',
        data: JSON.stringify({
            "key": "<?php echo esc_js($KEY); ?>",
            "params": formData.reduce((result, current) => {
                result[current.name] = current.value
                return result;
            }, {}),
        }),
        contentType: 'application/json'
    }

    // API接続
    $.ajax(
      '<?php echo urldecode($_GET['wp_content_url']); ?>/themes/happy-members/assets/api/v1/controller/member_update.php',
      conditions
    ).done(function (data) {
      data = JSON.parse(data)
      $('#confirm-save').hide()

      if (data.error) {
        Object.keys(data.error).forEach(function (key) {
          $('#update-validate-error').append('<p class="m-0">' + data.error[key] + '</p>')
        });

        $('#update-validate-error').removeClass('d-none')

        return false
      }
      $('#modal-close-btn').trigger('click')
      onSearch()
    })
  }

  /**
   * 削除
   */
  function onDelete() {
    $('#confirm-select').hide()
    $('#update-validate-error')
      .empty()
      .addClass('d-none')
    $('#db-accessing').show()

    var formData = $('#UpdateWebMember').serializeArray()

    var conditions = {
        type: 'post',
        data: JSON.stringify({
            "key": "<?php echo esc_js($KEY); ?>",
            "params": formData.reduce((result, current) => {
                result[current.name] = current.value
                return result;
            }, {}),
        }),
        contentType: 'application/json'
    }

    // API接続
    $.ajax(
      '<?php echo urldecode($_GET['wp_content_url']); ?>/themes/happy-members/assets/api/v1/controller/member_delete.php',
      conditions
    ).done(function (data) {
      data = JSON.parse(data)
      $('#confirm-save').hide()
      $('#modal-close-btn').trigger('click')
      onSearch()
    })
  }

  function onReset() {
    $('#confirm-select').hide()
    $('#detail-validate-error')
      .empty()
      .addClass('d-none')
    $('#db-accessing').show()

    var formData = $('#ResetPassword').serializeArray()

    console.log(formData);

    var conditions = {
        type: 'post',
        data: JSON.stringify({
            "key": "<?php echo esc_js($KEY); ?>",
            "params": formData.reduce((result, current) => {
                result[current.name] = current.value
                return result;
            }, {}),
        }),
        contentType: 'application/json'
    }

    // API接続
    $.ajax(
      '<?php echo urldecode($_GET['wp_content_url']); ?>/themes/happy-members/assets/api/v1/controller/password_reset.php',
      conditions
    ).done(function (data) {
      data = JSON.parse(data)
      console.log(data);
      $('#confirm-save').hide()

      if (data.error) {
        Object.keys(data.error).forEach(function (key) {
          $('#detail-validate-error').append('<p class="m-0">' + data.error[key] + '</p>')
        });

        $('#detail-validate-error').removeClass('d-none')

        return false
      }
      $('#modal-close-btn').trigger('click')
      onSearch()
    })
  }

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
  .table-user-list tr td {
    padding: 0.5rem;
    font-size: 0.8rem;
    vertical-align: middle;
  }
</style>