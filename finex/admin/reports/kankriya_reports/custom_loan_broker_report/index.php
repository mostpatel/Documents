<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/customer-functions.php";
require_once "../../../../lib/extra-customer-functions.php";
require_once "../../../../lib/agency-functions.php";
require_once "../../../../lib/our-company-function.php";
require_once "../../../../lib/file-functions.php";
require_once "../../../../lib/vehicle-functions.php";
require_once "../../../../lib/vehicle-company-functions.php";
require_once "../../../../lib/vehicle-dealer-functions.php";
require_once "../../../../lib/vehicle-model-functions.php";
require_once "../../../../lib/vehicle-type-functions.php";
require_once "../../../../lib/report-functions.php";
require_once "../../../../lib/area-functions.php";
require_once "../../../../lib/broker-functions.php";
require_once "../../../../lib/file-charges-functions.php";
require_once "../../../../lib/loan-functions.php";


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
		$from=NULL;
		
		if(isset($_POST['end_date']))
		{
		$to=$_POST['end_date'];
		}
		else
		$to=NULL;	
		
		if(isset($_POST['start_date_approval']))
		{
		$from_approval_date=$_POST['start_date_approval'];
		}
		else
		$from_approval_date=NULL;
		
		if(isset($_POST['end_date_approval']))
		{
		$to_approval_date=$_POST['end_date_approval'];
		}
		else
		$to_approval_date=NULL;	
		
		if(isset($_POST['start_date_noc']))
		{
		$from_noc_date=$_POST['start_date_noc'];
		}
		else
		$from_noc_date=NULL;
		
		if(isset($_POST['end_date_noc']))
		{
		$to_noc_date=$_POST['end_date_noc'];
		}
		else
		$to_noc_date=NULL;	
		
		if(isset($_POST['from_emi_date']))
		{
		$from_emi_date=$_POST['from_emi_date'];
		}
		else
		$from_emi_date=NULL;
		
		if(isset($_POST['to_emi_date']))
		{
		$to_emi_date=$_POST['to_emi_date'];
		}
		else
		$to_emi_date=NULL;	
		
		if(isset($_POST['win_gt']))
		{
		$win_gt=$_POST['win_gt'];
		}
		else
		$win_gt=NULL;
		
		if(isset($_POST['win_lt']))
		{
		$win_lt=$_POST['win_lt'];
		}
		else
		$win_lt=NULL;
		
		if(isset($_POST['roi_gt']))
		{
		$roi_gt=$_POST['roi_gt'];
		}
		else
		$roi_gt=NULL;
		
		if(isset($_POST['roi_lt']))
		{
		$roi_lt=$_POST['roi_lt'];
		}
		else
		$roi_lt=NULL;
		
		if(isset($_POST['emi_gt']))
		{
		$emi_gt=$_POST['emi_gt'];
		}
		else
		$emi_gt=NULL;
		
		if(isset($_POST['emi_lt']))
		{
		$emi_lt=$_POST['emi_lt'];
		}
		else
		$emi_lt=NULL;
		
		if(isset($_POST['balance_gt']))
		{
		$balance_gt=$_POST['balance_gt'];
		}
		else
		$balance_gt=NULL;
		
		if(isset($_POST['balance_lt']))
		{
		$balance_lt=$_POST['balance_lt'];
		}
		else
		$balance_lt=NULL;
		
		if(isset($_POST['city_id']))
		{
		$city_id=$_POST['city_id'];
		}
		else
		$city_id=NULL;
		
		if(isset($_POST['area']))
		{
			
		$area_array=$_POST['area'];
		
		$area_id_string=implode(',',$area_array);
		}
		else
		$area_id_string=NULL;
		
		if(isset($_POST['broker']))
		{
			
		$broker_array=$_POST['broker'];
		
		$broker_id_string=implode(',',$broker_array);
		}
		else
		$broker_id_string=NULL;
		
		if(isset($_POST['vehicle_type']))
		{
			
		$vehicle_type_array=$_POST['vehicle_type'];
		
		$vehicle_type_string=implode(',',$vehicle_type_array);
		}
		else
		$vehicle_type_string=NULL;
		
		if(isset($_POST['vehicle_condition']))
		{
			
		$vehicle_condition=$_POST['vehicle_condition'];
		
		}
		else
		$vehicle_condition=NULL;
		
		if(isset($_POST['noc_status']))
		{
			
		$noc_status=$_POST['noc_status'];
		
		}
		else
		$noc_status=NULL;
		
		
		if(isset($_POST['agency_id']))
		{
			
		$agency_id=$_POST['agency_id'];
		}
		else
		$agency_id=NULL;
		
		if(isset($_POST['file_status']))
		{
			
		$file_status=$_POST['file_status'];
		}
		else
		$file_status=NULL;
		
		if(isset($_POST['show_legal']))
		{
			
		$show_legal=$_POST['show_legal'];
		}
		else
		$show_legal=1;
		
		if($area_id_string==false)
		$area_id_string=NULL;
		
		if($city_id==-1)
		$city_id=NULL;
		
		if($_POST['view_type']==0)
		{
			if(is_array($_POST['selectTR']))
			$file_id_array = $_POST['selectTR'];
			
			if(is_array($file_id_array) && count($file_id_array)<1)
			$file_id_array=NULL;
		}
		if(checkForNumeric($_POST['dc'],$_POST['our_roi'],$_POST['participation'],$_POST['lr']) && $_POST['dc']>=0 && $_POST['our_roi']>=0 && $_POST['participation']>=0 && $_POST['lr']>=0) 
		{
		$reportArray=kankriyaBrokerReports($from,$to,$from_approval_date,$to_approval_date,$from_noc_date,$to_noc_date,$roi_gt,$roi_lt,$vehicle_condition,$win_gt,$win_lt,$emi_gt,$emi_lt,$balance_gt,$balance_lt,$city_id,$area_id_string,$file_status,$agency_id,$broker_id_string,NULL,$vehicle_type_string,$from_emi_date,$to_emi_date,NULL,$_POST['view_type'],$file_id_array,$show_legal,1,$noc_status);
		}
		$_SESSION['cKankriyaBrokerReport']['emi_array']=$reportArray;
		$_SESSION['cKankriyaBrokerReport']['from_approval_date']=$from_approval_date;
		$_SESSION['cKankriyaBrokerReport']['to_approval_date']=$to_approval_date;
		$_SESSION['cKankriyaBrokerReport']['from_noc_date']=$from_noc_date;
		$_SESSION['cKankriyaBrokerReport']['to_noc_date']=$to_noc_date;
		$_SESSION['cKankriyaBrokerReport']['from']=$from;
		$_SESSION['cKankriyaBrokerReport']['to']=$to;
		$_SESSION['cKankriyaBrokerReport']['win_gt']=$win_gt;
		$_SESSION['cKankriyaBrokerReport']['win_lt']=$win_lt;
		$_SESSION['cKankriyaBrokerReport']['roi_gt']=$roi_gt;
		$_SESSION['cKankriyaBrokerReport']['roi_lt']=$roi_lt;
		$_SESSION['cKankriyaBrokerReport']['emi_gt']=$emi_gt;
		$_SESSION['cKankriyaBrokerReport']['emi_lt']=$emi_lt;
		$_SESSION['cKankriyaBrokerReport']['vehicle_condition']=$vehicle_condition;
		$_SESSION['cKankriyaBrokerReport']['noc_status']=$noc_status;
		$_SESSION['cKankriyaBrokerReport']['balance_gt']=$balance_gt;
		$_SESSION['cKankriyaBrokerReport']['balance_lt']=$balance_lt;
		$_SESSION['cKankriyaBrokerReport']['city_id']=$city_id;
		$_SESSION['cKankriyaBrokerReport']['area_id_array']=$area_array;
		$_SESSION['cKankriyaBrokerReport']['broker_id_array']=$broker_array;
		$_SESSION['cKankriyaBrokerReport']['vehicle_type_array']=$vehicle_type_array;
		$_SESSION['cKankriyaBrokerReport']['agency_id']=$agency_id;
		$_SESSION['cKankriyaBrokerReport']['file_status']=$file_status;
		$_SESSION['cKankriyaBrokerReport']['seized']=$_POST['seized'];
		$_SESSION['cKankriyaBrokerReport']['show_legal']=$_POST['show_legal'];
		$_SESSION['cKankriyaBrokerReport']['view_type']=$_POST['view_type'];
		$_SESSION['cKankriyaBrokerReport']['lr']=$_POST['lr'];
		$_SESSION['cKankriyaBrokerReport']['penalty_type']=$_POST['penalty_type'];
		$_SESSION['cKankriyaBrokerReport']['dc']=$_POST['dc'];
		$_SESSION['cKankriyaBrokerReport']['our_roi']=$_POST['our_roi'];
		$_SESSION['cKankriyaBrokerReport']['participation']=$_POST['participation'];
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
		$win_lt=NULL;
		
		if(isset($_POST['emi_gt']))
		{
		$emi_gt=$_POST['emi_gt'];
		}
		else
		$emi_gt=NULL;
		
		if(isset($_POST['emi_lt']))
		{
		$emi_lt=$_POST['emi_lt'];
		}
		else
		$emi_lt=NULL;
		
		if(isset($_POST['balance_gt']))
		{
		$balance_gt=$_POST['balance_gt'];
		}
		else
		$balance_gt=NULL;
		
		if(isset($_POST['balance_lt']))
		{
		$balance_lt=$_POST['balance_lt'];
		}
		else
		$balance_lt=NULL;
		
		if(isset($_POST['city_id']))
		{
		$city_id=$_POST['city_id'];
		}
		else
		$city_id=NULL;
		
		if(isset($_POST['area']))
		{
			
		$area_array=$_POST['area'];
		
		$area_id_string=implode(',',$area_array);
		}
		else
		$area_id=NULL;
		
		if(isset($_POST['$agency_id']))
		{
			
		$agency_id=$_POST['agency_id'];
		}
		else
		$agency_id=NULL;
		
		if(isset($_POST['file_status']))
		{
			
		$file_status=$_POST['file_status'];
		}
		else
		$file_status=NULL;
		
		if($area_id_string==false)
		$area_id_string=NULL;
		
		if($city_id==-1)
		$city_id=NULL;
		
		
		
		$reportArray=generalEMIReports($from,$to,$win_gt,$win_lt,$emi_gt,$emi_lt,$balance_gt,$balance_lt,$city_id,$area_id_string,$file_status,$agency_id);
		
		$_SESSION['cKankriyaBrokerReport']['emi_array']=$reportArray;
		
		$_SESSION['cKankriyaBrokerReport']['from']=$from;
		$_SESSION['cKankriyaBrokerReport']['to']=$to;
		$_SESSION['cKankriyaBrokerReport']['win_gt']=$win_gt;
		$_SESSION['cKankriyaBrokerReport']['win_lt']=$win_lt;
		$_SESSION['cKankriyaBrokerReport']['emi_gt']=$emi_gt;
		$_SESSION['cKankriyaBrokerReport']['emi_lt']=$emi_lt;
		$_SESSION['cKankriyaBrokerReport']['balance_gt']=$balance_gt;
		$_SESSION['cKankriyaBrokerReport']['balance_lt']=$balance_lt;
		$_SESSION['cKankriyaBrokerReport']['city_id']=$city_id;
		$_SESSION['cKankriyaBrokerReport']['area_id_array']=$area_array;
		$_SESSION['cKankriyaBrokerReport']['agency_id']=$agency_id;
		$_SESSION['cKankriyaBrokerReport']['file_status']=$file_status;
	
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
		$from=NULL;
		
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
		$win_lt=NULL;
		
		if(isset($_POST['emi_gt']))
		{
		$emi_gt=$_POST['emi_gt'];
		}
		else
		$emi_gt=NULL;
		
		if(isset($_POST['emi_lt']))
		{
		$emi_lt=$_POST['emi_lt'];
		}
		else
		$emi_lt=NULL;
		
		if(isset($_POST['balance_gt']))
		{
		$balance_gt=$_POST['balance_gt'];
		}
		else
		$balance_gt=NULL;
		
		if(isset($_POST['balance_lt']))
		{
		$balance_lt=$_POST['balance_lt'];
		}
		else
		$balance_lt=NULL;
		
		if(isset($_POST['city_id']))
		{
		$city_id=$_POST['city_id'];
		}
		else
		$city_id=NULL;
		
		if(isset($_POST['area']))
		{
			
		$area_array=$_POST['area'];
		
		$area_id_string=implode(',',$area_array);
		}
		else
		$area_id=NULL;
		
		if(isset($_POST['$agency_id']))
		{
			
		$agency_id=$_POST['agency_id'];
		}
		else
		$agency_id=NULL;
		
		if(isset($_POST['file_status']))
		{
			
		$file_status=$_POST['file_status'];
		}
		else
		$file_status=1;
		
		if($area_id_string==false)
		$area_id_string=NULL;
		
		if($city_id==-1)
		$city_id=NULL;
		
		
		
		$reportArray=generalEMIReports($from,$to,$win_gt,$win_lt,$emi_gt,$emi_lt,$balance_gt,$balance_lt,$city_id,$area_id_string,$file_status,$agency_id);
		
		$_SESSION['cKankriyaBrokerReport']['emi_array']=$reportArray;
		
		$_SESSION['cKankriyaBrokerReport']['from']=$from;
		$_SESSION['cKankriyaBrokerReport']['to']=$to;
		$_SESSION['cKankriyaBrokerReport']['win_gt']=$win_gt;
		$_SESSION['cKankriyaBrokerReport']['win_lt']=$win_lt;
		$_SESSION['cKankriyaBrokerReport']['emi_gt']=$emi_gt;
		$_SESSION['cKankriyaBrokerReport']['emi_lt']=$emi_lt;
		$_SESSION['cKankriyaBrokerReport']['balance_gt']=$balance_gt;
		$_SESSION['cKankriyaBrokerReport']['balance_lt']=$balance_lt;
		$_SESSION['cKankriyaBrokerReport']['city_id']=$city_id;
		$_SESSION['cKankriyaBrokerReport']['area_id_array']=$area_array;
		$_SESSION['cKankriyaBrokerReport']['agency_id']=$agency_id;
		$_SESSION['cKankriyaBrokerReport']['file_status']=$file_status;
	
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
		$from=NULL;
		
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
		$win_lt=NULL;
		
		if(isset($_POST['emi_gt']))
		{
		$emi_gt=$_POST['emi_gt'];
		}
		else
		$emi_gt=NULL;
		
		if(isset($_POST['emi_lt']))
		{
		$emi_lt=$_POST['emi_lt'];
		}
		else
		$emi_lt=NULL;
		
		if(isset($_POST['balance_gt']))
		{
		$balance_gt=$_POST['balance_gt'];
		}
		else
		$balance_gt=NULL;
		
		if(isset($_POST['balance_lt']))
		{
		$balance_lt=$_POST['balance_lt'];
		}
		else
		$balance_lt=NULL;
		
		if(isset($_POST['city_id']))
		{
		$city_id=$_POST['city_id'];
		}
		else
		$city_id=NULL;
		
		if(isset($_POST['area']))
		{
			
		$area_array=$_POST['area'];
		
		$area_id_string=implode(',',$area_array);
		}
		else
		$area_id=NULL;
		
		if(isset($_POST['$agency_id']))
		{
			
		$agency_id=$_POST['agency_id'];
		}
		else
		$agency_id=NULL;
		
		if(isset($_POST['file_status']))
		{
			
		$file_status=$_POST['file_status'];
		}
		else
		$file_status=2;
		
		if($area_id_string==false)
		$area_id_string=NULL;
		
		if($city_id==-1)
		$city_id=NULL;
		
		
		
		$reportArray=generalEMIReports($from,$to,$win_gt,$win_lt,$emi_gt,$emi_lt,$balance_gt,$balance_lt,$city_id,$area_id_string,$file_status,$agency_id);
		
		$_SESSION['cKankriyaBrokerReport']['emi_array']=$reportArray;
		
		$_SESSION['cKankriyaBrokerReport']['from']=$from;
		$_SESSION['cKankriyaBrokerReport']['to']=$to;
		$_SESSION['cKankriyaBrokerReport']['win_gt']=$win_gt;
		$_SESSION['cKankriyaBrokerReport']['win_lt']=$win_lt;
		$_SESSION['cKankriyaBrokerReport']['emi_gt']=$emi_gt;
		$_SESSION['cKankriyaBrokerReport']['emi_lt']=$emi_lt;
		$_SESSION['cKankriyaBrokerReport']['balance_gt']=$balance_gt;
		$_SESSION['cKankriyaBrokerReport']['balance_lt']=$balance_lt;
		$_SESSION['cKankriyaBrokerReport']['city_id']=$city_id;
		$_SESSION['cKankriyaBrokerReport']['area_id_array']=$area_array;
		$_SESSION['cKankriyaBrokerReport']['agency_id']=$agency_id;
		$_SESSION['cKankriyaBrokerReport']['file_status']=$file_status;
	
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