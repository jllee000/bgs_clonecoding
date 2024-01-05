<script>
  <? // TODO 모듈화 필요 
  ?>
  let isVisible = false;


  $('.game-wrapper .app-main').on('scroll', function() {
    if (checkVisible($('<?= isset($recommendPopupGameConfig['targetEl']) ? $recommendPopupGameConfig['targetEl'] : '.recommend-list' ?>')) && !isVisible) {
      if (typeof JS_RECOMMEND_POPUP_GAME_IDX !== 'undefined') {
        gameActions.moveToGame(JS_RECOMMEND_POPUP_GAME_IDX);
        isVisible = true;
      }
    }
  });


  function checkVisible(_el, _eval) {

    let _viewportHeight = $(window).height(); // Viewport Height
    let _scrolltop = $(window).scrollTop(); // Scroll Top
    let _y = $(_el).offset().top;
    let _elementHeight = $(_el).height();

    let result = false;
    if (!_eval) {

      if ((_y < (_viewportHeight + _scrolltop)) && (_y > (_scrolltop - _elementHeight))) {
        result = true;
      }
    }
    return result;

  }
</script>