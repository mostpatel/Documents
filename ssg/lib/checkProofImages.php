<?php
require_once('cg.php');
require_once('bd.php');
$file_number_array = array();
$sql="SELECT insurance_img_href, file_number FROM fin_insurance_img, fin_vehicle_insurance, fin_file WHERE fin_insurance_img.insurance_id = fin_vehicle_insurance.insurance_id   AND fin_file.file_id = fin_vehicle_insurance.file_id ";

$result = dbQuery($sql);
$resultArray = dbResultToArray($result);

foreach($resultArray as $re)
{
	
	$img_href = $re['insurance_img_href'];
	$file_number = $re['file_number'];
	$img_href  = WEB_ROOT."images/insurance_proof/".$img_href;

	if(file_exists($img_href))
	{}
	else
	$file_number_array[] = $file_number;
} 

$file_number_array=array_unique($file_number_array);
foreach($file_number_array as $file_number)
{ echo $file_number."<br>";}
 ?>