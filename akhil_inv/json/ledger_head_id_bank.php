<?php require_once "../lib/cg.php";
require_once "../lib/bd.php";
require_once "../lib/customer-functions.php";
require_once "../lib/vehicle-functions.php";
require_once "../lib/currencyToWords.php";
require_once "../lib/account-ledger-functions.php";
require_once "../lib/account-period-functions.php";
require_once "../lib/account-head-functions.php";

$ledger_id = $_GET['id'];	
$ledger = getLedgerById($ledger_id);
$ledger_head_id = $ledger['head_id'];
if($ledger_head_id==getBankAccountsHeadId())
echo 1;
else
echo 0;
 ?>