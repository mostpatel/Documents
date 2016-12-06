<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/customer-functions.php";
require_once "../../../../lib/our-company-function.php";
require_once "../../../../lib/tax-functions.php";
require_once "../../../../lib/product-functions.php";
require_once "../../../../lib/packing-unit-functions.php";
require_once "../../../../lib/account-ledger-functions.php";
require_once "../../../../lib/branch-counter-function.php";
require_once "../../../../lib/report-functions.php";
require_once "../../../../lib/area-functions.php";
require_once "../../../../lib/lr-functions.php";
require_once "../../../../lib/trip-memo-functions.php";


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
		
		if(isset($_POST['from_branch_ledger_id']))
		{
		$from_branch_ledger_id=$_POST['from_branch_ledger_id'];
		}
		else
		$from_branch_ledger_id=null;
		
		if(isset($_POST['to_branch_ledger_id']))
		{
		$to_branch_ledger_id=$_POST['to_branch_ledger_id'];
		}
		else
		$to_branch_ledger_id=null;	
		
		
		
		if(isset($_POST['lr_type']))
		{
			
		$area_array=$_POST['lr_type'];
	
		}
		else
		$area_array=null;
		
		if(isset($_POST['untripped']))
		{
			
		$untripped=$_POST['untripped'];
	
		}
		else
		$untripped=null;
		
		
		
		
		
		$reportArray=generateDeleteLrReports($from,$to);
		$_SESSION['cEMIReport']['emi_array']=$reportArray;
		$_SESSION['cEMIReport']['from']=$from;
		$_SESSION['cEMIReport']['to']=$to;
		header("Location: index.php");		
		exit;
	}
	else if($_GET['action']=='fromHomeUpcoming')
	{
		if(isset($_POST['start_date']))
		{
		$from=$_POST['start_date'];
		}
		else
		$from=date('d/m/Y');
		
		if(isset($_POST['end_date']))
		{
		$to=$_POST['end_date'];
		}
		else
		{
	 $today=date('Y-m-d');
	$to = new DateTime(date('Y-m-d'));
	$to->add(new DateInterval('P25D'));
	$to=$to->format('d/m/Y');
		}
		
		if(isset($_POST['win_gt']))
		{
		
		$win_gt=$_POST['win_gt'];
		}
		else
		$win_gt=1;
		
		if(isset($_POST['win_lt']))
		{
		$win_lt=$_POST['win_lt'];
		}
		else
		$win_lt=null;
		
		if(isset($_POST['emi_gt']))
		{
		$emi_gt=$_POST['emi_gt'];
		}
		else
		$emi_gt=null;
		
		if(isset($_POST['emi_lt']))
		{
		$emi_lt=$_POST['emi_lt'];
		}
		else
		$emi_lt=null;
		
		if(isset($_POST['balance_gt']))
		{
		$balance_gt=$_POST['balance_gt'];
		}
		else
		$balance_gt=null;
		
		if(isset($_POST['balance_lt']))
		{
		$balance_lt=$_POST['balance_lt'];
		}
		else
		$balance_lt=null;
		
		if(isset($_POST['city_id']))
		{
		$city_id=$_POST['city_id'];
		}
		else
		$city_id=null;
		
		if(isset($_POST['area']))
		{
			
		$area_array=$_POST['area'];
		
		$area_id_string=implode(',',$area_array);
		}
		else
		$area_id=null;
		
		if(isset($_POST['$agency_id']))
		{
			
		$agency_id=$_POST['agency_id'];
		}
		else
		$agency_id=null;
		
		if(isset($_POST['file_status']))
		{
			
		$file_status=$_POST['file_status'];
		}
		else
		$file_status=null;
		
		if($area_id_string==false)
		$area_id_string=null;
		
		if($city_id==-1)
		$city_id=null;
		
		
		
		$reportArray=generalEMIReports($from,$to,$win_gt,$win_lt,$emi_gt,$emi_lt,$balance_gt,$balance_lt,$city_id,$area_id_string,$file_status,$agency_id);
		
		$_SESSION['cEMIReport']['emi_array']=$reportArray;
		
		$_SESSION['cEMIReport']['from']=$from;
		$_SESSION['cEMIReport']['to']=$to;
		$_SESSION['cEMIReport']['win_gt']=$win_gt;
		$_SESSION['cEMIReport']['win_lt']=$win_lt;
		$_SESSION['cEMIReport']['emi_gt']=$emi_gt;
		$_SESSION['cEMIReport']['emi_lt']=$emi_lt;
		$_SESSION['cEMIReport']['balance_gt']=$balance_gt;
		$_SESSION['cEMIReport']['balance_lt']=$balance_lt;
		$_SESSION['cEMIReport']['city_id']=$city_id;
		$_SESSION['cEMIReport']['area_id_array']=$area_array;
		$_SESSION['cEMIReport']['agency_id']=$agency_id;
		$_SESSION['cEMIReport']['file_status']=$file_status;
	
		header("Location: index.php");		
		exit;
	}
	else if($_GET['action']=='fromHomeExpiredOpen')
	{
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
		$to=date('d/m/Y');	
		
		if(isset($_POST['win_gt']))
		{
		$win_gt=$_POST['win_gt'];
		}
		else
		$win_gt=0.1;
		
		if(isset($_POST['win_lt']))
		{
		$win_lt=$_POST['win_lt'];
		}
		else
		$win_lt=null;
		
		if(isset($_POST['emi_gt']))
		{
		$emi_gt=$_POST['emi_gt'];
		}
		else
		$emi_gt=null;
		
		if(isset($_POST['emi_lt']))
		{
		$emi_lt=$_POST['emi_lt'];
		}
		else
		$emi_lt=null;
		
		if(isset($_POST['balance_gt']))
		{
		$balance_gt=$_POST['balance_gt'];
		}
		else
		$balance_gt=null;
		
		if(isset($_POST['balance_lt']))
		{
		$balance_lt=$_POST['balance_lt'];
		}
		else
		$balance_lt=null;
		
		if(isset($_POST['city_id']))
		{
		$city_id=$_POST['city_id'];
		}
		else
		$city_id=null;
		
		if(isset($_POST['area']))
		{
			
		$area_array=$_POST['area'];
		
		$area_id_string=implode(',',$area_array);
		}
		else
		$area_id=null;
		
		if(isset($_POST['$agency_id']))
		{
			
		$agency_id=$_POST['agency_id'];
		}
		else
		$agency_id=null;
		
		if(isset($_POST['file_status']))
		{
			
		$file_status=$_POST['file_status'];
		}
		else
		$file_status=1;
		
		if($area_id_string==false)
		$area_id_string=null;
		
		if($city_id==-1)
		$city_id=null;
		
		
		
		$reportArray=generalEMIReports($from,$to,$win_gt,$win_lt,$emi_gt,$emi_lt,$balance_gt,$balance_lt,$city_id,$area_id_string,$file_status,$agency_id);
		
		$_SESSION['cEMIReport']['emi_array']=$reportArray;
		
		$_SESSION['cEMIReport']['from']=$from;
		$_SESSION['cEMIReport']['to']=$to;
		$_SESSION['cEMIReport']['win_gt']=$win_gt;
		$_SESSION['cEMIReport']['win_lt']=$win_lt;
		$_SESSION['cEMIReport']['emi_gt']=$emi_gt;
		$_SESSION['cEMIReport']['emi_lt']=$emi_lt;
		$_SESSION['cEMIReport']['balance_gt']=$balance_gt;
		$_SESSION['cEMIReport']['balance_lt']=$balance_lt;
		$_SESSION['cEMIReport']['city_id']=$city_id;
		$_SESSION['cEMIReport']['area_id_array']=$area_array;
		$_SESSION['cEMIReport']['agency_id']=$agency_id;
		$_SESSION['cEMIReport']['file_status']=$file_status;
	
		header("Location: index.php");		
		exit;
	}
	else if($_GET['action']=='fromHomeExpiredClosed')
	{
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
		$to=date('d/m/Y');	
		
		if(isset($_POST['win_gt']))
		{
		$win_gt=$_POST['win_gt'];
		}
		else
		$win_gt=0.1;
		
		if(isset($_POST['win_lt']))
		{
		$win_lt=$_POST['win_lt'];
		}
		else
		$win_lt=null;
		
		if(isset($_POST['emi_gt']))
		{
		$emi_gt=$_POST['emi_gt'];
		}
		else
		$emi_gt=null;
		
		if(isset($_POST['emi_lt']))
		{
		$emi_lt=$_POST['emi_lt'];
		}
		else
		$emi_lt=null;
		
		if(isset($_POST['balance_gt']))
		{
		$balance_gt=$_POST['balance_gt'];
		}
		else
		$balance_gt=null;
		
		if(isset($_POST['balance_lt']))
		{
		$balance_lt=$_POST['balance_lt'];
		}
		else
		$balance_lt=null;
		
		if(isset($_POST['city_id']))
		{
		$city_id=$_POST['city_id'];
		}
		else
		$city_id=null;
		
		if(isset($_POST['area']))
		{
			
		$area_array=$_POST['area'];
		
		$area_id_string=implode(',',$area_array);
		}
		else
		$area_id=null;
		
		if(isset($_POST['$agency_id']))
		{
			
		$agency_id=$_POST['agency_id'];
		}
		else
		$agency_id=null;
		
		if(isset($_POST['file_status']))
		{
			
		$file_status=$_POST['file_status'];
		}
		else
		$file_status=2;
		
		if($area_id_string==false)
		$area_id_string=null;
		
		if($city_id==-1)
		$city_id=null;
		
		
		
		$reportArray=generalEMIReports($from,$to,$win_gt,$win_lt,$emi_gt,$emi_lt,$balance_gt,$balance_lt,$city_id,$area_id_string,$file_status,$agency_id);
		
		$_SESSION['cEMIReport']['emi_array']=$reportArray;
		
		$_SESSION['cEMIReport']['from']=$from;
		$_SESSION['cEMIReport']['to']=$to;
		$_SESSION['cEMIReport']['win_gt']=$win_gt;
		$_SESSION['cEMIReport']['win_lt']=$win_lt;
		$_SESSION['cEMIReport']['emi_gt']=$emi_gt;
		$_SESSION['cEMIReport']['emi_lt']=$emi_lt;
		$_SESSION['cEMIReport']['balance_gt']=$balance_gt;
		$_SESSION['cEMIReport']['balance_lt']=$balance_lt;
		$_SESSION['cEMIReport']['city_id']=$city_id;
		$_SESSION['cEMIReport']['area_id_array']=$area_array;
		$_SESSION['cEMIReport']['agency_id']=$agency_id;
		$_SESSION['cEMIReport']['file_status']=$file_status;
	
		header("Location: index.php");		
		exit;
	}
}
?>

<?php

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="reports";
$jsArray=array("jquery.validate.js","dropDown.js","jquery-ui/js/jquery-ui.min.js","validators/generalEMIReports.js","customerDatePicker.js");
$cssArray=array("jquery-ui.css");
require_once "../../../../inc/template.php";
 ?>