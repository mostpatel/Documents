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
require_once "../../../lib/receipt-type-functions.php";

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
		$content="list_add.php";
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
				
										
						$result=insertReceipt($_POST["amount"],$_POST['payment_date'],$_POST['to_ledger_id'],$_POST['from_ledger_id'],$_POST['remarks'],$_POST['auto_rasid_type'],$_POST['customer_id'],'NA',0,$_POST['oc_id'],$_POST['payment_mode_id'],$_POST['cheque_date'],$_POST['cheque_no'],$_POST['bank_name'],$_POST['branch_name']); // $cheque_return is 0 when inserting a payment			
				
							
					
					
				
				if($result=="success")
				{
					
					$_SESSION['ack']['msg']="Receipt successfully added!";
					$_SESSION['ack']['type']=1; // 1 for insert
					
					header("Location: ".WEB_ROOT."admin/customer/receipt/index.php?view=allReceipts&id=".$_POST["customer_id"]);
					exit;
				}
				else if($result=="date_error")
				{
				$_SESSION['ack']['msg']="Date Should be greater than Books starting date!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/receipt/index.php?view=allReceipts&id=".$_POST["customer_id"]);
				}
				else
				{
				$_SESSION['ack']['msg']="Invalid Input!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/receipt/index.php?view=index&id=".$_POST["customer_id"]);
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/payment/index.php");
				exit;
			}
		}	
	if($_GET['action']=='delete')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$result=deleteReceipt($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Receipt deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				header("Location: ".WEB_ROOT."admin/customer/receipt/index.php?view=allReceipts&id=".$_GET["customer_id"]);
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/payment/index.php");
				exit;
			}
		}
	if($_GET['action']=='edit')
	{
		
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				
				$result=updateReceipt($_POST["lid"],$_POST["amount"],$_POST['payment_date'],$_POST['to_ledger_id'],$_POST['from_ledger_id'],$_POST['remarks']);
				updateAutoRasidTypeForReceiptId($_POST['lid'],$_POST['auto_rasid_type']);
				if($result=="success")
				{	
				$_SESSION['ack']['msg']="Receipt updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".WEB_ROOT."admin/customer/receipt/index.php?view=allReceipts&id=".$_POST["customer_id"]);
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/receipt/index.php?view=allReceipts&id=".$_POST["customer_id"]);
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/receipt/index.php?view=allReceipts&id=".$_POST["customer_id"]);
			}
			
	}
	
	}
?>

<?php

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="accounts";
if(isset($link))
$selectedLink=$link;
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","addInsuranceProof.js","customerDatePicker.js","validators/addReceipt_Payment.js","cScript.ashx","transliteration.I.js");
$cssArray=array("jquery-ui.css");

require_once "../../../inc/template.php";
 ?>