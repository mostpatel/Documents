<?php
require_once "../../lib/cg.php";
require_once "../../lib/bd.php";
require_once "../../lib/hotel-location-functions.php";
require_once "../../lib/hotel-package-functions.php";


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
		$link="viewHotelPackage";
		}	
	else if($_GET['view']=='list')
	{
		$content="list.php";
		$link="viewHotelPackage";
		}	
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
		$link="viewHotelPackage";
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
				
				$result=insertHotelPackage($_POST["name"],$_POST['location_id'],$_POST['days'],$_POST['nights'],$_POST['stars'],$_FILES['thumb_img'],$_POST['tarriff']);
				
				if(is_numeric($result))
				{
				$_SESSION['ack']['msg']="Package successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/hotel_package/index.php?view=details&id=".$result);
				exit;
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
	if($_GET['action']=='addFeatured')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))
			{
				
				$result=insertFeaturedHotelPackage($_GET["id"]);
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Featured Package successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/hotel_package/index.php?view=list");
				exit;
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
	if($_GET['action']=='delFeatured')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,$admin_rights)))
			{
				
				$result=deleteFeaturedHotelPackage($_GET["id"]);
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Featured Package successfully Removed!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/hotel_package/index.php?view=list");
				exit;
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
	if($_GET['action']=='edit')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$result=updateHotelPackage($_POST['lid'],$_POST["name"],$_POST['location_id'],$_POST['days'],$_POST['nights'],$_POST['stars'],$_FILES['thumb_img'],$_POST['tarriff']);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Package updated Successfuly!";
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
	if($_GET['action']=='delete')
	{
		
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				$result=deleteHotelPackage($_GET["id"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Package Deleted Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
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
								
}

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="addHotelPackage";
if(isset($link))
$selectedLink=$link;
$jsArray=array("jquery.validate.js",'package.js');
$cssArray=array("jquery-ui.css");
require_once "../../inc/template.php";
 ?>