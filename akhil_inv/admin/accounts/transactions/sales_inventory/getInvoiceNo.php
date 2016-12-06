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
require_once "../../../../lib/invoice-counter-functions.php";

if(isset($_GET['id']) && isset($_GET['state'])){
	
$full_ledger_name=$_GET['id'];
$invoice_type = $_GET['state'];

$to_ledger = getCustomerLedgerIDFromLedgerNameLedgerId($full_ledger_name);

if(substr($to_ledger, 0, 1) == 'L')
	{
		
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$led=getLedgerById($to_ledger);
	
		if((validateForNull($led['sales_no']) && !($led['sales_no']==0 || $led['sales_no']=="NA")) || INVOICE_TYPE_SHOW_ALWAYS==1)
		{
		$invoice_no = getInvoiceCounterForOCID($invoice_type,$led['oc_id']);
		}
		else 
		{
		$invoice_type = getRetailInvoiceTypeForOcId($led['oc_id']);
		$invoice_no= $invoice_type['retail_counter'];
		}
		$to_customer="NULL";		
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($to_customer);
		if(CUSTOMER_MULTI_COMPANY==1)
		{
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			
			$curent_companny = getCurrentCompanyForUser($admin_id);
			
			$oc_id = $curent_companny[0];
			}
		else
		$oc_id = $customer['oc_id'];	
	
		if((validateForNull($customer['tin_no']) && !($customer['tin_no']==0 || $customer['tin_no']=="NA")) || INVOICE_TYPE_SHOW_ALWAYS==1)
		{
		$invoice_no = getInvoiceCounterForOCID($invoice_type,$oc_id);
		}
		else 
		{
		$invoice_type = getRetailInvoiceTypeForOcId($oc_id);
		$invoice_no= $invoice_type['retail_counter'];
		}
		$customer_id=$customer['customer_id'];
		$oc_id=getCompanyIdFromCustomerId($customer_id);

		}	


echo "new Array($invoice_no,$invoice_type)";

}
?>