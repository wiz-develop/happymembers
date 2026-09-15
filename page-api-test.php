<?php
/**
 * Template Name: APIテストページ
 *
 */
get_header();

// オートロード
require("assets/api/v1/common/autoload.php");
?>

<div class="page">
    <?php
        // 呼び出し
        /*
        $func = "API_GetSystem";
        $answer = $func();
        $func = "API_GetMemberStat";
        $answer = $func(0, "000002");
        $answer = $func(1, "000002");
        $func = "API_ChekMember";
        $answer = $func(0, "000002", 20000101, "2_ｼﾒｲｶﾅ");
        $answer = $func(1, "000002", 20000101, "Bｻﾝ");
        $func = "API_GetMemberDetail_hp";
        $answer = $func("000002");
        $func = "API_GetMemberDetail_ex";
        $answer = $func("000014");
        $func = "API_GetMemberDetail_as";
        $answer = $func("000002");
        $func = "API_GetBonusInf_hp";
        $answer = $func("105246", 202001, 202006);
        $answer = $func("105246", 202006);
        $func = "API_GetBonusInf_ex";
        $answer = $func("000023", 202101, 202106);
        $answer = $func("000023", 202105);
        $func = "API_GetMemberShip_hp";
        $answer = $func("000002");
        $func = "API_GetMemberShip_ex";
        $answer = $func("000001");
        $func = "API_GetMemberShip_bi";
        $answer = $func("000001");
        */

        /**
         * トライキャッチでjsonもしくはarrayを返す
         * function tryCatch($funcName, $params, $isReturnJson = false)
         * happy-members/assets/api/v1/common/data_operation.php
         *
         * @param string $funcName
         * @param array $params
         * @param bool $isReturnJson
         *
         * @return string|array
         */
        var_dump(tryCatch('API_GetSystem', [], false));
    ?>
    <?php the_content(); ?>
</div>

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php
get_footer();
