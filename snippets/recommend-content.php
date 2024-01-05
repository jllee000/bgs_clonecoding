<div class="recommend-list page-full">
  <div class="inner">
    <?php if (!isset($userLocale) || $userLocale == "ko-KR") { ?>
      <h3 class="title"><?= _t('common.common_recommend', '추천') ?></h3>
      <div class="swiper-container swiper-container-horizontal swiper-container-free-mode swiper-container-ios">
        <div class="swiper-wrapper">
          <?php
          $arecommend_result = isset($dao->content['arecommend_result']) ? $dao->content['arecommend_result'] : '';

          $recommendContents = $dao->getRecommendContents($arecommend_result);
          foreach ($recommendContents as $rc) :
          ?>
            <div class="swiper-slide">
              <div class="slide-box">
                <a href="javascript:gameActions.moveToOtherTest(<?= $rc['idx'] ?>, '<?= $rc['atitle'] ?>', <?= ($rc['ptype'] == "TypeMetav" || $rc['ptype'] == "TypeURL") ? "'{$rc['alink']}'" : "" ?>)" class="link">
                  <div class="img-box" style="background-image:url('<?= $rc['afile_regName1'] != "" ? $rc['afile_regName1'] : $rc['afile_regName_m'] ?>')"></div>
                  <p class="slide-text"><?= _t("common.idx{$rc['idx']}_title", $rc['atitle']) ?></p>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php } ?>
    <div class="btn-wrap">
      <a href="javascript:gameActions.moveToHome()" class="btn-white-round"><?= _t('common.result_other_game', '다른 테스트 하러 가기') ?></a>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    recommendSwiper = new Swiper('.recommend-list .swiper-container', {
      slidesPerView: "auto",
      spaceBetween: 0,
      freeMode: true
    });
  });
</script>