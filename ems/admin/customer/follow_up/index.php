<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/common.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/follow-up-functions.php";
require_once "../../../lib/follow-up-type-functions.php";

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
				$enquiry_id=$_POST["enquiry_id"];
				$enquiry_id=clean_data($enquiry_id);
				
				
				
			$result=insertFollowUp($enquiry_id, $_POST["followUpDiscussion"], $_POST["next_follow_up_date"]. " ".$_POST["next_follow_up_time"], $_POST["sms_status"], $_POST["follow_up_type_id"]);
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Follow Up successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				}
				
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_id);
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
				$result=deleteFollowUp($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Follow Up deleted Successfuly!";
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
	if($_GET['action']=='edit')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$result=updateSuperCategory($_POST["lid"],$_POST["name"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Super Category updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".$_SERVER['PHP_SELF']);
				exit;
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					header("Location: ".$_SERVER['PHP_SELF']."?view=edit&lid=".$_POST["lid"]);
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
$jsArray=array("jquery.validate.js","validators/addFollowUp.js", "customerDatePicker.js", "jquery.timepicker.js");
$cssArray=array("bootstrap-select.css", "bp.css", "jquery-ui.css", "jquery.timepicker.css");
$pathLinks=array("Home","Registration Form","Manage Locations");
require_once "../../../inc/template.php";
 ?>