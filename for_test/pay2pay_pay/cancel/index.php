<? include dirname(__FILE__)."/../inc/head.php"; ?>

<h3>결제 취소</h3>
<form method="post" action="result.php" id="pay2pay_form">
    
    거래번호 : <input type="text" name="tr_no" value=""><br>
    취소금액 : <input type="text" name="amt" value=""><br>
    취소사유 : <input type="text" name="cancel_memo" value=""><br>

    <input type="submit" value="취소" >
</form>

<? include dirname(__FILE__)."/../inc/foot.php"; ?>