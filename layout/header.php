<?php
if (!defined('PAGE_TITLE')) {
  if (defined('PAGE_TYPE') && PAGE_TYPE == 'result') {
    $defaultPageTitle = " - " . _t('common.common_site_title', '방구석 연구소') . " - " . _t('common.common_result', '결과');
  } else {
    $defaultPageTitle = " - " . _t('common.common_site_title', '방구석 연구소');
  }
  define('PAGE_TITLE', _t('game.gameTitle', $dao->content['atitle']) . $defaultPageTitle);
}
if (!defined('PAGE_DESC')) {
  define('PAGE_DESC', _t('game.gameSubtitle', $dao->content['asubtitle']));
}


if (!defined('PAGE_OG_TITLE')) {
  define('PAGE_OG_TITLE', _t('game.gameTitle', $dao->content['atitle']));
}
if (!defined('PAGE_OG_IMAGE')) {
  define('PAGE_OG_IMAGE', $dao->content['afile_regName_m'] != "" ? _t('game.afile_regName_m', $dao->content['afile_regName_m']) : _t('game.afile_regName', $dao->content['afile_regName']));
}
if (!defined('PAGE_OG_DESC')) {
  define('PAGE_OG_DESC', _t('game.gameSubtitle', $dao->content['asubtitle']));
}

/*
// GL Guide
 TypeA : 베이직 컨텐츠는 관리자의 컨텐츠를 TypeA 로 지정해야함(game-wrapper에 basic class  추가 됨)
 TypeB : 프리미엄 컨텐츠 && 객관식 (ex. 넷플릭스 좀비 테스트, 운명의 집사 테스트)
 TypeC : 프리미엄 컨텐츠 && 객관식 && 주관식 (ex. 머니게임 테스트)
 TypeD : 프리미엄 컨텐츠 && 주관식 (ex. 살인사건 추리 테스트)
 TypeE ~ G : GL이전(idx 40 미만) MBTI 테스트
 TypeM : 궁합 테스트 (ex. 우리은행 케미테스트)
 TypeQ : 퀴즈형 테스트 (ex. 먹잘알고사)
 TypeP : 기타 테스트 (ex. 폰타자, 짤뽑기, 스낵테스트)
 TypeR : 프리미엄 컨텐츠 (유형X)
*/

$basicTemplateType = array('TypeA', 'TypeM', 'TypeQ');
if (in_array($dao->content['ptype'], $basicTemplateType)) {
  $gameWrapperType = 'basic';
} else {
  $gameWrapperType = '';
}

$premiumContentType = array('TypeB', 'TypeC', 'TypeR', 'TypeG'); // 프리미엄 컨텐츠 타입
if (in_array($dao->content['ptype'], $premiumContentType)) {
  $jsVar['JS_GAME_FOOTER_AD'] = "'N'"; // 게임 내부 Footer 광고 유무
} else {
  if (isset($jsVar['JS_GAME_PATTERN']) && $jsVar['JS_GAME_PATTERN'] == "'matching-maker'") { // 케미 테스트는 maker 문제 선택 중 광고 제외
    $jsVar['JS_GAME_FOOTER_AD'] = "'N'";
  } else {
    $jsVar['JS_GAME_FOOTER_AD'] = "'Y'";
  }
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
  <title><?= PAGE_TITLE ?></title>
  <meta name="description" content="<?= PAGE_DESC ?>">
  <link rel="shortcut icon" href="/favicon.ico" />
  <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
  <link rel="icon" type="image/png" href="/favicon-24x24.png" sizes="24x24">
  <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
  <link rel="image_src" href="<?= $dao->content['afile_regName'] ?>">
  <meta property="twitter:title" content="<?= PAGE_OG_TITLE ?>">
  <meta property="twitter:description" content="<?= PAGE_OG_DESC ?>">
  <meta property="twitter:image" content="<?= PAGE_OG_IMAGE ?>">
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:image:height" content="250">
  <meta property="twitter:image:width" content="500">
  <meta property="og:title" content="<?= PAGE_OG_TITLE ?>" />
  <meta property="og:type" content="article" />
  <meta property="og:url" content="<?= PROD_SITE_URL . $_SERVER["REQUEST_URI"] . $dataUrl ?>" />
  <meta property="og:image" content="<?= PAGE_OG_IMAGE ?>" />
  <meta property="og:description" content="<?= PAGE_OG_DESC ?>" />
  <meta property="og:site_name" content="<?= _t('common.common_site_title', '방구석 연구소') ?>" />
  <meta property="fb:app_id" content="868684923711460" />

  <?php if ($dao->content['astatus'] != 1 || (defined('PAGE_TYPE') && PAGE_TYPE != 'intro')) :  // 방구석연구소 내 게시중이 아닌 테스트는 크롤링 차단 
  ?>
    <meta name="googlebot" content="noindex" />
    <meta name="daumoa" content="noindex" />
    <meta name="naverbot" content="noindex" />
    <meta name="Yeti" content="noindex" />
    <meta name="Zumbot" content="noindex" />
  <?php endif; ?>

  <!-- 200번에서만 전화번호 자동 인식 방지 -->
  <?php if (defined('GAME_IDX') && GAME_IDX == 200) { ?>
    <meta name="format-detection" content="telephone=no" />
  <?php } ?>

  <link rel="stylesheet" href="/gl/game-styles.css<?= $chashver ?>">
  <link rel="stylesheet" href="./styles.css<?= $chashver ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
  <script src="/assets/js/jquery-3.6.0.min.js"></script>
  <script src="/assets/js/jquery-ui.min.js"></script>
  <script>
    // JS용 상수 선언
    <?php
    // footer에서 호출할 스크립트
    $footerScript = array();

    foreach ($jsVar as $_var => $_val) {
      echo "const {$_var} = {$_val}; \n";
      if ($_var == 'JS_GA_ID') { // GA_ID가 입력되어있는 경우 별도 GA 속성 연결 필요!
        $footerScript[] = "gtag('config', JS_GA_ID);";
      }
    }
    ?>
  </script>
</head>

<body class="<?= $userLocale ?>" style="<?= isset($gameBodyBackgroundStyle) && $gameBodyBackgroundStyle != "" ? "background: {$gameBodyBackgroundStyle};" : "" ?>">
  <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_admin/assets/include/gtm_tag.php" ?>

  <div class="app game-wrapper <?= $gameWrapperType ?>" data-app>