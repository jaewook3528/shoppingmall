<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "전체 거래진행 통계내역";
include_once("./admin_head.sub.php");

$sql_where = " where seller_id = '{$seller['seller_code']}' ";

$sodrr = admin_order_status_sum("{$sql_where} and dan > 1 "); // 총 주문내역
$sodr2 = admin_order_status_sum("{$sql_where} and dan = 2 "); // 총 입금완료
$sodr3 = admin_order_status_sum("{$sql_where} and dan = 3 "); // 총 배송준비
$sodr4 = admin_order_status_sum("{$sql_where} and dan = 4 "); // 총 배송중
$sodr5 = admin_order_status_sum("{$sql_where} and dan = 5 "); // 총 배송완료
$sodr6 = admin_order_status_sum("{$sql_where} and dan = 6 "); // 총 입금전 취소
$sodr7 = admin_order_status_sum("{$sql_where} and dan = 7 "); // 총 배송후 반품
$sodr8 = admin_order_status_sum("{$sql_where} and dan = 8 "); // 총 배송후 교환
$sodr9 = admin_order_status_sum("{$sql_where} and dan = 9 "); // 총 배송전 환불
$final1 = admin_order_status_sum("{$sql_where} and dan = 5 and user_ok = 1 "); // 총 구매확정
$final2 = admin_order_status_sum("{$sql_where} and dan = 5 and user_ok = 0 "); // 총 구매미확정
?>

<div id="main_wrap">
	<section>
		<h2>전체 주문통계<a href="<?php echo TB_MYPAGE_URL; ?>/page.php?code=seller_odr_list" class="btn_small">주문내역 바로가기</a></h2>
		<div class="order_vbx">
			<dl class="od_bx1">
				<dt>전체 주문현황</dt>
				<dd>
					<p class="ddtit">총 주문건수</p>
					<p><?php echo number_format($sodrr['cnt']); ?></p>
				</dd>
				<dd class="total">
					<p class="ddtit">총 주문액</p>
					<?php echo display_price2($sodrr['price']); ?>
				</dd>
			</dl>

			<dl class="od_bx2">
				<dt>주문상태 현황</dt>
				<dd>
					<p class="ddtit">입금완료</p>
					<p><?php echo number_format($sodr2['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">배송준비</p>
					<p><?php echo number_format($sodr3['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">배송중</p>
					<p><?php echo number_format($sodr4['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">배송완료</p>
					<p><?php echo number_format($sodr5['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">구매확정</p>
					<p><?php echo number_format($final1['cnt']); ?></p>
				</dd>
			</dl>
			<dl class="od_bx2">
				<dt>구매확정/클래임 현황</dt>
				<dd>
					<p class="ddtit">구매미확정</p>
					<p><?php echo number_format($final2['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">취소</p>
					<p><?php echo number_format($sodr6['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">환불</p>
					<p><?php echo number_format($sodr9['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">반품</p>
					<p><?php echo number_format($sodr7['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">교환</p>
					<p><?php echo number_format($sodr8['cnt']); ?></p>
				</dd>
			</dl>
		</div>
	</section>

	<section class="sidx_head01">
		<h2>최근 주문내역<a href="<?php echo TB_MYPAGE_URL; ?>/page.php?code=seller_odr_list" class="btn_small">주문내역 바로가기</a></h2>
		<table>
		<thead>
		<tr>
			<th scope="col">주문번호</th>
			<th scope="col">주문자명</th>
			<th scope="col">수령자명</th>
			<th scope="col">전화번호</th>
			<th scope="col">결제방법</th>
			<th scope="col">총주문액</th>
			<th scope="col">주문일시</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$sql = " select * from shop_order {$sql_where} and dan > 1 group by od_id order by index_no desc limit 5 ";
		$res = sql_query($sql);
		for($i=0; $row=sql_fetch_array($res); $i++){
			$amount = get_order_spay($row['od_id'], " and seller_id = '{$seller['seller_code']}' ");
		?>
		<tr class="tr_alignc">
			<td><?php echo $row['od_id']; ?></td>
			<td><?php echo $row['name']; ?></td>
			<td><?php echo $row['b_name']; ?></td>
			<td><?php echo $row['cellphone']; ?></td>
			<td><?php echo $row['paymethod']; ?></td>
			<td><?php echo number_format($amount['buyprice']); ?></td>
			<td><?php echo substr($row['od_time'],0,16); ?> (<?php echo get_yoil($row['od_time']); ?>)</td>
		</tr>
		<?php
		}
		if($i==0)
			echo '<tr><td colspan="7" class="empty_table">자료가 없습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</section>

	<section>
		<table class="wfull">
		<tr>
			<td width="49.5%" valign="top" class="sidx_head01">
				<h2>공지사항<a href="<?php echo TB_BBS_URL; ?>/list.php?boardid=20" class="btn_small">바로가기</a></h2>
				<table>
				<colgroup>
					<col width="20%">
					<col width="80%">
				</colgroup>
				<thead>
				<tr>
					<th scope="col">등록일</th>
					<th scope="col">제목</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$sql = "select * from shop_board_20 order by wdate desc limit 5 ";
				$res = sql_query($sql);
				for($i=0;$row=sql_fetch_array($res);$i++){
					$bo_subject = cut_str($row['subject'],40);
					$bo_date = date('Y-m-d', $row['wdate']);
					$bo_href = TB_BBS_URL."/read.php?boardid=20&index_no=$row[index_no]";
				?>
				<tr>
					<td class="tac"><?php echo $bo_date; ?></td>
					<td class="tal"><a href="<?php echo $bo_href; ?>"><?php echo $bo_subject; ?></a></td>
				</tr>
				<?php
				}
				if($i == 0)
					echo '<tr><td colspan="2" class="empty_table">자료가 없습니다.</td></tr>';
				?>
				</tbody>
				</table>
			</td>
			<td width="1%"></td>
			<td width="49.5%" valign="top" class="sidx_head01">
				<h2>질문과답변<a href="<?php echo TB_BBS_URL; ?>/list.php?boardid=21" class="btn_small">바로가기</a></h2>
				<table>
				<colgroup>
					<col width="20%">
					<col width="80%">
				</colgroup>
				<thead>
				<tr>
					<th scope="col">등록일</th>
					<th scope="col">제목</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$sql = "select * from shop_board_21 order by wdate desc limit 5 ";
				$res = sql_query($sql);
				for($i=0;$row=sql_fetch_array($res);$i++){
					$bo_subject = cut_str($row['subject'],40);
					$bo_date = date('Y-m-d', $row['wdate']);
					$bo_href = TB_BBS_URL."/read.php?boardid=21&index_no=$row[index_no]";
				?>
				<tr>
					<td class="tac"><?php echo $bo_date; ?></td>
					<td class="tal"><a href="<?php echo $bo_href; ?>"><?php echo $bo_subject; ?></a></td>
				</tr>
				<?php
				}
				if($i == 0)
					echo '<tr><td colspan="2" class="empty_table">자료가 없습니다.</td></tr>';
				?>
				</tbody>
				</table>
			</td>
		</tr>
		</table>
	</section>
</div>

<?php
include_once("./admin_tail.sub.php");
?>