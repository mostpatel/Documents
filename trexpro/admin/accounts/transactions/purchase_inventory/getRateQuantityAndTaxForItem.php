<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/inventory-functions.php";
require_once "../../../../lib/inventory-item-functions.php";
require_once "../../../../lib/item-type-functions.php";

if(isset($_GET['id']) && isset($_GET['state'])){
$id=$_GET['id'];
$godown_id = $_GET['state'];

$item = getInventoryItemById($id);
$item_type_id = $item['item_type_id'];
$item_type = getItemTypeById($item_type_id);
$inc_inventory = $item_type['inc_inventory'];

if($inc_inventory==1)
$remaing_quantity=getRemainingQuanityForItemForDate($id,$godown_id);
else
$remaing_quantity=1;

if($inc_inventory==1)
$mrp = $item['dealer_price'];
else
$mrp = $item['mrp'];
$tax_group_id = $item['tax_group_id'];

$str="";

$str=$str . "\"$remaing_quantity\"".",". "\"$mrp\"".",". "\"$tax_group_id\"".",". "\"$inc_inventory\"".",";

$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
echo "new Array($str)";

}
?>