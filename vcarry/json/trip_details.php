<?php require_once '../lib/cg.php';
require_once '../lib/common.php';
require_once '../lib/trip-functions.php';
?>

<?php
$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
$trip = getTripById($_GET['trip_id']);	      
echo json_encode($trip); 

   




?>