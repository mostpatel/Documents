<?php
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('adminuser-functions.php');
require_once('adminuser-functions.php');
require_once('customer-functions.php');
require_once('customer-extra-details-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

$inputFileName = 'sundry.xlsx';

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
for ($row = 1; $row <= $highestRow; $row++)
{ 

if($row == 1)
continue;
   //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
	
	
	$customer_name = $rowData[0][0];
	
	$customer_phone_array = array();
	
	
	array_push($customer_phone_array, 9999999999);
	
	
	

	
	if(validateForNull($customer_name))
	$customer_id=insertCustomer($customer_name,"NA", $customer_phone_array, 1);
	else
	$customer_id=NULL;
	
	if(!is_numeric($customer_id))
	{
	echo $row;
	echo $customer_name." ";
	echo " <br>";
	}
	
    //  Insert row data array into your database of choice here
}

?>