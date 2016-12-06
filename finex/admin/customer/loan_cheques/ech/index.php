<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/cheque-functions.php";
require_once "../../../../lib/loan-functions.php";
require_once "../../../../lib/customer-functions.php";

if(isset($_SESSION['EMSadminSession']['admin_rights']))
$admin_rights=$_SESSION['EMSadminSession']['admin_rights'];

if(isset($_GET['view']))
{
	if($_GET['view']=='add')
	{
		$showTitle = false;
		$content="list_add.php";
	}
	
	}
else
{
	$showTitle = false;
		$content="list_add.php";
}
		


?>

<?php

$selectedLink="newCustomer";
$jsArray=array("jquery.validate.js", "bootstrap-select.js");
$cssArray=array("bootstrap-select.css", "bp.css", "jquery-ui.css", "seema.css");
$pathLinks=array("Home","Registration Form","Manage Locations");
require_once "../../../../inc/template.php";
 ?>