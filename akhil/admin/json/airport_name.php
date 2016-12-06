<?php require_once '../lib/cg.php';
require_once '../lib/bd.php';
require_once '../lib/common.php';
?>
<?php

$_REQUEST['term']= clean_data($_REQUEST['term']);


$sql = "SELECT CONCAT_WS('-',airport_name,airport_code,city_name,country_name) as airport_full_name FROM trl_airports WHERE airport_code LIKE '%".$_REQUEST['term']."%' 
       OR "."airport_name LIKE '%".$_REQUEST['term']."%' OR "."city_name LIKE '%".$_REQUEST['term']."%' OR "."country_name LIKE '%".$_REQUEST['term']."%' ";

	   
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);

foreach ($resultArray as $r) 
{
	
    $results[] = array('label' => $r['airport_full_name']);
}


echo json_encode($results); 
?>