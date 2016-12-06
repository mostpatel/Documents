<?php require_once '../lib/cg.php';
require_once '../lib/common.php';
require_once '../lib/file-functions.php';
require_once '../lib/area-functions.php';
?>
<?php
$oc_id=$_SESSION['adminSession']['oc_id'];
$area_name = $_GET['area'];
$city_id = $_GET['city_id'];
$area=getAreaByID(getAreaIdFromName($area_name,$city_id));
echo $area['pincode']; ?>