<?php
require_once "../../lib/cg.php";
require_once "../../lib/bd.php";
require_once "../../lib/account-period-functions.php";

if(isset($_SESSION['edmsAdminSession']['admin_rights']))
$admin_rights=$_SESSION['edmsAdminSession']['admin_rights'];


	$content="list.php";
	
$pathLinks=array("Home","Registration Form");
$selectedLink="accounts";
require_once "../../inc/template.php";	
 ?>


 