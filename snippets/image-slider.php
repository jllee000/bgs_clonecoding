<div class="brand-slide-wrapper">
  <div class="swiper-container swiper-container-horizontal swiper-container-free-mode swiper-container-ios">
    <div class="swiper-wrapper">
      <?php
      $defaultSlideCnt = isset($imageSliderConfig['defaultSlideCnt']) ? $imageSliderConfig['defaultSlideCnt'] : 0;
      $maxSlideCnt = isset($imageSliderConfig[$resultScore]['slideCnt']) ? $imageSliderConfig[$resultScore]['slideCnt'] : $defaultSlideCnt;
      $imgExtension = isset($imageSliderConfig['ext']) ? $imageSliderConfig['ext'] : 'jpg';
      for ($slideIdx = 1; $slideIdx <= $maxSlideCnt; $slideIdx++) {
        $slideImagePath = CDN_PATH . "/assets/images/game" . GAME_IDX . "/result/" . $resultScore . "/" . $slideIdx . '.' . $imgExtension;
      ?>
        <div class="swiper-slide">
          <div class="slide-box">
            <img src="<?= $slideImagePath ?>" />
          </div>
        </div>
      <? } ?>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    recommendSwiper = new Swiper('.brand-slide-wrapper .swiper-container', {
      slidesPerView: "auto",
      spaceBetween: 0,
      freeMode: true
    });
  });
</script>