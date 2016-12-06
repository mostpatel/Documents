<?php
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('airport-functions.php');
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
for ($row = 2; $row <= $highestRow; $row++){ 
    //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
	$airport_code=$rowData[0][0];
	$airport_name=$rowData[0][1];
	$city_name=$rowData[0][3];
	$country_code=$rowData[0][4];
	$country_name=$rowData[0][5];
	
	
	if(validateForNull($airport_code) && validateForNull($airport_name) && validateForNull($city_name) && validateForNull($country_code) && validateForNull($country_name))
	{
			insertAirport($airport_code,$airport_name,$city_name,$country_code,$country_name);
	}
    //  Insert row data array into your database of choice here
}

 ?>