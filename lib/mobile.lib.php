<?php
if(!defined('_TUBEWEB_')) exit;

// 5차카테고리
function mobile_tree_category($catecode)
{
	global $tb, $config;

	$sql_search = " and p_hide='0' ";

	// 본사카테고리 고정일때
	if($config['pf_auth_cgy'] == 1)
		$sql_search .= " and p_oper='y' ";

	$t_catecode = $catecode;

	$sql_common = " from {$tb['category_table']} ";
	$sql_where  = " where u_hide='0' {$sql_search} ";
	$sql_order  = " order by list_view asc ";

	$sql = " select count(*) as cnt {$sql_common} {$sql_where} and upcate = '$catecode' ";
	$res = sql_fetch($sql);
	if($res['cnt'] < 1) {
		$catecode = substr($catecode,0,-3);
	}

	$sql = "select * {$sql_common} {$sql_where} and upcate = '$catecode' {$sql_order} ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i==0) {
			echo '<div id="sct_ct">'.PHP_EOL;
			echo '<ul>'.PHP_EOL;
		}

		$addclass = "";
		if($t_catecode==$row['catecode'])
			$addclass = ' class="sct_here"';

		$href = TB_MSHOP_URL.'/list.php?ca_id='.$row['catecode'];

		echo "<li><a href=\"{$href}\"{$addclass}>{$row['catename']}</a></li>\n";
	}

	if($i > 0) {
		echo '</ul>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
	}
}

// mobile_display_goods("영역", "출력수", "타이틀", "클래스명")
function mobile_display_goods($type, $rows, $mtxt, $li_css='')
{
	global $default, $pt_id;

	echo "<h2 class=\"mtit\"><span>{$mtxt}</span></h2>\n";
	echo "<p class=\"sct_li_type\">\n";
		echo "<a href=\"\"><img src=\"".TB_MTHEME_URL."/img/bt_litype1.gif\"></a>\n";
		echo "<a href=\"wli2\"><img src=\"".TB_MTHEME_URL."/img/bt_litype2_on.gif\"></a>\n";
		echo "<a href=\"wli3\"><img src=\"".TB_MTHEME_URL."/img/bt_litype3.gif\"></a>\n";
	echo "</p>\n";

	echo "<ul class=\"{$li_css}\">\n";
	$result = display_itemtype($pt_id, $type, $rows);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
		$it_imageurl = get_it_image_url($row['index_no'], $row['simg2'], $default['de_item_medium_wpx'], $default['de_item_medium_hpx']);
		$it_name = get_text($row['gname']);
		$it_price = mobile_price($row['index_no']);
		$it_amount = get_sale_price($row['index_no']);
		$it_point = display_point($row['gpoint']);

		// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
		$it_sprice = $sale = '';
		if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
			$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
			$sale = '<span class="sale">['.number_format($sett,0).'%]</span>';
			$it_sprice = display_price2($row['normal_price']);
		}

		echo "<li>\n";
			echo "<a href=\"{$it_href}\">\n";
			echo "<dl>\n";
				echo "<dt><img src=\"{$it_imageurl}\"></dt>\n";
				echo "<dd class=\"pname\">{$it_name}</dd>\n";
				if($row['info_color']) {
					echo "<dd class=\"op_color\">\n";
					$arr = explode(",", trim($row['info_color']));
					for($g=0; $g<count($arr); $g++) {
						echo get_color_boder(trim($arr[$g]), 1);
					}
					echo "</dd>\n";
				}
				echo "<dd class=\"price\">{$it_sprice}{$it_price}</dd>\n";
			echo "</dl>\n";
		echo "</a>\n";
		echo "<span onclick='javascript:itemlistwish(\"$row[index_no]\")' id='$row[index_no]' class='$row[index_no] ".zzimCheck($row['index_no'])."'></span>\n";
		echo "</li>\n";
	}
	echo "</ul>\n";
	echo "<p class=\"sct_btn\"><a href=\"".TB_MSHOP_URL."/listtype.php?type=$type\" class=\"btn_lsmall bx-white wfull\">더보기 <i class=\"fa fa-angle-right marl3\"></i></a></p>\n";
}

// mobile_slide_goods("영역", "출력수", "타이틀", "클래스명")
function mobile_slide_goods($type, $rows, $mtxt, $li_css='')
{
	global $default, $pt_id;

	echo "<h2><span>{$mtxt}</span></h2>\n";
	echo "<div class=\"{$li_css}\">\n";

	$result = display_itemtype($pt_id, $type, $rows);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
		$it_imageurl = get_it_image_url($row['index_no'], $row['simg2'], $default['de_item_medium_wpx'], $default['de_item_medium_hpx']);
		$it_name = get_text($row['gname']);
		$it_price = mobile_price($row['index_no']);
		$it_amount = get_sale_price($row['index_no']);
		$it_point = display_point($row['gpoint']);

		// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
		$it_sprice = $sale = '';
		if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
			$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
			$sale = '<span class="sale">['.number_format($sett,0).'%]</span>';
			$it_sprice = display_price2($row['normal_price']);
		}

		echo "<dl>\n";
			echo "<a href=\"{$it_href}\">\n";
				echo "<dt><img src=\"{$it_imageurl}\"></dt>\n";
				echo "<dd class=\"pname\">{$it_name}</dd>\n";
				echo "<dd class=\"price\">{$it_sprice}{$it_price}</dd>\n";
			echo "</a>\n";
		echo "</dl>\n";
	}
	echo "</div>\n";
}

