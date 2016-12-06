<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/sms-functions.php";
require_once "../../../../lib/city-functions.php";
require_once "../../../../lib/sub-category-functions.php";
require_once "../../../../lib/category-functions.php";
require_once "../../../../lib/super-category-functions.php";
require_once "../../../../lib/customer-type-functions.php";
require_once "../../../../lib/adminuser-functions.php";
require_once "../../../../lib/lead-functions.php";
require_once "../../../../lib/enquiry-functions.php";
require_once "../../../../lib/customer-functions.php";
require_once "../../../../lib/report-functions.php";
require_once "../../../../lib/rel-subcat-enquiry-functions.php";
require_once "../../../../lib/customer-functions.php";
require_once "../../../../lib/customer-extra-details-functions.php";
require_once "../../../../lib/customer-group-functions.php";




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
        else if($_GET['view']=='labelPrinting')
	{
		$content="labelSheet.php";
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
				
				
				
					$customers=viewCustomers($_POST['from_date'],$_POST['to_date'], $_POST['min_amount'], $_POST['max_amount'], $_POST['city_id'], NULL, $_POST['from_date_added'],$_POST['to_date_added'], $_POST['customer_group_id']);
					
				
			
				$_SESSION['cSmsReport']['customer_array']=$customers;
				$_SESSION['cSmsReport']['from_date']=$_POST['from_date'];
				$_SESSION['cSmsReport']['to_date']=$_POST['to_date'];
				$_SESSION['cSmsReport']['min_amount']=$_POST['min_amount'];
				$_SESSION['cSmsReport']['max_amount']=$_POST['max_amount'];
				$_SESSION['cSmsReport']['city_id']=$_POST['city_id'];
				$_SESSION['cSmsReport']['from_date_added']=$_POST['from_date_added'];
				$_SESSION['cSmsReport']['to_date_added']=$_POST['to_date_added'];
				$_SESSION['cSmsReport']['customer_group_id']=$_POST['customer_group_id'];
				
				
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
		
		if($_GET['action']=='sendsms')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				
		sendFixSMS($_POST['smsContent'], $_POST['selectTR']);
		header("Location: ".$_SERVER['PHP_SELF']);
				exit;
			}
	}
				
	}
?>

<?php
$selectedLink="reports";
$jsArray=array("jquery.validate.js","customerDatePicker.js", "bootstrap-select.js");
$cssArray=array("bootstrap-select.css", "bp.css", "jquery-ui.css");
$pathLinks=array("Home","Registration Form","Manage Locations");
require_once "../../../../inc/template.php";
 ?>