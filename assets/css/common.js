jQuery(function ($) {
    /*-------------------------------------------*/
    /*  トップページバナーのスライダー（slick slider）
    /*-------------------------------------------*/
    window.addEventListener('load', function() {
        var maxSliderHeight = 0;
        $('.bnr-list__detail').each(function(idx, elem) {
            var sliderHeight = $(elem).height();
            if(maxSliderHeight < sliderHeight) {
            maxSliderHeight = sliderHeight;
            }
        });
        $('.bnr-list__detail').height(maxSliderHeight);
    });

    $('.bnr-list').slick({
        infinite: true,
        autoplay: true,
        slidesToScroll: 1,
        adaptiveHeight: true,
        arrows: true,
        dots: true,
        centerMode: true,
        //centerPadding: '100px',
        slidesToShow: 3,
        pauseOnHover: true,
        responsive: [
            {
            breakpoint: 768,
            settings: {
                slidesToShow: 2,
            }
            },
            {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
            }
            },
        ]
    });

    /*-------------------------------------------*/
    /*  ページ内リンク
    /*-------------------------------------------*/
    var siteHeader = $('#site-header').outerHeight() + 40;

    // ページ遷移時ページ内リンク
    $(document).ready(function(){
        //URLのハッシュ値を取得
        var urlHash = location.hash;
        //ハッシュ値があればページ内スクロール
        if(urlHash) {
            hashposi = $(urlHash).offset().top;
            hashposi = hashposi - siteHeader;
            setTimeout(function () {
            //ロード時の処理を待ち、時間差でスクロール実行
            $('body,html').animate({scrollTop:hashposi}, 200, 'swing');
            }, 100);
        }
    });

    $('a[href^="#"]').on('click', function() {
        if (!$(this).parent('li').hasClass('nav-item')) {
            var speed = 300;
            var href = $(this).attr("href");
            var target = $(href == "#" || href == "" ? 'html' : href);
            var positionTarget = target.offset().top;
            var position = positionTarget - siteHeader;
            $('body,html').animate({scrollTop:position}, speed, 'swing');
          // return false;
        }
    });

    /*-------------------------------------------*/
    /*  モーダル
    /*-------------------------------------------*/
    // モーダルのボタンをクリックした時
    $(document).on('click','.modal_trigger', function(){
      let postId = $(this).data('id');
      let category = $(this).data('category');
      let title = $(this).data('title');
      $(this).next('.modal_box').fadeIn(); // モーダルを表示する
    });

    // ×やモーダルの背景をクリックした時
    $(document).on('click','.modal_close , .modal_bg', function(){
      $('.modal_box').fadeOut(); // モーダルを非表示にする
    });

    /*-------------------------------------------*/
    /*  アコーディオン
    /*-------------------------------------------*/
    // $(function (){
    //   $('.accordion-content').addClass('active');
      $('.menu').on('click', function(){
        $(this).toggleClass('opend');
        // $('.accordion-content').addClass('active');
        $(this).next('.accordion-content').slideToggle();
      });
    // });

    // TODO:
    /*-------------------------------------------*/
    /*  組織図
    /*-------------------------------------------*/
    // 「続きを見る」クリックで次の3階層表示
    $('.chart-branch__hierarchy__next').on('click', function(){
      $(this).next('.chart-branch__hierarchy__next__content').fadeIn();
    });

    /*-------------------------------------------*/
    /*  商品検索の色を変える
    /*-------------------------------------------*/
    $(document).on('click','.category-btn', function(){
      var checkState = $(this).children('input').prop("checked");
      if (checkState) {
        $(this).addClass('category-btn__checked');
      } else {
        $(this).removeClass('category-btn__checked');
      }
    });

    /*-------------------------------------------*/
    /*  パスワードを表示
    /*-------------------------------------------*/
    $(document).on('click', '#visible-button', function(){
      $(this).toggleClass('visible');
      if ($(this).hasClass('visible')) {
          $('input#login_password').attr('type', 'text');
          $(this).text('非表示');
      } else {
          $('input#login_password').attr('type', 'password');
          $(this).text('表示');
      }
    });

    /*-------------------------------------------*/
    /*  カレンダー
    /*-------------------------------------------*/
    $(function () {
      if ( $('body').hasClass('home') ) {
        var today = new Date();
        var year = today.getFullYear();
        var month = today.getMonth()+1;
        var day = today.getDate();
        fulltoday = year+'-'+month+'-'+day;
        eventdata = [];

        // 祝日取得
        calender_holidays = [];
        var url = happyMembersUrl+'/assets/api/v1/common/calender_holidays.php';
        $.ajaxSetup({async: false}); //同期通信(json取ってくるまで待つ)
        $.getJSON(url, function(data){
            calender_holidays = data;
        });
        $.ajaxSetup({async: true});

        showCalendar(year,month);
        atoday = pickADate(fulltoday);
        setEventDetail(atoday);
      }
    });

    // ポップアップ
    $(document).on('click','.cancel-btn',function(){
        $(this).parents('.pop-up-child').toggleClass('opened');
    });

    // カレンダー
    const weeks = ['月', '火', '水', '木', '金', '土', '日'];
    const date = new Date();
    let year = date.getFullYear();
    let month = date.getMonth() + 1;
    const config = {
        show: 3,
    }

    // カレンダー設定
    function showCalendar(year, month) {
        const calendarHtml = createCalendar(year, month);
        const sec = document.createElement('section');
        sec.innerHTML = calendarHtml;
        document.querySelector('.calendar-show').appendChild(sec);
        get_calender_events(year,month);
    }

    // カレンダー作成
    function createCalendar(year, month) {
        const startDate = new Date(year, month - 1, 1) // 月の最初の日を取得
        const endDate = new Date(year, month,  0) // 月の最後の日を取得
        const endDayCount = endDate.getDate() // 月の末日
        const lastMonthEndDate = new Date(year, month - 1, 0) // 前月の最後の日の情報
        const lastMonthendDayCount = lastMonthEndDate.getDate() // 前月の末日
        let startDay = startDate.getDay()-1 // 月の最初の日の曜日を取得
        if(startDay == -1){
            startDay = 6; // 月の最初の日の曜日を取得
        }
        today = new Date();
        day = today.getDate(); //今日の曜日を取得
        minmonth = today.getMonth()-1; //先々月判定
        thismonth = today.getMonth()+1; //今月判定
        maxmonth = today.getMonth()+3; //3ヶ月後判定
        let dayCount = 1 // 日にちのカウント
        let calendarHtml = '' // HTMLを組み立てる変数
        calendarHtml += '<div class="d-flex flex-row justify-content-center align-items-center">';
        if(month > minmonth){
            calendarHtml += '<i id="c_prev" class="fas fa-chevron-left cursor-pointer prev-mont d-flex align-items-center"></i>';
        }
        calendarHtml += '<p id="c_year" class="pl-5 pr-2 mb-0">'+year+'年</p><h5 id="c_month" class="bold my-1 pr-5">' + month + '月</h5>';
        if(month < maxmonth){
            calendarHtml += '<i id="c_next" class="calendar-control fas fa-chevron-right cursor-pointer next-mont d-flex align-items-center"></i>';
        }
        calendarHtml += '</div>';
        calendarHtml += '<table class="table-calendar text-center bg-white mx-auto mt-3 mb-0" data-year="'+year+'" data-month="'+month+'">';
        // 曜日の行を作成
        for (let i = 0; i < weeks.length; i++) {
            calendarHtml += '<td class="week'+i+'">' + weeks[i] + '</td>'
        }
        for (let w = 0; w < 6; w++) {
            calendarHtml += '<tr>'
            for (let d = 0; d < 7; d++) {
                if (w == 0 && d < startDay) {
                    // 1行目で1日の曜日の
                    let num = lastMonthendDayCount - startDay + d + 1
                    calendarHtml += '<td class="is-disabled">' + num + '</td>'
                } else if (dayCount > endDayCount) {
                    // 末尾の日数を超えた
                    let num = dayCount - endDayCount
                    calendarHtml += '<td class="is-disabled">' + num + '</td>'
                    dayCount++
                } else if (dayCount == day && thismonth == month) {
                    calendarHtml += '<td class="c-date-pick today" data-date="'+dayCount+'"><span>' + dayCount + '</span></td>'
                    dayCount++
                } else {
                    var dateInfo = checkDate(year, month, dayCount);
                    if(dateInfo) {
                        calendarHtml += '<td class="c-date-pick holiday" data-date="'+dayCount+'"><span>' + dayCount + '</span></td>'
                    } else {
                        calendarHtml += '<td class="c-date-pick" data-date="'+dayCount+'"><span>' + dayCount + '</span></td>'
                    }
                    dayCount++
                }
            }
            calendarHtml += '</tr>'
        }
        calendarHtml += '</table>'
        return calendarHtml
    }

    // 祝日かどうかをチェック
    function checkDate(year, month, day) {
        var month = ( '00' + month ).slice( -2 );
        var day = ( '00' + day ).slice( -2 );
        var checkDate = year + '-' + month + '-' + day;
        return calender_holidays[checkDate];
    }

    // イベント情報取得
    function get_calender_events(year,month){
        c_url = happyMembersUrl+'/assets/api/v1/common/get_calender.php';
        if(year.length !== 0){
            // c_url = thermUrl+'/assets/api/v1/common/get_calender.php/?anu='+year+'&mont='+month;
            c_url = happyMembersUrl+'/assets/api/v1/common/get_calender.php/?anu='+year+'&mont='+month;
            // c_url = '/happymembers/cms/wp-content/themes/happy-members/assets/api/v1/common/get_calender.php/?anu='+year+'&mont='+month;
            // ★★★★★★
        }
        $.ajax({
            url: c_url,
            type: 'get',
            cache: false,
            dataType: 'json',
            data: null,
            async: false
        })
        .done(function (event) {
            setCalendarEvent(event);
            eventdata = event;
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest);
            console.log(textStatus);
            console.log(errorThrown);
        });
    }

    // カレンダーにイベント付与
    function setCalendarEvent(data){
        $.each(data, function(index, value) {
            var eventdate =  value.post_date;
            eventdate = pickADate(eventdate);
            $('td.c-date-pick[data-date="'+eventdate+'"]').addClass('hasevent');
        });
    }

    // イベントの詳細を付与
    function setEventDetail(pickeddate){
        document.getElementById("detail-box").innerHTML = "";
        i = 0;
        p_year = $('#c_year').text();
        p_year = p_year.slice( 0, -1 );
        p_month = $('#c_month').text();
        p_month = p_month.slice( 0, -1 );
        p_fulldate = p_year+'-'+p_month+'-'+pickeddate;
        getday = new Date(p_fulldate);
        dayOfWeek = getday.getDay() ;
        dayOfWeekStr = [ "日", "月", "火", "水", "木", "金", "土" ][dayOfWeek];

        $.each(eventdata, function(index, value) {
          original_date = value.post_date;
          eventdate = pickADate(original_date);
          if(eventdate == pickeddate){

            // ターム一覧
            var terms = value.post_terms;
            if (terms) {
              terms_list = '';
              terms.forEach(function(term) {
                terms_list += '<div class="article-detail__category '+term.slug+'">'+term.name+'</span></div>';
              });
            }

            // イベント一覧
            item = '<article class="modal_trigger">';
            if (value.post_thumbnail) {
              item += '<div class="article-image">'+value.post_thumbnail+'</div>'
            }
            item += '<div class="article-content"><div class="article-detail">';
            if (terms) {
              item += terms_list;
            }
            item += '<div class="article-title"><p>'+value.post_title+'</p></div>';
            item += '</div></div>';

            item += '</article>';
            // イベント詳細（ポップアップ用）
            item += '<div class="modal_box">';

              item += '<div class="modal_bg"></div>';

              item += '<div class="modal_inner">';

                item += '<div class="modal_block">';

                  item += '<div class="event-content">';
                    item += '<div class="event-content__detail">';
                      item += '<div class="event-content__detail__header">';
                        if (terms) {
                          item += terms_list;
                        }
                        item += '<div class="article-title"><p>'+value.post_title+'</p></div>';
                      item += '</div>';
                      item += '<table>';
                        item += '<tbody>';
                          if (value.event_date) {
                          item += '<tr>';
                            item += '<td>開催日</td>';
                            item += '<td>'+value.event_date+'</td>';
                          item += '</tr>';
                          }
                          if(value.event_time) {
                          item += '<tr>';
                            item += '<td>開催時間</td>';
                            item += '<td>'+value.event_time+'</td>';
                          item += '</tr>';
                          }
                          if (value.post_status) {
                          item += '<tr>';
                            item += '<td>開催状況</td>';
                            item += '<td><span class="status-'+Object.keys(value.post_status)+'">'+Object.values(value.post_status)+'</span></td>';
                          item += '</tr>';
                          }
                          if (value.post_meeting_division) {
                          item += '<tr>';
                            item += '<td>会合区分</td>';
                            item += '<td>'+value.post_meeting_division+'</td>';
                          item += '</tr>';
                          }
                          if (value.prefectures) {
                          item += '<tr>';
                            item += '<td>都道府県</td>';
                            item += '<td>'+value.prefectures+'</td>';
                          item += '</tr>';
                          }
                          if (value.municipalities) {
                          item += '<tr>';
                            item += '<td>市町村</td>';
                            item += '<td>'+value.municipalities+'</td>';
                          item += '</tr>';
                          }
                          if (value.event_place) {
                          item += '<tr>';
                            item += '<td>開催場所</td>';
                            item += '<td>'+value.event_place+'</td>';
                          item += '</tr>';
                          }
                          if (value.event_speaker) {
                          item += '<tr>';
                            item += '<td>スピーカー</td>';
                            item += '<td>'+value.event_speaker+'</td>';
                          item += '</tr>';
                          }
                          if (value.meeting_name) {
                          item += '<tr>';
                            item += '<td>会合名</td>';
                            item += '<td>'+value.meeting_name+'</td>';
                          item += '</tr>';
                          }
                          if (value.event_note) {
                          item += '<tr>';
                            item += '<td>会合名</td>';
                            item += '<td>'+value.event_note+'</td>';
                          item += '</tr>';
                          }
                        item += '</tbody>';
                      item += '</table>';

                  item += '</div>'; // .event-content

                item += '</div>'; // .modal_block

                if (value.post_thumbnail) {
                  item += '<div class="event-image">';
                    item += '<div class="article-image">'+value.post_thumbnail+'</div>'
                  item += '</div>';
                }

                item += '<div class="modal_close"><div class="black-btn">閉じる<span>×</span></div>';

              item += '</div>'; // .modal_inner

            item += '</div>'; // .modal_box

            $("#detail-box").append(item);
            i++;
          }
        });
        if(i == 0){
            item =
            "<div class=\"detail-box mt-2\">\n<div class=\"title-box d-flex flex-column mx-3 p-2\">\n<p class=\"\">\u4E88\u5B9A\u306F\u3042\u308A\u307E\u305B\u3093\u3002</p>\n</div>\n</div>";
            // `<div class="detail-box mt-2">
            //     <div class="title-box d-flex flex-column mx-3 p-2">
            //         <p class="">予定はありません。</p>
            //     </div>
            // </div>`;
            document.getElementById("detail-box").innerHTML = item;
        }
    }

    // 0を除いた日付を取得
    function pickADate(date){
        adate = date.slice(-2);
        var firstletter = adate.slice(0,1) ;
        if(firstletter == 0){
            var adate = adate.slice(-1);
        }
        return adate;
    }

    // イベント表示
    $(document).on('click','td.c-date-pick.hasevent,td.c-date-pick.today',function(){
        pickedyear = $('.table-calendar').data('year');
        pickedmonth = $('.table-calendar').data('month');
        pickeddate = $(this).data('date');
        var Weeks = [ "日", "月", "火", "水", "木", "金", "土" ];
        var dObj = new Date( pickedyear+'/'+pickedmonth+'/'+pickeddate );
        var wDay = dObj.getDay();

        setEventDetail(pickeddate);
        var setDay = pickedmonth+'<span>月</span>'+pickeddate+'<span>日</span><span>（'+Weeks[wDay]+'）</span>';
        var articleDate = document.getElementById('calender-date');
        articleDate.innerHTML = setDay;
    });


    // 月送り
    $(document).on('click','.prev-mont,.next-mont',function(){
        p_year = $('#c_year').text();
        p_year = p_year.slice( 0, -1 );
        p_month = $('#c_month').text();
        p_month = p_month.slice( 0, -1 );
        controll = $(this).attr("id");
        if (controll === 'c_prev') {
            p_month--
            if (p_month < 1) {
                p_year--
                p_month = 12
            }
        }
        if (controll === 'c_next') {
            p_month++
            if (p_month > 12) {
                p_year++
                p_month = 1
            }
        }
        $('.calendar-show').empty();
        showCalendar(p_year,p_month);
    });

    /*-------------------------------------------*/
    /*   イベント・インフォメーション もっと見るボタン
    /*-------------------------------------------*/
      // 現在表示されている数
      var event_now_post = 3; // イベント
      var info_now_post = 3; // インフォメーション
      var product_now_post = 10; // 商品

      // 一度に取得する数
      var get_post_num = 3;

      $('.more_disp').on('click', function() {
        var button = $(this);
        var post_type =  $(this).children('button').data('post');
        var data_postnum =  $(this).children('button').data('postnum');
        button.css('pointer-events','none');

        var now_post_num = 0;
        if (post_type == "event") {
          var ajax_url = happyMembersUrl+'/assets/api/v1/common/event-readmore.php';
          now_post_num = event_now_post;
          if (data_postnum) {
            get_post_num = data_postnum - event_now_post;
          }
        } else if (post_type == "information") {
          var ajax_url = happyMembersUrl+'/assets/api/v1/common/info-readmore.php';
          now_post_num = info_now_post;
          if (data_postnum) {
            get_post_num = data_postnum - info_now_post;
          }
        } else if (post_type == "product") {
          var ajax_url = happyMembersUrl+'/assets/api/v1/common/product-readmore.php';
          now_post_num = product_now_post;
          get_post_num = 10;
          if (data_postnum) {
            get_post_num = data_postnum - product_now_post;
          }
        }

        $.ajax({
          type: 'POST',
          url: ajax_url,
          data: {
              'now_post_num': now_post_num,
              'get_post_num': get_post_num,
          },
          dataType: 'html',
        })
        .done(function(data){
          now_post_num = now_post_num + get_post_num;

          if (post_type == "event") {
            event_now_post = now_post_num;
            button.before(data);
          } else if (post_type == "information") {
            info_now_post = now_post_num;
            button.before(data);
          } else if (post_type == "product") {
            product_now_post = now_post_num;
            $('.product-list').append(data);
          }
          button.css('pointer-events','auto');
        })
        .fail(function(){ // ajax通信成失敗の処理
          console.log('エラーが発生しました');
        })
        return false;
      });

      // .fail(function (XMLHttpRequest, textStatus, errorThrown) {
      //   console.log(XMLHttpRequest);
      //   console.log(textStatus);
      //   console.log(errorThrown);
      //   return false;
      // });

    /*-------------------------------------------*/
    /*  商品検索
    /*-------------------------------------------*/
      // const ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';

      var search_criteria = getParam('search_criteria');
      var all_member = $('#all-member-category').attr('value');
      var happy_member = $('#happy-category').attr('value');
      var excellent_member = $('#excellent-category').attr('value');
      var all_product = $('#all-product-category').attr('value');
      var all_array = [all_member, all_product];

      // 【検索】ボタンを押した時
      $(document).on('click','#btn-search', function() {
          var checkedCategories = [];
          var cat = '';
          $('.prod_search:checked').each(function(index, element){
              cat = $(element).val();
              checkedCategories.push(cat);
              console.log(index);
              console.log(cat);
          });
          str = checkedCategories.join();
          location.href = homeUrl+'/product/?search_criteria=' +  encodeURIComponent(str);
      });

      if (search_criteria) {
          search_criteria = search_criteria.split(',');
          search_criteria.forEach(function(value) {
              categoryChecked(value);
          });
      } else {
        memberAllCheck();
        productAllCheck();
      }

      // 検索条件：取引区分　制御
      $('.member_category .prod_search').change(function() {
          var check = $(this).prop('checked');
          //チェックあり -> それ以外のチェックを外す
          if(check) {
              categoryCansell(all_member);
              categoryCansell(happy_member);
              categoryCansell(excellent_member);
              categoryChecked($(this).val());
          //チェックなし -> 全てにチェック
          } else {
              categoryChecked(all_member);
          }
      });

      // var productNum = $(".product_category .prod_search").length - 1;
      $('.product_category .prod_search').on('change', function() {
          var check = $(this).prop('checked');
          // チェックされているチェックボックスの数
          var productCheckNum = $(".product_category .prod_search:checked").length;
          if(check) {
              var checkValue =  $(this).val();
              if (checkValue === all_product) {
                  productAllCheck();
              // } else if (productCheckNum === productNum) {
              //     productAllCheck();
              } else {
                  categoryCansell(all_product);
              }
          } else {
              if(productCheckNum <= 0) {
                  categoryChecked(all_product);
              }
          }
      });

      $(document).on('click', '#btn-clear-conditions', function(){
          memberAllCheck();
          productAllCheck();
      });

      // URLパラメータを取得
      function getParam(name, url) {
          if (!url) url = window.location.href;
          name = name.replace(/[\[\]]/g, "\\$&");
          var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
              results = regex.exec(url);
          if (!results) return null;
          if (!results[2]) return '';
          return decodeURIComponent(results[2].replace(/\+/g, " "));
      }

      // チェックをつける
      function categoryChecked(value) {
          var checkbox = $('input[value="'+value+'"]');
          checkbox.prop('checked', true);
          checkbox.parent('.category-btn').addClass('category-btn__checked');
      }

      // チェックを外す
      function categoryCansell(value) {
          var checkbox = $('input[value="'+value+'"]');
          checkbox.prop('checked', false);
          checkbox.parent('.category-btn').removeClass('category-btn__checked');
      }

      // 商品選択のチェックを「すべて」のみにする
      function memberAllCheck() {
          $('.member_category .prod_search').prop('checked', false);
          $('.member_category .category-btn').removeClass('category-btn__checked');
          categoryChecked(all_member);
      }

      // 商品カテゴリーのチェックを「すべて」のみにする
      function productAllCheck() {
          $('.product_category .prod_search').prop('checked', false);
          $('.product_category .category-btn').removeClass('category-btn__checked');
          categoryChecked(all_product);
      }
});
