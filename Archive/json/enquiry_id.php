<?php require_once '../lib/cg.php';
?>

<?php


$sql = "SELECT DISTINCT unique_enquiry_id FROM ems_enquiry_form WHERE unique_enquiry_id LIKE '%".$_REQUEST['term']."%'";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);

foreach ($resultArray as $r) 
{
	
    $results[] = array('label' => $r['unique_enquiry_id']);
}



echo json_encode($results); 

?>