// 메인 고객상품평 배열을 리턴
function mobile_review_rows($name, $rows)
{
	global $default, $pt_id;

	echo "<div class=\"main_post tline10\">\n";
	echo "<h2 class=\"m_tit\"><span class=\"mtxt\">$name</span></h2>\n";
	echo "<ul>\n";

	$sql_common = " from shop_goods_review ";
	$sql_search = " where (left(seller_id,3)='AP-' or seller_id = 'admin' or seller_id = '$pt_id') ";
	if($default['de_review_wr_use']) $sql_search .= " and pt_id = '$pt_id' ";
	$sql_order = " order by index_no desc limit $rows ";

	$sql = " select * $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gs = get_goods($row['gs_id'], 'gname');
		$it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['gs_id'];
		$it_name = get_text(cut_str($gs['gname'], 40));

		echo "<li>\n";
			echo "<a href=\"{$it_href}\">\n";
			echo "<p class=\"tit\">{$it_name}</p>\n";
			echo "<p>{$row['memo']}</p>\n";
			echo "</a>\n";
		echo "</li>\n";
	}

	if($i == 0) {
		echo "<li class=\"sct_noitem\">자료가 없습니다</li>\n";
	}

	echo "</ul>\n";
	echo "<p class=\"sct_btn\"><a href=\"".TB_MBBS_URL."/review.php\" class=\"btn_lsmall bx-white wfull\">더보기 <i class=\"fa fa-angle-right marl3\"></i></a></p>\n";
	echo "</div>\n";
}

// 최근게시물 추출
function mobile_display_board($bo_table, $rows)
{
	global $default, $pt_id;

	$sql_where = "";
	if($default['de_board_wr_use']) {
		$sql_where = " where pt_id = '$pt_id' ";
	}

	$sql = "select * from shop_board_{$bo_table} $sql_where order by wdate desc limit $rows ";
	$res = sql_query($sql);
	for($i=0;$row=sql_fetch_array($res);$i++){
		$subject = get_text($row['subject']);
		$wdate	= date('Y.m.d',intval($row['wdate'],10));
		echo "<a href=\"".TB_MBBS_URL."/board_read.php?boardid=$bo_table&index_no=$row[index_no]\">$subject</a>";
	}

	if($i==0){ echo "게시물이 없습니다"; }
}

// 인기 검색어 추출
function mobile_display_tick($name, $rows)
{
	global $pt_id;

	echo "<h2>{$name}</h2>\n";
	echo "<ul id='ticker'>\n";

	$sql_common = " from shop_keyword ";
	$sql_search = " where pt_id = '$pt_id' ";
	$sql_order  = " order by scount desc, old_scount desc limit $rows ";

	$sql = " select * $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row = sql_fetch_array($result); $i++){
		if($row['old_scount'] > 0) // 과거 데이터가 있는 경우
			$rank_gap = $row['scount'] - $row['old_scount'];
		else // 과거 데이터가 없는 경우
			$rank_gap = 'N';

		if($rank_gap > 0)
			$u_rkw = "rkw_icon rkw_up";
		else if($rank_gap < 0)
			$u_rkw = "rkw_icon rkw_dw";
		else if($rank_gap == '0')
			$u_rkw = "rkw_icon rkw_sm";
		else
			$u_rkw = "rkw_icon rkw_nw";

		$rkn = $i + 1;
		echo "<li>\n";
			echo "<a href='".TB_MSHOP_URL."/search.php?ss_tx=$row[keyword]'><span class='rkw_num'>{$rkn}</span> {$row['keyword']}</a>\n";
			echo "<span class='{$u_rkw}'>{$rank_gap}</span>\n";
		echo "</li>\n";
	}

	echo "</ul>\n";
}

