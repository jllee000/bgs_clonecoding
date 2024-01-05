<?php
$testNowBgm = array();
$testNowBgmIndex = null;

$testBeforeBgm = array();
$beforeBgmIndex = null;

if (isset($bgmPlayConfig)) {
  foreach ($bgmPlayConfig as $bgmPlayIndex => $testBgm) {
    $bgmStartArea = explode('-', $testBgm['page'])[0];
    $bgmEndArea = explode('-', $testBgm['page'])[1];
    $bgmPlayArea = range($bgmStartArea, $bgmEndArea);
    $bgmStopArea = array();

    if (isset($bgmPlayConfig[$bgmPlayIndex - 1])) {
      $beforeBgm = $bgmPlayConfig[$bgmPlayIndex - 1];
      $beforeStartArea = explode('-', $beforeBgm['page'])[1];
      $bgmStopArea = range($beforeStartArea, $bgmStartArea);
    }
    
    if (in_array($step, $bgmPlayArea)) {
      $testNowBgmIndex = $bgmPlayIndex;
      $testNowBgm = $testBgm;

      break;
    } else if (in_array($step, $bgmStopArea)) {
      $beforeBgmIndex = $bgmPlayIndex - 1;
      $testBeforeBgm = $beforeBgm;

      break;
    }
  }
}
?>

<script>
  <?php if (!empty($testNowBgm)) { ?>
    audios.audioPlayControler('bgm', <?= $testNowBgmIndex ?>, 'play', <?= $testNowBgm['fade'] === false ?  0 : 1 ?>, <?= $testNowBgm['fadeTime'] ?>);
  <? } else if (isset($beforeBgmIndex)) { ?>
    audios.audioPlayControler('bgm', <?= $beforeBgmIndex ?>, 'stop', <?= $testBeforeBgm['fade'] === false ?  0 : 1 ?>, <?= $testBeforeBgm['fadeTime'] ?>);
  <? } ?>
</script>