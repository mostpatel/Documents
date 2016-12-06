<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("file-functions.php");
require_once("loan-functions.php");
require_once("common.php");
require_once("bd.php");


function listLegalNoticesForFileID($file_id){
	
	try
	{
		if(checkForNumeric($file_id))
		{
			$sql="SELECT legal_notice_id, notice_date, case_no,remarks, file_id, created_by, last_modified_by, date_added, date_modified FROM fin_legal_notice WHERE file_id=$file_id ORDER BY notice_date DESC";
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

function getNumberOfLegalNoticesForFileID($file_id)
{
	if(checkForNumeric($file_id))
	{
		
		$sql="SELECT count(legal_notice_id) FROM fin_legal_notice WHERE file_id=$file_id ";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0][0];
			else
			return 0;
		
		}
	
}

function getLatestLegalNoticeDateForFile($file_id)
{
	if(checkForNumeric($file_id))
	{
		
		$sql="SELECT notice_date FROM fin_legal_notice WHERE file_id=$file_id ORDER BY notice_date DESC";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0][0];
			else
			return 0;
		
		}
	}

function insertLegalNotice($file_id,$notice_date,$case_no="NA",$remarks = "NA"){
	
	try
	{
		 $loan_id=getLoanIdFromFileId($file_id);
		 $admin_id=$_SESSION['adminSession']['admin_id'];
		 $notice_date=clean_data($notice_date);
		 $case_no = clean_data($case_no);
		 $remarks = clean_data($remarks);
		 
		 if(!validateForNull($case_no)) 
		 $case_no="NA";
		  if(!validateForNull($remarks)) 
		 $remarks="NA";
		 
		 
		 if(checkForNumeric($file_id) && validateForNull($case_no,$remarks,$notice_date))
		 {
		
		$notice_date = str_replace('/', '-', $notice_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$notice_date = date('Y-m-d',strtotime($notice_date)); // converts date to Y-m-d format
				 
		$sql="INSERT INTO fin_legal_notice(notice_date,  case_no,remarks, file_id, created_by, last_modified_by, date_added, date_modified) VALUES ('$notice_date', '$case_no','$remarks', $file_id, $admin_id, $admin_id, NOW(), NOW())";
	
		$result=dbQuery($sql);
		return dbInsertId();
		 }
		 return "error";
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteLegalNotice($id){
	
	try
	{
		if(checkForNumeric($id))
		{
			$sql="DELETE FROM fin_legal_notice WHERE legal_notice_id=$id";
			dbQuery($sql);
			return "success";
			}
		return "error";	
	}
	catch(Exception $e)
	{
	}
	
}	

function updateLegalNotice($legal_notice_id,$file_id,$notice_date,$customer_name,$customer_address,$bucket,$bucket_amount){
	
	try
	{
	}
	catch(Exception $e)
	{
	}
	
}	

function getLegalNoticeById($id){
	
	try
	{
		
		if(checkForNumeric($id))
		{
			$sql="SELECT legal_notice_id, notice_date, case_no,remarks, file_id, created_by, last_modified_by, date_added, date_modified FROM fin_legal_notice WHERE legal_notice_id=$id";
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