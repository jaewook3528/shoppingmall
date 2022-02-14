<?php
include_once('./_common.php');

$tax_mny = preg_replace('/[^0-9]/', '', $_POST['mod_tax_mny']);
$free_mny = preg_replace('/[^0-9]/', '', $_POST['mod_free_mny']);

if(!$tax_mny && !$free_mny)
    alert('과세 취소금액 또는 비과세 취소금액을 입력해 주십시오.');

if(!trim($mod_memo))
    alert('요청사유를 입력해 주십시오.');

// 주문정보
$sql = " select * from shop_order where od_id = '$od_id' and od_no = '$od_no' ";
$od = sql_fetch($sql);

if(!$od['od_id'])
    alert_close('주문정보가 존재하지 않습니다.');

if($od['paymethod'] == '계좌이체' && substr($od['receipt_time'], 0, 10) >= TB_TIME_YMD)
    alert_close('실시간 계좌이체건의 부분취소 요청은 결제일 익일에 가능합니다.');

// 금액비교
$amount = get_order_spay($od_id); // 결제정보 합계
$od_receipt_price = $amount['useprice'] - $amount['refund'];

if(($tax_mny && $free_mny) && ($tax_mny + $free_mny) > $od_receipt_price)
    alert('과세, 비과세 취소금액의 합을 '.display_price($od_receipt_price).' 이하로 입력해 주십시오.');

if($tax_mny && $tax_mny > $od_receipt_price)
    alert('과세 취소금액을 '.display_price($od_receipt_price).' 이하로 입력해 주십시오.');

if($free_mny && $free_mny > $od_receipt_price)
    alert('비과세 취소금액을 '.display_price($od_receipt_price).' 이하로 입력해 주십시오.');

// 가맹점 PG결제 정보
$default = set_partner_value($od['od_settle_pid']);

// PG사별 부분취소 실행
include_once(TB_SHOP_PATH.'/'.strtolower($od['od_pg']).'/orderpartcancel.inc.php');

include_once(TB_ADMIN_PATH."/admin_head.php");
?>

<script>
alert("<?php echo $od['paymethod']; ?> 부분취소 처리됐습니다.");
opener.document.location.reload();
self.close();
</script>

<?php
include_once(TB_ADMIN_PATH."/admin_tail.sub.php");
?>