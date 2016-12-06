<?php require_once '../lib/cg.php';
?>

<?php
$oc_id=$_SESSION['EMSadminSession']['oc_id'];

      
$sql = "SELECT customer_name FROM ems_customer WHERE customer_name LIKE '%".$_REQUEST['term']."%'";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['customer_name']);
}


	
echo json_encode($results); 

   




?>