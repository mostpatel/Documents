<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/city-functions.php";
require_once "../../../../lib/file-functions.php";
require_once "../../../../lib/agency-functions.php";
require_once "../../../../lib/our-company-function.php";
require_once "../../../../lib/vehicle-functions.php";
require_once "../../../../lib/vehicle-model-functions.php";
require_once "../../../../lib/vehicle-dealer-functions.php";
require_once "../../../../lib/vehicle-company-functions.php";
require_once "../../../../lib/vehicle-type-functions.php";
require_once "../../../../lib/rto-agent-functions.php";

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
	else if($_GET['view']=='details')
	{
		$content="details.php";
		$link="searchCustomer";
		}
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
		$link="searchCustomer";
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
	if($_GET['action']=='add')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))
			{
				
				
				$vehicle_id = $_POST['vehicle_id'];
				
				if(checkForNumeric($vehicle_id))	
				$vehicle_docs_id=insertVehicleDocuments($vehicle_id,$_POST["rto"],$_POST['passing'],$_POST['permit'],$_POST['insurance'],$_POST['hp'],$_POST['bill'],$_POST['remarks'],$_POST['key'],$_POST['rto_agent'],$_POST['work_given_date'],$_POST['rto_work'],$_POST['work_completion_date'],$_POST['customer_given_date'],$_POST['customer_received_date']);	
				if(checkForNumeric($vehicle_docs_id))
				{
				if(defined("ADD_NEW_CUSTOMER_REDIRECT") && (ADD_NEW_CUSTOMER_REDIRECT==1 || ADD_NEW_CUSTOMER_REDIRECT==2))
				{	
				$_SESSION['ack']['msg']="Vehicle Documents successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=ledgerView&id=".$_POST['file_id']);
				exit;
				}
				else
				{
				$_SESSION['ack']['msg']="Vehicle successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/customer/vehicle/docs/index.php?view=details&id=".$_POST['vehicle_id']);
				exit;
				}
				
				
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/vehicle/docs/index.php?id=".$_POST['vehicle_id']);
				exit;
				}
				
				
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".$_SERVER['PHP_SELF']);
			exit;
			}
		}
	if($_GET['action']=='delete')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				deleteCity($_GET["lid"]);
				
				$_SESSION['ack']['msg']="Item deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				
				header("Location: ".$_SERVER['PHP_SELF']);
				exit;
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".$_SERVER['PHP_SELF']);
			exit;
			}
		}
	if($_GET['action']=='edit')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				editLocation($_POST["lid"],$_POST["location"]);
				
				$_SESSION['ack']['msg']="Item updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				
				header("Location: ".$_SERVER['PHP_SELF']);
				exit;
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".$_SERVER['PHP_SELF']);
			exit;
			}
			
		}	
	if($_GET['action']=='editVehicle')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				if(checkForNumeric($_POST["lid"]))	
				$result=updateVehicleDocuments($_POST["lid"],$_POST["rto"],$_POST['passing'],$_POST['permit'],$_POST['insurance'],$_POST['hp'],$_POST['bill'],$_POST['remarks'],$_POST['key'],$_POST['rto_agent'],$_POST['work_given_date'],$_POST['rto_work'],$_POST['work_completion_date'],$_POST['customer_given_date'],$_POST['customer_received_date']);		
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Vehicle updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
					}
				header("Location: ".$_SERVER['PHP_SELF']."?view=details&id=".$_POST["lid"]);
				exit;
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".$_SERVER['PHP_SELF']);
					exit;
			}
			
		}
	if($_GET['action']=='delVehicleProof')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				$result=deleteVehicleProof($_GET["state"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Vehicle Proof Deleted Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Unable to delete Vehicle Proof!";
					$_SESSION['ack']['type']=4; // 4 for error
					
					}
				header("Location: ".$_SERVER['PHP_SELF']."?view=guarantorDetails&id=".$_GET["id"]);
				exit;
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".$_SERVER['PHP_SELF']);
					exit;
			}
			
		}				
	}

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="searchCustomer";
$jsArray=array("jquery.validate.js","scanProof.js","dropDown.js","checkAvailability.js","jquery-ui/js/jquery-ui.min.js","customerDatePicker.js","addVehicleProof.js","generateProofimgVehicle.js","validators/addNewVehicle.js");
$cssArray=array("jquery-ui.css");
require_once "../../../../inc/template.php";
 ?>