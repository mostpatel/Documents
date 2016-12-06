<?php require_once '../lib/cg.php';
 require_once '../lib/vehicle-functions.php';
?>
<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];

$regno=$_REQUEST['term'];
$regno=stripVehicleno($regno);   
$sql = "SELECT vehicle_reg_no FROM edms_customer,edms_vehicle WHERE our_company_id=$oc_id AND vehicle_reg_no LIKE '%".$regno."%' 
       AND edms_customer.customer_id=edms_vehicle.customer_id AND is_deleted=0";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['vehicle_reg_no']);
}


	
echo json_encode($results);
?>