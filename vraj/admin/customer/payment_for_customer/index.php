<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/vehicle-functions.php";
require_once "../../../lib/currencyToWords.php";
require_once "../../../lib/account-ledger-functions.php";
require_once "../../../lib/account-period-functions.php";
require_once "../../../lib/account-payment-functions.php";
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
			
				$result=addPaymentForCustomer($_POST["amount"],$_POST['payment_date'],$_POST['to_ledger_id'],$_POST['from_ledger_id'],$_POST['remarks'],$_POST['customer_id'],$_POST['auto_rasid_type']); // $cheque_return is 0 when inserting a payment			
				
				if(is_numeric($result))
				{
					$_SESSION['ack']['msg']="Payment successfully added!";
					$_SESSION['ack']['type']=1; // 1 for insert
					header("Location: ".WEB_ROOT."admin/customer/payment_for_customer/index.php?id=".$_POST['customer_id']);
					exit;
				}
				else if($result=="date_error")
				{
				$_SESSION['ack']['msg']="Date Should be greater than Books starting date!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/payment_for_customer/index.php");
				exit;
				}
				else
				{
				$_SESSION['ack']['msg']="Invalid Input!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/payment_for_customer/index.php");
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/payment_for_customer/index.php");
				exit;
			}
		}	
	if($_GET['action']=='delete')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$result=deletePaymentForCustomer($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Payment deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				header("Location: ".WEB_ROOT."admin/customer/payment_for_customer/index.php?id=".$_GET['customer_id']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/payment_for_customer/index.php");
				exit;
			}
		}
	if($_GET['action']=='edit')
	{
		
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				$result=updatePaymentForCustomer($_POST["lid"],$_POST["amount"],$_POST['payment_date'],$_POST['to_ledger_id'],$_POST['from_ledger_id'],$_POST['remarks'],$_POST['customer_id'],$_POST['auto_rasid_type']);
				if($result=="success")
				{	
				$_SESSION['ack']['msg']="Payment updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".WEB_ROOT."admin/customer/payment_for_customer/index.php?view=details&id=".$_POST['lid']);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/payment_for_customer/index.php?view=details&id=".$_POST['lid']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/payment_for_customer/index.php?view=details&id=".$_POST['lid']);
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
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","addInsuranceProof.js","customerDatePicker.js","validators/addReceipt_Payment.js","cScript.ashx","transliteration.I.js");
$cssArray=array("jquery-ui.css");

require_once "../../../inc/template.php";
 ?>