계정 아이디 : <input type="text" name="vendor_id" value="<?=$this->pg_info['vendor_id']?>" maxlength="16"><br>

결제타입 : <input type="text" name="pay_type" value="3"><br>

결제금액(승인금액) : <input type="text" name="amt" value="<?php echo $tot_price; ?>"><br>

주문번호 : <input type="text" name="order_no" value="<?=$od['od_id']?>"><br>

결제자 성명 : <input type="text" name="buyer_nm" value="<?=$od['name']?>"><br>
결제자 E-mail : <input type="text" name="email_addr" value="<?=$od['email']?>"><br>

상품명 : <input type="text" name="product_nm" value="<?php echo $product_nm; ?>"><br>
상품코드 : <input type="text" name="product_cd" value="<?php echo $product_cd; ?>"><br>
쇼핑몰 회원 ID : <input type="text" name="pmember_id" value="<?php echo $member['id']; ?>"><br>

인증정보수신URL : <input type="text" name="receive_url" value="<?=TB_SHOP_URL."/pay2pay_pay/receive.php"?>"><br>

주문정보암호화필드 : <input type="text" name="enc_data" value=""><br>

<script>

    var prev_form = null;

    function pay2pay_pay_submit(f) {
        var new_win_name = "pay2pay_pay";
        var new_win = window.open("", new_win_name, "width=579,height=527");
        new_win.focus();

        var prev_attrs = { action : f.action, target : f.target };

        f.action = "https://<?=self::$service_r[ self::SERV_KEY ].self::PG_DIRECT_URL?>";
        f.target = new_win_name;
        f.submit();
        f.action = prev_attrs.action;
        f.target = prev_attrs.target;

        prev_form = f;
    }

    // 결과값 반환( receive 페이지에서 호출 )
    function result_submit(result_cd,result_msg,enc_data) {
        if( result_cd != '0000' ) {
            window.setTimeout(
                function () {
                    history.go(-1);
                    alert(result_cd + " : " + result_msg);
                }
                , 1000
            );
        } else {
            prev_form.enc_data.value = enc_data;
            prev_form.submit();
        }
    }

</script>