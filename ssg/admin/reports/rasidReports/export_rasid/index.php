<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/customer-functions.php";
require_once "../../../../lib/agency-functions.php";
require_once "../../../../lib/our-company-function.php";
require_once "../../../../lib/file-functions.php";
require_once "../../../../lib/vehicle-functions.php";
require_once "../../../../lib/report-functions.php";
require_once "../../../../lib/area-functions.php";
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
		$from=null;
		
		if(isset($_POST['end_date']))
		{
		$to=$_POST['end_date'];
		}
		else
		$to=null;	
		
		
		
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
		
		if(isset($_POST['agency_id']))
		{
		$agency_id=$_POST['agency_id'];
		}
		else
		$agency_id=null;
		
		if(isset($_POST['payment_mode']))
		{	
		$payment_mode=$_POST['payment_mode'];
		}
		else
		$payment_mode=null;
		
		if(isset($_POST['type']))
		{	
		$type_array=$_POST['type'];
		$type=implode(',',$type_array);
		}
		else
		$type=null;
		
		if($area_id_string==false)
		$area_id_string=null;
		
		if($city_id==-1)
		$city_id=null;
		
		
		
		$reportArray=generalRasidReports($from,$to,$city_id,$area_id_string,$agency_id,$payment_mode,$type);
		
		
		$_SESSION['cRasidReportSTFC']['emi_array']=$reportArray;
		$_SESSION['cRasidReportSTFC']['from']=$from;
		$_SESSION['cRasidReportSTFC']['to']=$to;
		$_SESSION['cRasidReportSTFC']['city_id']=$city_id;
		$_SESSION['cRasidReportSTFC']['area_id_array']=$area_array;
		$_SESSION['cRasidReportSTFC']['agency_id']=$agency_id;
		$_SESSION['cRasidReportSTFC']['payment_mode']=$payment_mode;
		$_SESSION['cRasidReportSTFC']['type']=$type_array;
		header("Location: index.php");		
		exit;
	}
}
?>

<?php

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="reports";
$jsArray=array("jquery.validate.js","dropDown.js","jquery-ui/js/jquery-ui.min.js","validators/generalEMIReports.js","validators/rasidReports.js","customerDatePicker.js");
$cssArray=array("jquery-ui.css");
require_once "../../../../inc/template.php";
 ?>