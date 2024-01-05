<?php
$shareListTitle = "";

if (isset($shareConfig['listTitle'])) {
  $shareListTitle = $shareConfig['listTitle'];
} else {
  switch (PAGE_TYPE) {
    case 'result':
      $shareListTitle = _t('common.result_share_mine', '내 결과 공유하기');
      break;

    default:
      $shareListTitle = _t('common.game_test_share', '테스트 공유하기');
      break;
  }
}

$shareListCount = 0;
$shareListCountText = "";

if (isset($shareConfig['showCount'])) {
  $shareListCount = $shareConfig['showCount'];
} else {
  // showCount는 지정 안된게 기본값
  $shareListCount = intval($dao->content['ashares']);
}
if ($userLocale == "en-US" || $userLocale == "ca-ES") {
  if ($shareListCount < 1000) {
    $shareListCountText = number_format($shareListCount);
  } else if ($shareListCount < 1000000) {
    $shareListCountText = round($shareListCount / 1000) . "K";
  } else {
    $shareListCountText = round($shareListCount / 1000000, 1) . "M";
  }
} else if ($shareListCount < 10000) {
  $shareListCountText = number_format($shareListCount);
} else {
  $shareListCountText = round($shareListCount / 10000, 1) . _t('common.common_share_count', "만");
}

// 설정 안되어 있으면 기본값
if (!isset($shareConfig['listSNS'])) {
  $shareConfig['listSNS'] =  ['kakaotalk', 'instagram', 'facebook', 'twitter', 'clipboard'];
}

$clipboardURL = '';
if (isset($shareConfig['playerURL'])) {
  //케미테스트 제작자 
  $clipboardURL = $shareConfig['playerURL'] . $dataUrl;
} else if (isset($shareConfig['clipboardURL'])) {
  //클립보드 커스텀
  $dataUrl = strpos($shareConfig['clipboardURL'], '?') > -1 ? '&locale=' . $userLocale : '?locale=' . $userLocale;
  $clipboardURL = $shareConfig['clipboardURL'] . $dataUrl;
} else {
  // 기본 : 현재 페이지 URL, 공유시 wv 파라미터 삭제
  $clipboardURL = PROD_SITE_URL . $_SERVER['REQUEST_URI'] . $dataUrl;
}
$clipboardURL = removeWVParam($clipboardURL);
?>

<div class="share-sns-list">
  <div class="list-title" onclick="$('.share-sns-list .list.toggleOn').toggle();">
    <span><?= $shareListTitle ?></span>
    <?php if ($shareListCount > 1000) { ?>
    <div class="countBox">
      <div class="countImg"></div>
      <div class="countNumber"><?= $shareListCountText ?></div>
    </div>
    <?php } ?>
  </div>

  <?php if (isset($shareConfig['listTitleImg'])) { ?>
  <img class="list-title-img" src="<?= $shareConfig['listTitleImg'] ?>">
  <?php } ?>

  <ul class="list<?= isset($shareConfig['listHide']) && $shareConfig['listHide'] ? " toggleOn" : "" ?>">
    <?php
    foreach ($shareConfig['listSNS'] as $listSNSItem) {
      switch ($listSNSItem) {
        case 'kakaotalk':
          echo '<li><a href="javascript: shareKakaotalk();" class="btn-share kakao">카카오공유</a></li>';
          break;
        case 'kakaotalk-shinhanSolApp':
          echo '<li><a href="javascript: shareKakaotalk(null, \'바로가기\', 1200, 630, false);" class="btn-share kakao">카카오공유</a></li>';
          break;
        case 'instagram':
          echo '<li><a href="javascript: shareInstagram();" class="btn-share instagram">인스타그램공유</a></li>';
          break;
        case 'facebook':
          echo '<li><a href="javascript: shareFacebook();" class="btn-share facebook">페이스북공유</a></li>';
          break;
        case 'twitter':
          echo '<li><a href="javascript: shareTwitter();" class="btn-share twitter">트위터공유</a></li>';
          break;
        case 'naver':
          echo '<li><a href="javascript: shareNaver();" class="btn-share naver">네이버공유</a></li>';
          break;
        case 'clipboard':
          echo '<li><a href="javascript: shareLink();" data-toggle="sns-share" data-service="link" data-url="' . $clipboardURL . '" class="btn-share link-copy">링크복사</a></li>';
          break;

        default:
          echo '<li></li>';
          break;
      }
    }
    ?>
  </ul>
</div>