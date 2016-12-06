<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/common.php";
require_once "../../../lib/city-functions.php";
require_once "../../../lib/area-functions.php";
require_once "../../../lib/agency-functions.php";
require_once "../../../lib/our-company-function.php";
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/guarantor-functions.php";
require_once "../../../lib/bank-functions.php";
require_once "../../../lib/file-functions.php";
require_once "../../../lib/loan-functions.php";
require_once "../../../lib/vehicle-functions.php";
require_once "../../../lib/vehicle-insurance-functions.php";
require_once "../../../lib/insurance-company-functions.php";
require_once "../../../lib/vehicle-model-functions.php";
require_once "../../../lib/vehicle-dealer-functions.php";
require_once "../../../lib/vehicle-company-functions.php";
require_once "../../../lib/vehicle-type-functions.php";
require_once "../../../lib/addNewCustomer-functions.php";
require_once "../../../lib/adminuser-functions.php";
require_once "../../../lib/noc-functions.php";


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
		$showTitle=false;
		$content="notice.php";
		$link="searchEMI";
		}
	else if($_GET['view']=='search')
	{
		$content="search.php";
		$link="searchEMI";
		}	
	else if($_GET['view']=='noc')
	{
		$showTitle=false;
		$content="noc_garg.php";
		$link="searchEMI";
		}		
	else
	{
		$showTitle=false;
		$content="list_add.php";
		$link="searchEMI";
	}	
}
else
{
		
		$showTitle=false;
		$content="list_add.php";
		$link="searchEMI";
		
		$file_id=$_GET['id'];

		$file=getFileDetailsByFileId($file_id);
		$customer_id = getCustomerIdByFileId($file_id);
		
		$current_balance = getOpeningBalanceForLedgerForDate('C'.$customer_id,getTodaysDateTimeAfterMonthsAndDays(120,0));
		
		if((round($current_balance)>0 || round($current_balance)<0) && NOC_NON_ISSUE_CUSTOMER_BALANCE==1)
{
	
	$_SESSION['ack']['msg']="Customer Account Balance Non Zero! Cannot Issue NOC!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$file_id);
	exit;
	
}
}		
if(isset($_GET['action']))
{
	if($_GET['action']=='add')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(11,$admin_rights) || in_array(7,$admin_rights)))
			{
				$file_id=$_POST['file_id'];
				$file=getFileDetailsByFileId($file_id);
		$customer_id = getCustomerIdByFileId($file_id);
		
		$current_balance = getOpeningBalanceForLedgerForDate('C'.$customer_id,getTodaysDateTimeAfterMonthsAndDays(120,0));
		echo $current_balance;
		exit;
		if((round($current_balance)>0 || round($current_balance)<0) && NOC_NON_ISSUE_CUSTOMER_BALANCE==1)
{
	$_SESSION['ack']['msg']="Customer Account Balance Non Zero! Cannot Issue NOC!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$file_id);
	exit;
}
				$result=insertNOC($_POST['file_id'],$_POST["noc_date"],$_POST['remarks']);
				
				if($result!="error")
				{
				$_SESSION['ack']['msg']="NOC successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/customer/noc/index.php?duplicate=0&view=noc&id=".$_POST['file_id']);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/noc/index.php?id=".$_POST['file_id']);
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
	if($_GET['action']=='delete')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && ((in_array(4,$admin_rights) && in_array(11,$admin_rights)) || in_array(7,$admin_rights)))
			{	
				deleteNOC($_GET["lid"]);
				
				$_SESSION['ack']['msg']="NOC deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['id']);
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
		if(isset($_SESSION['adminSession']['admin_rights']) && ((in_array(3,$admin_rights) && in_array(11,$admin_rights)) || in_array(7,					$admin_rights)))
			{
				updateNOC($_POST['file_id'],$_POST["noc_date"],$_POST['remarks']);
				
				$_SESSION['ack']['msg']="NOC updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				
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
$selectedLink="searchEMI";
if(isset($link))
$selectedLink=$link;
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","validators/notice.js","addInsuranceProof.js","customerDatePicker.js","validators/addNewInsurance.js");
$cssArray=array("jquery-ui.css","interest.css");
require_once "../../../inc/template.php";
?>