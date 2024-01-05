<?php
$buttonScript = '';
if ($BrowserType_ == 'M' && strpos($_SERVER['HTTP_USER_AGENT'], 'Banggooso') > -1) {
  $buttonScript = "goToRelationPage('" . GAME_IDX . "');";
} else {
  //웹에서 앱으로 열기 팝업
  $buttonScript = "openConnectAppPopup('" . GAME_IDX . "')";
}
?>

<div class="game-btn-wrapper">
  <a class="game-btn relation-btn" href="#" onclick="<?= $buttonScript ?>">
    궁합 보러가기
  </a>
</div>

<script>
  function goToRelationPage(_idx) {
    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - 궁합 알아보기`,
      _category: `${JS_GAME_TITLE} - ${pageTypeText[JS_PAGE_TYPE]}`,
      _label: `궁합 알아보기 버튼`,
    });

    location.href = '/gl/' + _idx + '/list-match';
  }

  function openConnectAppPopup(_idx) {
    func_handleEventGtag({
      _title: `${JS_GAME_TITLE} - 앱 다운 팝업`,
      _category: `${JS_GAME_TITLE} - 결과 페이지`,
      _label: `가입/앱다운 유도`,
    });

    popup_openPopupModule('app-open', _idx);
  }
</script>