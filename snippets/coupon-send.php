<script>
  let alertMsgArray = {
    "0000": {
      "useyn": "Y",
      "msg": "성공"
    },
    "4001": {
      "useyn": "Y",
      "msg": "테스트에 잔여 쿠폰이 없습니다"
    },
    "4002": {
      "useyn": "Y",
      "msg": "세션 아이디 부재"
    },
    "4003": {
      "useyn": "Y",
      "msg": "해당 아이디가 이미 쿠폰을 보유"
    },
    "5001": {
      "useyn": "Y",
      "msg": "문자 발송 실패"
    },
    "9999": {
      "useyn": "Y",
      "msg": "알 수 없는 에러"
    }
  };
  async function getCoupon(_idx, coupon_type = '', _aphone = '') {
    const couponType = coupon_type;
    const authType = '<?= $couponConfig['authType'] ?>';
    const sendType = '<?= $couponConfig['sendType'] ?>';
    const couponCheckType = '<?= $couponConfig['couponCheckType'] ?>';
    const aphone = _aphone;
    const aid = '<?= $_SESSION['u_aid'] ?>';
    const resultTypeArray = <?= json_encode($couponConfig['couponResultType']) ?>;


    <?php if (isset($couponConfig['alertCustom'])) : ?>
      alertMsgArray = <?= json_encode($couponConfig['alertCustom']) ?>;
    <?php endif; ?>

    if (!aphone) {
      // 로그인 팝업
      alert('폰번호 입력 해주세요');
      return;
    }

    // 쿠폰 난수번호 노출

    let rtnCoupon = '';
    let rtnCouponDesc = '';
    let rtnCouponType = '';

    try {
      const couponApiResult = await $.ajax({
        type: 'post',
        data: {
          proc: 'coupon-user',
          idx: _idx,
          authtype: authType,
          aphone: aphone,
          aid: '',
          send_type: sendType,
          coupon_check_type: couponCheckType,
          coupon_type: couponType,
          coupon_result_type: {
            "result_type_array": resultTypeArray
          },
          locale: '',
        },
        url: '/ajax/ajax.php',
        dataType: 'json',
      });

      if (couponApiResult.status === 'success' || couponApiResult.code === '4003') {
        // 쿠폰을 가져오는데 성공하거나 이미 보유한 경우(4003)
        rtnCoupon = couponApiResult.response.result.coupon_code;
        rtnCouponDesc = couponApiResult.response.result.coupon_desc;
        rtnCouponType = couponApiResult.response.result.coupon_type;
        rtnErrCode = couponApiResult.code;
        if (alertMsgArray[couponApiResult.code]['useyn'] == "Y") {
          alert(alertMsgArray[couponApiResult.code]['msg']);
        }

      } else if (couponApiResult.code === '4001') { // 쿠폰을 전부 소진
        if (alertMsgArray[couponApiResult.code]['useyn'] == "Y") {
          alert(alertMsgArray[couponApiResult.code]['msg']);
        }

        return;
      } else {
        if (alertMsgArray['9999']['useyn'] == "Y") {
          alert(alertMsgArray['9999']['msg']);
          return;
        }
      }
    } catch (e) {
      if (alertMsgArray['9999']['useyn'] == "Y") {
        alert(alertMsgArray['9999']['msg']);
        return;
      }
    }

    return {
      err_code: rtnErrCode,
      coupon_code: rtnCoupon,
      coupon_desc: rtnCouponDesc,
      coupon_type: rtnCouponType
    }
  }
</script>