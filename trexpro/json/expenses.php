<?php require_once "../lib/cg.php";
require_once "../lib/bd.php";
require_once "../lib/customer-functions.php";
require_once "../lib/currencyToWords.php";
require_once "../lib/account-ledger-functions.php";
require_once "../lib/account-period-functions.php";

$ledgers=listExpenseLedgers($_REQUEST['term']);
foreach($ledgers as $ledger)
{	
	 $results[] = array('id'=>$ledger['ledger_id'],'label' => $ledger['ledger_name']." | [L".$ledger['ledger_id']."]");
}
echo json_encode($results); 	

 ?>