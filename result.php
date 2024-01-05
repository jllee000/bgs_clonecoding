<?php
//평소 json
$Json = file_get_contents("./usually.json");
// Converts to an array 
$usually = json_decode($Json, true);
// prints array
?>

<?php
//멘토 json
$Json = file_get_contents("./mento.json");
// Converts to an array 
$mento = json_decode($Json, true);
// prints array
?>


<?php
//뼈때리는 json
$Json = file_get_contents("./bone.json");
// Converts to an array 
$bone = json_decode($Json, true);
// prints array
?>

<?php
define('GAME_IDX', 1042); // <------------ [GL Guide]: 변경 필
define('PAGE_TYPE', 'result');
define('GOOGLE_ADSENSE_USE', false); // <------------ [GL Guide]: 애드센스 사용 유무 boolean
require_once $_SERVER['DOCUMENT_ROOT'] . '/gl/modules/dao.php';

$csrf = rand();
$_SESSION['csrf'] = $csrf;

$resultCode = isset($_REQUEST['code']) ? Fn_Requestx($_REQUEST['code']) : '';
$resultScore = isset($_REQUEST['score']) ? Fn_Requestx($_REQUEST['score']) : '';

if ($resultCode == "" && $resultScore == "") {
  alert("잘못된 접근입니다!", "/gl/" . GAME_IDX . "/");
  exit;
} else {
  $dao = new DAO(GAME_IDX);

  if (empty($dao->content)) {
    /* 콘텐츠 정보 없을 때, */
    Alert('잘못된 접근입니다.', '/');
    exit;
  }

  if ($resultScore == "") {
    /* 결과 데이터 불러오기 */
    $dao->loadResultData(GAME_IDX, $resultCode);
    if (empty($dao->result)) {
      alert("잘못된 접근입니다!", "/gl/" . GAME_IDX . "/");
      exit;
    }

    /* resultScore 할당 */
    $resultScore = $dao->result['score'];
  }
}

/* 결과 score 데이터 불러오기 */
$dao->loadScoreData();

/* 결과 상세 타이틀 */
$resultTitleConfig = array();
$resultTitleConfig['allowDownImg'] = true;
$resultTitleConfig['descTitle'] = '';

$descTitleArray = explode('[]', $dao->questionResult[$resultScore]['descTitle']);
foreach ($descTitleArray as $descArrayIndex => $descTitleLine) {
  $resultTitleConfig['descTitle'] .= "<p>$descTitleLine</p>";

  if ($descArrayIndex < count($descTitleArray) - 1) {
    $resultTitleConfig['descTitle'] .= "<br />";
  }
}

/* 결과 제목 가져오기 */
$resultTitle = str_replace('[]', ' ', $dao->questionResult[$resultScore]['title']);

$shareImageRoot = CDN_PATH . "/assets/images/game" . GAME_IDX . "/share";
$shareConfig = array();
$shareConfig['title'] = "나의 팩폭BTI는 {$resultTitle}"; // <------------ [GL Guide]: 변경 필
$shareConfig['desc'] = $dao->content['atitle'];
$shareConfig['imageSquare'] = $dao->questionResult[$resultScore]['shareSquareImg'];
$shareConfig['imagePc'] = $dao->questionResult[$resultScore]['sharePcImg']; //kakao pc
$shareConfig['imageM'] = $dao->questionResult[$resultScore]['shareMImg']; // kakao m, facebook

define('PAGE_OG_TITLE', $shareConfig['title']);
define('PAGE_OG_DESC', $shareConfig['desc']);
define('PAGE_OG_IMAGE', $shareConfig['imageM']);

/* Javascript 상수로 사용 */
$jsVar = array();
$jsVar['JS_PAGE_TYPE'] = "'" . PAGE_TYPE . "'";
$jsVar['JS_CSRF'] = "'{$_SESSION['csrf']}'";
$jsVar['JS_GAME_IDX'] = "'" . GAME_IDX . "'";
$jsVar['JS_GAME_TITLE'] = "'{$dao->content['atitle']}'";

