<?php
$circleChartConfig = [
  "ENTJ" => ["70", "80", "80", "70"],
  "ENTP" => ["100", "100", "95", "90"],
  "ENFJ" => ["95", "70", "75", "70"],
  "ENFP" => ["80", "90", "70", "85"],
  "ESTJ" => ["75", "70", "85", "60"],
  "ESTP" => ["90", "90", "85", "70"],
  "ESFJ" => ["70", "80", "60", "65"],
  "ESFP" => ["60", "70", "55", "85"],
  "INTJ" => ["75", "90", "85", "70"],
  "INTP" => ["65", "85", "60", "90"],
  "INFJ" => ["85", "70", "70", "90"],
  "INFP" => ["85", "75", "70", "90"],
  "ISTJ" => ["70", "75", "80", "80"],
  "ISTP" => ["85", "60", "90", "75"],
  "ISFJ" => ["65", "95", "60", "95"],
  "ISFP" => ["85", "60", "90", "80"],
];
$circleChartTextConfig = [
  "type" => [["E", "I"], ["N", "S"], ["F", "T"],["J", "P"]],
  "left" => ["E", "N", "F", "J"],
  "caption" => ["E" => "외향형(E)", "I" => "내향형(I)", "N" => "도전형(N)", "S" => "안정형(S)", "F" => "간접형(F)", "T" => "직접형(T)", "J" => '계획형(J)', "P" => "유연형(P)"]
]
?>

<div class="circle-chart-wrapper">
  <?php
  foreach ($circleChartConfig[$resultScore] as $cIndex => $cItem) {
    $resultType = substr($resultScore, $cIndex, 1);
    $cPercent = $resultType == $circleChartTextConfig["type"][$cIndex][1] ? $circleChartConfig[$resultScore][$cIndex] :  $circleChartConfig[$resultScore][$cIndex];
  ?>
    <div class="circle-chart-inside">
      <div class="circle-chart-box">
        <section>  
          <? if(in_array($resultType, $circleChartTextConfig['left'])) : ?>   
          <div class="graph-background">
            <div class="percent-background" style="width:<?= $cPercent ?>%"></div>
            <div class="end-point left" style="margin-right:<?= 100 - $cPercent ?>%">$</div>
          </div>
          <? else :?>
            <div class="graph-background" style="transform:scaleX(-1)">
            <div class="percent-background" style="width:<?= $cPercent ?>%"></div>
            <div class="end-point right" style="margin-right:<?= 100 - $cPercent ?>%">$</div>
          </div>
          <? endif; ?>
          <div class="circle-chart-caption"> 
            <?php
            foreach ($circleChartTextConfig["type"][$cIndex] as $cType) {
              echo "<span>" . $circleChartTextConfig["caption"][$cType] . "</span>";
            }
            ?>
          </div>
        </section>
      </div>
    </div>
  <? } ?>
</div>