<?
$listItemSubTitle = $scoreItems[$scr]['subTitle'];
$listItemTitle = $scoreItems[$scr]['title'];
$listItemDescTitle = $scoreItems[$scr]['descTitle'];
if ($listItemData) {
  $listItemData[$scr]['subTitle'] && $listItemSubTitle = $listItemData[$scr]['subTitle'];
  $listItemData[$scr]['title'] && $listItemTitle = $listItemData[$scr]['title'];
  $listItemData[$scr]['descTitle'] && $listItemDescTitle = $listItemData[$scr]['descTitle'];
}

//iframe시 wv값 추가
$listTypeWVParam = isset($listTypeConfig['additionalUrlQuery']) ? '&' . $listTypeConfig['additionalUrlQuery'] : '';
?>

<li class="test-list-item rank_list">
  <a class="item-wrap" href="./result?score=<?= $sdt['result'] . $listTypeWVParam ?>">
    <div class="num"><?= $rankNum++ ?></div>
    <div class="item-info">
      <h4 class="item-title">
        <p><?= _t("game.result{$scr}Subtitle", $listItemSubTitle) ?></p>
        <span><?= str_replace("[]", "<br />", _t("game.result{$scr}Title", $listItemTitle)) ?></span>
      </h4>
      <ul class="item-tags">
        <li><?= str_replace('[]', '<br />', _t("game.result{$scr}Text", $listItemDescTitle)) ?></li>
      </ul>
    </div>
    <figure class="thumb">
      <div class="percen"><?= _t('common.result_type_percentage', '<span class="percen_bold">%s</span>가 이 유형입니다.', isset($sdt['percent']) ? $sdt['percent'] : '0%') ?></div>
      <img src="<?= _t("game.result{$scr}ImageMain", $scoreItems[$scr]['mainImg']) ?>">
    </figure>
  </a>
</li>