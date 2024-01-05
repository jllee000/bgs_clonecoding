<?php
/* INTRO 유입 카운팅 */
$dao->logging(PAGE_TYPE);

?>

<div class="intro-imgdiv">

  <div class="intro-contents">
    <img class="intro-gif" src="<?= CDN_PATH ?>/assets/images/game144/intro-sub.gif">

    <!-- 게임시작 버튼 (필수) -->
    <div class="btn-wrap">
      <a href="javascript:gameActions.initGame('normal', 'basic');" class="btn-game-start twayair-r">시작하기 !</a>
    </div>

    <h5 class="visittxt">참여자수</h5>
    <div class="visit"><?= $dao->content['aclicks'] ?></div>



    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/gl/snippets/share-sns.php" ?>

    <div class="intro-rank">
      <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/gl/snippets/most-types.php" ?>
    </div>




  </div>

</div>