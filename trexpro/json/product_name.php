<?php require_once '../lib/cg.php';
?>
<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];

      
$sql = "SELECT product_id,product_name FROM edms_product WHERE  product_name LIKE '%".$_REQUEST['term']."%'";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['product_name']);
}


	
echo json_encode($results); 

   




?>