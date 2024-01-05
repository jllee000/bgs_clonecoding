<div class="topRanking">
  <h3><?= $rankingConfig['rankingTitle'] ?> TOP10</h3>
  <div class="list_content_title">
    <ul class="list_title">
      <li>
        <ul>
          <li class="number">번호</li>
          <li class="id">ID</li>
          <li class="score">맞은 개수</li>
          <li class="time">소요시간</li>
        </ul>
      </li>
    </ul>
  </div>
<div class="list_content_div">
  <ul id="list_content"></ul>
</div>
<div class="btn_refresh btn_refresh2">
  <img src="<?= CDN_PATH ?>/assets/images/common/re-test-b.png" alt="새로고침 하기" onclick="refreshAllRank();">
</div>

<script>
  async function getAllRank() {
    let order = {
      'arank1': 'DESC',
      'arank2': 'ASC'
    };
    return await gameActions.getAllRanking(10, order);
  }

  async function setAllRank(_rankingList) {
    let $listWrap = $('#list_content');
    _rankingList.forEach(function({
      rank,
      userName,
      arank1,
      time,
    }) {
      html = `<li><ul><li>${rank}</li><li>${userName}</li><li>${arank1}</li><li>${time}</li></ul></li>`;
      $listWrap.append(html);
    })
  }

  async function refreshAllRank() {
    $('#list_content').empty();
    let rankList = await getAllRank();
    setAllRank(rankList);
  }

  $(document).ready(function() {
    refreshAllRank();
  });
</script>