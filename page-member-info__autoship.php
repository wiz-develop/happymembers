<?php
/*
 * Template Post Type: page
 * Template Name: オートシップ情報詳細
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

get_header(); ?>
<?php
$user_id = wp_get_current_user()->ID;
$excellent_id = get_user_meta($user_id, 'excellent_id', true);

// オートシップ情報詳細を取得
if (isset($_GET['br_id'])) {
    $br_id = $_GET['br_id'];
    $autoship_detail = get_autoship_detail($excellent_id, $br_id);
}

// ブランチステイタスのチェック
if (isset($_GET['br_stat'])) {
    $autoship_status = intval($_GET['br_stat']);

    $status = 'Active';
    $status_class = 'active';
    if ($autoship_status === 1) {
        $status = '休眠中';
        $status_class = 'dormant';
    }
    if ($autoship_status === 2) {
        $status = '停止中';
        $status_class = 'withdrawal';
    }
}?>
<div id="page-autoship" class="page-product-archive page-member-info page-autoship">
    <div class="mod-body">
        <div class="content flex-side">
            <div class="flame-side">
                <?php get_template_part('/assets/template/member-nav');?>
            </div>
            <div class="flame-body">
                <div class="autoship">
                    <div class="autoship__title d-flex">
                        <h2><?php echo $autoship_detail['br_id']; ?></h2>
                        <div class="autoship-status">
                            <span class="<?php echo $status_class; ?>">
                                <?php  echo $status; ?>
                            </span>
                        </div>
					</div>
                    </div>
                    <div class="autoship__content">
                        <table>
                            <tr>
                                <td>支払方法</td>
                                <td>
                                    <?php
                                    if ($autoship_detail['shiharai_kbn']) {
                                        echo $autoship_detail['shiharai_kbn'];
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>商品金額合計</td>
                                <td>¥<?php echo number_format($autoship_detail['price_in']); ?></td>
                            </tr>
                            <tr>
                                <td>出荷開始日</td>
                                <td><?php echo date('Y年n月j日', strtotime($autoship_detail['ship_date'])); ?></td>
                            </tr>
                            <tr>
                                <td>停止日</td>
                                <td>
                                    <?php
                                    if ($autoship_detail['stop_date']) {
                                        echo date('Y年n月j日', strtotime($autoship_detail['stop_date']));
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>停止理由</td>
                                <td>
                                    <?php
                                    if ($autoship_detail['stop_kbn']) {
                                        echo $autoship_detail['stop_kbn'];
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php if ($autoship_detail['shiharai_kbn'] === '自動引落') : ?>
                                <tr>
                                    <td>引落開始年月</td>
                                    <td>
                                        <?php
                                        if ($autoship_detail["hiki_ym"]) {
                                            echo substr($autoship_detail["hiki_ym"], 0, 4).'年'.substr($autoship_detail["hiki_ym"], 4, 6).'月';
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>引落日</td>
                                    <td>
                                        <?php if ($autoship_detail['hiki_d']) {
                                            echo $autoship_detail['hiki_d'].'日';
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endif ;?>
                            <tr>
                                <td>定期商品発送日</td>
                                <td>
                                    <?php
                                    if ($autoship_detail['as_d']) {
                                        echo "{$autoship_detail['as_d']}日";
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>同送先ID</td>
                                <td>
                                    <?php if ($autoship_detail['inc_mbr_id']) {
                                        echo $autoship_detail['inc_mbr_id'];
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>同送先氏名</td>
                                <td>
                                    <?php if ($autoship_detail['inc_mbr_nm']) {
                                        echo $autoship_detail['inc_mbr_nm'];
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>同送先ブランチID</td>
                                <td>
                                    <?php if ($autoship_detail['inc_mbr_br']) {
                                        echo $autoship_detail['inc_mbr_br'];
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>時間指定</td>
                                <td>
                                    <?php if ($autoship_detail['time_kbn']) {
                                        echo $autoship_detail['time_kbn'];
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                        <div class="link-btn">
                            <a href="<?php echo site_url(); ?>/member-info/#autoship">
                                <div class="black-btn">
                                    オートシップ一覧へ戻る
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>
