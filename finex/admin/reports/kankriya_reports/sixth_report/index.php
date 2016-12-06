<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/customer-functions.php";
require_once "../../../../lib/agency-functions.php";
require_once "../../../../lib/legal-notice-functions.php";
require_once "../../../../lib/our-company-function.php";
require_once "../../../../lib/file-functions.php";
require_once "../../../../lib/loan-functions.php";
require_once "../../../../lib/vehicle-functions.php";
require_once "../../../../lib/vehicle-company-functions.php";
require_once "../../../../lib/vehicle-dealer-functions.php";
require_once "../../../../lib/vehicle-model-functions.php";
require_once "../../../../lib/vehicle-type-functions.php";
require_once "../../../../lib/report-functions.php";
require_once "../../../../lib/area-functions.php";
require_once "../../../../lib/broker-functions.php";
require_once "../../../../lib/extra-customer-functions.php";
require_once "../../../../lib/cheque-return-functions.php";


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
		$from=null;
		
		if(isset($_POST['end_date']))
		{
		$to=$_POST['end_date'];
		}
		else
		$to=null;	
		
		if(isset($_POST['from_emi_date']))
		{
		$from_emi_date=$_POST['from_emi_date'];
		}
		else
		$from_emi_date=null;
		
		if(isset($_POST['to_emi_date']))
		{
		$to_emi_date=$_POST['to_emi_date'];
		}
		else
		$to_emi_date=null;	
		
		if(isset($_POST['from_case_date']))
		{
		$from_case_date=$_POST['from_case_date'];
		}
		else
		$from_case_date=null;
		
		if(isset($_POST['to_case_date']))
		{
		$to_case_date=$_POST['to_case_date'];
		}
		else
		$to_case_date=null;	
		
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
			
		$area_array=$_POST['area'];
		
		$area_id_string=implode(',',$area_array);
		}
		else
		$area_id_string=null;
		
		if(isset($_POST['broker']))
		{
			
		$broker_array=$_POST['broker'];
		
		$broker_id_string=implode(',',$broker_array);
		}
		else
		$broker_id_string=null;
		
		if(isset($_POST['vehicle_type']))
		{
			
		$vehicle_type_array=$_POST['vehicle_type'];
		
		$vehicle_type_string=implode(',',$vehicle_type_array);
		}
		else
		$vehicle_type_string=null;
		
		
		if(isset($_POST['agency_id']))
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
		
		
		
		$reportArray=generalEMIReportsPaymentDate($from,$to,$win_gt,$win_lt,$emi_gt,$emi_lt,$balance_gt,$balance_lt,$city_id,$area_id_string,$file_status,$agency_id,$broker_id_string,null,$vehicle_type_string,$from_emi_date,$to_emi_date,NULL,$_POST['view_type'],$file_id_array,2);
		
		$_SESSION['cKankriyaSeventhReport']['emi_array']=$reportArray;
		$_SESSION['cKankriyaSeventhReport']['from']=$from;
		$_SESSION['cKankriyaSeventhReport']['to']=$to;
		$_SESSION['cKankriyaSeventhReport']['from_emi_date']=$from_emi_date;
		$_SESSION['cKankriyaSeventhReport']['to_emi_date']=$to_emi_date;
		$_SESSION['cKankriyaSeventhReport']['from_case_date']=$from_case_date;
		$_SESSION['cKankriyaSeventhReport']['to_case_date']=$to_case_date;
		$_SESSION['cKankriyaSeventhReport']['win_gt']=$win_gt;
		$_SESSION['cKankriyaSeventhReport']['win_lt']=$win_lt;
		$_SESSION['cKankriyaSeventhReport']['emi_gt']=$emi_gt;
		$_SESSION['cKankriyaSeventhReport']['emi_lt']=$emi_lt;
		$_SESSION['cKankriyaSeventhReport']['balance_gt']=$balance_gt;
		$_SESSION['cKankriyaSeventhReport']['balance_lt']=$balance_lt;
		$_SESSION['cKankriyaSeventhReport']['city_id']=$city_id;
		$_SESSION['cKankriyaSeventhReport']['area_id_array']=$area_array;
		$_SESSION['cKankriyaSeventhReport']['broker_id_array']=$broker_array;
		$_SESSION['cKankriyaSeventhReport']['vehicle_type_array']=$vehicle_type_array;
		$_SESSION['cKankriyaSeventhReport']['agency_id']=$agency_id;
		$_SESSION['cKankriyaSeventhReport']['file_status']=$file_status;
		$_SESSION['cKankriyaSeventhReport']['seized']=$_POST['seized'];
		$_SESSION['cKankriyaSeventhReport']['case_no']=$_POST['case_no'];
		$_SESSION['cKankriyaSeventhReport']['reg_ad']=$_POST['reg_ad'];
		$_SESSION['cKankriyaSeventhReport']['warrant']=$_POST['warrant'];
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
		
		$_SESSION['cKankriyaSeventhReport']['emi_array']=$reportArray;
		
		$_SESSION['cKankriyaSeventhReport']['from']=$from;
		$_SESSION['cKankriyaSeventhReport']['to']=$to;
		$_SESSION['cKankriyaSeventhReport']['win_gt']=$win_gt;
		$_SESSION['cKankriyaSeventhReport']['win_lt']=$win_lt;
		$_SESSION['cKankriyaSeventhReport']['emi_gt']=$emi_gt;
		$_SESSION['cKankriyaSeventhReport']['emi_lt']=$emi_lt;
		$_SESSION['cKankriyaSeventhReport']['balance_gt']=$balance_gt;
		$_SESSION['cKankriyaSeventhReport']['balance_lt']=$balance_lt;
		$_SESSION['cKankriyaSeventhReport']['city_id']=$city_id;
		$_SESSION['cKankriyaSeventhReport']['area_id_array']=$area_array;
		$_SESSION['cKankriyaSeventhReport']['agency_id']=$agency_id;
		$_SESSION['cKankriyaSeventhReport']['file_status']=$file_status;
	
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
		
		$_SESSION['cKankriyaSeventhReport']['emi_array']=$reportArray;
		
		$_SESSION['cKankriyaSeventhReport']['from']=$from;
		$_SESSION['cKankriyaSeventhReport']['to']=$to;
		$_SESSION['cKankriyaSeventhReport']['win_gt']=$win_gt;
		$_SESSION['cKankriyaSeventhReport']['win_lt']=$win_lt;
		$_SESSION['cKankriyaSeventhReport']['emi_gt']=$emi_gt;
		$_SESSION['cKankriyaSeventhReport']['emi_lt']=$emi_lt;
		$_SESSION['cKankriyaSeventhReport']['balance_gt']=$balance_gt;
		$_SESSION['cKankriyaSeventhReport']['balance_lt']=$balance_lt;
		$_SESSION['cKankriyaSeventhReport']['city_id']=$city_id;
		$_SESSION['cKankriyaSeventhReport']['area_id_array']=$area_array;
		$_SESSION['cKankriyaSeventhReport']['agency_id']=$agency_id;
		$_SESSION['cKankriyaSeventhReport']['file_status']=$file_status;
	
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
		
		$_SESSION['cKankriyaSeventhReport']['emi_array']=$reportArray;
		
		$_SESSION['cKankriyaSeventhReport']['from']=$from;
		$_SESSION['cKankriyaSeventhReport']['to']=$to;
		$_SESSION['cKankriyaSeventhReport']['win_gt']=$win_gt;
		$_SESSION['cKankriyaSeventhReport']['win_lt']=$win_lt;
		$_SESSION['cKankriyaSeventhReport']['emi_gt']=$emi_gt;
		$_SESSION['cKankriyaSeventhReport']['emi_lt']=$emi_lt;
		$_SESSION['cKankriyaSeventhReport']['balance_gt']=$balance_gt;
		$_SESSION['cKankriyaSeventhReport']['balance_lt']=$balance_lt;
		$_SESSION['cKankriyaSeventhReport']['city_id']=$city_id;
		$_SESSION['cKankriyaSeventhReport']['area_id_array']=$area_array;
		$_SESSION['cKankriyaSeventhReport']['agency_id']=$agency_id;
		$_SESSION['cKankriyaSeventhReport']['file_status']=$file_status;
	
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