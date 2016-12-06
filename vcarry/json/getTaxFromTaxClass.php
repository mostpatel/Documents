<?php
require_once "../lib/cg.php";
require_once "../lib/bd.php";
require_once "../lib/account-ledger-functions.php";
require_once "../lib/tax-functions.php";


if(isset($_GET['id'])){
$id=$_GET['id'];
$result=array();
$result=getTaxGroupsForTaxClassId($id);
$str="";
foreach($result as $branch){
	
	if($branch['in_out']!=3)
	$tax_percent = getTotalTaxPercentForTaxGroup($branch['tax_group_id']);
	else
	$tax_percent="0";
	
	$el_id = "tax".$tax_percent;
	
	$str=$str . "\"$branch[tax_group_id]\"".",". "\"$branch[tax_group_name]\"".",". "\"$el_id\"".",";

}
$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
echo "new Array($str)";

}
?>