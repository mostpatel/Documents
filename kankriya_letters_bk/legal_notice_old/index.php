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
require_once "../../../lib/legal-notice-functions.php";
require_once "../../../lib/court-functions.php";
require_once "../../../lib/case-type-functions.php";
require_once "../../../lib/case-petetionar-functions.php";
require_once "../../../lib/advocate-functions.php";


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
	else if($_GET['view']=='edit')
	{
		$showTitle=false;
		$content="edit.php";
		$link="searchEMI";
		}	
	else if($_GET['view']=='search')
	{
		$content="search.php";
		$link="searchEMI";
		}	
	else if($_GET['view']=='notice')
	{
		$showTitle=false;
		$content="notice.php";
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
}		
if(isset($_GET['action']))
{
	if($_GET['action']=='add')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))
			{
				
				$result=insertLegalNotice($_POST['file_id'],$_POST["notice_date"],$_POST['case_no'],$_POST['remarks'],$_POST['advocate_id'],$_POST['case_type_id'],$_POST['court_id'],$_POST['stage'],$_POST['next_date'],$_POST['type'],$_POST['cheque_return_id'],$_POST['case_petetionar_id']);
				
				if($result!="error" && is_numeric($result))
				{
				$_SESSION['ack']['msg']="notice successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/customer/legal_notice/index.php?id=".$_POST['file_id']);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/legal_notice/index.php?id=".$_POST['file_id']);
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
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				deleteLegalNotice($_GET["lid"]);
				
				$_SESSION['ack']['msg']="Notice deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				
				header("Location: ".WEB_ROOT."admin/customer/legal_notice/index.php?id=".$_GET['id']);
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
				$result=updateLegalNotice($_POST['legal_notice_id'],$_POST['file_id'],$_POST["notice_date"],$_POST['case_no'],$_POST['remarks'],$_POST['advocate_id'],$_POST['case_type_id'],$_POST['court_id'],$_POST['stage'],$_POST['next_date'],$_POST['type'],$_POST['cheque_return_id'],$_POST['case_petetionar_id']);
				
				$_SESSION['ack']['msg']="Item updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				
				header("Location: ".WEB_ROOT."admin/customer/legal_notice/index.php?id=".$_POST['file_id']);
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