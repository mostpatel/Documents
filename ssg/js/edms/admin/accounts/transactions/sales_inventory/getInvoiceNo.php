<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/inventory-functions.php";
require_once "../../../../lib/our-company-function.php";
require_once "../../../../lib/inventory-item-functions.php";
require_once "../../../../lib/item-type-functions.php";
require_once "../../../../lib/account-ledger-functions.php";
require_once "../../../../lib/customer-functions.php";

if(isset($_GET['id']) && isset($_GET['state'])){
$full_ledger_name=$_GET['id'];
$invoice_type = $_GET['state'];

$to_ledger = getCustomerLedgerIDFromLedgerNameLedgerId($full_ledger_name);



if(substr($to_ledger, 0, 1) == 'L')
	{
		
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$led=getLedgerById($to_ledger);
		if($retail_tax==1 && validateForNull($led['sales_no']) && !($led['sales_no']==0 || $led['sales_no']=="NA"))
		{
		$invoice_no = getTaxInvoiceCounterForOCID($led['oc_id']);
		$inv_type = 1;
		}
		else 
		{
		$invoice_no = getInvoiceCounterForOCID($led['oc_id']);
		$inv_type = 0;
		}
		$to_customer="NULL";
		
				
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($to_customer);
		if($retail_tax==1 && validateForNull($customer['tin_no']) && !($customer['tin_no']==0 || $customer['tin_no']=="NA"))
		{
		$invoice_no = getTaxInvoiceCounterForOCID($led['oc_id']);
		$inv_type = 1;
		}
		else 
		{
		$invoice_no = getInvoiceCounterForOCID($led['oc_id']);
		$inv_type = 0;
		}
		$customer_id=$customer['customer_id'];
		
		$oc_id=getCompanyIdFromCustomerId($customer_id);
		
				
	
				
		
		}	


echo "new Array($invoice_no,$inv_type)";

}
?>