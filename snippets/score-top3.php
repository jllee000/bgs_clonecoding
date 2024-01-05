<?php
$top3SectionTitle = isset($rankingTop3Config['title']) ? $rankingTop3Config['title'] : '';
$RankingButtonTitle = isset($rankingTop3Config['button']) ? $rankingTop3Config['button'] : _t('common.ranking_btn', '내 순위 보러가기') . ' >';
$rankingTop3Info = array();
$testTypeForTop3 = isset($rankingTop3Config['testType']) ? $rankingTop3Config['testType'] : '';

switch ($testTypeForTop3) {
  case 'Matching':
    $mcodeForTop3 = isset($rankingTop3Config['mcode']) ? $rankingTop3Config['mcode'] : '';
    $pcodeForTop3 = isset($rankingTop3Config['pcode']) ? $rankingTop3Config['pcode'] : '';
    $matchingRankCode = isset($rankingTop3Config['mcode']) ? $rankingTop3Config['mcode'] : $rankingTop3Config['pcode'];
    $rankingTop3Info = array_map(function ($rank) {
      return array(
        'userName' => $rank['init_data']['nickname'],
        'apic' => $rank['apic'], //apic도 보내줘야
      );
    }, $dao->getMatchingRankData($pcodeForTop3, $mcodeForTop3, 3));
    break;

  default:
    $rankingTop3Info = $dao->getAllRankBySeq(array('arank1' => 'DESC', 'arank2' => 'ASC'), 3);
    break;
}
?>

<div class="result-box">
  <div class="result-bot-text">
    <h3 class="result-box-title">
      <p><?= $top3SectionTitle ?></p>
    </h3>
  </div>

  <div class="medal-box">
    <img src="<?= isset($rankingTop3Config['customTop3Img']) ? $rankingTop3Config['customTop3Img']  :  CDN_PATH . '/assets/images/common/medal.png' ?>" />
    <?php foreach ($rankingTop3Info as $top3Index => $top3UserInfo) { ?>
      <div class="ranking rank<?= $top3Index + 1 ?>">
        <img src="<?= CDN_PATH ?>/assets/images/uploadImg/<?= $top3UserInfo['apic'] ? $top3UserInfo['apic'] : 'user_default_img.jpg' ?>" class="medal-user-img" />
        <span class="name"><?= $top3UserInfo['userName'] ?></span>
      </div>
    <? } ?>
  </div>

  <div class="btn_medal">
    <?php
    switch ($testTypeForTop3) {
      case 'Matching': ?>
        <a href="javascript:matchingGameActions.moveToMatchingRank('<?= $matchingRankCode ?>', '<?= PAGE_TYPE ?>')"><?= $RankingButtonTitle ?></a>
      <? break;
      default: ?>
        <a href="javascript:gameActions.moveToGameRank(<?= $resultCode ?>)"><?= $RankingButtonTitle ?></a>
    <? break;
    }
    ?>
  </div>
</div>