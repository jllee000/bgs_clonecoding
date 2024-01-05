<?php
$templateValue = isset($matchingTemplateValue) ? $matchingTemplateValue : $basicTemplateValue;
?>

<div class="test-progress-header test-step-<?= $templateValue['qNum'] ?>">
  <div class="test-controller-top">
    <div class="back-btn-wrap <?= $templateValue['step'] < 2 ? 'hide-back-btn' : '' ?>">
      <button type="button" class="game-previous" onclick="gameActions.backStep('basic')">
        <?= _t('game.common.back', '뒤로') ?>
      </button>
    </div>
    <div class="game-title page"><?= _t('game.gameTitle', $dao->content['atitle']) ?></div>
    <div class="game-stage-wrap">
      <div class="game-stage">
        <? if (isset($isMatchingMakerTest) && $isMatchingMakerTest === true) : ?>
          <span class="current-stage"><?= $templateValue['completeAnswer'] ?></span> / <?= $templateValue['pageSize'] ?>
        <? else : ?>
          <span class="current-step"><?= $templateValue['step'] ?></span> / <span><?= $templateValue['pageSize'] ?></span>
        <? endif; ?>
      </div>
    </div>
  </div>
  <div class="test-controller-bottom">
    <?php
    if (isset($gameStageValue)) { ?>
      <div class="game-header">
        <? foreach ($gameStageValue['item'] as $gamePageItemIndex => $gamePageItem) {
          if (!empty($gameStageValue['no-item']) && in_array($templateValue['step'], $gameStageValue['no-item'])) {
            // 해더 타이틀 제외
          } else {
            switch ($gamePageItem) {
              case 'timer':
                include_once $_SERVER['DOCUMENT_ROOT'] . '/gl/snippets/header-quiz-timer.php';
                break;
              case 'title':
                include_once $_SERVER['DOCUMENT_ROOT'] . '/gl/snippets/header-stage-title.php';
                break;
              case 'custom':
                include_once $_SERVER['DOCUMENT_ROOT'] . "/gl/{$idx}/header-stage-title.php";
                break;
              default:
                break;
            }
          }
        } ?>
      </div>
    <? } ?>
    <div class="game-progress">
      <? if (isset($isMatchingMakerTest) && $isMatchingMakerTest === true) : ?>
        <div class="game-progress-inner" style="width: <?= ($templateValue['completeAnswer'] / $templateValue['pageSize']) * 100 ?>%"></div>
      <? else : ?>
        <div class="game-progress-inner" style="width: <?= ($templateValue['step'] / $templateValue['pageSize']) * 100 ?>%"></div>
      <? endif; ?>
    </div>
  </div>
</div>