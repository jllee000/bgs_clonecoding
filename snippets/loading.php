<div class="popup popup_new">
  <div class="pop_1216">
    <!--  이미지 위에 안내 텍스트-->
    <p class="popup_p ing_1216">
      <?= _t('common.loading_wait', '<span>%s</span>의<br>결과분석 중...', isset($_SESSION['u_aid']) ? $_SESSION['u_aname'] : _t('common.common_researcher', '연구원')) ?>
    </p>
    <p class="popup_p result_1216" style="display:none;">
      <?= _t('common.loading_wait', '<span>%s</span>의<br>결과분석 완료!', isset($_SESSION['u_aid']) ? $_SESSION['u_aname'] : _t('common.common_researcher', '연구원')) ?>
    </p>
    <div class="popup_img_1216">
      <img src="<?= CDN_PATH ?>/assets/images/common/loading_gif_1216.gif" alt="방구석 팝업 gif">
      <div class="img_btn">
        <div class="btn ing_1216">
          <a class="btn_induce" href="javascript:void(0)">
            <?= _t('common.loading_result_btn', '결과보러 가기') ?> >
          </a>
        </div>
        <div class="btn result_1216 result_hide" style="display:none;">
          <a class="btn_induce next-btn" href="javascript:gameActions.resultStep();">
            <?= _t('common.loading_result_btn', '결과보러 가기') ?> >
          </a>
        </div>
      </div>
    </div>

    <!-- 버튼 하단 안내 텍스트 -->
    <p class="btn_retest_1216 ing_1216"><?= _t('common.loading_second_analysis', '%s초 후 분석이 완료됩니다.', '<span id="count_seconds">5</span>') ?></p>
    <p class="btn_retest_1216 result_1216" style="display:none;"><span></span><?= _t('common.loading_complete', '분석완료') ?>!</p>
    <div id="bannerWrap" class="banner_1216">
      <?php
      $adsTarget = 'gl-loading';
      include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/include/ads-google.php";
      ?>
      <!-- ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-7576712279485173" data-ad-slot="7158172547" data-ad-format="auto" data-full-width-responsive="false"></ins -->
    </div>

  </div>
</div>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({}); // 구글 애드센스

  $(document).ready(function() {
    $('.ads-banner-wrap').addClass('display-none');
    $('.app-header').hide();
    deleteCookie('refer');

    $('.result_hide').on('tap click', function() {
      $(this).parents('.pop_induce.loading').hide();
    });

    setTimeout(function() {
      $('.ing_1216').hide();
      $('.result_1216').show();
      gameActions.loading = false;
    }, 5000);

    //카운트 시작 숫자
    var count = 5;
    //카운트다운함수
    var countdown = setInterval(function() {
      $("#count_seconds").empty();
      $("#count_seconds").append(count);

      if (count == 0) {
        clearInterval(countdown);
      }
      count--; //카운트 감소
    }, 600);
  });
</script>
<small>
  <?php
  // 개발 디버깅 용 영역
  // echo "aa<pre>" . print_r($_SESSION['game'], 1) . "</pre>";
  ?>
</small>