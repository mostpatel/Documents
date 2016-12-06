<?php
ini_set('memory_limit', '-1');
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('inventory-functions.php');
require_once('item-manufacturer-functions.php');
require_once('inventory-item-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

$sql="SELECT * FROM edms_tem_part_number_2 WHERE useful=1";
$result = dbQuery($sql);
$resultArray = dbResultToArray($result);
foreach($resultArray as $item){

	$item_name = $item['item_name'];
	$mfg_id = 4;
	$mfg_item_code = $item['part_number'];
	$mrp = $item['mrp'];
	$item_type_id = 2;
	
	insertInventoryItem($item_name,'',NULL,NULL,$mfg_id,$mfg_item_code,NULL,$mrp,0,0,'',$item_type_id,0);
	
}
?>