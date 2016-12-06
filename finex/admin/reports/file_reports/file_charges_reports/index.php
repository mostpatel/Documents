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
require_once "../../../../lib/broker-functions.php";
require_once "../../../../lib/file-charges-functions.php";


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
		
		if(isset($_POST['remainder_status']))
		{
		$remainder_status=$_POST['remainder_status'];
		}
		else
		$remainder_status=null;
		
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
		$broker_id_string=NULL;
		
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
		
		
		
		
		$reportArray=getFileChargesForLoansBetweenDates($from,$to,$agency_id,$broker_id_string,$file_status);
		$_SESSION['cFileChargesReport']['remainder_array']=$reportArray;
		$_SESSION['cFileChargesReport']['from']=$from;
		$_SESSION['cFileChargesReport']['to']=$to;
		$_SESSION['cFileChargesReport']['agency_id']=$agency_id;
		$_SESSION['cFileChargesReport']['file_status']=$file_status;
		$_SESSION['cFileChargesReport']['broker_id_array']=$broker_array;
		header("Location: index.php");		
		exit;
	}
}
?>

<?php

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="reports";
$jsArray=array("jquery.validate.js", "dropDown.js", "jquery-ui/js/jquery-ui.min.js","validators/generalEMIReports.js","customerDatePicker.js");
$cssArray=array("jquery-ui.css");
require_once "../../../../inc/template.php";
 ?>