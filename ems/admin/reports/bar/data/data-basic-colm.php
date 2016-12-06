<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";

$result = mysql_query("SELECT COUNT(DISTINCT ems_enquiry_form.enquiry_form_id) as total, YEAR(enquiry_date) as y, MONTHNAME(enquiry_date) as m, (SELECT COUNT(DISTINCT em.enquiry_form_id) as successful FROM ems_enquiry_form as em WHERE enquiry_date>='2016/01/01' AND enquiry_date<='2016/12/01' AND is_bought=1 AND YEAR(ems_enquiry_form.enquiry_date) = YEAR(em.enquiry_date) AND MONTHNAME(ems_enquiry_form.enquiry_date) = MONTHNAME(em.enquiry_date) GROUP BY YEAR(em.enquiry_date), MONTHNAME(em.enquiry_date)) as success FROM ems_enquiry_form WHERE enquiry_date>='2016/01/01' AND enquiry_date<='2016/12/01' GROUP BY y, m ORDER BY MONTH(enquiry_date)");

print_r($result);

$result1 = mysql_query("");

$bln = array();

$bln['name'] = 'Month';

$rows = array();
$rows['name'] = 'No Of Enquiries';

$result1 = array();
$result1['name'] = 'Successful';


while ($r = mysql_fetch_array($result)) 
{
	
    $bln['data'][] = $r['m'];
    $rows['data'][] = $r['total'];
	$result1['data'][] = $r['success'];
}
$rslt = array();
array_push($rslt, $bln);
array_push($rslt, $rows);
array_push($rslt, $result1);
print json_encode($rslt, JSON_NUMERIC_CHECK);

mysql_close($con);

?>





