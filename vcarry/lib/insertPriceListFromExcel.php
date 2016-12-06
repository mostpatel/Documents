<?php
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('inventory-functions.php');
require_once('item-manufacturer-functions.php');
require_once('inventory-item-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

$inputFileName = 'greaves.xls';

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
for ($row = 3; $row <= $highestRow; $row++){ 
   //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
	
//	$item_name=$rowData[0][2];
	$mfg_item_code = $rowData[0][0];
	
//	$mrp=round($rowData[0][3],2);
//	$mfg = "Greaves Cotton";

//	if(!is_numeric($mrp))
	//$mrp=0;
	
	if(validateForNull($mfg_item_code))
	{
		$sql="SELECT * FROM edms_inventory_item WHERE mfg_item_code= '$mfg_item_code' ";
	$result=dbQuery($sql);	
	if(dbNumRows($result)==0)
	{
		echo $mfg_item_code." <br>";
			}
	else
	{
		$sql="UPDATE edms_tem_part_number_2 SET useful=1 WHERE  part_number= '$mfg_item_code' ";
//		dbQuery($sql);
	}
	}
	else if($item_name!=""  && $mrp>=0 && $mfg_item_code!="")
	{
	
	$item_type_id=2;	
	$item_name=clean_data($item_name);	
	$mfg_id = insertItemManufacturerIFNotDuplicate($mfg);
	
	$item_id =getItemIdFromMfgCOde($mfg_item_code,$mfg_id);
	
	if(is_numeric($item_id))
	{
	$sql="SELECT * FROM edms_tem_part_number_2 WHERE part_number= '$mfg_item_code' ";
	$result=dbQuery($sql);	
	if(dbNumRows($result)==0)
	{	
	$sql="INSERT INTO edms_tem_part_number_2(part_number,item_name,mrp,useful,for_vehicle) VALUES('$mfg_item_code','$item_name',$mrp,0,'')";
	dbQuery($sql);	
	
	}
//	$sql="UPDATE edms_inventory_item SET mrp = $mrp WHERE item_id = $item_id ";
//	dbQuery($sql);
	}
	else 
	{
	//echo $item_name." ".$mfg_id." ".$mrp." ".$openning_quantity." ".$opening_rate." ".$item_type_id." here <br>";	
	
//	insertInventoryItem($item_name,'',NULL,NULL,$mfg_id,$mfg_item_code,NULL,$mrp,0,0,'',$item_type_id,0);
	//$sql="INSERT INTO edms_tem_part_number_2(part_number,item_name,mrp,useful,for_vehicle) VALUES('$mfg_item_code','$item_name',$mrp,0,'')";
	//dbQuery($sql);
	}
	}
	else if($item_name!=""  && $mrp>=0)
	{
	$item_type_id=2;	
	$item_name=clean_data($item_name);	
	$mfg_id = insertItemManufacturerIFNotDuplicate($mfg);
	echo $item_name." ".$mfg_id." ".$mrp." ".$openning_quantity." ".$opening_rate." ".$item_type_id." <br>";
//    insertInventoryItem($item_name,'',NULL,NULL,$mfg_id,"",NULL,$mrp,$openning_quantity,$opening_rate,'',$item_type_id,0);	
	}
    //  Insert row data array into your database of choice here
}
?>