<?php
$mostTypeTitle = isset($mostTypeConfig['title']) ? $mostTypeConfig['title'] : _t('common.game_most_type', '가장 많은 유형');
$rankingButtonTitle = isset($mostTypeConfig['rankingButtonTitle']) ? _t('game.result_check_rank', $mostTypeConfig['rankingButtonTitle']) : _t('common.result_check_rank', '내 유형 순위 보러 가기');

//iframe시 wv값 추가
$mostTypeWVParam = isset($mostTypeConfig['additionalUrlQuery']) ? '&' . $mostTypeConfig['additionalUrlQuery'] : '';
?>

<div class="result-box most">
  <h3 class="result-box-title">
    <span><?= $mostTypeTitle ?></span>
  </h3>
  <h4 class="result-box-title2"><small>*<?= _t('common.game_most_type_desc', '참여 통계는 1시간마다 갱신됩니다.') ?></small></h4>

  <div class="img-halt-box">
    <ul class="list">
      <?php
      /* 결과 score 데이터 불러오기 */
      $score = $dao->loadScoreData();

      $labelClass = ['first', 'second'];

      $mostContents = $dao->getMostContents(2);
      foreach ($mostContents as $ii => $mc) :
        $mcScore = $mc['aresult'];

        foreach ($dao->questionResult as $qrst) {
          if ($qrst['result'] == $mcScore) {
            $mcTitle = explode("[]", _t("game.result{$mcScore}Title", $dao->questionResult[$mcScore]['title']));
          }
        }
      ?>
        <li>
          <a href="./result?score=<?= $mcScore . $mostTypeWVParam ?>">
            <span class="label-top <?= $labelClass[$ii] ?>"><?= _t("common.common_rank", "%s위", ($ii + 1)) ?></span>
            <span class="label-bottom">
              <?php
              foreach ($mcTitle as $mcTitleText) :
              ?>
                <span><?= $mcTitleText ?></span>
              <?php endforeach; ?>
              <em>(<?= $mc['Percentage'] ?>)</em>
            </span>
            <img src="<?= _t("game.result{$mcScore}ImageMain", $dao->questionResult[$mcScore]['mainImg']) ?>" alt="<?= implode(' ', $mcTitle) ?>" class="img-responsive">
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <?php
  if (PAGE_TYPE == 'result') : ?>
    <div class="game-btn-wrapper btn_myranking">
      <a class="game-btn" href="javascript:gameActions.moveToTypeRank()" oncontextmenu="return false"><?= $rankingButtonTitle ?></a>
    </div>
  <?php endif; ?>
</div>