<?php
require_once "../../lib/cg.php";
require_once "../../lib/bd.php";
require_once "../../lib/common.php";
require_once "../../lib/city-functions.php";
require_once "../../lib/area-functions.php";
require_once "../../lib/our-company-function.php";
require_once "../../lib/customer-functions.php";
require_once "../../lib/vehicle-functions.php";
require_once "../../lib/job-card-functions.php";
require_once "../../lib/vehicle-insurance-functions.php";
require_once "../../lib/delivery-challan-functions.php";
require_once "../../lib/insurance-company-functions.php";
require_once "../../lib/vehicle-model-functions.php";
require_once "../../lib/vehicle-functions.php";
require_once "../../lib/vehicle-company-functions.php";
require_once "../../lib/vehicle-type-functions.php";
require_once "../../lib/vehicle-invoice-functions.php";
require_once "../../lib/vehicle-sale-cert-functions.php";
require_once "../../lib/vehicle-sales-functions.php";
require_once "../../lib/vehicle-purchase-functions.php";
require_once "../../lib/adminuser-functions.php";
require_once "../../lib/report-functions.php";
require_once "../../lib/account-combined-agency-functions.php";
require_once "../../lib/account-delivery-challan-functions.php";
require_once "../../lib/account-ledger-functions.php";
require_once "../../lib/customer-group-functions.php";
require_once "../../lib/broker-group-functions.php";

if(isset($_SESSION['edmsAdminSession']['admin_rights']))
$admin_rights=$_SESSION['edmsAdminSession']['admin_rights'];


if(isset($_GET['view']))
{
	if($_GET['view']=='add')
	{
		$content="list_add.php";
	}
	else if($_GET['view']=='details')
	{
		$content="details.php";
		$link="searchCustomer";
		}
	else if($_GET['view']=='list')
	{
		$content="allCustomers.php";
		$link="searchCustomer";
		}	
	else if($_GET['view']=='customerDetails')
	{
		$content="customerDetails.php";
		$link="searchCustomer";
		}	
	else if($_GET['view']=='editCustomer')
	{
		$content="edit.php";
		$link="searchCustomer";
		}					
	else if($_GET['view']=='addRemainder')
	{
		$content="addRemainder.php";
		$link="searchCustomer";
		}
	else if($_GET['view']=='editRemainder')
	{
		
		$content="remainderEdit.php";
		$link="searchCustomer";
		}	
	else if($_GET['view']=='customerGroup')
	{
		$showTitle=false;
		$content="addToCustomerGroup.php";
		$link="searchCustomer";
		}	
	else if($_GET['view']=='brokerGroup')
	{
		$showTitle=false;
		$content="addToBrokerGroup.php";
		$link="searchCustomer";
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
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))
			{
				
				
				if(isset($_FILES['customerProofImg']))
				{
					$CustomerProof=$_FILES['customerProofImg'];
					}
				else
				{
					$CustomerProof=false;
					}
				
				$result=insertCustomer($_POST['customer_name'],$_POST['customer_address'],$_POST['customer_city_id'],$_POST['customer_area'],$_POST['customer_pincode'],$_POST['customerContact'],$_POST['customerProofId'],$_POST['customerProofNo'],$CustomerProof,$_POST['pan_no'], $_POST['tin_no'],$_POST['notes'],$_POST['opening_balance'],$_POST['opening_balance_cd'],$_POST['cst_no'],$_POST['service_tax_no'],$_POST['email'],$_POST['customer_no']);
				
				if(is_numeric($result))
				{
				AddCustomerToGroups($result,$_POST['customer_group_id']);	
				AddCustomerToBrokers($result,$_POST['broker_group_id']);
				$_SESSION['ack']['msg']="Customer successfully added!";
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
	if($_GET['action']=='editCustomer')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$result=updateCustomer($_POST["lid"],$_POST['customer_name'],$_POST['customer_address'],$_POST['customer_city_id'],$_POST['customer_area'],$_POST['customer_pincode'],$_POST['customerContact'],$_POST['customerProofId'],$_POST['customerProofNo'],$_FILES['customerProofImg'],$_POST['pan_no'],$_POST['tin_no'],$_POST['notes'],$_POST['opening_balance'],$_POST['opening_balance_cd'],$_POST['cst_no'],$_POST['service_tax_no'],$_POST['email'],$_POST['customer_no']);
	
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Customer updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
					}
				header("Location: ".$_SERVER['PHP_SELF']."?view=customerDetails&id=".$_POST["lid"]);
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
	if($_GET['action']=='deleteCustomer')
	{
		
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				$result=deletetCustomer($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Customer Deleted Successfuly!";
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
		if($_GET['action']=='deleteVehicle')
	{
		
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				$result=deleteVehicle($_GET["lid"]);
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Vehicle Deleted Successfuly!";
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
	if($_GET['action']=='delCustomerProof')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
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
		if($_GET['action']=='addRemainder')
		{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$result=addRemainder($_POST["lid"],$_POST['remainderDate'],$_POST['remarks']);
	
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Remainder Added Successfuly!";
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
		if($_GET['action']=='editRemainder')
		{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$result=editRemainDer($_POST["lid"],$_POST['remainderDate'],$_POST['remarks']);
	
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Remainder Updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
					}
				header("Location: ".$_SERVER['PHP_SELF']."?view=addRemainder&id=".$_POST["customer_id"]);
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
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$result=AddCustomerToGroups($_POST["file_id"],$_POST['customer_group_id']);
	
				if($result=="success")
				{
				$_SESSION['ack']['msg']="File Groups Added Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
					}
				header("Location: ".$_SERVER['PHP_SELF']."?view=customerGroup&id=".$_POST["file_id"]);
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
		if($_GET['action']=='addToBrokerGroup')
		{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				$result=AddCustomerToBrokers($_POST["file_id"],$_POST['customer_group_id']);
	
				if($result=="success")
				{
				$_SESSION['ack']['msg']= BROKER_NAME." Groups Added Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					
					}
				header("Location: ".$_SERVER['PHP_SELF']."?view=brokerGroup&id=".$_POST["file_id"]);
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
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,$admin_rights) || in_array(8,$admin_rights)))
			{
			
				$result=deleteRemainder($_GET["lid"]);
				
				if($result=="success")
				{	
				$_SESSION['ack']['msg']="Remainder Deleted Successfuly!";
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
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$result=setDoneRemainderGeneral($_GET["lid"]);
				
				if($result=="success")
				{	
				$_SESSION['ack']['msg']="Remainder Updated Successfuly!";
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
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$result=setUnDoneRemainderGeneral($_GET["lid"]);
				
				if($result=="success")
				{	
				$_SESSION['ack']['msg']="Remainder Updated Successfuly!";
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
								
}


$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="newCustomer";
if(isset($link))
$selectedLink=$link;
$jsArray=array("jquery.validate.js","dropDown.js","scanProof.js","generateEMIDuration.js","checkAvailability.js","Ajax/prefixFromAgencyCustomer.js","Ajax/calculatePenalty.js","jquery-ui/js/jquery-ui.min.js","customerDatePicker.js","generateContactNoCustomer.js","generateContactNoGuarantor.js","addCustomerProof.js","generateProofimgCustomer.js","addGuarantorProof.js","generateProofimgGuarantor.js","validators/addNewCustomer.js","cScript.ashx","transliteration.I.js");
$cssArray=array("jquery-ui.css");
require_once "../../inc/template.php";
 ?>