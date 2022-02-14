인증키 : <input type="text" name="verify_key" value="" maxlength="50"> <input type="button" value="인증번호 전송" onclick="send_verify_code(this.form, '<?=$od['cellphone'];?>')"/> <br>
인증번호 : <input type="text" name="verify_code" value="" maxlength="50"><br>
카드번호 : <input type="text" name="card_no" value="카드번호" maxlength="16"><br>
유효기간 : <input type="text" name="cardvalid_ym" value="유효기간" maxlength="4"><br>
할부개월 : <input type="text" name="sell_mm" value="할부개월" maxlength="2"><br>
상품명 : <input type="text" name="product_nm" value="<?php echo $product_nm; ?>"><br>
상품코드 : <input type="text" name="product_cd" value="<?php echo $product_cd; ?>"><br>
쇼핑몰 회원 ID : <input type="text" name="pmember_id" value="<?php echo $member['id']; ?>"><br>


<script>
    function send_verify_code(f, pn) {        
        $.ajax({
            type: "POST",
            data: {phone_number : pn},
            url: '/shop/pay2pay_pay/send_verify_code.php',
            dataType : "json",
            cache: false,            
        })
            .done( function(data) {
                if( data.result_code == '0000' ) {
                    f.verify_key.value = data.verify_key;
                    alert('휴대폰으로 받으신 인증번호를 입력해주십시오.');
                } else {
                    alert(data.error_msg);
                }                
            });
    }
</script>
