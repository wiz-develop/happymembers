<?php
/**
 * The template for displaying the footer
 *
 * Contains the opening of the #site-footer div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

?>

            <footer id="site-footer" role="contentinfo" class="header-footer-group">
                <?php if (is_user_logged_in() && (!is_page('会員ログイン') && !is_page('WEB会員登録') && !is_page('入力内容確認') && !is_page('仮登録完了') && !is_page('WEB会員登録完了') && !is_page('WEB会員登録手順') && !is_page('パスワード再発行のご依頼') && !is_page('仮パスワード送信完了'))) :?>
                    <?php
                        $setting_page = get_page_by_path('setting');
                        $setting_page_id = $setting_page->ID;
                        $link_list = CFS()->get('link_list', $setting_page_id);
                        if ($link_list) :
                    ?>
                        <?php // get_template_part('/assets/template/sp-order');?>
                        <div class="footer-inner">
                            <div class="footer-top">
                                <nav>
                                    <ul class="footer-menu">
                                        <?php
                                            foreach ($link_list as $link) :
                                                $link_name = $link['link_name'];
                                                $link_url = $link['link_url'];
                                        ?>
                                        <li>
                                            <a href="<?php echo $link_url; ?>">
                                                <?php echo $link_name; ?>
                                            </a>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="section-inner">

                    <div class="footer-credits d-block">
                        <a href="http://jdsa.or.jp/" target="_blank">
                            <div class="bnr-link">
                                <img src="<?php $upload_dir = wp_upload_dir(); echo $upload_dir['baseurl']; ?>/2021/06/jdsa-logo.jpg" alt="公益社団法人日本訪問販売協会公式WEBサイト"/>
                            </div>
                        </a>
                        <p class="footer-copyright">
                        Copyright © Happy Family Co., Ltd. All Rights Reserved.
                        </p><!-- .footer-copyright -->

                    </div><!-- .footer-credits -->

                    <a class="to-the-top" href="#site-header">
                        <span class="to-the-top-long">
                            <?php
                            /* translators: %s: HTML character for up arrow. */
                            printf(__('To the top %s', 'twentytwenty'), '<span class="arrow" aria-hidden="true">&uarr;</span>');
                            ?>
                        </span><!-- .to-the-top-long -->
                        <span class="to-the-top-short">
                            <?php
                            /* translators: %s: HTML character for up arrow. */
                            printf(__('Up %s', 'twentytwenty'), '<span class="arrow" aria-hidden="true">&uarr;</span>');
                            ?>
                        </span><!-- .to-the-top-short -->
                    </a><!-- .to-the-top -->

                </div><!-- .section-inner -->

            </footer><!-- #site-footer -->
        <?php wp_footer(); ?>

    </body>
</html>