<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/vehicle-functions.php";
require_once "../../../lib/currencyToWords.php";
require_once "../../../lib/account-ledger-functions.php";
require_once "../../../lib/account-period-functions.php";
require_once "../../../lib/account-receipt-functions.php";
require_once "../../../lib/account-jv-functions.php";
require_once "../../../lib/account-purchase-functions.php";
require_once "../../../lib/vehicle-purchase-functions.php";

if(isset($_GET['id'])){
$vehicle_id=$_GET['id'];

$purchase_id=getPurchaseIdFromVehicleId($vehicle_id);
		if(!$purchase_id)
		{
		$vehicle = getVehicleById($vehicle_id);
		$from_ledger_id = $vehicle['extra_ledger_id'];
		}
		else if(checkForNumeric($purchase_id))
		{
		$purchase = getPurchaseById($purchase_id);
		$from_ledger_id = $purchase['from_ledger_id'];
		$from_customer_id = $purchase['from_customer_id'];	
		}

$customer_id=getCustomerIDFromVehicleId($vehicle_id);	
$customer = getCustomerDetailsByCustomerId($customer_id);
$customer_id = "C".$customer_id;
$str="\"$customer_id\"".",". "\"$customer[customer_name]\"";		
if(is_numeric($from_ledger_id))		
{
$ledger_name=getLedgerNameFromLedgerId($from_ledger_id);
$from_ledger_id = "L".$from_ledger_id;
$str=$str .","."\"$from_ledger_id\"".",". "\"$ledger_name\"";
}
else if(is_numeric($from_customer_id))
{
$from_customer =  getCustomerDetailsByCustomerId($from_customer_id);
$ledger_name = $from_customer['customer_name'];
$from_customer_id = "C".$from_customer_id;
$str=$str.","."\"$from_customer_id\"".",". "\"$ledger_name\"";

}

echo "new Array($str)";
}
?>