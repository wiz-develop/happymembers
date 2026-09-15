<?php
/*
 * Template Name: 組織図
 * Template Post Type: page
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
session_check();?>
<script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/chart.js"></script>
<?php
$user = get_member_info();
$happy_id = null;
$excellent_id = null;
if (isset($user['hp']['mbr_id'])) {
    $happy_id = $user['hp']['mbr_id'];
}
if (isset($user['ex']['mbr_id'])) {
    $excellent_id = $user['ex']['mbr_id'];
}
?>
<div id="page-member-info-chart" class="page-member-info__chart" data-happy-id="<?php echo $happy_id;?>" data-excellent-id="<?php echo $excellent_id;?>">
    <div class="mod-body">
        <div class="content flex-side">
            <div class="flame-side">
                <?php get_template_part('/assets/template/member-nav');?>
            </div>
            <div class="flame-body">
                <div class="page-title">
                    <h1 class="page-title__name"><?php the_title(); ?></h1>
                    <div class="label-detail">
                        <div class="label-detail__active">
                            Active
                        </div>
                        <div class="label-detail__dormant display-none">
                            休眠
                        </div>
                        <div class="label-detail__withdrawal">
                            退会
                        </div>
                    </div>
                </div>
                <div class="chart">
                    <div class="chart__search">
                        <div class="chart__search__function">
                            <div class="chart__search__function__back default_function">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chart/you-icon.png">
                                <span>あなたに戻る</span>
                            </div>
                            <div class="chart__search__function__back for_binary_function display-none">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chart/you-icon.png">
                                <span>あなたに戻る</span>
                            </div>
                            <div class="chart__search__function__zoom zoom_in">
                                <span>拡大</span>
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chart/expansion-icon.png">
                            </div>
                            <div class="chart__search__function__zoom zoom_out">
                                <span>縮小</span>
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chart/shrink-icon.png">
                            </div>
                        </div>
                        <!-- 検索ボックスを非表示に。今後万が一復活した時のため、機能自体は残している(画面で表示されたユーザーの) -->
                        <div class="chart__search__input" style="display:none;">
                            <input type="search" name="chart-search" placeholder="ID,名前,フリガナで検索" class="input-area search">
                            <input type="submit" value="検索" class="search-btn">
                        </div>
                        <div class="chart__search__select">
                            <div class="chart__search__select__branch ex-introducer display-none">
                                <div class="title">紹介者</div>
                                <select class="select ex-introducer" name="branch_for_introducer">
                                    <?php foreach ($user['ex']['br'] as $br_data) :?>
                                        <option class="branch_option" value="<?php echo $br_data['br_id']; ?>">
                                            <?php echo $br_data['br_id']; ?>
                                        </option>
                                    <?php endforeach ;?>
                                </select>
                            </div>
                            <div class="chart__search__select__branch ex-binary display-none">
                                <div class="title">バイナリ</div>
                                <select class="select ex-binary" name="branch_for_binary">
                                    <?php foreach ($user['ex']['br'] as $br_data) :?>
                                        <option class="branch_option" value="<?php echo $br_data['br_id']; ?>">
                                            <?php echo $br_data['br_id']; ?>
                                        </option>
                                    <?php endforeach ;?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="chart__content">
                        <?php if ($happy_id) :?>
                            <!-- ハッピー-紹介者 -->
                            <input type="radio" name="tab_name" id="introducer-happy" value="introducer-happy">
                            <label class="tab_class list-tab ei-tab" for="introducer-happy">ハッピー<span>紹介者</span></label>
                            <div class="content_class content_introducer-happy">
                                <div class="chart_scroll_target" data-base-width="" data-zoom="0">
                                    <div class="chart__base-box hp-introducer" id="base-box-hp" style="left: 50%; top: 5%;"  data-type="hp" data-member_type="0"　position="relative">
                                        <div class="chart__base-box__inner hp-introducer you" style="left: 0px; top: 0px;" id="<?php echo 'hp-'.$happy_id;?>"
                                            data-my_id="<?php echo $happy_id; ?>" data-level="1">
                                            <div class="modal_trigger chart-user">
                                                <div class="modal_trigger__member-img chart-user__icon">
                                                    <img class="img-mbr" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chart/you-icon.png">
                                                </div>
                                                <div class="modal_trigger__member-name" id="you--introducer">あなた</div>
                                            </div>
                                            <div class="btn-display">
                                                <div class="btn-display__text" style="display: none">表示する</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($excellent_id) :?>
                            <!-- エクセレント-紹介者 -->
                            <input type="radio" name="tab_name" id="introducer-excellent" value="introducer-excellent">
                            <label class="tab_class list-tab ei-tab" for="introducer-excellent">エクセレント<span>紹介者</span></label>
                            <div class="content_class content_introducer-excellent">
                                <div class="chart_scroll_target" data-base-width="" data-zoom="0">
                                    <div class="chart__base-box ex-introducer" id="base-box-ex" style="left: 50%; top: 5%;"  data-type="ex" data-member_type="1" position="relative">
                                        <div class="chart__base-box__inner ex-introducer you" style="left: 0px; top: 0px;" id="<?php echo 'ex-'.$excellent_id.'001';?>"
                                            data-my_id="<?php echo $excellent_id; ?>" data-my_branch="001" data-level="1">
                                            <div class="modal_trigger chart-user">
                                                <div class="modal_trigger__member-img chart-user__icon">
                                                    <img class="img-mbr" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chart/you-icon.png">
                                                </div>
                                                <div class="modal_trigger__member-name first_view" id="you-ex-introducer">あなた</div>
                                            </div>
                                            <div class="btn-display">
                                            <div class="btn-display__text" style="display: none">表示する</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- エクセレント-バイナリ -->
                            <input type="radio" name="tab_name" id="excellent-binary" value="excellent-binary">
                            <label class="tab_class list-tab ei-tab" for="excellent-binary">エクセレント<span>バイナリ</span></label>
                            <div class="content_class content_excellent-binary">
                                <div class="chart_scroll_target" data-base-width="" data-zoom="0">
                                    <div class="chart__base-box ex-binary" id="base-box-binary" style="left: 50%; top: 5%;"  data-type="ex" data-member_type="1" position="relative">
                                        <div class="chart__base-box__inner ex-binary you" style="left: 0px; top: 0px;" id="<?php echo 'ex-bi-'.$excellent_id.'001';?>"
                                            data-my_id="<?php echo $excellent_id; ?>" data-my_branch="001">
                                            <div class="modal_trigger chart-user">
                                                <div class="modal_trigger__member-img chart-user__icon">
                                                    <img class="img-mbr" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/chart/you-icon.png">
                                                </div>
                                                <div class="modal_trigger__member-name first_view" id="you-ex-binary">あなた</div>
                                            </div>
                                            <div class="btn-display" data-parent_id="">
                                            <div class="btn-display__text" style="display: none">表示する</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif;?>
                        <!-- モーダル -->
                        <div class="modal_box">
                            <div class="modal_bg"></div>
                            <div class="modal_inner" id="modal-ex">
                                <div class="modal_close">
                                    <div class="black-btn">
                                        閉じる<span>×</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>
<style>
.chart__search__select__branch {
    display: flex;
}
.chart__search__select__branch  .title {
    width: 30%;
}
.chart__search__select__branch  .select {
    width: 70%;
}
.display-none {
    display: none !important;
}

#you-ex-binary {
    cursor: pointer;
}
.chart {
    position: relative;
    min-height :70vh !important;
    max-height :90vh !important;
}
/* 正方形のbox */
.chart__base-box {
    position: relative;
    width :100px;
    height:70px;
    margin: 0px;
    transform: scale(1);
    transform-origin: 0% 0%;
}
/* 全体の高さ */
.chart__base-box__inner {
    position: absolute;
    width: 100px;
    height: 70px;
    margin: 0px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 10 !important;
}
.chart__introducer-ex-parts {
    position: absolute;
    height:70px;
    margin: 0px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 10 !important;
}
.modal_trigger {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 8px;
    cursor: pointer;
}
/* 正方形のbox */
.modal_trigger__member-img{
    width :30px;
    height: 30px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    /* background-color: skyblue; */
    border-radius: 50%;
}
.modal_trigger__member-img .img-mbr {
    width: 80%;
}
/* 名前の場所 */
.modal_trigger__member-name {
    width: 70px;
    height:20px;
    text-align: center;
}
/* 子供のボタン */
.btn-display {
    /* width: 120%; */
    height: 20px;
    width: 50px;
    cursor: pointer;
}
.btn-display__text{
    background-color: pink;
    border-radius: 5px;
    text-align: center;
    line-height: 20px;
    font-size: 8px;
}

