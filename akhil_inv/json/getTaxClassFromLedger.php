<?php
require_once "../lib/cg.php";
require_once "../lib/bd.php";
require_once "../lib/account-ledger-functions.php";
require_once "../lib/tax-functions.php";


if(isset($_GET['id'])){
$id=$_GET['id'];
$result=array();
$result=getTaxClassByLedgerId($id);
$str="";
foreach($result as $branch){
$str=$str . "\"$branch[tax_class_id]\"".",". "\"$branch[tax_class]\"".",";
}
$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
echo "new Array($str)";

}
?>