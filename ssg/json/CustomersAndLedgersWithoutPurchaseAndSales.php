<?php require_once "../lib/cg.php";
require_once "../lib/bd.php";
require_once "../lib/customer-functions.php";
require_once "../lib/vehicle-functions.php";
require_once "../lib/currencyToWords.php";
require_once "../lib/account-ledger-functions.php";
require_once "../lib/account-period-functions.php";

$ledgers=listCustomerAndLedgers($_REQUEST['term']);
foreach($ledgers as $ledger)
{	
	 $results[] = array('id'=>$ledger['id'],'label' => $ledger['name']." | [".$ledger['id']."]");
}
echo json_encode($results); 	

 ?>