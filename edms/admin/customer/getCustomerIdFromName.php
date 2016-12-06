<?php
require_once "../../lib/cg.php";
require_once "../../lib/bd.php";
require_once "../../lib/customer-functions.php";
require_once "../../lib/area-functions.php";

if(isset($_GET['name'])){
$name=$_GET['name'];
$result=array();
$customer=getCustomerIdFromCustomerName($name);
$str="";
if(is_numeric($customer))
{
	$str=$str . "\"$customer\"";
}
else if(is_array($customer))
{
foreach($customer as $branch){
$str=$str . "\"$branch[customer_id]\"".",";
}
$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
}
else
$str=$str . "\"0\"";
echo "new Array($str)";
}
?>