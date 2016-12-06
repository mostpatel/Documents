<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("file-functions.php");
require_once("loan-functions.php");
require_once("common.php");
require_once("bd.php");


function listNoticesForFileID($file_id){
	
	try
	{
		if(checkForNumeric($file_id))
		{
			$sql="SELECT notice_id, notice_date, customer_name, customer_address, guarantor_name, guarantor_address, bucket, bucket_amount, file_id, created_by, last_modified_by, date_added, date_modified, notice_type FROM fin_loan_notice WHERE file_id=$file_id ORDER BY notice_date DESC";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray;
			else
			return false;
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfNoticesForFileID($file_id)
{
	if(checkForNumeric($file_id))
	{
		
		$sql="SELECT count(notice_id) FROM fin_loan_notice WHERE file_id=$file_id ";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0][0];
			else
			return 0;
		
		}
	
}

function getLatestNoticeDateForFile($file_id)
{
	if(checkForNumeric($file_id))
	{
		
		$sql="SELECT notice_date FROM fin_loan_notice WHERE file_id=$file_id ORDER BY notice_date DESC";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0][0];
			else
			return 0;
		
		}
	}

function insertNotice($file_id,$notice_date,$customer_name,$customer_address,$guarantor_name, $guarantor_address ,$bucket=null,$bucket_amount=null,$notice_type=NULL){
	
	try
	{
		 $loan_id=getLoanIdFromFileId($file_id);
		 $admin_id=$_SESSION['adminSession']['admin_id'];
		 $customer_name=clean_data($customer_name);
		 $customer_address=clean_data($customer_address);
$guarantor_name_name=clean_data($guarantor_name);
		 $guarantor_address=clean_data($guarantor_address);
		 $notice_date=clean_data($notice_date);
		 
		 if(!validateForNull($bucket))
		 $bucket=getBucketForLoan($loan_id); 
		 
		  if(!validateForNull($bucket_amount))
	     $bucket_amount=getTotalBucketAmountForLoan($loan_id);
		 
		 $bucket=clean_data($bucket);
		 $bucket_amount=clean_data($bucket_amount);
		 
		 if(checkForNumeric($file_id) && validateForNull($customer_name,$customer_address,$notice_date))
		 {
		$customer_address='<pre>'.$customer_address.'</pre>';

$guarantor_address='<pre>'.$guarantor_address.'</pre>';
		$notice_date = str_replace('/', '-', $notice_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$notice_date = date('Y-m-d',strtotime($notice_date)); // converts date to Y-m-d format
				 
		$sql="INSERT INTO fin_loan_notice(notice_date,  customer_name, customer_address, guarantor_name, guarantor_address, bucket, bucket_amount, file_id, created_by, last_modified_by, date_added, date_modified,notice_type) VALUES ('$notice_date', '$customer_name', '$customer_address', '$guarantor_name', '$guarantor_address', '$bucket', '$bucket_amount', $file_id, $admin_id, $admin_id, NOW(), NOW(),$notice_type)";
	
		$result=dbQuery($sql);
		return dbInsertId();
		 }
		 return "error";
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteNotice($id){
	
	try
	{
		if(checkForNumeric($id))
		{
			$sql="DELETE FROM fin_loan_notice WHERE notice_id=$id";
			dbQuery($sql);
			return "success";
			}
		return "error";	
	}
	catch(Exception $e)
	{
	}
	
}	

function updateNotice($notice_id,$file_id,$notice_date,$customer_name,$customer_address,$bucket,$bucket_amount){
	
	try
	{
	}
	catch(Exception $e)
	{
	}
	
}	

function getNoticeById($id){
	
	try
	{
		
		if(checkForNumeric($id))
		{
			$sql="SELECT notice_id, notice_date, customer_name, customer_address, guarantor_name, guarantor_address, bucket, bucket_amount, file_id, created_by, last_modified_by, date_added, date_modified, notice_type FROM fin_loan_notice WHERE notice_id=$id";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0];
			else
			return false;
			}
	}
	catch(Exception $e)
	{
	}
	
}		
?>