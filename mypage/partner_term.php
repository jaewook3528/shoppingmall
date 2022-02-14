<?php
if(!defined('_TUBEWEB_')) exit;

if(!$config['pf_expire_use'])
	alert('관리비 사용이 중지되었습니다.', './page.php?code=partner_info');

$pg_title = "가맹점 연장신청";
include_once("./admin_head.sub.php");

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_partner_term ";
$sql_search = " where mb_id = '{$member['id']}' ";
$sql_order  = " order by index_no desc ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택취소" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
EOF;

// 총 납부 건수
$sql2 = " select count(*) as cnt 
		    from shop_partner_term 
		   where mb_id = '{$member['id']}' 
		     and state = '1' ";
$term = sql_fetch($sql2);
$term_count = (int)$term['cnt'] + 1;

$pb = get_partner_basic($member['grade']);
?>

<h2>기간 연장</h2>
<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w120">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">가맹점 만료일</th>
		<td><b><?php echo $member['term_date']; ?></b></td>
	</tr>
	<tr>
		<th scope="row">연장기간</th>
		<td>
			<select name="expire_date">
				<?php
				for($i=1; $i<=5; $i++) {
					$date = $i * (int)$config['pf_expire_term'];
					echo "<option value=\"{$date}\">{$date}개월</option>\n";
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">결제방법</th>
		<td>
			<input type="radio" name="pay_method" value="무통장" id="pay_method" checked="checked">
			<label for="pay_method">무통장입금</label>		
		</td>
	</tr>
	<tr>
		<th scope="row">결제금액</th>
		<td>
			<input type="hidden" name="term_price" value="<?php echo $pb['gb_term_price']; ?>">
			<b class="fc_red"><?php echo number_format($pb['gb_term_price']); ?></b>원
			(총: <?php echo number_format($term_count); ?>회 납부)
		</td>
	</tr>
	<tr>
		<th scope="row">입금계좌</th>
		<td>
			<?php echo get_bank_account("bank_account"); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">입금자명</th>
		<td><input type="text" name="deposit_name" value="<?php echo $member['name']; ?>" required itemname="입금자명" class="required frm_input"></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="신청" class="btn_medium red">
</div>
</form>

<form name="ftermlist" id="ftermlist" method="post" action="./partner_term_delete.php" onsubmit="return ftermlist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>	
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w50">
		<col class="w50">
		<col class="w60">
		<col class="w140">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w140">
		<col>		
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col">번호</th>
		<th scope="col">승인</th>
		<th scope="col">신청일시</th>
		<th scope="col">연장기간</th>
		<th scope="col">결제방법</th>
		<th scope="col">결제금액</th>
		<th scope="col">입금자명</th>
		<th scope="col">본사 입금계좌</th>		
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($row['state']) { // 승인완료
			$disabled = " disabled";
			$td_bg = "";
		} else {	
			$disabled = "";			
			$td_bg = " style='background:yellow;'";
		}

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td<?php echo $td_bg; ?>>			
			<input type="hidden" name="index_no[<?php echo $i; ?>]" value="<?php echo $row['index_no']; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>"<?php echo $disabled; ?>>
		</td>
		<td><?php echo $num--; ?></td>
		<td><?php echo $row['state']?'완료':'<span class="fc_255">대기</span>'; ?></td>
		<td><?php echo $row['reg_time']; ?></td>
		<td><?php echo $row['expire_date']; ?>개월</td>
		<td><?php echo $row['pay_method']; ?></td>
		<td><?php echo number_format($row['term_price']); ?></td>
		<td><?php echo $row['deposit_name']; ?></td>
		<td class="tal"><?php echo $row['bank_account']; ?></td>		
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="9" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
function fregform_submit(f)
{
    errmsg = "";
    errfld = "";

	check_field(f.bank_account, "입금계좌를 선택하세요");
	check_field(f.deposit_name, "입금자명을 입력하세요");

    if(errmsg)
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

	if(!confirm("신청 하시겠습니까?"))
		return false;

	f.action = "./partner_term_update.php";
    return true;
}

function ftermlist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택취소") {
        if(!confirm("선택한 자료를 정말 취소하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>

<?php
include_once("./admin_tail.sub.php");
?>