// 금주의 인기 검색어 추출
function mobile_display_rank()
{
	global $pt_id;

	echo "<div class='m_rkw'>\n";
	echo "<ul>\n";

	$sql_common = " from shop_keyword ";
	$sql_search = " where pt_id = '$pt_id' ";
	$sql_order  = " order by scount desc, old_scount desc limit 10 ";

	$sql = " select * $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row = sql_fetch_array($result); $i++) {
		if($row['old_scount'] > 0) // 과거 데이터가 있는 경우
			$rank_gap = $row['scount'] - $row['old_scount'];
		else // 과거 데이터가 없는 경우
			$rank_gap = 'N';

		if($rank_gap > 0)
			$u_rkw = "rkw_icon rkw_up";
		else if($rank_gap < 0)
			$u_rkw = "rkw_icon rkw_dw";
		else if($rank_gap == '0')
			$u_rkw = "rkw_icon rkw_sm";
		else
			$u_rkw = "rkw_icon rkw_nw";

		$rkn = $i + 1;
		echo "<li>\n";
			echo "<a href='".TB_MSHOP_URL."/search.php?ss_tx=$row[keyword]'><span class='rkw_num'>{$rkn}</span> {$row['keyword']}</a>\n";
			echo "<span class='{$u_rkw}'>{$rank_gap}</span>\n";
		echo "</li>\n";
	}

	echo "</ul>\n";
	echo "</div>\n";
}

// 쿠폰 : 상세내역
function mobile_cp_contents()
{
	global $row, $gw_usepart;

	$str = "";
	$str .= "<div>&#183; <strong>".get_text($row['cp_subject'])."</strong></div>";

	// 동시사용 여부
	$str .= "<div class='fc_197'>&#183; ";
	if(!$row['cp_dups']) {
		$str .= '동일한 주문건에 중복할인 가능';
	} else {
		$str .= '동일한 주문건에 중복할인 불가 (1회만 사용가능)';
	}
	$str .= "</div>";

	// 쿠폰유효 기간
	$str .= "<div>&#183; 쿠폰유효 기간 : ";
	if(!$row['cp_inv_type']) {
		// 날짜
		if($row['cp_inv_sdate'] == '9999999999') $cp_inv_sdate = '';
		else $cp_inv_sdate = $row['cp_inv_sdate'];

		if($row['cp_inv_edate'] == '9999999999') $cp_inv_edate = '';
		else $cp_inv_edate = $row['cp_inv_edate'];

		if($row['cp_inv_sdate'] == '9999999999' && $row['cp_inv_sdate'] == '9999999999')
			$str .= '제한없음';
		else
			$str .= $cp_inv_sdate . " ~ " . $cp_inv_edate;

		// 시간대
		$str .= "&nbsp;(시간대 : ";
		if($row['cp_inv_shour1'] == '99') $cp_inv_shour1 = '';
		else $cp_inv_shour1 = $row['cp_inv_shour1'] . "시부터";

		if($row['cp_inv_shour2'] == '99') $cp_inv_shour2 = '';
		else $cp_inv_shour2 = $row['cp_inv_shour2'] . "시까지";

		if($row['cp_inv_shour1'] == '99' && $row['cp_inv_shour1'] == '99')
			$str .= '제한없음';
		else
			$str .= $cp_inv_shour1 . " ~ " . $cp_inv_shour2 ;
		$str .= ")";
	} else {
		$cp_inv_day = date("Y-m-d",strtotime("+{$row[cp_inv_day]} days",strtotime($row['cp_wdate'])));
		$str .= '다운로드 완료 후 ' . $row['cp_inv_day']. '일간 사용가능, 만료일('.$cp_inv_day.')';
	}
	$str .= "</div>";

	// 혜택
	$str .= "<div>&#183; ";
	if($row['cp_sale_type'] == '0') {
		if($row['cp_sale_amt_max'] > 0)
			$cp_sale_amt_max = "&nbsp;(최대 ".display_price($row['cp_sale_amt_max'])."까지 할인)";
		else
			$cp_sale_amt_max = "";

		$str .= $row['cp_sale_percent']. '% 할인' . $cp_sale_amt_max;
	} else {
		$str .= display_price($row['cp_sale_amt']). ' 할인';
	}
	$str .= "</div>";

	// 최대금액
	if($row['cp_low_amt'] > 0) {
		$str .= "<div>&#183; ".display_price($row['cp_low_amt'])." 이상 구매시</div>";
	}

	// 사용가능대상
	$str .= "<div>&#183; ".$gw_usepart[$row['cp_use_part']]."</div>";

	return $str;
}

