<?php require_once '../lib/cg.php';
require_once '../lib/common.php';
?>

<?php
$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		
$curent_companny = getCurrentCompanyForUser($admin_id);
$oc_id = $curent_companny[0];
      
$sql = "SELECT customer_name FROM edms_customer WHERE  customer_name LIKE '%".$_REQUEST['term']."%' 
      AND is_deleted=0 ";
	  if(defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
	  $sql=$sql." AND oc_id=$oc_id  ";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['customer_name']);
}


	
echo json_encode($results); 

   




?>