<?php
/*
 * Template Post Type: page
 * Template Name: 各種申請書ダウンロード
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
*/
get_header();
session_check();
global $MEM_COMBINE_STATUS_HPA;
global $MEM_COMBINE_STATUS_EXA;
global $MEM_COMBINE_STATUS_EXD;
global $MEM_COMBINE_STATUS_HPA_EXA;
global $MEM_COMBINE_STATUS_HPA_EXD;
?>

<div id="page-member-info" class="page-product-archive page-member-info page-document">
    <div class="mod-body">
        <div class="content flex-side">
            <div class="flame-side">
                <div class="product-nav document-nav">
                    <div class="product-menu">
                        <ul>
                            <li class="page-title">各種申請書ダウンロード</li>
                            <?php if ($_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXD) : ?>
                                <a href="<?php echo get_home_url(); ?>/document/#happy">
                                    <li>ハッピーファミリー</li>
                                </a>
                            <?php endif; ?>
                            <?php  if ($_SESSION['user']['mbr_combine_stat'] !== $MEM_COMBINE_STATUS_HPA) : ?>
                                <a href="<?php echo get_home_url(); ?>/document/#excellent">
                                    <li>エクセレント</li>
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo get_home_url(); ?>/document/#other">
                                <li>その他各種資料ダウンロード</li>
                            </a>
                        </ul>
                    </div>
                </div>
                <?php get_template_part('/assets/template/member-nav');?>
            </div>
            <div class="flame-body">
                <div class="page-title">
                    <h1 class="page-title__name"><?php the_title(); ?></h1>
                </div>
                <div class="accordion">
                    <?php if ($_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA || $_SESSION['user']['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXD) : ?>
                    <div id="happy"></div>
                    <p class="menu accordion-menu d-flex">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/happy-logo.png" alt="ハッピー会員">
                        <span>ハッピーファミリー</span>
                    </p>
                    <div class="accordion-content">
                        <div class="document-content-list">
                            <?php
                                $args = array(
                                    'post_type' => 'document',
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'document',
                                            'field'    => 'slug',
                                            'terms'    => 'happy_document',
                                        ),
                                    ),
                                    'meta_key'  => 'order_namber', // カスタムフィールドのキー
                                    'orderby' => 'meta_value_num', // 上で指定したカスタムフィールドの値の数値で並び替え
                                    'order' => 'ASC', // 昇順で並べる
                                );

                                $query = new WP_Query($args);
                                if ($query->have_posts()) :

                                while ($query->have_posts()) :
                                    $query->the_post();
                                    $postid = get_the_ID();
                                    $document_detail = $cfs->get('document_detail', $postid);
                                    $document_dl = $cfs->get('document_dl', $postid);
                            ?>
                                <div class="document-content">
                                    <a href="<?php echo $document_dl; ?>" target="_blank" rel="noopener">
                                        <div class="document-content__detail">
                                            <div class="document-content__detail__title">
                                                <?php the_title(); ?>
                                            </div>
                                            <div class="document-content__detail__txt">
                                                <p><?php echo $document_detail; ?></p>
                                            </div>
                                        </div>
                                        <div class="document-content__link">
                                            <div class="download-icon d-flex">
                                                <span>ダウンロード</span>
                                                <div class="download-icon__image">
                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/document/download-icon.svg">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="document-content__icon">
                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/dl-icon.png">
                                        </div>
                                    </a>
                                </div>
                            <?php
                                    endwhile;
                                    wp_reset_postdata();
                                endif;
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php  if ($_SESSION['user']['mbr_combine_stat'] !== $MEM_COMBINE_STATUS_HPA) : ?>
                    <div id="excellent"></div>
                    <p class="menu accordion-menu d-flex">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/excellent-logo.png" alt="エクセレント会員">
                        <span>エクセレント</span>
                    </p>
                    <div class="accordion-content">
                        <div class="document-content-list">
                            <?php
                                $args = array(
                                    'post_type' => 'document',
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'document',
                                            'field'    => 'slug',
                                            'terms'    => 'excellent_document',
                                        ),
                                    ),
                                    'meta_key'  => 'order_namber', // カスタムフィールドのキー
                                    'orderby' => 'meta_value_num', // 上で指定したカスタムフィールドの値の数値で並び替え
                                    'order' => 'ASC', // 昇順で並べる
                                );
                                $query = new WP_Query($args);
                                if ($query->have_posts()) :

                                    while ($query->have_posts()) :
                                        $query->the_post();
                                        $postid = get_the_ID();
                                        $document_detail = $cfs->get('document_detail', $postid);
                                        $document_dl = $cfs->get('document_dl', $postid);
                            ?>
                                <div class="document-content">
                                    <a href="<?php echo $document_dl; ?>" target="_blank" rel="noopener">
                                        <div class="document-content__detail">
                                            <div class="document-content__detail__title">
                                                <?php the_title(); ?>
                                            </div>
                                            <div class="document-content__detail__txt">
                                                <p><?php echo $document_detail; ?></p>
                                            </div>
                                        </div>
                                        <div class="document-content__link">
                                            <div class="download-icon d-flex">
                                                <span>ダウンロード</span>
                                                <div class="download-icon__image">
                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/document/download-icon.svg">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="document-content__icon">
                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/dl-icon.png">
                                        </div>
                                    </a>
                                </div>
                            <?php
                                    endwhile;
                                    wp_reset_postdata();
                                endif;
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div id="other"></div>
                    <p class="menu accordion-menu d-flex">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/contract-icon.png" alt="ご契約に関する申請書">
                        <span>その他各種資料ダウンロード</span>
                    </p>
                    <div class="accordion-content">
                        <div class="document-content-list">
                            <?php
                                $args = array(
                                    'post_type' => 'document',
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'document',
                                            'field'    => 'slug',
                                            'terms'    => 'other_document',
                                        ),
                                    ),
                                    'meta_key'  => 'order_namber', // カスタムフィールドのキー
                                    'orderby' => 'meta_value_num', // 上で指定したカスタムフィールドの値の数値で並び替え
                                    'order' => 'ASC', // 昇順で並べる
                                );
                                $query = new WP_Query($args);
                                if ($query->have_posts()) :

                                    while ($query->have_posts()) :
                                        $query->the_post();
                                        $postid = get_the_ID();
                                        $document_detail = $cfs->get('document_detail', $postid);
                                        $document_dl = $cfs->get('document_dl', $postid);
                            ?>
                                <div class="document-content">
                                    <a href="<?php echo $document_dl; ?>" target="_blank" rel="noopener">
                                        <div class="document-content__detail">
                                            <div class="document-content__detail__title">
                                                <?php the_title(); ?>
                                            </div>
                                            <div class="document-content__detail__txt">
                                                <p><?php echo $document_detail; ?></p>
                                            </div>
                                        </div>
                                        <div class="document-content__link">
                                            <div class="download-icon d-flex">
                                                <span>ダウンロード</span>
                                                <div class="download-icon__image">
                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/document/download-icon.svg">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="document-content__icon">
                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/dl-icon.png">
                                        </div>
                                    </a>
                                </div>
                            <?php
                                    endwhile;
                                    wp_reset_postdata();
                                endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>
