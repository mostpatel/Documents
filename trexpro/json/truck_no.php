<?php require_once '../lib/cg.php';
require_once '../lib/truck-functions.php';
?>
<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];    
$truck_no = $_REQUEST['term'];
$truck_no = stripVehicleno($truck_no);
$sql = "SELECT truck_id,truck_no FROM edms_trucks WHERE  truck_no LIKE '%".$truck_no."%'";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['truck_no']);
}
echo json_encode($results); 
?>