//  상품 상세페이지 : 배송비
function mobile_sendcost_amt()
{
	global $gs, $config, $sr;

	// 공통설정
	if($gs['sc_type']=='0') {
		if($gs['mb_id'] == 'admin') {
			$delivery_method  = $config['delivery_method'];
			$delivery_price	  = $config['delivery_price'];
			$delivery_price2  = $config['delivery_price2'];
			$delivery_minimum = $config['delivery_minimum'];
		} else {
			$delivery_method  = $sr['delivery_method'];
			$delivery_price   = $sr['delivery_price'];
			$delivery_price2  = $sr['delivery_price2'];
			$delivery_minimum = $sr['delivery_minimum'];
		}

		switch($delivery_method) {
			case '1':
				$str = "무료배송";
				break;
			case '2':
				$str = "상품수령시 결제(착불)";
				break;
			case '3':
				$str = display_price($delivery_price);
				break;
			case '4':
				$str = "무료~".display_price($delivery_price2)."&nbsp;(조건부무료)";
				break;
		}

		// sc_type(배송비 유형)   0:공통설정, 1:무료배송, 2:조건부무료배송, 3:유료배송
		// sc_method(배송비 결제) 0:선불, 1:착불, 2:사용자선택
		if(in_array($delivery_method, array('3','4'))) {
			if($gs['sc_method'] == 1)
				$str = '상품수령시 결제(착불)';
			else if($gs['sc_method'] == 2) {
				$str = "<select name=\"ct_send_cost\" style=\"width:100%\">
							<option value=\"0\">주문시 결제(선결제)</option>
							<option value=\"1\">상품수령시 결제(착불)</option>
						</select>";
			}
		}
	} else if($gs['sc_type']=='1') {
		$str = "무료배송";
	} else if($gs['sc_type']=='2') {
		$str = "무료~".display_price($gs['sc_amt'])."&nbsp;(조건부무료)";
	} else if($gs['sc_type']=='3') {
		$str = display_price($gs['sc_amt']);
	}

	// sc_type(배송비 유형)		0:공통설정, 1:무료배송, 2:조건부 무료배송, 3:유료배송
	// sc_method(배송비 결제)	0:선불, 1:착불, 2:사용자선택
	if(in_array($gs['sc_type'], array('2','3'))) {
		if($gs['sc_method'] == 1)
			$str = '상품수령시 결제(착불)';
		else if($gs['sc_method'] == 2) {
			$str = "<select name=\"ct_send_cost\" style=\"width:100%\">
						<option value=\"0\">주문시 결제(선결제)</option>
						<option value=\"1\">상품수령시 결제(착불)</option>
					</select>";
		}
	}

	return $str;
}

// 상품 가격정보의 배열을 리턴
function mobile_price($gs_id, $msg='<span>원</span>')
{
	global $member, $is_member;

	$gs = get_goods($gs_id, 'index_no, price_msg, buy_level, buy_only');

	$price = get_sale_price($gs_id);

	// 재고가 한정상태이고 재고가 없을때, 품절상태일때..
	if(is_soldout($gs['index_no'])) {
		$str = "<p class='soldout'>품절</p>";
	} else {
		if($gs['price_msg']) {
			$str = $gs['price_msg'];
		} else if($gs['buy_only'] == 1 && $member['grade'] > $gs['buy_level']) {
			$str = "";
		} else if($gs['buy_only'] == 0 && $member['grade'] > $gs['buy_level']) {
			if(!$is_member)
				$str = "<p class='memopen'>회원공개</p>";
			else
				$str = "<p class='mpr'>".number_format($price).$msg."</p>";
		} else {
			$str = "<p class='mpr'>".number_format($price).$msg."</p>";
		}
	}

	return $str;
}

//  상품 상세페이지 : 구매하기, 장바구니, 찜 버튼
function mobile_buy_button($msg, $gs_id)
{
	global $gs, $pt_id;

	$ui_btn   = array("1"=>"구매하기","2"=>"장바구니","3"=>"찜하기");
	$ui_class = array("1"=>"btn_medium wset","2"=>"btn_medium bx-white","3"=>"btn_medium bx-white");

	$str = "<div class=\"sp_btn\">";
	for($i=1; $i<=3; $i++) {
		switch($i){
			case '1':
				$sw_direct = "buy";
				break;
			case '2':
				$sw_direct = "cart";
				break;
			case '3':
				$sw_direct = "wish";
				break;
		}

		if($msg) {
			if($sw_direct == "buy") {
				$str .= "<p><button type=\"button\" onclick=\"alert('$msg');\" class='$ui_class[$i]'>$ui_btn[$i]</button></p>";
			} else {
				$str .= "<span><button type=\"button\" onclick=\"alert('$msg');\" class='$ui_class[$i]'>$ui_btn[$i]</button></span>";
			}
		} else {
			if($sw_direct == "wish") {
				$str .= "<span><button type=\"button\" onclick=\"item_wish(document.fbuyform);\" class='$ui_class[$i]'>$ui_btn[$i]</button></span>";
			} else if($sw_direct == "buy") {
				$str .= "<p><button type=\"button\" onclick=\"fbuyform_submit('".$sw_direct."');\" class='$ui_class[$i]'>$ui_btn[$i]</button></p>";
			} else {
				$str .= "<span><button type=\"button\" onclick=\"fbuyform_submit('".$sw_direct."');\" class='$ui_class[$i]'>$ui_btn[$i]</button></span>";
			}
		}
	}

	$str .= "</div>";

	return $str;
}

