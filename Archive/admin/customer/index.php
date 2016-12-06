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
require_once "../../lib/rel-attribute-functions.php";
require_once "../../lib/profession-functions.php";
require_once "../../lib/data-from-functions.php";
require_once "../../lib/member-functions.php";
require_once "../../lib/relations-function.php";
require_once "../../lib/team-functions.php";
require_once "../../lib/prefix-functions.php";
require_once "../../lib/reminder-functions.php";
require_once "../../lib/booking-form-functions.php";
require_once "../../lib/customer-group-functions.php";
require_once "../../lib/rel-customer-group-functions.php";
require_once "../../lib/enquiry-group-functions.php";
require_once "../../lib/rel-enquiry-group-functions.php";
require_once "../../lib/customer-note-functions.php";
require_once "../../lib/visit-functions.php";
require_once "../../lib/follow-up-type-functions.php";
require_once "../../lib/product-unit-functions.php";



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
	else if($_GET['view']=='addProof')
	
	{
		
		$content="addCustomerProof.php";
	}
	
	
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
	}
		
	else if($_GET['view']=='editCustomer')
	{
		$content="editCustomer.php";
	}
	
	else if($_GET['view']=='addRemainder')
	{
		$content="addRemainder.php";
		
	}
	
	else if($_GET['view']=='editRemainder')
	{
		
		$content="remainderEdit.php";
		
	}	
	
	else if($_GET['view']=='editFollwUpDetails')
	{
		$content="editFollowUp.php";
	}
	
	else if($_GET['view']=='editExtraCustomerDetails')
	{
		$content="editExtraCustomerDetails.php";
	}
	
	else if($_GET['view']=='addToGroup')
	{
		$content="addToCustomerGroup.php";
	}
	
	else if($_GET['view']=='addToEnquiryGroup')
	{
		$content="addToEnquiryGroup.php";
	}
	
	
	else if($_GET['view']=='editMemberDetails')
	{
		$content="editMemberDetails.php";
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
	else if($_GET['view']=='editCustomerNote')
	{
		$content="editCustomerNote.php";
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
			
				if(SHOW_PREFIX == 0)
				{
					$_POST["prefix_id"] = 1;
				}

			
				$result=insertLead($_POST["prefix_id"], $_POST["customer_name"], $_POST["product_id"], $_POST["mrp"], $_POST["unit_id"], $_POST["quantity_id"],$_POST['attribute_name_array'], $_POST["mobile_no"], $_POST["email_id"], $_POST["discussion"], $_POST["customer_type_id"], $_POST["refrence"], $_POST["reminder_date"]. " ".$_POST["reminder_time"], $_POST['enquiry_date'], $_POST["budget"],$_POST["customer_id"], $_POST["city"], $_POST["customer_area"], $_POST["km"], $_POST["sms_status"], $_POST['enquiry_group_id']);
		
				if(is_numeric($result) && is_numeric($_POST["customer_id"]))
				{
				$_SESSION['ack']['msg']="New Enquiry successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$result);
				exit;
				}
				else if(is_numeric($result))
				{
				$_SESSION['ack']['msg']="New Customer successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$result);
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
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
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
		
		if($_GET['action']=='deleteCustomer')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$result=deleteCustomer($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Customer deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				header("Location: ".$_SERVER['PHP_SELF']);
				exit;
			}
				else
				{
					$_SESSION['ack']['msg']="Cannot delete Customer! Customer already in use! Delete related Enquiries first.";
				$_SESSION['ack']['type']=6; // 6 for inUse
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$_GET["lid"]);
				exit;
				}
				
	            }
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights! Contact Admin.";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$_GET["lid"]);
					exit;
			}
		}
		
		
	if($_GET['action']=='deleteMember')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$result=deleteMember($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Member deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
			}
				else
				{
					$_SESSION['ack']['msg']="Cannot delete Member! Member already in use!";
				$_SESSION['ack']['type']=6; // 6 for inUse
				}
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$_GET["state"]);
				exit;
	            }
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights! Contact Admin.";
					$_SESSION['ack']['type']=5; // 5 for access
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$_GET["state"]);
					exit;
			}
		}
		
		
		if($_GET['action']=='deleteExtraCustomerDetails')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
		{	
				$result=deletetExtraCustomerDetails($_GET["lid"]);
				
			  if($result=="success")
		  {
				$_SESSION['ack']['msg']="Extra Details deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
			}
				else
				{
					$_SESSION['ack']['msg']="Cannot delete Extra Details!  Already in use!";
				$_SESSION['ack']['type']=6; // 6 for inUse
				}
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$_GET["lid"]);
				exit;
	            }
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights! Contact Admin.";
					$_SESSION['ack']['type']=5; // 5 for access
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$_GET["lid"]);
					exit;
			}
		}
		
		
		if($_GET['action']=='editEnquiry')
	{
		
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
		
		
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
		
		
		if($_GET['action']=='editFollowUp')
	{
		
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
		
		
			{
				
				
				
	$result=updateFollowUp($_POST["id"], $_POST["discussion"], $_POST["follow_up_date"]);
				
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
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights! Contact Admin.";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_POST["lid"]);
					exit;
			}
		}	
		
		
		
		if($_GET['action']=='editNote')
	{
		
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
		
		
			{
				
				
			$result=updateNote($_POST["id"], $_POST["note"]);
				
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
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights! Contact Admin";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_POST["lid"]);
					exit;
			}
		}	
		
		
		
		if($_GET['action']=='editCustomerNote')
	{
		
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
		
		
			{
				
				
			$result=updateCustomerNote($_POST["id"], $_POST["note"]);
				
				if($result=="success")
				{
				
				$_SESSION['ack']['msg']="Note updated Successfully!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$_POST["lid"]);
				 
				exit;
			}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$_POST["lid"]);
					exit;
				}
				
	}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights! Contact Admin";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$_POST["lid"]);
					exit;
			}
		}	
		
		
		
		if($_GET['action']=='addRemainder')
		{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$result=addRemainder($_POST["lid"],$_POST['remainderDate'],$_POST['remarks']);
	
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Reminder Added Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
					}
				header("Location: ".$_SERVER['PHP_SELF']."?view=addRemainder&id=".$_POST["lid"]);
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
		
		
		if($_GET['action']=='addToCustomerGroup')
		{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$result=updateRelCustomerGroup($_POST["customer_id"],$_POST['customer_group_id']);
	
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Customer has been Added To the Group Successfully!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
				}
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$_POST["customer_id"]);
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
		
		
		if($_GET['action']=='addToEnquiryGroup')
		{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$result=updateRelEnquiryGroup($_POST["enquiry_id"],$_POST['enquiry_group_id']);
	
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Enquiry has been Added To the Group Successfully!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
				}
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_POST["enquiry_id"]);
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
		
		if($_GET['action']=='addVisit')
		{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				$customer_id = $_GET["id"];;
				$enquiry_form_id = $_GET["state"];
				
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
				$_SESSION['ack']['msg']="Site Visit has been Successfully added!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
				}
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET["state"]);
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
		
		
		if($_GET['action']=='markImportant')
		{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				$customer_id = $_GET["id"];;
				$enquiry_form_id = $_GET["state"];
				
				$result=insertRelEnquiryGroup($enquiry_form_id, array(1));
		
	
				if($result=="success")
				{
				$_SESSION['ack']['msg']="This Enquiry has been marked Important.";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
				}
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET["state"]);
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
		
		
		
		if($_GET['action']=='sendAddressSMS')
		{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				$customer_id = $_GET["id"];;
				$enquiry_form_id = $_GET["state"];
				
				$admin_id=$_SESSION['EMSadminSession']['admin_id'];
				$adminDetails = getAdminUserByID($admin_id);
				$admin_name = $adminDetails['admin_name'];
				$admin_email = $adminDetails['admin_email'];
				$admin_number = $adminDetails['admin_phone'];
				
			
				$customer = getCustomerById($customer_id);
				$customer_name = $customer['customer_name'];
				
				$prefix_id = $customer['prefix_id'];
				$prefixDetails = getPrefixById($prefix_id);
				$customer_prefix = $prefixDetails['prefix'];
				$contact_nos = getCustomerContactNo($customer_id);
				
				$address = "";
	            
				foreach($contact_nos as $contact_no)
				{
				
				if(checkForNumeric($contact_no[0]) && strlen($contact_no[0])==10)
					{
				 $result = sendSiteAddress($customer_name, $address, $contact_no[0]);
				    }
				}
	
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Address Message has been Successfully sent!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
				}
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET["state"]);
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
		
		if($_GET['action']=='sendAddressSMS2')
		{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				$customer_id = $_GET["id"];
				$enquiry_form_id = $_GET["state"];
				
				$admin_id=$_SESSION['EMSadminSession']['admin_id'];
				$adminDetails = getAdminUserByID($admin_id);
				$admin_name = $adminDetails['admin_name'];
				$admin_email = $adminDetails['admin_email'];
				$admin_number = $adminDetails['admin_phone'];
				
			
				$customer = getCustomerById($customer_id);
				$customer_name = $customer['customer_name'];
				
				$prefix_id = $customer['prefix_id'];
				$prefixDetails = getPrefixById($prefix_id);
				$customer_prefix = $prefixDetails['prefix'];
				$contact_nos = getCustomerContactNo($customer_id);
				
				$address = "";
	            
				foreach($contact_nos as $contact_no)
				{
				
				if(checkForNumeric($contact_no[0]) && strlen($contact_no[0])==10)
					{
				 $result = sendSiteAddress2($customer_name, $address, $contact_no[0]);
				    }
				}
	
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Address Message has been Successfully sent!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
				}
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET["state"]);
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
		
		if($_GET['action']=='editRemainder')
		{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$result=editRemainDer($_POST["lid"],$_POST['remainderDate'],$_POST['remarks']);
	
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Reminder Updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
					}
				header("Location: ".$_SERVER['PHP_SELF']."?view=addRemainder&id=".$_POST["enquiry_form_id"]);
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
		if($_GET['action']=='deleteRemainder')
		{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,$admin_rights) || in_array(8,$admin_rights)))
			{
			
				$result=deleteRemainder($_GET["lid"]);
				
				if($result=="success")
				{	
				$_SESSION['ack']['msg']="Reminder Deleted Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
					}
				header("Location: ".$_SERVER['PHP_SELF']."?view=addRemainder&id=".$_GET["id"]);
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
		
		if($_GET['action']=='doneRemainderGeneral')
		{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$result=setDoneRemainderGeneral($_GET["lid"]);
				
				if($result=="success")
				{	
				$_SESSION['ack']['msg']="Reminder Updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
					}
				header("Location: ".$_SERVER['PHP_SELF']."?view=addRemainder&id=".$_GET["id"]);
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
		if($_GET['action']=='unDoneRemainderGeneral')
		{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$result=setUnDoneRemainderGeneral($_GET["lid"]);
				
				if($result=="success")
				{	
				$_SESSION['ack']['msg']="Reminder Updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
					}
			
				header("Location: ".$_SERVER['PHP_SELF']."?view=addRemainder&id=".$_GET["id"]);
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
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_POST["lid"]);
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
		
		
		if($_GET['action']=='deleteCustomerNote')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
		
			{	
			 
			  $lid = $_GET["lid"];
			 
			  
				$result=deleteCustomerNote($_GET["id"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Note deleted Successfully!";
				$_SESSION['ack']['type']=3; // 3 for delete
				}
				else
				{
					$_SESSION['ack']['msg']="Cannot delete Note! Note already in use!";
				$_SESSION['ack']['type']=6; // 6 for inUse
				}
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$lid);
				exit;
			}
			else
			{	
				
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights! Contact Admin.";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$_POST["lid"]);
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
			$lid = $_GET["lid"];
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights! Contact Admin.";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$lid);
					exit;
			}
		}
		
		if($_GET['action']=='editProducts')
	{
		
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
		
		
			{
				
				
				
				$result=updateRelSubCatDetails($_POST["lid"], $_POST["product"],$_POST["mrp"], $_POST["unit_id"], $_POST["quantity_id"],$_POST['attribute_name_array']);
				
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
				$customer_id=$_POST['lid'];
				$customer_id=clean_data($customer_id);
				
				$enquiry_id=$_POST['enquiry_id'];
				$enquiry_id=clean_data($enquiry_id);
				
				$result=updateCustomer($_POST["lid"],$_POST["name"],$_POST["email"], $_POST["customerContact"], $_POST["prefix_id"] );
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Customer updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				if(isset($_GET['redirect']) && $_GET['redirect']==1)
				header("Location: ".WEB_ROOT."admin/customer/follow_up/index.php?id=".$enquiry_id);
				else
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
	if($_GET['action']=='addCustomerProof')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$customer_id=$_POST['lid'];
				$customer_id=clean_data($customer_id);
				
				$customer=getCustomerById($customer_id);
				
				$result=addCustomerProof($_POST["lid"],$customer['customer_name'],$_POST["customerProofId"], $_POST["customerProofNo"],$_FILES['customerProofImg'],false, $_POST['member']);
				
				if($result=="success")
				{
					
				$_SESSION['ack']['msg']="Customer updated Successfuly!";
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
	else if($_GET['action']=='editExtraCustomerDetails')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$customer_id=$_POST['lid'];
				$customer_id=clean_data($customer_id);
				
				
				$result=updateExtraCustomerDetails($_POST["lid"],$_POST["dob"],$_POST["address"], $_POST["secondary_address"], $_POST["profession_id"], $_POST["data_from_id"], $_POST["customer_nationality"], $_POST["city_id"] ,$_POST['customer_area']);
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
		
		
		if($_GET['action']=='editMemberDetails')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$customer_id=$_POST['lid'];
				$customer_id=clean_data($customer_id);
				
				$member_id=$_POST['id'];
				$member_id=clean_data($member_id);
				
				
				$result = updateMember($_POST["id"],$_POST["name"],$_POST["email"], $_POST["memberContact"], $_POST["relation_id"], $_POST["dob"], $_POST["gender"], $_POST["id"], $_POST["lid"]  );
				
				
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Member updated Successfuly!";
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
		if($_GET['action']=='delCustomerProof')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$result=deleteCustomerProof($_GET["state"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Customer Proof Deleted Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Unable to delete Customer Proof!";
					$_SESSION['ack']['type']=4; // 4 for error
					
					}
				header("Location: ".$_SERVER['PHP_SELF']."?view=customerDetails&id=".$_GET["id"]);
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
if($_GET['action']=='editCustomerFromCustomerProfile')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$customer_id=$_POST['lid'];
				$customer_id=clean_data($customer_id);
				
				
				
				$result=updateExtraCustomerDetails($_POST["lid"],$_POST["dob"],$_POST["address"], $_POST["secondary_address"], $_POST["profession"], $_POST["city_id"],$_POST['customer_area'] );
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
$selectedLink="newCustomer";
$jsArray=array("jquery.validate.js","validators/newCustomer.js", "customerDatePicker.js", "generateContactNoCustomer.js",'attributeDropDown.js','bootstrap-select.js','addCustomerProof.js','generateProofimgCustomer.js', 'jquery.timepicker.js', 'enquiryTypeRefrence.js');
$pathLinks=array("Home","Registration Form","Manage Locations");

$cssArray=array("bootstrap-select.css", "bp.css", "jquery-ui.css", "jquery.timepicker.css");
require_once "../../inc/template.php";
?>