<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/vehicle-functions.php";
require_once "../../../lib/godown-functions.php";
require_once "../../../lib/vehicle-purchase-functions.php";
require_once "../../../lib/vehicle-model-functions.php";
require_once "../../../lib/vehicle-color-functions.php";
require_once "../../../lib/tax-functions.php";
require_once "../../../lib/currencyToWords.php";
require_once "../../../lib/account-ledger-functions.php";
require_once "../../../lib/account-period-functions.php";
require_once "../../../lib/account-purchase-functions.php";

if(isset($_SESSION['edmsAdminSession']['admin_rights']))
$admin_rights=$_SESSION['edmsAdminSession']['admin_rights'];

if(isset($_GET['view']))
{
	if($_GET['view']=='add')
	{
		$content="list_add.php";
	}
	if($_GET['view']=='list')
	{
		$content="list.php";
	}
	else if($_GET['view']=='details')
	{
		$content="details.php";
		$showTitle=false; // to turn off the company title on the top of the page
		}
	else if($_GET['view']=='search')
	{
		$content="search.php";
		
		}	
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
		}	
	else if($_GET['view']=='addMultiple')
	{
		$content="add_multiple.php";
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
				$result=AddOpeningBalanceToModel($_POST['model_id'],$_POST['vehicle_engine_no'],$_POST['vehicle_chasis_no'],$_POST['vehicle_color_id'],$_POST['model_year'],$_POST['basic_price'],$_POST['cng_cylinder_no'],$_POST['cng_kit_no'],$_POST['godown_id']); // $cheque_return is 0 when inserting a payment			
				
				if($result=="success")
				{
					$_SESSION['ack']['msg']="Opening Balance successfully added!";
					$_SESSION['ack']['type']=1; // 1 for insert
					header("Location: ".WEB_ROOT."admin/settings/vehicle_settings/model_settings/index.php?view=details&id=".$_POST['model_id']);
					exit;
				}
				else if($result=="date_error")
				{
				$_SESSION['ack']['msg']="Date Should be greater than Books starting date!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/purchase/index.php");
				exit;
				}
				else
				{
				$_SESSION['ack']['msg']="Invalid Input!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/purchase/index.php");
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/purchase/index.php");
				exit;
			}
		}	
	if($_GET['action']=='delete')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$result=deleteVehicleFromOpeningVehicles($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Vehicle deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				header("Location: ".WEB_ROOT."admin/purchase/openingVehicle/index.php?view=details&id=".$_GET['model_id']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/purchase/openingVehicle/index.php?view=details&id=".$_GET['model_id']);
				exit;
			}
		}
	if($_GET['action']=='edit')
	{
		
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
								
				$result=updateOpeningBalanceToModel($_POST['model_id'],$_POST['vehicle_engine_no'],$_POST['vehicle_chasis_no'],$_POST['vehicle_color_id'],$_POST['model_year'],$_POST['basic_price'],$_POST['cng_cylinder_no'],$_POST['cng_kit_no'],$_POST['godown_id'],$_POST['vehicle_id'],$_POST['condition'],$_POST['vehicle_reg_no'],$_POST['service_book_no']); 
				if($result=="success")
				{	
				$_SESSION['ack']['msg']="Purchase updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".WEB_ROOT."admin/purchase/openingVehicle/index.php?view=details&id=".$_POST['lid']);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/purchase/openingVehicle/index.php?id=".$_POST['lid']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/payment/index.php?view=details&id=".$_POST['lid']);
				exit;
			}
			
	}
	
	}
?>

<?php

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="purchaseVehicle";
if(isset($link))
$selectedLink=$link;
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","addInsuranceProof.js","customerDatePicker.js","purchase_vehicle.js");
$cssArray=array("jquery-ui.css");

require_once "../../../inc/template.php";
 ?>