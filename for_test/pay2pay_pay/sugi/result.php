<meta charset="utf-8">
<?php
include dirname(__FILE__)."/../_common.php";

$result = $pay2Pay_Pay->sugiProc($_POST);

Pay2Pay_Pay::printData($result);