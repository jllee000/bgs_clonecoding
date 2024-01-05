<?php
$listTypeTitle = isset($listTypeConfig['title']) ? $listTypeConfig['title'] : _t('common.result_rank_now', '실시간 유형 순위'); //타이틀
$isListSorted = isset($listTypeConfig['sort']) ? $listTypeConfig['sort'] : true; //list percent 정렬 여부
$sortingOrder = isset($gameStageInfo) ? $gameStageInfo['grade'] : array();
?>

<?php
if (isset($listTypeConfig['scoreItems'])) {
  $scoreItems = $listTypeConfig['scoreItems'];
} else {
  /* 결과 score 데이터 불러오기 */
  $dao->loadScoreData();
  $scoreItems = $dao->questionResult; // [score] => ([result], [title], ...)
}

$mostContents = $dao->getMostContents(false); // aresult, Count, Percentage
foreach ($mostContents as $mc) {
  $scoreItems[$mc['aresult']]['count'] = $mc['Count'];
  $scoreItems[$mc['aresult']]['percent'] = $mc['Percentage'];
}

function sortingScoreUsingPercent($x, $y)
{
  if (isset($x['count']) && isset($y['count'])) {
    if ($x['count'] > $y['count']) {
      return -1;
    } else if ($x['count'] < $y['count']) {
      return 1;
    } else {
      return 0;
    }
  } else if (isset($x['count'])) {
    return -1;
  } else if (isset($y['count'])) {
    return 1;
  } else {
    return 0;
  }
}

function sortingScoreUsingGrade($x, $y)
{
  global $sortingOrder;
  return array_search($x, $sortingOrder) < array_search($y, $sortingOrder) ? -1 : 1;
}

if ($isListSorted) {
  uasort($scoreItems, 'sortingScoreUsingPercent');
} else if ($sortingOrder) {
  uksort($scoreItems, 'sortingScoreUsingGrade');
}

// List Item 텍스트를 다르게 해야 할 경우 사용
$listItemDataFile = $_SERVER['DOCUMENT_ROOT'] . "/gl/" . GAME_IDX . "/include/listItemData.json";
if (file_exists($listItemDataFile)) {
  $jsonData = file_get_contents($listItemDataFile);
  $listItemData = json_decode($jsonData, true);
}
?>

<div class="test-lists-wrap rank-page" style="<?= isset($rankListStyle) ? $rankListStyle : '' ?>">
  <div class="rank_title_wrap text-center">
    <h1 class="title"><?= $listTypeTitle ?></h1>
  </div>
  <ul class="test-lists">
    <?php
    $rankNum = 1;
    foreach ($scoreItems as $scr => $sdt) :
      if (isset($listTypeConfig['listItem']) && $listTypeConfig['listItem'] == true) {
        include $_SERVER['DOCUMENT_ROOT'] . "/gl/" . GAME_IDX . "/include/list-item.php";
      } else {
        include $_SERVER['DOCUMENT_ROOT'] . "/gl/snippets/list-item.php";
      }
    endforeach; ?>
  </ul>
</div>