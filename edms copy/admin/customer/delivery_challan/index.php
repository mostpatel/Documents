<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/common.php";
require_once "../../../lib/city-functions.php";
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/our-company-function.php";
require_once "../../../lib/vehicle-functions.php";
require_once "../../../lib/vehicle-color-functions.php";
require_once "../../../lib/vehicle-model-functions.php";
require_once "../../../lib/vehicle-company-functions.php";
require_once "../../../lib/vehicle-type-functions.php";
require_once "../../../lib/vehicle-insurance-functions.php";
require_once "../../../lib/delivery-challan-functions.php";
require_once "../../../lib/battery-make-functions.php";
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
	else if($_GET['view']=='challan')
	{
		$showTitle = false;
		$content="khusboo_chalan.php";
		}	
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
		}	
	else if($_GET['view']=='vehicleDetails')
	{
		$content="vehicleDetails.php";
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
				
				$result=insertDeliveryChallanForVehicle($_POST["customer_id"],$_POST['delivery_date'],$_POST['challan_no'],$_POST['engine_no'],$_POST['chasis_no'],$_POST['insurance_comp'],$_POST['insurance_date'],$_POST['salesman'],$_POST['financer'],$_POST['tool_kit'],$_POST['service_book'],$_POST['battery_included'],$_POST['spare_wheel'],$_POST['water_bottle'],$_POST['battery_make'],$_POST['battery_no'],$_POST['key_no'],$_POST['service_book_no'],$_POST['battery_service_book_no'],$_POST['service_no']);
				
				if(is_numeric($result))
				{
				$_SESSION['ack']['msg']="Delivery Challan successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/customer/delivery_challan/index.php?view=details&id=".$result);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_POST['customer_id']);
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
			
				$result=deleteDeliveryChallanById($_GET["id"]);
				$customer_id = $_GET['state'];
				if($result!="error")
				{
				$_SESSION['ack']['msg']="Delivery Challan deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				}
				else
				{
					$_SESSION['ack']['msg']="Delivery Challan Already In Use!";
				$_SESSION['ack']['type']=4; // 4 for error
				}
				
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$customer_id);
				exit;
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$customer_id);
			exit;
			}
		}
	if($_GET['action']=='edit')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$result=updateDeliveryChallan($_POST["delivery_challan_id"],$_POST["vehicle_id"],$_POST["insurance_id"],$_POST['delivery_date'],$_POST['challan_no'],$_POST['engine_no'],$_POST['chasis_no'],$_POST['insurance_comp'],$_POST['insurance_date'],$_POST['salesman'],$_POST['financer'],$_POST['tool_kit'],$_POST['service_book'],$_POST['battery_included'],$_POST['spare_wheel'],$_POST['water_bottle'],$_POST['battery_make'],$_POST['battery_no'],$_POST['key_no'],$_POST['service_book_no'],$_POST['battery_service_book_no'],$_POST['service_no']);
				
				if($result=="success" && is_numeric($_POST["delivery_challan_id"]))
				{
				$_SESSION['ack']['msg']="Delivery Challan successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/customer/delivery_challan/index.php?view=details&id=".$_POST["delivery_challan_id"]);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_POST['customer_id']);
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
	if($_GET['action']=='editVehicle')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$result=updateVehicle($_POST["lid"],$_POST["file_id"],$_POST["model_id"],$_POST['vehicle_reg_no'],$_POST['vehicle_reg_date'],$_POST['vehicle_engine_no'],$_POST['vehicle_chasis_no'],$_POST['vehicle_type_id'],$_POST['model_year'],$_POST['condition'],$_POST['vehicle_company_id'],$_POST['vehicle_dealer_id'],$_POST['fitness_exp_date'],$_POST['permit_exp_date'],$_POST['vehicleProofId'],$_POST['vehicleProofNo'],$_FILES['vehicleProofImg'],$_POST['vehicleProofImg']);
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
				header("Location: ".$_SERVER['PHP_SELF']."?view=vehicleDetails&id=".$_POST["file_id"]);
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
require_once "../../../inc/template.php";
 ?>