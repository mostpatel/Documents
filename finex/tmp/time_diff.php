<?php
require_once("../lib/cg.php");
require_once("../lib/bd.php");

$sql="SELECT * FROM fin_loan_notice WHERE notice_stage>0 AND file_id IN (SELECT file_id FROM fin_guarantor)";
$result = dbQuery($sql);
$resultArray = dbResultToArray($result);
print_r($resultArray);
foreach($resultArray as $re)
{
	$notice_date = $re['notice_date'];
	$customer_name = $re['customer_name'];
	$customer_address = $re['customer_address'];
	$guarantor_name = $re['guarantor_name'];
	$guarantor_address = $re['guarantor_address'];
	$bucket = $re['bucket'];	
	$bucket_amount = $re['bucket_amount'];
	$file_id = $re['file_id'];
	$note = $re['note'];
	$admin_id = $re['created_by'];
	$notice_stage = $re['notice_stage'];
	$reg_ad = $re['reg_ad'];
	$received = $re['received'];
	$received_date = $re['received_date'];
	$bulk_notice_id = $re['bulk_notice_id'];
	if(!is_numeric($bulk_notice_id))
	$bulk_notice_id="NULL";
	$advocate_id = $re['advocate_id'];
	if(!is_numeric($advocate_id))
	$advocate_id="NULL";
	$notice_type = $re['notice_type'];
	$sql="INSERT INTO fin_loan_notice(notice_date,  customer_name, customer_address, guarantor_name, guarantor_address, bucket, bucket_amount, file_id, note, created_by, last_modified_by, date_added, date_modified, notice_stage, reg_ad, received, received_date,bulk_notice_id,advocate_id,notice_type) VALUES ('$notice_date', '$customer_name', '$customer_address', '$guarantor_name', '$guarantor_address', '$bucket', '$bucket_amount', $file_id , '$note', $admin_id, $admin_id, NOW(), NOW(), $notice_stage, '$reg_ad', $received, '$received_date',$bulk_notice_id,$advocate_id,1)";
	dbQuery($sql);	
}
?>
