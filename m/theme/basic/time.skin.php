<?php
if(!defined('_TUBEWEB_')) exit;

// 상품 종료 기간
$ar_data_y = substr($gs['eb_date'], 0, 4);
$ar_data_m = substr($gs['eb_date'], 5, 2);
$ar_data_d = substr($gs['eb_date'], 8, 2);
$ar_data_h = "23";
$ar_data_i = "59";
$ar_data_s = "59";

$eb_date = mktime($ar_data_h, $ar_data_i, $ar_data_s, $ar_data_m, $ar_data_d, $ar_data_y);
$t = getdate($eb_date);
?>

<script type="text/javascript">
var targetDate = new Date(<?=$t[year];?>,<?=$t[mon]-1;?>,<?=$t[mday];?>,<?=$t[hours];?>,<?=$t[minutes];?>,<?=$t[seconds];?>);
var targetInMS = targetDate.getTime();

var oneSec = 1000;
var oneMin = 60 * oneSec;
var oneHr = 60 * oneMin;
var oneDay = 24 * oneHr;

function countDown() {
    var nowInMS = new Date().getTime();
    var diff = targetInMS - nowInMS;
    var scratchPad = diff / oneDay;
    var daysLeft = Math.floor(scratchPad);
    // hours left
    diff -= (daysLeft * oneDay);
    scratchPad = diff / oneHr;
    var hrsLeft = Math.floor(scratchPad);
    // minutes left
    diff -= (hrsLeft * oneHr);
    scratchPad = diff / oneMin;
    var minsLeft = Math.floor(scratchPad);
    // seconds left
    diff -= (minsLeft * oneMin);
    scratchPad = diff / oneSec;
    var secsLeft = Math.floor(scratchPad);
    // now adjust images
    setImages(daysLeft, hrsLeft, minsLeft, secsLeft);
}

function setImages(days, hrs, mins, secs) {
	if(days > 999){ // 999보다 높을때 오류남 2017 01 03
		days = 999;
	}
    days = formatNum(days, 3);
	$('.days').html(days);
    hrs = formatNum(hrs, 2);
	$('.hours').html(hrs);
    mins = formatNum(mins, 2);
	$('.minutes').html(mins);
    secs = formatNum(secs, 2);
	$('.seconds').html(secs);
}

function formatNum(num, len) {
    var numStr = "" + num;
    while (numStr.length < len) {
        numStr = "0" + numStr;
    }
    return numStr
}
</script>

<div class="time">
	<span class="tit">남은시간</span>
	<span class="days">000</span>
	<span class="day">일</span>
	<span class="hours">00</span>
	<span class="day">시간</span>
	<span class="minutes">00</span>
	<span class="day">분</span>
	<span class="seconds">00</span>
	<span class="day">초</span>
</div>
<script language="javascript">setInterval('countDown()', 1000);</script>