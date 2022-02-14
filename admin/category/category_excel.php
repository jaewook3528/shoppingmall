<?php
include_once("./_common.php");

check_demo();

$sql_order = " order by list_view asc ";

$sql = " select * from shop_cate where length(catecode)='3' {$sql_order} ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

/** Include PHPExcel */
include_once(TB_LIB_PATH.'/PHPExcel.php');

// Create new PHPExcel object
$excel = new PHPExcel();

// Add some data
$char = 'A';
$excel->setActiveSheetIndex(0)
	->setCellValue($char++.'1', '1차분류')
	->setCellValue($char++.'1', '2차분류')
	->setCellValue($char++.'1', '3차분류')
	->setCellValue($char++.'1', '4차분류')
	->setCellValue($char++.'1', '5차분류')
	->setCellValue($char++.'1', '분류코드');

$i = 2;
while($row1 = sql_fetch_array($result)) { // 1차 분류
	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValue($char++.$i, $row1['catename'])
		->setCellValue($char++.$i, '')
		->setCellValue($char++.$i, '')
		->setCellValue($char++.$i, '')
		->setCellValue($char++.$i, '')
		->setCellValue($char++.$i, $row1['catecode']);
	$i++;

	$sql2 = " select * from shop_cate where upcate='{$row1['catecode']}' {$sql_order} ";
	$result2 = sql_query($sql2);
	while($row2 = sql_fetch_array($result2)) { // 2차 분류
		$char = 'A';
		$excel->setActiveSheetIndex(0)
			->setCellValue($char++.$i, $row1['catename'])
			->setCellValue($char++.$i, $row2['catename'])
			->setCellValue($char++.$i, '')
			->setCellValue($char++.$i, '')
			->setCellValue($char++.$i, '')
			->setCellValue($char++.$i, $row2['catecode']);
		$i++;

		$sql3 = " select * from shop_cate where upcate='{$row2['catecode']}' {$sql_order} ";
		$result3 = sql_query($sql3);
		while($row3 = sql_fetch_array($result3)) { // 3차 분류			
			$char = 'A';
			$excel->setActiveSheetIndex(0)
				->setCellValue($char++.$i, $row1['catename'])
				->setCellValue($char++.$i, $row2['catename'])
				->setCellValue($char++.$i, $row3['catename'])
				->setCellValue($char++.$i, '')
				->setCellValue($char++.$i, '')
				->setCellValue($char++.$i, $row3['catecode']);
			$i++;

			$sql4 = " select * from shop_cate where upcate='{$row3['catecode']}' {$sql_order} ";
			$result4 = sql_query($sql4);
			while($row4 = sql_fetch_array($result4)) { // 4차 분류	
				$char = 'A';
				$excel->setActiveSheetIndex(0)
					->setCellValue($char++.$i, $row1['catename'])
					->setCellValue($char++.$i, $row2['catename'])
					->setCellValue($char++.$i, $row3['catename'])
					->setCellValue($char++.$i, $row4['catename'])
					->setCellValue($char++.$i, '')
					->setCellValue($char++.$i, $row4['catecode']);
				$i++;

				$sql5 = " select * from shop_cate where upcate='{$row4['catecode']}' {$sql_order} ";
				$result5 = sql_query($sql5);
				while($row5 = sql_fetch_array($result5)) { // 5차 분류			
					$char = 'A';
					$excel->setActiveSheetIndex(0)
						->setCellValue($char++.$i, $row1['catename'])
						->setCellValue($char++.$i, $row2['catename'])
						->setCellValue($char++.$i, $row3['catename'])
						->setCellValue($char++.$i, $row4['catename'])
						->setCellValue($char++.$i, $row5['catename'])
						->setCellValue($char++.$i, $row5['catecode']);
					$i++;
				}
			}
		}
	}	
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('분류');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="분류-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>