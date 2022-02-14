<?php
if(!defined('_TUBEWEB_')) exit;

$co	= sql_fetch("select * from shop_content where pt_id='{$_SESSION['ss_mb_id']}' AND page_id='about'");
if($co['co_id']) $w = 'u';
if(!$co['co_id']) $co = sql_fetch("select * from shop_content where pt_id='default' AND page_id='about'");

$pg_title = "회사소개";
include_once("./admin_head.sub.php");

$frm_submit = '<div class="btn_confirm">';
$frm_submit .= '<input type="submit" value="저장" class="btn_large" accesskey="s">'.PHP_EOL;
$frm_submit .= '</div>';

?>

    <form name="frmcontentform" method="post" action="/mypage/page.php?code=partner_about_company_update" onsubmit="return frmcontentform_check(this);">
        <input type="hidden" name="w" value="<?php echo $w; ?>">
        <input type="hidden" name="co_id" value="<?php echo $co['co_id']; ?>">
        <input type="hidden" name="pt_id" value="<?php echo $_SESSION['ss_mb_id']; ?>">
        <input type="hidden" name="page_id" value="about">

        <div class="tbl_frm02">
            <table>
                <colgroup>
                    <col class="w140">
                    <col>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row">제목</th>
                    <td><input type="text" name="co_subject" value="<?php echo $co['co_subject']; ?>" required itemname="제목" class="required frm_input" size="60"></td>
                </tr>
                <?php if($w == 'u') { ?>
                    <tr>
                        <th scope="row">페이지 URL</th>
                        <td><input type="text" value="<?=$pt_id!='admin'?"":$partner['mb_id']."."?><?=$_SERVER['HTTP_HOST']?>/bbs/content.php?pt_id=<?=$_SESSION['ss_mb_id']?>&page_id=about" readonly class="frm_input list2" size="120"> <a href="http://<?=$pt_id!='admin'?"":$partner['mb_id']."."?><?=$_SERVER['HTTP_HOST']?>/bbs/content.php?pt_id=<?=$_SESSION['ss_mb_id']?>&page_id=about" target="_blank" class="btn_small grey">페이지 바로가기</a></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th scope="row">PC 내용</th>
                    <td>
                        <?php echo editor_html('co_content', get_text($co['co_content'], 0)); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">모바일 내용</th>
                    <td>
                        <?php echo editor_html('co_mobile_content', get_text($co['co_mobile_content'], 0)); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <?php echo $frm_submit; ?>
    </form>

    <script>
        function frmcontentform_check(f) {
            <?php echo get_editor_js('co_content'); ?>
            <?php echo get_editor_js('co_mobile_content'); ?>

            return true;
        }
    </script>

<?php
include_once("./admin_tail.sub.php");
?>