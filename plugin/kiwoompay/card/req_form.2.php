
<div style="display: none;">
    결제방법 : 신용카드 결제<br><br>
    <br>

    CPID : <input type="text" name="CPID" size="50" maxlength="50" value="<?php echo $kw_shop_id; ?>" style="IME-MODE:disabled"><br>
    주문번호 : <input type="text" name="ORDERNO" size="50" maxlength="50" value="<?php echo $od_id; ?>" style="IME-MODE:disabled"><br>
    상품구분 : <input type="text" name="PRODUCTTYPE" size="10" maxlength="2" value="2" style="IME-MODE:disabled"><br>
    과금유형 : <input type="text" name="BILLTYPE" size="10" maxlength="2"  value="1" style="IME-MODE:disabled"><br>
    결제금액 : <input type="text" name="AMOUNT" size="10" maxlength="10" value="<?php echo $tot_price; ?>" style="IME-MODE:disabled" onkeypress="fnNumCheck();"><br>

    <?php echo $cpquota_html; // 할부개월수 목록 ?>
    
    고객 E-MAIL : <input type="text" name="EMAIL" size="100" maxlength="100" value="<?php echo $od['email']; ?>"><br>
    고객아이디 : <input type="text" name="USERID" size="30" maxlength="30" value="<?php $t = $member['id']; echo $t?$t:'guest'; ?>"><font color="red"><b>(자신의 ID를 직접 넣어주세요.)</b></font><br>
    고객명 : <input type="text" name="USERNAME" size="50" maxlength="50" value="<?php echo cut_str($od['name'], 10); ?>"><br>
    상품코드 : <input type="text" name="PRODUCTCODE" size="10" value="<?php echo cut_str($product_cd, 10); ?>"><br>
    상품명 : <input type="text" name="PRODUCTNAME" size="50" value="<?php echo cut_str($product_nm,20); ?>"><br>
    예약항목1 : <input type="text" name="RESERVEDINDEX1" size="20" value=""><br>
    예약항목2 : <input type="text" name="RESERVEDINDEX2" size="20" value=""><br>
    예약항목:<input type="text" name="RESERVEDSTRING" size="100" value=""><br>
    RETURNURL:<input type=text name=RETURNURL value=""><br>
    HOMEURL:<input type=text name=HOMEURL value="<?php echo $receive_url; ?>"><br>
    DIRECTRESULTFLAG:<input type=text name=DIRECTRESULTFLAG value="Y"><br>
    CARDLIST : <input type=text name=CARDLIST value=""><br>
    HIDECARDLIST : <input type=text name=HIDECARDLIST value="">
    비과세결제유무(과세 테스트 <font color='blue'>00</font>, 비과세 테스트 : <font color='red'>01</font>):<input type=text name=TAXFREECD value="00">
    환금성(웹하드,SMS업종등)or게임사인경우만 사용:<input type=”hidden” name=”POPUPTYPE” value=""> A=게임사, B=충전식(환금성)
    <br><br><br>

    <input name="btnSubmit" type="button" value="주문하기" onclick="fnSubmit()" ><br>
</div>