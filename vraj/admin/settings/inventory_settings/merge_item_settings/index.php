<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/item-type-functions.php";
require_once "../../../../lib/godown-functions.php";
require_once "../../../../lib/item-unit-functions.php";
require_once "../../../../lib/inventory-item-functions.php";
require_once "../../../../lib/item-manufacturer-functions.php";
require_once("../../../../lib/our-company-function.php");

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
	else if($_GET['view']=='all')
	{
		$content="allParts.php";
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
				
				$result=mergeMultipleItemsIntoOne($_POST["item_ids"],$_POST["new_item_id"]);
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Item successfully merged!";
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
				$result=deleteInventoryItem($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Item deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				}
				else
				{
					$_SESSION['ack']['msg']="Cannot delete Item! Item already in use!";
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
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,$admin_rights)))
			{
				
				$result=updateInventoryItem($_POST["lid"],$_POST["name"],$_POST["alias"],$_POST["item_code"],$_POST["item_unit_id"],$_POST["manufacturer_id"],$_POST["mfg_item_code"],$_POST["dealer_price"],$_POST["mrp"],$_POST["opening_quantity"],$_POST["opening_rate"],$_POST["remarks"],$_POST["item_type_id"],$_POST["min_quantity"],$_POST["godown_id"],$_POST["tax_group_id"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Item updated Successfuly!";
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

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="settings";
$jsArray=array("jquery.validate.js","validators/vehicleTypes.js");
require_once "../../../../inc/template.php";
 ?>