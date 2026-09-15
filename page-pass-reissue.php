<div id="page-login" class="page-web-registration pass-reissue">
<?php
/*
 * Template Post Type: page
 * Template Name: パスワード再発行のご依頼
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
if (isset($_POST['btn_confirm'])) {
    $error = confirm_reissue_pass();
}
get_header(); ?>
    <div class="mod-body">
        <div class="page-title">
            <h1 class="page-header_pageTitle"><?php the_title(); ?></h1>
            <div class="content">
                <div class="page-note">
                    <p>ご登録されている情報をご入力ください。<br>ご登録のメールアドレスへパスワード再発行メールをお送りします。</p>
                </div>
            </div>
        </div>
        <div class="content">
            <form class="form-content" name="reissue-pass_form" id="reissue-pass_form"  method="post">
                <div>
                    <p class="form-item"><label>ログインID</label><span class="require">必須</span></p>
                    <div class="form-input">
                        <input type="text" name="login_id" size="60" placeholder="英語小文字・半角数字6~30文字で入力" id="login_id"
                            <?php if (isset($_SESSION['temp']['login_id'])) :?>
                                value="<?php echo $_SESSION['temp']['login_id'] ?>"
                            <?php endif;?>
                        >
                        <div id="error-login" class="error-message">
                            <?php if (isset($error['login_id'])) :?>
                                <?php echo $error['login_id']; ?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>氏名</label><span class="require">必須</span></p>
                    <div class="form-input">
                        <input type="text" name="mbr_nm" size="60" placeholder="（例）山田太郎"
                            <?php if (isset($_SESSION['temp']['mbr_nm'])) :?>
                                    value="<?php echo $_SESSION['temp']['mbr_nm'] ?>"
                            <?php endif;?>
                        >
                        <div id="error-mbr_nm" class="error-message">
                            <?php if (isset($error['mbr_nm'])) :?>
                                <?php echo $error['mbr_nm']; ?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>フリガナ</label><span class="require">必須</span></p>
                    <div class="form-input">
                        <input type="text" name="mbr_knm" size="60" placeholder="（例）ﾔﾏﾀﾞﾀﾛｳ"
                            <?php if (isset($_SESSION['temp']['mbr_knm'])) :?>
                                    value="<?php echo $_SESSION['temp']['mbr_knm'] ?>"
                            <?php endif;?>
                        >
                        <div id="error-kana" class="error-message">
                            <?php if (isset($error['mbr_knm'])) :?>
                                <?php echo $error['mbr_knm']; ?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>生年月日</label><span class="require">必須</span></p>
                    <div class="form-input">
                        <div class="birthday">
                            <div class="birthday__date">
                                <input type="text" name="year_birth" class="year" placeholder="（例）2021"
                                    <?php if (isset($_SESSION['temp']['year_birth'])) :?>
                                        value="<?php echo $_SESSION['temp']['year_birth'] ?>"
                                    <?php endif;?>
                                >
                                <span>年<span>
                            </div>
                            <div class="birthday__day">
                                <select name="month_birth">
                                    <?php if ($_SESSION['temp']['month_birth']) :?>
                                        <option value="<?php echo $_SESSION['temp']['month_birth'] ?>">
                                        <?php echo $_SESSION['temp']['month_birth'] ?></option>
                                    <?php endif;?>
                                    <option value="">選択</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                                <span>月<span>
                            </div>
                            <div class="birthday__day">
                                <select name="day_birth">
                                    <?php if ($_SESSION['temp']['day_birth']) :?>
                                        <option value="<?php echo $_SESSION['temp']['day_birth'] ?>">
                                        <?php echo $_SESSION['temp']['day_birth'] ?></option>
                                    <?php endif;?>
                                    <option value="">選択</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                    <option value="31">31</option>
                                </select>
                                <span>日<span>
                            </div>
                        </div>
                        <div id="error-birth" class="error-message">
                            <?php if (isset($error['mbr_bth'])) :?>
                                <?php echo $error['mbr_bth']; ?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>メールアドレス<br></label><span class="require">必須</span></p>
                    <div class="form-input">
                        <input type="text" name="mail" size="60" placeholder="（例）example@happy.jp"
                            <?php if (isset($_SESSION['temp']['mail'])) :?>
                                    value="<?php echo $_SESSION['temp']['mail'] ?>"
                            <?php endif;?>
                        >
                        <div id="error-mail" class="error-message">
                            <?php if (isset($error['mail'])) :?>
                                <?php echo $error['mail']; ?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>メールアドレス<br>（確認用）</label><span class="require">必須</span></p>
                    <div class="form-input">
                        <input type="text" name="confirmation_mail" size="60" placeholder="（例）example@happy.jp"
                            <?php if (isset($_SESSION['temp']['confirmation_mail'])) :?>
                                    value="<?php echo $_SESSION['temp']['confirmation_mail'] ?>"
                            <?php endif;?>
                        >
                        <div id="error-confirmation_mail" class="error-message">
                            <?php if (isset($error['confirmation_mail'])) :?>
                                <?php echo $error['confirmation_mail']; ?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <!-- 西岡さんへ：エラーメッセージ追加しました -->
                <?php if (isset($error['member_id'])) :?>
                    <div  class="error-message"><?php echo $error['member_id']; ?></div>
                <?php endif;?>
                <div class="btn-list">
                    <button class="black-btn" type="submit" name="btn_confirm" value="reissue-pass" id="btn-submit">
                        入力内容を確認する
                    </button>
                    <a href="<?php echo site_url(); ?>/login/">
                        <div class="white-btn">
                            ログイン画面へ戻る
                        </div>
                    </a>
                </div>
                <?php wp_nonce_field('reissue_pass_action', 'reissue_pass_nonce');  //nonceフィールド設置?>
            </form>
        </div>
    </div>
</div>
<div class="before-login">
    <?php get_footer(); ?>
</div>

<style>
html {
    padding-bottom: 0 !important;
}
body {
    margin: 0 !important;
}
</style>