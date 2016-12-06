<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/customer-functions.php";
require_once "../../../../lib/vehicle-functions.php";
require_once "../../../../lib/currencyToWords.php";
require_once "../../../../lib/account-ledger-functions.php";
require_once "../../../../lib/account-functions.php";
require_once "../../../../lib/account-period-functions.php";
require_once "../../../../lib/account-sales-functions.php";
require_once "../../../../lib/account-payment-functions.php";
require_once "../../../../lib/inventory-item-functions.php";
require_once "../../../../lib/item-type-functions.php";
require_once "../../../../lib/item-manufacturer-functions.php";
require_once "../../../../lib/tax-functions.php";
require_once "../../../../lib/invoice-counter-functions.php";
require_once "../../../../lib/report-functions.php";
require_once "../../../../lib/inventory-sales-functions.php";
require_once "../../../../lib/nonstock-sales-functions.php";

if(isset($_SESSION['edmsAdminSession']['admin_rights']))
$admin_rights=$_SESSION['edmsAdminSession']['admin_rights'];

if(isset($_GET['view']))
{
	if($_GET['view']=='add')
	{
		$content="list_add.php";
	}
	else if($_GET['view']=='details')
	{
		
		$content="details.php";
		$showTitle=false; // to turn off the company title on the top of the page
		}
	else if($_GET['view']=='search')
	{
		$content="search.php";
		
		}	
		else if($_GET['view']=='invoice')
	{
		$content="invoice.php";
		$showTitle=false; 
		}		
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
		}	
	else if($_GET['view']=='addMultiple')
	{
		$content="add_multiple.php";
		}			
	else
	{
		$content="list_add.php";
	}	
}
else
{
		$content="list_add.php";
}		
if(isset($_GET['action']))
{	
	if($_GET['action']=='add')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))
			{
				
					
						
						$result=insertInventoryNonStockSale($_POST["item_id"],$_POST['rate'],$_POST['quantity'],$_POST['disc'],$_POST["ns_item_id"],$_POST['ns_rate'],$_POST['ns_disc'],$_POST['payment_date'],$_POST['delivery_date'],$_POST['to_ledger_id'],$_POST['from_ledger_id'],$_POST['remarks'],$_POST['godown_id'],$_POST['tax_group_id'],$_POST['ns_tax_group_id'],$_POST['ref'],$_POST['ref_type'],$_POST['invoice_no'],$_POST['retail_tax'],$_POST['invoice_note'],$_POST['item_desc'],$_POST['ns_item_desc'],$_POST['delivery_note'], $_POST['terms_of_payment'], $_POST['supplier_ref_no'], $_POST['other_reference'], $_POST['buyers_order_no'], $_POST['order_date'], $_POST['despatch_doc_no'], $_POST['despatch_dated'], $_POST['despatched_through'], $_POST['destination'], $_POST['terms_of_delivery'],$_POST['oc_id'],$_POST['challan_id'],$_POST['consignee_address'],$_POST['unit_id'],$_POST['sales_ledger_id'],$_POST['tax_class_id'],$_POST['ns_sales_ledger_id'],$_POST['ns_tax_class_id']); // $cheque_return is 0 when inserting a payment			
				
							
					
					
				
				if(checkForNumeric($result))
				{
					if(checkForNumeric($_POST['kasar_amount'],$_POST['kasar_type']) && $_POST['kasar_amount']>0)
					{
						$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
						$kasar_ledger=getKasarLedgerIdForOC($current_company[0]);
						$from_ledger = $_POST['to_ledger_id']; 
						if(substr($from_ledger, 0, 1) == 'L') // if the pament is done to a general account ledger
						{
							$from_ledger=str_replace('L','',$from_ledger);
							$from_ledger=intval($from_ledger);
							
									
						}
						
						addPayment($_POST['kasar_amount'],$_POST['payment_date'],$from_ledger,'L'.$kasar_ledger,'Cash Sale Kasar Payment',5,$result);
					}
					$_SESSION['ack']['msg']="Sales successfully added!";
					$_SESSION['ack']['type']=1; // 1 for insert
					
					header("Location: ".WEB_ROOT."admin/accounts/transactions/cash_sale/index.php");
					exit;
				}
				else if($result=="date_error")
				{
				$_SESSION['ack']['msg']="Date Should be greater than Books starting date!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/cash_sale/index.php");
				exit;
				}
				else
				{
				$_SESSION['ack']['msg']="Invalid Input!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/cash_sale/index.php");
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/cash_sale/index.php");
				exit;
			}
		}	
	if($_GET['action']=='delete')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$result=deleteSale($_GET["lid"]);
				if($result=="success")
				{
				$kasar_payment=getKasarPaymentForCashSale($_GET['lid']);
				if($kasar_payment!=false)
				{
					removePayment($kasar_payment['payment_id']);
				}
				$_SESSION['ack']['msg']="Sales deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				header("Location: ".WEB_ROOT."admin/accounts/transactions/cash_sale/index.php");
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/cash_sale/index.php");
				exit;
			}
		}
	if($_GET['action']=='edit')
	{
		
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				
				$result=updateInventoryNonStockItemSale($_POST["id"],$_POST["item_id"],$_POST['rate'],$_POST['quantity'],$_POST['disc'],$_POST["ns_item_id"],$_POST['ns_rate'],$_POST['ns_disc'],$_POST['payment_date'],$_POST['delivery_date'],$_POST['to_ledger_id'],$_POST['from_ledger_id'],$_POST['remarks'],$_POST['godown_id'],$_POST['tax_group_id'],$_POST['ns_tax_group_id'],$_POST['ref'],$_POST['ref_type'],$_POST['retail_tax'],$_POST['invoice_note'],$_POST['item_desc'],$_POST['ns_item_desc'],$_POST['delivery_note'], $_POST['terms_of_payment'], $_POST['supplier_ref_no'], $_POST['other_reference'], $_POST['buyers_order_no'], $_POST['order_date'], $_POST['despatch_doc_no'], $_POST['despatch_dated'], $_POST['despatched_through'], $_POST['destination'], $_POST['terms_of_delivery'],$_POST['challan_id'],$_POST['consignee_address'],$_POST['unit_id'],$_POST['sales_ledger_id'],$_POST['tax_class_id'],$_POST['ns_sales_ledger_id'],$_POST['ns_tax_class_id']);
				if($result=="success")
				{	
				$kasar_payment=getKasarPaymentForCashSale($_POST['id']);
				if($kasar_payment!=false)
				{
					removePayment($kasar_payment['payment_id']);
				}
				if(checkForNumeric($_POST['kasar_amount'],$_POST['kasar_type']) && $_POST['kasar_amount']>0)
					{
						$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
						$kasar_ledger=getKasarLedgerIdForOC($current_company[0]);
						$from_ledger = $_POST['to_ledger_id']; 
						if(substr($from_ledger, 0, 1) == 'L') // if the pament is done to a general account ledger
						{
							$from_ledger=str_replace('L','',$from_ledger);
							$from_ledger=intval($from_ledger);
							
									
						}
						
						addPayment($_POST['kasar_amount'],$_POST['payment_date'],$from_ledger,'L'.$kasar_ledger,'Cash Sale Kasar Payment',5,$_POST['id']);
					}
				
				$_SESSION['ack']['msg']="Sales updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".WEB_ROOT."admin/accounts/transactions/cash_sale/index.php?view=details&id=".$_POST['id']);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/cash_sale/index.php?view=details&id=".$_POST['id']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/cash_sale/index.php?view=details&id=".$_POST['lid']);
				exit;
			}
			
	}
	
	}
?>

<?php

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="cash_sale";
if(isset($link))
$selectedLink=$link;
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","addInsuranceProof.js","customerDatePicker.js","validators/addReceipt_Payment.js","generateProductPurchase.js","getRateQuantityAndTaxForSales.js","cScript.ashx","transliteration.I.js","getUnitsForItem.js");
$cssArray=array("jquery-ui.css");

require_once "../../../../inc/template.php";
 ?>