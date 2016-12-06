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


function processExcel($file_id,$file)
{
	
$inputFileName = $file;
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


$loan_id = getLoanIdFromFileId($file_id);
//  Loop through each row of the worksheet in turn
for ($row = 1,$file_counter=0; $row <= $highestRow; $row++){ 
    //  Read a row of data into an array
    $rowDat = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
$amount=$rowDat[0][0];
$payment_date = $rowDat[0][1];
$rasid_no = $rowDat[0][2];

$loan_emi_id = getOldestUnPaidEmi($loan_id);
if(checkForNumeric($amount,$rasid_no))
{
if(checkForNumeric($payment_date))
$payment_date=convertExcelSerialNumberToDate($payment_date);
insertPayment($loan_emi_id,$amount,1,$payment_date,$rasid_no,"",'01/01/1970');
}
}
return "success";
}
function getCityNamesString()
{
	$sql="SELECT city_name FROM fin_city WHERE city_name !='Na'";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	$city_name_array = array();
	foreach($resultArray as $re)
	{
		$city_name_array[] = strtoupper($re[0]);
	}
	return implode("|",$city_name_array);
}

function convertExcelSerialNumberToDate($serial_no)
{
	if(checkForNumeric($serial_no))
	{
		$loan_approval_date_excel_serial_no = $serial_no; // number of days from 01/01/1900
		
		 $date_01_01_1970 = date("Y-m-d",mktime(0,0,0,1,1,1970));
 		
		 $days_between_1900_1970 = 25569;
		
		 $days_from_1970 = $loan_approval_date_excel_serial_no - $days_between_1900_1970;
		 
		  $loan_approval_date_add_days = $date_01_01_1970.' + '.$days_from_1970.' Days';
		
		$loan_approval_date = date("d/m/Y",strtotime($loan_approval_date_add_days));
		return $loan_approval_date;
	}
}
?>