// 상품 상세페이지 : 고객상품평
function mobile_goods_review($name, $cnt, $gs_id, $rows=10)
{
	global $member, $gw_star, $pt_id, $default;

	$sql_common = " from shop_goods_review ";
	$sql_search = " where gs_id = '$gs_id' ";
	if($default['de_review_wr_use']) {
		$sql_search .= " and pt_id = '$pt_id' ";
	}

	$sql_order  = " order by index_no desc limit $rows ";

	echo "<div class=sp_vbox_mr>\n";
		echo "<ul>\n";
			echo "<li class='tlst'>$name <span class=cate_dc>($cnt)</span></li>\n";
			echo "<li class='trst'><a href=\"javascript:window.open('".TB_MSHOP_URL."/view_user.php?gs_id=$gs_id');\">더보기</a><span class='im im_arr'></span></li>\n";
		echo "</ul>\n";
	echo "</div>\n";

	echo "<ul class=lst_w>\n";

	$sql = " select * $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$tmp_date  = substr($row['reg_time'],0,10);
		$tmp_score = $gw_star[$row['score']];

		$len = strlen($row['mb_id']);
		$str = substr($row['mb_id'],0,3);
		$tmp_name = $str.str_repeat("*",$len - 3);

		$hash = md5($row['index_no'].$row['reg_time'].$row['mb_id']);

		echo "<li class='lst'><span class=lst_post>$row[memo]</span>";
		echo "<span class='lst_h'><span class='fc_255'>$tmp_score</span> ";
		echo "<span class='fc_999'> / $tmp_name / $tmp_date";
		if(is_admin() || ($member['id'] == $row['mb_id'])) {
			echo "&nbsp;&nbsp;&nbsp;<a href=\"javascript:window.open('".TB_MSHOP_URL."/orderreview.php?gs_id=$row[gs_id]&me_id=$row[index_no]&w=u');\" /><span class='under fc_blk'>수정</span></a>&nbsp;&nbsp;&nbsp;<a href=\"".TB_MSHOP_URL."/orderreview_update.php?gs_id=$row[gs_id]&me_id=$row[index_no]&w=d&hash=$hash\" class='itemqa_delete'><span class='under fc_blk'>삭제</span></a>";
		}
		echo "</span></span>";
		echo "</li>\n";
	}

	if($i == 0) {
		echo "<li class=lst><span class='lst_a tac'>자료가 없습니다</span></li>\n";
	}

	echo "</ul>\n";
}

//  상품 상세페이지 : Q&A
function mobile_goods_qa($name, $cnt, $gs_id)
{
	global $member;

	echo "<div class=sp_vbox_qa>\n";
		echo "<ul>\n";
			echo "<li class='tlst'>$name <span class=cate_dc>($cnt)</span></li>\n";
			echo "<li class='trst'><a href=\"javascript:window.open('".TB_MSHOP_URL."/qaform.php?gs_id=$gs_id');\" class='btn_lsmall bx-white'>Q&A쓰기</a></li>\n";
		echo "</ul>\n";
	echo "</div>\n";

	echo "<ul class=lst_w>\n";

	$sql = " select * from shop_goods_qa where gs_id='$gs_id' order by iq_time desc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$iq_time = substr($row['iq_time'],0,10);

		$is_secret = false;
		if($row['iq_secret']) {
			$icon_secret = "<img src='".TB_IMG_URL."/icon/icon_secret.jpg' class='vam' alt='비밀글'>";

			if(is_admin() || $member['id' ] == $row['mb_id']) {
				$iq_answer = $row['iq_answer'];
			} else {
				$iq_answer = "";
				$is_secret = true;
			}
		} else {
			$icon_secret = "";
			$iq_answer = $row['iq_answer'];
		}

		if($row['iq_answer'])
			$icon_answer = "<span class='fc_7d6'>답변완료</span>&nbsp;&nbsp;";
		else
			$icon_answer = "<span class='fc_999'>답변대기</span>&nbsp;&nbsp;";

		$iq_subject = "";
		if(!$is_secret) { $iq_subject .= "<a href='javascript:void(0);' onclick=\"qna('".$i."')\">"; }
		$iq_subject .= "<span class=lst_post>".$icon_answer.$row['iq_subject']."</span>";

		$len = strlen($row['mb_id']);
		$str = substr($row['mb_id'],0,3);
		$mb_id = $str.str_repeat("*",$len - 3);

		$hash = md5($row['iq_id'].$row['iq_time'].$row['iq_ip']);

		echo "<li class='lst'>\n$iq_subject";
			echo "<span class='lst_h'><span class='fc_255'>$row[iq_ty]</span> ";
			echo "<span class='fc_999'> / $mb_id / $iq_time $icon_secret </span></span>";
			if(!$is_secret) { echo "</a>"; }

			echo "<div class='faq' id='qna".$i."' style='display:none;'>\n";
				echo "<table class='faqbody'>\n";
				echo "<tbody>\n";
				echo "<tr>\n";
					echo "<td class='mi_dt'><img src='".TB_IMG_URL."/sub/FAQ_Q.gif'></td>\n";
					echo "<td class='mi_bt fc_125'>\n".nl2br($row['iq_question']);

					if(is_admin() || $member['id' ] == $row['mb_id'] && !$iq_answer) {
						echo "<div class='padt10'><a href=\"javascript:window.open('".TB_MSHOP_URL."/qaform.php?gs_id=$row[gs_id]&iq_id=$row[iq_id]&w=u');\" /><span class='under fc_blk'>수정</span></a>&nbsp;&nbsp;&nbsp;<a href=\"".TB_MSHOP_URL."/qaform_update.php?gs_id=$row[gs_id]&iq_id=$row[iq_id]&w=d&hash=$hash\" class='itemqa_delete'><span class='under fc_blk'>삭제</span></a></div>\n";
					}
					echo "</td>\n";
				echo "</tr>\n";

				if($iq_answer) {
					echo "<tr>\n";
						echo "<td class='mi_dt padt20'><img src='".TB_IMG_URL."/sub/FAQ_A.gif'></td>\n";
						echo "<td class='mi_bt padt20 fc_7d6'>".nl2br($iq_answer)."</td>\n";
					echo "</tr>\n";
				}
				echo "</tbody>\n";
				echo "</table>\n";
			echo "</div>\n";
		echo "</li>\n";
	}

	if($i == 0) {
		echo "<li class=lst><span class='lst_a tac'>자료가 없습니다</span></li>\n";
	}

	echo "</ul>\n";
}

