<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/common.php";
require_once "../../../lib/city-functions.php";
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/our-company-function.php";
require_once "../../../lib/vehicle-functions.php";
require_once "../../../lib/vehicle-model-functions.php";
require_once "../../../lib/vehicle-company-functions.php";
require_once "../../../lib/vehicle-type-functions.php";
require_once "../../../lib/vehicle-insurance-functions.php";
require_once "../../../lib/insurance-company-functions.php";
require_once "../../../lib/account-ledger-functions.php";


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
	else if($_GET['view']=='vehicleDetails')
	{
		$content="details.php";
		$link="searchCustomer";
		}
	else if($_GET['view']=='editVehicle')
	{
		$content="vehicleEdit.php";
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
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))
			{
				
				$result=insertVehicle($_POST["model_id"],$_POST['vehicle_engine_no'],$_POST['vehicle_chasis_no'],NULL,$_POST['model_year'],$_POST['condition'],0,$_POST['vehicleProofId'],$_POST['vehicleProofNo'],$_FILES['vehicleProofImg'],NULL,$_POST['customer_id'],$_POST['vehicle_reg_no'],$_POST['vehicle_reg_date'],"NA","NA",0,$_POST['is_sold_customer'],NULL,"NA","NA","NA",$_POST['fitness_exp_date'],$_POST['permit_exp_date'],"NA","NA",$_POST['ledger_id'],$_POST['opening_balance'],$_POST['opening_balance_cd'],$_POST['extra_ledger_id'],$_POST['opening_balance_extra'],$_POST['opening_balance_cd_extra']);
				
				if(is_numeric($result))
				{
				$_SESSION['ack']['msg']="Vehicle successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_POST['customer_id']);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/vehicle/index.php?id=".$_POST['customer_id']);
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
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				deleteVehicle($_GET["lid"]);
				
				$_SESSION['ack']['msg']="Vehilce deleted Successfuly!";
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
	if($_GET['action']=='editVehicle')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$vehicle=getVehicleById($_POST["lid"]);
				$result=updateVehicle($_POST["lid"],$_POST["model_id"],$_POST['vehicle_engine_no'],$_POST['vehicle_chasis_no'],NULL,$_POST['model_year'],$_POST['condition'],NULL,$_POST['vehicleProofId'],$_POST['vehicleProofNo'],$_FILES['vehicleProofImg'],NULL,$_POST['customer_id'],$_POST['vehicle_reg_no'],$_POST['vehicle_reg_date'],NULL,NULL,NULL,$_POST['is_sold_customer'],NULL,NULL,NULL,NULL,$_POST['fitness_exp_date'],$_POST['permit_exp_date'],$vehicle['battery_service_book_no'],$vehicle['service_no'],$_POST['ledger_id'],$_POST['opening_balance'],$_POST['opening_balance_cd'],$_POST['extra_ledger_id'],$_POST['opening_balance_extra'],$_POST['opening_balance_cd_extra']);
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
				header("Location: ".$_SERVER['PHP_SELF']."?view=vehicleDetails&id=".$_POST["lid"]);
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
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
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
				header("Location: ".$_SERVER['PHP_SELF']."?view=vehicleDetails&id=".$_GET["id"]);
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
require_once "../../../inc/template.php";
 ?>