<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/vehicle-type-functions.php";
require_once "../../../lib/driver-functions.php";

if(isset($_GET['id'])){
$id=$_GET['id'];
$result=array();
$result=getDriverSByVehicleTypeId($id);
$str="";
foreach($result as $branch){
$str=$str . "\"$branch[driver_id]\"".",". "\"$branch[driver_name]\"".",";
}
$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
echo "new Array($str)";
}
?>