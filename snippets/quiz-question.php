<?php $dao->loadQuizData($qNum); ?>
<div class="pop-text question-text">
  <?= str_replace('[]', '<br>', _t(isset($quizQuestionConfig['locale_code']) ? $quizQuestionConfig['locale_code'] : 'no_locale_code', $dao->quiz[$qNum]['question'])); ?>
</div>