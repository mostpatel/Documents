<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/vehicle-model-functions.php";
require_once "../../../../lib/fuel-type-functions.php";
require_once "../../../../lib/vehicle-type-functions.php";
require_once "../../../../lib/vehicle-company-functions.php";
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
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$result=insertVehicleModel($_POST["name"],$_POST["vehicle_company_id"],$_POST["cubic_capacity"],$_POST["fuel_type_id"],$_POST["no_of_cylinders"],$_POST["seating_capacity"],$_POST["unladen_weight"],$_POST["gross_weight"],$_POST["axle_wt_fr"],$_POST["axle_wt_rr"],$_POST["no_tyres_fr"],$_POST["no_tyres_rr"],$_POST["tyre_type_fr"],$_POST["tyre_type_rr"],$_POST["vehicle_type_id"],$_POST["wheelbase"],$_POST["mrp"]);
				
				if($result!="error")
				{
				$_SESSION['ack']['msg']="Vehicle Model successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				}
				
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
	if($_GET['action']=='delete')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$result=deleteVehicleModel($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Vehicle Model deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				}
				else
				{
					$_SESSION['ack']['msg']="Cannot delete Vehicle Model! Vehicle Model already in use!";
				$_SESSION['ack']['type']=6; // 6 for inUse
				}
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
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$result=updateVehicleModel($_POST["lid"],$_POST["name"],$_POST["vehicle_company_id"],$_POST["cubic_capacity"],$_POST["fuel_type_id"],$_POST["no_of_cylinders"],$_POST["seating_capacity"],$_POST["unladen_weight"],$_POST["gross_weight"],$_POST["axle_wt_fr"],$_POST["axle_wt_rr"],$_POST["no_tyres_fr"],$_POST["no_tyres_rr"],$_POST["tyre_type_fr"],$_POST["tyre_type_rr"],$_POST["vehicle_type_id"],$_POST["wheelbase"],$_POST["mrp"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Vehicle Model updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".$_SERVER['PHP_SELF']);
				exit;
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					header("Location: ".$_SERVER['PHP_SELF']."?view=edit&lid=".$_POST["lid"]);
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
	}
?>

<?php
$selectedLink="settings";
$jsArray=array("jquery.validate.js","validators/vehicleModel.js");
$pathLinks=array("Home","Registration Form","Manage Locations");
require_once "../../../../inc/template.php";
 ?>