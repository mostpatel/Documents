<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/fin-enquiry-functions.php";
require_once "../../../../lib/status-functions.php";



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
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
			
				
				
			$finEnquiries=viewFinEnquiries($_POST['from_date'],$_POST['to_date'], $_POST['status_id']);
			
				$_SESSION['finEnqReport']['finEnquiries_array']=$finEnquiries;
				$_SESSION['finEnqReport']['from_date']=$_POST['from_date'];
				$_SESSION['finEnqReport']['to_date']=$_POST['to_date'];
				
				
				
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
	
	
	else if($_GET['action']=='fromHomeUpcomingFollowUps')
	{
		if(isset($_POST['from_date']))
		{
		$from=$_POST['to_date'];
		}
		else
		$from=date('d/m/Y');
		
		if(isset($_POST['to_date']))
		{
		$to=$_POST['to_date'];
		}
		else
		$to=null;	
		
		
		
		$finEnquiries=viewFollowUps($from, $to);
		
		$_SESSION['finEnqReport']['finEnquiries_array']=$finEnquiries;
		$_SESSION['finEnqReport']['from_date']=$from;
		$_SESSION['finEnqReport']['to_date']=$to;
		
		
		header("Location: index.php");		
		exit;
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