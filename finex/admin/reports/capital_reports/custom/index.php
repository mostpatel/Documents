<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/customer-functions.php";
require_once "../../../../lib/agency-functions.php";
require_once "../../../../lib/our-company-function.php";
require_once "../../../../lib/file-functions.php";
require_once "../../../../lib/vehicle-functions.php";
require_once "../../../../lib/vehicle-company-functions.php";
require_once "../../../../lib/customer-group-functions.php";
require_once "../../../../lib/report-functions.php";
require_once "../../../../lib/area-functions.php";


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
		
		
		
		if(isset($_POST['vehicle_company_id']))
		{
		$vehicle_company_id=$_POST['vehicle_company_id'];
		}
		else
		$vehicle_company_id=NULL;
		
		if(isset($_POST['model_year']))
		{
		$model_year=$_POST['model_year'];
		}
		else
		$model_year=NULL;
		
		if(isset($_POST['vehicle_type']))
		{
		$vehicle_type=$_POST['vehicle_type'];
		}
		else
		$vehicle_type=NULL;
		
		if(isset($_POST['group_id']))
		{
		$group_id=$_POST['group_id'];
		}
		else
		$group_id=NULL;
		
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
		
		if(isset($_POST['vehicle_type']))
		{	
		$vehicle_type=$_POST['vehicle_type'];
		}
		else
		$vehicle_type=null;
		
		if($area_id_string==false)
		$area_id_string=null;
		
		if($vehicle_company_id==-1)
		$vehicle_company_id=NULL;
		
		if($vehicle_type==-1)
		$vehicle_type=NULL;
		if($model_year==-1)
		$model_year=NULL;
		if($group_id==-1)
		$group_id=NULL;
		
		
	
		$reportArray=CapitalAndInterestReports($agency_id,$vehicle_company_id,$model_year,$group_id,$vehicle_type);
		
		
		$_SESSION['cCapitalReport']['collection_array']=$reportArray;
		$_SESSION['cCapitalReport']['from']=$from;
		$_SESSION['cCapitalReport']['to']=$to;
		$_SESSION['cCapitalReport']['vehicle_company_id']=$vehicle_company_id;
		$_SESSION['cCapitalReport']['vehicle_type']=$vehicle_type;
		$_SESSION['cCapitalReport']['group_id']=$group_id;
		$_SESSION['cCapitalReport']['model_year']=$model_year;
		$_SESSION['cCapitalReport']['area_id_array']=$area_array;
		$_SESSION['cCapitalReport']['agency_id']=$agency_id;
		
		header("Location: index.php");		
		exit;
	}
}
?>

<?php

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="reports";
$jsArray=array("jquery.validate.js", "dropDown.js","jquery-ui/js/jquery-ui.min.js","validators/generalEMIReports.js","customerDatePicker.js");
$cssArray=array("jquery-ui.css");
require_once "../../../../inc/template.php";
 ?>