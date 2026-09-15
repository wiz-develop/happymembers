<?php
/************************************************* 目次 **************************************************/
// ログイン処理
// 新規WEB会員登録
// WEB会員情報変更
// パスワード再発行
// 商品一覧 / 金額取得
// 商品詳細（未完了）
// カート関連
// 注文処理
// 注文履歴
// 金額パネル表示
// 組織図
// バリデーション
// メンバー情報取得 && 判定
// オートシップ情報
// メール本文作成
// その他 - 基本設定

/************************************************ WPの設定? *************************************************/
/**********************************************
*  子テーマの記述
***********************************************/
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if (!function_exists('chld_thm_cfg_locale_css')):
    function chld_thm_cfg_locale_css($uri)
    {
        if (empty($uri) && is_rtl() && file_exists(get_template_directory() . '/rtl.css')) {
            $uri = get_template_directory_uri() . '/rtl.css';
        }
        return $uri;
    }
endif;
add_filter('locale_stylesheet_uri', 'chld_thm_cfg_locale_css');

if (!function_exists('chld_thm_cfg_parent_css')):
    function chld_thm_cfg_parent_css()
    {
        wp_enqueue_style('chld_thm_cfg_parent', trailingslashit(get_template_directory_uri()) . 'style.css', array(  ));
    }
