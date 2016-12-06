<?php
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('file-functions.php');
require_once('loan-functions.php');
require_once('broker-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');
require_once('addNewCustomer-functions.php');
require_once('city-functions.php');
require_once('area-functions.php');
require_once('vehicle-functions.php');
require_once('vehicle-company-functions.php');
require_once('vehicle-model-functions.php');
require_once('vehicle-type-functions.php');
require_once('vehicle-dealer-functions.php');
require_once('customer-functions.php');
require_once('loan-functions.php');
$inputFileName = 'jeetbhai.xlsx';

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
$rowData=array();
$files_array = array();
$updated = 0;
		$not_updated = 0;
//  Loop through each row of the worksheet in turn
for ($row = 1,$file_counter=0; $row <= $highestRow; $row++)
{ 
    //  Read a row of data into an array
    $rowDat = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);

		$customer_details=$rowDat[0];
		$file_id = getFileIdFromAgreementNo($customer_details[0]);
		$customer_name = $customer_details[1];
		
		if(is_numeric($file_id))
		{
			$sql="UPDATE fin_customer SET customer_name = '$customer_name' WHERE file_id = $file_id";
			dbQuery($sql);
			$updated++;
		}
		else
		$not_updated++;
		
	
	
}
	echo $updated." ".$not_updated;
	
	
?>