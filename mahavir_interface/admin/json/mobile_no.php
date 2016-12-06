<?php require_once '../lib/cg.php';
?>

<?php


$sql = "SELECT customer_contact_no FROM ems_customer_contact_no WHERE customer_contact_no LIKE '%".$_REQUEST['term']."%'";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['customer_contact_no']);
}


$results=array_unique($results);
echo json_encode($results); 

?>