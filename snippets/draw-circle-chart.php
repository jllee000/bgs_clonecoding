<div class="circle-chart-wrapper">
  <?php
  foreach ($circleChartConfig[$resultScore] as $cIndex => $cItem) {
    $cPercent = $circleChartConfig[$resultScore][$cIndex]['per'];
    if (($cPercent > 4) && ($cPercent !== 50)) {
      $cPercent -= $cPercent % 4;
    }
  ?>

    <div class="circle-chart-inside">
      <p><?= $circleChartConfig[$resultScore][$cIndex]['name'] ?></p>
      <div class="circle-chart-box">
        <section>
          <svg class="circle-chart" viewbox="0 0 38 38" width="6" height="6" xmlns="http://www.w3.org/2000/svg">
            <? if ($circleChartConfig['isBlurred']) { ?>
              <filter id="sofGlow<?= $circleChartConfig[$resultScore][$cIndex]['color'] ?>" height="200%" width="200%" x="-75%" y="-75%">
                <!-- Thicken out the original shape -->
                <feMorphology operator="dilate" radius=".5" in="SourceAlpha" result="thicken" /> <!-- 두께 -->
                <feGaussianBlur in="thicken" stdDeviation="1" result="blurred" /> <!-- glow -->
                <feFlood flood-color="<?= $circleChartConfig[$resultScore][$cIndex]['color'] ?>" result="glowColor" /> <!-- glow 색상 -->
                <feComposite in="glowColor" in2="blurred" operator="in" result="softGlow_colored" />
                <!--	Layer the effects together -->
                <feMerge>
                  <feMergeNode in="softGlow_colored" />
                  <feMergeNode in="SourceGraphic" />
                </feMerge>
              </filter>
            <? } ?>
            <circle class="circle-chart__background" stroke="<?= $circleChartConfig['backgroundColor'] ?>" stroke-width="<?= $circleChartConfig['strokeWidth'] ?>" fill="none" cx="19" cy="19" r="15.91549431" />
            <circle filter="<?= $circleChartConfig['isBlurred'] ? 'url(#sofGlow' . $circleChartConfig[$resultScore][$cIndex]['color'] . ')' : '' ?>" class="circle-chart__circle" stroke="<?= $circleChartConfig[$resultScore][$cIndex]['color'] ?>" stroke-width="<?= $circleChartConfig['strokeWidth'] ?>" stroke-dasharray="<?= $cPercent ?>, 100" stroke-linecap="round" fill="none" cx="19" cy="19" r="15.91549431" />
          </svg>
          <p class="circle-chart-text"><?= $circleChartConfig[$resultScore][$cIndex]['per'] ?>%</p>
        </section>
      </div>
    </div>
  <? } ?>
</div>