<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/common.php";
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/our-company-function.php";
require_once "../../../lib/vehicle-functions.php";
require_once "../../../lib/vehicle-company-functions.php";
require_once "../../../lib/account-ledger-functions.php";
require_once "../../../lib/vehicle-model-functions.php";
require_once "../../../lib/report-functions.php";
require_once "../../../lib/area-functions.php";
require_once "../../../lib/inventory-functions.php";
require_once "../../../lib/inventory-item-functions.php";
require_once("../../../lib/checkStockFromExcel.php");
require_once("../../../lib/image-functions.php");

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
	if($_GET['action']=='generateReport')
	{
		$id=$_POST['ledger_id'];
		
		if(isset($_POST['to_date']))
		{
		$to=$_POST['to_date'];
		}
		else
		$to=null;	
		
		if(isset($_POST['from_date']))
		{
		$from=$_POST['from_date'];
		}
		else
		$from=null;	
		
		
		
		if(isset($_POST['city_id']))
		{
		$city_id=$_POST['city_id'];
		}
		else
		$city_id=null;
		
		if(isset($_POST['area']))
		{
			
		$area_array=$_POST['area'];
		
		$area_id_string=implode(',',$area_array);
		}
		else
		$area_id_string=null;
		
		
		
		if(isset($_POST['agency_id']))
		{
		$agency_id_array=$_POST['agency_id'];	
		$agency_id_string=implode(',',$agency_array);
		}
		else
		$agency_id_string=null;
		
		
		
		if($area_id_string==false)
		$area_id_string=null;
		
		if($city_id==-1)
		$city_id=null;
		
		
		
		$reportArray=unreceivedPurchaseOrderReports($from,$to,$id);
		
		$_SESSION['cDeductStockReport']['emi_array']=$reportArray;
		$_SESSION['cDeductStockReport']['to']=$to;
		$_SESSION['cDeductStockReport']['from']=$from;
		$_SESSION['cDeductStockReport']['city_id']=$city_id;
		$_SESSION['cDeductStockReport']['area_id_array']=$area_array;
		$_SESSION['cDeductStockReport']['agency_id']=$agency_id_array;
		$_SESSION['cDeductStockReport']['ledger_id']=$id;
		$_SESSION['cDeductStockReport']['outstanding_amount']=$_POST['outstanding_amount'];
		header("Location: index.php");		
		exit;
	}
	if($_GET['action']=='addExcel')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$file=UploadExcel($_FILES['excel_file'],'lib/');
				$return_array=checkStockForExcel('../../../lib/'.$file);
				
				if(count($return_array[2])>0)
				{
				$_SESSION['ack']['msg']="Excel Scanned With Errors!";
				$_SESSION['ack']['type']=4; // 1 for insert
				}
				else
				{
					
				$_SESSION['ack']['msg']="Excel Processed Successfully!";
				$_SESSION['ack']['type']=1; // 4 for error
				}
				$_SESSION['cDeductStockReport']['emi_array']=$return_array;
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
	if($_GET['action']=='deductStockExcel')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$file=UploadExcel($_FILES['excel_file'],'lib/');
				$return_array=deductStockFromExcel('../../../lib/'.$file);
				
				if(checkForNumeric($return_array))
				{
				$_SESSION['ack']['msg']="Excel Processed Successfully!";
				$_SESSION['ack']['type']=1; // 4 for error
				
				header("Location: ".WEB_ROOT."admin/accounts/transactions/credit_inventory_jv/index.php?view=details&id=".$return_array);
				exit;
				}
				else if(count($return_array[2])>0)
				{
				$_SESSION['ack']['msg']="Excel Scanned With Errors!";
				$_SESSION['ack']['type']=4; // 1 for insert
				}
				else if(count($return_array[1])>0)
				{
				$_SESSION['ack']['msg']="Stock Not Sufficient!";
				$_SESSION['ack']['type']=4; // 4 for error
				}
				$_SESSION['cDeductStockReport']['emi_array']=$return_array;
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
	else if($_GET['action']=='fromHomeUpcoming')
	{
		if(isset($_POST['start_date']))
		{
		$from=$_POST['start_date'];
		}
		else
		$from=date('d/m/Y');
		
		if(isset($_POST['end_date']))
		{
		$to=$_POST['end_date'];
		}
		else
		{
	 $today=date('Y-m-d');
	$to = new DateTime(date('Y-m-d'));
	$to->add(new DateInterval('P25D'));
	$to=$to->format('d/m/Y');
		}
		
		if(isset($_POST['win_gt']))
		{
		$win_gt=$_POST['win_gt'];
		}
		else
		$win_gt=1;
		
		if(isset($_POST['win_lt']))
		{
		$win_lt=$_POST['win_lt'];
		}
		else
		$win_lt=null;
		
		if(isset($_POST['emi_gt']))
		{
		$emi_gt=$_POST['emi_gt'];
		}
		else
		$emi_gt=null;
		
		if(isset($_POST['emi_lt']))
		{
		$emi_lt=$_POST['emi_lt'];
		}
		else
		$emi_lt=null;
		
		if(isset($_POST['balance_gt']))
		{
		$balance_gt=$_POST['balance_gt'];
		}
		else
		$balance_gt=null;
		
		if(isset($_POST['balance_lt']))
		{
		$balance_lt=$_POST['balance_lt'];
		}
		else
		$balance_lt=null;
		
		if(isset($_POST['city_id']))
		{
		$city_id=$_POST['city_id'];
		}
		else
		$city_id=null;
		
		if(isset($_POST['area']))
		{
			
		$area_array=$_POST['area'];
		
		$area_id_string=implode(',',$area_array);
		}
		else
		$area_id=null;
		
		if(isset($_POST['$agency_id']))
		{
			
		$agency_id=$_POST['agency_id'];
		}
		else
		$agency_id=null;
		
		if(isset($_POST['file_status']))
		{
			
		$file_status=$_POST['file_status'];
		}
		else
		$file_status=null;
		
		if($area_id_string==false)
		$area_id_string=null;
		
		if($city_id==-1)
		$city_id=null;
		
		
		
		$reportArray=generalEMIReports($from,$to,$win_gt,$win_lt,$emi_gt,$emi_lt,$balance_gt,$balance_lt,$city_id,$area_id_string,$file_status,$agency_id);
		
		$_SESSION['cEMIReport']['emi_array']=$reportArray;
		
		$_SESSION['cEMIReport']['from']=$from;
		$_SESSION['cEMIReport']['to']=$to;
		$_SESSION['cEMIReport']['win_gt']=$win_gt;
		$_SESSION['cEMIReport']['win_lt']=$win_lt;
		$_SESSION['cEMIReport']['emi_gt']=$emi_gt;
		$_SESSION['cEMIReport']['emi_lt']=$emi_lt;
		$_SESSION['cEMIReport']['balance_gt']=$balance_gt;
		$_SESSION['cEMIReport']['balance_lt']=$balance_lt;
		$_SESSION['cEMIReport']['city_id']=$city_id;
		$_SESSION['cEMIReport']['area_id_array']=$area_array;
		$_SESSION['cEMIReport']['agency_id']=$agency_id;
		$_SESSION['cEMIReport']['file_status']=$file_status;
	
		header("Location: index.php");		
		exit;
	}
	else if($_GET['action']=='fromHomeExpiredOpen')
	{
		if(isset($_POST['start_date']))
		{
		$from=$_POST['start_date'];
		}
		else
		$from=null;
		
		if(isset($_POST['end_date']))
		{
		$to=$_POST['end_date'];
		}
		else
		$to=date('d/m/Y');	
		
		if(isset($_POST['win_gt']))
		{
		$win_gt=$_POST['win_gt'];
		}
		else
		$win_gt=0.1;
		
		if(isset($_POST['win_lt']))
		{
		$win_lt=$_POST['win_lt'];
		}
		else
		$win_lt=null;
		
		if(isset($_POST['emi_gt']))
		{
		$emi_gt=$_POST['emi_gt'];
		}
		else
		$emi_gt=null;
		
		if(isset($_POST['emi_lt']))
		{
		$emi_lt=$_POST['emi_lt'];
		}
		else
		$emi_lt=null;
		
		if(isset($_POST['balance_gt']))
		{
		$balance_gt=$_POST['balance_gt'];
		}
		else
		$balance_gt=null;
		
		if(isset($_POST['balance_lt']))
		{
		$balance_lt=$_POST['balance_lt'];
		}
		else
		$balance_lt=null;
		
		if(isset($_POST['city_id']))
		{
		$city_id=$_POST['city_id'];
		}
		else
		$city_id=null;
		
		if(isset($_POST['area']))
		{
			
		$area_array=$_POST['area'];
		
		$area_id_string=implode(',',$area_array);
		}
		else
		$area_id=null;
		
		if(isset($_POST['$agency_id']))
		{
			
		$agency_id=$_POST['agency_id'];
		}
		else
		$agency_id=null;
		
		if(isset($_POST['file_status']))
		{
			
		$file_status=$_POST['file_status'];
		}
		else
		$file_status=1;
		
		if($area_id_string==false)
		$area_id_string=null;
		
		if($city_id==-1)
		$city_id=null;
		
		
		
		$reportArray=generalEMIReports($from,$to,$win_gt,$win_lt,$emi_gt,$emi_lt,$balance_gt,$balance_lt,$city_id,$area_id_string,$file_status,$agency_id);
		
		$_SESSION['cEMIReport']['emi_array']=$reportArray;
		
		$_SESSION['cEMIReport']['from']=$from;
		$_SESSION['cEMIReport']['to']=$to;
		$_SESSION['cEMIReport']['win_gt']=$win_gt;
		$_SESSION['cEMIReport']['win_lt']=$win_lt;
		$_SESSION['cEMIReport']['emi_gt']=$emi_gt;
		$_SESSION['cEMIReport']['emi_lt']=$emi_lt;
		$_SESSION['cEMIReport']['balance_gt']=$balance_gt;
		$_SESSION['cEMIReport']['balance_lt']=$balance_lt;
		$_SESSION['cEMIReport']['city_id']=$city_id;
		$_SESSION['cEMIReport']['area_id_array']=$area_array;
		$_SESSION['cEMIReport']['agency_id']=$agency_id;
		$_SESSION['cEMIReport']['file_status']=$file_status;
	
		header("Location: index.php");		
		exit;
	}
	else if($_GET['action']=='fromHomeExpiredClosed')
	{
		if(isset($_POST['start_date']))
		{
		$from=$_POST['start_date'];
		}
		else
		$from=null;
		
		if(isset($_POST['end_date']))
		{
		$to=$_POST['end_date'];
		}
		else
		$to=date('d/m/Y');	
		
		if(isset($_POST['win_gt']))
		{
		$win_gt=$_POST['win_gt'];
		}
		else
		$win_gt=0.1;
		
		if(isset($_POST['win_lt']))
		{
		$win_lt=$_POST['win_lt'];
		}
		else
		$win_lt=null;
		
		if(isset($_POST['emi_gt']))
		{
		$emi_gt=$_POST['emi_gt'];
		}
		else
		$emi_gt=null;
		
		if(isset($_POST['emi_lt']))
		{
		$emi_lt=$_POST['emi_lt'];
		}
		else
		$emi_lt=null;
		
		if(isset($_POST['balance_gt']))
		{
		$balance_gt=$_POST['balance_gt'];
		}
		else
		$balance_gt=null;
		
		if(isset($_POST['balance_lt']))
		{
		$balance_lt=$_POST['balance_lt'];
		}
		else
		$balance_lt=null;
		
		if(isset($_POST['city_id']))
		{
		$city_id=$_POST['city_id'];
		}
		else
		$city_id=null;
		
		if(isset($_POST['area']))
		{
			
		$area_array=$_POST['area'];
		
		$area_id_string=implode(',',$area_array);
		}
		else
		$area_id=null;
		
		if(isset($_POST['$agency_id']))
		{
			
		$agency_id=$_POST['agency_id'];
		}
		else
		$agency_id=null;
		
		if(isset($_POST['file_status']))
		{
			
		$file_status=$_POST['file_status'];
		}
		else
		$file_status=2;
		
		if($area_id_string==false)
		$area_id_string=null;
		
		if($city_id==-1)
		$city_id=null;
		
		
		
		$reportArray=generalEMIReports($from,$to,$win_gt,$win_lt,$emi_gt,$emi_lt,$balance_gt,$balance_lt,$city_id,$area_id_string,$file_status,$agency_id);
		
		$_SESSION['cEMIReport']['emi_array']=$reportArray;
		
		$_SESSION['cEMIReport']['from']=$from;
		$_SESSION['cEMIReport']['to']=$to;
		$_SESSION['cEMIReport']['win_gt']=$win_gt;
		$_SESSION['cEMIReport']['win_lt']=$win_lt;
		$_SESSION['cEMIReport']['emi_gt']=$emi_gt;
		$_SESSION['cEMIReport']['emi_lt']=$emi_lt;
		$_SESSION['cEMIReport']['balance_gt']=$balance_gt;
		$_SESSION['cEMIReport']['balance_lt']=$balance_lt;
		$_SESSION['cEMIReport']['city_id']=$city_id;
		$_SESSION['cEMIReport']['area_id_array']=$area_array;
		$_SESSION['cEMIReport']['agency_id']=$agency_id;
		$_SESSION['cEMIReport']['file_status']=$file_status;
	
		header("Location: index.php");		
		exit;
	}
}
?>

<?php

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="deduct_stock";
$jsArray=array("jquery.validate.js","dropDown.js","jquery-ui/js/jquery-ui.min.js","validators/generalEMIReports.js","customerDatePicker.js","calculateTotalSalesReport.js");
$cssArray=array("jquery-ui.css");
require_once "../../../inc/template.php";
 ?>