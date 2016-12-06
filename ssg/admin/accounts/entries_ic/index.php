<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/account-ledger-functions.php";
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
		{
		header("Location: index.php");		
		exit;
		}
		
		if(isset($_POST['debit_interest']))
		{
		$debit_interest=$_POST['debit_interest'];
		}
		else
		$debit_interest=null;
		
		if(isset($_POST['credit_interest']))
		{
		$credit_interest=$_POST['credit_interest'];
		}
		else
		$credit_interest=null;	
		
		if(isset($_POST['tds']))
		{
		$tds_rate=$_POST['tds'];
		}
		else
		$tds_rate=null;
		
		if(isset($_POST['tds_on_amount']))
		{
		$tds_amount=$_POST['tds_on_amount'];
		}
		else
		$tds_amount=null;	
		
		
		
		if(isset($_POST['transaction_type']))
		{
		$transaction_array=$_POST['transaction_type'];
		if(empty($transaction_array))
		$transaction_array=null;
		}
		else
		$transaction_array=null;
		
		
	
		$reportArray=getAllTransactionsForLedgerId($id,$transaction_array,$from,$to);
		$_SESSION['ledgerICEntriess']['entries_array']=$reportArray;
		$_SESSION['ledgerICEntriess']['from']=$from;
		$_SESSION['ledgerICEntriess']['to']=$to;
		$_SESSION['ledgerICEntriess']['transaction_array']=$transaction_array;
		$_SESSION['ledgerICEntriess']['ledger_id']=$id;
		$_SESSION['ledgerICEntriess']['debit_ic']=$debit_interest;
		$_SESSION['ledgerICEntriess']['credit_ic']=$credit_interest;
		$_SESSION['ledgerICEntriess']['tds']=$tds_rate;
		$_SESSION['ledgerICEntriess']['tds_on_amount']=$tds_amount;
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