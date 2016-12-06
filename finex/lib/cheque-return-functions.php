<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("bank-functions.php");
require_once("file-functions.php");
require_once("loan-functions.php");
require_once("customer-functions.php");
require_once("guarantor-functions.php");
require_once("common.php");
require_once("bd.php");

function getLatestChequeReturnDateForFileId($file_id,$type=NULL)
{
	
	if(checkForNumeric($file_id))
	{
		$sql="SELECT cheque_return_id,cheque_amount,type,cheque_no,cheque_date,fin_loan_cheque_return.bank_id,bank_name,fin_loan_cheque_return.branch_id,branch_name,slip_no,fin_loan_cheque_return.created_by,fin_loan_cheque_return.last_updated_by,fin_loan_cheque_return.date_added,fin_loan_cheque_return.date_modified,file_id,type, received, not_received_type_id, received_date, remarks FROM fin_loan_cheque_return,fin_bank,fin_bank_branch WHERE file_id = $file_id AND fin_bank.bank_id = fin_loan_cheque_return.bank_id AND fin_bank_branch.branch_id = fin_loan_cheque_return.branch_id";
		if(is_numeric($type))
		$sql=$sql." AND type = $type ";
		$sql=$sql."  ORDER BY cheque_date DESC";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
			if(dbNumRows($result)>0)
			return $resultArray[0];
			else
			return false;
		
	}
	return false;
}

function getChequeReturnDetailsForId($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT cheque_return_id,cheque_amount,type,cheque_no,cheque_date,fin_loan_cheque_return.bank_id,bank_name,fin_loan_cheque_return.branch_id,branch_name,slip_no,fin_loan_cheque_return.created_by,fin_loan_cheque_return.last_updated_by,fin_loan_cheque_return.date_added,fin_loan_cheque_return.date_modified,file_id,type, received, not_received_type_id, received_date FROM fin_loan_cheque_return,fin_bank,fin_bank_branch WHERE cheque_return_id = $id AND fin_bank.bank_id = fin_loan_cheque_return.bank_id AND fin_bank_branch.branch_id = fin_loan_cheque_return.branch_id";
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
			if(dbNumRows($result)>0)
			return $resultArray[0];
			else
			return false;
		
	}
	return false;
}

function ListChequeReturnsForFileId($file_id)
{
	
	if(checkForNumeric($file_id))
	{
		$sql="SELECT cheque_return_id,cheque_no,cheque_amount,type,cheque_date,fin_loan_cheque_return.bank_id,bank_name,fin_loan_cheque_return.branch_id,branch_name,slip_no,fin_loan_cheque_return.created_by,fin_loan_cheque_return.last_updated_by,fin_loan_cheque_return.date_added,fin_loan_cheque_return.date_modified,file_id, received, not_received_type_id, received_date FROM fin_loan_cheque_return,fin_bank,fin_bank_branch WHERE file_id = $file_id AND fin_bank.bank_id = fin_loan_cheque_return.bank_id AND fin_bank_branch.branch_id = fin_loan_cheque_return.branch_id  ORDER BY cheque_date DESC";
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
			if(dbNumRows($result)>0)
			return $resultArray;
			else
			return false;
		
	}
	
}

function insertLoanChequeReturn($file_id,$cheque_no,$cheque_date,$bank_name,$branch,$slip_no,$remarks,$cheque_amount,$type=0)
{
						$bank_array=insertIfNotDuplicateBank($bank_name,$branch);
						$bank_id=$bank_array[0];
						$branch_id=$bank_array[1];
						$cheque_no = clean_data($cheque_no);
						$cheque_date = clean_data($cheque_date);
						$bank_name = clean_data($bank_name);
						$branch = clean_data($branch);
						$slip_no = clean_data($slip_no);
						$remarks = clean_data($remarks);
						
						
				
	if(checkForNumeric($file_id,$cheque_no,$bank_id,$branch_id,$cheque_amount,$type) && validateForNull($cheque_date))
	{
		 $admin_id=$_SESSION['adminSession']['admin_id'];
		 if(checkForNumeric($admin_id))
		 {
			  $cheque_date = str_replace('/', '-', $cheque_date);// converts dd/mm/yyyy to dd-mm-yyyy
			  $cheque_date=date('Y-m-d',strtotime($cheque_date)); // converts date to Y-m-d format
			  
			  
				$sql="INSERT INTO fin_loan_cheque_return ( `cheque_amount`,`cheque_no`, `cheque_date`, `bank_id`, `branch_id`, `slip_no`, file_id,type, remarks, `created_by`, `last_updated_by`, `date_added`, `date_modified`) VALUES ($cheque_amount,'$cheque_no', '$cheque_date', $bank_id, $branch_id, '$slip_no', $file_id, $type, '$remarks', $admin_id, $admin_id, NOW(),NOW())";
				$result = dbQuery($sql);
				$cheque_return_id = dbInsertId();
				
		 }
	}
	return $cheque_return_id;
}


function updateChequeReturn($id,$cheque_no,$cheque_date,$bank_name,$branch,$slip_no,$remarks,$cheque_amount,$received=0,$not_received_type_id=NULL,$received_date="01/01/1970")
{
						$bank_array=insertIfNotDuplicateBank($bank_name,$branch);
						$bank_id=$bank_array[0];
						$branch_id=$bank_array[1];
						$cheque_no = clean_data($cheque_no);
						$cheque_date = clean_data($cheque_date);
						$bank_name = clean_data($bank_name);
						$branch = clean_data($branch);
						$slip_no = clean_data($slip_no);
						$remarks = clean_data($remarks);
		if(!validateForNull($not_received_type_id) || $received!=2)
		 $not_received_type_id="NULL";
		 
		  if(!validateForNull($received_date))
		 $received_date=getTodaysDate();
		 
		$received_date = str_replace('/', '-', $received_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$received_date = date('Y-m-d',strtotime($received_date)); // converts date to Y-m-d format	
				
	if(checkForNumeric($id,$cheque_no,$bank_id,$branch_id,$cheque_amount) && validateForNull($cheque_date))
	{
		 $admin_id=$_SESSION['adminSession']['admin_id'];
		 if(checkForNumeric($admin_id))
		 {
			  $cheque_date = str_replace('/', '-', $cheque_date);// converts dd/mm/yyyy to dd-mm-yyyy
			  $cheque_date=date('Y-m-d',strtotime($cheque_date)); // converts date to Y-m-d format
			  
			  
				$sql="UPDATE fin_loan_cheque_return SET `cheque_amount` = $cheque_amount,`cheque_no` = '$cheque_no', `cheque_date` = '$cheque_date', `bank_id` = $bank_id, `branch_id` = $branch_id, `slip_no` = '$slip_no',  remarks = '$remarks', `last_updated_by` = $admin_id,  `date_modified` = NOW(), received = $received, not_received_type_id = $not_received_type_id, received_date = '$received_date' WHERE cheque_return_id = $id";
				
				$result = dbQuery($sql);
				return "success";
		 }
	}
	return $cheque_return_id;
	
	
}

function deleteLoanChequeReturn($loan_cheque_return_id)
{
	
	if(checkForNumeric($loan_cheque_return_id))
	{
		$sql="DELETE FROM fin_loan_cheque_return WHERE cheque_return_id = $loan_cheque_return_id";
		dbQuery($sql);
		
		return "success";
	}
	return "error";
}

?>