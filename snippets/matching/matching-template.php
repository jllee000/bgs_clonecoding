<?
$isMatchingMakerTest = $matchingTemplateValue['pattern'] === 'matching-maker' ? true : false;
$customContentTextConfig = isset($_SESSION['game'][$idx]['init_data']) ? $_SESSION['game'][$idx]['init_data'] : array();
include $_SERVER['DOCUMENT_ROOT'] . "/gl/snippets/header-controller.php";
?>

<section class="game-body test-step-<?= $matchingTemplateValue['qNum'] ?> image-step-<?= $matchingTemplateValue['questionStep'] ?>">
  <? if ($isMatchingMakerTest) : ?>
    <div class="skip-question" onclick="matchingGameActions.nextStep()">
      질문 넘기기
    </div>
  <? endif; ?>

  <? foreach ($matchingTemplateValue['order'] as $content) {
    if ($content === 'title' && is_string($matchingTemplateValue[$content])) {  ?>
      <h4 class="question-title">
        <?php if ($isMatchingMakerTest) {
          echo $matchingTemplateValue['completeAnswer'] + 1 . ".&nbsp";
        }
        if (empty($customContentTextConfig)) {
          echo $matchingTemplateValue[$content];
        } else {
          $matchingQuestionTitleText = $matchingTemplateValue[$content];
          if ($isMatchingMakerTest) {
            foreach ($customContentTextConfig as $cKey => $cItem) {
              $matchingQuestionTitleText = str_replace($cKey, $cItem,  $matchingQuestionTitleText);
            }
          } else {
            $matchingQuestionTitleText = str_replace("nickname", $matchingTemplateValue['makerNickname'],  $matchingQuestionTitleText);
          }
          echo $matchingQuestionTitleText;
        } ?>
      </h4>
    <? } else if ($content === 'content' && is_string($matchingTemplateValue[$content])) {  ?>
      <p class="question-content">
        <?= $matchingTemplateValue[$content] ?>
      </p>
    <? } else if ($content === 'buttons' && is_array($matchingTemplateValue[$content])) { ?>
      <div class="question-options">
        <?
        foreach ($matchingTemplateValue['buttons'] as $bIndex => $button) {
          $onclick = "checkAnswer('{$bIndex}', '{$matchingTemplateValue['choice']}', '{$matchingTemplateValue['qNum']}')";
        ?>
          <button class="question-option" id="button-<?= $bIndex ?>" onclick="<?= $isMatchingMakerTest ? '' : $onclick ?>">
            <p><?= str_replace('[]', '<br />', _t("game.page{$matchingTemplateValue['qNum']}Button{$bIndex}", $button['answer'])) ?></p>
          </button>
        <? } ?>
      </div>
  <? }
  } ?>

  <? if ($isMatchingMakerTest) : ?>
    <button class="next-question">
      <?= $matchingTemplateValue['completeAnswer'] >= 9 ? '만들기 완료!' : '다음으로' ?>
    </button>
  <? endif; ?>
</section>

<script>
  //player
  function checkAnswer(_bIndex, _choice, _qNum) {
    const answerIdx = String(parseInt(_choice) - 1);

    $(`#button-${answerIdx}`).addClass('right');

    if (_bIndex !== answerIdx) {
      $(`#button-${_bIndex}`).addClass('wrong');
    }

    matchingGameActions.selectAnswer(_qNum, _bIndex);
  }

  //maker
  $(document).ready(function() {
    $('.question-option').on('click', function() {
      $('.question-options').find('.active').removeClass('active');
      $(this).addClass('active');
    });

    $('.next-question').on('click', function() {
      const activeAnswer = $('.question-options').find('.active');
      let activeId = $(activeAnswer).attr('id');

      // 선택된 질문이 있는 경우에만 다음으로 넘어감
      if (activeId !== undefined) {
        activeId = activeId.split("-")[1];
        matchingGameActions.selectAnswer('<?= $matchingTemplateValue['qNum'] ?>', activeId);
      }
    });
  });
</script>