<?php require_once('../../../../lib/cg.php');
require_once('../../../../lib/bd.php');
require_once('../../../../lib/adminuser-functions.php');

echo "su";

$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
$password=$_GET['p'];

$result=checkPasswordForDeletion($admin_id,$password);
echo $result;
 ?>