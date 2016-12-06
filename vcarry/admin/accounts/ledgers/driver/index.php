<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/account-ledger-functions.php";
require_once "../../../../lib/account-head-functions.php";
require_once "../../../../lib/account-period-functions.php";
require_once "../../../../lib/city-functions.php";
require_once "../../../../lib/area-functions.php";
require_once "../../../../lib/driver-functions.php";
require_once "../../../../lib/vehicle-type-functions.php";
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
		
		$result=insertDriver($_POST["name"],$_POST["contact_no_1"], $_POST["contact_no_2"], $_POST["vehicle_type_id"], $_POST["area_id"], $_POST['type'], $_POST["fixed_amount"], $_POST["share_expense"],$_POST['email'],$_POST['multi_trip']);
		if($result!="error")
				{
				$_SESSION['ack']['msg']="Driver successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				}
				
				header("Location: index.php");
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
				$result=deleteDriver($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Driver deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				}
				else
				{
				$_SESSION['ack']['msg']="Cannot delete Driver! Driver already in use!";
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
				
				$result=updateDriver($_POST['id'],$_POST["name"],$_POST["contact_no_1"], $_POST["contact_no_2"], $_POST["vehicle_type_id"], $_POST["area_id"], $_POST['type'], $_POST["fixed_amount"], $_POST["share_expense"],$_POST['email']);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Driver updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".$_SERVER['PHP_SELF'].'?view=details&lid='.$_POST['lid']);
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
$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="accounts";
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","validators/ledger.js");
$cssArray=array("jquery-ui.css");
require_once "../../../../inc/template.php";
 ?>