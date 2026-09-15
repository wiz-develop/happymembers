<div id="page-login" class="page-web-registration">
<?php
/*
 * Template Post Type: page
 * Template Name: WEB会員登録
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
// require_once("cms/wp-content/themes/happy-members/functions.php");
if (isset($_POST['btn_confirm'])) {
    $error = confirm_signup();
}
get_header();?>
    <div class="mod-body">
        <div class="page-title">
            <h1 class="page-header_pageTitle"><?php the_title(); ?></h1>
            <div class="content">
                <div class="page-note">
                    <p>初めて会員登録を行う方はこちらの手順をご確認ください。</p>
                    <a href="<?php echo home_url(); ?>/web-registration/procedure/">
                        <div class="procedure-link">
                            手順を確認する<span>></span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="content">
            <form class="form-content" name="signup_form" id="signup_form"  method="post">
                <div>
                    <p class="form-item"><label>ログインID</label><span class="require">必須</span></p>
                    <div class="form-input">
                        <input type="text" name="login_id" size="60" placeholder="英語小文字・半角数字6~30文字で入力" id="login_id"
                            <?php if (isset($_SESSION['sign_up']['login_id'])) :?>
                                value="<?php echo $_SESSION['sign_up']['login_id'] ?>"
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
                    <p class="form-item"><label>パスワード</label><span class="require">必須</span></p>
                    <div class="form-input">
                        <input type="text" name="pass" size="60" placeholder="英語小文字・半角数字・記号8~16文字で入力"
                            <?php if (isset($_SESSION['sign_up']['pass'])) :?>
                                value="<?php echo $_SESSION['sign_up']['pass'] ?>"
                            <?php endif;?>
                        >
                        <div id="error-pass" class="error-message">
                            <?php if (isset($error['pass'])) :?>
                                <?php echo $error['pass']; ?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>パスワード<br>（確認用）</label><span class="require">必須</span></p>
                    <div class="form-input">
                        <input type="text" name="confirmation_pass" size="60" placeholder="英語小文字・半角数字・記号8~16文字で入力"
                            <?php if (isset($_SESSION['sign_up']['confirmation_pass'])) :?>
                                value="<?php echo $_SESSION['sign_up']['confirmation_pass'] ?>"
                            <?php endif;?>
                        >
                        <div id="error-pass" class="error-message">
                            <?php if (isset($error['confirmation_pass'])) :?>
                                <?php echo $error['confirmation_pass']; ?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>会員ID</label><span class="require">必須</span></p>
                    <div class="form-input">
                        <div class="form-input__id">
                            <label>ハッピー会員</label>
                            <input type="text" name="happy_id" size="60" placeholder="6桁の数字で入力"
                                <?php if (isset($_SESSION['sign_up']['happy_id'])) :?>
                                        value="<?php echo $_SESSION['sign_up']['happy_id'] ?>"
                                <?php endif;?>
                            >
                        </div>
                        <div id="error-id" class="error-message">
                            <?php if (isset($error['happy_id'])) :?>
                                <?php echo $error['happy_id']; ?>
                            <?php endif;?>
                        </div>
                        <div class="form-input__id" style="margin-bottom: 0;">
                            <label>エクセレント会員</label>
                            <input type="text" name="excellent_id" size="60" placeholder="6桁の数字で入力"
                                <?php if (isset($_SESSION['sign_up']['excellent_id'])) :?>
                                        value="<?php echo $_SESSION['sign_up']['excellent_id'] ?>"
                                <?php endif;?>
                            >
                        </div>
                        <div id="error-id" class="error-message">
                            <?php if (isset($error['excellent_id'])) :?>
                                <?php echo $error['excellent_id']; ?>
                            <?php endif;?>
                        </div>
                        <div id="error-id" class="error-message">
                            <?php if (isset($error['error_both'])) :?>
                                <?php echo $error['error_both']; ?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="form-item"><label>氏名</label><span class="require">必須</span></p>
                    <div class="form-input">
                        <input type="text" name="mbr_nm" size="60" placeholder="（例）山田太郎"
                            <?php if (isset($_SESSION['sign_up']['mbr_nm'])) :?>
                                    value="<?php echo $_SESSION['sign_up']['mbr_nm'] ?>"
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
                            <?php if (isset($_SESSION['sign_up']['mbr_knm'])) :?>
                                    value="<?php echo $_SESSION['sign_up']['mbr_knm'] ?>"
                            <?php endif;?>
                        >
                        <div id="error-mbr_knm" class="error-message">
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
                                    <?php if (isset($_SESSION['sign_up']['year_birth'])) :?>
                                        value="<?php echo $_SESSION['sign_up']['year_birth'] ?>"
                                    <?php endif;?>
                                >
                                <span>年<span>
                            </div>
                            <div class="birthday__day">
                                <select name="month_birth">
                                    <?php if ($_SESSION['sign_up']['month_birth']) :?>
                                        <option value="<?php echo $_SESSION['sign_up']['month_birth'] ?>">
                                        <?php echo $_SESSION['sign_up']['month_birth'] ?></option>
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
                                    <?php if ($_SESSION['sign_up']['day_birth']) :?>
                                        <option value="<?php echo $_SESSION['sign_up']['day_birth'] ?>">
                                        <?php echo $_SESSION['sign_up']['day_birth'] ?></option>
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
                        <input type="email" id="registration-mail" name="mail" size="60" placeholder="（例）example@happy.jp" autocomplete="email"
                            <?php if (isset($_SESSION['sign_up']['mail'])) :?>
                                    value="<?php echo $_SESSION['sign_up']['mail'] ?>"
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
                        <input type="email" id="registration-mail-confirm" name="confirmation_mail" size="60" placeholder="（例）example@happy.jp" autocomplete="email"
                            <?php if (isset($_SESSION['sign_up']['confirmation_mail'])) :?>
                                    value="<?php echo $_SESSION['sign_up']['confirmation_mail'] ?>"
                            <?php endif;?>
                        >
                        <datalist id="email-list-confirm">
                            <?php echo $emailList; ?>
                        </datalist>
                        <div id="error-confirmation_mail" class="error-message">
                            <?php if (isset($error['confirmation_mail'])) :?>
                                <?php echo $error['confirmation_mail']; ?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <div class="mail-receive d-block bg-white border border-danger rounded p-3">
                    <p id="test-mail-btn" class="btn btn-primary d-inline-block">メール受信確認（事前テスト送信）</p>
                    <div id="test-mail-result" class="font-weight-normal"></div>
                    <p class="d-block w-100 font-weight-normal mb-4">入力したメールアドレスにハッピーファミリーからメールが届かない場合、<span class="d-inline-block">以下の理由が考えられます。</span></p>
                    <ol class="ml-0 mb-0 pl-2 pl-md-0">
                        <li>入力したメールアドレスが間違っている。受信フォルダーの容量を超えている。</li>
                        <li>迷惑メールフォルダーに迷惑メールとして判断されてメールが届いている。</li>
                        <li>
                            URLリンク規制（http://www.～/ 記述のあるメールを受信拒否）や<span class="d-inline-block">ドメイン認証規制（携帯アドレス以外のメール受信拒否）設定になっている。</span><br>
                            この場合は、規制を解除設定するか、他のメールアドレスに変えてください。
                        </li>
                    </ol>
                </div>
                <div class="privacy">
                    <p class="text-center">プライバシーポリシー、個人情報の取り扱いをご確認の上、<br>
                        「同意する」にチェックしてください。</p>
                    <p class="text-left text-md-center mb-4 p-md-3 mt-0">
                        WEB会員登録で入力頂きました、個人情報はお問合せなどのほか、<br>
                        当社が取り扱う各種商品・サービス等の情報提供（ダイレクトメール・<span class="d-inline-block">電子メール・</span><span class="d-inline-block">メールマガジン）に</span><span class="d-inline-block">利用させていただきます。</span>
                    </p>
                    <details>
                        <summary>プライバシーポリシー</summary>
                        <div class="policy-detail policy-bg">
                            <div class="mod-about"><p class="mt-0 pb-0">ハッピーファミリー株式会社（以下，「当社」といいます。）は，本ウェブサイト上で提供するサービス（以下,「本サービス」といいます。）における，ユーザーの個人情報の取扱いについて，以下のとおりプライバシーポリシー（以下，「本ポリシー」といいます。）を定めます。</p></div>
                            <div class="mod-body">
                                <section>
                                    <h2>第1条（個人情報）</h2>
                                    <p class="mt-0 pb-0">「個人情報」とは，個人情報保護法にいう「個人情報」を指すものとし，生存する個人に関する情報であって，当該情報に含まれる氏名，生年月日，住所，電話番号，連絡先その他の記述等により特定の個人を識別できる情報及び容貌，指紋，声紋にかかるデータ，及び健康保険証の保険者番号などの当該情報単体から特定の個人を識別できる情報（個人識別情報）を指します。</p>
                                </section>

                                <section>
                                    <h2>第2条（個人情報の収集方法）</h2>
                                    <p class="mt-0 pb-0">当社は，ユーザーが利用登録をする際に氏名，生年月日，住所，電話番号，メールアドレスなどの個人情報をお尋ねすることがあります。また，ユーザーと提携先などとの間でなされたユーザーの個人情報を含む取引記録や決済に関する情報を,当社の提携先（情報提供元，広告主，広告配信先などを含みます。以下，｢提携先｣といいます。）などから収集することがあります。</p>
                                </section>

                                <section>
                                    <h2>第3条（個人情報を収集・利用する目的）</h2>
                                    <p class="mt-0 pb-0">当社が個人情報を収集・利用する目的は，以下のとおりです。</p>
                                    <ol>
                                        <li>当社サービスの提供・運営のため</li>
                                        <li>ユーザーからのお問い合わせに回答するため（本人確認を行うことを含む）</li>
                                        <li>ユーザーが利用中のサービスの新機能，更新情報，キャンペーン等及び当社が提供する他のサービスの案内のメールを送付するため</li>
                                        <li>メンテナンス，重要なお知らせなど必要に応じたご連絡のため</li>
                                        <li>利用規約に違反したユーザーや，不正・不当な目的でサービスを利用しようとするユーザーの特定をし，ご利用をお断りするため</li>
                                        <li>有料サービスにおいて，ユーザーに利用料金を請求するため</li>
                                        <li>上記の利用目的に付随する目的</li>
                                    </ol>
                                </section>

                                <section>
                                    <h2>第4条（利用目的の変更）</h2>
                                    <ol>
                                        <li>当社は，利用目的が変更前と関連性を有すると合理的に認められる場合に限り，個人情報の利用目的を変更するものとします。</li>
                                        <li>利用目的の変更を行った場合には，変更後の目的について，当社所定の方法により，ユーザーに通知し，または本ウェブサイト上に公表するものとします。</li>
                                    </ol>
                                </section>

                                <section>
                                <h2>第5条（個人情報の第三者提供）</h2>
                                <ol>
                                    <li>当社は，次に掲げる場合を除いて，あらかじめユーザーの同意を得ることなく，第三者に個人情報を提供することはありません。ただし，個人情報保護法その他の法令で認められる場合を除きます。
                                        <ol>
                                            <li>人の生命，身体または財産の保護のために必要がある場合であって，本人の同意を得ることが困難であるとき</li>
                                            <li>公衆衛生の向上または児童の健全な育成の推進のために特に必要がある場合であって，本人の同意を得ることが困難であるとき</li>
                                            <li>国の機関もしくは地方公共団体またはその委託を受けた者が法令の定める事務を遂行することに対して協力する必要がある場合であって，本人の同意を得ることにより当該事務の遂行に支障を及ぼすおそれがあるとき</li>
                                            <li>予め次の事項を告知あるいは公表し，かつ当社が個人情報保護委員会に届出をしたとき
                                                <ol>
                                                <li>利用目的に第三者への提供を含むこと</li>
                                                <li>第三者に提供されるデータの項目</li>
                                                <li>第三者への提供の手段または方法</li>
                                                <li>本人の求めに応じて個人情報の第三者への提供を停止すること</li>
                                                <li>本人の求めを受け付ける方法</li>
                                                </ol>
                                            </li>
                                        </ol>
                                    </li>
                                    <li>前項の定めにかかわらず，次に掲げる場合には，当該情報の提供先は第三者に該当しないものとします。
                                        <ol>
                                            <li>当社が利用目的の達成に必要な範囲内において個人情報の取扱いの全部または一部を委託する場合</li>
                                            <li>合併その他の事由による事業の承継に伴って個人情報が提供される場合</li>
                                            <li>個人情報を特定の者との間で共同して利用する場合であって，その旨並びに共同して利用される個人情報の項目，共同して利用する者の範囲，利用する者の利用目的および当該個人情報の管理について責任を有する者の氏名または名称について，あらかじめ本人に通知し，または本人が容易に知り得る状態に置いた場合</li>
                                        </ol>
                                    </li>
                                </ol>
                                </section>

                                <section>
                                <h2>第6条（個人情報の開示）</h2>
                                <ol>
                                    <li>当社は，本人から個人情報の開示を求められたときは，本人に対し，遅滞なくこれを開示します。ただし，開示することにより次のいずれかに該当する場合は，その全部または一部を開示しないこともあり，開示しない決定をした場合には，その旨を遅滞なく通知します。なお，個人情報の開示に際しては，1件あたり1，000円の手数料を申し受けます。
                                        <ol>
                                            <li>本人または第三者の生命，身体，財産その他の権利利益を害するおそれがある場合</li>
                                            <li>当社の業務の適正な実施に著しい支障を及ぼすおそれがある場合</li>
                                            <li>その他法令に違反することとなる場合</li>
                                        </ol>
                                    </li>
                                    <li>前項の定めにかかわらず，履歴情報および特性情報などの個人情報以外の情報については，原則として開示いたしません。</li>
                                </ol>
                                </section>

                                <section>
                                    <h2>第7条（個人情報の訂正および削除）</h2>
                                    <ol>
                                        <li>ユーザーは，当社の保有する自己の個人情報が誤った情報である場合には，当社が定める手続きにより，当社に対して個人情報の訂正，追加または削除（以下，「訂正等」といいます。）を請求することができます。</li>
                                        <li>当社は，ユーザーから前項の請求を受けてその請求に応じる必要があると判断した場合には，遅滞なく，当該個人情報の訂正等を行うものとします。</li>
                                        <li>当社は，前項の規定に基づき訂正等を行った場合，または訂正等を行わない旨の決定をしたときは遅滞なく，これをユーザーに通知します。</li>
                                    </ol>
                                </section>

                                <section>
                                    <h2>第8条（個人情報の利用停止等）</h2>
                                    <ol>
                                        <li>当社は，本人から，個人情報が，利用目的の範囲を超えて取り扱われているという理由，または不正の手段により取得されたものであるという理由により，その利用の停止または消去（以下，「利用停止等」といいます。）を求められた場合には，遅滞なく必要な調査を行います。</li>
                                        <li>前項の調査結果に基づき，その請求に応じる必要があると判断した場合には，遅滞なく，当該個人情報の利用停止等を行います。</li>
                                        <li>当社は，前項の規定に基づき利用停止等を行った場合，または利用停止等を行わない旨の決定をしたときは，遅滞なく，これをユーザーに通知します。</li>
                                        <li>前2項にかかわらず，利用停止等に多額の費用を有する場合その他利用停止等を行うことが困難な場合であって，ユーザーの権利利益を保護するために必要なこれに代わるべき措置をとれる場合は，この代替策を講じるものとします。</li>
                                    </ol>
                                </section>

                                <section>
                                <h2>第9条（プライバシーポリシーの変更）</h2>
                                <ol>
                                    <li>本ポリシーの内容は，法令その他本ポリシーに別段の定めのある事項を除いて，ユーザーに通知することなく，変更することができるものとします。</li>
                                    <li>当社が別途定める場合を除いて，変更後のプライバシーポリシーは，本ウェブサイトに掲載したときから効力を生じるものとします。</li>
                                </ol>
                                </section>

                                <section>
                                    <h2>第10条（お問い合わせ窓口）</h2>
                                    <p class="mt-0 pb-0">本ポリシーに関するお問い合わせは，下記の窓口までお願いいたします。</p>
                                    <p class="mt-0 pb-0">住所：〒532-0003 大阪市淀川区宮原2-14-14<br>ハッピーファミリー株式会社<br>電話番号：0120-198-141</p>
                                </section><p class="mt-0 pb-0" style="text-align: right;">以上</p></div></div>
                    </details>
                </div>
                <div class="agree">
                    <div class="form-input">
                        <input type="checkbox" name="agree" value="agree">プライバシーポリシーの内容を確認し同意します。
                        <div id="error-agree" class="error-message">
                            <?php if (isset($error['agree'])) :?>
                                <?php echo $error['agree']; ?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <div class="btn-list">
                    <button class="black-btn" type="submit" name="btn_confirm" value="signup" id="btn-submit">
                        入力内容を確認する
                    </button>
                    <a href="<?php echo get_home_url(); ?>/login/">
                        <div class="white-btn">
                            ログイン画面へ戻る
                        </div>
                    </a>
                </div>
                <?php wp_nonce_field('sign_up_action', 'sign_up_nonce');  //nonceフィールド設置?>
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

<script>
    jQuery(function($){
        const $errorLogin = document.getElementById('error-login');
        $('input#login').on('input', function(){
            const $login = $("#login").val();
            console.log($login);
            // if (!$login.match(/[^A-Za-z0-9]+/)
        });

        var testMailBtn = $('#test-mail-btn');
        var testMailResult = document.getElementById('test-mail-result');
        var keyName = 'testmail_sended';
        $('#test-mail-btn').on('click', function() {
            testMailResult.innerHTML = '<p>送信中です…</p>';
            testMailBtn.addClass('click-disable');
            var mail = document.getElementById('registration-mail').value;

            if (!mail) {
                testMailResult.innerHTML = '<p>メールアドレスを入力してください。</p>';
            } else if (!mail.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/)) {
                testMailResult.innerHTML = '<p>送信に失敗しました。<br class="d-none d-md-block">メールアドレスが間違っていないかご確認いただき、再度テスト送信を行ってください。</p>';
            } else {
                const test_url = happyMembersUrl+'/assets/api/v1/common/send-testmail.php';
                $.ajax({
                    url: test_url,
                    type: 'POST',
                    dataType: 'text',
                    data: {
                        'mail': mail,
                    },
                })
                .done(function (data) {
                    if (data = 'success') {
                        sessionStorage.setItem(keyName, true);
                        testMailResult.innerHTML = '<p>送信しました。入力したメールアドレスに<br class="d-none d-md-block">ハッピーファミリーからメールが届いているか確認を行って下さい。</p>';
                    } else if (data = 'fail') {
                        testMailResult.innerHTML = '<p>送信に失敗しました。再度テスト送信を行ってください。</p>';
                        testMailBtn.removeClass('click-disable');
                    }
                }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
                    testMailResult.innerHTML = '<p>送信に失敗しました。再度テスト送信を行ってください。</p>';
                    testMailBtn.addClass('click-disable');
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
            }
        });

        $('#registration-mail').change(function() {
            sessionStorage.removeItem(keyName);
            testMailBtn.removeClass('click-disable');
            testMailResult.innerHTML = '';
        })

        if (sessionStorage.getItem(keyName, true)) {
            testMailBtn.addClass('click-disable');
        } else {
            testMailBtn.removeClass('click-disable');
        }
    })
</script>