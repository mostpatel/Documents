<?php require_once '../lib/cg.php';
require_once '../lib/bd.php';
require_once '../lib/common.php';
?>
<?php
$return_id=0;
$contact_no_str=$_GET['q'];
$contact_no_array=explode(',',$contact_no_str);

foreach($contact_no_array as $contact_no)
{
	if(is_numeric($contact_no) && $contact_no!=1234567890)
	{
	$sql = "SELECT customer_id FROM ems_customer_contact_no WHERE customer_contact_no =".$contact_no."
	UNION ALL SELECT customer_id FROM  ems_invoice_customer_contact_no, ems_invoice_customer WHERE in_customer_contact_no =".$contact_no." AND ems_invoice_customer_contact_no.in_customer_id=ems_invoice_customer.in_customer_id ";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	{
		$return_id=$resultArray[0][0];
		break;
		}
	
	}
}

echo $return_id;

?>