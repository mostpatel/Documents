<?php
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('inventory-functions.php');
require_once('item-manufacturer-functions.php');
require_once('inventory-item-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

$inputFileName = 'atul_stock.xlsx';

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
for ($row = 1; $row <= $highestRow; $row++){ 
   //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
	
	$item_name=$rowData[0][1];
	$mfg_item_code = $rowData[0][0];
	$mrp=round($rowData[0][3],2);
	$mfg = $rowData[0][2];
	$openning_quantity = $rowData[0][4];
	$openning_balance = $rowData[0][5];
	$opening_rate = $openning_balance/$openning_quantity;
	if(!is_numeric($mrp))
	$mrp=0;
	
	
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
?>