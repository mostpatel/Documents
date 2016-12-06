<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/common.php";
require_once "../../../lib/account-ledger-functions.php";
require_once "../../../lib/account-head-functions.php";
require_once "../../../lib/account-period-functions.php";
require_once "../../../lib/account-functions.php";
require_once "../../../lib/city-functions.php";
require_once "../../../lib/area-functions.php";
require_once "../../../lib/file-functions.php";
require_once "../../../lib/loan-functions.php";
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/vehicle-functions.php";
if(isset($_SESSION['adminSession']['admin_rights']))
$admin_rights=$_SESSION['adminSession']['admin_rights'];

if(isset($_GET['view']))
{
	if($_GET['view']=='monthView')
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
		$content="list_add_monthwise.php";
	}	
}
else
{
		$content="list_add_monthwise.php";
}		
if(isset($_GET['action']))
{
	if($_GET['action']=='generateReport')
	{
		$id=$_POST['ledger_id'];
		
		if(substr($id, 0, 1) === 'L')
		{
		$ledger_id=str_replace('L','',$id);
		$ledger_id=intval($ledger_id);
		$head_type=getLedgerHeadType($ledger_id);
		}
		else if(substr($id, 0, 1) === 'C')
		{
		$head_type=1;
		}	
		
		if(isset($_POST['start_date']) && validateForNull($_POST['start_date']))
		{
		$from=$_POST['start_date'];
		}
		else
		$from=date('d/m/Y',strtotime(getBooksStartingDateForLedgerCustomer($id)));
		
		if(isset($_POST['end_date']) && validateForNull($_POST['end_date']))
		{
		$to=$_POST['end_date'];
		}
		else
		$to=date('d/m/Y',strtotime(getTodaysDate()));	
		
		
		if(isset($_POST['transaction_type']))
		{
		$transaction_array=$_POST['transaction_type'];
		if(empty($transaction_array))
		$transaction_array=null;
		}
		else
		$transaction_array=null;
		
		
		$reportArray=getAllTransactionsForLedgerIdMonthWise($id,$transaction_array,$from,$to);
		
		
		$_SESSION['ledgerEntries']['entries_array']=$reportArray;
		$_SESSION['ledgerEntries']['from']=$from;
		$_SESSION['ledgerEntries']['to']=$to;
		$_SESSION['ledgerEntries']['transaction_array']=$transaction_array;
		$_SESSION['ledgerEntries']['ledger_id']=$id;
		$_SESSION['ledgerEntries']['head_type']=$head_type;
		header("Location: index.php");		
		exit;
	}			
	else if($_GET['action']=='listEntriesForMonth')
	{
		$id=$_GET['id'];
		if(isset($_GET['from']))
		{
		$from=$_GET['from'];
		}
		else
		$from=null;
		
		if(isset($_GET['to']))
		{
		$to=$_GET['to'];
		}
		else
		$to=null;	
		
		if(isset($_GET['month']))
		{
		$month=$_GET['month'];
		}
		else
		$month=null;	
		
		if(isset($_GET['year']))
		{
		$year=$_GET['year'];
		}
		else
		$year=null;	
		
		
		if(isset($from) && validateForNull($from))
{
	    $from_mysql = str_replace('/', '-', $from);
		$from_mysql=date('Y-m-d',strtotime($from_mysql));
	}
		
		
		if(date('m',strtotime($from_mysql))==$month && date('Y',strtotime($from_mysql))==$year)
		$from=$from;
		else
		{	
		$from='01/'.$month.'/'.$year;
		}
		
		
		
		if(isset($_POST['transaction_type']))
		{
		$transaction_array=$_POST['transaction_type'];
		if(empty($transaction_array))
		$transaction_array=null;
		}
		else
		$transaction_array=null;
		
		
		
		
		$reportArray=getAllTransactionsForLedgerIdForMonth($id,$month,$year,$transaction_array,$from,$to);
		
				
		$_SESSION['ledgerEntriesMonth']['entries_array']=$reportArray;
		$_SESSION['ledgerEntriesMonth']['from']=$from;
		$_SESSION['ledgerEntriesMonth']['to']=$to;
		$_SESSION['ledgerEntriesMonth']['transaction_array']=$transaction_array;
		$_SESSION['ledgerEntriesMonth']['ledger_id']=$id;
		header("Location: index.php?view=monthView");		
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