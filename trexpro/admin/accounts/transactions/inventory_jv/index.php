<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/customer-functions.php";
require_once "../../../../lib/currencyToWords.php";
require_once "../../../../lib/account-ledger-functions.php";
require_once "../../../../lib/account-functions.php";
require_once "../../../../lib/account-period-functions.php";
require_once "../../../../lib/account-sales-functions.php";
require_once "../../../../lib/account-receipt-functions.php";
require_once "../../../../lib/inventory-item-functions.php";
require_once "../../../../lib/item-type-functions.php";
require_once "../../../../lib/item-manufacturer-functions.php";
require_once "../../../../lib/tax-functions.php";
require_once "../../../../lib/report-functions.php";
require_once "../../../../lib/inventory-jv-functions.php";

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
	else if($_GET['view']=="allReceipts")	
	{
		$content="AllReceiptDetails.php";
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
				
					
						
						$result=insertInventoryJV($_POST["item_id"],$_POST['rate'],$_POST['quantity'],$_POST["ns_item_id"],$_POST['ns_rate'],$_POST['ns_quantity'],$_POST['payment_date'],$_POST['remarks'],$_POST['godown_id'],$_POST['ns_godown_id']); // $cheque_return is 0 when inserting a payment			
				
							
					
					
				
				if(checkForNumeric($result))
				{
					
					$_SESSION['ack']['msg']="Inventory JV successfully added!";
					$_SESSION['ack']['type']=1; // 1 for insert
					
					header("Location: ".WEB_ROOT."admin/accounts/transactions/inventory_jv/index.php");
					exit;
				}
				else if($result=="date_error")
				{
				$_SESSION['ack']['msg']="Date Should be greater than Books starting date!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/inventory_jv/index.php");
				exit;
				}
				else
				{
				$_SESSION['ack']['msg']="Invalid Input!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/inventory_jv/index.php");
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/inventory_jv/index.php");
				exit;
			}
		}	
	if($_GET['action']=='delete')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$result=deleteInventoryJv($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Inventory JV deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				header("Location: ".WEB_ROOT."admin/accounts/transactions/inventory_jv/index.php");
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/inventory_jv/index.php");
				exit;
			}
		}
	if($_GET['action']=='edit')
	{
		
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				
				$result=updateInventoryNonStockItemJV($_POST["id"],$_POST["item_id"],$_POST['rate'],$_POST['quantity'],$_POST["ns_item_id"],$_POST['ns_rate'],$_POST['ns_quantity'],$_POST['payment_date'],$_POST['remarks'],$_POST['godown_id'],$_POST['ns_godown_id']);
				if($result=="success")
				{	
				$_SESSION['ack']['msg']="Inventory JV updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".WEB_ROOT."admin/accounts/transactions/inventory_jv/index.php?view=details&id=".$_POST['id']);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/inventory_jv/index.php?view=details&id=".$_POST['id']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/inventory_jv/index.php?view=details&id=".$_POST['lid']);
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
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","addInsuranceProof.js","customerDatePicker.js","validators/addReceipt_Payment.js","generateProductPurchase.js","getRateQuantityAndTaxForJV.js");
$cssArray=array("jquery-ui.css");

require_once "../../../../inc/template.php";
 ?>