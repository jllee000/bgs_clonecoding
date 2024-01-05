<?
$customContentTextConfig = isset($_SESSION['game'][$idx]['init_data']) ? $_SESSION['game'][$idx]['init_data'] : array();

include $_SERVER['DOCUMENT_ROOT'] . "/gl/snippets/header-controller.php";
?>

<section class="game-body <?= empty($basicTemplateValue['image']) ? 'no-image' : '' ?> test-step-<?= $basicTemplateValue['qNum'] ?>">
  <? foreach ($basicTemplateValue['order'] as $content) {
    if ($content === 'image' && is_string($basicTemplateValue[$content])) { ?>
      <img class="question-image" src="<?= _t("game.page{$basicTemplateValue['qNum']}Image", $basicTemplateValue[$content]) ?>" />
    <? } else if ($content === 'title' && is_string($basicTemplateValue[$content])) {  ?>
      <h4 class="question-title">
        <?php if (empty($customContentTextConfig)) : ?>
          <?= $basicTemplateValue[$content] ?>
        <?php else : ?>
          <?php
          $basicQuestionTitleText = $basicTemplateValue[$content];
          foreach ($customContentTextConfig as $cKey => $cItem) {
            $basicQuestionTitleText = str_replace($cKey, $cItem,  $basicQuestionTitleText);
          } ?>
          <?= $basicQuestionTitleText ?>
        <?php endif; ?>
      </h4>
    <? } else if ($content === 'content' && is_string($basicTemplateValue[$content])) {  ?>
      <p class="question-content">
        <?= $basicTemplateValue[$content] ?>
      </p>
    <? } else if ($content === 'buttons' && is_array($basicTemplateValue[$content])) { ?>
      <div class="question-options">
        <?
        foreach ($basicTemplateValue['buttons'] as $bIndex => $button) {

          $onclick = '';
          switch ($button['onclick']) {
            case 'select':
              $onclick = "gameActions.selectAnswer('{$basicTemplateValue['qNum']}', '{$bIndex}')";
              break;
            case 'next':
              $onclick = "gameActions.nextStep()";
              break;

            default:
              $onclick = $button['onclick'];
              break;
          }
        ?>
          <button class="question-option" onclick="<?= $onclick ?>">
            <?= str_replace('[]', '<br />', _t("game.page{$basicTemplateValue['qNum']}Button{$bIndex}", $button['answer'])) ?>
          </button>
        <? } ?>
      </div>
  <? }
  } ?>

</section>