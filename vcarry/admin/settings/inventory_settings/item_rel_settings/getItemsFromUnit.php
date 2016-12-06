<?php
$omit_session_path = true;
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/item-unit-functions.php";
require_once "../../../../lib/inventory-item-functions.php";

if(isset($_GET['id'])){
$id=$_GET['id'];
$result=array();
$result=getItemsForBaseUnitId($id);
$str="";
foreach($result as $branch){
$str=$str . "\"$branch[item_id]\"".",". "\"$branch[item_name]\"".",";
}
$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
echo "new Array($str)";
}
?>