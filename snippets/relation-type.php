<?php

$relationConfig = isset($relationConfig) ? $relationConfig : array();

$relationTypeTitle = isset($relationConfig['title']) ? $relationConfig['title'] : _t('common.relation_title', '유형별 궁합');
$relationTypeTable = array_map(function ($scoreReuslt) {
  return array(
    'good' => $scoreReuslt['good'],
    'bad' => $scoreReuslt['bad'],
  );
}, $dao->questionResult);


$relationshipTextTable = array_map(function ($scoreReuslt) {
  return explode('[]', _t("game.result{$scoreReuslt['result']}Title", $scoreReuslt['title']));
}, $dao->questionResult);

$relationGoodAndBad = array();

if (isset($relationTypeTable[$resultScore])) {
  $relationGoodAndBad = $relationTypeTable[$resultScore];
}

$relationGoodAndBad = array_filter($relationGoodAndBad, function ($relationItem) {
  return !empty($relationItem);
});

//iframe시 wv값 추가
$relationWVParam = isset($relationConfig['additionalUrlQuery']) ? '&' . $relationConfig['additionalUrlQuery'] : '';
?>

<div class="result-box type">
  <h4 class="result-box-title"><?= _t('game.resultRelation', $relationTypeTitle) ?></h4>

  <div class="img-halt-box">
    <ul class="list most_types">
      <?php foreach ($relationGoodAndBad as $relKey => $rel) : ?>
        <li>
          <?php
          if (isset($relationConfig[$relKey]) && $relationConfig[$relKey]) {
            // 값이 설정되어있고 표기할 수 있는 경우 (ex: 45)
          ?>
            <p class="<?= $relKey ?>"><?= _t("game.resultRelation{$relKey}", $relationConfig[$relKey]) ?></p>
          <?
          } else if (isset($relationConfig[$relKey]) && $relationConfig[$relKey] === false) {
            // 값이 false로 설정된 경우 타이틀 끔 (ex: 52)
          ?>
          <?
          } else {
            // 설정이 되지 않은 경우
          ?>
            <p class="<?= $relKey ?>"><?= ucfirst($relKey) ?></p>
          <?
          }
          ?>
          <img src="<?= $dao->questionResult[$rel]['mainImg'] ?>" alt="" class="img-responsive">
          <span class="label-bottom">
            <?php
            foreach ($relationshipTextTable[$rel] as $relTextKey => $relationshipText) :
            ?>
              <span><?= $relationshipText ?></span>
              <?php if ($relTextKey < count($relationshipTextTable[$rel]) - 1) : ?>
                <br>
              <? endif; ?>
            <?php endforeach; ?>
          </span>
          <a href="./result?score=<?= $rel . $relationWVParam ?>" class="btn-gray-round small"><?= _t('common.result_check_type', '유형 보기') ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>