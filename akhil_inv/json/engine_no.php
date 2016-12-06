<?php require_once '../lib/cg.php';
require_once '../lib/common.php';
?>
<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
$regno=$_REQUEST['term'];
$regno=clean_data($regno);
  
$sql = "SELECT vehicle_engine_no FROM edms_customer,edms_vehicle WHERE our_company_id=$oc_id AND vehicle_engine_no LIKE '%".$regno."%'
       AND edms_customer.customer_id=edms_vehicle.customer_id AND is_deleted=0";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['vehicle_engine_no']);
}
echo json_encode($results); 
?>