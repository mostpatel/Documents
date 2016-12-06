<?php require_once '../lib/cg.php';
?>

<?php


      
$sql = "SELECT customer_email FROM ems_customer WHERE customer_email LIKE '%".$_REQUEST['term']."%'";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['customer_email']);
}


	
echo json_encode($results); 

   




?>