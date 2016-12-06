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

function updateShelfAndQuantityExcel($file)
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
$non_removed_item_rows = array();
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
		
		$upc = $cell_value[0];
		$shelf = $cell_value[1];
		$quantity = $cell_value[2];
		
		$sku = $cell_value[3];
		$name = $cell_value[4];
		
		$upc = clean_data($upc);
		$sku = clean_data($sku);
		$name = clean_data($name);
		$shelf_id = insertGodownIfNotDuplicate($shelf,"","NULL",9999999999);
		$item_id = getItemIdFromUPC($upc);
		
		if(validateForNull($upc) && checkForNumeric($item_id))
		{
			if(checkForNumeric($quantity) && $quantity>=0)
			updateOpeningQuantityForItem($item_id,$quantity);
			if(checkForNumeric($shelf) && $shelf_id>0)
			updateShelfForInventoryItem($item_id,$shelf_id);
			if(validateForNull($name))
			$sql = "UPDATE edms_inventory_item SET item_name = '$name'  WHERE item_id = $item_id";
			dbQuery($sql);
			if(validateForNull($sku))
			$sql = "UPDATE edms_inventory_item SET item_code = '$sku' WHERE item_id = $item_id";
			dbQuery($sql);
			
		}
		else if(validateForNull($upc))
		{
			$non_inserted_item_rows[] = $upc;
		}
	}
	

	
}
	return $non_inserted_item_rows;
}

?>