endif;
add_action('wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10);

// END ENQUEUE PARENT ACTION

/**********************************************
*  ここから追加設定
***********************************************/
// API呼び出し
require("assets/api/v1/common/autoload.php");
// config
require("assets/api/v1/common/config.php");
// 管理画面メニュー追加
require("admin-menu-functions.php");

/*-------------------------------------------*/
/*  グローバル変数
/*-------------------------------------------*/
global $MEM_TYPE_HAPPY;
global $MEM_STATUS_ACTIVE;    // 稼働中
global $MEM_STATUS_DORMANT;   // 休眠中
global $MEM_STATUS_WITHDRAWAL;// 退会中

global $VALIDATION_TYPE_SIGNUP;
global $VALIDATION_TYPE_EDIT;
global $VALIDATION_TYPE_REISSUE_PASS;

global $MEM_SYUBETSU_EX;         // エクセレント
global $MEM_SYUBETSU_MEMBER;     // メンバー会員
global $MEM_SYUBETSU_DOOR_SALES; // 訪販会員
global $MEM_SYUBETSU_EMPLOYEE;   // 一般会員・社員

/*-------------------------------------------*/
/*  セキュリティー対策
/*-------------------------------------------*/
remove_action('wp_head', 'wp_generator');                   // WordPressのバージョン
remove_action('wp_head', 'wp_shortlink_wp_head');           // 短縮URLのlink
remove_action('wp_head', 'wlwmanifest_link');               // ブログエディターのマニフェストファイル
remove_action('wp_head', 'rsd_link');                       // 外部から編集するためのAPI
remove_action('wp_head', 'feed_links_extra', 3);            // フィードへのリンク
remove_action('wp_head', 'print_emoji_detection_script', 7);// 絵文字に関するJavaScript
remove_action('wp_head', 'rel_canonical');                  // カノニカル
remove_action('wp_print_styles', 'print_emoji_styles');     // 絵文字に関するCSS
remove_action('admin_print_scripts', 'print_emoji_detection_script');// 絵文字に関するJavaScript
remove_action('admin_print_styles', 'print_emoji_styles');  // 絵文字に関するCSS

/*-------------------------------------------*/
/*  bootstrap 読み込み
/*-------------------------------------------*/
function themebs_enqueue_styles()
{
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/bootstrap-5.0.2-dist/css/bootstrap.min.css');
    wp_enqueue_style('core', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'themebs_enqueue_styles');

function themebs_enqueue_scripts()
{
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js', array( 'jquery' ));
}
add_action('wp_enqueue_scripts', 'themebs_enqueue_scripts');

/*-------------------------------------------*/
/*  <head>タグ内に自分の追加したいタグを追加する
/*-------------------------------------------*/
function add_wp_head_custom() { ?>
<!-- head内に書きたいコード -->
    <script>
            // JS用 固定URL
            // WordPressでAjaxを使用する場合、urlにはadmin-ajax.phpの絶対パスを指定
            const ajaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";
            const thermeUrl = "<?php echo get_stylesheet_directory_uri();?>";
            const happyMembersUrl = "<?php echo get_stylesheet_directory_uri();?>";
            const homeUrl = "<?php echo get_home_url();?>";
        </script>
    <!-- <script src="<?php // echo get_stylesheet_directory_uri();?>/assets/js/cart.js"></script> -->
    <script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/order-total.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/purchase_common.js"></script>
<?php }
add_action('wp_head', 'add_wp_head_custom', 1);

function add_wp_footer_custom() { ?>
<!-- footerに書きたいコード -->
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/slick.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery.email-autocomplete.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/common.js"></script>
<?php }
add_action('wp_footer', 'add_wp_footer_custom', 1);

/*-------------------------------------------*/
/*  管理画面内にテンプレート名表示
/*-------------------------------------------*/
function add_pages_columns($columns)
{
    $columns['template'] = 'テンプレート';
    return $columns;
}
function custom_pages_column($column_name, $post_id)
{
    if ($column_name == 'template') {
        $template = get_page_template_slug($post_id);
        echo ($template) ? $template : 'Default';
    }
}
add_filter('manage_pages_columns', 'add_pages_columns');
add_action('manage_pages_custom_column', 'custom_pages_column', 10, 2);


/*-------------------------------------------*/
/*  Wordpressのデフォルト機能をストップするためのフック
/*-------------------------------------------*/
//メールアドレス変更時のメール送信STOP
add_filter('send_email_change_email', '__return_false');
//パスワード変更時のメール送信STOP
add_filter('send_password_change_email', '__return_false');

/*-------------------------------------------*/
/*  テンプレート命名規則
/*-------------------------------------------*/
add_filter('page_template_hierarchy', 'my_page_templates');
function my_page_templates($templates)
{
    global $wp_query;

    $template = get_page_template_slug();
    $pagename = $wp_query->query['pagename'];

    if ($pagename && ! $template) {
        $pagename = str_replace('/', '__', $pagename);
        $decoded = urldecode($pagename);

        if ($decoded == $pagename) {
            array_unshift($templates, "page-{$pagename}.php");
        }
    }

    return $templates;
}

/*-------------------------------------------*/
/*  wp_cron
/*-------------------------------------------*/
// $set_time = data('Y-m-d 00:00:00')
// //時間の設定
// if (!wp_next_scheduled('my_task_hook')) {
//     date_default_timezone_set('Asia/Tokyo');  // タイムゾーンの設定
//     wp_schedule_event( strtotime('00:00:00'), 'daily', 'my_task_hook');
// }

// // 指定の時間に行いたい処理
// add_action( 'my_task_hook', 'my_task_function');

// function my_task_function(){
//     $mail  = 'a';
// }

/*-------------------------------------------*/
/*  権限設定
/*-------------------------------------------*/
// 寄稿者・購読者にADMINバーを表示しない
add_filter('show_admin_bar', 'hide_admin_bar');
function hide_admin_bar($content)
{
    return (current_user_can('publish_posts') ? $content : false);
}

// 寄稿者・購読者を会員画面に入れない
add_action('auth_redirect', 'contributor_redirect');
function contributor_redirect($user_id)
{
    $user = get_userdata($user_id);
    if (! $user->has_cap('publish_posts')) {
        wp_redirect(home_url());
        exit();
    }
}


function my_require_login()
{
    // ログインしていない　且つ　以下のページでなければ'/login'へリダイレクト
    if (!is_user_logged_in() && !is_page('会員ログイン') && !is_page('WEB会員登録') && !is_page('入力内容確認') && !is_page('仮登録完了') && !is_page('WEB会員登録完了') &&  !is_page('WEB会員登録手順') && !is_page('パスワード再発行のご依頼') && !is_page('仮パスワード送信完了')) {
        wp_redirect(get_home_url()."/login"); // ログインページのURL
        exit();
    }

    // ログインしている　且つ　以下のページの場合 / へリダイレクト
    if (is_user_logged_in() && (is_page('会員ログイン') || is_page('WEB会員登録') || is_page('入力内容確認') || is_page('仮登録完了') || is_page('WEB会員登録完了') || is_page('パスワード再発行のご依頼') || is_page('仮パスワード送信完了'))) {
        wp_redirect(get_home_url()."/");
        exit();
    }
}

/********************************************** ログイン処理 ***********************************************/
/*-------------------------------------------*/
/*  ログイン認証
/*-------------------------------------------*/
function my_user_login()
{
    session_check();
    $_SESSION['temp']['user_login'] = isset($_POST['user_login']) ? sanitize_text_field($_POST['user_login']) : '';
    $_SESSION['temp']['user_pass'] =  isset($_POST['user_pass']) ? sanitize_text_field($_POST['user_pass']) : '';

    $user_login = isset($_POST['user_login']) ? sanitize_text_field($_POST['user_login']) : '';
    $user_pass = isset($_POST['user_pass']) ? sanitize_text_field($_POST['user_pass']) : '';
    $user_info = get_user_by('login', $user_login);
    // TODO:チェック必要
    if ($user_info) {
        $is_valid = $user_info->is_valid;
    }

    $result_login = [
        'user_login' => $user_login,
        'user_pass' => $user_pass,
        'error' => [],
    ];

    // 1）ログインID存在チェック
    $username_exist = username_exists($user_login);
    if (!$username_exist) {
        $result_login['error']['user_login'] = 'ご入力いただいたログインIDは存在しません。';
        return $result_login;
    }

    // 2) ログインIDの有効無効チェック
    if (!$is_valid) {
        $result_login['error']['user_pass'] = '有効なIDではありません。WEB会員ご登録時に送付されたURLより本登録を行ってください。';
        return $result_login;
    }

    // 3）会員ID存在チェック APIチェック
    $response = [];
    // ハッピー会員ステイタスチェック
    $happy_id = $user_info->happy_id;
    $res_hp = tryCatch('API_GetMemberStat', [0, $happy_id], false);
    $response['hp_stat'] = false;
    if ($res_hp->code === 0) {
        if ($res_hp->mbr_stat === 0 && $res_hp->mbr_syu === 1) {
            $response['hp_stat'] = true;
        }
    }
    // エクセレント会員ステイタスチェック
    $excellent_id = $user_info->excellent_id;
    $response['ex_stat'] = false;
    $res_ex = tryCatch('API_GetMemberStat', [1, $excellent_id], false);
    if ($res_ex->code === 0) {
        if ($res_ex->mbr_stat === 0 || $res_ex->mbr_stat === 1) {
            $response['ex_stat'] = true;
        }
    }

    // ハッピー会員ID、エクセレント会員IDの結果がどちらもfalseであれば
    if (!in_array(true, $response)) {
        $result_login['error']['user_pass'] = '有効な会員ではありませんでした。';
        return $result_login;
    }

    // 4) ログイン処理
    $creds = array(
        'user_login' => $user_login,
        'user_password' => $user_pass,
    );
    $user = wp_signon($creds);

    // ログイン処理通過できなければエラーを返す。
    if (is_wp_error($user)) {
        $result_login['error']['user_pass'] = 'パスワードが間違っています。お忘れの方は下記リンクからパスワードの再発行をしてください。';
        return $result_login;
    }

    // ５）ログイン成功時の処理
    get_member_info($user);
    session_check();

    // ６）ログイン時に権限のない商品がカートに存在していれば削除する。
    // カート一覧を取得
    // 商品一つひとつタイプを出して
    // ログイン権限に反している商品をカートから削除する
    // WEB会員変更時にも同じ処理を

    wp_redirect(get_home_url());
    return $result_login;
}

/*-------------------------------------------*/
/*  ログアウト
/*-------------------------------------------*/
function do_logout()
{
    wp_logout();
    // wp_redirect(get_home_url()."/login");
    // return ;
}
add_action('wp_ajax_do_logout', 'do_logout');
add_action('wp_ajax_nopriv_do_logout', 'do_logout');
add_action('wp_logout', 'session_clear_logout');

function session_clear_logout()
{
    if (!isset($_SESSION)) {
        session_start();
    }
    $_SESSION = array();
    session_destroy();
}

/*-------------------------------------------*/
/*  自動ログアウト
/*-------------------------------------------*/
// プラグインで設定

/********************************************** 新規WEB会員登録 ***********************************************/
/*-------------------------------------------*/
/*  新規WEB会員登録 => 確認画面
/*-------------------------------------------*/
function confirm_signup()
{
    global $VALIDATION_TYPE_SIGNUP;
    session_check();
    // $_SESSION['sign_up'][] = []; // 一度セッションを空にする
    $_SESSION['sign_up']['login_id'] = isset($_POST['login_id']) ? sanitize_text_field($_POST['login_id']) : '';
    $_SESSION['sign_up']['pass'] = isset($_POST['pass']) ? sanitize_text_field($_POST['pass']) : '';
    $_SESSION['sign_up']['confirmation_pass'] = isset($_POST['confirmation_pass']) ? sanitize_text_field($_POST['confirmation_pass']) : '';
    $_SESSION['sign_up']['happy_id'] = isset($_POST['happy_id']) ? sanitize_text_field($_POST['happy_id']) : '';
    $_SESSION['sign_up']['excellent_id'] = isset($_POST['excellent_id']) ? sanitize_text_field($_POST['excellent_id']) : '';
    $_SESSION['sign_up']['mbr_nm'] = isset($_POST['mbr_nm']) ? sanitize_text_field($_POST['mbr_nm']) : '';
    $_SESSION['sign_up']['mbr_knm'] = isset($_POST['mbr_knm']) ? sanitize_text_field($_POST['mbr_knm']) : '';
    $_SESSION['sign_up']['year_birth'] = isset($_POST['year_birth']) ? sanitize_text_field($_POST['year_birth']) : '';
    $_SESSION['sign_up']['month_birth'] = isset($_POST['month_birth']) ? sanitize_text_field($_POST['month_birth']) : '';
    $_SESSION['sign_up']['day_birth'] = isset($_POST['day_birth']) ? sanitize_text_field($_POST['day_birth']) : '';
    $_SESSION['sign_up']['mail'] = isset($_POST['mail']) ? sanitize_text_field($_POST['mail']) : '';
    $_SESSION['sign_up']['confirmation_mail'] = isset($_POST['confirmation_mail']) ? sanitize_text_field($_POST['confirmation_mail']) : '';
    $_SESSION['sign_up']['agree'] = isset($_POST['agree']) ? sanitize_text_field($_POST['agree']) : '';

    $year_birth = $_SESSION['sign_up']['year_birth'];
    $month_birth = $_SESSION['sign_up']['month_birth'];
    $day_birth = $_SESSION['sign_up']['day_birth'];

    $data['login_id'] = $_SESSION['sign_up']['login_id'];
    $data['pass'] = $_SESSION['sign_up']['pass'];
    $data['confirmation_pass'] = $_SESSION['sign_up']['confirmation_pass'];
    $data['happy_id'] = $_SESSION['sign_up']['happy_id'];
    $data['excellent_id'] = $_SESSION['sign_up']['excellent_id'];
    $data['mbr_nm'] = $_SESSION['sign_up']['mbr_nm'];
    $data['mbr_knm'] = $_SESSION['sign_up']['mbr_knm'];
    $data['mail'] =  $_SESSION['sign_up']['mail'];
    $data['confirmation_mail'] = $_SESSION['sign_up']['confirmation_mail'];
    $data['mbr_bth'] = $year_birth.($month_birth < 10 ? "0$month_birth" : $month_birth).($day_birth < 10 ? "0$day_birth" : $day_birth);
    $data['agree'] = $_SESSION['sign_up']['agree'];
    $data['year_birth'] = $year_birth;
    $data['month_birth'] = $month_birth;
    $data['day_birth'] = $day_birth;
    $data['validation_type'] = $VALIDATION_TYPE_SIGNUP;

    // 仮登録中のユーザーで無効なユーザーを削除
    delete_invalid_user();

    // ★ wordPress バリデーションチェック
    $res_wp_valid = validation_wp_user($data);
    if ($res_wp_valid) {
        return $res_wp_valid;
    }

    // ★ API バリデーションチェック
    $res_api_valid = validation_api($data);
    if ($res_api_valid) {
        return $res_api_valid;
    }

    wp_redirect(get_home_url().'/web-registration/confirmation/');
    return;
}

/*-------------------------------------------*/
/*  AJAX: 新規WEB会員登録 確認画面 => 完了画面  => 完了画面
/*-------------------------------------------*/
function save_signup()
{
    session_check();
    global $VALIDATION_TYPE_SIGNUP;

    $year_birth = $_SESSION['sign_up']['year_birth'];
    $month_birth = $_SESSION['sign_up']['month_birth'];
    $day_birth = $_SESSION['sign_up']['day_birth'];

    $data['login_id'] = $_SESSION['sign_up']['login_id'];
    $data['pass'] = $_SESSION['sign_up']['pass'];
    $data['confirmation_pass'] = $_SESSION['sign_up']['confirmation_pass'];
    $data['happy_id'] = $_SESSION['sign_up']['happy_id'];
    $data['excellent_id'] = $_SESSION['sign_up']['excellent_id'];
    $data['mbr_nm'] = $_SESSION['sign_up']['mbr_nm'];
    $data['mbr_knm'] = $_SESSION['sign_up']['mbr_knm'];
    $data['mail'] =  $_SESSION['sign_up']['mail'];
    $data['confirmation_mail'] = $_SESSION['sign_up']['confirmation_mail'];
    $data['mbr_bth'] = $year_birth.($month_birth < 10 ? "0$month_birth" : $month_birth).($day_birth < 10 ? "0$day_birth" : $day_birth);
    $data['agree'] = $_SESSION['sign_up']['agree'];
    $data['year_birth'] = $year_birth;
    $data['month_birth'] = $month_birth;
    $data['day_birth'] = $day_birth;
    $data['agree'] = true;
    $data['validation_type'] = $VALIDATION_TYPE_SIGNUP;

    // ★ wordPress バリデーションチェック
    $res_wp_valid = validation_wp_user($data);
    if ($res_wp_valid) {
        wp_redirect(get_home_url().'/web-registration');
        wp_die();
    }

    // ★ API バリデーションチェック
    $res_api_valid = validation_api($data);
    if ($res_api_valid) {
        wp_die();
        wp_redirect(get_home_url().'/web-registration');
    }

    $res_create_user =  exec_create_user($data);
    $user_id = $res_create_user ['user_id'];

    if ($res_create_user['code'] === 1) {
        $result['code'] = 1;
        echo json_encode($result);
        wp_die();
    }

    // メール処理
    // メール本文に記載するコンテンツ作成
    $nonce = $res_create_user['nonce'];
    $method = $res_create_user['method'];
    $key = 'happy-family';
    $encode = urlencode(openssl_encrypt($nonce, $method, $key, 0)); // url変換時に/が%になってしまうため、文字変換。
    $signup_url = home_url('/web-registration/register/?_wpnonce='.$encode); // 暗号化したURLを作成
    $mbr_name = $data['mbr_nm'];
    $mail = $data['mail'];
    // メール本文作成
    $user_text = make_sign_up_email($mbr_name, $signup_url);
    // メール送信
    // $wiz_mail = ['nishioka-tsubasa@wiznet.co.jp', 'yoshizaki-airi@wiznet.co.jp', $mail];
    $header = 'From: ハッピーファミリーWEB会員サイト <happymembers@happyfamily.co.jp>' . "\r\n";
    $subject = '【WEB会員登録】仮登録メール';
    $result_send_email = wp_mail($mail, $subject, $user_text, $header);
    $result_send_email = true;
    $user_id = '';

    // メール送信が成功の有無 / メール送信が失敗すれば作成したユーザーを削除
    if ($result_send_email === true) {
        $result['code'] = 0;
        $_SESSION['sing_up'] = [];
        echo json_encode($result);
        wp_die();
    }
    $result['code'] = 1;
    wp_delete_user($user_id);
    echo json_encode($result);
    wp_die();
}
add_action('wp_ajax_save_signup', 'save_signup');
add_action('wp_ajax_nopriv_save_signup', 'save_signup');

/*-------------------------------------------*/
/*  新規WEB会員登録  確認画面 => メール送信
/*-------------------------------------------*/
function exec_create_user($data)
{
    // ユーザー保存
    $user_id = wp_create_user($data['login_id'], $data['pass'], $data['mail']);
    $created_at  = date_i18n('Y-m-d H:i:s');
    $updated_at = "";

    // ユーザー作成失敗
    if (is_wp_error($user_id)) {
        $result['code'] = 1;
        // echo json_encode($result);
        // wp_die();
        return $result;
    }

    // nonceなどメタ情報作成
    $method = 'AES-128-CBC'; //暗号化の方法
    $ivLen = openssl_cipher_iv_length($method); //nonce作成
    $nonce = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $ivLen);
    $valid = 0;
    $nonce_created_at = date_i18n('Y-m-d H:i:s');

    // user_meta登録
    $metadata = array(
        'happy_id'      => $data['happy_id'],
        'excellent_id'  => $data['excellent_id'],
        'nonce'            => $nonce,
        'nonce_created_at' => $nonce_created_at,
        'is_valid'         => $valid,
        'create_at'     => $created_at,
        'updated_at'    => $updated_at,
    );
    $result_add_meta = [];
    foreach ($metadata as $key => $value) {
        $res = add_user_meta($user_id, $key, $value);
        array_push($result_add_meta, $res);
    }

    // メタが正しく登録されなければ処理中断
    if (in_array(false, $result_add_meta, true)) {
        wp_delete_user($user_id);
        $result['code'] = 1;
        return $result;
    }

    $result = ['code' => 0, 'nonce' => $nonce, 'method' => $method, 'user_id' => $user_id];
    return $result;
}

/*-------------------------------------------*/
/*  新規WEB会員登録  本会員登録完了
/*-------------------------------------------*/
function main_member_registration($wpnonce)
{
    global $wpdb;
    $key = 'happy-family';
    $method = 'AES-128-CBC';
    $nonce = openssl_decrypt($wpnonce, $method, $key, 0);

    if (!$nonce) {
        $result['message'] = 'WEB会員登録を完了できませんでした。<br>URLが正しくありません。';
        $result['code'] = 1;
        return $result;
    }

    $res_nonce = $wpdb->get_results("SELECT user_id FROM wp_usermeta WHERE meta_key = 'nonce' AND meta_value = '$nonce'");

    // 期限が切れて削除されている場合は存在しないため
    if (!$res_nonce) {
        $result['message'] = 'URLが期限切れです。下記のWEB会員登録ページより再度仮登録を行ってください。';
        $result['code'] = 1;
        return $result;
    }

    $user_id = $res_nonce[0]->user_id;

    $current_time = date_i18n('Y-m-d H:i:s');
    $nonce_created_at = get_user_meta($user_id, 'nonce_created_at', true);
    $limit_time = date('Y-m-d H:i:s', strtotime($nonce_created_at."+1 day"));
    // $limit_time = date('Y-m-d H:i:s', strtotime($nonce_created_at."+ 60"));

    if (strtotime($limit_time) > strtotime($current_time)) {
        update_user_meta($user_id, 'is_valid', 1); // metaのis_validを有効にする
        $result['message'] = 'WEB会員登録が完了しました。<br>以下のボタンよりログインを行なってください。';
        $result['code'] = 0;
        return $result;
    }
    $result['message'] = 'WEB会員登録を完了できませんでした。<br>URLが期限切れです。下記のWEB会員登録ページより再度仮登録を行ってください。';
    $result['code'] = 1;
    return $result;
}


/********************************************** WEB会員情報変更 ***********************************************/
/*-------------------------------------------*/
/*  WEB会員情報変更画面 => 確認画面
/*-------------------------------------------*/
function edit_user_info()
{
    global $VALIDATION_TYPE_EDIT;
    session_check();

    // $user = wp_get_current_user();
    // $user_id = $user->ID;

    $_SESSION['temp']['pass'] = isset($_POST['pass']) ? sanitize_text_field($_POST['pass']) : '';
    $_SESSION['temp']['confirmation_pass'] = isset($_POST['pass']) ? sanitize_text_field($_POST['confirmation_pass']) : '';
    $_SESSION['temp']['happy_id'] = isset($_POST['happy_id']) ? sanitize_text_field($_POST['happy_id']) : '';
    $_SESSION['temp']['excellent_id'] = isset($_POST['excellent_id']) ? sanitize_text_field($_POST['excellent_id']) : '';
    $_SESSION['temp']['mail'] = isset($_POST['mail']) ? sanitize_text_field($_POST['mail']) : '';
    $_SESSION['temp']['confirmation_mail'] = isset($_POST['confirmation_mail']) ? sanitize_text_field($_POST['confirmation_mail']) : '';

    // バリデーションの引数
    $data['pass'] = $_SESSION['temp']['pass'];
    $data['confirmation_pass'] = $_SESSION['temp']['confirmation_pass'];
    $data['happy_id'] = $_SESSION['temp']['happy_id'];
    $data['excellent_id'] = $_SESSION['temp']['excellent_id'];
    // $data['happy_id_current'] = get_user_meta($user_id, 'happy_id', true);
    // $data['excellent_id_current'] = get_user_meta($user_id, 'excellent_id', true);
    $data['mbr_nm'] = $_SESSION['user']['mbr_nm'];
    $data['mbr_knm'] = $_SESSION['user']['mbr_knm'];
    $data['mail'] = $_SESSION['temp']['mail'];
    $data['confirmation_mail'] = $_SESSION['temp']['confirmation_mail'];

    // セッションの情報
    if (isset($_SESSION['user']['hp']['mbr_id'])) {
        $data['hp']['mbr_nm'] = $_SESSION['user']['hp']['mbr_nm'];
        $data['hp']['mbr_knm'] = $_SESSION['user']['hp']['mbr_knm'];
    }
    if (isset($_SESSION['user']['ex']['mbr_id'])) {
        $data['ex']['mbr_nm'] = $_SESSION['user']['ex']['mbr_nm'];
        $data['ex']['mbr_knm'] = $_SESSION['user']['ex']['mbr_knm'];
    }
    $data['mbr_bth'] = $_SESSION['user']['mbr_bth'];
    $data['validation_type'] = $VALIDATION_TYPE_EDIT;

    // 登録済で有効ではないユーザーを削除する
    delete_invalid_user();

    // ★ wordPress バリデーションチェック
    $res_wp_valid = validation_wp_user($data);
    if ($res_wp_valid) {
        return $res_wp_valid;
    }

    // ★ API バリデーションチェック
    $res_api_valid = validation_api($data);
    if ($res_api_valid) {
        return $res_api_valid;
    }

    wp_redirect(get_home_url().'/member-info/edit/confirmation');
    return;
}
/*-------------------------------------------*/
/*  AJAX: WEB会員情報更新処理 => メール送信 => 完了画面
/*-------------------------------------------*/
function update_user()
{
    global $VALIDATION_TYPE_EDIT;
    session_check();

    // バリデーションの引数
    $data['pass'] = $_SESSION['temp']['pass'];
    $data['confirmation_pass'] = $_SESSION['temp']['confirmation_pass'];
    $data['happy_id'] = $_SESSION['temp']['happy_id'];
    $data['excellent_id'] = $_SESSION['temp']['excellent_id'];
    // $data['happy_id_current'] = get_user_meta($user_id, 'happy_id', true);
    // $data['excellent_id_current'] = get_user_meta($user_id, 'excellent_id', true);
    $data['mbr_nm'] = $_SESSION['user']['mbr_nm'];
    $data['mbr_knm'] = $_SESSION['user']['mbr_knm'];
    $data['mail'] = $_SESSION['temp']['mail'];
    $data['confirmation_mail'] = $_SESSION['temp']['confirmation_mail'];

    // セッションの情報
    if (isset($_SESSION['user']['hp']['mbr_id'])) {
        $data['hp']['mbr_nm'] = $_SESSION['user']['hp']['mbr_nm'];
        $data['hp']['mbr_knm'] = $_SESSION['user']['hp']['mbr_knm'];
    }
    if (isset($_SESSION['user']['ex']['mbr_id'])) {
        $data['ex']['mbr_nm'] = $_SESSION['user']['ex']['mbr_nm'];
        $data['ex']['mbr_knm'] = $_SESSION['user']['ex']['mbr_knm'];
    }
    $data['mbr_bth'] = $_SESSION['user']['mbr_bth'];
    $data['validation_type'] = $VALIDATION_TYPE_EDIT;

    // ★ wordPress バリデーションチェック
    // $res_wp_valid = validation_edit_user($happy_id, $excellent_id, $pass, $confirmation_pass, $mail, $confirmation_mail);
    $res_wp_valid = validation_wp_user($data);
    if ($res_wp_valid) {
        $res_wp_valid['code'] = 1;
        echo json_encode($res_wp_valid);
        wp_die();
    }

    // 更新処理
    $user = wp_get_current_user();
    $user_id = $user->ID;
    $res_update = exec_update_user($data['mail'], $data['happy_id'], $data['excellent_id'], $data['pass'], $user_id);
    $mbr_nm = $data['mbr_nm'];

    if ($res_update['code'] === 1) {
        $result['code'] = 1;
        echo json_encode($result);
        wp_die();
    }

    // メール文面
    $user_text = <<< EOT
        {$mbr_nm}様<br><br>

        ハッピーファミリーWEB会員サイトの会員情報の変更が完了いたしました。<br><br>

        このメールに心あたりのない場合、<br>
        こちらのメールアドレスへお問い合わせいただくようお願いいたします。<br>
        happymembers@happyfamily.co.jp<br><br>

        【お問い合わせ先】<br>
        ■ 会社名<br>
        ハッピーファミリー株式会社<br><br>
        ■ 住所<br>
        〒532-0003 大阪市淀川区宮原2丁目14番14号<br><br>
        ■ 電話番号<br>
        0120-198-141<br><br>

        EOT;

    // 更新処理が完了したらメールで通知
    // $wiz_mail = ['nishioka-tsubasa@wiznet.co.jp', 'yoshizaki-airi@wiznet.co.jp', $data['mail']];
    $header = 'From: ハッピーファミリーWEB会員サイト <happymembers@happyfamily.co.jp>' . "\r\n";
    $subject = '【WEB会員情報変更】完了通知メール';
    $success = wp_mail($data['mail'], $subject, $user_text, $header);

    if ($success) {
        $result['code'] = 0;
        $_SESSION['temp'] = [];
        echo json_encode($result);
        wp_die();
    }

    $result['code'] = 1;
    echo json_encode($result);
    wp_die();
}
add_action('wp_ajax_update_user', 'update_user');
add_action('wp_ajax_nopriv_update_user', 'update_user');

/*-------------------------------------------*/
/*  会員登録情報アップデート実行
/*-------------------------------------------*/
function exec_update_user($mail, $happy_id, $excellent_id, $pass, $user_id)
{
    session_check();

    $old_happy_id = get_user_meta($user_id, 'happy_id', true);
    $old_excellent_id = get_user_meta($user_id, 'excellent_id', true);
    $updated_at  = date_i18n('Y-m-d H:i:s');

    // var_dump($hash_pass);
    // wp_die();
    // return;

    // USRテーブルの情報変更
    $user = get_user_by('id', $user_id);
    $user->user_email = $mail;
    if ($pass) {
        $hash_pass = wp_hash_password($pass);
        $user->user_pass = $hash_pass;
    }

    $user_id = wp_insert_user($user);
    if (is_wp_error($user_id)) {
        $result['code'] = 1;
        return $result;
    }
    $_SESSION['user']['user_email'] = $mail;

    // // 本番環境でパスワードがハッシュ化されないためinsertへ変更
    // $user_id = wp_update_user($user);

    // if (is_wp_error($user_id)) {
    //     return false;
    // } else {
    //     $_SESSION['user']['user_email'] = $mail;
    // }

    // 変更されていないデータをupdateするとエラーになるので、変更データのみ配列に入れる。
    // TODO: アップデート処理で失敗した時元に戻す方法がわからない。
    if ($happy_id !== $old_happy_id) {
        $metadata['happy_id'] = $happy_id;
    }
    if ($excellent_id !==  $old_excellent_id) {
        $metadata['excellent_id'] = $excellent_id;
    }
    if (isset($metadata)) {
        $metadata['updated_at'] = $updated_at;
        foreach ($metadata as $key => $value) {
            $result[$key] = update_user_meta($user_id, $key, $value);
        }
    }
    $result['code'] = 0;
    return $result;
}

/********************************************** パスワード再発行 ***********************************************/
/*-------------------------------------------*/
/*  パスワード再発行 => 確認画面
/*-------------------------------------------*/
function confirm_reissue_pass()
{
    global $VALIDATION_TYPE_REISSUE_PASS;
    session_check();

    $_SESSION['temp']['login_id'] = isset($_POST['login_id']) ? sanitize_text_field($_POST['login_id']) : '';
    $_SESSION['temp']['mbr_nm'] = isset($_POST['mbr_nm']) ? sanitize_text_field($_POST['mbr_nm']) : '';
    $_SESSION['temp']['mbr_knm'] = isset($_POST['mbr_knm']) ? sanitize_text_field($_POST['mbr_knm']) : '';
    $_SESSION['temp']['year_birth'] = isset($_POST['year_birth']) ? sanitize_text_field($_POST['year_birth']) : '';
    $_SESSION['temp']['month_birth'] = isset($_POST['month_birth']) ? sanitize_text_field($_POST['month_birth']) : '';
    $_SESSION['temp']['day_birth'] = isset($_POST['day_birth']) ? sanitize_text_field($_POST['day_birth']) : '';
    $_SESSION['temp']['mail'] = isset($_POST['mail']) ? sanitize_text_field($_POST['mail']) : '';
    $_SESSION['temp']['confirmation_mail'] = isset($_POST['confirmation_mail']) ? sanitize_text_field($_POST['confirmation_mail']) : '';

    $login_id = $_SESSION['temp']['login_id'];
    $year_birth = $_SESSION['temp']['year_birth'];
    $month_birth = $_SESSION['temp']['month_birth'];
    $day_birth = $_SESSION['temp']['day_birth'];

    $data['login_id'] = $_SESSION['temp']['login_id'];
    $data['mbr_nm'] = $_SESSION['temp']['mbr_nm'];
    $data['mbr_knm'] = $_SESSION['temp']['mbr_knm'];
    $data['year_birth'] = $_SESSION['temp']['year_birth'];
    $data['month_birth'] = $_SESSION['temp']['month_birth'];
    $data['day_birth'] = $_SESSION['temp']['day_birth'];
    $data['mail'] =  $_SESSION['temp']['mail'];
    $data['confirmation_mail'] = $_SESSION['temp']['confirmation_mail'];
    $data['mbr_bth'] = $year_birth.($month_birth < 10 ? "0$month_birth" : $month_birth).($day_birth < 10 ? "0$day_birth" : $day_birth);
    $data['validation_type'] = $VALIDATION_TYPE_REISSUE_PASS;

    // ★ wordPress バリデーションチェック
    $res_wp_valid = validation_wp_user($data);
    if ($res_wp_valid) {
        return $res_wp_valid;
    }

    // ログインIDからハッピーIDとエクセレントIDを呼び出す
    $user = get_user_by('login', $login_id);
    $user_id =$user->ID;
    $data['happy_id'] = get_user_meta($user_id, 'happy_id', true);
    $data['excellent_id'] = get_user_meta($user_id, 'excellent_id', true);

    // ★ API バリデーションチェック
    $res_api_valid = validation_api($data);
    if ($res_api_valid) {
        return $res_api_valid;
    }

    wp_redirect(get_home_url().'/pass-reissue/confirmation/');
    return;
}

/*-------------------------------------------*/
/* AJAX： パスワード再発行 確認画面 => メール送信 => 完了画面
/*-------------------------------------------*/
function reissue_pass()
{
    session_check();

    $login_id = $_SESSION['temp']['login_id'];
    $mbr_nm = $_SESSION['temp']['mbr_nm'];
    $mail =  $_SESSION['temp']['mail'];

    // ログインIDからハッピーIDとエクセレントIDを呼び出す
    $user = get_user_by('login', $login_id);
    $user_id =$user->ID;

    $res_reissue_pass = exec_reissue_pass($user_id);

    if ($res_reissue_pass['code'] === 1) {
        $result['code'] = 1;
        echo json_encode($result);
        wp_die();
    }

    // メール本文作成
    $temp_pass = $res_reissue_pass['temp_pass'];
    $user_text = <<< EOT
    {$mbr_nm}様<br><br>

    ハッピーファミリーWEB会員サイトより<br><br>

    パスワード再発行を承りました。<br>
    ログイン後、必ずパスワードの再設定を行なってください。<br><br>

    仮パスワード：$temp_pass<br><br>

    このメールに心あたりのない場合、<br>
    こちらのメールアドレスへお問い合わせいただくようお願いいたします。<br>
    happymembers@happyfamily.co.jp<br><br>

    【お問い合わせ先】<br>
    ■ 会社名<br>
    ハッピーファミリー株式会社<br><br>
    ■ 住所<br>
    〒532-0003 大阪市淀川区宮原2丁目14番14号<br><br>
    ■ 電話番号<br>
    0120-198-141<br><br>

    EOT;

    // $wiz_mail = ['nishioka-tsubasa@wiznet.co.jp', 'yoshizaki-airi@wiznet.co.jp', $mail];
    $header = 'From: ハッピーファミリーWEB会員サイト <happymembers@happyfamily.co.jp>' . "\r\n";
    $subject = '【WEB会員】パスワード再発行';
    $success = wp_mail($mail, $subject, $user_text, $header);

    if ($success) {
        $result['code'] = 0;
        $_SESSION['temp'] = [];
        echo json_encode($result);
        wp_die();
    }
    $result['code'] = 1; // 変更完了 メール送信不可の場合でも再度再発行を行ってもらう。
    // $_SESSION['temp'] = [];
    echo json_encode($result);
    wp_die();
}
add_action('wp_ajax_reissue_pass', 'reissue_pass');
add_action('wp_ajax_nopriv_reissue_pass', 'reissue_pass');

/*-------------------------------------------*/
/*  パスワード再発行 実行
/*-------------------------------------------*/
function exec_reissue_pass($user_id)
{
    $temp_pass = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz#@|-$!'), 0, 10);

    // パスワード更新処理
    $user = get_user_by('id', $user_id);
    $user->user_pass = $temp_pass;
    wp_update_user($user);

    if (is_wp_error($user_id)) {
        return $result['code'] = 1;
    }

    $result['temp_pass'] = $temp_pass;
    $result['code'] = 0;

    return $result;
}

/********************************************** 商品一覧 / 金額取得 ***********************************************/
function normalize_product_code($code)
{
    $raw = (string) $code;
    $raw = trim($raw);
    $ltrimed = ltrim($raw, '0');
    if ($ltrimed === '') {
        $ltrimed = '0';
    }
    return [
        'raw' => $raw,
        'no_leading_zeros' => $ltrimed,
    ];
}
function ajax_get_product_title_by_code()
{
    if (!isset($_REQUEST['product_code'])) {
        wp_send_json(['found' => false]);
        wp_die();
    }

    $code = sanitize_text_field($_REQUEST['product_code']);
    $detail = show_product($code, true);

    if ($detail['post_id'] && $detail['post_id'] > 0) {
        $permalink = get_permalink(intval($detail['post_id']));
        wp_send_json([
            'found' => true,
            'title' => $detail['title'],
            'post_id' => intval($detail['post_id']),
            'permalink' => $permalink,
        ]);
        wp_die();
    }

    wp_send_json(['found' => false]);
    wp_die();
}
add_action('wp_ajax_get_product_title_by_code', 'ajax_get_product_title_by_code');
add_action('wp_ajax_nopriv_get_product_title_by_code', 'ajax_get_product_title_by_code');

/*-------------------------------------------*/
/*  商品金額取得
/*-------------------------------------------*/
function get_product_price($mbr_kd, $code, $cat_product, $regular_price)
{
    session_check();

    $regular_price_num = (int)str_replace(',', '', $regular_price);
    $user = wp_get_current_user();
    $user_id = $user->ID;
    $happy_id = get_user_meta($user_id, 'happy_id', true);
    $excellent_id = get_user_meta($user_id, 'excellent_id', true);

    // 管理者アカウントであれば一律1000円で商品を表示
    if (current_user_can('manage_options')) {
        $price = 1000;
        return number_format($price);
    }

    //  分類カテゴリを判別
    if ($cat_product === '健康食品') {
        $classification = 1;
    } elseif ($cat_product === '化粧品') {
        $classification = 2;
    } elseif ($cat_product === '洗剤') {
        $classification = 3;
    } elseif ($cat_product === '機器販促品') {
        $classification = 4;
    } elseif ($cat_product === 'カートリッジ') {
        $classification = 5;
    } elseif ($cat_product === 'ファミリー割引商品') {
        $classification = 6;
    } elseif ($cat_product === '販促品') {
        $classification = 7;
    }

    // 所属によってAPIに渡すIDを変更する
    $res = null;
    if ($mbr_kd === 0) {
        if ($happy_id) {
            $regular_price_num = str_replace(',', '', $regular_price);
            $res = tryCatch('API_GetProductAmount', [$mbr_kd, $happy_id, (int)$code, (int)$classification, (int)$regular_price_num], false);
        // echo 'パラメーター / mbr_kd:'.$mbr_kd.'<br>mbr_id:'.$happy_id.'<br>code:'.$code.'<br>syohin_bn:'.$classification.'<br>regular_price:'.$regular_price_num.'<br>戻り値：';
        } else {
            $_SESSION['product'][$code] = $regular_price;
            return $regular_price;
        }
    } elseif ($mbr_kd === 1) {
        if ($excellent_id) {
            $regular_price_num = str_replace(',', '', $regular_price);
            $res = tryCatch('API_GetProductAmount', [$mbr_kd, $excellent_id, (int)$code, (int)$classification, (int)$regular_price_num], false);
        // echo 'パラメーター / mbr_kd:'.$mbr_kd.'<br>mbr_id:'.$excellent_id.'<br>code:'.$code.'<br>syohin_bn:'.$classification.'<br>regular_price:'.$regular_price_num.'<br>戻り値：';
        } else {
            $_SESSION['product'][$code] = $regular_price;
            return $regular_price;
        }
    }

    // データ取得に成功しなければ
    if ($res->code !== 0) {
        return $regular_price_num;
    }

    // 管理者アカウントではない 且 データを正常に取得
    $price = $res->kin;
    $_SESSION['product'][$code] = $price;
    return $price;
}

/********************************************** 商品詳細 ***********************************************/
// /*-------------------------------------------*/
// /*  ***** 商品一覧の詳細情報取得
// /*-------------------------------------------*/
// function complete_product_list_for_order($data_order_products)
// {
//     foreach ($data_order_products as $key => $data) {
//         $detail = show_product($data->product_code);                   // 商品情報取得
//         $status = check_status($data->order_id, $data->product_code);  // 発送状況

//         $product['status'] = $status;
//         $product['post_id'] = $detail['post_id'];
//         $product['product_name'] = $detail['title'];
//         $product['order_id'] = $data->order_id;
//         $product['product_code'] = $data->product_code ;
//         $product['quantity'] = $data->quantity;
//         $product['purchase_price'] = number_format($data->purchase_price);
//         $product['subtotal'] = number_format($data->subtotal); // 自動で円に変換

//         $terms = get_the_terms($product['post_id'], 'product');
//         foreach ($terms as $term) {
//             // 取引区分
//             if ($term->parent === 12) {
//                 $transaction = $term->name;   // 取引区分
//                 $transaction_check[] = $term->name;
//             // 商品分類
//             } elseif ($term->parent === 15) {
//                 $cat_product = $term->name;
//             }
//             // 購入権限判断
//             if ($term->term_id === 13) {        // 13: ハッピー商品
//                 $product_type = 0;              // 商品タイプ 0:ハッピー 1:エクセレント
//                 $cant_buy_mbr_type = 1;         // 購入不可　 0:ハッピー 1:エクセレント 2:どちらも購入できる
//             } elseif ($term->term_id === 14) {  // 14: エクセレント商品
//                 $product_type = 1;
//                 $cant_buy_mbr_type = 0;
//             }
//         }
//         if (count($transaction_check) > 2) {
//             $transaction = '共通';
//         }

//         $product['transaction'] = $transaction;
//         $product['category'] = $cat_product;
//         $product['product_type'] = $product_type;
//         $product['cant_buy_mbr_type'] = $cant_buy_mbr_type;

//         if ($status) {
//             $product['status'] = $status;
//         }

//         $order_products[$key] = $product;
//     }

//     return $order_products;
// }

/********************************************** カート関連 ***********************************************/
/*-------------------------------------------*/
/*  ユーザー毎にカート一覧取得
/*-------------------------------------------*/
function fetch_cart_list_by_user_id()
{
    global $wpdb;
    $user_id = wp_get_current_user()->ID;
    if (! $user_id) {
        return [];
    }

    $sql = $wpdb->prepare("SELECT * FROM carts WHERE user_id = %d ORDER BY CAST(`product_code` AS SIGNED)", $user_id);
    $res_cart = $wpdb->get_results($sql);
    return $res_cart;
}

/*-------------------------------------------*/
/*  AJAX: カート数量取得 (ユーザー毎)
/*-------------------------------------------*/
function count_cart_product_by_user_id()
{
    global $wpdb;
    $user_id = wp_get_current_user()->ID;
    if (! $user_id) {
        echo json_encode(['count' => 0]);
        wp_die();
    }
    $sum = $wpdb->get_var($wpdb->prepare("SELECT SUM(quantity) FROM carts WHERE user_id = %d", $user_id));
    $result_count = $sum ? intval($sum) : 0;
    echo json_encode(['count' => $result_count]);
    wp_die();
}
add_action('wp_ajax_count_cart_product_by_user_id', 'count_cart_product_by_user_id');
add_action('wp_ajax_nopriv_count_cart_product_by_user_id', 'count_cart_product_by_user_id');

/*-------------------------------------------*/
/*  上と一緒　AJAX： カート数量取得 (ユーザー毎)
/*-------------------------------------------*/
function fetch_quantity_by_user_id()
{
    global $wpdb;
    $user_id = wp_get_current_user()->ID;
    if (! $user_id) {
        echo json_encode(['count' => 0]);
        wp_die();
    }
    $sum = $wpdb->get_var($wpdb->prepare("SELECT SUM(quantity) FROM carts WHERE user_id = %d", $user_id));
    $result_count = $sum ? intval($sum) : 0;
    echo json_encode(['count' => $result_count]);
    wp_die();
}
add_action('wp_ajax_fetch_quantity_by_user_id', 'fetch_quantity_by_user_id');
add_action('wp_ajax_nopriv_fetch_quantity_by_user_id', 'fetch_quantity_by_user_id');
/*-------------------------------------------*/
/*  AJAX：カート 更新/新規登録処理
/*-------------------------------------------*/
function create_or_update_cart()
{
    global $wpdb;
    $result = [];
    // sanitize incoming params
    $product_code = isset($_POST['code']) ? sanitize_text_field($_POST['code']) : '';
    $input_quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    if ($product_code === '' || $input_quantity === 0) {
        $result['result'] = 'error';
        echo json_encode($result);
        wp_die();
    }

    $detail = show_product($product_code);
    $regular_price = $detail['regular_price'];
    $user_id = wp_get_current_user()->ID;

    // 安全に取得
    $res = $wpdb->get_results($wpdb->prepare("SELECT * FROM carts WHERE product_code = %s AND user_id = %d", $product_code, $user_id));

    // cartに存在している場合は更新
    if ($res) {
        $cart_quantity = intval($res[0]->quantity);
        $total_quantity = $cart_quantity + $input_quantity;
        $result['count'] = $cart_quantity;
        // 合計が99個以下の場合更新
        if ($total_quantity <= 99) {
            $cart_id = intval($res[0]->id);
            $data =  [
                'quantity' => $total_quantity,
                'regular_price' => $regular_price,
                'updated_at' => date_i18n('Y-m-d H:i:s'),
            ];
            $where = ['id' => $cart_id];
            $format = ['%d','%d','%s'];
            $where_format = ['%d'];
            $wpdb->update('carts', $data, $where, $format, $where_format);
            $result['result'] = 'success';
        } else {
            $result['result'] = 'over';
        }
        echo json_encode($result);
        wp_die();
    }

    // cartに存在していない場合新規登録
    $data =  [
        'user_id' => $user_id,
        'product_code' => $product_code,
        'quantity' => $input_quantity,
        'regular_price' => $regular_price,
        'created_at' => date_i18n('Y-m-d H:i:s'),
    ];
    $format = ['%d','%s','%d','%d','%s'];
    $wpdb->insert('carts', $data, $format);
    $result['result'] = 'success';
    echo json_encode($result);
    wp_die();
    return;
}
add_action('wp_ajax_create_or_update_cart', 'create_or_update_cart');
add_action('wp_ajax_nopriv_create_or_update_cart', 'create_or_update_cart');
/*-------------------------------------------*/
/*  AJAX：カート 更新処理(1つづつ追加or減少)
/*-------------------------------------------*/
function update_cart_by_one()
{
    global $wpdb;
    $result = [];
    $product_code = isset($_POST['code']) ? sanitize_text_field($_POST['code']) : '';
    $input_quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    if ($product_code === '') {
        $result['result'] = 'error';
        echo json_encode($result);
        wp_die();
    }

    $detail = show_product($product_code);
    $regular_price = $detail['regular_price'];
    $user_id = wp_get_current_user()->ID;

    $res = $wpdb->get_results($wpdb->prepare("SELECT * FROM carts WHERE product_code = %s AND user_id = %d", $product_code, $user_id));
    if (! $res) {
        $result['result'] = 'error';
        echo json_encode($result);
        wp_die();
    }
    $cart_id = intval($res[0]->id);
    $data =  [
        'quantity' => $input_quantity,
        'regular_price' => $regular_price,
        'updated_at' => date_i18n('Y-m-d H:i:s'),
    ];
    $where = ['id' => $cart_id];
    $format = ['%d','%d','%s'];
    $where_format = ['%d'];
    $update_res = $wpdb->update('carts', $data, $where, $format, $where_format);
    if ($update_res === false) {
        $result['result'] = 'error';
        echo json_encode($result);
        wp_die();
    }
    $result['result'] = 'success';
    echo json_encode($result);
    wp_die();
}
add_action('wp_ajax_update_cart_by_one', 'update_cart_by_one');
add_action('wp_ajax_nopriv_update_cart_by_one', 'update_cart_by_one');
/*-------------------------------------------*/
/*  AJAX: カート 削除
/*-------------------------------------------*/
function delete_cart()
{
    global $wpdb;
    $product_code = isset($_POST['code']) ? sanitize_text_field($_POST['code']) : '';
    $user_id = wp_get_current_user()->ID;

    if ($product_code === '') {
        echo json_encode(['result' => 'error']);
        wp_die();
    }

    $res_product = $wpdb->get_results($wpdb->prepare("SELECT * FROM carts WHERE product_code = %s AND user_id = %d", $product_code, $user_id));
    if (! $res_product) {
        echo json_encode(['result' => 'not_found']);
        wp_die();
    }
    $cart_id = intval($res_product[0]->id);
    $where = ['id' => $cart_id];
    $where_format = ['%d'];
    $wpdb->delete('carts', $where, $where_format);

    // ユーザーがカートに保持している商品取得
    $res_cart = fetch_cart_list_by_user_id();

    $result = [
        'product_code' => $product_code,
        'product_exist' => !empty($res_cart),
    ];
    echo json_encode($result);
    wp_die();
}
add_action('wp_ajax_delete_cart', 'delete_cart');
add_action('wp_ajax_nopriv_delete_cart', 'delete_cart');
/*-------------------------------------------*/
/*  数量が0の商品はカートから削除する
/*-------------------------------------------*/
function delete_empty_order_product()
{
    $user_id = wp_get_current_user()->ID;
    if (! $user_id) {
        return;
    }
    global $wpdb;
    $query0_prod = $wpdb->get_results($wpdb->prepare("SELECT * FROM carts WHERE user_id = %d AND quantity = 0", $user_id));
    if (! $query0_prod) {
        return;
    }
    $cart_ids = [];
    foreach ($query0_prod as $prod) {
        $cart_ids[] = intval($prod->id);
    }
    if (! empty($cart_ids)) {
        $delete_ids = implode(',', $cart_ids);
        $wpdb->query("DELETE FROM carts WHERE id in (". $delete_ids .") ");
    }
    return;
}

/*-------------------------------------------*/
/*  カート商品の権限チェック / 削除 + 削除後の商品取得
/*-------------------------------------------*/
function delete_not_purchase_cart_product()
{
    session_check();
    global $wpdb;
    global $MEM_COMBINE_STATUS_HPA, $MEM_COMBINE_STATUS_EXA, $MEM_COMBINE_STATUS_EXD, $MEM_COMBINE_STATUS_HPA_EXA, $MEM_COMBINE_STATUS_HPA_EXD;

    $res_cart_products = fetch_cart_list_by_user_id();
    if (! $res_cart_products) {
        return $res_cart_products;
    }

    $removed_product_names = []; // 削除された商品名を収集する配列
    $delete_cart_product_ids = [];

    foreach ($res_cart_products as $cart_product) {
        $product_code = $cart_product->product_code;
        // まずは公開商品として取得を試みる
        $prod_detail = show_product($product_code, true);
        $post_id = $prod_detail['post_id'];

        // --- 商品が非公開（publish以外）または存在しない場合 ---
        if ($post_id === 0) {
            // ★重要：削除前に商品名を取得する（第2引数をfalseにする）
            $hidden_detail = show_product($product_code, false);
            $removed_product_names[] = $hidden_detail['title'];
            
            $delete_cart_product_ids[] = intval($cart_product->id);
            continue; // 非公開商品はここで処理終了（次の商品へ）
        }

        // --- 以下は公開されている商品の権限チェック ---
        $terms = get_the_terms($post_id, 'product');
        $product_type = 0;
        $product_type_list = [];
        foreach ($terms as $term) {
            if ($term->term_id === 13) { $product_type = 0; array_push($product_type_list, $term->name); }
            if ($term->term_id === 14) { $product_type = 1; array_push($product_type_list, $term->name); }
        }
        if (count($product_type_list) === 2) { $product_type = 2; }

        // ログインユーザーのステータスに応じた削除判定
        if ($product_type === 0) {
            if ($_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_EXD || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_EXA) {
                array_push($delete_cart_product_ids, intval($cart_product->id));
            }
        } elseif ($product_type === 1) {
            if ($_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_EXD || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXD) {
                array_push($delete_cart_product_ids, intval($cart_product->id));
            }
        }
    }

    // 削除実行
    if (!empty($delete_cart_product_ids)) {
        $delete_ids = implode(',', array_map('intval', $delete_cart_product_ids));
        $wpdb->query("DELETE FROM carts WHERE id in (". $delete_ids .") ");

        if (!empty($removed_product_names)) {
            $_SESSION['cart_removed_names'] = $removed_product_names;
        }

        return fetch_cart_list_by_user_id();
    }

    return $res_cart_products;
}

/********************************************** 注文処理 ***********************************************/
/*-------------------------------------------*/
/*  AJAX: 注文確定ボタンクリック後の処理/　orders order_products更新 miles mile_historiesの更新 cartから購入商品削除 メール送信
/*-------------------------------------------*/
function send_order_confirmation_mail()
{
    session_check();
    global $wpdb;
    global $MEM_COMBINE_STATUS_HPA;
    global $MEM_COMBINE_STATUS_HPA_EXA;
    global $MEM_COMBINE_STATUS_HPA_EXD;
    global $MEM_COMBINE_STATUS_EXA;
    global $DELIVERY_ADDRESS_KE;
    global $DELIVERY_ADDRESS_RE;

    if (!is_user_logged_in()) {
        wp_send_json(['code' => 4, 'message' => 'ログイン状態を確認できません。再度ログインしてください。'], 401);
    }

    if (!check_ajax_referer('send_order_confirmation_mail', 'security', false)) {
        wp_send_json(['code' => 4, 'message' => '画面の有効期限が切れています。カートからやり直してください。'], 403);
    }

    $order_request_token = isset($_POST['order_token']) ? sanitize_text_field(wp_unslash($_POST['order_token'])) : '';
    $valid_order_tokens = isset($_SESSION['order_request_tokens']) && is_array($_SESSION['order_request_tokens'])
        ? $_SESSION['order_request_tokens']
        : [];

    if ($order_request_token === '' || !isset($valid_order_tokens[$order_request_token])) {
        wp_send_json(['code' => 3, 'message' => 'この注文はすでに処理されたか、画面の有効期限が切れています。'], 409);
    }

    // PHPセッションのロック中にトークンを消費し、同じリクエストの再実行を防ぐ。
    unset($_SESSION['order_request_tokens'][$order_request_token]);

    // user情報
    $user = get_member_info();
    $user_id = $user['id'];
    $user_email = $user['email'];
    $user_name = $user['mbr_nm'];
    $user_syodlv = $user['syodlv'];
    $user_syodlv_tel = $user['syodlv_tel'];

    // 入力情報
    $delivery_request_time = isset($_POST['delivery_request_time'])
        ? str_replace(' ', '', sanitize_text_field(wp_unslash($_POST['delivery_request_time'])))
        : '';
    $use_point = isset($_POST['use_point']) ? intval($_POST['use_point']) : 0;
    if (!$use_point) {
        $use_point = 0;
    }
    if (isset($_POST['delivery_address']) && $_POST['delivery_address'] !== '') {
        $delivery_address = intval($_POST['delivery_address']);
    } else {
        $delivery_address = $user_syodlv;
    }
    // $fee = intval(str_replace('¥', '', $_POST['fee']));

    // カート行をロックし、別タブ・別端末から同時に確定されても片方だけを処理する。
    $wpdb->query('START TRANSACTION');

    $abort_order = function ($message, $status = 500) use ($wpdb) {
        $database_error = $wpdb->last_error;
        $wpdb->query('ROLLBACK');
        if ($database_error) {
            error_log('Order transaction failed: '.$database_error);
        }
        wp_send_json(['code' => 5, 'message' => $message], $status);
    };

    // 購入商品文字列・カートから削除するid・購入商品金額を取得
    $cart_sql = $wpdb->prepare(
        "SELECT * FROM carts WHERE user_id = %d ORDER BY CAST(`product_code` AS SIGNED) FOR UPDATE",
        $user_id
    );
    $cart_list = $wpdb->get_results($cart_sql);
    if ($cart_list === null && $wpdb->last_error) {
        $abort_order('注文情報を確認できませんでした。時間をおいてカートからやり直してください。');
    }
    if (empty($cart_list)) {
        $wpdb->query('ROLLBACK');
        wp_send_json(['code' => 2, 'message' => 'カートに商品がありません。注文は送信されませんでした。'], 409);
    }
    $order_product_list = [];  // メールに記述する購入商品
    $order_items = [];         // 検証済みの購入商品
    $cart_ids = [];            // 購入後削除するid
    $sum_total_arr = [];       // 購入する商品

    foreach ($cart_list as $cart) {
        $cart_id = intval($cart->id);
        $quantity = intval($cart->quantity);
        $code = (string) $cart->product_code;

        $product_detail = show_product($code);
        if (intval($product_detail['post_id'] ?? 0) === 0 || $quantity <= 0 || !isset($_SESSION['product'][$code])) {
            continue;
        }

        $price = intval($_SESSION['product'][$code]); // セッション(商品コードごとに)取得した金額が入っている
        $sub_total = intval($price * $quantity);
        $purchase_price_str = number_format($price);
        $sub_total_str = number_format($sub_total);
        $product_name = htmlspecialchars_decode($product_detail['title']);
        $cart_product = "<tr>\n<td style='text-align:right;padding-right:8px'>{$code}</td><td>{$product_name}</td><td style='text-align:right;padding-right:8px'>{$purchase_price_str}円</td><td style='text-align:right;padding-right:8px'>{$quantity}</td><td style='text-align:right;padding-right:8px'>{$sub_total_str}円</td></tr>\n";

        array_push($sum_total_arr, $sub_total);
        array_push($order_product_list, $cart_product);
        $order_items[] = [
            'cart' => $cart,
            'product' => $product_detail,
        ];
        array_push($cart_ids, $cart_id);
    }
    if (empty($order_product_list) || empty($order_items) || empty($cart_ids)) {
        $wpdb->query('ROLLBACK');
        wp_send_json(['code' => 2, 'message' => '注文可能な商品がありません。注文は送信されませんでした。'], 409);
    }
    // 商品合計 sum_total / 割引後価格 discount_price
    $sum_total = array_sum($sum_total_arr);
    $fee = 0;
    $no_fee_purchase_piice = getSettingPriceForFee();
    if ($sum_total > 0 && $sum_total < $no_fee_purchase_piice) {
        $fee = getFee();
    }

    // マイル使用があった場合：メールの文面に項目追加
    if ($use_point) {
        $use_point_format = number_format($use_point);
        $order_product_list[] = "<tr>\n<td style='text-align:right;padding-right:8px'>3200</td><td>マイル使用</td><td style='text-align:right;padding-right:8px'>-".$use_point_format."円</td><td style='text-align:right;padding-right:8px'>-1</td><td style='text-align:right;padding-right:8px'>-{$use_point_format}円</td></tr>\n";
    }
    // 出荷事務手数料があった場合：メールの文面に項目追加
    if ($fee > 0) {
        $order_product_list[] = "<tr>\n<td style='text-align:right;padding-right:8px'>4033</td><td>出荷事務手数料</td><td style='text-align:right;padding-right:8px'>".number_format($fee)."円</td><td style='text-align:right;padding-right:8px'>1</td><td style='text-align:right;padding-right:8px'>".number_format($fee)."円</td></tr>\n";
    }

    // orders_code作成
    $this_year = date("Y");
    $yy = substr($this_year, 2, 3);
    $order = $wpdb->get_results("SELECT * FROM `orders` WHERE `created_at` LIKE '%$this_year%'");
    $order_num = count($order);
    $num_of_next = $order_num + 1;
    $num_of_zero = 6 - strlen($num_of_next);
    $order_code = 'WB'.$yy.str_repeat("0", $num_of_zero).$num_of_next; // order番号
    $created_at = date_i18n('Y-m-d H:i:s');
    // マイル割引後金額
    $discounted_price = $sum_total + $fee - $use_point;

    $delivery = array();
    // 送り先（契約者住所 or 別送住所）
    if ($user['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA || $user['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA || $user['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXD) {
        $delivery = $user['hp']; // ハッピー： Active のときハッピーアカウントの送り先情報を参照
    } elseif ($user['mbr_combine_stat'] === $MEM_COMBINE_STATUS_EXA) {
        $delivery = $user['ex']; // ハッピー： 退会中(登録なし) / エクセレント: Active のときエクセレントアカウントから送り先情報を参照
    }

    $address_1 = '';
    $address_2 = '';
    $zip = '';
    $tel = '';
    if ($delivery_address == $DELIVERY_ADDRESS_KE) {
        $address_1 = $delivery['ke_add1'];
        $address_2 = $delivery['ke_add2'];
        $zip = str_replace(array("〒", "-"), "", $delivery['ke_pcd']);
    } elseif ($delivery_address == $DELIVERY_ADDRESS_RE) {
        $address_1 = $delivery['re_add1'];
        $address_2 = $delivery['re_add2'];
        $zip = str_replace(array("〒", "-"), "", $delivery['re_pcd']);
    }

    // 発送先がデフォルトのときは、送り先の電話番号には商品お届け電話番号を表示
    if ($delivery_address == $user_syodlv) {
        $tel = $user_syodlv_tel;
    } else {
        // 発送先を変更したときは、送り先の電話番号にはその住所の携帯番号を設定。携帯番号がない場合は電話番号を設定
        if ($delivery_address == $DELIVERY_ADDRESS_KE) {
            if ($delivery['ke_mob']) {
                $tel = $delivery['ke_mob'];
            } else {
                $tel = $delivery['ke_tel'];
            }
        } elseif ($delivery_address == $DELIVERY_ADDRESS_RE) {
            if ($delivery['re_mob']) {
                $tel = $delivery['re_mob'];
            } else {
                $tel = $delivery['re_tel'];
            }
        }
    }

    // ordersテーブルへ保存
    $data =  [
        'order_code' => $order_code,
        'user_id' => $user_id,
        'status' => 0,  // 0: 受付
        'sum_total' => $discounted_price,
        'time_type' =>  $delivery_request_time,
        'zip' =>  $zip,
        'address_1' => $address_1,
        'address_2' => $address_2,
        'syodlv' => $delivery_address,
        'created_at' => $created_at,
        ];
    if ($wpdb->insert('orders', $data) === false) {
        $abort_order('注文を保存できませんでした。注文は送信されていません。');
    }
    $order_id = intval($wpdb->insert_id);
    if ($order_id <= 0) {
        $abort_order('注文番号を発行できませんでした。注文は送信されていません。');
    }

    // order_productテーブルへ保存
    $placeholders = [];
    $args = [];
    foreach ($order_items as $order_item) {
        $cart = $order_item['cart'];
        $product = $order_item['product'];
        $product_code = (string) $cart->product_code;
        $regular_price = intval(str_replace(',', '', $product['regular_price']));
        $quantity = intval($cart->quantity);
        $purchase_price = intval(isset($_SESSION['product'][$product_code]) ? $_SESSION['product'][$product_code] : 0);
        $subtotal = intval($purchase_price * $quantity);
        // use empty string for updated_at to avoid SQL literal 'null' string
        $updated_at_val = ''; // keep as string (will be inserted as '')
        // placeholder: order_id(int), product_code(string), quantity(int), purchase_price(int), subtotal(int), regular_price(int), created_at(string), updated_at(string)
        $placeholders[] = "(%d, %s, %d, %d, %d, %d, %s, %s)";
        array_push($args, $order_id, $product_code, $quantity, $purchase_price, $subtotal, $regular_price, $created_at, $updated_at_val);
    }

    if ($use_point) {
        // 3200 is a pseudo-product code for mile usage, keep as string
        $placeholders[] = "(%d, %s, %d, %d, %d, %d, %s, %s)";
        array_push($args, $order_id, '3200', -1, -intval($use_point), -intval($use_point), 0, $created_at, '');
    }
    if ($fee > 0) {
        $placeholders[] = "(%d, %s, %d, %d, %d, %d, %s, %s)";
        array_push($args, $order_id, '4033', 1, intval($fee), intval($fee), 0, $created_at, '');
    }

    if (!empty($placeholders)) {
        $values_sql = implode(',', $placeholders);
        $sql = "INSERT INTO order_products (order_id, product_code, quantity, purchase_price, subtotal, regular_price, `created_at`,`updated_at`) VALUES {$values_sql}";
        // build prepare args array: first element is the query then all args
        $prepare_args = array_merge([$sql], $args);
        // call prepare with dynamic args
        $prepared = call_user_func_array([$wpdb, 'prepare'], $prepare_args);
        if ($wpdb->query($prepared) === false) {
            $abort_order('注文明細を保存できませんでした。注文は送信されていません。');
        }
    }

    // milesテーブルを更新
    if ($use_point) {
        // $remain_mile =  $user['ex']['new_m_point'] - $use_point;
        $res_mile = $wpdb->get_results($wpdb->prepare("SELECT * FROM miles WHERE user_id = %d FOR UPDATE", $user_id));
        if ($res_mile === null && $wpdb->last_error) {
            $abort_order('マイル情報を確認できませんでした。注文は送信されていません。');
        }

        if ($res_mile) {
            $mile_id = $res_mile[0] -> id;
            $use_mile_total = $res_mile[0] -> use_mile_total;
            $new_use_mile_total = $use_mile_total + $use_point;

            $data =  [
                'use_mile_total' => $new_use_mile_total,
                'updated_at' => date_i18n('Y-m-d H:i:s'),
                ];
            $id = ['id' => (int)$mile_id];
            if ($wpdb->update('miles', $data, $id) === false) {
                $abort_order('マイル情報を更新できませんでした。注文は送信されていません。');
            }
        }

        if (!$res_mile) {
            $data =  [
                'user_id' => $user_id,
                'use_mile_total' => $use_point,
                'created_at' => date_i18n('Y-m-d H:i:s'),
                ];
            if ($wpdb->insert('miles', $data) === false) {
                $abort_order('マイル情報を保存できませんでした。注文は送信されていません。');
            }
            $mile_id = intval($wpdb->insert_id);

            $new_use_mile_total = $use_point;
        }

        // mile_historiesテーブルを更新
        $data =  [
            'mile_id' => $mile_id,
            'use_mile_total_history' => $new_use_mile_total,
            'created_at' => date_i18n('Y-m-d H:i:s'),
            ];
        if ($wpdb->insert('mile_histories', $data) === false) {
            $abort_order('マイル履歴を保存できませんでした。注文は送信されていません。');
        }
    }



    // cartテーブルの購入済みの商品を削除
    if (!empty($cart_ids)) {
        $delete_placeholders = implode(',', array_fill(0, count($cart_ids), '%d'));
        $delete_sql = "DELETE FROM carts WHERE user_id = %d AND id IN ({$delete_placeholders})";
        $delete_args = array_merge([$delete_sql, $user_id], array_map('intval', $cart_ids));
        $prepared_delete = call_user_func_array([$wpdb, 'prepare'], $delete_args);
        $deleted_count = $wpdb->query($prepared_delete);
        if ($deleted_count === false || intval($deleted_count) !== count($cart_ids)) {
            $abort_order('カートの確定処理に失敗しました。注文は送信されていません。');
        }
    }

    if ($wpdb->query('COMMIT') === false) {
        $abort_order('注文を確定できませんでした。注文は送信されていません。');
    }

    // メール本文作成
    $email_content['happy_id'] = '-';
    $email_content['pos'] = '-';
    $email_content['mbr_grd'] = '-';
    $email_content['excellent_id'] = '-';
    if ($user['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA || $user['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA ||$user['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXD) {
        $email_content['happy_id'] = $user['hp']['mbr_id'];
        $email_content['pos'] = $user['hp']['pos'];
        $email_content['mbr_grd'] = $user['hp']['mbr_grd'];
    }
    if ($user['mbr_combine_stat'] !== $MEM_COMBINE_STATUS_HPA) {
        $email_content['excellent_id'] = $user['ex']['mbr_id'];
    }
    $email_content['method_of_payment'] = 'コレクト';
    if ($discounted_price === 0) {
        $email_content['method_of_payment'] = '元払い';
    }
    $order_product_list_text = implode($order_product_list);
    $email_content['order_code'] = $order_code;
    $email_content['login_id'] = $user['login_id'];
    $email_content['name'] = $user_name;
    $email_content['email'] = $user['email'];
    $email_content['email'] = $user['email'];
    $email_content['sum_total'] = number_format($sum_total);
    $email_content['use_point'] = $use_point;
    $email_content['discounted_price'] = number_format($discounted_price);
    $email_content['delivery_request_time'] = $delivery_request_time;
    $email_content['order_day'] = $created_at;
    $email_content['zip'] = $zip;
    $email_content['address_1'] = $address_1;
    $email_content['address_2'] = $address_2;
    $email_content['syodlv_tel'] = $tel;

    $email_content['co_nm'] = '-';
    $email_content['mbr_kata'] = '-';
    $email_content['syodlv'] = $delivery_address;
    if ($delivery_address == $DELIVERY_ADDRESS_KE) {
        if ($user['co_nm']) {
            $email_content['co_nm'] = $user['co_nm'];
        }
        if ($user['mbr_kata']) {
            $email_content['mbr_kata'] = $user['mbr_kata'];
        }
    }
    $user_text = make_order_email_to_user($order_product_list_text, $email_content);
    $admin_text = make_order_email_to_admin($order_product_list_text, $email_content);

    // $wiz_mail = ['nishioka-tsubasa@wiznet.co.jp', 'yoshizaki-airi@wiznet.co.jp', $user_email]; // ウイズ確認用
    $header = 'From: ハッピーファミリーWEB会員サイト <weborder@happyfamily.co.jp>' . "\r\n";
    $subject = '【ハッピーファミリー】注文確認メール';
    $success = wp_mail($user_email, $subject, $user_text, $header);
    // $success = wp_mail($wiz_mail, $subject, $user_text, $header); // ウイズ確認用
    $admin_mail = 'weborder@happyfamily.co.jp'; //weborder@happyfamily.co.jp
    if ($_SERVER["HTTP_HOST"] ==='happyfamily-members.3d-showcase.net') {
        $admin_mail = ['nishioka-tsubasa@wiznet.co.jp', 'yoshioka-yuko@wiznet.co.jp']; // ウイズ確認用
        // $admin_mail = ['weborder@happyfamily.co.jp', 'nishioka-tsubasa@wiznet.co.jp', 'yoshioka-yuko@wiznet.co.jp']; // ハッピー様確認用
    }

    $admin_headers = 'From: WEB会員サイト  <weborder@happyfamily.co.jp>' . "\r\n";
    $admin_subject = '【注文依頼】WEB会員サイト';
    $success = wp_mail($admin_mail, $admin_subject, $admin_text, $admin_headers);

    if ($success) {
        $result['code'] = 0;
        if ($use_point) {
            $_SESSION['user']['ex']['new_m_point']  = $_SESSION['user']['ex']['new_m_point'] - $use_point;
        }
        echo json_encode($result);
        wp_die();
    }
    $result['code'] = 1;
    echo json_encode($result);
    wp_die();
}
add_action('wp_ajax_send_order_confirmation_mail', 'send_order_confirmation_mail');
add_action('wp_ajax_nopriv_send_order_confirmation_mail', 'send_order_confirmation_mail');
add_filter('wp_mail_content_type', 'wpdocs_set_html_mail_content_type');

/*-------------------------------------------*/
/*  商品コードから詳細情報取得
/*-------------------------------------------*/
function show_product($code, $require_published = true)
{
    global $wpdb;

    // normalize_product_code がファイル内に定義済みであることを前提とする
    $norm = normalize_product_code($code);
    // 候補順：入力そのまま（raw）、先頭ゼロ削除版（no_leading_zeros）
    $candidates = [];
    if (isset($norm['raw']) && $norm['raw'] !== '') {
        $candidates[] = $norm['raw'];
    }
    if (isset($norm['no_leading_zeros']) && $norm['no_leading_zeros'] !== $norm['raw']) {
        $candidates[] = $norm['no_leading_zeros'];
    }

    // 最終的に見つかった post_id（0 = not found）
    $found_post_id = 0;

    // まずは公開(post_status = 'publish')のみで候補を順に探す（require_published が true の場合）
    if ($require_published) {
        $query = "
            SELECT p.ID as post_id
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID
            WHERE pm.meta_key = %s
              AND pm.meta_value = %s
              AND p.post_status = 'publish'
            ORDER BY p.ID DESC
            LIMIT 1
        ";
        foreach ($candidates as $cand) {
            $row = $wpdb->get_row($wpdb->prepare($query, 'product_code', $cand));
            if ($row && isset($row->post_id) && intval($row->post_id) > 0) {
                $found_post_id = intval($row->post_id);
                break;
            }
        }

        // 見つかれば取得して返す（公開のみを優先）
        if ($found_post_id > 0) {
            $post = get_post($found_post_id);
            return [
                'post_id' => $found_post_id,
                'title' => $post ? $post->post_title : '(タイトル取得不可)',
                'regular_price' => get_post_meta($found_post_id, 'regular_price', true) ?: 0,
                'terms' => get_the_terms($found_post_id, 'product') ?: [],
            ];
        }

        // 公開で見つからなければ「未検出」として返す（require_published=true の契約通り）
        return [
            'post_id' => 0,
            'title' => '存在しない商品',
            'regular_price' => 0,
            'terms' => [],
        ];
    }

    // require_published が false の場合（既存挙動を尊重）：公開/非公開問わず検索
    $query_any = "
        SELECT pm.post_id as post_id
        FROM {$wpdb->postmeta} pm
        WHERE pm.meta_key = %s
          AND pm.meta_value = %s
        ORDER BY pm.post_id DESC
        LIMIT 1
    ";
    foreach ($candidates as $cand) {
        $row = $wpdb->get_row($wpdb->prepare($query_any, 'product_code', $cand));
        if ($row && isset($row->post_id) && intval($row->post_id) > 0) {
            $found_post_id = intval($row->post_id);
            break;
        }
    }

    if (!$found_post_id) {
        return [
            'post_id' => 0,
            'title' => '存在しない商品',
            'regular_price' => 0,
            'terms' => [],
        ];
    }

    $post = get_post($found_post_id);

    return [
        'post_id' => $found_post_id,
        'title' => $post ? $post->post_title : '(タイトル取得不可)',
        'regular_price' => get_post_meta($found_post_id, 'regular_price', true) ?: 0,
        'terms' => get_the_terms($found_post_id, 'product') ?: [],
    ];
}

/********************************************** 注文履歴 ***********************************************/
/*-------------------------------------------*/
/*  注文履歴取得
/*-------------------------------------------*/
function fetch_orders()
{
    global $wpdb;
    $user_id = wp_get_current_user()->ID;
    $data_orders = $wpdb->get_results("SELECT `orders`.id AS `id`, `orders`.`order_code`, `orders`.`user_id`, `orders`.`sum_total`, `orders`.`created_at`, `orders`.`updated_at`, `order_products`.`order_id`, `order_products`.`product_code`, `order_products`.`quantity`, `order_products`.`purchase_price`, `order_products`.`subtotal`, `order_products`.`regular_price` FROM `orders` LEFT OUTER JOIN `order_products` ON `orders`.id = `order_products`.order_id  WHERE `user_id`=$user_id ORDER BY `orders`.created_at DESC");
    $check = -1;

    $order = [];
    $orders = [];
    foreach ($data_orders as $key => $data) {
        if ($check !== $data->id) {
            if ($key !== 0) {
                $orders[$check] = $order;
                $order = [];
            }
            $order['id'] = $data->id;
            $order['order_code'] = $data->order_code;
            $order['user_id'] = $data->user_id;
            $order['sum_total'] = number_format($data->sum_total);
            $order['created_at'] = $data->created_at;
            $order['updated_at'] = $data->updated_at;
        }
        $product_code = $data->product_code;
        $detail = show_product($product_code, false);

        $trans_check = [];
        $transaction = '-'; 
        $cat_product = '-';

        $order_product['post_id'] = $detail['post_id'];
        $order_product['product_name'] = $detail['title'];
        $order_product['order_id'] = $data->order_id;
        $order_product['product_code'] = $data->product_code;
        $order_product['quantity'] = $data->quantity;
        $order_product['purchase_price'] = number_format($data->purchase_price);
        $order_product['subtotal'] = number_format($data->subtotal);
        $order_product['regular_price'] = number_format($data->regular_price);

        foreach ($detail['terms'] as $term) {
            // 取引区分
            if ($term->parent === 12) {
                $transaction = $term->name;   // 取引区分
                $trans_check[] = $term->name;
            // 商品分類
            } elseif ($term->parent === 15) {
                $cat_product = $term->name;
            }
            $order_product['slug'] = $term->slug;
        }

        if (count($trans_check) === 2) {
            $transaction = '共通';
        }

        $order_product['transaction'] = $transaction;
        $order_product['category'] = $cat_product;

        $order['order_products'][$key] = $order_product;
        $check = $data->id;
    }
    if ($check !== -1) {
        $orders[$check] = $order;
    }

    return $orders;
}
/*-------------------------------------------*/
/*  注文IDから購入商品一覧を取得
/*-------------------------------------------*/
function fetch_order_products($order_id)
{
    global $wpdb;
    $data_order_products = $wpdb->get_results("SELECT * FROM `order_products` WHERE `order_id` = $order_id");
    // $order_product_list = complete_product_list_for_order($data_order_products);

    $order_product_list = [];
    foreach ($data_order_products as $key => $data) {
        $detail = show_product($data->product_code, false);                   // 商品情報取得
        $status = check_status($data->order_id);  // 発送状況

        $product = []; // 初期化
        $transaction_check = [];
        $transaction = '-';
        $cat_product = '-';
        $product_type = 0;
        $cant_buy_mbr_type = 2;

        $product['status'] = $status;
        $product['post_id'] = $detail['post_id'];
        $product['product_name'] = $detail['title'];
        $product['order_id'] = $data->order_id;
        $product['product_code'] = $data->product_code ;
        $product['quantity'] = $data->quantity;
        $product['purchase_price'] = number_format($data->purchase_price);
        $product['subtotal'] = number_format($data->subtotal); // 自動で円に変換

        $terms = get_the_terms($product['post_id'], 'product');
        if ($terms && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                // 取引区分
                if ($term->parent === 12) {
                    $transaction = $term->name;   // 取引区分
                    $transaction_check[] = $term->name;
                // 商品分類
                } elseif ($term->parent === 15) {
                    $cat_product = $term->name;
                }
                // 購入権限判断
                if ($term->term_id === 13) {        // 13: ハッピー商品
                    $product_type = 0;              // 商品タイプ 0:ハッピー 1:エクセレント
                    $cant_buy_mbr_type = 1;         // 購入不可　 0:ハッピー 1:エクセレント 2:どちらも購入できる
                } elseif ($term->term_id === 14) {  // 14: エクセレント商品
                    $product_type = 1;
                    $cant_buy_mbr_type = 0;
                }
                $product['slug'] = $term->slug;
            }
        }
       if (is_array($transaction_check) && count($transaction_check) >= 2) {
            $transaction = '共通';
        }

        $product['transaction'] = $transaction;
        $product['category'] = $cat_product;
        $product['product_type'] = $product_type;
        $product['cant_buy_mbr_type'] = $cant_buy_mbr_type;

        if ($status) {
            $product['status'] = $status;
        }

        $order_product_list[$key] = $product;
    }

    return $order_product_list;
}
/*-------------------------------------------*/
/*  注文IDから購入商品一覧を取得
/*-------------------------------------------*/
function check_status($order_code)
{
    $res_status = tryCatch('API_GetWebOrderStat', [$order_code], false);
    // echo '<br>API：API_GetWebOrderStat<br>order_code：'.$order_code.'<br>戻り値<br>';
    // var_dump($res_status);

    $order_status = '受付中';
    if ($res_status->code === 0) {
        if ($res_status->syuka_st === 1) {
            $order_status = '未出荷';
        }
        if ($res_status->syuka_st === 2) {
            $order_status = '出荷ずみ';
        }
        if ($res_status->syuka_st === 9) {
            $order_status = '取り消し';
        }
    }
    return $order_status;
}

/********************************************** 金額パネル表示 ***********************************************/
/*-------------------------------------------*/
/*  AJAX：ご注文合計金額計算
/*-------------------------------------------*/
function calculate_sum_total()
{
    session_check();
    $use_point = $_GET['use_point'];
    $cart_list = fetch_cart_list_by_user_id();

    if (!$cart_list) {
        $result = ['product_sum_total' => 0, 'sum_quantity' => 0, 'fee' => 0, 'mile_point' => 0, 'order_sum_total' => 0];
        echo json_encode($result);
        wp_die();
        return;
    }

    // 各商品の個数、合計点数
    $sum_quantity = 0;
    $product_list = [];
    foreach ($cart_list as $cart) {
        $detail_check = show_product($cart->product_code);
        if ($detail_check['post_id'] === 0) continue;
        $sum_quantity += $cart->quantity;                      // 合計商品点数
        $product_list[$cart->product_code] = $cart->quantity;  // [商品コード => 個数, 商品コード => 個数, 商品コード => 個数...]
    }

    //　合計(出荷事務手数料別)
    $product_sum_total = 0;
    foreach ($product_list as $code => $quantity) {
        $discount_price = $_SESSION['product'][$code];
        $subtotal = $discount_price * $quantity;
        $product_sum_total += $subtotal;
    }

    // 出荷事務手数料の有無
    $fee = 0;
    $no_fee_purchase_piice = getSettingPriceForFee();
    if ($product_sum_total > 0 && $product_sum_total < $no_fee_purchase_piice) {
        $fee = getFee();
    }

    // マイル割引後の価格
    if ($use_point) {
        $discounted_price = $product_sum_total - $use_point + $fee;
        $result = ['product_sum_total' => $product_sum_total, 'sum_quantity' => $sum_quantity, 'fee' => $fee, 'mile_point' => $use_point, 'order_sum_total' => $discounted_price];
        echo json_encode($result);
        wp_die();
        return;
    }

    // マイル割引ない場合のご注文合計（総計）
    $order_sum_total = $product_sum_total + $fee;
    $result =  ['product_sum_total' => $product_sum_total, 'sum_quantity' => $sum_quantity, 'fee' => $fee, 'mile_point' => 0, 'order_sum_total' => $order_sum_total];
    echo json_encode($result);
    wp_die();
    return;
}
add_action('wp_ajax_calculate_sum_total', 'calculate_sum_total');
add_action('wp_ajax_nopriv_calculate_sum_total', 'calculate_sum_total');

/********************************************** 組織図 ***********************************************/
/*-------------------------------------------*/
/*  AJAX：組織図(紹介者) hp
/*-------------------------------------------*/
function get_introducer_hp()
{
    $axis_id = $_POST['hp_id'];
    $get_num = $_POST['get_num'];
    $res_introducer_hp = tryCatch('API_GetMemberShip_hp', [$axis_id, $get_num], false);
    $digit = '%06d'; // 会員IDの桁を6桁に揃える

    $data = [];
    foreach ($res_introducer_hp->result as $i_hp) {
        $d = [];
        $d['c_id_br'] = sprintf($digit, $i_hp->c_id);
        $d['c_id'] = sprintf($digit, $i_hp->c_id);
        $d['c_nm'] = $i_hp->c_nm;
        $d['c_knm'] = $i_hp->c_knm;
        $d['c_stat'] = $i_hp->c_stat;
        $d['p_id_br'] = sprintf($digit, $i_hp->p_id);
        $d['p_id'] = sprintf($digit, $i_hp->p_id);
        $d['next'] = $i_hp->next;

        $data[$d['p_id']][] = $d;
    }

    // 第二階層：基軸のidを基に二階層目のデータを入れる
    // [axis_id = ○, mbr_type = 'hp', directory = [[c_id = 〇, c_nm = 〇], [c_id = 〇, c_nm = 〇]]]
    $tree['axis_id'] = $axis_id;
    $tree['chart_type'] = 'hp';
    $tree['directory'] = [];
    $directory_ids = [];
    foreach ($data[$axis_id] as $d) {
        $tree['directory'][] = $d;
        $directory_ids[] = $d['c_id'];
    }

    if (!$directory_ids) {
        echo json_encode($tree);
        wp_die();
        return;
    }

    // 第三階層：二階層目の親のidを基にデータを二階層目に入れる。
    foreach ($tree['directory'] as &$directory) {
        if ($data[$directory['c_id']]) {
            $directory['directory'] = $data[$directory['c_id']];
        }
    }
    echo json_encode($tree);
    wp_die();
    return;
}
add_action('wp_ajax_get_introducer_hp', 'get_introducer_hp');
add_action('wp_ajax_nopriv_get_introducer_hp', 'get_introducer_hp');

/*-------------------------------------------*/
/* AJAX： 組織図(紹介者) ex
/*-------------------------------------------*/
function get_introducer_ex()
{
    $mbr_id = $_POST['ex_id'];
    $get_num = $_POST['get_num'];
    if ($_POST['br_id']) {
        $br_id = $_POST['br_id'];
    } else {
        $br_id = '001';
    }
    $axis_id = $mbr_id.$br_id;

    $res_introducer_ex = tryCatch('API_GetMemberShip_ex', [$mbr_id, $br_id, $get_num], false);

    $data = [];
    foreach ($res_introducer_ex->result as $i_ex) {
        $d = [];
        $d['c_id_br'] = $i_ex->c_id.$i_ex->c_br;
        $d['c_id'] = $i_ex->c_id;
        $d['c_br'] = $i_ex->c_br;
        $d['c_nm'] = $i_ex->c_nm;
        $d['c_knm'] = $i_ex->c_knm;
        $d['c_stat'] = $i_ex->c_stat;
        $d['p_id_br'] = $i_ex->p_id.$i_ex->p_br;
        $d['p_id'] = $i_ex->p_id;
        $d['p_br'] = $i_ex->p_br;
        $d['next'] = $i_ex->next;

        $data[$i_ex->p_id.$i_ex->p_br][] = $d;
    }

    // 第二階層：基軸のidを基に二階層目のデータを入れる
    // [axis_id = ○, mbr_type = 'ex', directory = [[c_id = 〇, c_nm = 〇], [c_id = 〇, c_nm = 〇]]]
    $tree['axis_id'] = $axis_id;
    $tree['chart_type'] = 'ex';
    $tree['directory'] = [];
    $directory_ids = [];
    foreach ($data[$axis_id] as $d) {
        $tree['directory'][] = $d;
        $directory_ids[] = $d['c_id_br'];
    }

    if (!$directory_ids) {
        echo json_encode($tree);
        wp_die();
        return;
    }

    // 第三階層：二階層目の親のidを基にデータを二階層目に入れる。
    foreach ($tree['directory'] as &$directory) {
        if ($data[$directory['c_id_br']]) {
            $directory['directory'] = $data[$directory['c_id_br']];
        }
    }
    echo json_encode($tree);
    wp_die();
    return;
}
add_action('wp_ajax_get_introducer_ex', 'get_introducer_ex');
add_action('wp_ajax_nopriv_get_introducer_ex', 'get_introducer_ex');
/*-------------------------------------------*/
/*  AJAX：組織図(バイナリ) binary
/*-------------------------------------------*/
function get_binary_ex()
{
    $mbr_id = $_GET['ex_id'];
    $br_id = '001';
    if ($_GET['br_id']) {
        $br_id = $_GET['br_id'];
    }
    $axis_id = $mbr_id.$br_id;

    $res_binary_ex = tryCatch('API_GetMemberShip_bi', [$mbr_id, $br_id, 3], false);
    // $res_binary_ex = tryCatch('API_GetMemberShip_bi', ['000182', '001'], false);
    // echo json_encode($res_binary_ex);
    // wp_die();
    // return;

    $data = [];
    foreach ($res_binary_ex->result as $i_ex) {
        $d = [];
        $d['c_id_br'] = $i_ex->c_id.$i_ex->c_br;
        $d['c_id'] = $i_ex->c_id;
        $d['c_nm'] = $i_ex->c_nm;
        $d['c_knm'] = $i_ex->c_knm;
        $d['c_br'] = $i_ex->c_br;
        $d['c_stat'] = $i_ex->c_stat;
        $d['c_lr'] = $i_ex->c_lr;
        $d['p_id_br'] = $i_ex->p_id.$i_ex->p_br;
        $d['p_id'] = $i_ex->p_id;
        $d['p_br'] = $i_ex->p_br;
        $d['next_l'] = $i_ex->next_l;
        $d['next_r'] = $i_ex->next_r;

        $data[$i_ex->p_id.$i_ex->p_br][] = $d;
    }

    // 第二階層：基軸のidを基に$treeへ二階層目のデータを入れる
    // [axis_id = ○, chart_type = 'ex-bi', directory = [[c_id = 〇, c_nm = 〇], [c_id = 〇, c_nm = 〇]]]
    $tree['axis_id'] = $axis_id;
    $tree['chart_type'] = 'ex-bi';
    $tree['directory'] = $data[$axis_id];

    // 第三階層：二階層目[directory]を回して、$data[親ID]の親IDと一致するIDの配列が存在していれば、一致する配列の[directory]にデータを入れる。
    foreach ($tree['directory'] as &$directory) {
        if ($data[$directory['c_id_br']]) {
            $directory['directory'] = $data[$directory['c_id_br']];
            $exist_child = true;
        }
    }

    // 第三階層が存在しなければここまでの値を返す。
    if (!$exist_child) {
        echo json_encode($tree);
        wp_die();
        return;
    }

    // 第四階層：データ
    for ($i = 0; $i < 2; $i++) {
        foreach ($tree['directory'][$i]['directory'] as &$directory) {
            if ($data[$directory['c_id_br']]) {
                $directory['directory'] = $data[$directory['c_id_br']];
            }
        }
    }
    echo json_encode($tree);
    wp_die();
    return;
}
add_action('wp_ajax_get_binary_ex', 'get_binary_ex');
add_action('wp_ajax_nopriv_get_binary_ex', 'get_binary_ex');

/*-------------------------------------------*/
/*  AJAX：会員情報詳細取得 組織図のモーダル表示
/*-------------------------------------------*/
function get_member_detail()
{
    $mbr_id = $_POST['mbr_id'];
    $member_type = $_POST['member_type'];
    // $mbr_br = '001';
    if (isset($_POST['my_branch'])) {
        $my_branch = $_POST['my_branch'];
    }
    if ($member_type === '0') {
        $res = tryCatch('API_GetMemberDetail_hp', [$mbr_id], false);
        $res->type = 0;
    } elseif ($member_type === '1') {
        $res = tryCatch('API_GetMemberDetail_ex', [$mbr_id, $my_branch], false);
        $res->type = 1;
    }

    echo json_encode($res);
    wp_die();
    return;
}
add_action('wp_ajax_get_member_detail', 'get_member_detail');
add_action('wp_ajax_nopriv_get_member_detail', 'get_member_detail');

/********************************************** バリデーション ***********************************************/
/*-------------------------------------------*/
/*  新規WEB会員登録　WordPress バリデ
/*-------------------------------------------*/
function validation_wp_user($data)
{
    global $wpdb;
    global $VALIDATION_TYPE_SIGNUP;
    global $VALIDATION_TYPE_EDIT;
    global $VALIDATION_TYPE_REISSUE_PASS;

    $error = [];

    // 変数設定 =========
    // 新規WEB会員登録 パスワード再発行：
    if (intval($data['validation_type']) === $VALIDATION_TYPE_SIGNUP || intval($data['validation_type']) === $VALIDATION_TYPE_REISSUE_PASS) {
        if (isset($data['login_id'])) {
            $login_id = $data['login_id'];
            $user_login_exist = $wpdb->get_results("SELECT `user_login` FROM `wp_users` WHERE `user_login` = '$login_id'");
        }
    }

    // 新規WEB会員登録 WEB会員情報更新：
    if (intval($data['validation_type']) === $VALIDATION_TYPE_SIGNUP || intval($data['validation_type']) === $VALIDATION_TYPE_EDIT) {
        $happy_id_exist = [];
        $excellent_id_exist = [];
        if ($data['happy_id']) {
            $happy_id_input = $data['happy_id'];
            $happy_id_exist = $wpdb->get_results("SELECT `meta_value` FROM wp_usermeta WHERE `meta_key` = 'happy_id' AND `meta_value` = '$happy_id_input'");
        }
        if ($data['excellent_id']) {
            $excellent_id_input = $data['excellent_id'];
            $excellent_id_exist = $wpdb->get_results("SELECT `meta_value` FROM wp_usermeta WHERE `meta_key` = 'excellent_id' AND `meta_value` = '$excellent_id_input'");
        }
    }

    // WEB会員情報更新：
    if (intval($data['validation_type']) === $VALIDATION_TYPE_EDIT) {
        $user = wp_get_current_user();
        $user_id = $user->ID;
        $happy_id_current = get_user_meta($user_id, 'happy_id', true);
        $excellent_id_current = get_user_meta($user_id, 'excellent_id', true);
    }
    // =================

    // 新規WEB会員登録：
    if (intval($data['validation_type']) === $VALIDATION_TYPE_SIGNUP) {
        // ログインIDバリデーション
        if (empty($login_id)) {
            $error['login_id'] = '必須項目は必ずご入力ください。';
        } else {
            if (!preg_match("<^[a-z0-9]{6,30}+$>", $data["login_id"])) {
                $error['login_id'] = '半角英数字(英語は小文字のみ利用可能)を含む6文字~30文字でご入力ください。';
            } elseif ($user_login_exist) {
                $error['login_id'] = 'すでに登録済のログインIDのため使用できません。';
            }
        }

        // パスワードバリデーション <^[a-z0-9!-/:-@¥[-`{-~]{8,16}+$>
        if (empty($data['pass'])) {
            $error['pass'] = '必須項目は必ずご入力ください。';
        } else {
            if (!preg_match("<^[a-z0-9!-/:-@¥[-`{-~]{8,16}+$>", $data['pass'])) {
                $error['pass'] = '半角英数字記号(英語は小文字のみ利用可能)を含む8文字~16文字でご入力ください。';
            }
            if ($data['pass'] !== $data['confirmation_pass']) {
                $error['confirmation_pass'] = '確認用のパスワードが異なります。';
            }
        }

        // ハッピー会員、エクセレント会員バリデーション
        if (empty($data['happy_id']) && empty($data['excellent_id'])) {
            $error['error_both'] = 'ハッピー会員IDまたはエクセレント会員IDのどちらかを必ずご入力ください。';
        } else {
            if ($data['happy_id']) {
                if (!preg_match('/^([0-9]{6})$/', $data['happy_id'])) {
                    $error['happy_id'] = '半角数字6桁でご入力ください。';
                } elseif ($happy_id_exist) {
                    $error['happy_id'] = 'すでに登録済の会員IDのため使用できません。';
                }
            }
            if ($data['excellent_id']) {
                if (!preg_match('/^([0-9]{6})$/', $data['excellent_id'])) {
                    $error['excellent_id'] = '半角数字6桁でご入力ください。';
                } elseif ($excellent_id_exist) {
                    $error['excellent_id'] = 'すでに登録済の会員IDのため使用できません。';
                }
            }
        }

        // プライバシーポリシーへの同意バリデーション
        if (empty($data['agree'])) {
            $error['agree'] = 'プライバシーポリシーへの同意にチェックを入れてください。';
        }
    }

    // パスワード再発行：
    if (intval($data['validation_type']) === $VALIDATION_TYPE_REISSUE_PASS) {
        // ログインIDバリデーション
        if (empty($login_id)) {
            $error['login_id'] = '必須項目は必ずご入力ください。';
        } else {
            if (!preg_match("<^[a-z0-9]{6,30}+$>", $data["login_id"])) {
                $error['login_id'] = '半角英数字(英語は小文字のみ利用可能)を含む6文字~30文字でご入力ください。';
            } elseif (!$user_login_exist) {
                $error['login_id'] = '存在しないログインIDです。';
            }
        }
    }

    // WEB会員情報更新：
    if (intval($data['validation_type']) === $VALIDATION_TYPE_EDIT) {
        // パスワード
        if ($data['pass']) {
            if (!preg_match("<^[a-z0-9!-/:-@¥[-`{-~]{8,16}+$>", $data['pass'])) {
                $error['pass'] = '半角英数字記号(英語は小文字のみ利用可能)を含む8文字~16文字でご入力ください。';
            }
        }
        if ($data['confirmation_pass']) {
            if ($data['pass'] !== $data['confirmation_pass']) {
                $error['confirmation_pass'] = '確認用のパスワードが異なります。';
            }
        }

        // ハッピー会員、エクセレント会員バリデーション
        if (empty($data['happy_id']) && empty($data['excellent_id'])) {
            $error['error_both'] = 'ハッピー会員IDまたはエクセレント会員IDのどちらかを必ずご入力ください。';
        } else {
            if ($data['happy_id']) {
                if (!preg_match('/^([0-9]{6})$/', $data['happy_id'])) {
                    $error['happy_id'] = '半角数字6桁でご入力ください。';
                } elseif ($happy_id_exist) {
                    if ($happy_id_exist[0]->meta_value !== $happy_id_current) {
                        $error['happy_id'] = 'すでに登録済の会員IDのため使用できません。';
                    }
                }
            }
            if ($data['excellent_id']) {
                if (!preg_match('/^([0-9]{6})$/', $data['excellent_id'])) {
                    $error['excellent_id'] = '半角数字6桁でご入力ください。';
                } elseif ($excellent_id_exist) {
                    if ($excellent_id_exist[0]->meta_value !== $excellent_id_current) {
                        $error['excellent_id'] = 'すでに登録済の会員IDのため使用できません。';
                    }
                }
            }
        }
    }

    // 新規WEB会員登録 パスワード再発行：
    if (intval($data['validation_type']) === $VALIDATION_TYPE_SIGNUP || intval($data['validation_type']) === $VALIDATION_TYPE_REISSUE_PASS) {
        // 漢字氏名バリデーション
        if (empty($data['mbr_nm'])) {
            $error['mbr_nm'] = '必須項目は必ずご入力ください。';
        }
        // 半角カナ氏名バリデーション
        if (empty($data['mbr_knm'])) {
            $error['mbr_knm'] = '必須項目は必ずご入力ください。';
        } else {
            if (!preg_match("/\A[ｦ-ﾟァ-ヴー 　]+\z/u", $data['mbr_knm'])) {
                $error['mbr_knm'] = 'カタカナでご入力ください。';
            }
        }
        // 生年月日バリデーション
        if (empty($data['year_birth']) || empty($data['month_birth']) || empty($data['day_birth'])) {
            $error['mbr_bth'] = '必須項目は必ずご入力ください。';
        } else {
            if (!preg_match("/\A[0-9]{4,4}\z/", $data['year_birth'])) {
                $error['mbr_bth'] = '誕生日年は半角数字でご入力ください。';
            }
        }
    }

    // 全タイプ共通バリデーション
    // メールバリデーション
    if (empty($data['mail'])) {
        $error['mail'] = '必須項目は必ずご入力ください。';
    } else {
        if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD", $data['mail'])) {
            $error['mail'] = 'メールアドレスの形式が違います。';
        }
    }
    // メール(確認用)バリデーション
    if (empty($data['confirmation_mail'])) {
        $error['confirmation_mail'] = '必須項目は必ずご入力ください。';
    } else {
        if ($data['confirmation_mail'] !== $data['mail']) {
            $error['confirmation_mail'] = '確認用メールアドレスが異なります。';
        }
    }

    return $error;
}


/*-------------------------------------------*/
/*  Api バリデーション
/*-------------------------------------------*/
// API_CheckMember
function validation_api($data)
{
    global $wpdb;
    global $VALIDATION_TYPE_SIGNUP;
    global $VALIDATION_TYPE_EDIT;
    global $VALIDATION_TYPE_REISSUE_PASS;

    global $MEM_STATUS_WITHDRAWAL;

    // 全角カナを半角カナへ変更
    $mbr_knm = mb_convert_kana($data['mbr_knm'], "k");

    // 新規会員登録、パスワード再設定、管理画面のWEB会員情報照会: ハッピー、エクセレント同じ名前を採用
    $mbr_nm_hp = str_replace(array(" ", "　"), "", $data['mbr_nm']);
    $mbr_knm_hp = str_replace(array(" ", "　"), "", $mbr_knm);
    $mbr_nm_ex = str_replace(array(" ", "　"), "", $data['mbr_nm']);
    $mbr_knm_ex = str_replace(array(" ", "　"), "", $mbr_knm);

    // WEB会員情報変更: エクセレント、ハッピー個別で名前があるときはそっち採用、なければ共通の名前。
    // 結婚などの氏名変更でハッピーとエクセレントで名前が異なる場合がある。
    if (isset($data['validation_type'])) {
        if (intval($data['validation_type']) === $VALIDATION_TYPE_EDIT) {
            if (isset($data['hp']['mbr_nm'])) {
                $mbr_hp_knm = mb_convert_kana($data['hp']['mbr_knm'], "k");
                $mbr_nm_hp = str_replace(array(" ", "　"), "", $data['hp']['mbr_nm']);
                $mbr_knm_hp = str_replace(array(" ", "　"), "", $mbr_hp_knm);
            }
            if (isset($data['ex']['mbr_nm'])) {
                $mbr_ex_knm = mb_convert_kana($data['ex']['mbr_knm'], "k");
                $mbr_nm_ex = str_replace(array(" ", "　"), "", $data['ex']['mbr_nm']);
                $mbr_knm_ex = str_replace(array(" ", "　"), "", $mbr_ex_knm);
            }
        }
    }

    $error =[];

    if ($data['happy_id']) {
        $res_hp = tryCatch('API_CheckMember', [0, $data['happy_id'], (int)$data['mbr_bth'], $mbr_nm_hp, $mbr_knm_hp], false);
        // var_dump('ハッピーAPI返り値');
        // $res_hp = tryCatch('API_CheckMember', [0, "000002", 20000101, "2_氏名", "2_ｼﾒｲｶﾅ"], false);
        // var_dump($res_hp);
        if ($res_hp->code === 0) {
            if ($res_hp->mbr_bth_ans === 1) {
                $error['mbr_bth'] = '生年月日入力内容が正しくありません。ご登録内容と齟齬のないよう再度ご確認くださいませ。';
            }
            if ($res_hp->mbr_nm_ans === 1) {
                $error['mbr_nm'] = '氏名の入力内容が正しくありません。ご登録内容と齟齬のないよう再度ご確認くださいませ。';
            }
            if ($res_hp->mbr_knm_ans === 1) {
                $error['mbr_knm'] = 'フリガナの入力内容が正しくありません。ご登録内容と齟齬のないよう再度ご確認くださいませ。';
            }
            if ($res_hp->mbr_knm_ans === 1 || $res_hp->mbr_nm_ans === 1 || $res_hp->mbr_bth_ans === 1) {
                $error['registration_info_hp'] = 'ご入力いただいた会員IDと登録されている個人情報が一致いたしません。';
            }
            if (intval($data['validation_type']) === $VALIDATION_TYPE_SIGNUP || intval($data['validation_type']) === $VALIDATION_TYPE_EDIT) {
                if ($res_hp->mbr_stat === $MEM_STATUS_WITHDRAWAL) {
                    $error['happy_id'] = '退会中の会員IDはご登録いただけません。';
                } elseif ($res_hp->mbr_syu !== 1) {
                    $error['happy_id'] = 'メンバー会員以外はWEB会員登録は行えません。';
                }
            }
        } else {
            $error['happy_id'] = '会員IDが存在しておりません。管理者にお問い合わせください。';
        }
    }

    if ($data['excellent_id']) {
        $res_ex = tryCatch('API_CheckMember', [1, $data['excellent_id'], (int)$data['mbr_bth'], $mbr_nm_ex, $mbr_knm_ex], false);
        // var_dump('エクセレントAPI返り値');
        // $res_ex = tryCatch('API_CheckMember', [1, "000003", 19480622, "三谷　千鶴子", "ｻﾝﾀﾆ ﾁﾂﾞｺ"], false);
        // var_dump($res_ex);
        if ($res_ex->code === 0) {
            if ($res_ex->mbr_bth_ans === 1) {
                $error['mbr_bth'] = '生年月日入力内容が正しくありません。ご登録内容と齟齬のないよう再度ご確認くださいませ。';
            }
            if ($res_ex->mbr_nm_ans === 1) {
                $error['mbr_nm'] = '氏名の入力内容が正しくありません。ご登録内容と齟齬のないよう再度ご確認くださいませ。';
            }
            if ($res_ex->mbr_knm_ans === 1) {
                $error['mbr_knm'] = 'フリガナの入力内容が正しくありません。ご登録内容と齟齬のないよう再度ご確認くださいませ。';
            }
            if ($res_ex->mbr_knm_ans === 1 || $res_ex->mbr_nm_ans === 1 || $res_ex->mbr_bth_ans === 1) {
                $error['registration_info_ex'] = 'ご入力いただいた会員IDと登録されている個人情報が一致いたしません。';
            }
            if (intval($data['validation_type']) === $VALIDATION_TYPE_SIGNUP || intval($data['validation_type']) === $VALIDATION_TYPE_EDIT) {
                if ($res_ex->mbr_stat === $MEM_STATUS_WITHDRAWAL) {
                    $error['excellent_id'] = '退会中の会員IDはご登録いただけません。';
                }
            }
        } else {
            $error['excellent_id'] = '会員IDが存在しておりません。管理者にお問い合わせください。';
        }
    }

    return $error;
}


/********************************************** メンバー情報取得 && 判定 ***********************************************/
/*-------------------------------------------*/
/*  会員情報取得 && 権限チェック
/*-------------------------------------------*/
function get_member_info($wp_user = null)
{
    global $MEM_STATUS_ACTIVE;     // 稼働中
    global $MEM_STATUS_DORMANT;    // 休眠中
    global $MEM_STATUS_WITHDRAWAL; // 退会中

    global $MEM_COMBINE_STATUS_HPA; // 0
    global $MEM_COMBINE_STATUS_EXA;
    global $MEM_COMBINE_STATUS_EXD;
    global $MEM_COMBINE_STATUS_HPA_EXA;
    global $MEM_COMBINE_STATUS_HPA_EXD;

    global $MEM_SYUBETSU_EX;         // エクセレント
    global $MEM_SYUBETSU_MEMBER;     // メンバー会員
    global $MEM_SYUBETSU_DOOR_SALES; // 訪販会員
    global $MEM_SYUBETSU_EMPLOYEE;   // 一般会員・社員

    global $DELIVERY_ADDRESS_KE; // 契約者住所
    global $DELIVERY_ADDRESS_RE; // 別送住所

    $user_wp = wp_get_current_user();
    if ($user_wp->ID === 0 && $wp_user) {
        $user_wp = $wp_user;
    }
    // var_dump($user_wp);
    $user_id = $user_wp->ID;
    $happy_id = get_user_meta($user_id, 'happy_id', true);
    $excellent_id  = get_user_meta($user_id, 'excellent_id', true);


    $res_hp = tryCatch('API_GetMemberDetail_hp', [$happy_id], false);
    $res_ex = tryCatch('API_GetMemberDetail_ex', [$excellent_id], false);
    // $res_h = tryCatch('API_GetMemberStat', [0, $happy_id], false);

    // echo "API =>API_GetMemberDetail_hp<br>パラメーター=> <br>ハッピーID：".$happy_id."<br>";
    // echo "戻り値<br>";
    // var_dump($res_hp);

    // echo "API =>API_GetMemberStat<br>パラメーター=> <br>mbr_kd：0<br>mbr_id：".$happy_id."<br>";
    // echo "戻り値<br>";
    // var_dump($res_h);

    $user['id'] =  $user_wp->ID;
    $user['login_id'] =  $user_wp->user_login;
    $user['email'] =  $user_wp->user_email;
    $user['happy_id'] = $happy_id;         // user_info[id]がnullの場合、会員ID登録なしと判断できる。
    $user['excellent_id'] = $excellent_id; // user_info[id]がnullの場合、会員ID登録なしと判断できる。

    // MEMBER STATUS設定 ※ハッピーはメンバー会員でない場合「退会中」のステイタスとする
    $hp_status = $MEM_STATUS_WITHDRAWAL;
    if ($res_hp->code === 0) {
        if ($res_hp->mbr_syu === $MEM_SYUBETSU_MEMBER) {
            $hp_status = $res_hp->mbr_stat;
        }
    }
    $ex_status = $MEM_STATUS_WITHDRAWAL;
    if ($res_ex->code === 0) {
        $ex_status = $res_ex->mbr_stat;
    }

    // WITHDRAWAL:退会 // DORMANT:休眠
    // MEMBER COMBINED STATUS設定
    if ($hp_status === $MEM_STATUS_ACTIVE && $ex_status === $MEM_STATUS_WITHDRAWAL) {
        // ハッピー： Active / エクセレント: 退会(登録なし)
        $user['mbr_combine_stat'] = $MEM_COMBINE_STATUS_HPA;
        $user['hp'] = get_member_info_hp($happy_id);
    } elseif ($hp_status === $MEM_STATUS_WITHDRAWAL && $ex_status === $MEM_STATUS_ACTIVE) {
        // ハッピー： 退会中(登録なし) / エクセレント: Active
        $user['mbr_combine_stat'] = $MEM_COMBINE_STATUS_EXA;
        $user['ex'] = get_member_info_ex($excellent_id, $user_id);
    } elseif ($hp_status === $MEM_STATUS_WITHDRAWAL && $ex_status === $MEM_STATUS_DORMANT) {
        // ハッピー： 退会中(登録なし) / エクセレント: 休眠中
        $user['mbr_combine_stat'] = $MEM_COMBINE_STATUS_EXD;
        $user['ex'] = get_member_info_ex($excellent_id, $user_id);
    } elseif ($hp_status === $MEM_STATUS_ACTIVE && $ex_status === $MEM_STATUS_ACTIVE) {
        // ハッピー： Active / エクセレント: Active
        $user['mbr_combine_stat'] = $MEM_COMBINE_STATUS_HPA_EXA;
        $user['hp'] = get_member_info_hp($happy_id);
        $user['ex'] = get_member_info_ex($excellent_id, $user_id);
    } elseif ($hp_status === $MEM_STATUS_ACTIVE && $ex_status === $MEM_STATUS_DORMANT) {
        // ハッピー： Active / エクセレント: 休眠中
        $user['mbr_combine_stat'] = $MEM_COMBINE_STATUS_HPA_EXD;
        $user['hp'] = get_member_info_hp($happy_id);
        $user['ex'] = get_member_info_ex($excellent_id, $user_id);
    } else {
        // var_dump('ハッピーID、エクセレントIDどちらもアクティブではない');
        if (is_user_logged_in()) {
            do_logout();
        }
        return;
    }

    // 配送先住所
    $user['delivery_address'] = '商品発送先住所のご登録がございません。';
    if ($hp_status === $MEM_STATUS_ACTIVE) {
        $user['mbr_nm'] = $res_hp->mbr_nm;
        $user['mbr_knm'] = $res_hp->mbr_knm;
        $user['mbr_bth'] = $res_hp->mbr_bth;
        $user['co_nm'] = $res_hp->co_nm;
        $user['mbr_kata'] = $res_hp->mbr_kata;
        $user['syodlv'] = $res_hp->syodlv;
        $user['syodlv_tel'] = $res_hp->syodlv_tel;
        if ($res_hp->syodlv === $DELIVERY_ADDRESS_KE) {
            $zip_ke_lead = substr($res_hp->ke_pcd, 0, 3);
            $zip_ke_behind = substr($res_hp->ke_pcd, 3);
            $user['zip_lead'] = $zip_ke_lead;
            $user['zip_behind'] = $zip_ke_behind;
            $user['address_1']  = $res_hp->ke_add1;
            $user['address_2']  = $res_hp->ke_add2;
            $user['delivery_address'] = "〒". $zip_ke_lead . "-" . $zip_ke_behind."<br>".$user['address_1']."<br>".$user['address_2'];
        }
        if ($res_hp->syodlv === $DELIVERY_ADDRESS_RE) {
            $zip_re_lead = substr($res_hp->re_pcd, 0, 3);
            $zip_re_behind = substr($res_hp->re_pcd, 3);
            $user['zip_lead'] = $zip_re_lead;
            $user['zip_behind'] = $zip_re_behind;
            $user['address_1'] = $res_hp->re_add1;
            $user['address_2'] = $res_hp->re_add2;
            $user['delivery_address'] =  "〒". $zip_re_lead . "-" . $zip_re_behind."<br>".$user['address_1']."<br>".$user['address_2'];
        }
    }
    if ($hp_status !== $MEM_STATUS_ACTIVE) {
        $user['mbr_nm'] = $res_ex->mbr_nm;
        $user['mbr_knm'] = $res_ex->mbr_knm;
        $user['mbr_bth'] = $res_ex->mbr_bth;
        $user['co_nm'] = $res_ex->co_nm;
        $user['mbr_kata'] = $res_ex->mbr_kata;
        $user['syodlv'] = $res_ex->syodlv;
        $user['syodlv_tel'] = $res_ex->syodlv_tel;
        if ($res_ex->syodlv === $DELIVERY_ADDRESS_KE) {
            $zip_ke_lead = substr($res_ex->ke_pcd, 0, 3);
            $zip_ke_behind = substr($res_ex->ke_pcd, 3);
            $user['zip_lead'] = $zip_ke_lead;
            $user['zip_behind'] = $zip_ke_behind;
            $user['address_1']  = $res_ex->ke_add1;
            $user['address_2']  = $res_ex->ke_add2;
            $user['delivery_address'] = "〒". $zip_ke_lead . "-" . $zip_ke_behind."<br>".$user['address_1']."<br>".$user['address_2'];
        }
        if ($res_ex->syodlv === $DELIVERY_ADDRESS_RE) {
            $zip_re_lead = substr($res_ex->re_pcd, 0, 3);
            $zip_re_behind = substr($res_ex->re_pcd, 3);
            $user['zip_lead'] = $zip_re_lead;
            $user['zip_behind'] = $zip_re_behind;
            $user['address_1'] = $res_ex->re_add1;
            $user['address_2'] = $res_ex->re_add2;
            $user['delivery_address'] =  "〒". $zip_re_lead . "-" . $zip_re_behind."<br>".$user['address_1']."<br>".$user['address_2'];
        }
    }

    session_check();
    $_SESSION['user']['id'] = $user['id'];
    $_SESSION['user']['mbr_combine_stat'] = $user['mbr_combine_stat'];
    $_SESSION['user']['mbr_nm'] = $user['mbr_nm'];
    $_SESSION['user']['mbr_knm'] = $user['mbr_knm'];
    $_SESSION['user']['mbr_bth'] = $user['mbr_bth'];
    $_SESSION['user']['co_nm'] = $user['co_nm'];

    return $user;
}

/*-------------------------------------------*/
/*  HAPPY 会員情報 詳細取得
/*-------------------------------------------*/
function get_member_info_hp($happy_id)
{
    $res = tryCatch('API_GetMemberDetail_hp', [$happy_id], false);

    $zip_ke_lead = substr($res->ke_pcd, 0, 3);
    $zip_ke_behind = substr($res->ke_pcd, 3);

    $zip_re_lead = substr($res->re_pcd, 0, 3);
    $zip_re_behind = substr($res->re_pcd, 3);

    $hp_detail['mbr_id'] = $res->mbr_id;
    $hp_detail['mbr_nm'] = $res->mbr_nm;
    $hp_detail['mbr_knm'] = $res->mbr_knm;
    $hp_detail['mbr_bth'] = date('Y年m月d日', strtotime($res->mbr_bth));
    $hp_detail['mbr_stat'] = $res->mbr_stat; // 0:稼働中、1:休眠中、2:退会
    $hp_detail['mbr_kata'] = $res->mbr_kata;
    $hp_detail['mbr_syu'] = $res->mbr_syu;   // 0:エクセレント 1:メンバー 5:訪販会員 9:一般会員・社員
    $hp_detail['co_nm'] = $res->co_nm;
    $hp_detail['ke_pcd'] = "〒". $zip_ke_lead . "-" . $zip_ke_behind;
    $hp_detail['ke_add1'] = $res->ke_add1;
    $hp_detail['ke_add2'] = $res->ke_add2;
    $hp_detail['ke_tel'] = $res->ke_tel;
    $hp_detail['ke_mob'] = $res->ke_mob;
    $hp_detail['ke_fax'] = $res->ke_fax;
    $hp_detail['re_pcd'] = "〒". $zip_re_lead . "-" . $zip_re_behind;
    $hp_detail['re_add1'] = $res->re_add1;
    $hp_detail['re_add2'] = $res->re_add2;
    $hp_detail['re_tel'] = $res->re_tel;
    $hp_detail['re_mob'] = $res->re_mob;
    $hp_detail['re_fax'] = $res->re_fax;
    $hp_detail['syodlv'] = $res->syodlv; // 商品発送先
    $hp_detail['shodlv'] = $res->shodlv; // 書類発送先
    $hp_detail['syodlv_tel'] = $res->syodlv_tel;
    $hp_detail['pi_id'] = $res->pi_id;
    $hp_detail['pi_nm'] = $res->pi_nm;
    $hp_detail['trkday'] = $res->trkday;
    $hp_detail['pos'] = $res->pos;         // ポジション
    $hp_detail['mbr_grd'] = $res->mbr_grd; // 取引率
    $hp_detail['aiyousya'] = $res->aiyousya;
    $hp_detail['tokumbr'] = $res->tokumbr;
    $hp_detail['tokuyakuten'] = $res->tokuyakuten;
    $hp_detail['eigyosyo'] = $res->eigyosyo;
    $hp_detail['dairiten'] = $res->dairiten;
    $hp_detail['hansya'] =$res->hansya;
    $hp_detail['syo_delivery_type'] = '契約者住所';
    $hp_detail['sho_delivery_type'] = '契約者住所';
    if ($res->syodlv === 1) {
        $hp_detail['syo_delivery_type'] = '別送住所';
    }
    if ($res->shodlv === 1) {
        $hp_detail['sho_delivery_type'] = '別送住所';
    }

    // セッション更新
    session_check();
    $_SESSION['user']['hp']['mbr_id'] = $res->mbr_id;
    $_SESSION['user']['hp']['mbr_stat'] = $res->mbr_stat;
    $_SESSION['user']['hp']['pos'] = $res->pos;
    $_SESSION['user']['hp']['mbr_grd'] = $res->mbr_grd;
    $_SESSION['user']['hp']['mbr_nm'] = $res->mbr_nm;
    $_SESSION['user']['hp']['mbr_knm'] = $res->mbr_knm;

    return $hp_detail;
}

function test_log()
{
    $res = tryCatch('API_GetMemberDetail_ex', ['000396', '102'], false);
    return $res;
}
/*-------------------------------------------*/
/*  EXCELLENT 会員情報 詳細取得
/*-------------------------------------------*/
function get_member_info_ex($excellent_id, $user_id)
{
    $res = tryCatch('API_GetMemberDetail_ex', [$excellent_id], false);

    $zip_ke_lead = substr($res->ke_pcd, 0, 3);
    $zip_ke_behind = substr($res->ke_pcd, 3);

    $zip_re_lead = substr($res->re_pcd, 0, 3);
    $zip_re_behind = substr($res->re_pcd, 3);

    // // 最新マイルを取得するためのデータ
    // $m_point = $res->m_point;
    // $m_point_upd = $res->m_point_upd;

    $ex_detail['mbr_id'] = $res->mbr_id;
    $ex_detail['mbr_nm'] = $res->mbr_nm;
    $ex_detail['mbr_knm'] = $res->mbr_knm;
    $ex_detail['mbr_bth'] = date('Y年m月d日', strtotime($res->mbr_bth));
    $ex_detail['mbr_stat'] = $res->mbr_stat; // 0:稼働中、1:休眠中、2:退会
    $ex_detail['mbr_kata'] = $res->mbr_kata;
    $ex_detail['mbr_syu'] = $res->mbr_syu;   // 0:エクセレント 1:メンバー 5:訪販会員 9:一般会員・社員
    $ex_detail['co_nm'] = $res->co_nm;
    $ex_detail['ke_pcd'] =  "〒". $zip_ke_lead . "-" . $zip_ke_behind;
    $ex_detail['ke_add1'] = $res->ke_add1;
    $ex_detail['ke_add2'] = $res->ke_add2;
    $ex_detail['ke_tel'] = $res->ke_tel;
    $ex_detail['ke_mob'] = $res->ke_mob;
    $ex_detail['ke_fax'] = $res->ke_fax;
    $ex_detail['re_pcd'] = "〒". $zip_re_lead . "-" . $zip_re_behind;
    $ex_detail['re_add1'] = $res->re_add1;
    $ex_detail['re_add2'] = $res->re_add2;
    $ex_detail['re_tel'] = $res->re_tel;
    $ex_detail['re_mob'] = $res->re_mob;
    $ex_detail['re_fax'] = $res->re_fax;
    $ex_detail['syodlv'] = $res->syodlv;
    $ex_detail['shodlv'] = $res->shodlv;
    $ex_detail['syodlv_tel'] = $res->syodlv_tel;
    $ex_detail['pi_id'] = $res->pi_id;
    $ex_detail['pi_nm'] = $res->pi_nm;
    $ex_detail['trkday'] = $res->trkday;
    $ex_detail['m_point'] = $res->m_point;
    $ex_detail['m_point_upd'] = $res->m_point_upd;
    // $ex_detail['new_m_point'] = get_mile_point($user_id, $m_point, $m_point_upd);
    $ex_detail['new_m_point'] = get_mile_point($user_id, $res->m_point);
    $ex_detail['syo_delivery_type'] = '契約者住所';
    $ex_detail['sho_delivery_type'] = '契約者住所';
    if ($res->syodlv === 1) {
        $ex_detail['syo_delivery_type'] = '別送住所';
    }
    if ($res->shodlv === 1) {
        $ex_detail['sho_delivery_type'] = '別送住所';
    }

    // ブランチのデータ作成
    foreach ($res->br as $br_data) {
        $data['br_id'] = $br_data->br_id;
        $data['br_ky'] = $br_data->br_ky;
        $data['br_kystr'] = $br_data->br_kystr;
        $data['br_r'] = $br_data->br_r;
        $data['br_l'] = $br_data->br_l;
        $data['br_tyoku'] = $br_data->br_tyoku;
        $data['br_stat'] = $br_data->br_stat;

        $ex_detail['br'][] = $data;
    }

    // セッション更新
    session_check();
    $_SESSION['user']['ex']['mbr_id'] = $res->mbr_id;
    $_SESSION['user']['ex']['mbr_nm'] = $res->mbr_nm;
    $_SESSION['user']['ex']['mbr_knm'] = $res->mbr_knm;
    $_SESSION['user']['ex']['mbr_stat'] = $ex_detail['mbr_stat'];
    $_SESSION['user']['ex']['new_m_point'] = $ex_detail['new_m_point'];

    return $ex_detail;
}

/*-------------------------------------------*/
/*  EXCELLENT マイルポイント取得
/*-------------------------------------------*/
function get_mile_point($user_id, $m_point_core_db)
{
    global $wpdb;

    $res_mile = $wpdb->get_results("SELECT * FROM miles WHERE user_id = $user_id");

    // milesテーブルに保存されていなければAPIのマイルポイントを優先する。
    if (!$res_mile) {
        return $m_point_core_db;
    }

    // mileテーブルに利用マイル累計が保存されていれば、ソレを差し引く。
    if ($res_mile) {
        $m_point_wpdb = $res_mile[0]->use_mile_total;

        $new_m_point = $m_point_core_db - $m_point_wpdb;
        return $new_m_point;
    }
}

/********************************************** オートシップ情報 ***********************************************/
/*-------------------------------------------*/
/*  オートシップ情報取得
/*-------------------------------------------*/
function get_autoship($excellent_id)
{
    // データ取得後、以下の形にデータを整形
    // ['code'=>0, 'auto'=> [['br_id'=> 001, detail=>[詳細array]],['br_id'=> 002, detail=>[詳細array]]...]]
    $data_autoship = tryCatch('API_GetMemberDetail_as', [$excellent_id], true); // json

    // jsonを配列に変換
    $data_autoship_ar = json_decode($data_autoship, true);

    // APIでエラーが帰ってきたらエラーをフロントに返す。 // TODO:
    if ($data_autoship_ar['code'] === 1) {
        return $data_autoship;
    }

    // br_ids順で配列をソートする
    $br_ids = array_column($data_autoship_ar['result'], 'br_id');
    array_multisort($br_ids, SORT_ASC, $data_autoship_ar['result']);

    // br_id毎にネストしている配列を作成
    $check = -1;
    $autoship = [];
    $autoship_info['autoships'] = [];
    foreach ($data_autoship_ar['result'] as $key => $data) {
        if ($check !== $data['br_id']) {
            if ($key !== 0) {
                array_push($autoship_info['autoships'], $autoship);
                $autoship = [];
            }
            $autoship['br_id'] = $data['br_id'];
            $autoship['br_stat'] = $data['br_stat'];
        }
        $detail['syo_cd'] = $data['syo_cd'];
        $detail['syo_nm'] = $data['syo_nm'];
        $detail['syo_num'] = $data['syo_num'];
        $detail['syo_kin'] = $data['syo_kin'];

        $autoship['detail'][$key] = $detail;
        $check = $data['br_id'];
    }
    array_push($autoship_info['autoships'], $autoship);
    $autoship_info['code'] = 0;

    return $autoship_info;
}

/*-------------------------------------------*/
/*  オートシップ詳細
/*-------------------------------------------*/
function get_autoship_detail($excellent_id, $br_id)
{
    // 値を取得してjsonを配列に変換
    $res_autoship_dtl = tryCatch('API_GetMemberDetail_as_dtl', [$excellent_id, $br_id], true);
    $res_autoship_dtl_ar = json_decode($res_autoship_dtl, true);

    if ($res_autoship_dtl_ar['code'] === 0) {
        return $res_autoship_dtl_ar;
    } else {
        return false;
    }
}

/********************************************** メール本文作成  ***********************************************/
/*-------------------------------------------*/
/* メール：TO USER: WEB会員仮登録
/*-------------------------------------------*/
function make_sign_up_email($mbr_name, $signup_url)
{
    $mail_body = '
    <!DOCTYPE html>
    <html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <meta content="width=device-width,initial-scale=1" name="viewport"/>
            <!--[if mso]>
            <xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
        </head>
        <body style="margin:20px;padding:0;-webkit-text-size-adjust:none;text-size-adjust:none">
            <div>'.$mbr_name.'様</div><br><br>

            ハッピーファミリーWEB会員サイトに仮登録いただき誠にありがとうございます。<br>
            下記URLにアクセスすることで登録完了となります。<br>
            （24時間有効です。）<br>
            <br>
            URL：<a href="'.$signup_url.'">'.$signup_url.'</a><br><br>
            <br>
            このメールに心あたりのない場合、<br>
            こちらのメールアドレスへお問い合わせいただくようお願いいたします。<br>
            happymembers@happyfamily.co.jp<br><br>
            <br>
            <div>【お問い合わせ先】</div>
            <div>■ 会社名</div>
            <div>ハッピーファミリー株式会社</div>
            <br>
            <div>■ 住所</div>
            <div>〒532-0003 大阪市淀川区宮原2丁目14番14号</div>
            <br>
            <div>■ 電話番号</div>
            <div>0120-198-141</div>
            <!-- <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff" width="80%"> -->
            <!-- End -->
        </body>
    </html>
    ';
    return $mail_body;
}
/*-------------------------------------------*/
/* メール：TO USER: 注文メール
/*-------------------------------------------*/
function make_order_email_to_user($order_product_list, $email_content)
{
    global $DELIVERY_ADDRESS_KE;
    global $DELIVERY_ADDRESS_RE;

    $mail_body = '
    <!DOCTYPE html>
    <html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <meta content="width=device-width,initial-scale=1" name="viewport"/>
            <!--[if mso]>
            <xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
        </head>
        <body style="margin:20px;padding:0px 5px;-webkit-text-size-adjust:none;text-size-adjust:none">
            <div>'.$email_content['name'].'様　注文日：'.$email_content['order_day'].'</div>
            <br>
            <div>ご注文ありがとうございます。</div><br>
            <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0" width="600px">
                <tr  style="text-align:left;">
                    <td colspan="2" width="66%">ログインID：'.$email_content['login_id'].'</td>
                    <td width="33%"></td>
                </tr>
                <tr>
                    <td colspan="2">WEB伝票番号：'.$email_content['order_code'].'</td>
                    <td>時間指定：'.$email_content['delivery_request_time'].'</td>
                </tr>
                <tr>
                    <td>Happy_ID：'.$email_content['happy_id'].'</td>
                    <td>ポジション：'.$email_content['pos'].'</td>
                    <td>取引区分：'.$email_content['mbr_grd'].'%</td>
                </tr>
                <tr>
                    <td>Excellent_ID : '.$email_content['excellent_id'].'</td>
                </tr>
                <tr>
                    <td>お支払い方法：'.$email_content['method_of_payment'].'</td>
                </tr>
            </table>
            <br>
            <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0" width="600px">
                <tr  style="text-align:left;">
                    <td width="10%" style="text-align:center;">コード<br>------</td>
                    <td width="40%">商品名<br>------</td>
                    <td width="10%" style="text-align:center;">購入単価<br>------</td>
                    <td width="10%" style="text-align:center;">個数<br>------</td>
                    <td width="10%" style="text-align:center;">小計<br>------</td>
                </tr>
                    '.$order_product_list.'
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align:right;padding-right:8px">合計</td>
                    <td style="text-align:right;padding-right:8px">'.$email_content['discounted_price'].'円</td>
                </tr>
            </table>
            <br>
            <br>
            <div>■ 氏名</div>
            <div>'.$email_content['name'].'様</div>
            <br>';
            if ($email_content['syodlv'] == $DELIVERY_ADDRESS_KE) {
                $mail_body .= '
                <div>■ 法人名</div>
                <div>'.$email_content['co_nm'].'</div>
                <br>
                <div>■ 肩書き</div>
                <div>'.$email_content['mbr_kata'].'</div>
                <br>';
            }
    $mail_body .= '
            <div>■ 発送先住所</div>
            <div>'.$email_content['zip'].'</div>
            <div>'.$email_content['address_1'].'</div>
            <div>'.$email_content['address_2'].'</div>
            <br>
            <div>■ 発送先電話番号</div>
            <div>'.$email_content['syodlv_tel'].'</div>
            <br>
            <div>こちらのメールは送信専用となります。</div>
            <div>商品のご変更やキャンセルはお電話からのみ受け付けております。</div>
            <br>
            <div>弊社の出荷日については、午前11時（WEB注文の場合は午前10時30分）までのご注文は<br>当日出荷となります。</div>
            <div>その時間を過ぎますと翌営業日出荷となりますのでご了承くださいませ。</div>
            <div>※土曜日、日曜日、祝日にいただいたご注文の場合は翌営業日の出荷となります。</div>
            <!-- <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff" width="600px"> -->
            <!-- End -->
        </body>
    </html>
    ';
    return $mail_body;
}

/*-------------------------------------------*/
/* メール：TO ADMIN: 注文メール
/*-------------------------------------------*/
function make_order_email_to_admin($order_product_list, $email_content)
{
    global $DELIVERY_ADDRESS_KE;
    global $DELIVERY_ADDRESS_RE;
    $mail_body = '
    <!DOCTYPE html>
    <html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <meta content="width=device-width,initial-scale=1" name="viewport"/>
            <!--[if mso]>
            <xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
        </head>
        <body style="margin:20px;padding:0px 5px;-webkit-text-size-adjust:none;text-size-adjust:none">
            <div>'.$email_content['name'].'様よりご注文を承りました。　注文日：'.$email_content['order_day'].'</div>
            <br>
            <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0" width="600px">
                <tr  style="text-align:left;">
                    <td colspan="2">ログインID：'.$email_content['login_id'].'</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3">メールアドレス：'.$email_content['email'].'</td>
                </tr>
                <tr>
                    <td colspan="2">WEB伝票番号：'.$email_content['order_code'].'</td>
                    <td>時間指定：'.$email_content['delivery_request_time'].'</td>
                </tr>
                <tr>
                    <td width="33%">Happy_ID：'.$email_content['happy_id'].'</td>
                    <td width="33%">ポジション：'.$email_content['pos'].'</td>
                    <td width="33%">取引区分：'.$email_content['mbr_grd'].'%</td>
                </tr>
                <tr>
                    <td>Excellent_ID : '.$email_content['excellent_id'].'</td>
                </tr>
                <tr>
                    <td>お支払い方法：'.$email_content['method_of_payment'].'</td>
                </tr>
            </table>
            <br>
            <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0" width="600px">
                    <tr">
                        <td width="10%" style="text-align:center;">コード<br>------</td>
                        <td width="40%">商品名<br>------</td>
                        <td width="10%" style="text-align:center;">購入単価<br>------</td>
                        <td width="10%" style="text-align:center;">個数<br>------</td>
                        <td width="10%" style="text-align:center;">小計<br>------</td>
                    </tr>
                        '.$order_product_list.'
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align:right;padding-right:8px">合計</td>
                        <td style="text-align:right;padding-right:8px">'.$email_content['discounted_price'].'円</td>
                    </tr>
            </table>
            <br>
            <br>
            <div>■ 氏名</div>
            <div>'.$email_content['name'].'様</div>
            <br>';
            if ($email_content['syodlv'] == $DELIVERY_ADDRESS_KE) {
                $mail_body .= '
                <div>■ 法人名</div>
                <div>'.$email_content['co_nm'].'</div>
                <br>
                <div>■ 肩書き</div>
                <div>'.$email_content['mbr_kata'].'</div>
                <br>';
            }
    $mail_body .= '
            <div>■ 発送先住所</div>
            <div>'.$email_content['zip'].'</div>
            <div>'.$email_content['address_1'].'</div>
            <div>'.$email_content['address_2'].'</div>
            <br>
            <div>■ 発送先電話番号</div>
            <div>'.$email_content['syodlv_tel'].'</div>
            <br>
            <div>■ 商品発送区分</div>
            <div>';
            if ($email_content['syodlv'] == $DELIVERY_ADDRESS_KE) {
                $mail_body .= '契約者住所';
            } else if ($email_content['syodlv'] == $DELIVERY_ADDRESS_RE) {
                $mail_body .= '別送住所';
            } else {
                $mail_body .= $email_content['syodlv'];
            }
    $mail_body .= '</div>
            <br>
            <!-- <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff" width="80%"> -->
            <!-- End -->
        </body>
    </html>
    ';
    return $mail_body;
}

/********************************************** その他 - 基本設定  ***********************************************/
/*-------------------------------------------*/
/*  無効ユーザーの削除
/*-------------------------------------------*/
function delete_invalid_user()
{
    require_once(ABSPATH.'wp-admin/includes/user.php');
    global $wpdb;
    $query_invalid_user = $wpdb->get_results("
    SELECT
        distinct user_id
    FROM
        wp_usermeta
    WHERE
        user_id IN(
            SELECT
                t_member_id.user_id
            FROM
                wp_usermeta t_member_id
                INNER JOIN
                    wp_usermeta t_valid
                ON  t_valid.user_id = t_member_id.user_id
                INNER JOIN
                    wp_usermeta t_created_at
                ON  t_created_at.user_id = t_member_id.user_id
            WHERE
                t_valid.meta_key = 'is_valid'
            AND t_valid.meta_value = false
            AND t_created_at.meta_key = 'nonce_created_at'
            AND t_created_at.meta_value <= DATE_FORMAT((NOW() - INTERVAL 1 DAY), '%Y-%m-%d %H:%i:%s')
        )
    ");
    if (!$query_invalid_user) {
        return;
    }
    foreach ($query_invalid_user as $invalid_user_id) {
        echo '<br><br><br><br>'.$invalid_user_id->user_id.'削除';
        wp_delete_user($invalid_user_id->user_id);
    }
    return;
}

/*-------------------------------------------*/
/* 出荷事務手数料取得
/*-------------------------------------------*/
function getFee()
{
    $setting_page = get_page_by_path('setting');
    $setting_page_id = $setting_page->ID;
    $fee = CFS()->get('basic', $setting_page_id);
    if (!$fee) {
        return 1000;
    }
    return intval(str_replace(',', '', $fee));
}

/*-------------------------------------------*/
/* 出荷事務手数料発生基準料金 //購入金額が○円以上だと出荷事務手数料がかからない金額を取得
/*-------------------------------------------*/
function getSettingPriceForFee()
{
    $setting_page = get_page_by_path('setting');
    $setting_page_id = $setting_page->ID;
    $no_fee_purchase_piice = CFS()->get('commission_standard', $setting_page_id);
    if (!$no_fee_purchase_piice) {
        return 7000;
    }
    return intval(str_replace(',', '', $no_fee_purchase_piice));
}

/*-------------------------------------------*/
/*  mileポイント最新チェック
/*-------------------------------------------*/
function check_mile_point()
{
    if (!isset($_SESSION)) {
        session_start();
    }

    $user_id = wp_get_current_user()->ID;
    $m_point_sys = $_SESSION['info']['ex']['m_point'];
    $m_point_upd_sys = $_SESSION['info']['ex']['m_point_upd'];

    global $wpdb;
    $res_mile = $wpdb->get_results("select * from miles WHERE user_id = $user_id");

    if (!$res_mile) {
        return $m_point_sys;
    } elseif ($res_mile) {
        $m_point_db = $res_mile[0]->remain_mile;
        $m_point_upd_db = $res_mile[0]->updated_at;

        if (strtotime($m_point_upd_db) > strtotime($m_point_upd_sys)) {
            $m_point = $m_point_db;
        // var_dump('基幹システムの方が古い');
        } else {
            $m_point = $m_point_sys;
            // var_dump('基幹システムの方が新しい');
        }
        return $m_point;
    }
}

/*-------------------------------------------*/
/*  メールをhtmlで作成
/*-------------------------------------------*/
function wpdocs_set_html_mail_content_type()
{
    return 'text/html';
}

/*-------------------------------------------*/
/*  console.log
/*-------------------------------------------*/
function console_log($data)
{
    echo '<script>';
    echo 'console.log('. json_encode($data) .')';
    echo '</script>';
}

/*-------------------------------------------*/
/*  セッションをチェックして動いてなければスタートする
/*-------------------------------------------*/
function session_check()
{
    if (!isset($_SESSION)) {
        session_start();
    }
    return ;
}
/********************************************** ここからまで ***********************************************/
