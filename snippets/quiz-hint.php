<?php $dao->loadQuizData($qNum); ?>
<div class="modal-bg">
  <div class="modal-cont">
    <div class="closeHint close"></div>
    <div class="Hint01">
      <div class="hint-title color-white"><?= _t('common.hint_title', '힌트') ?></div>
      <p class="hint">
        <?= str_replace('[]', '<br>', _t(isset($quizHintConfig['locale_code']) ? $quizHintConfig['locale_code'] : 'no_locale_code', $dao->quiz[$qNum]['hint'])); ?>
      </p>
      <div id="answer"><?= _t('common.hint_to_see_answer', '정답보기') ?></div>
    </div>
    <div class="Hint02">
      <div class="hint-title color-white"><?= _t('common.hint_answer', '정답') ?></div>
      <p class="hint"><?= str_replace('[]', '<br>', _t(isset($quizAnswerConfig['locale_code']) ? $quizAnswerConfig['locale_code'] : 'no_locale_code',  $dao->quiz[$qNum]['answer'])); ?></p>
    </div>
  </div>

</div>

<script>
  $('#answer').on('click tap', function() {
    $('.Hint01').animate({
      left: '-100%'
    }), $('.Hint02').animate({
      left: '0'
    }, 500);
  });
</script>