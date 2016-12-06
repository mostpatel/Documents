<?php require_once '../lib/cg.php';
require_once '../lib/bd.php';
require_once '../lib/common.php';
?>
<?php

$_POST['term']= clean_data($_POST['term']);


$sql = "SELECT DISTINCT  CONCAT_WS('-',city_name,country_name) as airport_full_name FROM trl_airports WHERE city_name LIKE '%".$_POST['term']."%' OR "."country_name LIKE '%".$_POST['term']."%' ";

	   
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);

foreach ($resultArray as $r) 
{
	
    $results[] = array('label' => $r['airport_full_name']);
}


echo json_encode($results); 
?>