// 상품 선택옵션
function mobile_item_options($gs_id, $subject, $event='')
{
	if(!$gs_id || !$subject)
		return '';

	$amt = get_sale_price($gs_id);

	$sql = " select * from shop_goods_option where io_type = '0' and gs_id = '$gs_id' and io_use = '1' order by io_no asc ";
	$result = sql_query($sql);
	if(!sql_num_rows($result))
		return '';

	$str = '';
	$subj = explode(',', $subject);
	$subj_count = count($subj);

	if($subj_count > 1) {
		$options = array();

		// 옵션항목 배열에 저장
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$opt_id = explode(chr(30), $row['io_id']);

			for($k=0; $k<$subj_count; $k++) {
				if(!is_array($options[$k]))
					$options[$k] = array();

				if($opt_id[$k] && !in_array($opt_id[$k], $options[$k]))
					$options[$k][] = $opt_id[$k];
			}
		}

		// 옵션선택목록 만들기
		for($i=0; $i<$subj_count; $i++) {
			$opt = $options[$i];
			$opt_count = count($opt);
			$disabled = '';
			if($opt_count) {
				$seq = $i + 1;
				if($i > 0)
					$disabled = ' disabled="disabled"';
				$str .= '<div class=sp_obox>'.PHP_EOL;
				$str .= '<ul>'.PHP_EOL;
				$str .= '<li class=tlst><label for="it_option_'.$seq.'">'.$subj[$i].'</label></li>'.PHP_EOL;

				$select  = '<select id="it_option_'.$seq.'" class="it_option"'.$disabled.' '.$event.'>'.PHP_EOL;
				$select .= '<option value="">(필수) 선택하세요</option>'.PHP_EOL;
				for($k=0; $k<$opt_count; $k++) {
					$opt_val = $opt[$k];
					if($opt_val) {
						$select .= '<option value="'.$opt_val.'">'.$opt_val.'</option>'.PHP_EOL;
					}
				}
				$select .= '</select>'.PHP_EOL;

				$str .= '<li class=trst>'.$select.'</li>'.PHP_EOL;
				$str .= '</ul>'.PHP_EOL;
				$str .= '</div>'.PHP_EOL;
			}
		}
	} else {
		$str .= '<div class=sp_obox>'.PHP_EOL;
		$str .= '<ul>'.PHP_EOL;
		$str .= '<li class=tlst><label for="it_option_1">'.$subj[0].'</label></li>'.PHP_EOL;

		$select  = '<select id="it_option_1" class="it_option" '.$event.'>'.PHP_EOL;
		$select .= '<option value="">(필수) 선택하세요</option>'.PHP_EOL;
		for($i=0; $row=sql_fetch_array($result); $i++) {
			if($row['io_price'] >= 0)
				$price = '&nbsp;&nbsp;(+'.display_price($row['io_price']).')';
			else
				$price = '&nbsp;&nbsp;('.display_price($row['io_price']).')';

			if(!$row['io_stock_qty'])
				$soldout = '&nbsp;&nbsp;[품절]';
			else
				$soldout = '';

			$select .= '<option value="'.$row['io_id'].','.$row['io_price'].','.$row['io_stock_qty'].','.$amt.'">'.$row['io_id'].$price.$soldout.'</option>'.PHP_EOL;
		}
		$select .= '</select>'.PHP_EOL;

		$str .= '<li class=trst>'.$select.'</li>'.PHP_EOL;
		$str .= '</ul>'.PHP_EOL;
		$str .= '</div>'.PHP_EOL;
	}

	return $str;
}

