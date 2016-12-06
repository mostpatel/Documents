<?php
require_once('cg.php');
require_once('bd.php');
require_once('file-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

$inputFileName = 'sheet.xlsx';

//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

//  Get worksheet dimensions
$sheet = $objPHPExcel->getSheet(0); 
$highestRow = $sheet->getHighestRow(); 
$highestColumn = $sheet->getHighestColumn();

//  Loop through each row of the worksheet in turn
for ($row = 1; $row <= $highestRow; $row++){ 
    //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
	$company_code=$rowData[0][1];
	$item_name=$rowData[0][2];
	$mrp=$rowData[0][5];
	if($company_code!="" && $item_name!="" && $mrp!="")
	{
	echo $company_code." ".$item_name." ".$mrp." <br>";			
	}
    //  Insert row data array into your database of choice here
}

 ?>