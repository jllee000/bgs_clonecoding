<?php
$mostTypeTitle = isset($mostTypeConfig['title']) ? $mostTypeConfig['title'] : '가장 많은 유형';
?>

<div class="result-box most">
  <h3 class="result-box-title">
    <span><?= $mostTypeTitle ?></span>
  </h3>
  <h4 class="result-box-title2"><small>*Testing statistics is renewed every hour</small></h4>

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
            $mcTitle = explode("[]", $dao->questionResult[$mcScore]['title']);
          }
        }
      ?>
        <li>
          <a href="./result?score=<?= $mcScore ?>">
            <span class="label-top <?= $labelClass[$ii] ?>">Ranked at No.<?= ($ii + 1) ?></span>
            <span class="label-bottom">
              <?php
              foreach ($mcTitle as $mcTitleText) :
              ?>
                <span><?= $mcTitleText ?></span>
              <?php endforeach; ?>
              <em>(<?= $mc['Percentage'] ?>)</em>
            </span>
            <img src="<?= $dao->questionResult[$mcScore]['mainImg'] ?>" alt="<?= implode(' ', $mcTitle) ?>" class="img-responsive">
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <?php
  if (PAGE_TYPE == 'result') : ?>
    <div class="game-btn-wrapper btn_myranking">
      <a class="game-btn" href="javascript:gameActions.moveToTypeRank()">Go and check<br>the rank of my type</a>
    </div>
  <?php endif; ?>
</div>