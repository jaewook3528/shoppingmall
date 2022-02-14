<?php
$t_admin = $pt ? $pt : $config;
$custom_domain = "http://www.";
if($mk['homepage']!==''){
    $custom_domain .= $mk['homepage'];
}else{
    if($pt['mb_id']!=''){
        $custom_domain .= "{$pt['mb_id']}.";
    }
    $custom_domain .= $config['admin_shop_url'];
}
?>
<section id="sod_fin_vendor">
    <h2 class="anc_tit">공급자</h2>
    <div class="tbl_frm01 tbl_wrap">
        <table>
            <colgroup>
                <col class="w120">
                <col>
                <col class="w120">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row">사업자번호</th>
                <td colspan="3"><?=$t_admin['company_saupja_no']?></td>
            </tr>
            <tr>
                <th scope="row">상호명</th>
                <td><?=$t_admin['company_name']?></td>
                <th scope="row">대표</th>
                <td><?=$t_admin['company_owner']?></td>
            </tr>
            <tr>
                <th scope="row">사업장주소</th>
                <td colspan="3"><?php echo get_text(sprintf("(%s)", $t_admin['company_zip']).' '.$t_admin['company_addr']); ?></td>
            </tr>
            <tr>
                <th scope="row">업태</th>
                <td><?=$t_admin['company_item']?></td>
                <th scope="row">종목</th>
                <td><?=$t_admin['company_service']?></td>
            </tr>
            <tr>
                <th scope="row">쇼핑몰 주소 / 연락처</th>
                <td colspan="3"><?=$custom_domain?> / <?=$t_admin['company_tel']?></td>
            </tr>
            </tbody>
        </table>
    </div>
</section>