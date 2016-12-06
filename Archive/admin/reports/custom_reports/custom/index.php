<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/broker-functions.php";
require_once "../../../../lib/customer-functions.php";
require_once "../../../../lib/loan-functions.php";
require_once "../../../../lib/guarantor-functions.php";
require_once "../../../../lib/agency-functions.php";
require_once "../../../../lib/our-company-function.php";
require_once "../../../../lib/file-functions.php";
require_once "../../../../lib/vehicle-functions.php";
require_once "../../../../lib/vehicle-insurance-functions.php";
require_once "../../../../lib/super-category-functions.php";
require_once "../../../../lib/insurance-company-functions.php";
require_once "../../../../lib/vehicle-dealer-functions.php";
require_once "../../../../lib/vehicle-model-functions.php";
require_once "../../../../lib/vehicle-type-functions.php";
require_once "../../../../lib/report-functions.php";
require_once "../../../../lib/area-functions.php";


if(isset($_SESSION['EMSadminSession']['admin_rights']))
$admin_rights=$_SESSION['EMSadminSession']['admin_rights'];

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
		
		if(isset($_POST['win_gt']))
		{
		$win_gt=$_POST['win_gt'];
		}
		else
		$win_gt=null;
		
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
			
		$area=$_POST['area'];
		$area_id=getAreaIdFromName($area);
		}
		else
		$area_id=null;
		
		if(isset($_POST['area']))
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
		
		if($area_id==false)
		$area_id=null;
		
		if($city_id==-1)
		$city_id=null;
		
		
		$reportArray=generalEMIReports($from,$to,$win_gt,$win_lt,$emi_gt,$emi_lt,$balance_gt,$balance_lt,$city_id,$area_id,$file_status,$agency_id);
		
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
		$_SESSION['cEMIReport']['area_id']=$area;
		$_SESSION['cEMIReport']['agency_id']=$agency_id;
		$_SESSION['cEMIReport']['file_status']=$file_status;
		$_SESSION['cEMIReport']['fields']=$_POST['fields'];
	
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
		$to=null;	
		
		if(isset($_POST['win']))
		{
		$win=$_POST['win'];
		}
		else
		$win=null;
		
		if(isset($_POST['city_id']))
		{
		$city_id=$_POST['city_id'];
		}
		else
		$city_id=null;
		
		if(isset($_POST['area']))
		{
			
		$area=$_POST['area'];
		$area_id=getAreaIdFromName($area);
		}
		else
		$area_id=null;
		
		if(isset($_POST['area']))
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
		
		if($area_id==false)
		$area_id=null;
		
		if($city_id==-1)
		$city_id=null;
		
		
		
		$reportArray=generalEMIReports($from,$to,$win,$city_id,$area_id,$file_status,$agency_id);
		
		$_SESSION['cEMIReport']['emi_array']=$reportArray;
		
		$_SESSION['cEMIReport']['from']=$from;
		$_SESSION['cEMIReport']['to']=$to;
		$_SESSION['cEMIReport']['win']=$win;
		$_SESSION['cEMIReport']['city_id']=$city_id;
		$_SESSION['cEMIReport']['area_id']=$area;
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
		$to=date('Y-m-d');	
		
		if(isset($_POST['win']))
		{
		$win=$_POST['win'];
		}
		else
		$win=1;
		
		if(isset($_POST['city_id']))
		{
		$city_id=$_POST['city_id'];
		}
		else
		$city_id=null;
		
		if(isset($_POST['area']))
		{
			
		$area=$_POST['area'];
		$area_id=getAreaIdFromName($area);
		}
		else
		$area_id=null;
		
		if(isset($_POST['area']))
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
		
		if($area_id==false)
		$area_id=null;
		
		if($city_id==-1)
		$city_id=null;
		
		
		
		$reportArray=generalEMIReports($from,$to,$win,$city_id,$area_id,$file_status,$agency_id);
		
		$_SESSION['cEMIReport']['emi_array']=$reportArray;
		
		$_SESSION['cEMIReport']['from']=$from;
		$_SESSION['cEMIReport']['to']=$to;
		$_SESSION['cEMIReport']['win']=$win;
		$_SESSION['cEMIReport']['city_id']=$city_id;
		$_SESSION['cEMIReport']['area_id']=$area;
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
		$to=date('Y-m-d');	
		
		if(isset($_POST['win']))
		{
		$win=$_POST['win'];
		}
		else
		$win=1;
		
		if(isset($_POST['city_id']))
		{
		$city_id=$_POST['city_id'];
		}
		else
		$city_id=null;
		
		if(isset($_POST['area']))
		{
			
		$area=$_POST['area'];
		$area_id=getAreaIdFromName($area);
		}
		else
		$area_id=null;
		
		if(isset($_POST['area']))
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
		
		if($area_id==false)
		$area_id=null;
		
		if($city_id==-1)
		$city_id=null;
		
		
		
		$reportArray=generalEMIReports($from,$to,$win,$city_id,$area_id,2,$agency_id);
		
		$_SESSION['cEMIReport']['emi_array']=$reportArray;
		
		$_SESSION['cEMIReport']['from']=$from;
		$_SESSION['cEMIReport']['to']=$to;
		$_SESSION['cEMIReport']['win']=$win;
		$_SESSION['cEMIReport']['city_id']=$city_id;
		$_SESSION['cEMIReport']['area_id']=$area;
		$_SESSION['cEMIReport']['agency_id']=$agency_id;
		$_SESSION['cEMIReport']['file_status']=2;
	
		header("Location: index.php");		
		exit;
	}
}
?>

<?php

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="reports";
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","validators/generalEMIReports.js","customerDatePicker.js");
$cssArray=array("jquery-ui.css");
require_once "../../../../inc/template.php";
 ?>