$jsVar['JS_SHARE_TITLE'] = "'{$shareConfig['title']}'";
$jsVar['JS_SHARE_DESC'] = "'{$shareConfig['desc']}'";
$jsVar['JS_SHARE_IMG_SQUARE'] = "'{$shareConfig['imageSquare']}'";
$jsVar['JS_SHARE_IMG_PC'] = "'{$shareConfig['imagePc']}'";
$jsVar['JS_SHARE_IMG_M'] = "'{$shareConfig['imageM']}'";



// 유형별 퍼센트 가져오기
$mostContents = $dao->getMostContents(false);
$resultStats = array();

foreach ($mostContents as $i => $mc) {
  $mcResult = $mc['aresult'];
  $precent = str_replace('%', '', $mc['Percentage']);
  $resultStats[$mcResult] = $precent;
}


/* 차트 결과값 */
$filepath = $_SERVER['DOCUMENT_ROOT'] . "/gl/" . GAME_IDX . "/include/results/circleChartConfig.json";
if (file_exists($filepath)) {
  $jsonArr = json_decode(file_get_contents($filepath), true);
  $circleChartConfig = isset($jsonArr[$resultScore]) ? $jsonArr : array();
} else {
  $circleChartConfig = array();
}

/* popup 설정 */
$popupConfig = array();
$popupConfig['most-type-link'] = 'ranking';
$popupConfig['list'] = array(
  0 => 'most-type'
);

$relationConfig = array();
$relationConfig['title'] = '유형별 궁합';

$restartButtonText = "테스트 다시하기";
?>
<!--헤더불러옴-->
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/gl/layout/header.php'; ?>
<header class="app-header result-test-header">
  <a class="app-header-btn back" href="#" onclick="func_goBack(JS_PAGE_TYPE, '/gl/' + JS_GAME_IDX + '/', JS_GAME_TITLE)" title="뒤로가기"></a>
  <a href="/" class="app-logo result-logo">방구석연구소</a>
</header>
<!--헤더 밑에 제목 고정-->
<div class="game-title"><?= $dao->content['atitle'] ?></div>


<!--index.php구조와 같게 위에 선언 후, header, app-main, footer 지정-->
<main class="app-main">
  <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/gl/snippets/result-title.php" ?>
  <div class="result-descTitle">
    <?= $resultTitleConfig['descTitle'] ?>
  </div>
  <div class="result-bti-div">
    <div>나의 팩폭BTI는?</div>
    <div><?= $resultScore ?></div>
    <div>* 본 결과는 성격MBTI와는 다름을 알려드립니다.</div>
  </div>


  <div class="result-explain">
    <!--result부분을 mbti별로 하나하나 하드코딩으로 파는게 아니라, json으로 관리하도록-->
    <!--평소에~부분-->
    <h3 class="result-usually-title">#평소에 나는</h3>
    <ul class="result-usually-ul">
      <?php foreach ($usually[$resultScore] as $key => $value) : ?>
        <li>
          <p class="mark"></p><?= $value ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <!--나에게필요~부분-->
    <h3 class="result-mento-title">#나에게 필요한 멘토는?</h3>
    <div class="result-mentodiv">
      <div class="result-mentoimg"><img src="<?= CDN_PATH ?>/assets/images/game144/character/<?= $resultScore ?>.png" /></div>
      <div>
        <?php foreach ($mento[$resultScore] as $key => $value) : ?>
          <li class="mentotxt">
            <?= $value ?>
          </li>
        <?php endforeach; ?>
      </div>
    </div>


    <!--뼈때리는 조언부분-->
    <h3 class="result-mento-title">#뼈때리는 조언 듣고 성공하는 법</h3>
    <ul class="result-usually-ul">
      <?php foreach ($bone[$resultScore] as $key => $value) : ?>
        <li>
          <p class="mark"></p><?= $value ?>
        </li>
      <?php endforeach; ?>
    </ul>


  </div>
  <div class="result-share">
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/gl/snippets/share-sns.php'; ?>
  </div>


</main>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/gl/snippets/relation-type.php'; ?>



<!-- 테스트 다시하기 -->

<!-- 추천 컨텐츠 -->

<!-- modeled after idx# -->