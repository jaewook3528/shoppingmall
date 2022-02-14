<?php
include_once('./_common.php');
include_once(TB_MSHOP_PATH.'/settle_inicis.inc.php');

// 세션 초기화
set_session('P_TID',  '');
set_session('P_AMT',  '');
set_session('P_HASH', '');

$sql = " select * from shop_order_data where od_id = '$oid' ";
$row = sql_fetch($sql);

$data = unserialize(base64_decode($row['dt_data']));

$order_action_url = TB_HTTPS_MSHOP_URL.'/orderformresult.php';
$page_return_url  = TB_MSHOP_URL.'/orderinicis.php?od_id='.$oid;

$sql = " select * from shop_inicis_log where oid = '$oid' ";
$row = sql_fetch($sql);

if(!$row['oid'])
    alert('결제 정보가 존재하지 않습니다.\\n\\n올바른 방법으로 이용해 주십시오.', $page_return_url);

if($row['P_STATUS'] != '00')
    alert('오류 : '.$row['P_RMESG1'].' 코드 : '.$row['P_STATUS'], $page_return_url);

$PAY = array_map('trim', $row);

// TID, AMT 를 세션으로 주문완료 페이지 전달
$hash = md5($PAY['P_TID'].$PAY['P_MID'].$PAY['P_AMT']);
set_session('P_TID',  $PAY['P_TID']);
set_session('P_AMT',  $PAY['P_AMT']);
set_session('P_HASH', $hash);

// 로그 삭제
@sql_query(" delete from shop_inicis_log where oid = '$oid' ");

$tb['title'] = 'KG 이니시스 결제';
$tb['body_script'] = ' onload="setPAYResult();"';
include_once(TB_MPATH.'/head.sub.php');

$exclude = array('res_cd', 'P_HASH', 'P_TYPE', 'P_AUTH_DT', 'P_VACT_BANK', 'P_AUTH_NO');

echo '<form name="forderform" method="post" action="'.$order_action_url.'" autocomplete="off">'.PHP_EOL;

echo make_order_field($data, $exclude);

echo '<input type="hidden" name="res_cd"      value="'.$PAY['P_STATUS'].'">'.PHP_EOL;
echo '<input type="hidden" name="P_HASH"      value="'.$hash.'">'.PHP_EOL;
echo '<input type="hidden" name="P_TYPE"      value="'.$PAY['P_TYPE'].'">'.PHP_EOL;
echo '<input type="hidden" name="P_AUTH_DT"   value="'.$PAY['P_AUTH_DT'].'">'.PHP_EOL;
echo '<input type="hidden" name="P_VACT_BANK" value="'.$PAY['P_FN_NM'].'">'.PHP_EOL;
echo '<input type="hidden" name="P_AUTH_NO"   value="'.$PAY['P_AUTH_NO'].'">'.PHP_EOL;

echo '</form>'.PHP_EOL;
?>

<div id="pay_working" style="display:none;">
     <span style="display:block;text-align:center;margin-top:120px"><img src="<?php echo TB_MSHOP_URL; ?>/img/loading.gif" alt=""></span>
    <span style="display:block;text-align:center;margin-top:10px;font-size:14px">주문완료 중입니다. 잠시만 기다려 주십시오.</span>
</div>

<script type="text/javascript">
function setPAYResult() {
    setTimeout( function() {
        document.forderform.submit();
    }, 300);
}
</script>

<?php
include_once(TB_MPATH.'/tail.sub.php');
?>