<?php require_once '../lib/cg.php';
?>
<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];

$regno=$_REQUEST['term'];   
$sql = "SELECT lr_no FROM edms_lr,edms_customer WHERE our_company_id=$oc_id AND lr_no LIKE '%".$regno."%' 
       AND edms_customer.customer_id=edms_lr.to_customer_id AND is_deleted=0";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['lr_no']);
}


	
echo json_encode($results);
?>