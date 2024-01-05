<?
$shareTitle = [
  'ENFP' => '남들 따라 주식 하는<br/>호기심 많은 팔랑귀',
  'ENTP' => '내일 없이 사는<br/>화끈한 스릴 라이더',
  'ESFP' => '눈 뜨고 코 베이는<br/>재테크 초년생',
  'ESTP' => '커피값도 겨우 버는<br/>노력형 빈털털이',
  'ENFJ' => '시선을 즐기는<br/>단톡방 인플루언서 ',
  'ENTJ' => '잔머리 잘 굴리는<br/>빈틈 공략가',
  'ESFJ' => '떡상만 기다리는<br/>마이너스 텅장 주인',
  'ESTJ' => '몸이 10개라도 부족한<br/>프로 참견러',
  'INFP' => '돈에만 반응하는<br/>머니 사냥꾼',
  'INTP' => '이론만 빠삭한<br/>방구석 분석가',
  'ISFP' => '흘러가는 대로 사는<br/>극강의 귀차니즘',
  'ISTP' => '하루 종일 눈치 보는<br/>소심한 쫄보',
  'INFJ' => '필요할 때만 찾는<br/>뻔뻔한 마이웨이러',
  'INTJ' => '떡락에도 존버 하는<br/>자기합리화 끝판왕',
  'ISFJ' => '돈 앞에 얄짤 없는<br/>극한의 효율러',
  'ISTJ' => '꿀팁 혼자만 보는<br/>깐깐한 욕심쟁이'
];

$shareSubtitle = [
  'ENFP' => '#수익률에 일희일비 #단타',
  'ENTP' => '#고위험투자 #과감한',
  'ESFP' => '#투자에 생소한 #주로 예금,적금',
  'ESTP' => '#경험 중시 #노력캐',
  'ENFJ' => '#전문성 어필 #수익 인증',
  'ENTJ' => '#미래지향적 #흐름을 잘 읽는',
  'ESFJ' => '#긍정적 #참을성 있는',
  'ESTJ' => '#아마추어 전문가 #계획적인',
  'INFP' => '#욕심이 많은 #분산투자 하는',
  'INTP' => '#입만 산 #실력에 비해 꿈이 큰',
  'ISFP' => '#재테크에 소질이 없는<br/>#남들 따라 하는',
  'ISTP' => '#안정성 최우선 #타이밍을 못 찾는',
  'INFJ' => '#정보 수집가 #눈치가 빠른',
  'INTJ' => '#뚝심 있는 #하락장에도 굳건한',
  'ISFJ' => '#효율 추구 #이해타산적',
  'ISTJ' => '#목표 수익이 높은 #철두철미'
];
?>



<li class="test-list-item rank_list">
  <a class="item-wrap" href="./result?score=<?= $sdt['result'] ?>">
    <div class="num"><?= $rankNum++ ?></div>
    <div class="item-info">
      <h4 class="item-title">
        <p><?= $scoreItems[$scr]['subTitle'] ?></p>
        <span><?= $shareTitle[$scr] ?></span>
      </h4>
      <ul class="item-tags">
        <li><?= $shareSubtitle[$scr] ?></li>
      </ul>
    </div>
    <figure class="thumb">
      <div class="percen"><span class="percen_bold"><?= $sdt['percent'] ?></span>가 이 유형입니다.</div>
      <img src="<?= $scoreItems[$scr]['mainImg'] ?>">
    </figure>
  </a>
</li>