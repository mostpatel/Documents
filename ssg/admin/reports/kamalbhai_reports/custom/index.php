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
		
		if(isset($_POST['payment_date']))
		{
		$payment_date=$_POST['payment_date'];
		}
		else
		$payment_date=null;
		
		if(isset($_POST['end_date']))
		{
		$to=$_POST['end_date'];
		}
		else
		$to=null;	
		
	
		
		if(isset($_POST['agency_id']))
		{
			
			
		$agency_id=$_POST['agency_id'];
		}
		else
		$agency_id=null;
				
		
		
		$reportArray=generateKamalBhaiReports($from,$to,$agency_id);
		
		
		$_SESSION['cKamalReport']['emi_array']=$reportArray;
		$_SESSION['cKamalReport']['from']=$from;
		
		$_SESSION['cKamalReport']['payment_date']=$payment_date;
		
		$_SESSION['cKamalReport']['to']=$to;
		$_SESSION['cKamalReport']['agency_id']=$agency_id;
		header("Location: index.php");		
		exit;
	}
	
	if($_GET['action']=='add')
	{
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
		
		if(isset($_GET['payment_date']))
		{
		$payment_date=$_GET['payment_date'];
		}
		else
		$payment_date=null;	
		
	
		
		if(isset($_GET['agency_id']))
		{
			
			
		$agency_id=$_GET['agency_id'];
		}
		else
		$agency_id=null;
				
		
		
		$reportArray=addPaymentsForKamalBhaiReports($from,$to,$agency_id,$payment_date);
		
		$_SESSION['cKamalReport']['payment_date']=$payment_date;
		$_SESSION['cKamalReport']['emi_array']=$reportArray;
		$_SESSION['cKamalReport']['from']=$from;
		$_SESSION['cKamalReport']['to']=$to;
		$_SESSION['cKamalReport']['agency_id']=$agency_id;
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