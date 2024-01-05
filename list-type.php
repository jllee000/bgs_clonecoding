<?php
define('GAME_IDX', 1042); // <------------ [GL Guide]: 변경 필
define('PAGE_TYPE', 'list-type');

require_once $_SERVER['DOCUMENT_ROOT'] . '/gl/modules/dao.php';

$csrf = rand();
$_SESSION['csrf'] = $csrf;

$dao = new DAO(GAME_IDX);

$gameBlockMsg = null;
if (empty($dao->content)) {
  /* 콘텐츠 정보 없을 때, */
  // $gameBlockMsg = '존재하지 않는 테스트입니다.';
  $gameBlockMsg = '잘못된 접근입니다.';
} else {
  /* 게임 차단 안 함! */
}

if ($gameBlockMsg != null) {
  echo "<script>";
  if ($gameBlockMsg != '') {
    echo "alert('{$gameBlockMsg}');";
  }
  echo "location.href = '/'";
  echo "</script>";
  exit;
}

/* Javascript 상수로 사용 */
$jsVar = array();
$jsVar['JS_PAGE_TYPE'] = "'" . PAGE_TYPE . "'";
$jsVar['JS_GAME_IDX'] = "'" . GAME_IDX . "'";
$jsVar['JS_CSRF'] = "'{$_SESSION['csrf']}'";
$jsVar['JS_GAME_TITLE'] = "'{$dao->content['atitle']}'";
$jsVar['JS_SHARE_TITLE'] = "'{$dao->content['atitle']}'";
$jsVar['JS_SHARE_DESC'] = "'{$dao->content['acontent']}'";
$jsVar['JS_SHARE_IMG_PC'] = "'{$dao->content['afile_regName']}'";
$jsVar['JS_SHARE_IMG_M'] = "'{$dao->content['afile_regName_m']}'";
$jsVar['JS_SHARE_IMG_SQUARE'] = "'{$dao->content['afile_regName3']}'";

$listTypeConfig['listItem'] = true; // 커스텀 유형별 순위 유무
?>
<?php
// $gameBodyBackgroundStyle = "";
require_once $_SERVER['DOCUMENT_ROOT'] . '/gl/layout/header.php';
?>

<header class="app-header result-test-header">
  <a class="app-header-btn back" href="javascript:func_goBack(JS_PAGE_TYPE, document.referrer, JS_GAME_TITLE);" title="뒤로가기"><i class="app-header-icon fas fa-chevron-left"></i></a>
  <a href="/" class="app-logo">방구석연구소</a>
</header>

<div class="game-title rank-page"><?= $dao->content['atitle'] ?></div>
<main class="app-main">

  <?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/gl/snippets/list-type.php';
  ?>

</main>

<small>
  <?php
  // 개발 디버깅 용 영역
  // echo "<pre>" . print_r($_SESSION['game'], 1) . "</pre>";
  ?>
</small>


<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/gl/layout/footer.php'; ?>

<!-- modeled after idx#49 -->