<?php
echo "Hi";
exit;
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('lead-functions.php');
require_once('customer-extra-details-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

$inputFileName = 'balaji.xlsx';
echo "hi";

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

echo "inside";
echo $highestRow;
echo $highestColumn;
exit;

//  Loop through each row of the worksheet in turn
for ($row = 1; $row <= $highestRow; $row++)
{ 
if($row == 1 || $row==2)
continue;
   //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
	print_r($rowData);
	exit;
	
	$enquiry_date = $rowData[0][0];
	
	echo $enquiry_date;
	exit;
	
	$customer_address_line1 = $rowData[0][1];
	$customer_address_line2 = $rowData[0][2];
	$customer_address_line3 = $rowData[0][3];
	
	$customer_phone = $rowData[0][4];
	$customer_mobile = $rowData[0][5];
	
    echo $customer_name;
	echo $final_address = $customer_address_line1. ", ".$customer_address_line2. ", ".$customer_address_line3;
	
	echo $customer_phone;
	echo $customer_mobile;
	$customer_phone_array = array();
	$customer_mobile_array = array();
	$customer_phone_array = explode(",",$customer_phone);
	$customer_mobile_array = explode(",",$customer_mobile);
	if(!is_array($customer_phone_array))
	$customer_phone_array = array();
	if(!is_array($customer_mobile_array))
	$customer_mobile_array = array();
	
	$contact_no_array = array_merge($customer_mobile_array,$customer_phone_array);
	print_r($contact_no_array);
	
	
	if(1==1)
	{
	
	$customer_id=insertCustomer($customer_name,"NA", $contact_no_array, 1);
	insertCustomerExtraDetails("", $final_address, "", -1, -1, -1, -1, $customer_id);
	
	}
	
    //  Insert row data array into your database of choice here
}



?>