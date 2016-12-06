<?php require_once "../lib/cg.php";
require_once "../lib/bd.php";
require_once "../lib/customer-functions.php";
require_once "../lib/vehicle-functions.php";
require_once "../lib/currencyToWords.php";
require_once "../lib/customer-group-functions.php";
require_once "../lib/account-period-functions.php";

$ledgers=listCustomerGroups($_REQUEST['term']);
foreach($ledgers as $ledger)
{	
	 $results[] = array('id'=>$ledger['group_id'],'label' => $ledger['group_name']);
}
echo json_encode($results); 	

 ?>