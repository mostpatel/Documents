<?php require_once '../lib/cg.php';
?>
<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
$regno=$_REQUEST['term'];   
$sql = "SELECT trip_memo_summary_no FROM edms_trip_memo_summary WHERE  trip_memo_summary_no LIKE '%".$regno."%' ";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['trip_memo_summary_no']);
}


	
echo json_encode($results);
?>