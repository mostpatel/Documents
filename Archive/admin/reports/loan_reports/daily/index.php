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
		
		
		$reportArray=generalLoanReports($from,$to,$city_id,$area_id,$file_status,$agency_id);
		$_SESSION['dLoanReport']['remainder_array']=$reportArray;
		
		$_SESSION['dLoanReport']['from']=$from;
		$_SESSION['dLoanReport']['to']=$to;
		$_SESSION['dLoanReport']['remainder_status']=$remainder_status;
		$_SESSION['dLoanReport']['city_id']=$city_id;
		$_SESSION['dLoanReport']['area_id']=$area;
		$_SESSION['dLoanReport']['agency_id']=$agency_id;
		$_SESSION['dLoanReport']['file_status']=$file_status;
	
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