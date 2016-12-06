<?php
require_once('lib/cg.php');
if(isset($_SESSION['edmsAdminSession']['admin_id']))
{
		header("Location: ".WEB_ROOT."admin/reports/inventory_reports/custom/");
		exit;
}
else
{
		header("Location: ".WEB_ROOT."login.php");
		exit;	
}
?>