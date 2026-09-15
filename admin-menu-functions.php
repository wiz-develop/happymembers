<?php
/**
 * 管理画面メニューカスタマイズ
 */

/********************************
 * フック
 ********************************/
/**
 * データ照会
 */
add_action('admin_menu', 'add_data_inquiry_parent_page');
function add_data_inquiry_parent_page()
{
    /**
     * 親メニュー
     */
    add_menu_page('データ照会', 'データ照会', 'manage_options', 'admin-data-inquiry', '', 'dashicons-database', 59.9);

    /**
     * 子メニュー
     */
    add_submenu_page('admin-data-inquiry', 'WEB会員登録状況', 'WEB会員登録状況', 'manage_options', 'admin-member-status', 'admin_member_status_page', 1);
    add_submenu_page('admin-data-inquiry', '購入状況照会', '購入状況照会', 'manage_options', 'admin-purchase-status', 'admin_purchase_status_page', 2);
}

// データ照会親メニューページは非表示
add_action('admin_menu', function () {
    global $submenu;
    unset($submenu['admin-data-inquiry'][0]);
});

/********************************
 * ページ内容
 ********************************/
// WEB会員登録状況
function admin_member_status_page()
{
    echo '
        <iframe
            style="
                height: 100%;
                width: calc(100% - 20px);
            "
            src="' . content_url() . '/themes/happy-members/admin-page-member-status.php?access_from_admin=true&wp_content_url=' . urlencode(content_url()) . '"
            allow="fullscreen"
            id="admin-page-member-status"
        />
        </iframe>
        <script>
            ; (function ($) {
                $(function() {
                    $("#wpbody-content").css("height", $("#wpwrap").height() - 46 + "px");
                    $("#wpbody-content").css("padding-bottom", 0);
                });
            })(jQuery);;
        </script>
    ';
}

// 購入状況照会
function admin_purchase_status_page()
{
    echo '
        <iframe
            style="
                height: 100%;
                width: calc(100% - 20px);
            "
            src="' . content_url() . '/themes/happy-members/admin-page-purchase-status.php?access_from_admin=true&wp_content_url=' . urlencode(content_url()) . '"
            allow="fullscreen"
            id="admin-page-purchase-status"
        />
        </iframe>
        <script>
            ; (function ($) {
                $(function() {
                    $("#wpbody-content").css("height", $("#wpwrap").height() - 46 + "px");
                    $("#wpbody-content").css("padding-bottom", 0);
                });
            })(jQuery);;
        </script>
    ';
}
