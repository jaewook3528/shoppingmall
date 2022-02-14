<?php
// 회원가입축하 메일 (회원님께 발송)
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>회원가입 알림 메일</title>
</head>

<body>

<div style="margin:30px auto;width:600px;border:10px solid #f7f7f7">
    <div style="border:1px solid #dedede">
        <h1 style="padding:30px 30px 0;background:#f7f7f7;color:#555;font-size:1.4em">
            회원가입 알림 메일
        </h1>
        <span style="display:block;padding:10px 30px 30px;background:#f7f7f7;text-align:right">
            <a href="<?php echo TB_URL; ?>" target="_blank"><?php echo $config['company_name']; ?></a>
        </span>
        <p style="margin:20px 0 0;padding:30px 30px 50px;min-height:200px;height:auto !important;height:200px;border-bottom:1px solid #eee">
			<b><?php echo $mb['name']; ?></b>님께서 회원가입 하셨습니다.<br>
            회원 아이디 : <b><?php echo $mb['id']; ?></b><br>
            회원 이름 : <?php echo $mb['name']; ?><br>
            추천인아이디 : <?php echo $mb['pt_id']; ?>
        </p>

        <a href="<?php echo TB_URL; ?>" target="_blank" style="display:block;padding:30px 0;background:#484848;color:#fff;text-decoration:none;text-align:center">사이트바로가기</a>
    </div>
</div>

</body>
</html>
