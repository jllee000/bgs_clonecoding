<div class="game-timer">
  <div class="game-timer-clock">
    <img src="<?= CDN_PATH ?>/assets/images/common/clock.png" alt="타이머">
  </div>
  <div class="game-timer-progressbar">
    <div class="game-timer-progressbar-inner"></div>
  </div>
</div>

<script>
  function gameTimer() {
    this.goToNextTimer = null;
    this.shakingClockTimer = null;
  }

  gameTimer.prototype.set = (_callback, _time, _type) => {
    let _timer = setTimeout(function() {
      _callback && _callback();
    }, _time);

    if (_type === 'shake') {
      this.shakingClockTimer = _timer;
    } else {
      this.goToNextTimer = _timer;
    }
  }

  gameTimer.prototype.clear = () => {
    clearTimeout(this.goToNextTimer);
    clearTimeout(this.shakingClockTimer);
    this.goToNextTimer = null;
    this.shakingClockTimer = null;
  }

  function startDrawingGraph(_time) {
    $('.game-timer-progressbar-inner').animate({
      width: "0%"
    }, _time);

    setTimer.set(function() {
      gameActions.nextStep();
    }, _time);

    setTimer.set(function() {
      $('.game-timer .game-timer-clock').css({
        animation: 'shake-clock 2s infinite linear'
      });
    }, _time / 2, 'shake');
  }

  var setTimer = new gameTimer();

  if (typeof setTimer !== undefined) {
    setTimer.clear();
    startDrawingGraph(<?= $gameStageValue['timeLimit'] ?>);
  }
</script>