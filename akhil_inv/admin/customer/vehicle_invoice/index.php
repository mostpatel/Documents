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
require_once "../../../lib/vehicle-invoice-functions.php";
require_once "../../../lib/vehicle-company-functions.php";
require_once "../../../lib/vehicle-type-functions.php";
require_once "../../../lib/vehicle-sales-functions.php";
require_once "../../../lib/vehicle-insurance-functions.php";
require_once "../../../lib/delivery-challan-functions.php";
require_once "../../../lib/battery-make-functions.php";
require_once "../../../lib/insurance-company-functions.php";
require_once "../../../lib/account-ledger-functions.php";
require_once "../../../lib/account-sales-functions.php";
require_once "../../../lib/purchase-sales-jv-functions.php";
require_once "../../../lib/currencyToWords.php";
require_once "../../../lib/vehicle-purchase-functions.php";

if(isset($_SESSION['edmsAdminSession']['admin_rights']))
$admin_rights=$_SESSION['edmsAdminSession']['admin_rights'];

if(isset($_GET['view']))
{
	if($_GET['view']=='add')
	{
		$content="list_add.php";
	}
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
	}
	else if($_GET['view']=='details')
	{
		$showTitle = false;
		$content="details.php";
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
				$ledger_id_array=getLedgerIdsArrayForAutoCompleteName($_POST['ledger_name']);	
				if(checkForNumeric($_POST['loan_amount']) && $_POST['loan_amount']>0 && validateForNull($_POST['ledger_name']) && in_array($_POST["ledger_id"],$ledger_id_array))	// valid loan amount check for valid ledger
				{				
				
				$result=insertVehicleSale($_POST['sale_date'],$_POST['to_ledger'],$_POST['from_ledger'],$_POST['remarks'],$_POST['delivery_challan_id'],$_POST['amount'],$_POST['tax_group_id'],$_POST['invoice_no'],$_POST['retail_tax'],$_POST['sales_jvs'],$_POST['loan_amount'],$_POST['ledger_id'],$_POST['exchange'],$_POST['to_ledger_id'],$_POST['from_ledger_id'],$_POST['remarks'],$_POST['model_id'],$_POST['vehicle_engine_no'],$_POST['vehicle_chasis_no'],$_POST['vehicle_color_id'],$_POST['model_year'],$_POST['basic_price'],$_POST['cng_cylinder_no'],$_POST['cng_kit_no'],$_POST['godown_id'],$_POST['tax_group_id'],$_POST['service_book_no'],$_POST['condition'],$_POST['vehicle_reg_no']);
				}
				else if(!checkForNumeric($_POST['loan_amount']) || $_POST['loan_amount']<=0) // no loan amount
				{
				$result=insertVehicleSale($_POST['sale_date'],$_POST['to_ledger'],$_POST['from_ledger'],$_POST['remarks'],$_POST['delivery_challan_id'],$_POST['amount'],$_POST['tax_group_id'],$_POST['invoice_no'],$_POST['retail_tax'],$_POST['sales_jvs'],$_POST['loan_amount'],$_POST['ledger_id'],$_POST['exchange'],$_POST['to_ledger_id'],$_POST['from_ledger_id'],$_POST['remarks'],$_POST['model_id'],$_POST['vehicle_engine_no'],$_POST['vehicle_chasis_no'],$_POST['vehicle_color_id'],$_POST['model_year'],$_POST['basic_price'],$_POST['cng_cylinder_no'],$_POST['cng_kit_no'],$_POST['godown_id'],$_POST['tax_group_id'],$_POST['service_book_no'],$_POST['condition'],$_POST['vehicle_reg_no']);
				}
				else $result="error";
				
				
				if(is_numeric($result))
				{
				
				
					
				$_SESSION['ack']['msg']="Vehicle Invoice successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/customer/vehicle_invoice/index.php?view=details&id=".$_POST['delivery_challan_id']);
				exit;
				}
				else{
				$customer_id=getCustomerIdFromDeliveryChallan($_POST['delivery_challan_id']);	
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$customer_id);
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
				$customer_id=getCustomerIdFromVehicleInvoice($_GET["id"]);	
				deleteVehicleSale($_GET["id"]);
				
				$_SESSION['ack']['msg']="Invoice deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$customer_id);
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
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$ledger_id_array=getLedgerIdsArrayForAutoCompleteName($_POST['ledger_name']);	
				if(checkForNumeric($_POST['loan_amount']) && $_POST['loan_amount']>0 && validateForNull($_POST['ledger_name']) && in_array($_POST["ledger_id"],$ledger_id_array))	// valid loan amount check for valid ledger
				{
					
				updateVehicleSale($_POST["sale_date"],$_POST['to_ledger'],$_POST['from_ledger'],$_POST['remarks'],$_POST['delivery_challan_id'],$_POST['amount'],$_POST['tax_group_id'],$_POST['invoice_no'],$_POST['retail_tax'],$_POST['sales_jvs'],$_POST['loan_amount'],$_POST["ledger_id"],$_POST['exchange'],$_POST['to_ledger_id'],$_POST['from_ledger_id'],$_POST['remarks'],$_POST['model_id'],$_POST['vehicle_engine_no'],$_POST['vehicle_chasis_no'],$_POST['vehicle_color_id'],$_POST['model_year'],$_POST['basic_price'],$_POST['cng_cylinder_no'],$_POST['cng_kit_no'],$_POST['godown_id'],$_POST['tax_group_id'],$_POST['service_book_no'],$_POST['condition'],$_POST['vehicle_reg_no']);
				}
				else if(!checkForNumeric($_POST['loan_amount']) || $_POST['loan_amount']<=0) // no loan amount
				{
					
				updateVehicleSale($_POST["sale_date"],$_POST['to_ledger'],$_POST['from_ledger'],$_POST['remarks'],$_POST['delivery_challan_id'],$_POST['amount'],$_POST['tax_group_id'],$_POST['invoice_no'],$_POST['retail_tax'],$_POST['sales_jvs'],$_POST['loan_amount'],$_POST["ledger_id"],$_POST['exchange'],$_POST['to_ledger_id'],$_POST['from_ledger_id'],$_POST['remarks'],$_POST['model_id'],$_POST['vehicle_engine_no'],$_POST['vehicle_chasis_no'],$_POST['vehicle_color_id'],$_POST['model_year'],$_POST['basic_price'],$_POST['cng_cylinder_no'],$_POST['cng_kit_no'],$_POST['godown_id'],$_POST['tax_group_id'],$_POST['service_book_no'],$_POST['condition'],$_POST['vehicle_reg_no']);
				}
				
				$_SESSION['ack']['msg']="Invoice updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				
			header("Location: ".WEB_ROOT."admin/customer/vehicle_invoice/index.php?view=details&id=".$_POST['delivery_challan_id']);
				exit;
				}
				else{
				$customer_id=getCustomerIdFromDeliveryChallan($_POST['delivery_challan_id']);	
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/index.php?id=".$customer_id);
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
$jsArray=array("jquery.validate.js","scanProof.js","dropDown.js","checkAvailability.js","jquery-ui/js/jquery-ui.min.js","customerDatePicker.js","addVehicleProof.js","generateProofimgVehicle.js");
$cssArray=array("jquery-ui.css");
require_once "../../../inc/template.php";
 ?>