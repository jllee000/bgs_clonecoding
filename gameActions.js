if (typeof JS_GAME_PATTERN === 'undefined') {
  JS_GAME_PATTERN = 'step';
}

let currentStep = 0;
let stepHistory = ['1-0'];

const pageTypeText = {
  intro: '인트로 페이지',
  result: '결과 페이지',
};

function progressStepBar(_$progress, _step) {
  _$progress.find('.progress .progress-inner').css({
    width: `${(currentStep * 100) / JS_GAME_STEP_MAX}%`,
  });

  let percent = ((_step * 100) / JS_GAME_STEP_MAX).toFixed(0) + '%';
  _$progress.find('.percen').html(percent);
}

const gameActions = {
  loading: false,
  begin_time_ms: 0,
  end_time_ms: 0,

  /**
   * 테스트 시작 버튼 onclick에 사용하세요.
   * @param {string} _renderType 'normal' | 'fade'
   * @returns article.game-wrap에 게임 플레이 화면(HTML) 랜더링
   */
  initGame: function (_renderType = 'normal') {
    const GA = this;
    const cookieCheck = cookieEnabledAlert(); // 쿠키 차단 alert

    if (!cookieCheck) {
      // 쿠키 차단시 진행 X
      return false;
    }

    //func.js
    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - 테스트 시작`,
      _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
      _label: `테스트 시작 버튼`,
    });

    //kakaoPixel
    kakaoPixel('6894544435604599766').pageView();
    kakaoPixel('6894544435604599766').viewContent({
      id: JS_GAME_IDX,
    });

    fbq('trackCustom', `${JS_GAME_TITLE} testStart`, {
      promotion: `${JS_GAME_TITLE} 테스트 시작 버튼`,
    });

    if (GA.loading) {
      return false;
    }

    GA.loading = true;

    let isValidInputs = true;
    const _data = {};
    $('input[data-ga="init"]').each(function (idx) {
      const fff = $(this).attr('name');
      const vvv = $(this).val();
      if (!vvv) {
        GA.loading = false;
        alert($(this).data('error'));
        $('input[name="' + fff + '"]').focus();
        isValidInputs = false;
        return false;
      }
      _data[fff] = vvv;
    });

    if (isValidInputs) {
      $.ajax({
        type: 'post',
        data: {
          proc: 'init',
          idx: JS_GAME_IDX,
          data: _data,
          csrf: JS_CSRF,
          pattern: JS_GAME_PATTERN,
          locale: userlocale,
        },
        url: '/gl/modules/api.php',
        cache: false,
        success: function (_htmlContent) {
          GA.loading = false;
          GA.begin_time_ms = new Date().getTime();
          currentStep = 1;
          func_logging(JS_GAME_IDX, 'start', JS_CSRF);
          $('article.game-intro').addClass('started');
          $('.app-header').toggle();
          if (typeof JS_GAME_FOOTER_AD !== 'undefined' && JS_GAME_FOOTER_AD === 'N') {
            //베이직 컨텐츠
            // console.log('JS_GAME_FOOTER_AD : ' + JS_GAME_FOOTER_AD);
            $('.progress-bottom').removeClass('display-none');
            $('.ads-banner-wrap').addClass('display-none');
          } else {
            //프리미엄 컨텐츠
            // console.log('JS_GAME_FOOTER_AD : ' + JS_GAME_FOOTER_AD);
          }

          GA.renderGameHTML(_htmlContent, _renderType);

          $progress = $('.progress-bottom');
          if ($progress.get(0)) {
            progressStepBar($progress, currentStep);
          }

          if (typeof JS_VIDEO_SOUND !== 'undefined' && JS_VIDEO_SOUND) {
            if ($('video').get(0)) {
              // 1번 페이지에 video가 없는 경우가 존재함
              $('video').get(0).play();
            }
          }
        },
        error: function () {
          console.log('failed');
        },
      });
    }
  },

  /**
   * (내부사용) 게임 플레이 화면 랜더링 함수
   * @param {HTML} _htmlContent 게임 플레이 화면(HTML)
   * @param {string} _renderType 'normal' | 'fade'
   */
  renderGameHTML: function (_htmlContent, _renderType = 'normal') {
    const GA = this;

    if (_renderType == 'fade') {
      $('article.game-wrap')
        .fadeOut(500, function () {
          $(this).html(_htmlContent).append('<div id="modal"></div>');
        })
        .fadeIn(1000, function () {
          GA.loading = false;
        })
        .css('display', 'flex');
    } else {
      $('article.game-wrap').html(_htmlContent).append('<div id="modal"></div>').css('display', 'flex');
      GA.loading = false;
    }
  },

  /**
   * 다음 화면으로 넘어갈 때 사용하세요.
   * @returns 다음 스텝의 문제 화면 랜더링(HTML)
   */
  nextStep: async function () {
    const GA = this;

    if (JS_GAME_STEP_MAX <= currentStep) {
      GA.end_time_ms = new Date().getTime();
      GA.loadingStep();
      return false;
    }

    if (GA.loading) {
      return false;
    }

    GA.loading = true;

    // nextStep에서 다음파일이 없는 경우 loadingStep으로 진행
    function _errorCallback() {
      GA.loading = false;

      GA.loadingStep();
    }

    try {
      await GA.moveStep({
        _moveType: 'next',
        _step: currentStep + 1,
        _errorCallback,
      });
    } catch (e) {
      GA.loading = false;
      console.log('failed');

      return;
    }

    GA.loading = false;
  },

  nextSubStep: async function (_subStep) {
    const GA = this;

    if (GA.loading) {
      return false;
    }

    GA.loading = true;

    function _errorCallback() {
      GA.loading = false;

      GA.loadingStep();
    }

    try {
      await GA.moveStep({
        _step: currentStep,
        _moveType: 'nextSub',
        _subStep,
        _errorCallback,
      });
    } catch (e) {
      GA.loading = false;
      console.log('failed');

      return;
    }

    GA.loading = false;
  },

  /**
   * (내부 로직에서 사용) 로딩 화면 랜더링 함수
   * JS_GAME_LOADING == 'N' ? 테스트 정보 저장 후 결과 화면으로 이동
   * @returns 로딩 및 광고 화면 랜더링(HTML)
   */
  loadingStep: function () {
    const GA = this;

    if (!JS_GAME_LOADING || JS_GAME_LOADING == 'N') {
      GA.resultStep();
      return false;
    }

    GA.loading = true;

    // loading 페이지를 ajax로 불러온다.
    $.ajax({
      type: 'post',
      data: {
        proc: 'loading-step',
        idx: JS_GAME_IDX,
        csrf: JS_CSRF,
      },
      url: '/gl/modules/api.php',
      cache: false,
      success: function (_htmlContent) {
        $('article.game-wrap').hide();
        $('.progress-bottom').addClass('display-none');
        $('article.game-loading-wrap').show().html(_htmlContent);
      },
      error: function () {
        console.log('failed');
      },
    });
  },

  /**
   * 객관식 문제 정답 선택시 사용하세요.
   * @param {number} _questionNumber 1~99
   * @param {number} _answerNumber 0~n
   * @returns 다음 화면으로 넘어간다.
   */
  selectAnswer: function (_questionNumber, _answerNumber, _callback) {
    const GA = this;
    const cookieCheck = cookieEnabledAlert(); // 쿠키 차단 alert

    if (!cookieCheck) {
      // 쿠키 차단시 index 페이지로 이동
      location.reload();
      return false;
    }

    if (GA.loading) {
      return false;
    }
    GA.loading = true;

    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - 문제${_questionNumber} -<답변${parseInt(_answerNumber, 10) + 1}>`,
      _category: `${JS_GAME_TITLE} - 문제`,
      _label: `문제 답변 버튼`,
    });

    $.ajax({
      type: 'post',
      data: {
        proc: 'select-answer',
        idx: JS_GAME_IDX,
        csrf: JS_CSRF,
        qnum: _questionNumber,
        anum: _answerNumber,
      },
      url: '/gl/modules/api.php',
      cache: false,
      success: function (_rst) {
        GA.loading = false;
        if (_rst == '1') {
          if (_callback && typeof _callback === 'function') {
            _callback();

            return;
          }

          GA.nextStep();
        } else {
          alert(_t('common.js_error_access', '잘못된 접근입니다. 반복될 시 관리자에게 문의하세요.'));
        }
      },
      error: function () {
        console.log('failed');
      },
    });
  },

  /**
   * 주관식 문제 정답 입력시.
   * @param {number} _questionNumber 1~99
   * @param {number} _answerNumber 0~n
   * @returns 다음 화면으로 넘어간다.
   */
  inputAnswer: function (_questionNumber, _answerNumber, _answerInput, _callback) {
    const GA = this;
    const cookieCheck = cookieEnabledAlert(); // 쿠키 차단 alert

    if (!cookieCheck) {
      // 쿠키 차단시 index 페이지로 이동
      location.reload();
      return false;
    }

    if (GA.loading) {
      return false;
    }
    GA.loading = true;

    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - 문제${_questionNumber} -<답변${parseInt(_answerNumber, 10) + 1}>`,
      _category: `${JS_GAME_TITLE} - 문제`,
      _label: `문제 답변 버튼`,
    });

    $.ajax({
      type: 'post',
      data: {
        proc: 'submit-input-text-basic',
        idx: JS_GAME_IDX,
        csrf: JS_CSRF,
        qnum: _questionNumber,
        anum: _answerNumber,
        inputtxt: _answerInput,
      },
      url: '/gl/modules/api.php',
      cache: false,
      success: function (_rst) {
        GA.loading = false;
        if (_rst == '1') {
          if (_callback && typeof _callback === 'function') {
            _callback();

            return;
          }

          GA.nextStep();
        } else {
          alert(_t('common.js_error_access', '잘못된 접근입니다. 반복될 시 관리자에게 문의하세요.'));
        }
      },
      error: function () {
        console.log('failed');
      },
    });
  },
  /**
   * 로딩 화면에 버튼에서 쓰거나 내부 로직에서만 사용
   * @returns result.php 화면으로 이동
   */
  resultStep: function () {
    const GA = this;

    if (GA.loading) {
      return false;
    }
    GA.loading = true;

    $.ajax({
      type: 'post',
      data: {
        proc: 'save-result',
        idx: JS_GAME_IDX,
        begin_time_ms: GA.begin_time_ms,
        end_time_ms: GA.end_time_ms,
        csrf: JS_CSRF,
        locale: userlocale,
      },
      url: '/gl/modules/api.php',
      cache: false,
      success: function (_code) {
        if (_code === 'NID') {
          alert(_t('common.js_result_error', '결과 등록 Error. 반복될 시 관리자에게 문의하세요.'));
        } else {
          if (typeof JS_GAME_FOOTER_AD !== 'undefined' && JS_GAME_FOOTER_AD === 'N') {
            $('.ads-banner-wrap').removeClass('display-none');
          }
          GA.loading = false;
          let url = './result?code=' + _code;
          // wv 파라미터 추가
          const urlParams = new URLSearchParams(location.search);
          if (urlParams.has('wv')) {
            url = url + '&wv=' + urlParams.get('wv');
          }

          if (typeof JS_RESULT_BACK_HISTORY !== 'undefined' && JS_RESULT_BACK_HISTORY === 'N') {
            location.replace(url);
          } else {
            location.href = url;
          }
        }
      },
      error: function () {
        console.log('failed');
      },
    });
  },

  /**
   * 힌트보기 버튼에 사용하세요.
   * [참고] 기본적으로 .openHint 클래스를 포함한 엘리먼트는 이 기능이 동작하게 구현됨
   * @returns 힌트 팝업 데이터 불러와서 표시
   */
  openHint: function () {
    // if ($('#modal').find('.modal-cont').length == 0) {
    $.ajax({
      type: 'post',
      data: {
        proc: 'open-hint',
        idx: JS_GAME_IDX,
        step: currentStep,
        csrf: JS_CSRF,
      },
      url: '/gl/modules/api.php',
      cache: false,
      success: function (_htmlContent) {
        if (_htmlContent == 'FNF') {
          alert(_t('common.js_no_hint', '힌트가 없습니다.'));
        } else {
          $('#modal').html(_htmlContent);
        }
      },
      error: function () {
        console.log('failed');
      },
    });
    // }
    $('#modal').removeAttr('class').addClass('one');
  },

  /**
   * (내부 로직에서 사용) 입력한 답이 틀렸을 때 팝업
   * @returns 팝업에 오답임을 알리는 내용 삽입 후 표시
   */
  openWrong: function () {
    // if ($('#modal').find('.modal-cont').length == 0) {
    $.ajax({
      type: 'post',
      data: {
        proc: 'open-wrong',
        idx: JS_GAME_IDX,
        csrf: JS_CSRF,
      },
      url: '/gl/modules/api.php',
      cache: false,
      success: function (_htmlContent) {
        $('#modal').html(_htmlContent);
      },
      error: function () {
        console.log('failed');
      },
    });
    // }
    $('#modal').removeAttr('class').addClass('one');
  },

  /**
   * 팝업(modal) 닫기 용도로 사용하세요.
   * [참고] 기본적으로 .closeHint, .closeModal 클래스를 포함한 엘리먼트는 이 기능이 동작하게 구현됨
   * @returns 열려있는 팝업 닫힘(style class 추가: .out)
   */
  closeModal: function () {
    $('#modal').addClass('out');
  },

  /**
   * 주관식 form onsubmit 함수에 사용하세요.
   * @param {string} _answer 입력받은 정답
   * @returns 다음 화면으로 이동 or 오답 표시 팝업
   */
  checkAnswer: function (_answer) {
    const GA = this;

    if (GA.loading) {
      return false;
    }
    GA.loading = true;

    $.ajax({
      type: 'post',
      data: {
        proc: 'submit-quiz',
        idx: JS_GAME_IDX,
        step: currentStep,
        answer: _answer,
        csrf: JS_CSRF,
      },
      url: '/gl/modules/api.php',
      cache: false,
      success: function (_res) {
        GA.loading = false;
        if (_res == '1') {
          //맞음
          GA.nextStep();
        } else if (_res == '0') {
          //틀림 -  idx
          // alert('틀렸습니다.');
          GA.openWrong();
        } else {
          alert(_t('js_ask_admin', 'Error. 관리자에 문의하세요.'));
        }
      },
      error: function () {
        console.log('failed');
      },
    });

    return false; // submit 취소
  },

  /**
   * 테스트 다시시작 버튼에 사용하세요.
   * @returns 현재 테스트의 인트로 화면으로 이동
   */
  restartTest: function () {
    //func.js
    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - 테스트 다시하기`,
      _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
      _label: `테스트 다시하기 버튼`,
    });

    let url = `/intro.html?idx=${JS_GAME_IDX}`;

    if (JS_GAME_IDX > 40) {
      url = `/gl/${JS_GAME_IDX}/`;
    }
    const urlParams = new URLSearchParams(location.search);
    if (urlParams.has('wv')) {
      url = url + '?wv=' + urlParams.get('wv');
    }

    location.href = url;
  },

  /**
   * 추천 테스트 목록 등 다른 테스트로 이동할 때 사용하세요.
   * @param {string} _idx 테스트 번호
   * @param {string} _title 테스트 제목
   * @param {string} _link 테스트 링크 주소
   */
  moveToOtherTest: function (_idx, _title, _link = '') {
    //func.js
    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - 추천 - ${_title}`,
      _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
      _label: `결과 추천 버튼`,
    });

    let url = `/intro.html?idx=${_idx}`;

    if (_idx > 40) {
      url = `/gl/${_idx}/`;
    }

    if (_link) {
      goBanner(_link ? _link : '/');
    } else {
      location.href = url;
    }
  },

  /**
   * 다른 테스트 하러가기 버튼에 사용하세요.
   * @returns 홈으로 이동함
   */
  moveToHome: function () {
    //func.js
    let _title = `${JS_GAME_TITLE} - 추천 - 다른 테스트 하러 가기`;

    func_handleEventGtag({
      _title,
      _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
      _label: `결과 추천 버튼`,
    });

    location.href = `/`;
  },

  /**
   * 배너 영역 클릭시 사용하세요.
   * @param {string} _link 이동할 경로
   */
  moveToBannerLink: function (_link = '', _bannerName = '') {
    let _title = `${JS_GAME_TITLE} - 배너 클릭`;

    if (_bannerName) {
      _title += ` - ${_bannerName}`;
    }

    func_handleEventGtag({
      _title,
      _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
      _label: `배너 클릭 버튼`,
    });

    kakaoPixel('6894544435604599766').pageView();
    kakaoPixel('6894544435604599766').addToCart({
      id: JS_GAME_IDX,
      tag: `${_title} 배너 클릭 버튼`,
    });

    fbq('trackCustom', `${_title} moveToBannerLink`, {
      promotion: `${_title} 배너 클릭 버튼`,
    });

    // location.href = _link ? _link : '/';
    goBanner(_link ? _link : '/');
  },

  /**
   * gtag 마음대로 지정하기
   * @param {string} _link 이동할 경로
   */
  moveToSpecialBannerLink: function (_link = '', _titleNaming, _categoryNaming, _labelNaming) {
    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - ${_titleNaming}`,
      _category: `${JS_GAME_TITLE} - ${_categoryNaming}`,
      _label: `${_labelNaming}`,
    });

    // location.href = _link ? _link : '/';
    goBanner(_link ? _link : '/');
  },

  // getIsAblePopup: function (_hasFile, _hasSession) {
  //   // pop의 경우 불러오는 쪽에서
  //   // test type?
  //   // 세션 여부
  //   // 를 종합하여 boolean으로서 가져온다
  //   // 세션이 없고, 필요한 파일이 없고, notToday가 N인 경우,

  //   const isNotToday = getCookie('notToday') !== 'Y';

  //   return isNotToday && !_hasFile && !_hasSession;
  // },

  /**
   * 내 순위 보러가기
   * @returns 내 순위 화면으로 이동
   */

  moveToMyTypeRank: function () {
    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - 내 순위 보러가기`,
      _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
      _label: `내 순위 보러가기 버튼`,
    });

    location.href = '/'; // rank page URL format
  },

  moveToGameRank: function (_code = '') {
    const GA = this;

    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - 내 순위 보러가기`,
      _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
      _label: `내 순위 보러가기 버튼`,
    });

    $.ajax({
      type: 'post',
      data: {
        proc: 'is-login',
        idx: JS_GAME_IDX,
        csrf: JS_CSRF,
      },
      url: '/gl/modules/api.php',
      cache: false,
      success: function (_res) {
        if (typeof JS_RANK_POPUP === 'undefined' || JS_RANK_POPUP) {
          // console.log('팝업 열림 설정됨');
          if (_res != '1') {
            // console.log('비로그인 상태');
            popup_openPopupModule('game-rank', JS_GAME_IDX, _code); //popup.js

            return false;
          }
        }
        popup_moveToGameRank(JS_GAME_IDX, _code);
      },
      error: function () {
        console.log('failed');
      },
    });
  },

  /**
   * 전체 유형 순위 보러가기
   * @returns 전체 유형 순위 화면으로 이동
   */
  moveToTypeRank: function () {
    const GA = this;

    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - 전체 유형 보기`,
      _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
      _label: `전체 유형 보기 버튼`,
    });

    $.ajax({
      type: 'post',
      data: {
        proc: 'is-login',
        idx: JS_GAME_IDX,
        csrf: JS_CSRF,
      },
      url: '/gl/modules/api.php',
      cache: false,
      success: function (_res) {
        if (typeof JS_RANK_POPUP === 'undefined' || JS_RANK_POPUP) {
          // console.log('팝업 열림 설정됨');
          if (_res != '1') {
            // console.log('비로그인 상태');
            popup_openPopupModule('most-type', JS_GAME_IDX); //popup.js

            return;
          }
        }
        popup_moveToTypeRank(JS_GAME_IDX);
      },
      error: function () {
        console.log('failed');
      },
    });
  },

  /**
   * 전체 이미지 보러가기
   * @returns 전체 이미지 화면으로 이동
   */
  moveToImageHidden: function () {
    const GA = this;

    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - 히든 짤 보러가기`,
      _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
      _label: `히든 짤 보러가기 버튼`,
    });

    if (isInApp && window.s3app) {
      // broswerInfo.indexOf("Banggooso") > -1
      // 방구소 앱?
      popup_moveToImageHidden(JS_GAME_IDX);
    } else {
      // 그 외
      popup_openPopupModule('image-hidden', JS_GAME_IDX);
      return;
    }
  },

  /**
   * 다른 게임 하러가기
   * @returns 해당 게임으로 이동
   */
  moveToGame: function (_idx) {
    const GA = this;

    // func_handleEventGtag({
    //   _title: `${JS_GAME_TITLE} - 전체 히든 이미지 보기`,
    //   _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
    //   _label: `전체 히든 이미지 보기`,
    // });
    // 그 외
    popup_openPopupModule('recommend-popup', _idx);

    return;
  },

  /**
   * 정답 보러가기 버튼에 사용하세요.
   * @returns 정답 보기 화면으로 이동
   */
  moveToCorrectAnswer: function () {
    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - 정답 보러가기`,
      _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
      _label: `정답 보러가기 버튼 `,
    });

    location.href = '/'; // rank page URL format
  },

  /**
   * 마이페이지로 이동하는 기능 작동 시 사용하세요.
   * (사용 이력 없음)
   */
  moveToMyPage: function () {
    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - 마이페이지`,
      _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
      _label: `마이페이지 버튼`,
    });

    location.href = '/my/myroom.html';
  },

  /**
   * 결과 페이지 영상(들)을 클릭할 때 사용하세요.
   * @param {number} _num 1~n: 몇 번째 영상인지
   * @param {string} _url 리다이렉션 URL
   */
  moveToVideoURL: function (_num = '', _url = '') {
    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - 영상 클릭${_num}`,
      _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
      _label: `영상 클릭 버튼`,
    });

    goBanner(_url ? _url : '/');
  },

  getMyRanking: async function (_seq, _order = { arank2: 'ASC', arank1: 'ASC' }) {
    let myRank = await $.ajax({
      type: 'post',
      data: {
        proc: 'get-my-rank',
        idx: JS_GAME_IDX,
        csrf: JS_CSRF,
        seq: _seq,
        order: _order,
      },
      url: '/gl/modules/api.php',
      cache: false,
    });

    let participantCount = await $.ajax({
      type: 'post',
      data: {
        proc: 'get-participant-count',
        idx: JS_GAME_IDX,
        csrf: JS_CSRF,
      },
      url: '/gl/modules/api.php',
      cache: false,
    });

    return {
      myRank: parseInt(myRank) || 0,
      participantCount: parseInt(participantCount) || 0,
    };
  },

  getAllRanking: async function (_limit = 100, _order = { arank2: 'ASC', arank1: 'ASC' }, _timeFormat = 'hh:mm:ss') {
    let listString = await $.ajax({
      type: 'post',
      data: {
        proc: 'get-ranks',
        idx: JS_GAME_IDX,
        csrf: JS_CSRF,
        limit: _limit,
        order: _order,
      },
      url: '/gl/modules/api.php',
      cache: false,
    });

    let list = JSON.parse(listString);
    list = typeof list === 'object' ? list : [];

    return list.map(({ userName, arank1, arank2 }, index) => ({
      rank: index + 1,
      userName,
      arank1,
      time: func_msToTime(arank2, _timeFormat),
    }));
  },

  moveToRelationType: function (_score, _relation = '') {
    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - 궁합 - ${_relation}`,
      _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
      _label: `궁합 버튼`,
    });

    location.href = `/gl/${JS_GAME_IDX}/result?score=${_score}`;
  },

  sendEventMsg: function () {
    $('#modal').addClass('out');

    const phoneNumber = $('#event_sms_phone_number').val().replaceAll('-', '');

    if (phoneNumber == '') {
      $('#modal')
        .html(
          `
            <div class="modal-bg closeModal">
              <div class="modal-cont">
                <div class="hint-title color-w">${_t('common.setting_notice', '알림')}</div>
                <p>${_t('common.js_msg_modal1', '문자메시지를 받을 번호를 입력해 주세요.')}</p>
                <div class="closeModal">${_t('common.common_check', '확인')}</div>
              </div>
            </div>
          </div>
        `
        )
        .removeAttr('class')
        .addClass('one');
      $('#event_sms_phone_number').focus();
      return false;
    }

    var expPhoneNumber = /\d{3}\d{4}\d{4}/;

    if (!expPhoneNumber.test(phoneNumber)) {
      $('#modal')
        .html(
          `
            <div class="modal-bg closeModal">
              <div class="modal-cont">
                <div class="hint-title color-w">${_t('common.setting_notice', '알림')}</div>
                <p>${_t('common.js_msg_modal2', '문자메세지를 받을 번호를<br>정확히 입력해주세요.')}</p>
                <div class="closeModal">${_t('common.common_check', '확인')}</div>
              </div>
            </div>
          </div>
        `
        )
        .removeAttr('class')
        .addClass('one');
      $('#event_sms_phone_number').focus();
      return false;
    }

    $.ajax({
      url: '/gl/modules/api.php',
      type: 'POST',
      data: {
        proc: 'event-sms',
        idx: JS_GAME_IDX,
        csrf: JS_CSRF,
        aphone: phoneNumber,
      },
      success: function (response) {
        let _htmlContent = '';

        if (response == '1') {
          _htmlContent = `
          <div class="modal-bg closeModal">
            <div class="modal-cont">
              <div class="hint-title color-w">${_t('common.setting_notice', '알림')}</div>
              <p>${_t('common.js_msg_modal3', '쿠폰번호를<br>문자로 보내드렸습니다 :)')}</p>
              <div class="closeModal">${_t('common.common_check', '확인')}</div>
            </div>
          </div>`;
        } else if (response == '-1') {
          _htmlContent = `
        <div class="modal-bg closeModal">
          <div class="modal-cont">
            <div class="hint-title color-w">${_t('common.setting_notice', '알림')}</div>
            <p>${_t('common.js_msg_modal4', '이미 참여한<br>번호입니다.')}</p>
            <div class="closeModal">${_t('common.common_check', '확인')}</div>
          </div>
        </div>`;
        } else if (response == '-3') {
          _htmlContent = `
        <div class="modal-bg closeModal">
          <div class="modal-cont">
            <div class="hint-title color-w">${_t('common.setting_notice', '알림')}</div>
            <p>${_t('common.js_msg_modal5', '이벤트가 종료되었습니다.')}</p>
            <div class="closeModal">${_t('common.common_check', '확인')}</div>
          </div>
        </div>`;
        } else if (response == '-50' || response == '-6') {
          _htmlContent = `
          <div class="modal-bg closeModal">
            <div class="modal-cont">
              <div class="hint-title color-w">${_t('common.setting_notice', '알림')}</div>
              <p>${_t('common.js_msg_modal6', '잘못된 번호입니다.')} (${response})<</p>
              <div class="closeModal">${_t('common.common_check', '확인')}</div>
            </div>
          </div>
        `;
        } else {
          _htmlContent = `
          <div class="modal-bg closeModal">
            <div class="modal-cont">
              <div class="hint-title color-w">${_t('common.setting_notice', '알림')}</div>
              <p>Error ${response}, ${_t('common.js_msg_modal7', '관리자에게 문의해주세요.')}</p>
              <div class="closeModal">${_t('common.common_check', '확인')}</div>
            </div>
          </div>
        `;
        }

        $('#modal').html(_htmlContent).removeAttr('class').addClass('one');
        return false;
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('ajax error : ' + textStatus + '\n' + errorThrown);
      },
    });
  },

  moveStep: async function ({ _step, _subStep = 0, _moveType, _errorCallback }) {
    let GA = this;

    let _htmlContent = '';

    try {
      _htmlContent = await $.ajax({
        type: 'post',
        data: {
          proc: 'move-step',
          idx: JS_GAME_IDX,
          step: _step,
          sub: _subStep,
          csrf: JS_CSRF,
          pattern: JS_GAME_PATTERN,
          locale: userlocale,
        },
        url: '/gl/modules/api.php',
        cache: false,
      });
    } catch (e) {
      throw e;
    }

    if (_htmlContent === 'FNF') {
      _errorCallback && _errorCallback();

      return;
    }

    if (_moveType !== 'back') {
      stepHistory.push(`${_step}-${_subStep}`);
    }

    const beforeStep = currentStep;
    currentStep = _step;

    $progress = $('.progress-bottom');
    if ($progress.get(0)) {
      progressStepBar($progress, currentStep);
    }

    GA.renderGameHTML(_htmlContent);

    if (typeof JS_VIDEO_SOUND !== 'undefined' && JS_VIDEO_SOUND) {
      if ($('video').get(0)) {
        // 1번 페이지에 video가 없는 경우가 존재함
        $('video').get(0).play();
      }
    }
    // todo:
    // muted 되지 않은 비디오가 autoplay되도록(크롬 정책 위배) 임의로 initStep과 nextStep에 비디오 play를 걸어줌
    // initStep만 play걸어주고 nextStep에서는 play 넣지 않아도 autoplay로 play될 수 있는지 체크 후 삭제 필요
  },

  backStep: async function () {
    const lastStep = stepHistory[stepHistory.length - 1];
    const lastMainStep = parseInt(lastStep.split('-')[0], 10);
    const lastSubStep = parseInt(lastStep.split('-')[1], 10);

    if (lastMainStep < 2 && lastSubStep < 1) {
      func_goBack(JS_PAGE_TYPE, '/gl/' + JS_GAME_IDX + '/', JS_GAME_TITLE);

      return;
    }

    const GA = this;

    if (GA.loading) {
      return false;
    }

    GA.loading = true;

    function _errorCallback() {
      GA.loading = false;

      alert(_t('common.js_no_page', '페이지를 찾을 수 없습니다.'));
    }

    stepHistory.pop();
    const beforeMoveStep = stepHistory[stepHistory.length - 1];
    const backStep = parseInt(beforeMoveStep.split('-')[0], 10);
    const backSubStep = parseInt(beforeMoveStep.split('-')[1], 10);

    try {
      await GA.moveStep({
        _moveType: 'back',
        _step: backStep,
        _subStep: backSubStep,
        _errorCallback,
      });
    } catch (e) {
      console.log('failed back step');
    }

    GA.loading = false;
  },

  checkEmployeeId: async function (_company, _employeeId) {
    let count = null;

    try {
      count = await $.ajax({
        type: 'post',
        data: {
          proc: 'get-employeeId-count',
          idx: JS_GAME_IDX,
          csrf: JS_CSRF,
          company: _company,
          employeeId: _employeeId,
        },
        url: '/gl/modules/api.php',
        cache: false,
      });
    } catch (e) {
      throw e;
    }

    return !!parseInt(count);
  },
  /**
   * 우리카드 드롭다운 데이터 저장
   * @returns result.php 화면으로 이동
   */
  dropdownResultStep: function (_resultType, _wv = null) {
    const GA = this;

    if (GA.loading) {
      return false;
    }
    GA.loading = true;

    $.ajax({
      type: 'post',
      data: {
        proc: 'save-dropdown-data',
        idx: JS_GAME_IDX,
        begin_time_ms: GA.begin_time_ms,
        end_time_ms: GA.end_time_ms,
        result_type: JSON.stringify(_resultType),
        csrf: JS_CSRF,
        locale: userlocale,
      },
      url: '/gl/modules/api.php',
      cache: false,
      success: function (_code) {
        if (_code === 'NID') {
          alert(_t('common.js_result_error', '결과 등록 Error. 반복될 시 관리자에게 문의하세요.'));
        } else {
          if (typeof JS_GAME_FOOTER_AD !== 'undefined' && JS_GAME_FOOTER_AD === 'N') {
            $('.ads-banner-wrap').removeClass('display-none');
          }
          GA.loading = false;
          let url = './result?code=' + _code;
          // wv 파라미터 추가
          const urlParams = new URLSearchParams(location.search);
          if (urlParams.has('wv')) {
            url = url + '&wv=' + urlParams.get('wv');
          }
          location.href = url;
        }
      },
      error: function () {
        console.log('failed');
      },
    });
  },

  /**
   * 이벤트 응모
   * @param {String} _type  페이지 타입 : intro, page, loading, result
   * (type page일때 algorighm에서 세션 entry_data 데이터를 이용하여 저장 $dao->setEventEntry($entryName, $entryValue)function 직접 사용)
   * @param {String} _entryName 응모자명
   * @param {String} _entryValue 응모자 정보(전화번호, 이메일, 등등 유저를 유추할수 있는 데이터)
   */
  saveEntry: function (_type, _entryName, _entryValue) {
    const GA = this;
    $.ajax({
      type: 'post',
      data: {
        proc: 'save-entry-data',
        idx: JS_GAME_IDX,
        type: _type,
        entry_name: _entryName,
        entry_value: _entryValue,
      },
      url: '/gl/modules/api.php',
      cache: false,
      success: function (response) {
        if (response == '1') {
          if (_type == 'page') {
            GA.nextStep();
          }
          if (_type == 'loading') {
            GA.resultStep();
          }
        } else {
          console.log('failed');
        }
      },
      error: function () {
        console.log('failed');
      },
    });
  },

  /**
   * 해시태그 복사하기
   *  @param {String} _type title에 추가로 넣을 문자열
   */
  copyHash: function (_type) {
    let type = _type ? _type + ' - ' : '';

    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - ${type}해시태그 복사하기`,
      _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
      _label: `해시태그 복사하기 버튼`,
    });
  },
};

$(document).ready(function () {
  // 다음 스텝으로 이동
  $('article.game-wrap').on('click', '.goToNext', function () {
    gameActions.nextStep();
  });
  $('article.game-wrap').on('click', '.goToNextSub', function () {
    gameActions.nextSubStep(1);
  });

  // Hint 보기
  $('article.game-wrap').on('click', '.openHint', function () {
    gameActions.openHint();
  });
  // Hint 닫기
  $('article.game-wrap').on('click', '.closeHint', function (e) {
    // e.stopPropagation();
    if ($(e.target).hasClass('closeHint')) {
      gameActions.closeModal();
    }
  });

  // 모달창 닫기
  $('.app-main').on('click', '.closeModal', function (e) {
    // e.stopPropagation();
    if ($(e.target).hasClass('closeModal')) {
      gameActions.closeModal();
    }
  });

  // 참여자 수
  const aClicks = parseInt($('.game-count .count-num').text());
  // 조회수 10000 이상일때만 노출
  if (aClicks >= 10000) {
    $({ val: 0 }).animate(
      { val: aClicks },
      {
        duration: 2000,
        step: function () {
          const num = numberWithCommas(Math.floor(this.val));
          $('.game-count .count-num').text(num); //참여자수 세팅
        },
        complete: function () {
          const num = numberWithCommas(Math.floor(this.val));
          $('.game-count .count-num').text(num); //참여자수 세팅
        },
      }
    );
  } else {
    $('.game-count').hide();
  }
});
