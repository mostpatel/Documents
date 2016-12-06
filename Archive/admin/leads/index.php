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
require_once "../../lib/customer-functions.php";
require_once "../../lib/report-functions.php";
require_once "../../lib/rel-subcat-enquiry-functions.php";



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
				
			if(isset($_POST['from_date']))
		{
		$from=$_POST['from_date'];
		}
		else
		$from = date('d/m/Y', strtotime("$to -30 days") );
		
		
		if(isset($_POST['to_date']))
		{
		$to=$_POST['to_date'];
		}
		else
		$to=date('d/m/Y');	
		
		
		
		$min_amount=null;
		$max_amount=null;
		$user_id=null;
		$customer_type_id=null;
		$leadStatus=null;
		$product=null;
		$super_cat_id=null;
		$cat_id=null;
		$attribute_name_array=null;
		
		$leads=viewLeads($from, $to, $min_amount, $max_amount, $user_id, $customer_type_id, $leadStatus, $product, $super_cat_id, $cat_id, $attribute_name_array);
				
				$_SESSION['directLeadReport']['leads_array']=$leads;
				
				$_SESSION['directLeadReport']['from_date']=$_POST['from_date'];
				$_SESSION['directLeadReport']['to_date']=$_POST['to_date'];
				
				
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
?>

<?php
$selectedLink="leads";
$jsArray=array("jquery.validate.js","customerDatePicker.js", "bootstrap-select.js", "attributeDropDownForReports.js");
$cssArray=array("bootstrap-select.css", "bp.css", "jquery-ui.css");
$pathLinks=array("Home","Registration Form","Manage Locations");
require_once "../../inc/template.php";
 ?>