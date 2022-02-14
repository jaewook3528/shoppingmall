<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_cs.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>고객센터<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<h3 class="anc_tit">입금받으실 계좌</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">은행명</th>
			<td><?php echo $partner['bank_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">계좌번호</th>
			<td><?php echo $partner['bank_account']; ?></td>
		</tr>
		<tr>
			<th scope="row">예금주명</th>
			<td><?php echo $partner['bank_holder']; ?></td>
		</tr>
		</tbody>
		</table>
	</div>

	<h3 class="anc_tit mart30">결제정보</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">신청일시</th>
			<td><?php echo $partner['reg_time']; ?></td>
		</tr>
		<tr>
			<th scope="row">결제방법</th>
			<td><?php echo ($partner['pay_settle_case']=='1')?"무통장입금":"신용카드"; ?></td>
		</tr>
		<tr>
			<th scope="row">결제금액</th>
			<td>
				<?php 
				if($partner['receipt_price'] > 0) 
					echo display_price($partner['receipt_price']);
				else
					echo '무료';
				?>
			</td>
		</tr>
		<?php if($partner['pay_settle_case']=='1') { ?>		
		<tr>
			<th scope="row">입금계좌</th>
			<td><?php echo $partner['bank_acc']; ?></td>
		</tr>
		<tr>
			<th scope="row">입금자명</th>
			<td><?php echo $partner['deposit_name']; ?></td>
		</tr>
		<?php } ?>
		<?php if($partner['memo']) { ?>
		<tr>
			<th scope="row">전달사항</th>
			<td><?php echo conv_content($partner['memo'], 0); ?></td>
		</tr>
		<?php } ?>
		</table>
	</div>

	<div class="btn_confirm">
		<a href="<?php echo TB_URL; ?>" class="btn_large wset">확인</a>
	</div>
</div>
