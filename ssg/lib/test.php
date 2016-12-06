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
$inputFileName = '31_march_rasid.csv';

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

//  Loop through each row of the worksheet in turn
for ($row = 1,$file_counter=0; $row <= $highestRow; $row++){ 
    //  Read a row of data into an array
    $rowDat = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
/*	$file_number=$rowData[0][0];
	$agreement_no=$rowData[0][1];
	if($agreement_no!="" && $file_number!="")
	{
	$file_number=stripFileNo($file_number);
	$agreement_no=substr_replace($agreement_no,"O",1,1);
	$sql="UPDATE fin_file SET file_agreement_no='$agreement_no' WHERE file_number='$file_number'";	
	dbQuery($sql);		
		
	} */
    //  Insert row data array into your database of choice here
	$rowData[]=$rowDat[0];
	print_r($rowData);
	
	//print_r($rowDat[0]);
	//echo "<br>";
	
}
?>