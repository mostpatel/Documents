<?php
ini_set('memory_limit', '-1');
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('inventory-functions.php');
require_once('item-manufacturer-functions.php');
require_once('inventory-item-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

$inputFileName = 'product_list.xlsx';

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
$sql_main = "START TRANSACTION;";
//  Loop through each row of the worksheet in turn
for ($row = 1; $row <= $highestRow; $row++){ 
   //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
									
	
	foreach($rowData as $cell_value)
	{
		$item_name = $cell_value[0];
		$manufacturer = $cell_value[1];
		$item_name =clean_data($item_name);
		$manufacturer = clean_data($manufacturer);
		$manufacturer_id = insertItemManufacturerIFNotDuplicate($manufacturer);
		if(validateForNull($item_name) && checkForNumeric($manufacturer_id))
		{
			
			echo insertInventoryItem($item_name,'',NULL,NULL,$manufacturer_id,NULL,NULL,0,0,0,'',2,0,NULL,NULL,0);		
		}
	echo " <br>";	
	}
	
/*	$item_name=$rowData[0][1];
	$item_name = clean_data($item_name);
	$mfg_item_code = $rowData[0][0];
	$mrp=round($rowData[0][3],2); */
	
//	$mfg = $rowData[0][2];
//	$openning_quantity = $rowData[0][4];
//	$openning_balance = $rowData[0][5];
//	$opening_rate = $openning_balance/$openning_quantity;
//	if(!is_numeric($mrp))
//	$mrp=0;
	
	
	if( $mfg_item_code!="")
	{
/*	if($row<=35160)	
	{
	$sql="UPDATE edms_tem_part_number SET item_name = '$item_name', mrp = $mrp WHERE part_number ='$mfg_item_code' ";
	dbQuery($sql);	
	}
	else */
	{
//	echo $row." <br>";
//	$sql="INSERT INTO edms_tem_part_number (part_number,item_name,mrp) VALUES ('$mfg_item_code','$item_name',$mrp)";
//	dbQuery($sql);
	}
	} 
	
	
}
	$sql_main=$sql_main."COMMIT;";	
?>