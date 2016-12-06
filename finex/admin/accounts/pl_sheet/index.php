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
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/vehicle-functions.php";

if(isset($_SESSION['adminSession']['admin_rights']))
$admin_rights=$_SESSION['adminSession']['admin_rights'];

if(isset($_GET['view']))
{
	if($_GET['view']=='monthView')
	{
		$current_company=getCurrentCompanyForUser($_SESSION['adminSession']['admin_id']);
		
		$company_heading = $current_company[2];
		$content="list_add.php";
	}
	else if($_GET['view']=='second')
	{
		$current_company=getCurrentCompanyForUser($_SESSION['adminSession']['admin_id']);
		
		$company_heading = $current_company[2];
		$content="second.php";
	}
	else if($_GET['view']=='third')
	{
		$current_company=getCurrentCompanyForUser($_SESSION['adminSession']['admin_id']);
		
		$company_heading = $current_company[2];
		$content="third.php";
	}
	else if($_GET['view']=='fourth')
	{
		$current_company=getCurrentCompanyForUser($_SESSION['adminSession']['admin_id']);
		
		$company_heading = $current_company[2];
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
		$current_company=getCurrentCompanyForUser($_SESSION['adminSession']['admin_id']);
		
		$company_heading = $current_company[2];
		$content="list_add_.php";
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
		$admin_id=$_SESSION['adminSession']['admin_id'];
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
		$admin_id=$_SESSION['adminSession']['admin_id'];
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