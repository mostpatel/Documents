<?php require_once '../lib/cg.php';
?>

<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];

      
$sql = "SELECT customer_id,customer_name FROM edms_customer WHERE our_company_id=$oc_id  AND  customer_name LIKE '%".$_REQUEST['term']."%' 
      AND is_deleted=0";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['customer_name']." | C".$r['customer_id']);
}


	
echo json_encode($results); 

   




?>