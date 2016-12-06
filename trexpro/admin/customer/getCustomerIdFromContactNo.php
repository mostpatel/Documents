<?php
require_once "../../lib/cg.php";
require_once "../../lib/bd.php";
require_once "../../lib/customer-functions.php";
require_once "../../lib/area-functions.php";

if(isset($_GET['no'])){
$contact_no=$_GET['no'];
$result=array();
$customer=getCustomerIdFromCustomerNo($contact_no);
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