.chart__search__select {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

.chart__search__select .branch_option {
    background-color: pink;
    cursor: pointer;
    margin: 10px;
    width: 100%;
}

.chart__search__select  .chart__search__select__branch {
    /* display: flex;
    justify-content: space-between;
    align-items: center; */
    width: 50%;
    padding: 10px;
}

.active {
    background-color: #71A6EF;
}
.withdrawal {
    background-color: #EF7171;
}
.dormant {
    background-color: #FDD000;
}

.modal_trigger .member-img .chart-user__icon {
    width: 100% !important;
}
/* .chart__search__function{
    display: none !important;
    height: 0px !important;
}
.chart__search__select {
    display: none !important;
    height: 0px !important;
} */

.connector-line-box {
    position: absolute;
    width:100px;
    height:100px;
}
.connector-line-box .upper-box{
    width:100%;
    height:50%;
}
    .connector-line-box .under-box{
    width:100%;
    height:50%;
}
.ex-introducer .connector-line-box.left .upper-box,
.hp-introducer .connector-line-box.left .upper-box {
    border-right:1px solid gray;
    border-bottom:1px solid gray;
}
.ex-introducer .connector-line-box.left .under-box,
.hp-introducer .connector-line-box.left .under-box{
    border-left:1px solid gray;
}
.ex-introducer .connector-line-box.center .upper-box,
.hp-introducer .connector-line-box.center .upper-box{
    border-right:1px solid gray;
}
.ex-introducer .connector-line-box.center .under-box,
.hp-introducer .connector-line-box.center .under-box{
    border-right:1px solid gray;
}
.ex-introducer .connector-line-box.right .upper-box,
.hp-introducer .connector-line-box.right .upper-box{
    border-left:1px solid gray;
    border-bottom:1px solid gray;
}
.ex-introducer .connector-line-box.right .under-box,
.hp-introducer .connector-line-box.right .under-box{
    border-right:1px solid gray;
}

.ex-binary .connector-line-box.left {
    background: linear-gradient(to left top, transparent 49%, gray 49.5%, gray 50.5%, transparent 51%);
}
.ex-binary .connector-line-box.right{
    background: linear-gradient(to right top, transparent 49%, gray 49.5%, gray 50.5%, transparent 51%);
}


/**********************************************
*　アニメーション
***********************************************/
@keyframes fadeIn {
    /*animation-nameで設定した値を書く*/
    0% {
        opacity: 0;
    }
    /*アニメーション開始時は不透明度0%*/
    100% {
        opacity: 1;
    }
    /*アニメーション終了時は不透明度100%*/
}

@-webkit-keyframes sk-stretchdelay {
    0%, 40%, 100% {
        -webkit-transform: scaleY(0.4);
    }
    20% {
        -webkit-transform: scaleY(1);
    }
}

/**********************************************
* ローディング アニメーション
***********************************************/
@keyframes sk-stretchdelay {
    0%, 40%, 100% {
        transform: scaleY(0.4);
        -webkit-transform: scaleY(0.4);
    }
    20% {
        transform: scaleY(1);
        -webkit-transform: scaleY(1);
    }
}

.spinner {
    position:absolute;
    top:50%;
    left:60%;
    width: 50px;
    height: 40px;
    text-align: center;
    font-size: 10px;
}

.spinner > div {
    background-color: #666;
    height: 100%;
    width: 6px;
    display: inline-block;
    -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;
    animation: sk-stretchdelay 1.2s infinite ease-in-out;
}

.spinner .rect2 {
    -webkit-animation-delay: -1.1s;
    animation-delay: -1.1s;
}

.spinner .rect3 {
    -webkit-animation-delay: -1.0s;
    animation-delay: -1.0s;
}

.spinner .rect4 {
    -webkit-animation-delay: -0.9s;
    animation-delay: -0.9s;
}

.spinner .rect5 {
    -webkit-animation-delay: -0.8s;
    animation-delay: -0.8s;
}

.chart__base-box__inner.search_result_target .modal_trigger__member-name {
    background:#FFD9D7;
}
</style>