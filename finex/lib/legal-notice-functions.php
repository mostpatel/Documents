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
			$sql="SELECT legal_notice_id, notice_date, case_no,remarks, fin_advocate.advocate_id, advocate_name, fin_case_type.case_type_id, case_type, fin_court.court_id, court, file_id, stage, next_date, type, cheque_return_id, fin_legal_notice.created_by, fin_legal_notice.last_modified_by, fin_legal_notice.date_added, fin_legal_notice.date_modified, fin_legal_notice.case_petetionar_id, case_petetionar, prev_date, warrant FROM fin_legal_notice 
			LEFT JOIN fin_advocate ON fin_advocate.advocate_id = fin_legal_notice.advocate_id 
			LEFT JOIN fin_case_petetionar ON fin_case_petetionar.case_petetionar_id = fin_legal_notice.case_petetionar_id 
			LEFT JOIN fin_case_type ON fin_case_type.case_type_id = fin_legal_notice.case_type_id 
			LEFT JOIN fin_court ON fin_court.court_id = fin_legal_notice.court_id  WHERE file_id=$file_id ORDER BY notice_date DESC";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray;
			else
			return false;
			}
			return false;
	}
	catch(Exception $e)
	{
	}
	
}

function listFinishNoticesForFileID($file_id){
	
	try
	{
		if(checkForNumeric($file_id))
		{
			$sql="SELECT legal_notice_id, notice_date, case_no,remarks, fin_advocate.advocate_id, advocate_name, fin_case_type.case_type_id, case_type, fin_court.court_id, court, file_id, stage, next_date, type, cheque_return_id, fin_legal_notice_finished.created_by, fin_legal_notice_finished.last_modified_by, fin_legal_notice_finished.date_added, fin_legal_notice_finished.date_modified, fin_legal_notice_finished.case_petetionar_id, case_petetionar, finish_date, finished_by, finish_remarks FROM fin_legal_notice_finished
			LEFT JOIN fin_advocate ON fin_advocate.advocate_id = fin_legal_notice_finished.advocate_id 
			LEFT JOIN fin_case_petetionar ON fin_case_petetionar.case_petetionar_id = fin_legal_notice_finished.case_petetionar_id 
			LEFT JOIN fin_case_type ON fin_case_type.case_type_id = fin_legal_notice_finished.case_type_id 
			LEFT JOIN fin_court ON fin_court.court_id = fin_legal_notice_finished.court_id  WHERE file_id=$file_id ORDER BY notice_date DESC";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray;
			else
			return false;
			}
			return false;
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

function insertLegalNotice($file_id,$notice_date,$case_no="NA",$remarks = "NA",$advocate_id = NULL, $case_type_id = NULL, $court_id = NULL,$stage="",$next_date = '1970-01-01', $type=0,$cheque_return_id=NULL,$case_petetionar_id = 'NULL',$warrant=0){
	
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
		 
		  if(!checkForNumeric($advocate_id)) 
		 $advocate_id="NULL";
		 if(!checkForNumeric($case_type_id)) 
		 $case_type_id="NULL";
		 if(!checkForNumeric($court_id)) 
		 $court_id="NULL";
		 if(!checkForNumeric($cheque_return_id)) 
		 $cheque_return_id="NULL";
		 if(!checkForNumeric($case_petetionar_id)) 
		 $case_petetionar_id="NULL";
		 
		 if(!validateForNull($next_date)) 
		 $next_date="1970-01-01";
		 
		 if(!checkForNumeric($warrant))
		 $warrant=0;
		
		 if(checkForNumeric($file_id,$warrant) && validateForNull($notice_date) && !checkForDuplicateCase($file_id,$case_no))
		 {
		
		$notice_date = str_replace('/', '-', $notice_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$notice_date = date('Y-m-d',strtotime($notice_date)); // converts date to Y-m-d format
		
		$next_date = str_replace('/', '-', $next_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$next_date = date('Y-m-d',strtotime($next_date)); // converts date to Y-m-d format
				 
		$sql="INSERT INTO fin_legal_notice(notice_date, case_no,remarks, advocate_id, case_type_id, court_id, file_id, stage, next_date, type, cheque_return_id, created_by, last_modified_by, date_added, date_modified, case_petetionar_id, warrant) VALUES ('$notice_date', '$case_no','$remarks', $advocate_id, $case_type_id, $court_id,  $file_id, '$stage', '$next_date', $type, $cheque_return_id,  $admin_id, $admin_id, NOW(), NOW(),$case_petetionar_id,$warrant)";
		$result=dbQuery($sql);
		return dbInsertId();
		 }
		 return "error";
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateCase($file_id,$case_no,$id=false)
{
	
	if(checkForNumeric($file_id) && validateForNull($case_no) && $case_no!="NA")
	{
		$sql="SELECT legal_notice_id FROM fin_legal_notice WHERE case_no='$case_no'";
		
		if(is_numeric($id))
		$sql=$sql." AND legal_notice_id!=$id";
		$result = dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
	}
	return false;
	
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

function finishLegalNotice($legal_notice_id,$finish_date,$finish_remarks)
{
	if(checkForNumeric($legal_notice_id) && validateForNull($finish_date))
	{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$legal_notice=getLegalNoticeById($legal_notice_id);
		$file_id= $legal_notice['file_id'];
		deleteLegalNotice($legal_notice_id);
		
		$notice_date  = $legal_notice['notice_date'];
		$case_no=$legal_notice['case_no'];
		$remarks = $legal_notice['remarks'];
		$advocate_id = $legal_notice['advocate_id'];
		$case_type_id = $legal_notice['case_type_id'];
		$court_id = $legal_notice['court_id'];
		$stage=$legal_notice['stage'];
		$next_date = $legal_notice['next_date'];
		$type=$legal_notice['type'];
		$cheque_return_id=$legal_notice['cheque_return_id'];
		$case_petetionar_id = $legal_notice['case_petetionar_id'];
		
		 if(!validateForNull($case_no)) 
		 $case_no="NA";
		  if(!validateForNull($remarks)) 
		 $remarks="NA";
		 
		  if(!checkForNumeric($advocate_id)) 
		 $advocate_id="NULL";
		 if(!checkForNumeric($case_type_id)) 
		 $case_type_id="NULL";
		 if(!checkForNumeric($court_id)) 
		 $court_id="NULL";
		 if(!checkForNumeric($cheque_return_id)) 
		 $cheque_return_id="NULL";
		 if(!checkForNumeric($case_petetionar_id)) 
		 $case_petetionar_id="NULL";
		 
		 if(!validateForNull($next_date)) 
		 $next_date="1970-01-01";
		 
		 if(checkForNumeric($file_id) && validateForNull($case_no,$remarks,$notice_date))
		 {
		
		$notice_date = str_replace('/', '-', $notice_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$notice_date = date('Y-m-d',strtotime($notice_date)); // converts date to Y-m-d format
		
		$finish_date = str_replace('/', '-', $finish_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$finish_date = date('Y-m-d',strtotime($finish_date)); // converts date to Y-m-d format
		
		$next_date = str_replace('/', '-', $next_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$next_date = date('Y-m-d',strtotime($next_date)); // converts date to Y-m-d format
				 
		$sql="INSERT INTO fin_legal_notice_finished(notice_date, case_no,remarks, advocate_id, case_type_id, court_id, file_id, stage, next_date, type, cheque_return_id, created_by, last_modified_by, date_added, date_modified, case_petetionar_id,finish_date,finished_by, finish_remarks) VALUES ('$notice_date', '$case_no','$remarks', $advocate_id, $case_type_id, $court_id,  $file_id, '$stage', '$next_date', $type, $cheque_return_id,  $admin_id, $admin_id, NOW(), NOW(),$case_petetionar_id,'$finish_date',$admin_id,'$finish_remarks')";
		$result=dbQuery($sql);
		return dbInsertId();
		 }
		
	}
}

function updateLegalNotice($legal_notice_id,$file_id,$notice_date,$case_no="NA",$remarks = "NA",$advocate_id = NULL,$case_type_id = NULL,$court_id=NULL,$stage="",$next_date = '1970-01-01',$type=0,$cheque_return_id=NULL,$case_petetionar_id='NULL',$warrant=0){
	
	try
	{
		 $legal_notice=getLegalNoticeById($legal_notice_id);
		 $loan_id=getLoanIdFromFileId($file_id);
		 $admin_id=$_SESSION['adminSession']['admin_id'];
		 $notice_date=clean_data($notice_date);
		 $case_no = clean_data($case_no);
		 $remarks = clean_data($remarks);
		 $prev_date = $legal_notice['next_date'];
		 if(!validateForNull($case_no)) 
		 $case_no="NA";
		 
		 if(!validateForNull($remarks)) 
		 $remarks="NA";
		 
		  if(!checkForNumeric($advocate_id)) 
		 $advocate_id="NULL";
		 if(!checkForNumeric($case_type_id)) 
		 $case_type_id="NULL";
		 if(!checkForNumeric($court_id)) 
		 $court_id="NULL";
		 if(!checkForNumeric($cheque_return_id)) 
		 $cheque_return_id="NULL";
		 
		 if(!checkForNumeric($case_petetionar_id)) 
		 $case_petetionar_id="NULL";
		 
		 if(!checkForNumeric($warrant)) 
		 $warrant=0;
		 
		 if(checkForNumeric($file_id,$legal_notice_id) && validateForNull($case_no,$remarks,$notice_date) && !checkForDuplicateCase($file_id,$case_no,$legal_notice_id))
		 {
		
		$notice_date = str_replace('/', '-', $notice_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$notice_date = date('Y-m-d',strtotime($notice_date)); // converts date to Y-m-d format
		
		$next_date = str_replace('/', '-', $next_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$next_date = date('Y-m-d',strtotime($next_date)); // converts date to Y-m-d format
				 
		$sql="UPDATE fin_legal_notice SET notice_date = '$notice_date', case_no = '$case_no',remarks = '$remarks', advocate_id = $advocate_id, case_type_id = $case_type_id, court_id = $court_id,  stage = '$stage', next_date = '$next_date', type = $type,  last_modified_by = $admin_id, date_modified = NOW(), case_petetionar_id = $case_petetionar_id, prev_date = '$prev_date', warrant= $warrant WHERE legal_notice_id = $legal_notice_id";
		$result=dbQuery($sql);
		return "success";
		 }
		 return "error";
			
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
			$sql="SELECT legal_notice_id, notice_date, case_no,remarks, advocate_id, case_type_id, court_id, file_id, stage, next_date, type, cheque_return_id, fin_legal_notice.case_petetionar_id, case_petetionar, warrant  FROM fin_legal_notice LEFT JOIN fin_case_petetionar ON fin_case_petetionar.case_petetionar_id = fin_legal_notice.case_petetionar_id WHERE legal_notice_id=$id";
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