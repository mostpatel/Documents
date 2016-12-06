<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/common.php";
require_once "../../../lib/city-functions.php";
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/our-company-function.php";
require_once "../../../lib/lr-functions.php";
require_once "../../../lib/trip-memo-functions.php";
require_once "../../../lib/tax-functions.php";
require_once "../../../lib/product-functions.php";
require_once "../../../lib/packing-unit-functions.php";
require_once "../../../lib/account-ledger-functions.php";
require_once "../../../lib/branch-counter-function.php";


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
		else if($_GET['view']=='trip_memo')
	{
		$showTitle=false;
		$content="dalabhai_trip_memo.php";
		}
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
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
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (((in_array(2,$admin_rights) || in_array(7,$admin_rights) ) && SLAVE==1) || (in_array(11,$admin_rights) && SLAVE==0)))
			{
				
				$result=insertTripMemo($_POST["trip_memo_date"],$_POST['trip_memo_no'],$_POST['from_branch_ledger_id'],$_POST['to_branch_ledger_id'],$_POST['selectTR'],$_POST['truck_no'],$_POST['driver_id'],$_POST['remarks'],$_POST['select_lr_option']);
				
				if(is_numeric($result))
				{
				$_SESSION['ack']['msg']="Trip Memo successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/transportation/trip_memo/index.php?view=trip_memo&id=".$result);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/transportation/trip_memo/index.php");
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
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (((in_array(4,$admin_rights) || in_array(7,					$admin_rights) ) && SLAVE==1) || (in_array(11,$admin_rights) && SLAVE==0)))
			{	
			
				$result=deleteTrip($_GET["id"]);
				
				if($result!="error")
				{
				$_SESSION['ack']['msg']="Trip Memo deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				}
				else
				{
					$_SESSION['ack']['msg']="Trip Memo Already In Use!";
				$_SESSION['ack']['type']=4; // 4 for error
				}
				
				header("Location: ".WEB_ROOT."admin/transportation/trip_memo/index.php?view=details&id=".$_GET['id']);
				exit;
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/transportation/trip_memo/index.php?view=details&id=".$customer_id);
			exit;
			}
		}
	if($_GET['action']=='edit')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (((in_array(3,$admin_rights) || in_array(7,					$admin_rights) ) && SLAVE==1) || (in_array(11,$admin_rights) && SLAVE==0)))
			{
				
				$result=updateTripMemo($_POST['trip_memo_id'],$_POST["trip_memo_date"],$_POST['trip_memo_no'],$_POST['from_branch_ledger_id'],$_POST['to_branch_ledger_id'],$_POST['selectTR'],$_POST['truck_no'],$_POST['driver_id'],$_POST['remarks']);
				
				if($result=="success" && is_numeric($_POST["trip_memo_id"]))
				{
				$_SESSION['ack']['msg']="Trip Memo successfully updated!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/transportation/trip_memo/index.php?view=details&id=".$_POST["trip_memo_id"]);
				exit;
				}
				else if($result=="invoice_error")
				{
				$_SESSION['ack']['msg']="Invoice Already Generated! Delete Invoice To Make Any Changes";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/transportation/trip_memo/index.php?view=details&id=".$_POST['trip_memo_id']);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/transportation/trip_memo/index.php?view=details&id=".$_POST['trip_memo_id']);
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
$selectedLink="newMemo";
$jsArray=array("jquery.validate.js","scanProof.js","dropDown.js","checkAvailability.js","jquery-ui/js/jquery-ui.min.js","customerDatePicker.js","addVehicleProof.js","generateProofimgVehicle.js","validators/addNewVehicle.js");
$cssArray=array("jquery-ui.css");
require_once "../../../inc/template.php";
 ?>