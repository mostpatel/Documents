<?php
#Basic Line

require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";

$result = mysql_query("SELECT COUNT(DISTINCT ems_enquiry_form.enquiry_form_id) as total, YEAR(enquiry_date) as y, MONTHNAME(enquiry_date) as m FROM ems_enquiry_form WHERE enquiry_date>='2015/01/01' AND enquiry_date<='2015/12/01' GROUP BY y, m");



$bln = array();
$bln['name'] = 'Month';
$rows['name'] = 'No Of Enquiries';
while ($r = mysql_fetch_array($result)) 
{
    $bln['data'][] = $r['m'];
    $rows['data'][] = $r['total'];
}
$rslt = array();
array_push($rslt, $bln);
array_push($rslt, $rows);

print json_encode($rslt, JSON_NUMERIC_CHECK);

mysql_close($con);


