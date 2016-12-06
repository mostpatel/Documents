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
$result=getInventoryItemById($id);
if(is_numeric($result['opening_godown_id']))
echo $result['opening_godown_id'];
else
echo "-1";
}
?>