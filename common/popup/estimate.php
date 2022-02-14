<?php
include_once("../../common.php");

$tb['title'] = '견적서';
include_once(TB_PATH."/head.sub.php");
?>
    <style>
        #sod_fin_vendor table tr th { text-align: center; }
        .top-title { height: 50px; line-height: 50px; text-align: center; font-size: 2em; background: #f3f3f3; }
    </style>
    <style type="text/css" media="print">
        .noprint { display: none; }
    </style>
    <div class="top-title">
        <b> 견 적 서 </b>
    </div>
    <div style="margin:10px">
        <?php include_once dirname(__FILE__)."/inc/sod_fin_vendor.php"; ?>

        <section id="sod_fin_receiver" style="padding-bottom: 30px;">
            <h2 class="anc_tit">공급받는 자</h2>
            <div class="tbl_frm01 tbl_wrap">
                <table>
                    <colgroup>
                        <col class="w50">
                        <col class="w200">
                        <col class="w50">
                        <col class="w200">
                    </colgroup>
                    <tr>
                        <th scope="row">작성일</th>
                        <td><?=date('Y-m-d')?></td>
                        <th scope="row">수신</th>
                        <td><span id="customName"><input type="text" class="frm_input" name="customNameValue" placeholder="공급받는 업체명을 입력해주세요." style="width: 170px;"></span></td>
                    </tr>
                    <tr>
                        <th scope="row">담당자</th>
                        <td><span id="memberName"><input type="text" class="frm_input" name="memberNameValue" placeholder="담당자명을 입력해주세요." style="width: 165px;"></span></td>
                        <th scope="row">전화번호</th>
                        <td><span id="memberTel"><input type="text" class="frm_input" name="memberTelValue" placeholder="전화번호를 입력해주세요." style="width: 170px;"></span></td>
                    </tr>
                </table>
            </div>
        </section>

        <div class="tbl_head02 tbl_wrap">
            <table>
                <colgroup>
                    <col>
                    <col class="w100">
                    <col class="w60">
                    <col class="w100">
                    <col class="w80">
                </colgroup>
                <thead>
                <tr>
                    <th scope="col">상품정보</th>
                    <th scope="col">상품금액</th>
                    <th scope="col">수량</th>
                    <th scope="col">결제금액</th>
                    <th scope="col">배송비</th>
                </tr>
                </thead>
                <?php
                $where_add = get_cart_mode('select','','where');

                $sql = " select * 
                           from shop_cart 
                          where ct_select = '0' 
                                {$where_add}
                          group by gs_id 
                          order by index_no ";

                $result = sql_query($sql);
                $tot_point		= 0;
                $tot_sell_price = 0;
                $tot_opt_price	= 0;
                $tot_sell_qty	= 0;
                $tot_sell_amt	= 0;
                $sellers_price = array();

                for($i=0; $row=sql_fetch_array($result); $i++) {
                    $gs = get_goods($row['gs_id']);

                    // 합계금액 계산
                    $sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
						SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
						SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
						SUM(io_price * ct_qty) as opt_price
					from shop_cart
				   where gs_id = '$row[gs_id]' 
                        {$where_add}
					 and ct_select = '0'";
                    $sum = sql_fetch($sql);

                    if($i==0) { // 계속쇼핑
                        $continue_ca_id = $row['ca_id'];
                    }

                    unset($it_name);
                    unset($mod_options);
                    $it_options = print_item_options($row['gs_id']);
                    if($it_options) {
                        $it_name = '<div class="sod_opt">'.$it_options.'</div>';
                    }

                    $point = $sum['point'];
                    $sell_price = $sum['price'];
                    $sell_opt_price = $sum['opt_price'];
                    $sell_qty = $sum['qty'];
                    $sell_amt = $sum['price'] - $sum['opt_price'];

                    // 배송비
                    if($gs['use_aff'])
                        $sr = get_partner($gs['mb_id']);
                    else
                        $sr = get_seller_cd($gs['mb_id']);

                    $info = get_item_sendcost($sell_price);
                    $item_sendcost[] = $info['pattern'];

                    $del_type = explode("|",$info['pattern']);
                    if($del_type[1]==='묶음') $sellers_price[$sr['seller_code']] += $sell_price;

                    $it_href = TB_SHOP_URL.'/view.php?index_no='.$row['gs_id'];
                    ?>
                    <tr>
                        <td class="td_name">
                            <input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
                            <a href="<?php echo $it_href; ?>" target="_blank"><?php echo $gs['gname']; ?></a>
                            <?php echo $it_name; ?>
                        </td>
                        <td class="tar"><?php echo number_format(($sell_price/$sell_qty)); ?></td>
                        <td class="tac"><?php echo number_format($sell_qty); ?></td>
                        <td class="tar"><?php echo number_format($sell_price); ?></td>
                        <td class="tar"><?php echo number_format($info['price']); ?></td>
                    </tr>
                    <?php
                    $tot_point		+= $point;
                    $tot_sell_price += $sell_price;
                    $tot_opt_price	+= $sell_opt_price;
                    $tot_sell_qty	+= $sell_qty;
                    $tot_sell_amt	+= $sell_amt;

                    if(!$is_member) {
                        $tot_point = 0;
                    }
                } // for

                if($i == 0) {
                    echo '<tr><td colspan="8" class="empty_table">장바구니에 담긴 상품이 없습니다.</td></tr>';
                }

                // 배송비 검사
                $send_cost = 0;
                $com_send_cost = 0;
                $sep_send_cost = 0;
                $max_send_cost = 0;

                if($i > 0) {
                    $k = 0;
                    $condition = array();
                    foreach($item_sendcost as $key) {
                        list($userid, $bundle, $price) = explode('|', $key);
                        $condition[$userid][$bundle][$k] = $price;
                        $k++;
                    }

                    $com_array = array();
                    $val_array = array();
                    foreach($condition as $key=>$value) {
                        if($condition[$key]['묶음']) {
                            if(chkFreeDelivery($sellers_price[$key],$sr)) {
                                $max_send_cost += 0; // 가장 큰 배송비 합산
                                $com_array[] = ""; // max key
                                $val_array[] = "";// max value
                            }else{
                                $max_send_cost += max($condition[$key]['묶음']); // 가장 큰 배송비 합산
                                $com_array[] = max(array_keys($condition[$key]['묶음'])); // max key
                                $val_array[] = max(array_values($condition[$key]['묶음']));// max value
                            }
                            $com_send_cost += array_sum($condition[$key]['묶음']); // 묶음배송 합산
                        }
                        if($condition[$key]['개별']) {
                            $sep_send_cost += array_sum($condition[$key]['개별']); // 묶음배송불가 합산
                            $com_array[] = array_keys($condition[$key]['개별']); // 모든 배열 key
                            $val_array[] = array_values($condition[$key]['개별']); // 모든 배열 value
                        }
                    }

                    $tune = get_tune_sendcost($com_array, $val_array);

                    $send_cost = $com_send_cost + $sep_send_cost; // 총 배송비합계
                    $tot_send_cost = $max_send_cost + $sep_send_cost; // 최종배송비
                    $tot_price = $tot_sell_price + $tot_send_cost; // 결제예정금액
                }
                ?>
                </tbody>
            </table>
        </div>

        <dl id="sod_ws_tot">
            <dt class="bt_nolne">주문총액</dt>
            <dd class="bt_nolne"><strong><?php echo display_price($tot_sell_price); ?></strong></dd>
            <dt>배송비</dt>
            <dd><strong><?php echo display_price($send_cost); ?></strong></dd>
            <dt class="ws_price">총계</dt>
            <dd class="ws_price"><strong><?php echo display_price($tot_price); ?></strong></dd>
            <dd class="bt_nolne" style="width: 100%; text-align: left;">※ 상기 금액은 견적일에 따라 상품 금액의 차이가 발생할 수 있습니다. 견적일 기준가격을 참고해 주세요.</dd>
        </dl>

        <div class="btn_confirm">
            <span>위와 같이 견적합니다.</span>
        </div>

        <div class="btn_confirm marb50 noprint">
            <a href='javascript:chk_od_print(["customName","memberName","memberTel"]);' class="btn_medium">인쇄</a>
            <a href='javascript:self.close();' class="btn_medium bx-white">닫기</a>
        </div>
    </div>
<?php
include_once(TB_PATH."/tail.sub.php");
?>