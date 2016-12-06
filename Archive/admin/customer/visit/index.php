<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/common.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/visit-functions.php";
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/enquiry-functions.php";
require_once "../../../lib/enquiry-group-functions.php";
require_once "../../../lib/rel-enquiry-group-functions.php";

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
				$enquiry_form_id=$_POST["enquiry_id"];
				$enquiry_form_id=clean_data($enquiry_form_id);
				
				
				
			$result=insertVisit($enquiry_form_id, $_POST["visitDiscussion"], $_POST["visit_date"], $_POST["sms_status"]);
			
			
			    $enquiryDetails = getEnquiryById($enquiry_form_id);
				$customer_id = $enquiryDetails['customer_id'];
			    
				
				$admin_id=$_SESSION['EMSadminSession']['admin_id'];
				$adminDetails = getAdminUserByID($admin_id);
				$admin_name = $adminDetails['admin_name'];
				$admin_email = $adminDetails['admin_email'];
				$admin_number = $adminDetails['admin_phone'];
				
			
				$customer = getCustomerById($customer_id);
				
				
				$prefix_id = $customer['prefix_id'];
				$prefixDetails = getPrefixById($prefix_id);
				$customer_prefix = $prefixDetails['prefix'];
				$contact_nos = getCustomerContactNo($customer_id);
	            
				
				$result=insertRelEnquiryGroup($enquiry_form_id, array(1));
				
				
				
				foreach($contact_nos as $contact_no)
				{
				
				if(checkForNumeric($contact_no[0]) && strlen($contact_no[0])==10)
					{
				 sendNewLeadSMS($customer_prefix." ".$customer['customer_name'], $contact_no[0], $admin_name, $admin_number, $admin_email, $type=6);
				    }
				}
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Visit successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				}
				
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_form_id);
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
				$result=deleteVisit($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Visit deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				}
				
				
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights! Contact Admin.";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET["state"]);
					exit;
			}
			
			header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET["state"]);
			
				exit;
		}
				
	}
?>

<?php
$selectedLink="newCustomer";
$jsArray=array("jquery.validate.js","validators/addFollowUp.js", "customerDatePicker.js");
$pathLinks=array("Home","Registration Form","Manage Locations");
require_once "../../../inc/template.php";
 ?>