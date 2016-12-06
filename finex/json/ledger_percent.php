<?php require_once "../lib/cg.php";
require_once "../lib/bd.php";
require_once "../lib/loan-functions.php";
require_once "../lib/file-functions.php";
require_once "../lib/customer-functions.php";
require_once "../lib/vehicle-functions.php";
require_once "../lib/currencyToWords.php";
require_once "../lib/account-ledger-functions.php";
require_once "../lib/account-period-functions.php";
$id=$_GET['id'];
if(substr($id, 0, 1) == 'L')
	{
		$ledger_id=str_replace('L','',$id);
		$ledger_id=intval($ledger_id);
		$ledgers=getInOutPercentForLedgerId($ledger_id);
		echo json_encode($ledgers);
		}
else
echo json_encode(array(0,0));	
 
 ?>