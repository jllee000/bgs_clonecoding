<div class="pop_induce pop_induce02 list" style="display:none;">
  <div class="popup">
    <img src="<?= CDN_PATH ?>/assets/images/common/pop_induce02.png" alt="팝업순위보러가기">
    <div class="btn_exit btn" onclick="gameActions.closeGameRank();"><img src="<?= CDN_PATH ?>/assets/images/common/btn_exit.png" alt="팝업 닫기 버튼"></div>
    <div class="btn_induce btn" style="bottom: 2.29rem">
      <a class="btn_induce" href="#" onclick="javascript:gameActions.moveToSignupPage('내 순위 보러가기');"><img src="<?= CDN_PATH ?>/assets/images/common/btn_induce02.png" alt="팝업 회원가입 버튼"></a>
      <a class="btn_login" href="#" onclick="javascript:gameActions.moveToLoginPage('내 순위 보러가기');"><img src="<?= CDN_PATH ?>/assets/images/common/btn_login.png" alt="팝업 로그인 버튼"></a>
    </div>
    <div class="wrap">
      <div class="btn_goRank">
        <a href="javascript:gameActions.goToGameRank(<?= $resultCode ?>)"><?= _t('common.pop_see_rank_later', '나중에요. 일단 순위보러 갈래요.') ?></a>
      </div>
    </div>
    <div class="checkbox"><input type="checkbox" name="check_list" id="check_list"><label for="check_list"><?= _t('common.pop_not_today', '오늘은 그만 볼래요.') ?></label></div>
  </div>
</div>