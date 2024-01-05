<div class="recommend-list page-full">
  <div class="inner">
    <h3 class="title">Recommend</h3>
    <div class="swiper-container swiper-container-horizontal swiper-container-free-mode swiper-container-ios">
      <div class="swiper-wrapper">
        <?php
        $recommendContents = $dao->getRecommendContents();
        foreach ($recommendContents as $rc) :
        ?>
          <div class="swiper-slide">
            <div class="slide-box">
              <a href="javascript:gameActions.moveToOtherTest(<?= $rc['idx'] ?>, '<?= $rc['atitle'] ?>')" class="link">
                <div class="img-box" style="background-image:url('<?= $rc['afile_regName1'] != "" ? $rc['afile_regName1'] : $rc['afile_regName_m'] ?>')"></div>
                <p class="slide-text"><?= $rc['atitle'] ?></p>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="btn-wrap">
      <a href="javascript:gameActions.moveToHome()" class="btn-white-round">Go to anther test</a>
    </div>
  </div>
</div>