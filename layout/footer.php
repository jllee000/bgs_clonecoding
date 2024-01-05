<?php
if (defined('GOOGLE_ADSENSE_USE') && GOOGLE_ADSENSE_USE === true) {
?>
  <div class="ads-banner-wrap">
    <?php
    $adsPush = '';
    $adsTarget = 'gl-contents';
    include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/include/ads-google.php";
    $adsPush = "(adsbygoogle = window.adsbygoogle || []).push({});\n";
    $adsPush .= "$('.game-wrapper .app-main').addClass('ads-app-main-footer');";
    ?>
  </div>
<?php
}
?>
</div><!-- End of .app -->
<!-- Scripts -->
<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/_admin/assets/include/Pixel.php" ?>
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script src="/assets/js/soundController.js<?= $chashver ?>"></script>
<script src="https://cdn.banggooso.com/assets/js/clipboard.min.js"></script>
<script src="/assets/js/common-re.js<?= $chashver ?>"></script>
<script src="/gl/gameActions.js<?= $chashver ?>"></script>
<script src="/gl/matchingGameActions.js<?= $chashver ?>"></script>
<script src="/gl/myAvatarGameActions.js<?= $chashver ?>"></script>
<script src="/gl/growGameActions.js<?= $chashver ?>"></script>
<script src="/gl/runGameActions.js<?= $chashver ?>"></script>
<script src="/gl/argueGameActions.js<?= $chashver ?>"></script>
<script src="/gl/quizGameActions.js<?= $chashver ?>"></script>
<script src="/gl/avatarMatchingGameActions.js<?= $chashver ?>"></script>
<script src="/gl/func.js<?= $chashver ?>"></script>
<script src="/assets/js/html2canvas_custom.js<?= $chashver ?>"></script>
<script>
  <?= isset($adsPush) ? $adsPush : ''; // 구글 애드센스 
  ?>

  <?= implode(' ', $footerScript) ?>
</script>
<script type="text/JavaScript" src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
<script type="text/JavaScript" src="/assets/js/share-sns.js<?= $chashver ?>"></script>
<?php if (isset($shareConfig['multiSnsUse']) && $shareConfig['multiSnsUse'] === true) { ?>
  <script type="text/JavaScript" src="/gl/<?= GAME_IDX ?>/share-multiple-sns.js<?= $chashver ?>"></script>
<?php } ?>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/assets/popups/index.php'; ?>
</body>

</html>