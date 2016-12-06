<?php
ini_set('memory_limit', '-1');
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('inventory-functions.php');
require_once('godown-functions.php');
require_once('item-manufacturer-functions.php');
require_once('inventory-item-functions.php');
require_once('account-ledger-functions.php');
require_once('account-head-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

function processExcel($file)
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
$non_inserted_item_rows = array();
$sql_main = "START TRANSACTION;";
//  Loop through each row of the worksheet in turn

for ($row = 2; $row <= $highestRow; $row++){ 
   //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
						

	foreach($rowData as $cell_value)
	{
		$item_name = $cell_value[0];
		$alias = $cell_value[1];
		$sku = $cell_value[2];
		$secondary_sku = $cell_value[3];
		$supplier = $cell_value[4];
		$manufacturer = $cell_value[5];
		$upc = $cell_value[6];
		$item_shelf = $cell_value[7];
		$opening_qty = $cell_value[8];
		$opening_rate = $cell_value[9];
		$item_name =clean_data($item_name);
		$manufacturer = clean_data($manufacturer);
		$supplier = clean_data($supplier);
		$alias = clean_data($alias);
		$sku = clean_data($sku);
		$upc = clean_data($upc);
		
		if(!checkForNumeric($opening_qty))
		$opening_qty = 0;
		if(!checkForNumeric($opening_rate))
		$opening_rate = 0;
		if(!validateForNull($item_shelf))
		$shelf_id=-1;
		else
		$shelf_id = insertGodownIfNotDuplicate($item_shelf);
		$secondary_sku =explode(',',$secondary_sku);
		$manufacturer_id = insertItemManufacturerIFNotDuplicate($manufacturer);
		
		$supplier_id=checkforDuplicateLedger($supplier);
		
		if(!checkForNumeric($supplier_id))
		$supplier_id=insertLedger($supplier,$_POST["postal_name"], $_POST["address"], -1,"NA",  $_POST["pincode"], getSundryCreditorsId(), $_POST["contactNo"],$_POST["pan_no"],$_POST["sales_no"],$_POST['notes'],$_POST['opening_balance'],$_POST['opening_balance_cd'],NULL,1);
		
		
		if(validateForNull($item_name,$sku,$upc) && checkForNumeric($manufacturer_id,$supplier_id))
		{
			$item_id = insertInventoryItem($item_name,$alias,$sku,4,$manufacturer_id,$upc,0,0,$opening_qty,$opening_rate,'',2,0,$shelf_id,-1,0,'','','',0,$supplier_id,'01/01/1970',$secondary_sku);		
		}
		else if(validateForNull($upc))
		{
			$non_inserted_item_rows[] = $row;
		}
	}
	

	
}
	return $non_inserted_item_rows;
}

?>