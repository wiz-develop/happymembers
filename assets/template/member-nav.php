<?php
global $MEM_COMBINE_STATUS_HPA;
global $MEM_COMBINE_STATUS_EXA;
global $MEM_COMBINE_STATUS_EXD;
global $MEM_COMBINE_STATUS_HPA_EXA;
global $MEM_COMBINE_STATUS_HPA_EXD;
session_check();
$mbr_combine_stat = $_SESSION['user']['mbr_combine_stat'];
?>
<div class="product-nav">
    <div class="product-menu">
        <ul class="ml-0">
            <li class="link-item d-flex align-items-center ml-0">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/member-icon_w.png">
                <span class="pl-2">会員情報</span>
            </li>
            <a href="<?php echo get_home_url(); ?>/member-info/chart">
                <li>組織図</li>
            </a>
            <a href="<?php echo get_home_url(); ?>/member-info/">
                <li>web会員ご登録情報</li>
            </a>
            <?php if ($mbr_combine_stat === $MEM_COMBINE_STATUS_HPA || $mbr_combine_stat === $MEM_COMBINE_STATUS_HPA_EXA || $mbr_combine_stat === $MEM_COMBINE_STATUS_HPA_EXD) : ?>
                <a href="<?php echo get_home_url(); ?>/member-info/#happy">
                    <li>ハッピーファミリー<br>登録情報</li>
                </a>
            <?php endif; ?>
            <?php if ($mbr_combine_stat !== $MEM_COMBINE_STATUS_HPA) : ?>
                <a href="<?php echo get_home_url(); ?>/member-info/#excellent">
                    <li>エクセレント<br>登録情報</li>
                </a>
                <a href="<?php echo get_home_url(); ?>/member-info/#autoship">
                    <li>オートシップ情報</li>
                </a>
            <?php endif; ?>
            <a href="<?php echo get_home_url(); ?>/member-info/bonus">
                <li>ボーナス明細</li>
            </a>
        </ul>
    </div>
</div>