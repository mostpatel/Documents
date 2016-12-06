<?php
ini_set('memory_limit', '-1');
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('inventory-functions.php');
require_once('item-manufacturer-functions.php');
require_once('inventory-item-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

$inputFileName = 'f.xlsx';

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
for ($row = 3; $row <= $highestRow; $row++){ 
   //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
									
	
	foreach($rowData as $cell_value)
	{
	//	$school_name = $cell_value[1];
		$product_name = $cell_value[1];
	//	$size = $cell_value[3];
		$opening_qty = $cell_value[2];
	//	$item_name = $school_name." ".$product_name." size:".$size;
		$item_name = $product_name;
		$item_name =clean_data($item_name);
		
		$sql="SELECT item_id, opening_quantity FROM edms_inventory_item WHERE item_name = '$item_name'";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$item_id = $resultArray[0][0];
			$old_opening_qty = $resultArray[0][1];
			$opening_qty = $old_opening_qty + $opening_qty;
			$sql="UPDATE edms_inventory_item SET opening_quantity=$opening_qty WHERE item_id = $item_id";
			$result=dbQuery($sql);
			echo "UPdate ".$item_id;
		}
		else
		if(validateForNull($item_name))
		{	
			echo insertInventoryItem($item_name,'',NULL,4,NULL,NULL,NULL,0,$opening_qty,0,'',2,0,NULL,NULL,0);		
		}
	echo " ".$item_name." ".$opening_qty;	
	echo " ".$row." <br>";	
	
	
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