// 상품 추가옵션
function mobile_item_supply($gs_id, $subject, $event='')
{
	if(!$gs_id || !$subject)
		return '';

	$sql = " select * from shop_goods_option where io_type = '1' and gs_id = '$gs_id' and io_use = '1' order by io_no asc ";
	$result = sql_query($sql);
	if(!sql_num_rows($result))
		return '';

	$str = '';

	$subj = explode(',', $subject);
	$subj_count = count($subj);
	$options = array();

	// 옵션항목 배열에 저장
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$opt_id = explode(chr(30), $row['io_id']);

		if($opt_id[0] && !array_key_exists($opt_id[0], $options))
			$options[$opt_id[0]] = array();

		if($opt_id[1]) {
			if($row['io_price'] >= 0)
				$price = '&nbsp;&nbsp;(+'.display_price($row['io_price']).')';
			else
				$price = '&nbsp;&nbsp;('.display_price($row['io_price']).')';
			$io_stock_qty = get_option_stock_qty($gs_id, $row['io_id'], $row['io_type']);

			if($io_stock_qty < 1)
				$soldout = '&nbsp;&nbsp;[품절]';
			else
				$soldout = '';

			$options[$opt_id[0]][] = '<option value="'.$opt_id[1].','.$row['io_price'].','.$io_stock_qty.',0">'.$opt_id[1].$price.$soldout.'</option>';
		}
	}

	// 옵션항목 만들기
	for($i=0; $i<$subj_count; $i++) {
		$opt = $options[$subj[$i]];
		$opt_count = count($opt);
		if($opt_count) {
			$seq = $i + 1;
			$str .= '<div class=sp_obox>'.PHP_EOL;
			$str .= '<ul>'.PHP_EOL;
			$str .= '<li class=tlst><label for="it_supply_'.$seq.'">'.$subj[$i].'</label></li>'.PHP_EOL;

			$select = '<select id="it_supply_'.$seq.'" class="it_supply" '.$event.'>'.PHP_EOL;
			$select .= '<option value="">선택안함</option>'.PHP_EOL;
			for($k=0; $k<$opt_count; $k++) {
				$opt_val = $opt[$k];
				if($opt_val) {
					$select .= $opt_val.PHP_EOL;
				}
			}
			$select .= '</select>'.PHP_EOL;

			$str .= '<li class=trst>'.$select.'</li>'.PHP_EOL;
			$str .= '</ul>'.PHP_EOL;
			$str .= '</div>'.PHP_EOL;
		}
	}

	return $str;
}

// 장바구니 옵션호출
function mobile_print_item_options($gs_id)
{
    $where_add = get_cart_mode('select','','where');

	$sql = " select io_id, ct_option, ct_qty, io_type, io_price
				from shop_cart where gs_id='$gs_id' {$where_add} and ct_select='0' order by io_type asc, index_no asc ";
	$result = sql_query($sql);

	$str = '';
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i == 0)
			$str .= '<ul>'.PHP_EOL;

		if(!$row['io_id']) continue;

        $price_plus = '';
        if($row['io_price'] >= 0)
            $price_plus = '+';

		$str .= "<li>{$row['ct_option']} ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")</li>".PHP_EOL;
	}

	if($i > 0)
		$str .= '</ul>';

	return $str;
}

// 주문완료 옵션호출
function mobile_print_complete_options($gs_id, $od_id)
{
	$sql = " select io_id, ct_option, ct_qty, io_type, io_price
				from shop_cart where od_id = '$od_id' and gs_id = '$gs_id' order by io_type asc, index_no asc ";
	$result = sql_query($sql);

	$str = '';
	$comma = '';
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i == 0)
			$str .= '<ul>'.PHP_EOL;

		if(!$row['io_id']) continue;

		$price_plus = '';
        if($row['io_price'] >= 0)
            $price_plus = '+';

		$str .= "<li class=\"fc_999\">{$row['ct_option']} ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")</li>".PHP_EOL;
	}

	if($i > 0)
		$str .= '</ul>';

	return $str;
}

