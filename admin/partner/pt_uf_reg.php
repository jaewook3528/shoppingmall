<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregisterform" method="post" action="./partner/pt_uf_reg_update.php" onsubmit="return fregisterform_submit(this);">
<input type="hidden" name="token" value="">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">회원명</th>
		<td><input type="text" name="name" required itemname="회원명" class="frm_input required" size="20"></td>
	</tr>
	<tr>
		<th scope="row">아이디</th>
		<td>
			<input type="text" name="id" id="mb_id" required memberid itemname="아이디" class="frm_input required" onkeyup="reg_mb_id_ajax();" size="20" minlength="3" maxlength="20">
			<span id="msg_mb_id" class="marl5"></span>
			<?php echo help('영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">비밀번호</th>
		<td>
			<input type="password" name="passwd" required itemname="비밀번호" class="frm_input required" size="20" minlength="4" maxlength="20">
			<?php echo help('4자 이상의 영문 및 숫자'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">전화번호</th>
		<td><input type="text" name="telephone" size="20"<?php echo $config['register_req_tel']?' required':''; ?> itemname="전화번호" class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>"></td>
	</tr>
	<tr>
		<th scope="row">휴대전화</th>
		<td>
			<input type="text" name="cellphone" size="20"<?php echo $config['register_req_hp']?' required':''; ?> itemname="휴대전화" class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>">
			<label><input type="checkbox" value="Y" name="smsser" checked> SMS를 수신합니다.</label>
		</td>
	</tr>
	<tr>
		<th scope="row">이메일</th>
		<td>
			<input type="text" name="email"<?php echo $config['register_req_email']?' required':''; ?>
			email itemname="이메일" class="frm_input<?php echo $config['register_req_email']?' required':''; ?>" size="40">
			<label><input type="checkbox" value="Y" name="mailser" checked> E-Mail을 수신합니다.</label>
		</td>
	</tr>
	<tr>
		<th scope="row">주소</th>
		<td>
			<div>
				<input type="text" name="zip"<?php echo $config['register_req_addr']?' required':''; ?> itemname="우편번호" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="8" maxlength="5" readonly>
				<a href="javascript:win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">주소검색</a>
			</div>
			<div class="mart5">
				<input type="text" name="addr1"<?php echo $config['register_req_addr']?' required':''; ?> itemname="주소" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="60" readonly> 기본주소
			</div>
			<div class="mart5">
				<input type="text" name="addr2" class="frm_input" size="60"> 상세주소
			</div>
			<div class="mart5">
				<input type="text" name="addr3" class="frm_input" size="60" readonly> 참고항목
				<input type="hidden" name="addr_jibeon" value="">
			</div>
		</td>
	</tr>

	<tr>
		<th scope="row">추천인</th>
		<td><input type="text" name="pt_id" value="admin" required itemname="추천인" class="frm_input required"></td>
	</tr>
	</tbody>
	</table>
</div>

<?
$partner['saupja_yes'] = 1;
$partner['company_type'] = 0;
?>

    <h2>사업자정보</h2>
    <div class="local_cmd01">
        <p>※ 아래 사업자정보는 쇼핑몰 하단에 노출되며 노출안함으로 설정시 본사 사업자정보가 노출 됩니다.</p>
    </div>
    <div class="tbl_frm01">
        <table>
            <colgroup>
                <col class="w180">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row">쇼핑몰 사업자노출 여부</th>
                <td>
                    <?php echo radio_checked('saupja_yes', $partner['saupja_yes'], '1', '노출함'); ?>
                    <?php echo radio_checked('saupja_yes', $partner['saupja_yes'], '0', '노출안함'); ?>
                </td>
            </tr>


            <tr>
                <th scope="row">사업자유형</th>
                <td>
                    <?php echo radio_checked('company_type', $partner['company_type'], '0', '일반과세자'); ?>
                    <?php echo radio_checked('company_type', $partner['company_type'], '1', '간이과세자'); ?>
                    <?php echo radio_checked('company_type', $partner['company_type'], '2', '면세사업자'); ?>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="company_owner">대표자명</label></th>
                <td>
                    <input type="text" name="company_owner" value="<?php echo $partner['company_owner']; ?>" id="company_owner" class="frm_input" size="30">
                    <em>예) 홍길동</em>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="company_saupja_no">사업자등록번호</label></th>
                <td>
                    <input type="text" name="company_saupja_no" value="<?php echo $partner['company_saupja_no']; ?>" id="company_saupja_no" class="frm_input" size="30">
                    <em>예) 000-00-00000</em>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="company_item">업태</label></th>
                <td>
                    <input type="text" name="company_item" value="<?php echo $partner['company_item']; ?>" id="company_item" class="frm_input" size="30">
                    <em>예) 소매업</em>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="company_service">종목</label></th>
                <td>
                    <input type="text" name="company_service" value="<?php echo $partner['company_service']; ?>" id="company_service" class="frm_input" size="30">
                    <em>예) 전자상거래업</em>
                </td>
            </tr>


            <tr>
                <th scope="row"><label for="tongsin_no">통신판매업신고번호</label></th>
                <td>
                    <input type="text" name="tongsin_no" value="<?php echo $partner['tongsin_no']; ?>" id="tongsin_no" class="frm_input" size="30">
                    <em>예) <?php echo TB_TIME_YEAR.'-서울강남-0000호'; ?></em>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="company_fax">팩스번호</label></th>
                <td>
                    <input type="text" name="company_fax" value="<?php echo $partner['company_fax']; ?>" id="company_fax" class="frm_input" size="30">
                    <em>예) 02-0000-0000</em>
                </td>
            </tr>


            </tbody>
        </table>
    </div>

    <h2>CS 운영시간</h2>
    <div class="tbl_frm01">
        <table>
            <colgroup>
                <col class="w180">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row"><label for="company_hours">상담가능시간</label></th>
                <td>
                    <input type="text" name="company_hours" value="오전9시~오후6시" id="company_hours" class="frm_input" size="60">
                    <em>예) 오전9시~오후6시</em>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="company_lunch">점심시간</label></th>
                <td>
                    <input type="text" name="company_lunch" value="오후12시~오후1시" id="company_lunch" class="frm_input" size="60">
                    <em>예) 오후12시~오후1시</em>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="company_close">휴무일</label></th>
                <td>
                    <input type="text" name="company_close" value="토요일,공휴일 휴무" id="company_close" class="frm_input" size="60">
                    <em>예) 토요일,공휴일 휴무</em>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <h2>전자결제 (PG) 설정</h2>
    <div class="tbl_frm01">
        <table>
            <colgroup>
                <col class="w180">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row"><label for="de_pg_service">결제대행사</label></th>
                <td>
                    <select name="de_pg_service" id="de_pg_service">
                        <?php echo option_selected("", $partner['de_pg_service'], " - 선택 - "); ?>
                        <?php echo option_selected("allat", $partner['de_pg_service'], "KG올앳"); ?>
                        <?php echo option_selected("kiwoom", $partner['de_pg_service'], "키움페이"); ?>
                    </select>
                    <?php echo help('쇼핑몰에서 사용할 결제대행사를 선택합니다.'); ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="pg_info_fld allat_info_fld">
        <h2>KG올앳 계약정보</h2>
        <div class="tbl_frm01">
            <table>
                <colgroup>
                    <col class="w180">
                    <col>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row"><label for="de_allat_mid">KG올앳 상점아이디</label></th>
                    <td>
                        <input type="text" name="de_allat_mid" value="<?php echo $partner['de_allat_mid']; ?>" id="de_allat_mid" class="frm_input" size="10">
                        <?php echo help('KG올앳으로 부터 발급 받으신 상점아이디(MID)를 입력해 주십시오.'); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="de_allat_crosskey">KG올앳 크로스키</label></th>
                    <td>
                        <input type="text" name="de_allat_crosskey" value="<?php echo $partner['de_allat_crosskey']; ?>" id="de_allat_crosskey" class="frm_input" size="40" maxlength="50">
                        <?php echo help('KG올앳으로 부터 발급 받으신 크로스키(CrossKey)를 입력해 주십시오.'); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div class="btn_confirm">
	<input type="submit" value="저장" id="btn_submit" class="btn_large" accesskey="s">
