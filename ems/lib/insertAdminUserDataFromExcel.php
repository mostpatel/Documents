<?php
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('adminuser-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

$inputFileName = 'AJMODI.xlsx';

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
for ($row = 0; $row <= $highestRow; $row++)
{ 
if($row == 0)
continue;
   //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
	
	$first_name = $rowData[0][0];
	$last_name = $rowData[0][1];
	$name = $first_name. " ".$last_name;
	$username = $rowData[0][2];
	$pass = $rowData[0][3];
	$phone = $rowData[0][4];
	$email = $rowData[0][5];
	$access = array(1,2,5,11);
	
	if(1==1)
	{
	
	insertAdminUser($name, $username, $pass, $email, $phone, $access);
	
	}
	
    //  Insert row data array into your database of choice here
}

?>