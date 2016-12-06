<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/common.php";
require_once "../../../lib/city-functions.php";
require_once "../../../lib/sub-category-functions.php";
require_once "../../../lib/category-functions.php";
require_once "../../../lib/super-category-functions.php";
require_once "../../../lib/customer-type-functions.php";
require_once "../../../lib/adminuser-functions.php";
require_once "../../../lib/lead-functions.php";
require_once "../../../lib/enquiry-functions.php";
require_once "../../../lib/follow-up-functions.php";
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/close-lead-functions.php";
require_once "../../../lib/decline-reasons-functions.php";
require_once "../../../lib/rel-subcat-enquiry-functions.php";
require_once "../../../lib/quantity-functions.php";
require_once "../../../lib/note-functions.php";
require_once "../../../lib/customer-extra-details-functions.php";
require_once "../../../lib/invoice-customer-functions.php";
require_once "../../../lib/invoice-functions.php";
require_once "../../../lib/invoice-rel-subcat-enquiry-functions.php";
require_once "../../../lib/customer-extra-details-functions.php";
require_once "../../../lib/vehicle-functions.php";
require_once "../../../lib/vehicle-company-functions.php";
require_once "../../../lib/vehicle-model-functions.php";
require_once "../../../lib/vehicle-cc-functions.php";
require_once "../../../lib/insurance-functions.php";
require_once "../../../lib/rel-attribute-functions.php";
require_once "../../../lib/profession-functions.php";
require_once "../../../lib/data-from-functions.php";
require_once "../../../lib/member-functions.php";
require_once "../../../lib/relations-function.php";
require_once "../../../lib/team-functions.php";
require_once "../../../lib/prefix-functions.php";
require_once "../../../lib/reminder-functions.php";
require_once "../../../lib/booking-form-functions.php";
require_once "../../../lib/customer-group-functions.php";
require_once "../../../lib/rel-customer-group-functions.php";
require_once "../../../lib/enquiry-group-functions.php";
require_once "../../../lib/rel-enquiry-group-functions.php";
require_once "../../../lib/customer-note-functions.php";
require_once "../../../lib/visit-functions.php";
require_once "../../../lib/follow-up-type-functions.php";
require_once "../../../lib/rel-subcat-supplier-functions.php";
require_once "../../../lib/supplier-functions.php";


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
	else if($_GET['view']=='editCloseLead')
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
				$enquiry_id=$_POST["enquiry_id"];
				$enquiry_id=clean_data($enquiry_id);
				
				$result=insertCloseLead($_POST["productStatus"], $_POST["decline_id"], $_POST["description"], $enquiry_id, $_POST["purchase_date"], $_POST["sms_status"]);
			
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Lead closed successfully!";
				$_SESSION['ack']['type']=1; // 1 for insert
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				}
				
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_id);
				exit;
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights! Contact Admin.";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_id);
					exit;
			}
		}
	if($_GET['action']=='delete')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$result=deleteCloseLead($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Lead Closing deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				}
				else
				{
					$_SESSION['ack']['msg']="Cannot delete this! It's already in use!";
				$_SESSION['ack']['type']=6; // 6 for inUse
				}
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_id);
				exit;
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights! Contact Admin.";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_id);
					exit;
			}
		}
	if($_GET['action']=='editCloseLead')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,	$admin_rights)))
			{
				$enquiry_id = $_POST["enquiry_id"];
				
				
				
				$result=updateCloseLead($_POST["productStatus"], $_POST["purchase_date"], $_POST["decline_id"], $_POST["description"], $_POST["enquiry_id"]);
				
				
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Lead Closing Details updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_id);
				exit;
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_id);
					exit;
				}
				
			}
			else
			{	
			        $enquiry_id = $_POST["enquiry_id"];
			
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights! Contact Admin.";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_id);
					exit;
			}
		}			
	}
?>

<?php
$selectedLink="newCustomer";
$jsArray=array("jquery.validate.js","customerDatePicker.js", "bootstrap-select.js", "generateOtherDetails.js");
$pathLinks=array("Home","Registration Form","Manage Locations");
require_once "../../../inc/template.php";
?>