</div>
</form>

<script>

$(function() {
    var $f = $('form[name="fregisterform"]');
    $f.find('input, select').each(function(){
        var $_ = $(this);
        if( $.inArray($_.attr('name'), ["company_fax", "addr3", "tongsin_no", "de_allat_mid", "de_allat_crosskey"]) != -1 ) {
            return true;
        }
        $_.prop('required',true);
    });

    $("#de_pg_service").on("change", function() {
        $(".pg_info_fld:visible").hide();
        var pg = $(this).val();
        $("."+pg+"_info_fld").show();
    }).change();
});

function fregisterform_submit(f)
{
	var mb_id = reg_mb_id_check(f.id.value);
	if(mb_id) {
		alert("'"+mb_id+"'은(는) 사용하실 수 없는 아이디입니다.");
		f.id.focus();
		return false;
	}

    // 회원아이디 검사
	if(f.id.value.length < 3) {
		alert("아이디를 3글자 이상 입력하십시오.");
		f.id.focus();
		return false;
	}

    // 패스워드 검사
	if(f.passwd.value.length < 4) {
		alert("패스워드를 4글자 이상 입력하십시오.");
		f.passwd.focus();
		return false;
	}

	if(confirm("입력하신 사항들이 맞는지 확인하시기 바랍니다.\n\n저장 하시려면 '확인'버튼을 클릭하세요") == false)
		return false;

	document.getElementById("btn_submit").disabled = "disabled";

    return true;
}

// 회원아이디 검사
function reg_mb_id_check(mb_id)
{
    mb_id = mb_id.toLowerCase();

    var prohibit_mb_id = "<?php echo trim(strtolower($config['prohibit_id'])); ?>";
    var s = prohibit_mb_id.split(",");

    for(i=0; i<s.length; i++) {
        if(s[i] == mb_id)
            return mb_id;
    }
    return "";
}

// 금지 메일 도메인 검사
function prohibit_email_check(email)
{
    email = email.toLowerCase();

    var prohibit_email = "<?php echo trim(strtolower(preg_replace("/(\r\n|\r|\n)/", ",", $config['prohibit_email']))); ?>";
    var s = prohibit_email.split(",");
    var tmp = email.split("@");
    var domain = tmp[tmp.length - 1]; // 메일 도메인만 얻는다

    for(i=0; i<s.length; i++) {
        if(s[i] == domain)
            return domain;
    }
    return "";
}

function reg_mb_id_ajax() {
	var mb_id = $.trim($("#mb_id").val());
	$.post(
		tb_bbs_url+"/ajax.mb_id_check.php",
		{ mb_id: mb_id },
		function(data) {
			$("#msg_mb_id").empty().html(data);
		}
	);
}
</script>
