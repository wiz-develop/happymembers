<?php
/**
 * Displays the menu icon and modal
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

// config呼べなかったので直書き
/**
 * MEMBER COMBINED STATUS
 */
$MEM_COMBINE_STATUS_HPA = 0;
$MEM_COMBINE_STATUS_EXA = 1;
$MEM_COMBINE_STATUS_EXD = 2;
$MEM_COMBINE_STATUS_HPA_EXA = 3;
$MEM_COMBINE_STATUS_HPA_EXD = 4;

/**
 * MEMBER STATUS
 */
$MEM_STATUS_ACTIVE = 0;    // 稼働中
$MEM_STATUS_DORMANT = 1;   // 休眠中
$MEM_STATUS_WITHDRAWAL = 2;// 退会中・登録なし
$MEM_STATUS_NONE = 3;      // 会員ID登録なし

/**
 * EXCELLENT ID *** ファミリー割引廃止を行うエクセレントIDの範囲。この値より大きいIDが廃止対象
 */
$MEM_ID_EXCELLENT = 3;      // 会員ID登録なし

session_check();
if (isset($_SESSION['user']['mbr_combine_stat'])) {
    $mbr_combine_stat = $_SESSION['user']['mbr_combine_stat'];
}?>

<div class="menu-modal cover-modal header-footer-group" data-modal-target-string=".menu-modal">

    <div class="menu-modal-inner modal-inner">

        <div class="menu-wrapper section-inner">

            <div class="menu-top">

                <button class="toggle close-nav-toggle fill-children-current-color" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".menu-modal">
                    <?php twentytwenty_the_theme_svg('cross'); ?>
                    <span class="toggle-text">閉じる</span>
                </button><!-- .nav-toggle -->

                <div class="mobile-nav-toggle">
                    <h2>会員情報</h2>
                    <div class="sp-membership-info member">
                        <div class="member__info">
                            <!-- ハッピー会員登録されている場合は表示 -->
                            <?php if (isset($_SESSION['user']['hp']['mbr_id'])) : ?>
                                <div class="member__info__detail">
                                    <?php
                                    if ($_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXD) {
                                        echo '<span>ポジション：'.$_SESSION['user']['hp']['pos'].'</span>';
                                        echo '<span>購入利率：'.$_SESSION['user']['hp']['mbr_grd'].'%</span>';
                                    } else {
                                        echo '退会中';
                                    } ?>
                                </div>
                            <?php endif; ?>
                            <!-- エクセレント会員登録されている場合は表示  -->
                            <?php if (isset($_SESSION['user']['ex']['mbr_id'])) : ?>
                                <div class="member__info__detail">
                                <?php
                                if ($_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA) {
                                    echo '退会中';
                                } else {
                                    if ($_SESSION['user']['ex']['mbr_stat'] === $MEM_STATUS_ACTIVE) {
                                        $status = 'Active';
                                    } elseif ($_SESSION['user']['ex']['mbr_stat'] === $MEM_STATUS_DORMANT) {
                                        $status = '休眠中';
                                    }
                                    echo '<span>ステイタス：'.$status.'</span>';
                                } ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php

                $mobile_menu_location = '';

                // If the mobile menu location is not set, use the primary and expanded locations as fallbacks, in that order.
                if (has_nav_menu('mobile')) {
                    $mobile_menu_location = 'mobile';
                } elseif (has_nav_menu('primary')) {
                    $mobile_menu_location = 'primary';
                } elseif (has_nav_menu('expanded')) {
                    $mobile_menu_location = 'expanded';
                }

                if (has_nav_menu('expanded')) {
                    $expanded_nav_classes = '';

                    if ('expanded' === $mobile_menu_location) {
                        $expanded_nav_classes .= ' mobile-menu';
                    } ?>

                    <nav class="expanded-menu<?php echo esc_attr($expanded_nav_classes); ?>" aria-label="<?php echo esc_attr_x('Expanded', 'menu', 'twentytwenty'); ?>" role="navigation">

                        <ul class="modal-menu reset-list-style">
                            <?php
                            if (has_nav_menu('expanded')) {
                                wp_nav_menu(
                                    array(
                                        'container'      => '',
                                        'items_wrap'     => '%3$s',
                                        'show_toggles'   => true,
                                        'theme_location' => 'expanded',
                                    )
                                );
                            } ?>
                        </ul>

                    </nav>

                    <?php
                }

                if ('expanded' !== $mobile_menu_location) {
                    ?>

                    <nav class="mobile-menu" aria-label="<?php echo esc_attr_x('Mobile', 'menu', 'twentytwenty'); ?>" role="navigation">

                        <ul class="modal-menu reset-list-style">

                        <?php
                        if ($mobile_menu_location) {
                            wp_nav_menu(
                                array(
                                    'container'      => '',
                                    'items_wrap'     => '%3$s',
                                    'show_toggles'   => true,
                                    'theme_location' => $mobile_menu_location,
                                )
                            );
                        } else {
                            wp_list_pages(
                                array(
                                    'match_menu_classes' => true,
                                    'show_toggles'       => true,
                                    'title_li'           => false,
                                    'walker'             => new TwentyTwenty_Walker_Page(),
                                )
                            );
                        } ?>

                        </ul>

                    </nav>

                    <?php
                }
                ?>
                <div class="mobile-nav-toggle">
                    <div class="logout">
                        <div id="btn-logout" class="logout-btn">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/logout-icon_blue.png">
                            <span>ログアウト</span>
                        </div>
                    </div>
                </div>

            </div><!-- .menu-top -->

            <div class="menu-bottom">

                <?php if (has_nav_menu('social')) { ?>

                    <nav aria-label="<?php esc_attr_e('Expanded Social links', 'twentytwenty'); ?>" role="navigation">
                        <ul class="social-menu reset-list-style social-icons fill-children-current-color">

                            <?php
                            wp_nav_menu(
                    array(
                                    'theme_location'  => 'social',
                                    'container'       => '',
                                    'container_class' => '',
                                    'items_wrap'      => '%3$s',
                                    'menu_id'         => '',
                                    'menu_class'      => '',
                                    'depth'           => 1,
                                    'link_before'     => '<span class="screen-reader-text">',
                                    'link_after'      => '</span>',
                                    'fallback_cb'     => '',
                                )
                );
                            ?>

                        </ul>
                    </nav><!-- .social-menu -->

                <?php } ?>

            </div><!-- .menu-bottom -->

        </div><!-- .menu-wrapper -->

    </div><!-- .menu-modal-inner -->

</div><!-- .menu-modal -->
