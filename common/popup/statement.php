<?php
include_once("../../common.php");

$tb['title'] = '거래명세서';
include_once(TB_PATH."/head.sub.php");

$od = get_order($od_id); // 주문정보
if(!$od['od_id']) {
    alert_close("존재하는 주문이 아닙니다.");
}

$stotal = get_order_spay($od_id); // 총계
?>
    <style>
        #sod_fin_vendor table tr th { text-align: center; }
        .top-title { height: 50px; line-height: 50px; text-align: center; font-size: 2em; background: #f3f3f3; }
    </style>
    <style type="text/css" media="print">
        .noprint { display: none; }
    </style>
    <div class="top-title">
        <b> 거 래 명 세 서 </b>
    </div>
    <div style="margin:10px">
        <?php include_once dirname(__FILE__)."/inc/sod_fin_vendor.php"; ?>

        <section id="sod_fin_receiver" style="padding-bottom: 30px;">
            <h2 class="anc_tit">공급받는 자</h2>
            <div class="tbl_frm01 tbl_wrap">
                <table>
                    <colgroup>
                        <col class="w50">
                        <col class="w180">
                        <col class="w50">
                        <col class="w200">
                    </colgroup>
                    <tr>
                        <th scope="row">작성일<br>(주문번호)</th>
                        <td><?=date('Y-m-d',strtotime($od['od_time']))?><br>(<?=$od['od_id']?>)</td>
                        <th scope="row">수신</th>
                        <td><span id="customName"><input type="text" class="frm_input" name="customNameValue" placeholder="공급받는 업체명을 입력해주세요." style="width: 170px;"></span></td>
                    </tr>
                    <tr>
                        <th scope="row">담당자</th>
                        <td><?=$od['name']?></td>
                        <th scope="row">전화번호</th>
                        <td><?=$od['b_cellphone']?></td>
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
                <tbody>
                <?php
                $sql = " select * 
				   from shop_cart 
				  where od_id = '$od_id' 
				  group by gs_id 
				  order by index_no ";
                $result = sql_query($sql);
                for($i=0; $row=sql_fetch_array($result); $i++) {
                    $rw = get_order($row['od_no']);
                    $gs = unserialize($rw['od_goods']);

                    $it_name = stripslashes($gs['gname']);
                    $it_options = print_complete_options($row['gs_id'], $row['od_id']);
                    if($it_options){
                        $it_name .= '<div class="sod_opt">'.$it_options.'</div>';
                    }
                    ?>
                    <tr>
                        <td><?php echo $it_name; ?></td>
                        <td class="tar"><?php echo display_price($gs['goods_price']); ?></td>
                        <td class="tac"><?php echo display_qty($rw['sum_qty']); ?></td>
                        <td class="tar"><?php echo display_price($gs['goods_price']*$rw['sum_qty']); ?></td>
                        <td class="tar"><?php echo display_price($rw['baesong_price']); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <dl id="sod_ws_tot">
            <dt class="bt_nolne">주문총액</dt>
            <dd class="bt_nolne"><strong><?php echo display_price($stotal['price']); ?></strong></dd>
            <?php if($stotal['coupon']) { ?>
                <dt>쿠폰할인</dt>
                <dd><strong><?php echo display_price($stotal['coupon']); ?></strong></dd>
            <?php } ?>
            <?php if($stotal['usepoint']) { ?>
                <dt>포인트결제</dt>
                <dd><strong><?php echo display_point($stotal['usepoint']); ?></strong></dd>
            <?php } ?>
            <?php if($stotal['baesong']) { ?>
                <dt>배송비</dt>
                <dd><strong><?php echo display_price($stotal['baesong']); ?></strong></dd>
            <?php } ?>
            <dt class="ws_price">총계</dt>
            <dd class="ws_price"><strong><?php echo display_price($stotal['useprice']); ?></strong></dd>
            <dt class="bt_nolne"></dt>
            <dd class="bt_nolne"></dd>
        </dl>

        <div class="btn_confirm">
            <span>위와 같이 계산합니다.</span>
        </div>

        <div class="btn_confirm marb50 noprint">
            <a href='javascript:chk_od_print(["customName"]);' class="btn_medium">인쇄</a>
            <a href='javascript:self.close();' class="btn_medium bx-white">닫기</a>
        </div>
    </div>
<?php
include_once(TB_PATH."/tail.sub.php");
?>