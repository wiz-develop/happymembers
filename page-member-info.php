<?php
/*
 * Template Post Type: page
 * Template Name: 会員登録情報
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
global $MEM_STATUS_ACTIVE;     // 稼働中
global $MEM_STATUS_DORMANT;    // 休眠中
global $MEM_STATUS_WITHDRAWAL; // 退会中

$_SESSION['temp'] = [];
$user = get_member_info();
$excellent_id = '';
// エクセレント会員のボーナス情報取得
if ($user['excellent_id']) {
    $autoship_info = get_autoship($user['excellent_id']);
}?>

<div id="page-member-info" class="page-product-archive page-member-info">
    <div class="mod-body">
        <div class="content flex-side">
            <div class="flame-side">
                <?php get_template_part('/assets/template/member-nav');?>
            </div>
            <div class="flame-body">
                <div class="page-title">
                    <h1 class="page-title__name"><?php the_title(); ?></h1>
                </div>
                <div class="web-member">
                    <div class="item-title">
                        <span>web会員ご登録情報</span>
                        <div class="link-bnr">
                            <a href="<?php echo get_home_url(); ?>/member-info/edit">
                                <div class="black-btn">
                                    変更する
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="item-content">
                        <table>
                            <tr>
                                <td>ログインID</td>
                                <td><?php echo $user['login_id']; ?></td>
                            </tr>
                            <tr>
                                <td>会員ID</td>
                                <td>
                                    <div class="id-namber">
                                        <label>ハッピー会員ID</label>
                                        <span>
                                            <?php echo $user['happy_id']?>
                                        </span>
                                    </div>
                                    <div class="id-namber">
                                        <label>エクセレント会員ID</label>
                                        <span>
                                            <?php echo $user['excellent_id']?>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>パスワード</td>
                                <td>●●●●●●●●●</td>
                            </tr>
                            <tr>
                                <td>メールアドレス</td>
                                <td><?php echo  $user['email']; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="accordion">
                    <?php if ($user['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA || $user['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXA || $user['mbr_combine_stat'] === $MEM_COMBINE_STATUS_HPA_EXD) :?>
                        <div id="happy"></div>
                        <p class="menu accordion-menu d-flex">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/happy-logo.png"
                                alt="ハッピー会員">
                            <span>ハッピーファミリー</span>
                        </p>
                        <div class="accordion-content">
                            <table>
                                <tr>
                                    <td>会員ID</td>
                                    <td><?php echo $user['happy_id']; ?></td>
                                </tr>
                                <tr>
                                    <td>ご登録状況</td>
                                    <td>
                                        <?php
                                        if ($user['hp']["mbr_stat"] === $MEM_STATUS_ACTIVE) {
                                            echo 'Active';
                                        } elseif ($user['hp']["mbr_stat"] === $MEM_STATUS_DORMANT) {
                                            echo '休眠中';
                                        } elseif ($user['hp']["mbr_stat"] === $MEM_STATUS_WITHDRAWAL) {
                                            echo '退会中';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>氏名</td>
                                    <td><?php echo $user['hp']['mbr_nm']; ?></td>
                                </tr>
                                <?php if (($user['hp']['co_nm'])) : ?>
                                    <tr>
                                        <td>法人名</td>
                                        <td>
                                            <?php echo $user['hp']['co_nm'];?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>肩書き</td>
                                        <td>
                                            <?php
                                            if ($user['hp']['mbr_kata']) {
                                                echo$user['hp']['mbr_kata'];
                                            } else {
                                                echo '肩書きのご登録はありません。';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endif ; ?>
                                <tr>
                                    <td>ご契約住所/<br>ご連絡先</td>
                                    <td>
                                        <div class="item">
                                            <div class="item-title">
                                                ご住所
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['hp']['ke_pcd']) {
                                                    echo $user['hp']['ke_pcd'].'<br>';
                                                    echo $user['hp']['ke_add1'].'<br>';
                                                    echo $user['hp']['ke_add2'].'<br>';
                                                } else {
                                                    echo '契約先住所のご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="item-title">
                                                TEL
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['hp']["ke_tel"]) {
                                                    echo $user['hp']['ke_tel'];
                                                } else {
                                                    echo '電話番号のご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="item-title">
                                                携帯番号
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['hp']["ke_mob"]) {
                                                    echo $user['hp']['ke_mob'];
                                                } else {
                                                    echo '携帯番号のご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="item-title">
                                                FAX
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['hp']["ke_fax"]) {
                                                    echo $user['hp']['ke_fax'];
                                                } else {
                                                    echo 'FAXのご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>別送ご住所/<br>ご連絡先</td>
                                    <td>
                                        <div class="item">
                                            <div class="item-title">
                                                ご住所
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['hp']["re_add1"]) {
                                                    echo $user['hp']['re_pcd'].'<br>';
                                                    echo $user['hp']['re_add1'].'<br>';
                                                    echo $user['hp']['re_add2'].'<br>';
                                                } else {
                                                    echo '連絡先住所のご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="item-title">
                                                TEL
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['hp']["re_tel"]) {
                                                    echo $user['hp']['re_tel'];
                                                } else {
                                                    echo '電話番号のご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="item-title">
                                                携帯番号
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['hp']["re_mob"]) {
                                                    echo $user['hp']['re_mob'];
                                                } else {
                                                    echo '携帯番号のご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="item-title">
                                                FAX
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['hp']["re_fax"]) {
                                                    echo $user['hp']['re_fax'];
                                                } else {
                                                    echo 'FAXのご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>紹介者情報</td>
                                    <td>
                                        <div class="item">
                                            <div class="item-title">
                                                紹介者ID
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['hp']['pi_id']) {
                                                    echo $user['hp']['pi_id'];
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="item-title">
                                                紹介者名
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['hp']['pi_nm']) {
                                                    echo $user['hp']['pi_nm'];
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <table>
                                <tr>
                                    <td>商品発送先</td>
                                    <td>
                                        <?php
                                        if ($user['hp']['syo_delivery_type']) {
                                            echo $user['hp']['syo_delivery_type'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>書類発送先</td>
                                    <td>
                                        <?php
                                        if ($user['hp']['sho_delivery_type']) {
                                            echo $user['hp']['sho_delivery_type'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>商品お届け<br>電話番号</td>
                                    <td>
                                        <?php
                                        if ($user['hp']["syodlv_tel"]) {
                                            echo $user['hp']['syodlv_tel'];
                                        } else {
                                            echo '商品お届け先電話番号のご登録はありません。';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>ポジション</td>
                                    <td>
                                        <?php
                                        if ($user['hp']['pos']) {
                                            echo $user['hp']['pos'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>取引利率</td>
                                    <td>
                                        <?php
                                        if ($user['hp']['mbr_grd']) {
                                            echo $user['hp']['mbr_grd'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr class="promotion-date-content">
                                    <td colspan="2">
                                        <div class="title">ポジション昇格年月日</div>
                                        <table>
                                            <tr>
                                                <td>愛用者</td>
                                                <td>
                                                    <?php
                                                    if ($user['hp']['aiyousya']) {
                                                        echo date('Y年m月d日', strtotime($user['hp']['aiyousya']));
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>特別会員</td>
                                                <td>
                                                    <?php
                                                    if ($user['hp']['tokumbr']) {
                                                        echo date('Y年m月d日', strtotime($user['hp']['tokumbr']));
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>特約店</td>
                                                <td>
                                                    <?php
                                                    if ($user['hp']['tokuyakuten']) {
                                                        echo date('Y年m月d日', strtotime($user['hp']['tokuyakuten']));
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>営業所</td>
                                                <td>
                                                    <?php
                                                    if ($user['hp']['eigyosyo']) {
                                                        echo date('Y年m月d日', strtotime($user['hp']['eigyosyo']));
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>代理店</td>
                                                <td>
                                                    <?php
                                                    if ($user['hp']['dairiten']) {
                                                        echo date('Y年m月d日', strtotime($user['hp']['dairiten']));
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>販社</td>
                                                <td>
                                                    <?php
                                                    if ($user['hp']['hansya']) {
                                                        echo date('Y年m月d日', strtotime($user['hp']['hansya']));
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <?php endif;?>
                    <?php if ($user['mbr_combine_stat'] !== $MEM_COMBINE_STATUS_HPA) : ?>
                        <div id="excellent"></div>
                        <p class="menu accordion-menu d-flex">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/common/excellent-logo.png"
                                alt="エクセレント会員">
                            <span>エクセレント</span>
                        </p>
                        <div class="accordion-content">
                            <table>
                                <tr>
                                    <td>会員ID</td>
                                    <td><?php echo $user['ex']["mbr_id"]; ?></td>
                                </tr>
                                <tr>
                                    <td>ご登録状況</td>
                                    <td>
                                        <?php
                                        if ($user['ex']["mbr_stat"] === $MEM_STATUS_ACTIVE) {
                                            echo 'Active';
                                        } elseif ($user['ex']["mbr_stat"] === $MEM_STATUS_DORMANT) {
                                            echo '休眠中';
                                        } elseif ($user['ex']["mbr_stat"] === $MEM_STATUS_WITHDRAWAL) {
                                            echo '退会中'; // 登録できないから表示する必要はないはずだけど。
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>氏名</td>
                                    <td><?php echo $user['ex']["mbr_nm"]; ?></td>
                                </tr>
                                <?php if (($user['ex']['co_nm'])) : ?>
                                    <tr>
                                        <td>法人名</td>
                                        <td>
                                            <?php echo $user['ex']['co_nm'];?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>肩書き</td>
                                        <td>
                                            <?php
                                            if ($user['ex']['mbr_kata']) {
                                                echo$user['ex']['mbr_kata'];
                                            } else {
                                                echo '肩書きのご登録はありません。';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endif ; ?>
                                <tr>
                                    <td>ご契約住所/<br>ご連絡先</td>
                                    <td>
                                        <div class="item">
                                            <div class="item-title">
                                                ご住所
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['ex']["ke_add1"]) {
                                                    echo $user['ex']['ke_pcd'].'<br>';
                                                    echo $user['ex']['ke_add1'].'<br>';
                                                    echo $user['ex']['ke_add2'].'<br>';
                                                } else {
                                                    echo '契約先住所のご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="item-title">
                                                TEL
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['ex']["ke_tel"]) {
                                                    echo $user['ex']['ke_tel'];
                                                } else {
                                                    echo '電話番号のご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="item-title">
                                                携帯番号
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['ex']["ke_mob"]) {
                                                    echo $user['ex']['ke_mob'];
                                                } else {
                                                    echo '携帯番号のご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="item-title">
                                                FAX
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['ex']["ke_fax"]) {
                                                    echo $user['ex']['ke_fax'];
                                                } else {
                                                    echo 'FAXのご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>別送ご住所/<br>ご連絡先</td>
                                    <td>
                                        <div class="item">
                                            <div class="item-title">
                                                ご住所
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['ex']["re_add1"]) {
                                                    echo $user['ex']['re_pcd'].'<br>';
                                                    echo $user['ex']['re_add1'].'<br>';
                                                    echo $user['ex']['re_add2'].'<br>';
                                                } else {
                                                    echo '連絡先住所のご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="item-title">
                                                TEL
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['ex']["re_tel"]) {
                                                    echo $user['ex']['re_tel'];
                                                } else {
                                                    echo '電話番号のご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="item-title">
                                                携帯番号
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['ex']["re_mob"]) {
                                                    echo $user['ex']['re_mob'];
                                                } else {
                                                    echo '携帯番号のご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="item-title">
                                                FAX
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['ex']["re_fax"]) {
                                                    echo $user['ex']['re_fax'];
                                                } else {
                                                    echo 'FAXのご登録はありません。';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>紹介者</td>
                                    <td>
                                        <div class="item">
                                            <div class="item-title">
                                                紹介者ID
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['ex']['pi_id']) {
                                                    echo $user['ex']['pi_id'];
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="item-title">
                                                紹介者名
                                            </div>
                                            <div class="item-detail">
                                                <?php
                                                if ($user['ex']['pi_nm']) {
                                                    echo $user['ex']['pi_nm'];
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <table>
                                <tr>
                                    <td>商品発送先</td>
                                    <td>
                                        <?php
                                        if ($user['ex']['syo_delivery_type']) {
                                            echo $user['ex']['syo_delivery_type'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>書類発送先</td>
                                    <td>
                                        <?php
                                        if ($user['ex']['sho_delivery_type']) {
                                            echo $user['ex']['sho_delivery_type'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>商品お届け<br>電話番号</td>
                                    <td>
                                        <?php
                                        if ($user['ex']["syodlv_tel"]) {
                                            echo $user['ex']['syodlv_tel'];
                                        } else {
                                            echo '商品お届け先電話番号のご登録はありません。';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td> グループ情報</td>
                                    <td>
                                    <?php foreach ($user['ex']['br'] as $br) :?>
                                        <div class="item">
                                            <div class="item-title">
                                                <?php
                                                if (['br_id']) {
                                                    echo $br['br_id'];
                                                }
                                                ?>
                                            </div>
                                            <!-- 法人契約の有無にかかわらず個人名を表示 -->
                                            <?php if (isset($user['mbr_nm'])) :?>
                                                <div class="item-company">
                                                    <?php  echo $user['mbr_nm']; ?>
                                                </div>
                                            <?php endif ; ?>
                                            <div class="item-detail">
                                                <table>
                                                    <tr>
                                                        <td>左</td>
                                                        <td>
                                                            <?php
                                                            if ($br['br_l']) {
                                                                echo $br['br_l'];
                                                            } else {
                                                                echo '0';
                                                            } ?>
                                                            人
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>右</td>
                                                        <td>
                                                            <?php
                                                            if ($br['br_r']) {
                                                                echo $br['br_r'];
                                                            } else {
                                                                echo '0';
                                                            }
                                                            ?>人
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>直紹介</td>
                                                        <td>
                                                            <?php
                                                            if ($br['br_tyoku']) {
                                                                echo $br['br_tyoku'];
                                                            } else {
                                                                echo '0';
                                                            }
                                                            ?>人
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>状況</td>
                                                        <td>
                                                            <?php
                                                            if ($br['br_stat'] === 0) {
                                                                echo 'Active';
                                                            } elseif ($br['br_stat'] === 1) {
                                                                echo '休眠中';
                                                            } elseif ($br['br_stat'] === 2) {
                                                                echo '退会中';
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    <?php endforeach ;?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="autoship"></div>
                        <p class="menu accordion-menu">
                            <span>オートシップ情報</span>
                        </p>
                        <div class="accordion-content">
                            <div class="product-archive-list">
                                <?php if ($autoship_info['code'] === 0) : ?>
                                    <?php foreach ($autoship_info['autoships'] as $autoship) : ?>
                                        <?php
                                        $status = 'Active';
                                        $status_class = 'active';
                                        if ($autoship['br_stat'] === 1) {
                                            $status = '休眠中';
                                            $status_class = 'dormant';
                                        }
                                        if ($autoship['br_stat'] === 2) {
                                            $status = '停止中';
                                            $status_class = 'withdrawal';
                                        }
                                        ?>
                                        <div class="product-archive">
                                            <div class="product-archive__header">
                                                <div class="product-archive__header__order-detail">
                                                    <?php echo $autoship['br_id']; ?>
                                                </div>
                                                <div class="product-archive__header__order-detail">
                                                    <div class="product-archive__header__order-detail__item">
                                                        <span class="<?php echo $status_class; ?>">
                                                            <?php echo $status; ?>
                                                        </span>
                                                    </div>
                                                    <div class="product-archive__header__order-detail__item">
                                                        <div>
                                                            <div class="black-btn" data-branch="<?php echo $autoship["br_id"]; ?>" data-stat="<?php echo $autoship['br_stat']; ?>">
                                                                詳細
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-archive__body">
                                                <?php foreach ($autoship['detail'] as $detail) : ?>
                                                    <div class="product-archive__body__card">
                                                        <div class="item-title">
                                                            <?php echo $detail["syo_nm"]; ?>
                                                        </div>
                                                        <div class="item-price">
                                                            ¥ <?php echo number_format($detail["syo_kin"] / $detail["syo_num"]); ?><span>(税込)</span>
                                                        </div>
                                                        <div class="item-quantity">
                                                            合計 <?php echo $detail["syo_num"]; ?> 点
                                                        </div>
                                                    </div>
                                                <?php endforeach ; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                        <div>オートシップ情報が取得できませんでした。</div>
                                <?php endif ;?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // TODO:
    jQuery(function($) {
        $('.black-btn').on('click', function(){
            queryBranch = $(this).data('branch');
            queryStat = $(this).data('stat');
            window.location.href =  "<?php echo get_home_url();?>/member-info/autoship/" + "?br_id=" + queryBranch+ "&br_stat=" + queryStat;
        })
    })
</script>

<?php get_footer(); ?>
