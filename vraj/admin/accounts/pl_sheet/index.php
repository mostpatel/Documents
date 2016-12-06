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
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/vehicle-functions.php";

if(isset($_SESSION['edmsAdminSession']['admin_rights']))
$admin_rights=$_SESSION['edmsAdminSession']['admin_rights'];

if(isset($_GET['view']))
{
	if($_GET['view']=='monthView')
	{
		$content="list_add.php";
	}
	else if($_GET['view']=='second')
	{
		$content="second.php";
	}
	else if($_GET['view']=='third')
	{
		$content="third.php";
	}
	else if($_GET['view']=='fourth')
	{
		$content="fourth.php";
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
		$content="list_add_.php";
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
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$period = getPeriodForUser($admin_id);
		
	
		
		$from=date('d/m/Y',strtotime($period[0]));
		$to=date('d/m/Y',strtotime($period[1]));
		
		$reportArray=getFirstPageBalanceSheet($to);
		
		
		$_SESSION['bl_one']['entries_array']=$reportArray;
		$_SESSION['bl_one']['from']=$from;
		$_SESSION['bl_one']['to']=$to;
		header("Location: index.php");		
		exit;
	}			
	else if($_GET['action']=='pl_sheet')
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$period = getPeriodForUser($admin_id);
		
	
		
		$from=date('d/m/Y',strtotime($period[0]));
		$to=date('d/m/Y',strtotime($period[1]));
		
		$reportArray=getProfitAndLossSheet($to);
		
		
		$_SESSION['bl_one']['entries_array']=$reportArray;
		$_SESSION['bl_one']['from']=$from;
		$_SESSION['bl_one']['to']=$to;
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