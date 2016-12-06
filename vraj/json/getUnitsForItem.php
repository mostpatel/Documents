<?php
require_once "../lib/cg.php";
require_once "../lib/bd.php";
require_once "../lib/account-ledger-functions.php";
require_once "../lib/tax-functions.php";
require_once '../lib/inventory-item-functions.php';
require_once '../lib/item-unit-functions.php';

if(isset($_GET['id'])){
$id=$_GET['id'];
$result=array();
$result=getUnitsForItemId($id);
if(empty($result))
{
	$item=getInventoryItemById($id);
	$item_unit = getItemUnitById($item['item_unit_id']);
	$result=array();
	$result[]=$item_unit;
}
$str="";
foreach($result as $branch){

	$str=$str . "\"$branch[item_unit_id]\"".",". "\"$branch[unit_name]\"".",";

}
$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
echo "new Array($str)";
}
?>