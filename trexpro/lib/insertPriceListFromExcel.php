<?php
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('inventory-functions.php');
require_once('item-manufacturer-functions.php');
require_once('inventory-item-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

$inputFileName = 'sheet.xlsx';

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
	
	$item_name=$rowData[0][0];
	$item_type = $rowData[0][1];
	$mrp=round($rowData[0][5],2);
	$mfg = $rowData[0][3];
	
	if(!is_numeric($mrp))
	$mrp=0;
	if($company_code!="" && $item_name!="" && $mrp!="" && $mrp>0)
	{
	if($item_type=="L" || $item_type=="l")
	$item_type_id=1;
	else
	$item_type_id=2;	
	$item_name=clean_data($item_name);	
	$mfg_id = insertItemManufacturerIFNotDuplicate($mfg);
	insertInventoryItem($item_name,'',NULL,NULL,$mfg_id,NULL,NULL,$mrp,0,0,'',$item_type_id,0);		
	}
	
    //  Insert row data array into your database of choice here
}

 ?>