<?php
require_once "../../lib/cg.php";
require_once "../../lib/bd.php";
require_once "../../lib/common.php";
require_once "../../lib/customer-functions.php";



if(isset($_SESSION['EMSadminSession']['admin_rights']))
$admin_rights=$_SESSION['EMSadminSession']['admin_rights'];

if(isset($_GET['view']))
{
	if($_GET['view']=='add')
	{
		$content="list_add.php";
	}
	else if($_GET['view']=='customerDetails')
	
	{
		
		$content="customerDetails.php";
		}
	else
	{
		$content="list_add.php";
	}	
}
else
{
		$content="search.php";
}		
if(isset($_GET['action']))
{
	
	
			
	if($_GET['action']=='search')
	{
		$enquiry_id = $_POST['enquiry_id'];
		$mobile_number = $_POST['mobile_no'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		
		if(validateForNull($email) || validateForNull($mobile_number) || validateForNull($name) || validateForNull($enquiry_id))
			{
				
				
						
					if(validateForNull($name))
					{
					$customer_id=getCustomerIdFromCustomerName($name);
					if(checkForNumeric($customer_id))
					{
						unset($_SESSION['search']);
						header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$customer_id);
						exit;
					}
					else if(is_array($customer_id))
					{
						
						$customer_id_array=$customer_id;
						
						if(count($customer_id_array)==1)
						{
						$_SESSION['search']['file_id_array']=$customer_id_array;
						$_SESSION['search']['parameter']="Customer Name Like";
						$_SESSION['search']['value']=$name;
						header("Location: ".$_SERVER['PHP_SELF']);
						exit;	
							}
						$_SESSION['search']['file_id_array']=$customer_id_array;

						if($file_id_array['nameType']=="like")
						$_SESSION['search']['parameter']="Customer Name Like";
						else
						$_SESSION['search']['parameter']="Customer Name";
						$_SESSION['search']['value']=$name;
						header("Location: ".$_SERVER['PHP_SELF']);
						exit;
					}
					else{
						unset($_SESSION['search']);
						$_SESSION['ack']['msg']="Invalid Customer Name!";
						$_SESSION['ack']['type']=5; // 5 for access
						header("Location: ".$_SERVER['PHP_SELF']);
						exit;
						
						}	
					}
				else if(validateForNull($mobile_number))
				{
					
				
					$customer_id=getCustomerIdFromContactNo($mobile_number);
					
					if(checkForNumeric($customer_id))
					{
						unset($_SESSION['search']);
						header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$customer_id);
						exit;
					}
					
					else{
						unset($_SESSION['search']);
						$_SESSION['ack']['msg']="Invalid Mobile Number!";
						$_SESSION['ack']['type']=5; // 5 for access
						header("Location: ".$_SERVER['PHP_SELF']);
						exit;
						
						}	
				}
				
				else if(validateForNull($enquiry_id))
				{
					
					
				
					$customer_id = getCustomerByUniqueEnquiryId($enquiry_id);
					
					if(checkForNumeric($customer_id))
					{
						unset($_SESSION['search']);
						header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$customer_id);
						exit;
					}
					
					else{
						unset($_SESSION['search']);
						$_SESSION['ack']['msg']="Invalid Enquiry ID!";
						$_SESSION['ack']['type']=5; // 5 for access
						header("Location: ".$_SERVER['PHP_SELF']);
						exit;
						
						}	
				}
				
				else if(validateForNull($email))
				{
					
				
					$customer_id=getCustomerIdFromEmail($email);
					
					if(checkForNumeric($customer_id))
					{
						unset($_SESSION['search']);
						header("Location: ".WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$customer_id);
						exit;
					}
					
					else{
						unset($_SESSION['search']);
						$_SESSION['ack']['msg']="Invalid Email!";
						$_SESSION['ack']['type']=5; // 5 for access
						header("Location: ".$_SERVER['PHP_SELF']);
						exit;
						
						}	
				}
								
			}
			else
			{	
					$_SESSION['ack']['msg']="Minimum One Input is Required!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".$_SERVER['PHP_SELF']);
			exit;
			}
			
			
		}				
	}
?>

<?php

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="searchCustomer";
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","validators/searchCustomer.js");
$cssArray=array("jquery-ui.css");
require_once "../../inc/template.php";
 ?>