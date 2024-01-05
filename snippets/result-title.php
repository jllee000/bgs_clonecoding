<?php
// 유형별 퍼센트 가져오기
$mostContents = $dao->getMostContents(false);
$resultStats = array();

foreach ($mostContents as $i => $mc) {
  $mcResult = $mc['aresult'];
  $precent = str_replace('%', '', $mc['Percentage']);
  $resultStats[$mcResult] = $precent;
}

$scoreSubTitle = '';
$scoreSubTitleArray = explode('[]', _t("game.result{$resultScore}Subtitle", $dao->questionResult[$resultScore]['subTitle']));
foreach ($scoreSubTitleArray as $scoreSubTitleLine) {
  $scoreSubTitle .= "<p>$scoreSubTitleLine</p>";
}

$scoreResultTitle = '';
$resultTitleArray = explode('[]', _t("game.result{$resultScore}Title", $dao->questionResult[$resultScore]['title']));
foreach ($resultTitleArray as $resultTitleLine) {
  $scoreResultTitle .= "<p>$resultTitleLine</p>";
}

$typePercent = isset($resultStats[$resultScore]) ? $resultStats[$resultScore] : 0;
?>

<div id="result-title">
  <h2><?= $scoreSubTitle ?></h2>
  <h1><?= $scoreResultTitle ?></h1>
  <p class="num-statement"><?= _t("common.result_share_same", "전체 테스트 참여자 중 &nbsp;<span>%s％</span><b>가 같은 유형입니다.</b>", $typePercent) ?></p>
  <div class="img-box">
    <?php if (isset($resultTitleConfig['imgCaption'][$resultScore])) { ?>
      <p class="img-caption"><?= $resultTitleConfig['imgCaption'][$resultScore] ?></p>
    <? } ?>
    <img class="<?= $resultTitleConfig['allowDownImg'] ? 'allow-context-menu' : '' ?>" src="<?= isset($customResultImageConfig) ? $customResultImageConfig  : _t("game.result{$resultScore}ImageMain", $dao->questionResult[$resultScore]['mainImg']) ?>" alt="" />
    <?php if ($resultTitleConfig['allowDownImg']) { ?>
      <p class="thumb-img-text">
        <?= isset($resultTitleConfig['imgSaveText']) ? $resultTitleConfig['imgSaveText'] : '▲&nbsp;&nbsp;' . _t('common.result_save_image', '꾹 눌러 이미지 저장하기') . '&nbsp;&nbsp;▲' ?>
      </p>
    <? } ?>
  </div>
</div>