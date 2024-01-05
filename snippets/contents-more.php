<?
// To-do-list : 자주 쓰인다면 snippets으로 옮기기 
$btnArr = [
  [
    "name" => '인스타툰 보러가기',
    "GA_link" => 'https://www.instagram.com/banggooso.kr/',
    "GA_name" => '결과페이지 - 인스타툰 보러가기 버튼',
  ],
  [
    "name" => '트위터 보러가기',
    "GA_link" => 'https://twitter.com/banggooso',
    "GA_name" => '결과페이지 - 트위터 보러가기 버튼',
  ],
  [
    "name" => '카카오톡 채널 추가하기',
    "GA_link" => 'https://pf.kakao.com/_iGbxmK',
    "GA_name" => '결과페이지 - 카카오톡 채널 추가하기 버튼',
  ],
];
?>
<div class="contents-more">
  <div class="contents-main">
    <div class="contents-logo">
      <img src="https://cdn.banggooso.com/assets/images/common/logo.png">
    </div>
    <div class="sub-text">
      더 많은 이야기가<br>궁금할 땐
    </div>
  </div>
  <div class="contents-list">
    <? for ($i = 0; $i < count($btnArr); $i++) { ?>
      <div class="game-btn">
        <a href="javascript:gameActions.moveToBannerLink('<?= $btnArr[$i]['GA_link'] ?>', '<?= $btnArr[$i]['GA_name'] ?>');">
          <?= $btnArr[$i]['name'] ?>
        </a>
      </div>
    <? } ?>
  </div>
</div>