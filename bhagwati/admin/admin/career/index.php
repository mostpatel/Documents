<?php
require_once "../../lib/cg.php";
require_once "../../lib/bd.php";
require_once "../../lib/common.php";
require_once "../../lib/location-functions.php";
require_once "../../lib/inquiry-functions.php";
require_once "../../lib/package-functions.php";
require_once "../../lib/package-type-functions.php"; 
require_once "../../lib/package-itenary-functions.php";
require_once "../../lib/careers-functions.php";

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
		$link="viewPackage";
		}	
	else if($_GET['view']=='list')
	{
		$content="list.php";
		$link="viewPackage";
		}	
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
		$link="viewPackage";
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
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$result = insertCareer ($_POST["name"],$_POST['qualification'],$_POST['description'],$_POST['gender'],$_POST['no']);
				if($result=="success")
				{
				$_SESSION['ack']['msg']=" Career Added Successfuly!";
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
	if($_GET['action']=='delete')
	{
		
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				$result=deleteCareer($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Career Deleted Successfuly!";
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
$selectedLink="career";
if(isset($link))
$selectedLink=$link;
$jsArray=array("jquery.validate.js",'package.js','customerDatePicker.js');
$cssArray=array("jquery-ui.css");
require_once "../../inc/template.php";
 ?>