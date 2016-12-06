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
processExcel();
function processExcel()
{
$inputFileName = "deleteSku.xlsx";

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
		
		$sku = $cell_value[1];
		
		$sku = clean_data($sku);
		$item_id=getItemIdFromSku($sku);
		echo $sku." ".$item_id." <br>";
		
		
		if(checkForNumeric($item_id))
		{
			 deleteInventoryItem($item_id);		
		}
	}
	

	
}
	return $non_inserted_item_rows;
}

?>