

<script type="text/javascript">

    function fnSubmit(pf) {
        var fileName;
        var fileName_pc = "<?=$pg_url?>/card/DaouCardMng.jsp"; // 피씨
        var fileName_m = "<?=$pg_url?>/m/card/DaouCardMng.jsp"; // 모바일

        var UserAgent = navigator.userAgent;

        if (UserAgent.match(/iPhone|iPod|iPad|Android|Windows CE|BlackBerry|Symbian|Windows Phone|webOS|Opera Mini|Opera Mobi|POLARIS|IEMobile|lgtelecom|nokia|SonyEricsson/i) != null || UserAgent.match(/LG|SAMSUNG|Samsung/) != null){
            fileName = fileName_m;
            KIWOOMPAY = window.open("", "KIWOOMPAY", "fullscreen");
            KIWOOMPAY.focus();
            pf.target = "KIWOOMPAY";
        }else{
            fileName = fileName_pc;
            KIWOOMPAY = window.open("", "KIWOOMPAY", "width=579,height=527");
            KIWOOMPAY.focus();
            pf.target = "KIWOOMPAY";
        }
        pf.action = fileName;
        pf.method = "post";
        var p_acceptCharset = pf.acceptCharset;
        pf.acceptCharset = 'euc-kr';

        // IE accept-charset 설정값 반영 되지 않는 문제 대응
        var prev_charset = document.charset;
        if (pf.canHaveHTML) { // detect IE
            document.charset = pf.acceptCharset;
        }

        pf.submit();

        // IE accept-charset 설정값 반영 되지 않는 문제 대응(복구)
        document.charset = prev_charset;

        pf.acceptCharset = p_acceptCharset;
    }


    function fnCheck() {

        var frm = document.frmConfirm;

        //주문번호
        if(trim(frm.ORDERNO.value) == "" || getByteLen(frm.ORDERNO.value) > 50) {
            alert("주문번호 (ORDERNO) 를 입력해주세요. (최대:50byte, 현재:" + getByteLen(frm.ORDERNO.value) + ")");
            return;
        }
        //상품구분
        if(trim(frm.PRODUCTTYPE.value) == "" || getByteLen(frm.PRODUCTTYPE.value) > 2) {
            alert("상품구분 (PRODUCTTYPE) 를 입력해주세요. (최대:2byte, 현재:" + getByteLen(frm.PRODUCTTYPE.value) + ")");
            return;
        }
        //과금유형
        if(trim(frm.BILLTYPE.value) == "" || getByteLen(frm.BILLTYPE.value) > 2) {
            alert("과금유형 (BILLTYPE) 를 입력해주세요. (최대:2byte, 현재:" + getByteLen(frm.BILLTYPE.value) + ")");
            return;
        }
        //결제금액
        if(trim(frm.AMOUNT.value) == "" || getByteLen(frm.AMOUNT.value) > 10) {
            alert("결제금액 (AMOUNT) 를 입력해주세요. (최대:10byte, 현재:" + getByteLen(frm.AMOUNT.value) + ")");
            return;

        }
        /********************  필수 입력 체크 끝  ***/
    }


    function trim(txt) {
        while (txt.indexOf(' ') >= 0) {
            txt = txt.replace(' ','');
        }
        return txt;
    }

    function getTimeStamp() {
        var d = new Date();
        var month = d.getMonth() + 1;
        var date = d.getDate();
        var hour = d.getHours();
        var minute = d.getMinutes();
        var second = d.getSeconds();

        month = (month < 10 ? "0" : "") + month;
        date = (date < 10 ? "0" : "") + date;
        hour = (hour < 10 ? "0" : "") + hour;
        minute = (minute < 10 ? "0" : "") + minute;
        second = (second < 10 ? "0" : "") + second;

        var s = d.getFullYear() + month + date + hour + minute + second;

        return s;
    }

    function getByteLen(p_val) {
        var onechar;
        var tcount = 0;

        for(i = 0; i < p_val.length; i++) {
            onechar = p_val.charAt(i);
            if(escape(onechar).length > 4)
                tcount += 2;
            else if(onechar != '\r')
                tcount++;
        }
        return tcount;
    }

    function fnNumCheck() {
        if(event.keyCode >= 48 && event.keyCode <= 57)
            event.returnValue = true;
        else
            event.returnValue = false;
    }

    // 결과값 반환( receive 페이지에서 호출 )
    function uf_get_order_form() {
        var form_name = null;
        // 모바일
        if( typeof tb_is_mobile === "string" && tb_is_mobile === "1" ){
            form_name = 'sendFm';
        } else { // 피씨
            form_name = 'fm';
        }

        return document.forms[form_name];
    }

    function uf_result_submit() {
        var fm = uf_get_order_form();
        fm.action = tb_shop_url+"/orderformresult.php";
        fm.method = "post";
        fm.target = "_self";
        fm.submit();
    }

    function uf_get_orderno() {
        var fm = uf_get_order_form();

        return fm.ORDERNO.value;
    }

</script>

