<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_faq ";
$sql_search = " where 1=1 ";
$sql_order  = " order by index_no desc";

if($_SESSION['ss_mb_id']!='admin')
{
    $sql_search .= " AND pt_id ='{$_SESSION['ss_mb_id']}' ";
}else{
    $sql_search .= " AND pt_id ='' ";
}

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if($sca) {
    $sql_search .= " and cate='$sca' ";
}

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$pg_title = "FAQ 관리";
include_once("./admin_head.sub.php");

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./page.php?code=partner_faq_form" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 추가하기</a>
EOF;
?>
<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
    <input type="hidden" name="code" value="<?php echo $code; ?>">
    <div class="tbl_frm01">
        <table>
            <colgroup>
                <col class="w100">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row">검색어</th>
                <td>
                    <select name="sca">
                        <option value="">FAQ카테고리</option>
                        <?php
                        $sql = get_faq_table_sql($pt_id,'shop_faq_cate');
                        $res = sql_query($sql);
                        while($row=sql_fetch_array($res)) {
                            echo option_selected($row['index_no'], $sca, $row['catename']);
                        }
                        ?>
                    </select>
                    <select name="sfl">
                        <?php echo option_selected('subject', $sfl, '제목'); ?>
                        <?php echo option_selected('memo', $sfl, '내용'); ?>
                    </select>
                    <input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="btn_confirm">
        <input type="submit" value="검색" class="btn_medium">
        <input type="button" value="초기화" id="frmRest" class="btn_medium grey">
    </div>
</form>

<form name="ffaqlist" id="ffaqlist" method="post" action="../admin/help/help_faq_delete.php" onsubmit="return ffaqlist_submit(this);">
    <input type="hidden" name="q1" value="<?php echo $q1; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="pt_id" value="<?=$pt_id?>">

    <div class="local_ov mart30">
        전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
    </div>
    <div class="local_frm01">
        <?php echo $btn_frmline; ?>
    </div>
    <div class="tbl_head01">
        <table>
            <colgroup>
                <col class="w50">
                <col class="w50">
                <col class="w100">
                <col>
                <col class="w80">
                <col class="w60">
            </colgroup>
            <thead>
            <tr>
                <th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
                <th scope="col">번호</th>
                <th scope="col">분류</th>
                <th scope="col">제목</th>
                <th scope="col">등록일</th>
                <th scope="col">관리</th>
            </tr>
            </thead>
            <?php
            for($i=0; $row=sql_fetch_array($result); $i++) {
                $index_no = $row['index_no'];
                $row2 = sql_fetch("select * from shop_faq_cate where index_no='$row[cate]'");

                $s_upd = "<a href=\"./page.php?code=partner_faq_form&w=u&index_no=$index_no$qstr&page=$page\" class=\"btn_small\">수정</a>";

                if($i==0)
                    echo '<tbody class="list">'.PHP_EOL;

                $bg = 'list'.($i%2);
                ?>
                <tr class="<?php echo $bg; ?>">
                    <td>
                        <input type="hidden" name="index_no[<?php echo $i; ?>]" value="<?php echo $index_no; ?>">
                        <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $index_no; ?></label>
                        <input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
                    </td>
                    <td><?php echo $num--; ?></td>
                    <td><?php echo $row2['catename']; ?></td>
                    <td class="tal"><a href="javascript:ubDisplay.display('<?php echo $i; ?>')"><?php echo get_text($row['subject']); ?></a></td>
                    <td><?php echo substr($row['wdate'],0,10); ?></td>
                    <td><?php echo $s_upd; ?></td>
                </tr>
                <tr id="view_<?php echo $i; ?>" style="display:none" bgcolor='fdfbf5'>
                    <td colspan="3"></td>
                    <td colspan="3" class="tal">
                        <?php echo get_view_thumbnail(conv_content($row['memo'], 1), 600); ?>
                    </td>
                </tr>
                <?php
            }
            if($i==0)
                echo '<tbody><tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
            ?>
            </tbody>
        </table>
    </div>
    <div class="local_frm02">
        <?php echo $btn_frmline; ?>
    </div>
</form>
</div>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
    function ffaqlist_submit(f)
    {
        if(!is_checked("chk[]")) {
            alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
            return false;
        }

        if(document.pressed == "선택삭제") {
            if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
                return false;
            }
        }

        return true;
    }

    var ubDisplay = {
        q_old : "",
        a_old : "",

        display : function(seq){
            var a_new = document.getElementById("view_"+seq);

            if(this.a_old != a_new){
                if(this.a_old != "")
                    this.a_old.style.display = "none";
                a_new.style.display = "";
                this.a_old = a_new;
            }else{
                a_new.style.display = "none";
                this.a_old = "";
            }
        }
    };
</script>
