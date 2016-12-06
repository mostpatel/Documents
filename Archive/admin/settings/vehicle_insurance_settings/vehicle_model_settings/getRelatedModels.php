<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/vehicle-cc-functions.php";
require_once "../../../../lib/vehicle-type-functions.php";




if(isset($_GET['id'])){
$id=$_GET['id'];

$result=array();

$result=getAllVehicleCCForAVehicleTypeId($id);

$str="";
foreach($result as $cc){
$str=$str . "\"$cc[vehicle_cc_id]\"".",". "\"$cc[vehicle_cc]\"".",";
}
$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
echo "new Array($str)";

}

?>