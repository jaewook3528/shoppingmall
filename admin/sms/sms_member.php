<?php
include_once("./_common.php");

$tb['title'] = '문자전송';
include_once(TB_ADMIN_PATH."/admin_head.php");

$sms = sql_fetch("select * from shop_sms ");
if(!$sms['cf_sms_use']) {
	alert_close('문자서비스를 사용가능한 설정 상태가 아닙니다.');
}
?>

<h1 class="psms_tit"><?php echo $tb['title']; ?></h1>
<div class="psms_wrap">
<form name="fsend" method="post" onsubmit="return fsend_submit(this)">
	<div class="tbl_frm02">
	<table>
	<colgroup>
		<col width="75px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<td colspan="2">
			<p class="marb7">※ 한글 40자까지 전송가능합니다.</p>
			<textarea name="sms_content" id="sms_content" class="frm_textbox wfull" onkeyup="byte_check('sms_content', 'byte', 'max_byte');" rows="6"></textarea>
			<p class="mart7 tar"><span id="byte">0</span> / <span id="max_byte"><?php echo ($sms['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte</p>
		</td>
	</tr>
	<tr>
		<th scope="row">발신번호</th>
		<td><?php echo replace_tel($sms['cf_sms_recall']); ?></td>
	</tr>
	<tr>
		<th scope="row">수신번호</th>
		<td>
			<select name="send_type" class="wfull">
				<option value="all">전체전송</option>
				<?php
				$sql = "select *
						  from shop_member_grade
						 where gb_name != ''
						   and gb_no != '1'
						 order by gb_no";
				$res = sql_query($sql);
				while($row = sql_fetch_array($res)) {
					echo "<option value='$row[gb_no]'>$row[gb_name]</option>\n";
				}
				?>
			</select>
		</td>
	</tr>
	</tbody>
	</table>
	</div>

	<div class="btn_confirm">
		<input type="submit" class="btn_lsmall" value="메세지보내기">
		<a href="javascript:self.close()" class="btn_lsmall bx-white">닫기</a>
	</div>
</form>
</div>

<script>
function fsend_submit(f) {
	if(f.sms_content.value == '') {
		alert('메세지 내용을 입력하세요.');
		f.sms_content.focus();
		return false;
	}

	f.action = "./sms_member_update.php";
    return true;
}

function byte_check(el_message, el_byte, el_max_byte)
{
    var conts = document.getElementById(el_message);
    var bytes = document.getElementById(el_byte);
    var max_bytes = document.getElementById(el_max_byte);

    var i = 0;
    var cnt = 0;
    var exceed = 0;
    var ch = '';

    for(i=0; i<conts.value.length; i++)
    {
        ch = conts.value.charAt(i);
        if(escape(ch).length > 4) {
            cnt += 2;
        } else {
            cnt += 1;
        }
    }

    bytes.innerHTML = cnt;

    <?php if($sms['cf_sms_type'] == 'LMS') { ?>
    if(cnt > 90)
        max_bytes.innerHTML = 1500;
    else
        max_bytes.innerHTML = 90;

    if(cnt > 1500)
    {
        exceed = cnt - 1500;
        alert('메시지 내용은 1500바이트를 넘을수 없습니다.\n\n작성하신 메세지 내용은 '+ exceed +'byte가 초과되었습니다.\n\n초과된 부분은 자동으로 삭제됩니다.');
        var tcnt = 0;
        var xcnt = 0;
        var tmp = conts.value;
        for(i=0; i<tmp.length; i++)
        {
            ch = tmp.charAt(i);
            if(escape(ch).length > 4) {
                tcnt += 2;
            } else {
                tcnt += 1;
            }

            if(tcnt > 1500) {
                tmp = tmp.substring(0,i);
                break;
            } else {
                xcnt = tcnt;
            }
        }
        conts.value = tmp;
        bytes.innerHTML = xcnt;
        return;
    }
    <?php } else { ?>
    if(cnt > 80)
    {
        exceed = cnt - 80;
        alert('메시지 내용은 80바이트를 넘을수 없습니다.\n\n작성하신 메세지 내용은 '+ exceed +'byte가 초과되었습니다.\n\n초과된 부분은 자동으로 삭제됩니다.');
        var tcnt = 0;
        var xcnt = 0;
        var tmp = conts.value;
        for(i=0; i<tmp.length; i++)
        {
            ch = tmp.charAt(i);
            if(escape(ch).length > 4) {
                tcnt += 2;
            } else {
                tcnt += 1;
            }

            if(tcnt > 80) {
                tmp = tmp.substring(0,i);
                break;
            } else {
                xcnt = tcnt;
            }
        }
        conts.value = tmp;
        bytes.innerHTML = xcnt;
        return;
    }
    <?php } ?>
}
</script>

<?php
include_once(TB_ADMIN_PATH.'/admin_tail.sub.php');
?>