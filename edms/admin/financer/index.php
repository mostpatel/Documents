<?php
require_once "../../lib/cg.php";
require_once "../../lib/bd.php";
require_once "../../lib/common.php";
require_once "../../lib/financer-functions.php";
require_once "../../lib/adminuser-functions.php";
require_once "../../lib/our-company-function.php";
require_once "../../lib/customer-functions.php";
require_once "../../lib/account-ledger-functions.php";
require_once "../../lib/account-payment-functions.php";
require_once "../../lib/currencyToWords.php";

if(isset($_SESSION['edmsAdminSession']['admin_rights']))
$admin_rights=$_SESSION['edmsAdminSession']['admin_rights'];

if(isset($_GET['view']))
{
	if($_GET['view']=='list')
	{
		$content="list.php";
	}
	else if($_GET['view']=='closureDetails')
	{
		$showTitle=false;
		$content="closureDetails.php";
		}
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
		}	
	else if($_GET['view']=='closureQuote')
	{
		
		$content="closureQuote.php";
		$link="searchCustomer";
		}		
}
else
{
	$content="list_add.php";
	$link="searchCustomer";
	
	}
	
if(isset($_GET['action']))
{
	if($_GET['action']=='add')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))
			{
				$from_ledger=$_POST['from_ledger_id'];
			  if(substr($from_ledger, 0, 1) == 'L') // if the pament is done to a general account ledger
				{
				$from_ledger=str_replace('L','',$from_ledger);
				$from_ledger=intval($from_ledger);
				}
				$result=insertFinancerPayment($_POST['amount'],$_POST['payment_date'],$_POST['to_ledger_id'],$_POST['from_ledger_id'],$_POST['remarks'],$_POST['selectTR']);
				
				if($result)
				{
				$_SESSION['ack']['msg']="Payment successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				}
				
				header("Location: ".WEB_ROOT."admin/financer/index.php?&id=".$from_ledger);
				exit;
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".$_SERVER['PHP_SELF']);
			exit;
			}
		}
		
		if($_GET['action']=='edit')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,$admin_rights)))
			{
					$from_ledger=$_POST['from_ledger_id'];
			  if(substr($from_ledger, 0, 1) == 'L') // if the pament is done to a general account ledger
				{
				$from_ledger=str_replace('L','',$from_ledger);
				$from_ledger=intval($from_ledger);
				}
			    
				
				$result=updateFinancerPayment($_POST['payment_id'],$_POST['amount'],$_POST['payment_date'],$_POST['to_ledger_id'],$_POST['from_ledger_id'],$_POST['remarks'],$_POST['selectTR']);
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Financer Payment edited successfully!";
				$_SESSION['ack']['type']=1; // 1 for insert
				}
				else{
					
				$_SESSION['ack']['msg']="Unable to Edit Financer Payment details!";
				$_SESSION['ack']['type']=4; // 4 for error
				}
				
				header("Location: ".WEB_ROOT."admin/financer/index.php?view=list&id=".$from_ledger);
				exit;
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".$_SERVER['PHP_SELF']);
			exit;
			}
		}
		
	if($_GET['action']=='delete')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,$admin_rights)))
			{
			    
				$result=deleteFinancerPayment($_GET['id']);
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Financer Payment details deleted successfully!";
				$_SESSION['ack']['type']=1; // 1 for insert
				}
				else{
					
				$_SESSION['ack']['msg']="Unable to Delete Financer Payment details!";
				$_SESSION['ack']['type']=4; // 4 for error
				}
				
				header("Location: ".WEB_ROOT."admin/financer/index.php?view=list&id=".$_GET['financer_id']);
				exit;
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".$_SERVER['PHP_SELF']);
			exit;
			}
		}	
			
	}
?>

<?php

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="settings";
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","validators/closeFile.js","customerDatePicker.js","calculateTotalFinancer.js");
$cssArray=array("jquery-ui.css");
require_once "../../inc/template.php";
 ?>