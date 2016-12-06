<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/customer-functions.php";
require_once "../../../../lib/our-company-function.php";
require_once "../../../../lib/report-functions.php";
require_once "../../../../lib/sms-functions.php";
require_once "../../../../lib/sms-record-functions.php";

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
		

		if(isset($_POST['type']))
		{	
		$type=$_POST['type'];
		}
		else
		$type=null;
	
		
		
		
		$reportArray=getAllSmsRecords($from,$to,$type);
		
		
		$_SESSION['cSMSReport']['emi_array']=$reportArray;
		$_SESSION['cSMSReport']['from']=$from;
		$_SESSION['cSMSReport']['to']=$to;
		$_SESSION['cSMSReport']['type']=$type;
		header("Location: index.php");		
		exit;
	}
	if($_GET['action']=='send_sms')
	{
		if(isset($_GET['id']))
		{
		$id=$_GET['id'];
		}
		else
		exit;
		
		$sms_Record=getSMSRecordById($id);
		$message = $sms_Record['message'];
		$contact_no = $sms_Record['contact_no'];
		resendSMS($id,$message,$contact_no);
		
		$reportArray=getAllSmsRecords($_SESSION['cSMSReport']['from'],$_SESSION['cSMSReport']['to'],$_SESSION['cSMSReport']['type']);
		
		
		$_SESSION['cSMSReport']['emi_array']=$reportArray;
		
		
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