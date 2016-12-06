<?php require_once '../lib/cg.php';
?>

<?php
$oc_id=$_SESSION['EMSadminSession']['oc_id'];

      
$sql = "SELECT refrence_name FROM ems_refrence_name WHERE refrence_name LIKE '%".$_REQUEST['term']."%'";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['refrence_name']);
}

echo json_encode($results); 

?>