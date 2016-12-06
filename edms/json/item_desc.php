<?php require_once '../lib/cg.php';
?>

<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
$regno=$_REQUEST['term'];
$regno=clean_data($regno);
      
$sql = "SELECT item_desc FROM edms_ac_sales_item
,edms_ac_sales WHERE edms_ac_sales_item.sales_id = edms_ac_sales.sales_id  AND item_desc LIKE '%".$regno."%'
       AND to_customer_id IS NOT NULL";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['item_desc']);
}


	
echo json_encode($results); 

?>