<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/sub-category-functions.php";
require_once "../../../../lib/super-category-functions.php";
require_once "../../../../lib/category-functions.php";

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
				
				
		$result=insertSubCategory($_POST["name"], $_FILES['subCat_img'], $_FILES['spec_sheet'], $_POST["youtube_link"], $_POST["sub_cat_description"], $_POST["sub_cat_other_description"], $_POST["sub_cat_tabular_description"], $_POST['cat_id']);
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Sub Category successfully added!";
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
				$result=deleteSubCategory($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Sub Category deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				}
				else
				{
					$_SESSION['ack']['msg']="Cannot delete Sub Category! Sub Category already in use!";
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
		
		if($_GET['action']=='deleteImage')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$result=deleteImageOfASubCategory($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Sub Category Image deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				}
				else
				{
				$_SESSION['ack']['msg']="Cannot delete Sub Category Image!";
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
		
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				
				$result=updateSubCategory($_POST["lid"], $_POST["name"], $_FILES['subCat_img'], $_FILES['spec_sheet'], $_POST["youtube_link"], $_POST["sub_cat_description"], $_POST["sub_cat_other_description"], $_POST["sub_cat_tabular_description"], $_POST['cat_id']);
				
				if($result=="success")
				{
					
				$_SESSION['ack']['msg']="Sub Category updated Successfuly!";
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
$jsArray=array("jquery.validate.js","validators/vehicleCompanies.js");
$pathLinks=array("Home","Registration Form","Manage Locations");
require_once "../../../../inc/template.php";
 ?>