<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/customer-group-functions.php";

if(isset($_SESSION['EMSadminSession']['admin_rights']))
$admin_rights=$_SESSION['EMSadminSession']['admin_rights'];

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
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$result=insertCustomerGroup($_POST["cus_group_name"]);
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Customer Group successfully added!";
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
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$result=deleteCustomerGroup($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Customer Group deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				}
				else
				{
				$_SESSION['ack']['msg']="Cannot delete Customer Group! Customer group already in use!";
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
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,$admin_rights)))
			{
				
				$result=updateCustomerGroup($_POST["lid"], $_POST["cus_group_name"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Customer group Name updated Successfuly!";
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
$jsArray=array("jquery.validate.js");
require_once "../../../../inc/template.php";
 ?>