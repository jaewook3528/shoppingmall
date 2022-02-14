<? include dirname(__FILE__)."/../inc/head.php"; ?>
<?
$cellphone = '01012345678'; // 인증할 휴대폰번호
?>

<h3>수기 결제</h3>

<form method="post" action="result.php" id="pay2pay_form">

    결제타입 : <input type="text" name="pay_type" value="1"><br>
    
    
    카드번호 : <input type="text" name="card_no" value="" maxlength="16"><br>
    유효기간 : <input type="text" name="cardvalid_ym" value="" maxlength="4"> YYMM<br>
    할부개월 : <input type="text" name="sell_mm" value="00" maxlength="2"><br>

    결제금액(승인금액) : <input type="text" name="amt" value="1004"><br>

    주문번호 : <input type="text" name="order_no" value="<?=$pay2Pay_Pay->pg_info['vendor_id'].'_'.Pay2Pay_Pay::getDatetimeWithMicroseconds()?>"><br>

    결제자 성명 : <input type="text" name="buyer_nm" value="홍길동"><br>
    결제자 E-mail : <input type="text" name="email_addr" value="test@test.com"><br>

    인증키 : <input type="text" name="verify_key" value="" maxlength="50"> <input type="button" value="인증번호 전송" onclick="send_verify_code(this.form, '<?=$cellphone;?>')"/> <br>
    인증번호 : <input type="text" name="verify_code" value="" maxlength="50"><br>

    상품명 : <input type="text" name="product_nm" value="테스트 상품"><br>
    상품코드 : <input type="text" name="product_cd" value="테스트 상품코드"><br>
    쇼핑몰 회원 ID : <input type="text" name="pmember_id" value="test"><br>

    <input type="submit" value="결제">
</form>

<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script>
    function send_verify_code(f, pn) {        
        $.ajax({
            type: "POST",
            data: {phone_number : pn},
            url: 'send_verify_code.php',
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
    
<? include dirname(__FILE__)."/../inc/foot.php"; ?>