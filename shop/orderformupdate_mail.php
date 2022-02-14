<table width="700" border="0" cellpadding="0" cellspacing="0" align="center" style="border:1px solid #bbc0c4;">
<tbody>
<tr>
	<td style="padding:24px 14px 0;">
		<table width="670" border="0" cellpadding="0" cellspacing="0">
		<tbody>
		<!-- 상단메인배너 -->
		<tr>
			<td><img src="<?php echo TB_IMG_URL; ?>/visual_title_5.jpg" style="border:0;"></td>
		</tr>
		<!-- //상단메인배너 -->
		<!-- 인사말 -->
		<tr>
			<td style="padding:50px 0 0 10px; font-size:12px; font-family:Gulim; color:#393939; line-height:19px;">
				<p>안녕하세요. <strong><?php echo $config['company_name']; ?></strong> 입니다.<br>
				저희 쇼핑몰을 이용해주셔서 진심으로 감사드립니다.</p>
				<p style="margin-top:13px;"><strong><?php echo $od['name']; ?></strong> 고객님께서 저희 쇼핑몰에서 주문하신 내역입니다.</p>
			</td>
		</tr>
		<!-- //인사말 -->
		<tr>
			<td>
				<!-- 컨텐츠 -->
				<table width="670" border="0" cellpadding="0" cellspacing="0" style="font-size:12px; font-family:Gulim; color:#393939; line-height:19px;">
				<tbody>
				<tr>
					<td style="padding:23px 0 0;">
						<p style="margin:0 0 25px 10px">고객님께서는 <strong><?php echo substr($od['od_time'],0,10);?></strong>에 <strong><?php echo $config['company_name']; ?></strong>에서 아래와 같은 상품을 주문하셨습니다.</p>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:12px; font-family:Gulim; line-height:15px; border-top:1px solid #d5d5d5;">
						<thead>
						<tr>
							<th width="33%" scope="col" style="padding:13px 10px 10px; font-weight:normal; background-color:#f5f6f5; border-bottom:1px solid #d5d5d5; border-right:1px solid #d5d5d5; border-left:1px solid #d5d5d5; color:#80878d;">주문자명</th>
							<th width="33%" scope="col" style="padding:13px 10px 10px; font-weight:normal; background-color:#f5f6f5; border-bottom:1px solid #d5d5d5; border-right:1px solid #d5d5d5; color:#80878d;">주문번호</th>
							<th width="34%" scope="col" style="padding:13px 10px 10px; font-weight:normal; background-color:#f5f6f5; border-bottom:1px solid #d5d5d5; border-right:1px solid #d5d5d5; color:#80878d;">주문일자</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td align="center" valign="middle" style="padding:13px 10px 10px;  border-bottom:1px solid #d5d5d5; border-right:1px solid #d5d5d5; border-left:1px solid #d5d5d5; color:#393939;"><?php echo $od['name']; ?></td>
							<td align="center" valign="middle" style="padding:13px 10px 10px;  border-bottom:1px solid #d5d5d5;border-right:1px solid #d5d5d5; color:#393939;"><?php echo $od['od_id']; ?></td>
							<td align="center" valign="middle" style="padding:13px 10px 10px;  border-bottom:1px solid #d5d5d5; border-right:1px solid #d5d5d5; color:#393939;"><?php echo substr($od['od_time'],0,10);?></td>
						</tr>
						</tbody>
						</table>
					</td>
				</tr>
				<tr><td height="40">&nbsp;</td></tr><!-- 컨텐츠 공통 여백 -->
				<!-- 주문 상품 정보 -->
				<tr>
					<td>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:0 0 20px;">
						<tbody>
						<tr>
							<td width="19" valign="middle"><img src="<?php echo TB_IMG_URL; ?>/visual_ico_title.jpg" style="border:0;"></td>
							<td valign="middle"><strong style="font-size:13px; font-family:Gulim; color:#1c1c1c;">주문 상품 정보</strong></td>
						</tr>
						</tbody>
						</table>
						
						<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:12px;font-family:Gulim;line-height:15px;border:1px solid #d5d5d5;">
						<colgroup>
							<col style="width:66%">										
							<col style="width:13%">
							<col style="width:8%">
							<col style="width:13%">
						</colgroup>
						<thead>
						<tr height="39">
							<th scope="col" valign="middle" style="color:#80878d;border-right:1px solid #d5d5d5;">상품정보</th>	
							<th scope="col" valign="middle" style="color:#80878d;border-right:1px solid #d5d5d5;">상품금액</th>
							<th scope="col" valign="middle" style="color:#80878d;border-right:1px solid #d5d5d5;">수량</th>	
							<th scope="col" valign="middle" style="color:#80878d;">소계</th>
						</tr>
						</thead>
						<tbody>
						<?php
						$sql = " select * 
									from shop_cart
									where od_id = '$od[od_id]'
									group by gs_id
									order by index_no ";	
						$res = sql_query($sql);
						for($i=0; $row2=sql_fetch_array($res); $i++)
						{	
							$rw = get_order($row2['od_no']);
							$gs = get_goods($row2['gs_id'], 'gname');
							$goods_total_pay += (int)$rw['use_price'];

							$it_name = stripslashes($gs['gname']);
							$it_options = print_complete_options2($row2['gs_id'], $od['od_id']);
							if($it_options && $row2['io_id']){
								$it_name .= '<div style="margin:5px 0 0 0;padding:0">'.$it_options.'</div>';
							}
						?>
						<tr>	
							<td style="padding:5px;border-top:1px solid #d5d5d5;border-right:1px solid #d5d5d5;" align="left"><?php echo $it_name; ?></td>
							<td style="padding:5px;border-top:1px solid #d5d5d5;border-right:1px solid #d5d5d5;" align="right"><?php echo number_format($rw['goods_price']); ?>원</td>
							<td style="padding:5px;border-top:1px solid #d5d5d5;border-right:1px solid #d5d5d5;" align="center"><?php echo number_format($rw['sum_qty']); ?>개</td>			
							<td style="padding:5px;border-top:1px solid #d5d5d5;" align="right"><?php echo number_format($rw['use_price']); ?>원</td>
						</tr>
						<?php 
						}
						?>
						</tbody>
						</table>
					</td>
				</tr>
				<!-- //주문 상품 정보 -->
				<tr><td height="40">&nbsp;</td></tr><!-- 컨텐츠 공통 여백 -->
				<tr>
					<!-- 결제 정보 -->
					<td>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:0 0 20px;">
						<tbody>
						<tr>
							<td width="19" valign="middle"><img src="<?php echo TB_IMG_URL; ?>/visual_ico_title.jpg" style="border:0;"></td>
							<td valign="middle"><strong style=" font-size:13px; font-family:Gulim; color:#1c1c1c;">결제 정보</strong></td>
						</tr>
						</tbody>
						</table>
						
						<?php
						$cont_st = 'font-size:12px;font-family:Gulim;line-height:15px;border:1px solid #d5d5d5;border-right:0;border-bottom:0';
						$th_st = 'padding:13px 10px 10px 10px;font-weight:normal;border-bottom:1px solid #d5d5d5;border-right:1px solid #d5d5d5;color:#80878d;background-color:#f5f6f5;text-align:left;';
						$td_st = 'padding:13px 10px 10px 10px;border-bottom:1px solid #d5d5d5;border-right:1px solid #d5d5d5;color:#393939;text-align:left;';
						?>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" style="<?php echo $cont_st; ?>">
						<colgroup>
							<col style="width:22%">
							<col style="width:78%">
						</colgroup>
						<tbody>
						<tr>
							<th scope="row" style="<?php echo $th_st; ?>">결제금액</th>
							<td style="<?php echo $td_st; ?>">
								<strong><?php echo number_format($goods_total_pay); ?>원</strong>
							</td>
						</tr>
						<tr>
							<th scope="row" style="<?php echo $th_st; ?>">결제방법</th>
							<td style="<?php echo $td_st; ?>">
								<strong><?php echo $od['paymethod']; ?></strong>
							</td>
						</tr>
						</tbody>
						</table>
					</td>
				</tr>
				<!-- //결제 정보 -->
				<tr><td height="40">&nbsp;</td></tr><!-- 컨텐츠 공통 여백 -->
				<tr>
					<!-- 배송지 정보 -->
					<td>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:0 0 20px;">
						<tbody>
						<tr>
							<td width="19" valign="middle"><img src="<?php echo TB_IMG_URL; ?>/visual_ico_title.jpg" style="border:0;"></td>
							<td valign="middle"><strong style="font-size:13px; font-family:Gulim; color:#1c1c1c;">배송지 정보</strong></td>
						</tr>
						</tbody>
						</table>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:12px; font-family:Gulim; line-height:15px; border-top:1px solid #d5d5d5;">
						<tbody>
						<tr>
							<th width="22%" scope="row" align="left" valign="middle" style="padding:13px 10px 10px; font-weight:normal;  border-bottom:1px solid #d5d5d5; border-right:1px solid #d5d5d5; border-left:1px solid #d5d5d5; color:#80878d; background-color:#f5f6f5;">받으시는분</th>
							<td width="78%" colspan="3" align="left" valign="middle" style="padding:13px 10px 10px; border-bottom:1px solid #d5d5d5; border-right:1px solid #d5d5d5; color:#393939;"><?php echo $od['b_name']; ?></td>
						</tr>
						<tr>
							<th width="22%" scope="row" align="left" valign="middle" style="padding:13px 10px 10px; font-weight:normal;  border-bottom:1px solid #d5d5d5; border-right:1px solid #d5d5d5; border-left:1px solid #d5d5d5; color:#80878d; background-color:#f5f6f5;">주소</th>
							<td width="78%" colspan="3" align="left" valign="middle" style="padding:13px 10px 10px; border-bottom:1px solid #d5d5d5; border-right:1px solid #d5d5d5; color:#393939;"><?php echo get_text(sprintf("(%s)", $od['b_zip']).' '.print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon'])); ?></td>
						</tr>
						<tr>
							<th width="22%" scope="row" align="left" valign="middle" style="padding:13px 10px 10px; font-weight:normal;  border-bottom:1px solid #d5d5d5; border-right:1px solid #d5d5d5; border-left:1px solid #d5d5d5; color:#80878d; background-color:#f5f6f5;">일반전화</th>
							<td width="28%" align="left" valign="middle" style="padding:13px 10px 10px; border-bottom:1px solid #d5d5d5; border-right:1px solid #d5d5d5; color:#393939;"><?php echo $od['b_telephone']; ?></td>
							<th width="22%" scope="row" align="left" valign="middle" style="padding:13px 10px 10px; font-weight:normal;  border-bottom:1px solid #d5d5d5; border-right:1px solid #d5d5d5; color:#80878d; background-color:#f5f6f5;">휴대전화</th>
							<td width="28%" align="left" valign="middle" style="padding:13px 10px 10px; border-bottom:1px solid #d5d5d5; border-right:1px solid #d5d5d5; color:#393939;"><?php echo $od['b_cellphone']; ?></td>
						</tr>
						<tr>
							<th width="22%" scope="row" align="left" valign="middle" style="padding:13px 10px 10px; font-weight:normal;  border-bottom:1px solid #d5d5d5; border-right:1px solid #d5d5d5; border-left:1px solid #d5d5d5; color:#80878d; background-color:#f5f6f5;">전하실말씀</th>
							<td width="78%" colspan="3" align="left" valign="middle" style="padding:13px 10px 10px; border-bottom:1px solid #d5d5d5; border-right:1px solid #d5d5d5; color:#393939;"><?php echo $od['memo']; ?></td>
						</tr>
						</tbody>
						</table>
					</td>
				</tr>
				<!-- //배송지 정보 -->
				</tbody>
				</table>
				<!-- //컨텐츠 -->
			</td>
		</tr>
		<!-- 맺음말 -->
		<tr>
			<td style="padding:30px 0 30px 10px; font-size:12px; font-family:Gulim; color:#393939; line-height:19px;">
				<p>주문내역에 착오가 있거나, 주문내역을 변경하실 경우, 그외 기타 문의사항이 있으시면<br>저희 쇼핑몰 고객 서비스 센터로 연락 주십시오.</p>
				<p style="margin-top:13px;">다시 한번 저희 쇼핑몰을 이용해주신 <strong><?php echo $config['company_name']; ?>(<?php echo $od['name']; ?>)</strong> 고객님께 진심으로 감사드립니다.</p>
			</td>
		</tr>
		<tr>
			<td style="padding-bottom:60px; text-align:center;"><a href="<?php echo TB_URL; ?>" target="_blank"><img src="<?php echo TB_IMG_URL; ?>/visual_go_btn.jpg" style="border:0;"></td>
		</tr>
		<!-- //맺음말 -->				
		</tbody>
		</table>
	</td>
</tr>
<tr>
	<td style="padding:24px 34px; font-family:Gulim; font-size:12px; line-height:18px; background-color:#cacdd4; color:#fff;">
	   <p>
			TEL : <strong><?php echo $config['company_tel']; ?></strong> | FAX : <?php echo $config['company_fax']; ?><br><?php echo $config['company_addr']; ?><br>대표이사 : <?php echo $config['company_owner']; ?> | 개인정보관리책임자 : <?php echo $config['info_name']; ?><br>사업자 등록번호 [<?php echo $config['company_saupja_no']; ?>] | 통신판매업 신고 :<?php echo $config['tongsin_no']; ?>
	   </p>
	   <p>Copyright(c) <?php echo $config['company_name']; ?> all rights reserved.</p>
	</td>
</tr>
</tbody>
</table>