<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/account-ledger-functions.php";
require_once "../../../lib/ledgers-group-functions.php";
require_once "../../../lib/account-head-functions.php";
require_once "../../../lib/account-period-functions.php";
require_once "../../../lib/account-functions.php";
require_once "../../../lib/city-functions.php";
require_once "../../../lib/area-functions.php";
require_once "../../../lib/file-functions.php";
require_once "../../../lib/customer-functions.php";

require_once "../../../lib/vehicle-functions.php";
if(isset($_SESSION['adminSession']['admin_rights']))
$admin_rights=$_SESSION['adminSession']['admin_rights'];

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
		 $current_company=getCurrentCompanyForUser($_SESSION['adminSession']['admin_id']);
		
		$company_heading = $current_company[2];
		$content="list_add.php";
	}	
}
else
{
	 $current_company=getCurrentCompanyForUser($_SESSION['adminSession']['admin_id']);
		
		$company_heading = $current_company[2];
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
		$transaction_array=null;
		}
		else
		$transaction_array=null;
		
		$reportArray=getAllTransactionForLedgerGroupId(-4,$transaction_array,$from,$to);
		$_SESSION['ledgerGroupEntriess']['entries_array']=$reportArray;
		$_SESSION['ledgerGroupEntriess']['from']=$from;
		$_SESSION['ledgerGroupEntriess']['to']=$to;
		$_SESSION['ledgerGroupEntriess']['transaction_array']=$transaction_array;
		$_SESSION['ledgerGroupEntriess']['group_id']=$id;
		
		header("Location: index.php");		
		exit;
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