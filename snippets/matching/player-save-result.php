<div class="btn_save">
  <a href="javascript:matchingGameActions.saveMyMatchingData('player')" class="save-button">이 결과 저장하기</a>
</div>

<script>
  function player_moveToTypeRank() {

    let playerSession = '<?= isset($_SESSION['game'][GAME_IDX]['playerType']) ? $_SESSION['game'][GAME_IDX]['playerType'] : '' ?>';

    if (playerSession && playerSession === 'player') {
      matchingGameActions.saveMyMatchingData('player');
    } else {
      alert('테스트를 앱이 아닌 웹에서 진행하신 경우\r\n진행했던 웹 브라우저에서 저장하실 수 있습니다.\r\n※다른 사람이 진행한 결과는 저장하실 수 없습니다.');
    }
  }
</script>