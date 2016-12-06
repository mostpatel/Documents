<?php require_once '../lib/cg.php';
?>
<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];

      
$sql = "SELECT packing_unit_id FROM edms_packing_unit WHERE  packing_unit LIKE '%".$_REQUEST['term']."%'";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['packing_unit']);
}


	
echo json_encode($results); 

   




?>