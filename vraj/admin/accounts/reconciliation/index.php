<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/account-ledger-functions.php";
require_once "../../../lib/account-head-functions.php";
require_once "../../../lib/account-period-functions.php";
require_once "../../../lib/bank-reconciliation-functions.php";
require_once "../../../lib/account-functions.php";
require_once "../../../lib/account-payment-functions.php";
require_once "../../../lib/account-purchase-functions.php";
require_once "../../../lib/account-sales-functions.php";
require_once "../../../lib/account-receipt-functions.php";
require_once "../../../lib/account-jv-functions.php";
require_once "../../../lib/city-functions.php";
require_once "../../../lib/area-functions.php";
require_once("../../../lib/receipt-type-functions.php");
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/vehicle-functions.php";

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
		}
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
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
	if($_GET['action']=='generateReport')
	{
		$id=$_POST['ledger_id'];
		if(isset($_POST['start_date']))
		{
		$from=$_POST['start_date'];
		}
		else
		$from=null;
		
		if(isset($_POST['end_date']))
		{
		$to=$_POST['end_date'];
		}
		else
		$to=null;	
		
		
		
		if(isset($_POST['transaction_type']))
		{
		$transaction_array=$_POST['transaction_type'];
		if(empty($transaction_array))
		$transaction_array=array(1,2);
		}
		else
		$transaction_array=null;
		
		
		
		$reportArray=getAllTransactionsForLedgerIdOneQuery($id,$transaction_array,$from,$to);
		$_SESSION['reconciliationEntries']['entries_array']=$reportArray;
		$_SESSION['reconciliationEntries']['from']=$from;
		$_SESSION['reconciliationEntries']['to']=$to;
		$_SESSION['reconciliationEntries']['transaction_array']=$transaction_array;
		$_SESSION['reconciliationEntries']['ledger_id']=$id;
		
		header("Location: index.php");		
		exit;
	}
	
	if($_GET['action']=='add')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))
			{
				
					
						
						$result=updatePaymentReceiptArrayReconciliationDate($_POST["trans_id"],$_POST['reconciliation']); // $cheque_return is 0 when inserting a payment			
				
							
					
					
				
				if($result=="success")
				{
					
					$_SESSION['ack']['msg']="Reconciliation successfully added!";
					$_SESSION['ack']['type']=1; // 1 for insert
					
					header("Location: ".WEB_ROOT."admin/accounts/reconciliation/index.php");
					exit;
				}
				else if($result=="date_error")
				{
				$_SESSION['ack']['msg']="Date Should be greater than Books starting date!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/reconciliation/index.php");
				exit;
				}
				else
				{
				$_SESSION['ack']['msg']="Invalid Input!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/reconciliation/index.php");
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/reconciliation/index.php");
				exit;
			}
		}	
				
	}
?>

<?php
$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="accounts";
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","validators/ledger.js","customerDatePicker.js");
$cssArray=array("jquery-ui.css");
require_once "../../../inc/template.php";
 ?>