// 계좌정보를 select 박스 형식으로 얻는다
function mobile_bank_account($name, $selected='')
{
	global $default;

	$str  = '<select id="'.$name.'" name="'.$name.'" style="width:100%">'.PHP_EOL;
	$str .= '<option value="">선택하십시오</option>'.PHP_EOL;

	$bank = unserialize($default['de_bank_account']);
	for($i=0; $i<5; $i++) {
		$bank_account = $bank[$i]['name'].' '.$bank[$i]['account'].' '.$bank[$i]['holder'];
		if(trim($bank_account)) {
			$str .= option_selected($bank_account, $selected, $bank_account);
		}
	}
	$str .= '</select>'.PHP_EOL;

	return $str;
}

// 로고
function mobile_display_logo($fld='mobile_logo')
{
    include(TB_LIB_PATH."/psys/PsysUtil.php");

    return PsysUtil::getLogoHtml($fld);
}

// mobile_listtype_cate('설정값')
function mobile_listtype_cate($list_best)
{
	global $default;

	$mod = 3;
	$ul_str = '';

	for($i=0; $i<count($list_best); $i++) {
		$str = '';

		$list_code = explode(",", $list_best[$i]['code']); // 배열을 만들고
		$list_code = array_unique($list_code); //중복된 아이디 제거
		$list_code = array_filter($list_code); // 빈 배열 요소를 제거
		$list_code = array_values($list_code); // index 값 주기

		$succ_count = 0;
		for($g=0; $g<count($list_code); $g++) {
			$gcode = trim($list_code[$g]);
			$row = sql_fetch(" select * from shop_goods where gcode = '$gcode' ");
			if(!$row['index_no']) continue;
			if($succ_count >= 3) break;

			$it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
			$it_imageurl = get_it_image_url($row['index_no'], $row['simg2'], $default['de_item_medium_wpx'], $default['de_item_medium_hpx']);
			$it_name = get_text($row['gname']);
			$it_price = mobile_price($row['index_no']);
			$it_amount = get_sale_price($row['index_no']);
			$it_point = display_point($row['gpoint']);

			// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
			$it_sprice = $sale = '';
			if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
				$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
				$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
				$it_sprice = display_price2($row['normal_price']);
			}

			$str .= "<li>\n";
			$str .=		"<a href=\"{$it_href}\">\n";
			$str .=		"<dl>\n";
			$str .=			"<dd class=\"pname\">{$it_name}</dd>\n";
			$str .=			"<dd class=\"pimg\"><img src=\"{$it_imageurl}\"></dd>\n";
			$str .=			"<dd class=\"price\">{$it_sprice}{$it_price}</dd>\n";
			$str .=		"</dl>\n";
			$str .=		"</a>\n";
			$str .= "</li>\n";

			$succ_count++;
		} // for end

		// 나머지 li
		$cnt = $succ_count%$mod;
		if($cnt) {
			for($k=$cnt; $k<$mod; $k++) { $str .= "<li></li>\n"; }
		}

		if(!$str) $str = "<li class='empty_list'>자료가 없습니다.</li>\n";

		$ul_str .= "<ul>\n{$str}</ul>\n";
	}

	return $ul_str;
}


/*************************************************************************
**
**  쇼핑몰 배너관련 함수 모음
**
*************************************************************************/

// 메인배너 출력
function mobile_slider($code, $mb_id)
{
	$str = "";

	$sql = sql_banner_rows($code, $mb_id);
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$a1 = $a2 = '';
		$file = TB_DATA_PATH.'/banner/'.$row['bn_file'];
		if(is_file($file) && $row['bn_file']) {
			if($row['bn_link']) {
				$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
				$a2 = "</a>";
			}

			$file = rpc($file, TB_PATH, TB_URL);
			$str .= "{$a1}<img src=\"{$file}\">{$a2}\n";
		}
	}

	return $str;
}

// 배너 자체만 리턴
function mobile_banner($code, $mb_id)
{
	$str = "";

	$sql = sql_banner($code, $mb_id);
	$row = sql_fetch($sql);

	$file = TB_DATA_PATH.'/banner/'.$row['bn_file'];
	if(is_file($file) && $row['bn_file']) {
		if($row['bn_link']) {
			$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
			$a2 = "</a>";
		}

		$file = rpc($file, TB_PATH, TB_URL);
		$str = "{$a1}<img src=\"{$file}\">{$a2}";
	}

	return $str;
}

// 배너 (동일한 배너코드가 부여될경우 세로로 계속하여 출력)
function mobile_banner_rows($code, $mb_id)
{
	$str = "";

	$sql = sql_banner_rows($code, $mb_id);
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$a1 = $a2 = $bg = '';

		$file = TB_DATA_PATH.'/banner/'.$row['bn_file'];
		if(is_file($file) && $row['bn_file']) {
			if($row['bn_link']) {
				$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
				$a2 = "</a>";
			}

			$file = rpc($file, TB_PATH, TB_URL);
			$str .= "<li>{$a1}<img src=\"{$file}\">{$a2}</li>\n";
		}
	}

	if($i > 0) 
		$str = "<ul>\n{$str}</ul>\n";

	return $str;
}
?>