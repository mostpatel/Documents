<?php
require_once "../../lib/cg.php";
require_once "../../lib/bd.php";
require_once "../../lib/common.php";
require_once "../../lib/city-functions.php";
require_once "../../lib/sub-category-functions.php";
require_once "../../lib/category-functions.php";
require_once "../../lib/super-category-functions.php";
require_once "../../lib/customer-type-functions.php";
require_once "../../lib/adminuser-functions.php";
require_once "../../lib/lead-functions.php";
require_once "../../lib/enquiry-functions.php";
require_once "../../lib/follow-up-functions.php";
require_once "../../lib/customer-functions.php";
require_once "../../lib/close-lead-functions.php";
require_once "../../lib/decline-reasons-functions.php";
require_once "../../lib/rel-subcat-enquiry-functions.php";
require_once "../../lib/quantity-functions.php";
require_once "../../lib/note-functions.php";
require_once "../../lib/customer-extra-details-functions.php";
require_once "../../lib/invoice-customer-functions.php";
require_once "../../lib/invoice-functions.php";
require_once "../../lib/invoice-rel-subcat-enquiry-functions.php";
require_once "../../lib/customer-extra-details-functions.php";
require_once "../../lib/vehicle-functions.php";
require_once "../../lib/vehicle-company-functions.php";
require_once "../../lib/vehicle-model-functions.php";
require_once "../../lib/vehicle-cc-functions.php";
require_once "../../lib/insurance-functions.php";
require_once "../../lib/prefix-functions.php";






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
	else if($_GET['view']=='customerDetails')
	
	{
		
		$content="customerDetails.php";
	}
	
	
	
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
		}
		
	else if($_GET['view']=='editCustomer')
	{
		$content="editCustomer.php";
	}
	
	
	
	else if($_GET['view']=='editFollwUpDetails')
	{
		$content="editFollowUp.php";
	}
	
	else if($_GET['view']=='editExtraCustomerDetails')
	{
		$content="editExtraCustomerDetails.php";
	}
	
	else if($_GET['view']=='editCustomerFromCustomerProfile')
	{
		$content="editCustomerFromCustomerProfile.php";
	}
		
	else if($_GET['view']=='editProducts')
	{
		$content="editProducts.php";
		}
		
	else if($_GET['view']=='editNote')
	{
		$content="editNote.php";
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
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{   
			   
			               
		$result=insertCustomer($_POST["customer_name"], $_POST["email_id"], $_POST["mobile_no"], $_POST["prefix_id"]);
				
				if(is_numeric($result))
				{
				$_SESSION['ack']['msg']="New Customer successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$result);
				exit;
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
		
		if($_GET['action']=='editEnquiry')
	{
		
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
		
		
			{
				
				
				
	$result=updateEnquiry($_POST["lid"], $_POST["discussion"],$_POST["enquiryType"], $_POST["follow_up_date"]);
				
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
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".$_SERVER['PHP_SELF']);
					exit;
			}
		}	
		
		
		if($_GET['action']=='editFollowUp')
	{
		
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
		
		
			{
				
				
				
	$result=updateFollowUp($_POST["lid"], $_POST["discussion"], $_POST["follow_up_date"]);
				
				if($result=="success")
				{
				
				$_SESSION['ack']['msg']="Follow Up updated Successfuly!";
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
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".$_SERVER['PHP_SELF']);
					exit;
			}
		}	
		
		
		
		if($_GET['action']=='editNote')
	{
		
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
		
		
			{
				
				
			$result=updateNote($_POST["lid"], $_POST["note"]);
				
				if($result=="success")
				{
				
				$_SESSION['ack']['msg']="Note updated Successfuly!";
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
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".$_SERVER['PHP_SELF']);
					exit;
			}
		}	
		
		
		if($_GET['action']=='deleteNote')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
			 
			  $lid = $_GET["lid"];
			  
			 
			  
				$result=deleteNote($_GET["id"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Note deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				}
				else
				{
					$_SESSION['ack']['msg']="Cannot delete Note! Note already in use!";
				$_SESSION['ack']['type']=6; // 6 for inUse
				}
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$lid);
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
		
		if($_GET['action']=='deleteLeadClose')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
			 
			  $lid = $_GET["lid"];
			  
			 
			  
				$result=deleteCloseLead($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Lead Closing deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				}
				
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$lid);
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
		
		if($_GET['action']=='editProducts')
	{
		
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
		
		
			{
				
				
				
				$result=updateRelSubCatDetails($_POST["lid"], $_POST["product"],$_POST["mrp"], $_POST["quantity_id"]);
				
				if($result=="success")
				{
				
				$_SESSION['ack']['msg']="Product Details updated Successfuly!";
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
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".$_SERVER['PHP_SELF']);
					exit;
			}
		}	
		
		
		
	if($_GET['action']=='editCustomer')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$enquiry_id=$_POST['enquiry_id'];
				$enquiry_id=clean_data($enquiry_id);
				$result=updateCustomer($_POST["lid"],$_POST["name"],$_POST["email"], $_POST["customerContact"] );
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Customer updated Successfuly!";
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
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".$_SERVER['PHP_SELF']);
					exit;
			}
		}	
		

if($_GET['action']=='editCustomerFromCustomerProfile')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$customer_id=$_POST['lid'];
				$customer_id=clean_data($customer_id);
				
				$result=updateExtraCustomerDetails($_POST["lid"],$_POST["dob"],$_POST["address"], $_POST["city"] );
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Customer Details updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$customer_id);
				exit;
			}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$customer_id);
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
$selectedLink="customer";
$jsArray=array("jquery.validate.js","validators/newCustomer.js", "customerDatePicker.js", "generateContactNoCustomer.js");
$pathLinks=array("Home","Registration Form","Manage Locations");
$cssArray=array("adminMain.css");
require_once "../../inc/template.php";
 ?>