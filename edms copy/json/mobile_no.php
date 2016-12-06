<?php require_once '../lib/cg.php';
?>

<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];

      
$sql = "SELECT customer_contact_no FROM edms_customer,edms_customer_contact_no WHERE  customer_contact_no LIKE '%".$_REQUEST['term']."%' 
        AND edms_customer.customer_id=edms_customer_contact_no.customer_id AND is_deleted=0";
		 if(defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
	  $sql=$sql." AND our_company_id=$oc_id  ";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['customer_contact_no']);
}


	$results=array_unique($results);
echo json_encode($results); 

?>