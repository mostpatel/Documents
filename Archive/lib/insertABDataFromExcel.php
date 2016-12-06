<?php
echo "Here";
exit;
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('customer-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

$inputFileName = 'city.xlsx';

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
	
	$city = $rowData[0][1];
	echo $city;
	exit;
	
	if($item_name!=""  && $mrp>=0 && $mfg_item_code!="")
	{
	
	$item_type_id=2;	
	$item_name=clean_data($item_name);	
	$mfg_id = insertItemManufacturerIFNotDuplicate($mfg);
	
	$item_id =getItemIdFromMfgCOde($mfg_item_code,$mfg_id);
	
	if(is_numeric($item_id))
	{
	$sql="UPDATE edms_inventory_item SET alias = '$item_name', opening_quantity = $openning_quantity,opening_rate = $opening_rate WHERE item_id = $item_id ";
//	dbQuery($sql);
	}
	else 
	{echo $row." ".$mfg_item_code." ".$item_name." ".$mrp." <br>";}
//	insertInventoryItem($item_name,'',NULL,NULL,$mfg_id,$mfg_item_code,NULL,$mrp,0,0,'',$item_type_id,0);		
	}
	else if($item_name!=""  && $mrp>=0)
	{
	$item_type_id=2;	
	$item_name=clean_data($item_name);	
	$mfg_id = insertItemManufacturerIFNotDuplicate($mfg);
	echo $item_name." ".$mfg_id." ".$mrp." ".$openning_quantity." ".$opening_rate." ".$item_type_id." <br>";
//insertInventoryItem($item_name,'',NULL,NULL,$mfg_id,"",NULL,$mrp,$openning_quantity,$opening_rate,'',$item_type_id,0);	
	}
    //  Insert row data array into your database of choice here
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