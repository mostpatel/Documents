<?php
require_once "../../lib/cg.php";
require_once "../../lib/bd.php";
require_once "../../lib/common.php";
require_once "../../lib/location-functions.php";
require_once "../../lib/inquiry-functions.php";
require_once "../../lib/package-functions.php";
require_once "../../lib/package-type-functions.php";
require_once "../../lib/package-itenary-functions.php";

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
	
	if($_GET['action']=='generateReport')
	{
		
	
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))
			{
				if(isset($_POST['from']))
				{
				$from=$_POST['from'];
				}
				else
				$from=null;
				
				if(isset($_POST['to']))
				{
				$to=$_POST['to'];
				}
				else
				$to=null;	
				
				if(isset($_POST['location_type']))
				{
				$location_type=$_POST['location_type'];
				}
				else
				$location_type=-1;	
				
				
				
				$reportArray=listAirBookingInquiries($from,$to,$location_type);
		
				$_SESSION['cAirBookingReport']['emi_array']=$reportArray;
				
				$_SESSION['cAirBookingReport']['from']=$from;
				$_SESSION['cAirBookingReport']['to']=$to;
				$_SESSION['cAirBookingReport']['location_type']=$location_type;
			
			
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
	if($_GET['action']=='addFeatured')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))
			{
				
				$result=insertFeaturedPackage($_GET["id"]);
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Featured Package successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/package/index.php?view=list");
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
				
				$result=deleteFeaturedPackage($_GET["id"]);
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Featured Package successfully Removed!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/package/index.php?view=list");
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
				$result=updatePackage($_POST['lid'],$_POST["name"],$_POST['location'],$_POST['places'],$_POST['days'],$_POST['nights'],$_FILES['thumb_img'],$_POST['itenary_heading'],$_POST['itenary_description'],$_POST['package_types'],$_POST['tariff']);
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
				header("Location: ".$_SERVER['PHP_SELF']."?view=fileDetails&id=".$_POST["lid"]);
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
				
				$result=deletePackage($_GET["id"]);
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
$selectedLink="air";
if(isset($link))
$selectedLink=$link;
$jsArray=array("jquery.validate.js",'package.js','customerDatePicker.js');
$cssArray=array("jquery-ui.css");
require_once "../../inc/template.php";
 ?>