<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/vehicle-functions.php";
require_once "../../../lib/vehicle-model-functions.php";
require_once "../../../lib/insurance-company-functions.php";
require_once "../../../lib/insurance-percentage-functions.php";


if(isset($_GET['id'], $_GET['state']))
{
	

	
$insurance_company_id=$_GET['id'];
$vehicle_id=$_GET['state'];

$vehicleDetails = getVehicleDetailsById($vehicle_id);

$reg_date = $vehicleDetails['vehicle_reg_date'];
$period = getDateDifference($reg_date);


$vehicle_model_id = $vehicleDetails['vehicle_model_id'];

$vehicleModelDetails = getVehicleModelById($vehicle_model_id);

$vehicle_type_id = $vehicleModelDetails['vehicle_type_id'];
$vehicle_cc_id = $vehicleModelDetails['vehicle_cc_id'];



$result=array();

$insure = getInsurancePercentageByRelatedInfo($insurance_company_id, $vehicle_cc_id, $vehicle_type_id, $period);

$str="";

$str=$str . "\"$insure[percentage]\"".",". "\"$insure[liablity_premium]\"".",". "\"$insure[compulsory_pa]\"".",". "\"$insure[pa_paid_driver]\"".",";

$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
echo "new Array($str)";


}

?>