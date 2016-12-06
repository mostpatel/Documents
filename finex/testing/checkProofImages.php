<?php
require_once('../lib/cg.php');
require_once('../lib/bd.php');
echo "here";
$sql="SELECT customer_proof_img_href, file_number FROM fin_customer_proof_img,fin_customer_proof, fin_customer, fin_file WHERE fin_customer_proof_img.customer_proof_id = fin_customer_proof.customer_proof_id AND fin_customer.customer_id = fin_customer_proof.customer_id  AND fin_file.file_id = fin_customer.file_id ";
$result = dbQuery($sql);
$resultArray = dbResultToArray($resultArray);
print_r($resultArray);
foreach($resultArray as $re)
{
	print_r($re);
	$img_href = $re['customer_proof_img_href'];
	$file_number = $re['file_number'];
	$img_href  = WEB_ROOT."images/customer_proof/".$img_href;
	echo $img_href;
	if(file_exists($img_href))
	{}
	else
	echo $file_number;
} 
 ?>