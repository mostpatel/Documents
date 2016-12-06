<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/city-functions.php";
require_once "../../../../lib/status-functions.php";
require_once "../../../../lib/fin-enquiry-functions.php";





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
	}
	
	
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
	}
		
	
	else if($_GET['view']=='editEnquiry')
	{
		$content="editEnquiry.php";
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
		
		
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{   
			
				$result=insertFinEnquiry($_POST["enquiry_date"], $_POST["customer_name"], $_POST["phone_no_primary"], $_POST["phone_no_secondary"], $_POST["address"], $_POST["status_id"],$_POST['note']);
		
				if($result=="success")
				{
				$_SESSION['ack']['msg']="New Enquiry successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".$_SERVER['PHP_SELF']);
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
	if($_GET['action']=='deleteEnquiry')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$result=deleteEnquiry($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Enquiry deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
			}
				else
				{
					$_SESSION['ack']['msg']="Cannot delete Enquiry! Enquiry already in use!";
				$_SESSION['ack']['type']=6; // 6 for inUse
				}
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$_GET["state"]);
				exit;
	            }
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights! Contact Admin.";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET["lid"]);
					exit;
			}
		}
		
		
		
		if($_GET['action']=='editEnquiry')
	{
		
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
		
		
			{
				
				
				
	$result=updateEnquiry($_POST["lid"], $_POST["discussion"],$_POST["enquiryType"], $_POST["follow_up_date"], $_POST["budget"]);
				
				if($result=="success")
				{
				
				$_SESSION['ack']['msg']="Enquiry Details updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_POST["lid"]);
				exit;
			}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_POST["lid"]);
					exit;
				}
				
	}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!Contact Admin.";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_POST["lid"]);
					exit;
			}
		}	
}
?>

<?php
$selectedLink="enquiry";
$jsArray=array("jquery.validate.js","validators/newCustomer.js", "customerDatePicker.js", "generateContactNoCustomer.js",'attributeDropDown.js','bootstrap-select.js','addCustomerProof.js','generateProofimgCustomer.js', 'jquery.timepicker.js', 'enquiryTypeRefrence.js');
$pathLinks=array("Home","Registration Form","Manage Locations");

$cssArray=array("bootstrap-select.css", "bp.css", "jquery-ui.css", "jquery.timepicker.css");
require_once "../../../../inc/template.php";
?>