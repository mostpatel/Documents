<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/customer-functions.php";
require_once "../../../../lib/vehicle-functions.php";
require_once "../../../../lib/currencyToWords.php";
require_once "../../../../lib/account-ledger-functions.php";
require_once "../../../../lib/account-period-functions.php";
require_once "../../../../lib/account-debit-note-functions.php";
require_once "../../../../lib/inventory-item-functions.php";
require_once "../../../../lib/item-type-functions.php";
require_once "../../../../lib/item-unit-functions.php";
require_once "../../../../lib/tax-functions.php";
require_once "../../../../lib/godown-functions.php";
require_once "../../../../lib/item-manufacturer-functions.php";
require_once "../../../../lib/inventory-debit-note-functions.php";
require_once "../../../../lib/nonstock-debit-note-functions.php";


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
		$purchase_id = $_GET['id'];	
		$purchase=getDebitNoteById($purchase_id);
		
		if(!is_numeric($purchase['debit_note_id']))
		{
		if(count($_SESSION['back_links'])>1)
		{
		array_pop($_SESSION['back_links']);	
		header("Location: ".array_pop($_SESSION['back_links']));
		}
		else
		header("Location: ".WEB_ROOT);
		exit;	
		}
		
		$content="details.php";
		$showTitle=false; // to turn off the company title on the top of the page
		}
	else if($_GET['view']=='search')
	{
		$content="search.php";
		
		}	
	else if($_GET['view']=='edit')
	{
		
		$purchase_id = $_GET['id'];	
		$purchase=getDebitNoteById($purchase_id);
		
		if(!is_numeric($purchase['debit_note_id']))
		{
		if(count($_SESSION['back_links'])>1)
		{
		array_pop($_SESSION['back_links']);	
		header("Location: ".array_pop($_SESSION['back_links']));
		}
		else
		header("Location: ".WEB_ROOT);
		exit;	
		}
		
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
			$from_ledger_id=getCustomerLedgerIDFromLedgerNameLedgerId($_POST['from_ledger_id']);
				$result=insertInventoryNonStockItemDebitNote($_POST["item_id"],$_POST['rate'],$_POST['quantity'],$_POST['disc'],$_POST["ns_item_id"],$_POST['ns_rate'],$_POST['ns_disc'],$_POST['payment_date'],$_POST['delivery_date'],$_POST['to_ledger_id'],$from_ledger_id,$_POST['remarks'],$_POST['godown_id'],$_POST['tax_group_id'],$_POST['ns_tax_group_id'],$_POST['ref'],$_POST['ref_type'],$_POST['unit_id'],$_POST['sales_ledger_id'],$_POST['tax_class_id'],$_POST['ns_sales_ledger_id'],$_POST['ns_tax_class_id'],$_POST['form_no'],$_POST['form_date']); // $cheque_return is 0 when inserting a payment			
				
				if(is_numeric($result))
				{
					$_SESSION['ack']['msg']="Debit Note successfully added!";
					$_SESSION['ack']['type']=1; // 1 for insert
					header("Location: ".WEB_ROOT."admin/accounts/transactions/debit_note/index.php?view=details&id=".$result);
					exit;
				}
				else if($result=="date_error")
				{
				$_SESSION['ack']['msg']="Date Should be greater than Books starting date!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/debit_note/index.php");
				exit;
				}
				else
				{
				$_SESSION['ack']['msg']="Invalid Input!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/debit_note/index.php");
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/debit_note/index.php");
				exit;
			}
		}	
	if($_GET['action']=='delete')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$result=removeDebitNote($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Debit Note deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				header("Location: ".WEB_ROOT."admin/accounts/transactions/debit_note/index.php?view=details&id=".$_GET['lid']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					if(count($_SESSION['back_links'])>1)
				{
				    header("Location: ".array_pop($_SESSION['back_links']));
				}
				else
				{
					
					header("Location: ".WEB_ROOT."admin/accounts/transactions/debit_note/index.php");
				}
				exit;
			}
		}
	if($_GET['action']=='edit')
	{
		
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$from_ledger_id=getCustomerLedgerIDFromLedgerNameLedgerId($_POST['from_ledger_id']);
				$result=updateInventoryNonStockItemDebitNote($_POST['id'],$_POST["item_id"],$_POST['rate'],$_POST['quantity'],$_POST['disc'],$_POST["ns_item_id"],$_POST['ns_rate'],$_POST['ns_disc'],$_POST['payment_date'],$_POST['delivery_date'],$_POST['to_ledger_id'],$from_ledger_id,$_POST['remarks'],$_POST['godown_id'],$_POST['tax_group_id'],$_POST['ns_tax_group_id'],$_POST['ref'],$_POST['ref_type'],$_POST['unit_id'],$_POST['sales_ledger_id'],$_POST['tax_class_id'],$_POST['ns_sales_ledger_id'],$_POST['ns_tax_class_id'],$_POST['form_no'],$_POST['form_date']);
				if($result=="success")
				{	
			
				$_SESSION['ack']['msg']="Debit Note updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".WEB_ROOT."admin/accounts/transactions/debit_note/index.php?view=details&id=".$_POST['id']);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/debit_note/index.php?view=details&id=".$_POST['id']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/debit_note/index.php?view=details&id=".$_POST['id']);
				exit;
			}
			
	}
	
	}
?>

<?php

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="accounts";
if(isset($link))
$selectedLink=$link;
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","addInsuranceProof.js","customerDatePicker.js","validators/addSales.js","validators/addTrans.js","generateProductPurchase.js","getRateQuantityAndTaxForPurchase.js","getUnitsForItem.js");
$cssArray=array("jquery-ui.css");

require_once "../../../../inc/template.php";
 ?>