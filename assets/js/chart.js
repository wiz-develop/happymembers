
jQuery(function($){
    /**********************************************
    *  固定値 ※stylesheetで数値を変えたらここも変更する
    ***********************************************/
    const boxWidth = 100;
    const boxHeight = 70;
    const spaceSizeForBinary = boxWidth * 5.6;
    const leftBox = -1;
    const rightBox = 1;
    const getDirectoryNumForIntroducerEx = 1; // Max2まで
    const getDirectoryNumForIntroducerHp = 2;
    const happyId = $('#page-member-info-chart').data('happy-id');
    const excellentId = $('#page-member-info-chart').data('excellent-id');
    /**********************************************
    *  イベント
    ***********************************************/
    /*-------------------------------------------*/
    /*  ロード時
    /*-------------------------------------------*/
    brId = '001';
    initial = true;
    isClicked = false;
    axisLevel = 1;

    cacheLineObj = $("<div></div>");

    // ハッピーID保持者：デフォルトでハッピー紹介者の組織図が表示
    // エクセレントIDのみ保持者：デフォルトでエクセレント紹介者の組織図が表示
    if (happyId) {
        $('input#introducer-happy').prop('checked', true);
        getIntroducerHpJs(happyId, axisLevel);
    } else {
        if (excellentId) {
            $('input#introducer-excellent').prop('checked', true);
            getIntroducerExJs(excellentId, brId, axisLevel);
            $('#you-ex-introducer').removeClass('first_view');
        }
    }

    /*-------------------------------------------*/
    /*  タブクリック時　
    /*  ※ ブランチ選択プルダウンを表示。（エクセレント）
    /*  ※ クリックされたタブの組織図が未表示であれば、表示処理を行う。
    /*-------------------------------------------*/
    // ハッピー紹介者
    $('#introducer-happy').on('click', function() {
        // ブランチ切り替え
        $('.chart__search__select__branch.ex-introducer').addClass('display-none');
        $('.chart__search__select__branch.ex-binary').addClass('display-none');
        // あなたに戻る
        $('.chart__search__function__back.default_function').removeClass('display-none');
        $('.chart__search__function__back.for_binary_function').addClass('display-none');
    })

    // エクセレント紹介者
    $('#introducer-excellent').on('click', function() {
        // ブランチ切り替え
        $('.chart__search__select__branch.ex-introducer').removeClass('display-none');
        $('.chart__search__select__branch.ex-binary').addClass('display-none');
        // あなたに戻る
        $('.chart__search__function__back.default_function').removeClass('display-none');
        $('.chart__search__function__back.for_binary_function').addClass('display-none');
        isFirstViewEx = $('#you-ex-introducer').hasClass('first_view');

        if (isFirstViewEx) {
            getIntroducerExJs(excellentId, brId);
            $('#you-ex-introducer').removeClass('first_view');
        }
    })

    // エクセレントバイナリ
    $('#excellent-binary').on('click', function() {
        // ブランチ切り替え
        $('.chart__search__select__branch.ex-introducer').addClass('display-none');
        $('.chart__search__select__branch.ex-binary').removeClass('display-none');
        // あなたに戻る
        $('.chart__search__function__back.default_function').addClass('display-none');
        $('.chart__search__function__back.for_binary_function').removeClass('display-none');
        isFirstViewBi = $('#you-ex-binary').hasClass('first_view');

        if (isFirstViewBi) {
            getBinaryExJs(excellentId, brId);
            $('#you-ex-binary').removeClass('first_view');
        }
    })

    /*-------------------------------------------*/
    /*  「つづき」ボタンクリック時の処理　紹介者 HAPPY
    /*-------------------------------------------*/
    $('.chart__base-box.hp-introducer').on('click', '.btn-display', function() {
        $(this).find('.btn-display__text').css('display', 'none');
        let axisHpId = $(this).parent('.chart__base-box__inner.hp-introducer').data('my_id');
        let axisLevel = $(this).parent('.chart__base-box__inner.hp-introducer').data('level');
        getIntroducerHpJs(axisHpId, axisLevel);
    })

    /*-------------------------------------------*/
    /*  「つづき」ボタンクリック時の処理　紹介者 EXCELLENT
    /*-------------------------------------------*/
    $('.chart__base-box.ex-introducer').on('click', '.btn-display', function() {
        $(this).find('.btn-display__text').css('display', 'none');
        let axisExId = $(this).parent('.chart__base-box__inner.ex-introducer').data('my_id');
        let axisBr =  $(this).parent('.chart__base-box__inner.ex-introducer').data('my_branch');
        let axisLevel = $(this).parent('.chart__base-box__inner.hp-introducer').data('level');
        getIntroducerExJs(axisExId, axisBr, axisLevel);
    })

    // /*-------------------------------------------*/
    // /*  過去：「つづき」ボタンクリック時の処理　バイナリ
    // /*-------------------------------------------*/
    // $('.chart__base-box.ex-binary').on('click', '.btn-display', function() {
    //     $(this).css('display', 'none');
    //     let axisExId = $(this).parent('.chart__base-box__inner.ex-binary').data('my_id');
    //     let axisBr = $(this).parent('.chart__base-box__inner.ex-binary').data('my_branch');
    //     let axisPositionX = $(this).parent('.chart__base-box__inner.ex-binary').position().left;

    //     $('.chart__base-box__inner.ex-binary').each(function(index, el) {
    //         let positionX = $(el).position().left;
    //         if (axisPositionX !== positionX) {
    //             if (axisPositionX > positionX) {
    //                 //クリックされた会員OBJより左にある会員OBJ
    //                 newPosition = positionX - ((spaceSizeForBinary - boxWidth) / 2);
    //                 $(el).css('left', `${newPosition}px`);
    //             } else {
    //                 //クリックされた会員OBJより右にある会員OBJ
    //                 newPosition = positionX + ((spaceSizeForBinary - boxWidth) / 2);
    //                 $(el).css('left', `${newPosition}px`);
    //             }
    //         }
    //         cacheLine('ex-bi', $(el).data('my_id'), $(el).data('my_branch'));
    //     })
    //     getBinaryExJs(axisExId, axisBr, false);
    // })

    /*-------------------------------------------*/
    /*  「つづき」ボタンクリック時の処理　バイナリ　２
    /*-------------------------------------------*/
    $('.chart__base-box.ex-binary').on('click', '.btn-display', function() {
        $(this).css('display', 'none');
        let axisExId = $(this).parents('.chart__base-box__inner.ex-binary').data('my_id');
        let axisBr = $(this).parents('.chart__base-box__inner.ex-binary').data('my_branch');
        let status = $(this).parents('.chart__base-box__inner.ex-binary').data('status');
        let axisName = $(this).parents('.chart__base-box__inner.ex-binary').data('name');
        let axisKana = $(this).parents('.chart__base-box__inner.ex-binary').data('kana');

        // 表示されているバイナリ組織図を一度削除
        $('.chart__base-box__inner.ex-binary').remove();
        $('.content_excellent-binary .connector-line-box').remove();

        // ステイタスクラス決定
        let statusClass = 'withdrawal';
        if (status === 0) {
            statusClass = 'active';
        }
        if (status === 1) {
            statusClass = 'dormant';
        }

        let introducerBoxYou =
        `
        <div class="chart__base-box__inner ex-binary you" style="left: 0px; top: 0px;" id="ex-bi-${axisExId}${axisBr}"
            data-my_id="${axisExId}" data-my_branch="${axisBr}" data-level="1" data-kana="${axisKana}">
            <div class="modal_trigger">
                <div class="modal_trigger__member-img ${statusClass}">
                    <img class="img-mbr" src="${thermeUrl}/assets/images/common/member-icon_w.png">
                </div>
                <div class="modal_trigger__member-name">${axisName}</div>
            </div>
            <div class="btn-display" id="you-ex-binary">
                <div class="btn-display__text" style="display: none">つづき</div>
            </div>
        </div>
        `

        $(`#base-box-binary`).append(introducerBoxYou);
        getBinaryExJs(axisExId, axisBr, true);
    })


    /*-------------------------------------------*/
    /*  ブランチの切り替え時の処理　紹介者 EXCELLENT
    /*-------------------------------------------*/
    $('.chart__search__select__branch').on('change', '.select.ex-introducer', function(){
        let branchId = $(this).val();
        let axisLevel = 1;
        $('.chart__base-box__inner.ex-introducer').remove();
        $('.content_introducer-excellent .connector-line-box').remove();

        let introducerBoxYou =
        `
        <div class="chart__base-box__inner ex-introducer you" style="left: 0px; top: 0px;" id="ex-${excellentId}${branchId}"
            data-my_id="${excellentId}" data-my_branch="${branchId}" data-level="1">
            <div class="modal_trigger chart-user">
                <div class="modal_trigger__member-img chart-user__icon">
                    <img class="img-mbr" src="${thermeUrl}/assets/images/chart/you-icon.png">
                </div>
                <div class="modal_trigger__member-name">あなた</div>
            </div>
            <div class="btn-display" id="you-ex-binary">
                <div class="btn-display__text" style="display: none">表示する</div>
            </div>
        </div>
        `
        $(`#base-box-ex`).append(introducerBoxYou);
        getIntroducerExJs(excellentId, branchId, axisLevel);
    });

    /*-------------------------------------------*/
    /*  ブランチの切り替え時の処理　バイナリ
    /*-------------------------------------------*/
    $('.chart__search__select__branch').on('change', '.select.ex-binary', function(){
        let branchId = $(this).val();
        $('.chart__base-box__inner.ex-binary').remove();
        $('.content_excellent-binary .connector-line-box').remove();

        let introducerBoxYou =
        `
        <div class="chart__base-box__inner ex-binary you" style="left: 0px; top: 0px;" id="ex-bi-${excellentId}${branchId}"
            data-my_id="${excellentId}" data-my_branch="${branchId}" data-level="1">
            <div class="modal_trigger chart-user">
                <div class="modal_trigger__member-img chart-user__icon">
                    <img class="img-mbr" src="${thermeUrl}/assets/images/chart/you-icon.png">
                </div>
                <div class="modal_trigger__member-name">あなた</div>
            </div>
            <div class="btn-display" id="you-ex-binary">
                <div class="btn-display__text" style="display: none">表示する</div>
            </div>
        </div>
        `

        $(`#base-box-binary`).append(introducerBoxYou);
        getBinaryExJs(excellentId, branchId, true);
    });

    /*-------------------------------------------*/
    /*  モーダルトリガー クリック
    /*-------------------------------------------*/
    // 人間アイコンクリック時
    $('.chart__base-box').on('click', '.modal_trigger', function(){

        // 二重クリック防止
        if (isClicked) {
            return;
        }
        isClicked = true;

        let selectedMemberId = $(this).parent('.chart__base-box__inner').data('my_id');
        let selectedMemberBr = $(this).parent('.chart__base-box__inner').data('my_branch');
        let memberType = $(this).parents('.chart__base-box').data('member_type');
        let timeType = $('[name=time]').val();

        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            cache: false,
            data: {
                'action' : 'get_member_detail',
                'mbr_id' : selectedMemberId,
                'my_branch' : selectedMemberBr,
                'member_type' : memberType,
                'time_type' : timeType,
            },
            dataType : "json"
        }).done(function(data) {
            // console.log(data);
            dataShapingModal(data, selectedMemberId, selectedMemberBr);
            isClicked = false;
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            console.log("function       : get_member_detail");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
            isClicked = false;
        });
    });

    /*-------------------------------------------*/
    /*  × モーダル 背景をクリック時
    /*-------------------------------------------*/
    $(document).on('click','.modal_close, .modal_bg', function(){
            $('.modal_box').fadeOut(); // モーダルを非表示にする
            $('div#modal-content').remove(); // モーダル削除
    });

    /********************************************** APIでデータ取得 ***********************************************/
    /*-------------------------------------------*/
    /*  HAPPY 組織図(紹介者) データ取得
    /*-------------------------------------------*/
    function getIntroducerHpJs(targetHpId, axisLevel) {
        showLoading('.flame-body');
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            cache: false,
            data: {
                'action' : 'get_introducer_hp',
                'hp_id' : targetHpId,
                'get_num' : 2,
            },
            dataType : "json"
        }).done(function(data) {
            // console.log(data)
            // axisId = targetHpId;
            console.log(data);
            treeDiagramIntroducer(data, axisLevel);
            drawLine();
            hideLoading();
            onFocusObj($('#' + data.chart_type + '-' + targetHpId));
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            console.log("function       : get_introducer_hp");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
            hideLoading();
        });
    }
    /*-------------------------------------------*/
    /*  EXCELLENT 組織図(紹介者) データ取得
    /*-------------------------------------------*/
    function getIntroducerExJs(targetExId, targetBr) {

        // showLoading('.flame-body');
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            cache: false,
            data: {
                'action' : 'get_introducer_ex',
                'ex_id' : targetExId,
                'br_id' : targetBr,
                'get_num' : getDirectoryNumForIntroducerEx,
            },
            dataType : "json"
        }).done(function(data) {
            // var arr = JSON.parse(JSON.stringify(data));
            // var obj = JSON.parse(data);
            // console.log(data);
            treeDiagramIntroducer(data, axisLevel);
            drawLine();
            hideLoading();
            axisId = targetExId+targetBr;
            onFocusObj($('#' + data.chart_type + '-' + axisId));
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            console.log("function       : get_introducer_ex");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
            hideLoading();
        });
    }
    /*-------------------------------------------*/
    /*  EXCELLENT 組織図(バイナリ) データ取得
    /*-------------------------------------------*/
    function getBinaryExJs(targetExId, targetBrId) {

        showLoading('.flame-body');
        $.ajax({
            type: 'GET',
            url: ajaxUrl,
            cache: false,
            data: {
                'action' : 'get_binary_ex',
                'ex_id' : targetExId,
                'br_id' : targetBrId,
            },
            dataType : "json"
        }).done(function(data) {
            // var arr = JSON.parse(JSON.stringify(data));
            // var obj = JSON.parse(data);
            // console.log(data);
            axisId = targetExId + ""+ targetBrId;
            treeDiagramBinary(data, axisId);
            drawLine();
            hideLoading();
            onFocusObj($('#' + data.chart_type + '-' + axisId));
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            console.log("function       : get_binary_ex");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
            hideLoading();
        });
    }
    /********************************************** 紹介者 ***********************************************/
    /*-------------------------------------------*/
    /*  紹介者　組織図の更新
    /*-------------------------------------------*/
    // chart__base-box__inner を作成してappendする
    // 次階層の子供の整形を子供がいなくなるまで繰り返し行う。
    function treeDiagramIntroducer(data, axisLevel) {
        // 紹介者 ハッピー OR エクセレントの判別
        const chartType = data.chart_type;

        $.when(
            // HTMLの作成 & append
            inputIntroducer(data, axisLevel)
        ).done(function(e){
            let grandChildDataList = [];
            // 次階層のデータを整形
            if (data.directory) {
                data.directory.forEach(function(childData) {
                    let grandChildData = {};
                    if (childData.directory) {
                        grandChildData.axis_id = childData.directory[0].p_id_br; // axis_idでOK
                        grandChildData.chart_type = chartType;
                        grandChildData.level = data.level + 1;
                        grandChildData.directory = childData.directory;
                        grandChildDataList.push(grandChildData);
                    }
                })
            }
            // 次階層に子供が存在すれば同じ処理を繰り返す(子供が存在する間回す)
            if (grandChildDataList.length) {
                grandChildDataList.forEach(function(data) {
                    if(data) {
                        treeDiagramIntroducer(data, axisLevel)
                    }
                })
            }
        });
        return;
    }
    /*-------------------------------------------*/
    /*  紹介者　BOX(人間アイコンのやつ)の作成、挿入
    /*-------------------------------------------*/
    function inputIntroducer(data, axisLevel) {
        const count = data.directory.length;
        const inputWidth = boxWidth * count;
        const axisId = data.axis_id; // 親のIdBr
        const chartType = data.chart_type;
        const parentPositionX = $(`#${chartType}-${axisId}`).position().left;
        const parentLevel = $(`#${chartType}-${axisId}`).data('level');
        const myLevel = parentLevel + 1;
        const basisPositionOfChildX = parentPositionX - (boxWidth * (count - 1)) / 2;

        // console.log(parentLevel);
        // console.log(myLevel);
        // console.log(axisLevel + getDirectoryNumForIntroducerHp);
        // console.log((axisLevel+ getDirectoryNumForIntroducerHp) === myLevel);

        $.when(
            keepSpaceForIntroducerBox(inputWidth, parentPositionX, chartType)
        )
        .done(function(introducer_box) {
            for (let i = 0; i < count; i++) {
                let directoryData = data.directory[i]
                let myName = directoryData.c_nm;
                let myNameKana = directoryData.c_knm;
                let myIdBranch = directoryData.c_id_br;
                let myId = directoryData.c_id;
                let myBranch = directoryData.c_br;
                let parentIdBranch = directoryData.p_id_br;
                let parentId = directoryData.p_id;
                let parentBranch = directoryData.p_br;
                let status = directoryData.c_stat;
                let flagChild = directoryData.next;
                let display = 'none';
                // ポジション計算
                let positionX = basisPositionOfChildX + (boxWidth * i);
                let positionY = boxHeight * myLevel;
                // ステイタスクラス決定
                let statusClass = 'withdrawal';
                if (status === 0) {
                    statusClass = 'active';
                }
                if (status === 1) {
                    statusClass = 'dormant';
                }
                // DOM作成
                let introducer_box = '';
                if (chartType === 'hp') {
                    // 子供の有無
                    if ((axisLevel + getDirectoryNumForIntroducerHp) === myLevel && flagChild){
                        display = 'block';
                    }
                    introducer_box =
                    `
                    <div class="chart__base-box__inner ${chartType}-introducer" style="left: ${positionX}px; top: ${positionY}px;" id="${chartType}-${myId}"
                    data-my_id="${myId}" data-div_id="hp-${parentIdBranch}" data-parent_id="${parentId}" data-level="${myLevel}" data-kana="${myNameKana}">
                        <div class="modal_trigger">
                            <div class="modal_trigger__member-img ${statusClass}">
                                <img class="img-mbr" src="${thermeUrl}/assets/images/common/member-icon_w.png"">
                            </div>
                            <div class="modal_trigger__member-name">${myName}</div>
                        </div>
                        <div class="btn-display">
                            <div class="btn-display__text" style="display: ${display}">つづき</div>
                        </div>
                    </div>`;
                }

                if (chartType === 'ex') {
                    // 子供の有無
                    if ((axisLevel + getDirectoryNumForIntroducerEx) === myLevel && flagChild && myId === excellentId) {
                        if (flagChild) {
                            display = 'block';
                        }
                    }
                    introducer_box =
                    `
                    <div class="chart__base-box__inner ${chartType}-introducer child" style="left: ${positionX}px; top: ${positionY}px;" id="${chartType}-${myIdBranch}"
                    data-my_id="${myId}" data-my_branch="${myBranch}" data-div_id="ex-${parentId}" data-parent_id="${parentId}" data-parent_branch="${parentBranch}" data-level="${myLevel}" data-kana="${myNameKana}">
                        <div class="modal_trigger">
                            <div class="modal_trigger__member-img ${statusClass}">
                                <img class="img-mbr" src="${thermeUrl}/assets/images/common/member-icon_w.png"">
                            </div>
                            <div class="modal_trigger__member-name">${myName}</div>
                        </div>
                        <div class="btn-display">
                            <div class="btn-display__text" style="display: ${display}">つづき</div>
                        </div>
                    </div>`;
                }

                $(`#base-box-${chartType}`).append(introducer_box);
                cacheLine(chartType, myId, myBranch);
            }
        })
        .fail(function() {
            // エラーがあった時
            console.log('error');
        });
        return true;
    }

    /*-------------------------------------------*/
    /*  紹介者　APPENDするBOX分スペースを開ける
    /*-------------------------------------------*/
    function keepSpaceForIntroducerBox(inputWidth, parentPositionX, chartType) {
        if (inputWidth === boxWidth) {
            return;
        }
        $(`.chart__base-box__inner.${chartType}-introducer`).each(function(index, el) {
            positionX = $(el).position().left;
            if (parentPositionX !== positionX) {
                if (parentPositionX > positionX) {
                    //クリックされた会員OBJより左にある会員OBJ
                    newPosition = positionX - (inputWidth / 2);
                    $(el).css('left', `${newPosition}px`);
                } else {
                    //クリックされた会員OBJより右にある会員OBJ
                    newPosition = positionX + (inputWidth / 2);
                    $(el).css('left', `${newPosition}px`);
                }
            }
            cacheLine(chartType, $(el).data('my_id'), $(el).data('my_branch'));
        })
        return;
    }


    /*-------------------------------------------*/
    /*  バイナリ　組織図の更新
    /*-------------------------------------------*/
    function treeDiagramBinary(data, initial) {
        const axisIdStr = data.axis_id; // クリックされたID
        const axisId = axisIdStr.replace("ex-bi-", "");

        if (data.directory) {
            // 二階層
            data.directory.forEach(dS => {
                if (dS.c_lr === 'L') {
                    parentPositionX = $(`#ex-bi-${axisId}`).position().left;
                    parentPositionY = $(`#ex-bi-${axisId}`).position().top;

                    resSecond = inputTreeBinary(1.2, leftBox, dS.c_id, dS.p_id, dS.c_nm, parentPositionX, parentPositionY, null, dS.c_stat, dS.c_br, dS.p_br, dS.c_knm, dS.c_knm)
                    secondX = resSecond.left;
                    secondY = resSecond.top;
                        // 三階層
                    if (dS.directory) {
                        dS.directory.forEach(dT => {
                            flagChild = false;
                            if (dT.c_lr === 'L') {
                                resThird = inputTreeBinary(0.6, leftBox, dT.c_id, dT.p_id, dT.c_nm, secondX, secondY, flagChild, dT.c_stat, dT.c_br, dT.p_br, dT.c_knm);
                                thirdX = resThird.left;
                                thirdY = resThird.top;
                                // 四階層目
                                if (dT.directory) {
                                    dT.directory.forEach(dF => {
                                        if (dF.next_l || dF.next_r) {
                                            flagChild = true;
                                        } else {
                                            flagChild = false;
                                        }
                                        if (dF.c_lr === 'L') {
                                            inputTreeBinary(0.3, leftBox, dF.c_id, dF.p_id, dF.c_nm, thirdX, thirdY, flagChild, dF.c_stat, dF.c_br, dF.p_br, dF.c_knm);
                                        } else if(dF.c_lr === 'R'){
                                            inputTreeBinary(0.3, rightBox, dF.c_id, dF.p_id, dF.c_nm, thirdX, thirdY, flagChild, dF.c_stat, dF.c_br, dF.p_br, dF.c_knm);
                                        }
                                    })
                                }
                            } else if (dT.c_lr === 'R'){
                                resThird = inputTreeBinary(0.6, rightBox, dT.c_id, dT.p_id, dT.c_nm, secondX, secondY, flagChild, dT.c_stat, dT.c_br, dT.p_br, dT.c_knm);
                                thirdX = resThird.left;
                                thirdY = resThird.top;
                                // 四階層目
                                if (dT.directory) {
                                    dT.directory.forEach(dF => {
                                        if (dF.next_l || dF.next_r) {
                                            flagChild = true;
                                        } else {
                                            flagChild = false;
                                        }
                                        if (dF.c_lr === 'L') {
                                            inputTreeBinary(0.3, leftBox, dF.c_id, dF.p_id, dF.c_nm, thirdX, thirdY, flagChild, dF.c_stat, dF.c_br, dF.p_br, dF.c_knm);
                                        } else if(dF.c_lr === 'R'){
                                            inputTreeBinary(0.3, rightBox, dF.c_id, dF.p_id, dF.c_nm, thirdX, thirdY, flagChild, dF.c_stat, dF.c_br, dF.p_br, dF.c_knm);
                                        }
                                    })
                                }
                            }
                        })
                    }
                } else if(dS.c_lr === 'R') {
                    parentPositionX = $(`#ex-bi-${axisId}`).position().left;
                    parentPositionY = $(`#ex-bi-${axisId}`).position().top;
                    resSecond = inputTreeBinary(1.2, rightBox, dS.c_id, dS.p_id, dS.c_nm, parentPositionX, parentPositionY, null, dS.c_stat, dS.c_br ,dS.p_br, dS.c_knm)
                    secondX = resSecond.left;
                    secondY = resSecond.top;
                    // 三階層
                    if (dS.directory) {
                        dS.directory.forEach(dT => {
                            flagChild = false;
                            if (dT.c_lr === 'L') {
                                resThird = inputTreeBinary(0.6, leftBox, dT.c_id, dT.p_id, dT.c_nm, secondX, secondY, flagChild, dT.c_stat, dT.c_br, dT.p_br, dT.c_knm);
                                thirdX = resThird.left;
                                thirdY = resThird.top;
                                // 四階層目
                                if (dT.directory) {
                                    dT.directory.forEach(dF => {
                                        if (dF.next_l || dF.next_r) {
                                            flagChild = true;
                                        } else {
                                            flagChild = false;
                                        }
                                        if (dF.c_lr === 'L') {
                                            inputTreeBinary(0.3, leftBox, dF.c_id, dF.p_id, dF.c_nm, thirdX, thirdY, flagChild, dF.c_stat, dF.c_br, dF.p_br, dF.c_knm);
                                        } else if (dF.c_lr === 'R'){
                                            inputTreeBinary(0.3, rightBox, dF.c_id, dF.p_id, dF.c_nm, thirdX, thirdY, flagChild, dF.c_stat, dF.c_br, dF.p_br, dF.c_knm);
                                        }
                                    })
                                }
                            } else {
                                resThird = inputTreeBinary(0.6, rightBox, dT.c_id, dT.p_id, dT.c_nm, secondX, secondY, flagChild, dT.c_stat, dT.c_br, dT.p_br, dT.c_knm);
                                thirdX = resThird.left;
                                thirdY = resThird.top;
                                // 四階層目
                                if (dT.directory) {
                                    dT.directory.forEach(dF => {
                                        if (dF.next_l || dF.next_r) {
                                            flagChild = true;
                                        } else {
                                            flagChild = false;
                                        }
                                        if (dF.c_lr === 'L') {
                                            inputTreeBinary(0.3, leftBox, dF.c_id, dF.p_id, dF.c_nm, thirdX, thirdY, flagChild, dF.c_stat, dF.c_br, dF.p_br, dF.c_knm);
                                        } else if (dF.c_lr === 'R') {
                                            inputTreeBinary(0.3, rightBox, dF.c_id, dF.p_id, dF.c_nm, thirdX, thirdY, flagChild, dF.c_stat, dF.c_br, dF.p_br, dF.c_knm);
                                        }
                                    })
                                }
                            }
                        })
                    }
                }
            });
        }

    }

    /*-------------------------------------------*/
    /*  バイナリ　BOX(人間アイコンのやつ)の作成、挿入
    /*-------------------------------------------*/
    function inputTreeBinary(magnification, leftOrRight, myId, parentId, myName, parentPositionX, parentPositionY, flagChild, status, myBranch, parentBranch, myNameKana) {
        positionX = parentPositionX + (boxWidth * leftOrRight * magnification);
        positionY = parentPositionY + (boxHeight + 10);

        // ステイタスクラス決定
        let statusClass = 'withdrawal';
        if (status === 0) {
            statusClass = 'active';
        }
        if (status === 1) {
            statusClass = 'dormant';
        }
        // 子供の有無
        let display = 'none';
        if (flagChild) {
            display = 'block';
        }

        var mbr_box =
            `
            <div class="chart__base-box__inner ex-binary" style="left: ${positionX}px; top: ${positionY}px;" id="ex-bi-${myId}${myBranch}" data-my_id="${myId}" data-my_branch="${myBranch}"
                data-div_id="ex-bi-${parentId}${parentBranch}" data-parent_id="${parentId}" data-parent_branch="${parentBranch}"
                    data-name="${myName}" data-kana="${myNameKana}" data-status=${status}>
                <div class="modal_trigger">
                    <div class="modal_trigger__member-img ${statusClass}">
                        <img class="img-mbr" src="${thermeUrl}/assets/images/common/member-icon_w.png">
                    </div>
                    <div class="modal_trigger__member-name">${myName}</div>
                </div>
                <div class="btn-display">
                    <div class="btn-display__text" style="display: ${display}">つづき</div>
                </div>
            </div>`;

        $('#base-box-binary').append(mbr_box);
        cacheLine('ex-bi', myId, myBranch);

        position = { left :positionX, top: positionY}
        return position;
    }

    /*-------------------------------------------*/
    /*  会員間のコネクタラインHTML作成
    /*  親会員と子会員の間に罫線用のダミーdivを配置し、そのボーダーを着色（バイナリの場合は斜線描画）することでコネクタを表現
    /*-------------------------------------------*/
    function cacheLine(tab_type, child_id, child_branch) {
        let btn_display_height = 20; // 「つづき」ボタンの高さの固定値

        // コネクタ対象の子要素取得
        if (child_branch === undefined) {
            child_branch = "";
        }
        let childObj = $("#" + tab_type +"-" + child_id + child_branch);

        // コネクタ対象の親要素取得
        parent_branch = childObj.data('parent_branch');
        if (parent_branch === undefined) {
            parent_branch = "";
        }
        let parentObj = $("#" + tab_type +"-" + childObj.data('parent_id') + parent_branch);

        // 最上位会員の場合スキップ
        if (childObj.data('parent_id') === undefined){
            return true;
        }

        line_box_class = "";
        line_box_style = null;

        /** ***** START コネクタの位置・サイズ計算 ***** */
        // コネクタdiv 縦位置：親会員要素の直下
        let line_box_y = $(parentObj).position().top + $(parentObj).height();

        // コネクタdiv 横位置：(子会員要素または親要素の左側にあるほう）の中央
        let line_box_x = 0;
        if (childObj.position().left < $(parentObj).position().left) {
            line_box_x = childObj.position().left + (childObj.width() / 2);
        } else {
            line_box_x = $(parentObj).position().left + ($(parentObj).width() / 2);
        }

        // コネクタdiv 高さ：子会員の縦位置　- 親会員の直下
        let line_box_h = childObj.position().top - ($(parentObj).position().top + $(parentObj).height());

        // コネクタdiv 幅：(子会員要素または親要素の右側にあるほうの横位置）- (子会員要素または親要素の左側にあるほうの横位置）
        // 子が左
        let line_box_left_right_class = null;
        if (childObj.position().left < $(parentObj).position().left) {
            line_box_w = Math.abs($(parentObj).position().left - childObj.position().left);
            line_box_left_right_class = 'left';
        // 子が右
        } else if (childObj.position().left > $(parentObj).position().left) {
            line_box_w = Math.abs(childObj.position().left - $(parentObj).position().left);
            line_box_left_right_class = 'right';
        // 親・子で横位置同じ
        } else {
            line_box_w = 1;
            line_box_left_right_class = 'center';
        }
        /** ***** END コネクタの位置・サイズ計算 ***** */

        line_box_style = {
            top: line_box_y - btn_display_height, // ボタンの高さ分を考慮
            left: line_box_x,
            height: (btn_display_height + line_box_h) + "px", // ボタンの高さ分を考慮
            width: line_box_w + "px",
        }

        line_div_id = 'line_' + childObj.attr('id');

        // ライン要素が既に存在している場合、一旦削除(子要素の移動の場合を考慮）
        if ($('#' + line_div_id).length) {
            $('#' + line_div_id).remove();
        }
        // キャッシュ情報からも削除
        removeObj = $(cacheLineObj).find('#' + line_div_id);
        if (removeObj != null){
            removeObj.remove();
        }

        // ライン要素描画
        let line_div =
        $("<div>", {
            id:line_div_id,
            class:'connector-line-box rel_p_' + childObj.data('parent_id') + parent_branch + '_c_' + childObj.attr('id') + ' ' + line_box_left_right_class,
        })
        .append(
            $("<div>", {
                class:'upper-box',
            }),
            $("<div>", {
                class:'under-box',
            })
        );
        line_div.css(line_box_style);
//        $(childObj).parent().append( $(line_div).css(line_box_style));
        $(cacheLineObj).append(line_div);
//        line_div.prependTo('#base-box-hp');

        // スクロール幅調整
        scrollObj = childObj.parents('.chart_scroll_target');
        cal_width = Math.abs(line_box_style.left) * 2 + 400;
        if ($(scrollObj).width() < cal_width ) {
            // コンテンツエリア幅の初期値を退避
            if ($(scrollObj).data('base-width') == "") {
                $(scrollObj).data('base-width',$(scrollObj).width());
            }
            $(scrollObj).width(cal_width);
        }

    }
    /*-------------------------------------------*/
    /*  会員間のコネクタラインHTML描画
    /*-------------------------------------------*/
    function drawLine() {
        content_area = $('.content_' + $('input[name=tab_name]:checked').val());
        content_area.find('.chart__base-box').append( $(cacheLineObj).contents());
        $(cacheLineObj).empty();
    }


    /********************************************** モーダル ***********************************************/
    /*-------------------------------------------*/
    /*  モーダルデータ整形
    /*-------------------------------------------*/
    function dataShapingModal(obj, selectedMemberId, selectedMemberBr) {
        let userDetail = {};
        userDetail.mbr_id = selectedMemberId;
        // userDetail.mbr_id = obj.mbr_id;
        userDetail.mbr_nm = obj.mbr_nm;
        userDetail.co_nm = obj.co_nm;
        userDetail.mbr_kata = obj.mbr_kata;
        userDetail.pi_nm = obj.pi_nm;
        userDetail.memberType = obj.type
        userDetail.branch = selectedMemberBr;

        userDetail.status ='退会';
        userDetail.statusClass = 'withdrawal';
        if (obj.mbr_stat === 0) {
            userDetail.status ='Active';
            userDetail.statusClass = 'active';
        }
        if (obj.mbr_stat === 1) {
            userDetail.status ='休眠中';
            userDetail.statusClass = 'dormant';
        }

        // ハッピー・エクセレントで項目異なる。 // 0:ハッピー 1:エクセレント
        if (obj.type === 0) {
            userDetail.pos = obj.pos;
            userDetail.mbr_grd = obj.mbr_grd; //　取引利率
            userDetail.trkday = '';
            userDetail.you_mbr_id = $('#page-member-info-chart').data('happy-id');

            if (userDetail.status === 'Active') {
                userDetail.status = '';
            }
        }
        if (obj.type === 1) {
            userDetail.pos = '';
            userDetail.mbr_grd = '';
            userDetail.trkday = toDate(obj.trkday);
            userDetail.you_mbr_id = $('#page-member-info-chart').data('excellent-id');

            obj.br.forEach(br =>{
                if (br.br_id === selectedMemberBr) {
                    userDetail.branchRight = br.br_r;
                    userDetail.branchLeft = br.br_l;
                }
            })
        }
        // console.log(userDetail);
        inputModalContent(userDetail);
    }

    function toDate(intDay) {
        //データを文字列に変換
        var date_str = intDay.toString();

        //「yyyy年mm月dd日」形式の文字列を作成
        var ymd_str = date_str.slice(0,4) + "年";
        ymd_str += date_str.slice(4,6) + "月";
        ymd_str += date_str.slice(-2) + "日";

        return ymd_str;
    }

    /*-------------------------------------------*/
    /*  モーダルデータインプット作成・挿入 (ハッピー・エクセレント)
    /*-------------------------------------------*/
    function inputModalContent(userDetail) {

        var header =
            `
            <div class="modal_block" id="modal-content">
                <div class="modal-member-info">
                    <div class="${userDetail.statusClass}">
                        <img src="${thermeUrl}/assets/images/common/member-icon_w.png">
                    </div>
                    <span>${userDetail.mbr_nm}</span>
                </div>
                <table>`;

        // あなた（自分自身）ではない場合、ブランチIDと会員IDを表示しない
        var memberId = '';
        var memberBr ='';
        if (userDetail.mbr_id === userDetail.you_mbr_id) {
            var memberId =
            `<tr>
                <td>会員ID</td>
                <td>${userDetail.mbr_id}</td>
            </tr>`;
        }

        // エクセレント用
        if (userDetail.memberType === 1) {
            var memberBr =
            `<tr>
                <td>Br</td>
                <td>${userDetail.branch}</td>
            </tr>`;
        }

        // 法人に値がない場合、項目自体表示しない。
        var companyName = '';
        if (userDetail.co_nm) {
            var companyName =
                `
                <tr>
                    <td>法人名</td>
                    <td>${userDetail.co_nm}</td>
                </tr>
                <tr>
                    <td>肩書き</td>
                    <td>${userDetail.mbr_kata}</td>
                </tr>`;
        }

        // ハッピー用
        var transactionRate = '';
        if (userDetail.memberType === 0) {
            var transactionRate =
            ` <tr>
                <td>ポジション</td>
                <td>${userDetail.pos}</td>
            </tr>
            <tr>
                <td>取引利率</td>
                <td>${userDetail.mbr_grd}</td>
            </tr>`;
        }

        var commonContent =
            ` <tr>
                <td>登録日</td>
                <td>${userDetail.trkday}</td>
            </tr>
            <tr>
                <td>状況</td>
                <td>${userDetail.status}</td>
            </tr>
            <tr>
                <td>紹介者名</td>
                <td>${userDetail.pi_nm}</td>
            </tr>`;

        // エクセレント用
        var branchCount = ''
        if (userDetail.memberType === 1) {
            var branchCount =
            `<tr>
                <td>Active人数</td>
                <td>
                    <div>右　：　${userDetail.branchRight}人</div>
                    <div>左　：　${userDetail.branchLeft}人</div>
                </td>
            </tr>`;
        }

        const modalContent = ["",header, memberId, memberBr, companyName, transactionRate, commonContent, branchCount].join("");

        $('#modal-ex').append(modalContent)
        $('.modal_box').fadeIn(); // モーダルを表示する
        return;
    }

    /*-------------------------------------------*/
    /* バイナリ：「あなたに戻る」クリック時
    /*-------------------------------------------*/
    $('.chart__search__function__back.for_binary_function').on('click', function() {
        // $(this).css('display', 'none');
        let axisExId = $(this).parents('.chart__base-box__inner.ex-binary').data('my_id');
        let axisBr = $('.select.ex-binary').val();
        // let axisBr = '001'
        // let branchId = $(this).val();
        $('.chart__base-box__inner.ex-binary').remove();
        $('.content_excellent-binary .connector-line-box').remove();

        let introducerBoxYou =
        `
        <div class="chart__base-box__inner ex-binary you" style="left: 0px; top: 0px;" id="ex-bi-${excellentId}${axisBr}"
            data-my_id="${excellentId}" data-my_branch="${axisBr}" data-level="1">
            <div class="modal_trigger chart-user">
                <div class="modal_trigger__member-img chart-user__icon">
                    <img class="img-mbr" src="${thermeUrl}/assets/images/chart/you-icon.png">
                </div>
                <div class="modal_trigger__member-name">あなた</div>
            </div>
            <div class="btn-display" id="you-ex-binary">
                <div class="btn-display__text" style="display: none">表示する</div>
            </div>
        </div>
        `

        $(`#base-box-binary`).append(introducerBoxYou);
        getBinaryExJs(excellentId, axisBr, true);
    })

    // あなたに戻る 紹介者
    $('.chart__search__function__back.default_function').on('click', function() {
        content_area = $('.content_' + $('input[name=tab_name]:checked').val());
        member_you_obj = $(content_area).find('.you');
        onFocusObj(member_you_obj);
    })

    // 拡大
    $('.chart__search__function__zoom.zoom_in').on('click', function() {
        content_area = $('.content_' + $('input[name=tab_name]:checked').val());
        targetObj = $(content_area).find('.chart_scroll_target');
        if (parseInt($(targetObj).data("zoom")) >= 4) {
            return;
        }
        zoom = parseInt($(targetObj).data("zoom")) + 1;
        $(targetObj).data("zoom",zoom);
        $(targetObj).css("zoom",1 + (zoom / 10));
    })

    // 縮小
    $('.chart__search__function__zoom.zoom_out').on('click', function() {
        content_area = $('.content_' + $('input[name=tab_name]:checked').val());
        targetObj = $(content_area).find('.chart_scroll_target');
        if (parseInt($(targetObj).data("zoom")) <= -4) {
            return;
        }
        zoom = parseInt($(targetObj).data("zoom")) - 1;
        $(targetObj).data("zoom",zoom);
        $(targetObj).css("zoom",1 + (zoom / 10));
    })

    bef_search_condition = "";
    search_result = null;
    search_result_idx = null;
    // 検索
    $('.search-btn').on('click', function() {
        search_condition = $.trim($('.input-area.search').val());
        if (search_condition == "" ) {
            clearSearchResult();
            return;
        }

        content_area = $('.content_' + $('input[name=tab_name]:checked').val());

        if (search_condition != bef_search_condition ) {
            clearSearchResult();

            //ID検索・カナ検索
            search_result = $(content_area).find('.chart__base-box__inner[data-my_id="' + search_condition + '"],.chart__base-box__inner[data-kana*="' + search_condition + '"]');
            //名前検索
            if (search_result.length == 0) {
                search_result = $(content_area).find('.chart__base-box__inner .modal_trigger__member-name:contains("' + search_condition + '")' ) .parents('.chart__base-box__inner');
            }
            $(search_result).addClass('search_result_target');
            search_result_idx = 1;
            bef_search_condition = search_condition;
        } else {
            search_result_idx++;
        }
        //一順したら初めの要素に戻る
        if (search_result.length <= search_result_idx) {
            search_result_idx = 0;
        }

        if (search_result.length >= search_result_idx + 1) {
            onFocusObj(search_result.eq(-1 * search_result_idx));
        }

    })

    //検索結果のリセット
	function clearSearchResult(){
        content_area = $('.content_' + $('input[name=tab_name]:checked').val());
        $(content_area).find('.chart__base-box__inner').removeClass('search_result_target');

        search_result = null;
        search_result_idx = null;

    }

    //要素の中央表示
	function onFocusObj(element){
        content_area = $('.content_' + $('input[name=tab_name]:checked').val());

        scrollObj = $(content_area).find('.chart_scroll_target');
        // コンテンツエリア幅の初期値を退避
        if ($(scrollObj).data('base-width') == "") {
            $(scrollObj).data('base-width',$(scrollObj).width());
        }

        zoom = parseInt($(scrollObj).data("zoom"));
        zoom_rate = 1 + (zoom / 10);
        //横位置 = 対象会員の位置 - (コンテンツエリア幅の増分 / 2) + 対象会員要素の幅 / 2
        target_obj_left = $(element).position().left;
        base_width = $(scrollObj).data('base-width');
        now_width = $(scrollObj).width();
        scroll_left = target_obj_left + ((now_width - base_width) / 2) + ($(element).width() / 2);
//        alert(target_obj_left + ":::" + now_width + ":::" + base_width + ":::" + $(element).width() );

        //移動
        $(content_area).animate({
            scrollLeft: scroll_left * zoom_rate,
            scrollTop: $(element).position().top * zoom_rate,
        });
    }

    //ローディング表示
	function showLoading(selector){
        $(selector).append(
				"<div class=\"spinner\">\n<div class=\"rect1\"></div>\n<div class=\"rect2\"></div>\n<div class=\"rect3\"></div>\n<div class=\"rect4\"></div>\n<div class=\"rect5\"></div>\n</div>"
				// `<div class="spinner">
            //     <div class="rect1"></div>
            //     <div class="rect2"></div>
            //     <div class="rect3"></div>
            //     <div class="rect4"></div>
            //     <div class="rect5"></div>
            // </div>`
				);
    }
    //ローディング非表示
    function hideLoading(){
        $('.spinner').remove();
    }

    let id = 0;
    //デバッグ表示
	function debug(i){
        content_area = $('.content_' + $('input[name=tab_name]:checked').val());
        member_you_obj = $(content_area).find('.you');
        testObj = null;
        if (i == 1) {
            testObj = $('#test1');
        } else if (i == 2) {
            testObj = $('#test1');
        }


        scrollObj = $(content_area).find('.chart_scroll_target');
        $(testObj).append(
            '<div style="width: 400px;float: left;">' +
            ':::::' + (id++) +'<br>' +
            'base-width:' + $(scrollObj).data('base-width') +'<br>' +
            'width:' + $(scrollObj).width() +'<br>' +
            'scrollWidth:' + $(content_area).get(0).scrollWidth +'<br>' +
            "scrollLeft:" + $(content_area).get(0).scrollLeft +'<br>' +
            "clientWidth:" + $(scrollObj).get(0).clientWidth +'<br>' +
            "offsetWidth:" + $(scrollObj).get(0).offsetWidth +'<br>' +
            "member_you_obj.left:" + $(member_you_obj).position().left +'<br>' +
            "member_you_obj.offset:" + $(member_you_obj).offset().left +'<br>' +
            "member_you_obj.parent().position().left:" + $(member_you_obj).parent().position().left +'<br>' +
            "member_you_obj.parent().offset().left:" + $(member_you_obj).parent().offset().left +'<br>' +
            '</div>'
        );
    }

})
