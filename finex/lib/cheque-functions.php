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


function ListChequesForFileId($file_id)
{
	
	if(checkForNumeric($file_id))
	{
		$sql="SELECT cheque_details_id,fin_customer_cheque_details.bank_id,fin_customer_cheque_details.branch_id,required_cheques,cheques_received,bank_name,used_cheques,unused_cheques,file_id,customer_id,branch_name,remarks, ac_no FROM fin_customer_cheque_details
,fin_bank,fin_bank_branch WHERE file_id = $file_id AND fin_bank.bank_id = fin_customer_cheque_details.bank_id AND fin_bank_branch.branch_id = fin_customer_cheque_details.branch_id";
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
			if(dbNumRows($result)>0)
			return $resultArray[0];
			else
			return false;
		
	}
	
}

function insertLoanCheques($file_id,$bank_name,$branch,$required_cheques,$cheques_received,$used_cheques,$unused_cheques,$customer_id,$remarks,$cheque_no_array,$ac_no="")
{
						$bank_array=insertIfNotDuplicateBank($bank_name,$branch);
						$bank_id=$bank_array[0];
						$branch_id=$bank_array[1];
						
						if(is_numeric($cheque_no_array[1]))
						{
							$first_cheque_no = $cheque_no_array[1];
							
							for($i=2,$j=1;$i<=$cheques_received;$i++,$j++)
							{
								$cheque_no = $first_cheque_no+$j;
								$cheque_no=str_pad($cheque_no,6,"0",STR_PAD_LEFT);
								$cheque_no_array[$i] = $cheque_no;
								
							}
							
						}
						
						if(!checkForNumeric($ac_no))
						$ac_no="";
									
	if(checkForNumeric($file_id,$required_cheques,$bank_id,$branch_id,$cheques_received,$used_cheques,$unused_cheques,$customer_id) && !checkForDuplicateLoanCheques($file_id) && $cheques_received<=$required_cheques && (($used_cheques+$unused_cheques)<=$cheques_received) )
	{
		 $admin_id=$_SESSION['adminSession']['admin_id'];
		 if(checkForNumeric($admin_id))
		 {
				$sql="INSERT INTO `fin_customer_cheque_details`( `bank_id`, `branch_id`, `required_cheques`, `cheques_received`, `used_cheques`, `unused_cheques`, `file_id`, `customer_id`, remarks,ac_no) VALUES ($bank_id,'$branch_id', '$required_cheques', $cheques_received, $used_cheques, '$unused_cheques', $file_id, $customer_id, '$remarks','$ac_no')";
				$result = dbQuery($sql);
				$cheque_return_id = dbInsertId();
				insertChequeNumbers($cheque_return_id,$cheque_no_array,0);
				
		 }
	}
	return $cheque_return_id;
}


function updateLoanCheques($file_id,$bank_name,$branch,$required_cheques,$cheques_received,$used_cheques,$unused_cheques,$customer_id,$remarks,$cheque_no_array,$ac_no="")
{
						$bank_array=insertIfNotDuplicateBank($bank_name,$branch);
						$bank_id=$bank_array[0];
						$branch_id=$bank_array[1];
						if(!checkForNumeric($ac_no))
						$ac_no="";
			
	if(checkForNumeric($file_id,$required_cheques,$bank_id,$branch_id,$cheques_received,$used_cheques,$unused_cheques,$customer_id) && $cheques_received<=$required_cheques && (($used_cheques+$unused_cheques)<=$cheques_received)  )
	{
		 $admin_id=$_SESSION['adminSession']['admin_id'];
		 if(checkForNumeric($admin_id))
		 {
				$sql="UPDATE `fin_customer_cheque_details` SET `bank_id` = $bank_id, `branch_id` = $branch_id, `required_cheques` = $required_cheques, `cheques_received` = $cheques_received, `used_cheques` = $used_cheques, `unused_cheques` = $unused_cheques, ac_no = '$ac_no' WHERE file_id = $file_id";
				
				$result = dbQuery($sql);
				
				$loan_cheques=ListChequesForFileId($file_id);
				$cheque_details_id = $loan_cheques['cheque_details_id'];
				
				deletedChequeNumbersForChequeDetailsId($cheque_details_id);
				insertChequeNumbers($cheque_details_id,$cheque_no_array,0);
				return "success";
		 }
	}
	return $file_id;
}



function deleteLoanCheques($loan_cheque_return_id)
{
	
	if(checkForNumeric($loan_cheque_return_id))
	{
		$sql="DELETE FROM fin_customer_cheque_details WHERE file_id = $loan_cheque_return_id";
		dbQuery($sql);
		return "success";
	}
	return "error";
}

function checkForDuplicateLoanCheques($file_id)
{
	if(checkForNumeric($file_id))
	{
		$sql="SELECT cheque_details_id FROM fin_customer_cheque_details WHERE file_id = $file_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	return false;
}

function insertChequeNumbers($cheque_details_id,$cheque_numbers_array,$used=0)
{
	if(checkForNumeric($cheque_details_id))
	{
		foreach($cheque_numbers_array as $cheque_number)
		insertChequeNumber($cheque_details_id,$cheque_number,$used);
		}
}

function insertChequeNumber($cheque_details_id,$cheque_number,$used=0)
{
	if(checkForNumeric($cheque_details_id,$cheque_number) && strlen($cheque_number)==6)
	{
		$sql="INSERT INTO fin_customer_cheques (cheque_no,cheque_details_id,used) VALUES ('$cheque_number',$cheque_details_id,$used)";
		dbQuery($sql);
		return true;
	}
	return false;
}

function deletedChequeNumbersForChequeDetailsId($cheque_details_id)
{
	if(checkForNumeric($cheque_details_id) )
	{
		
		$sql="DELETE FROM fin_customer_cheques WHERE cheque_details_id = $cheque_details_id";
		dbQuery($sql);
		return true;
	}
	return false;
}

function getChequeNumbersForChequeDetailsId($cheque_details_id)
{
	
	if(checkForNumeric($cheque_details_id))
	{
		$sql="SELECT * FROM fin_customer_cheques WHERE cheque_details_id = $cheque_details_id";
		$result=dbQuery($sql);
		$resultArray = dbResultToArray($result);
		
		return $resultArray;
	}
	
}

function getChequeNumbersForFileIdForEmiNo($file_id,$emi_id)
{
	
	
	if(checkForNumeric($file_id,$emi_id))
	{
		$emi_no = getEmiNoFromEmiId($emi_id);
		$sql="SELECT fin_customer_cheque_details.cheque_details_id,fin_customer_cheque_details.bank_id,fin_customer_cheque_details.branch_id,required_cheques,cheques_received,bank_name,used_cheques,unused_cheques,file_id,customer_id,branch_name,remarks,cheque_no,ac_no FROM fin_customer_cheque_details
,fin_bank,fin_bank_branch,fin_customer_cheques WHERE file_id = $file_id AND fin_customer_cheques.cheque_details_id =  fin_customer_cheque_details.cheque_details_id AND fin_bank.bank_id = fin_customer_cheque_details.bank_id AND fin_bank_branch.branch_id = fin_customer_cheque_details.branch_id AND cheque_no NOT IN (SELECT cheque_no FROM fin_loan_emi_payment_cheque,fin_loan_emi_payment, fin_loan_emi, fin_loan 
 WHERE fin_loan_emi_payment_cheque.emi_payment_id = fin_loan_emi_payment.emi_payment_id AND fin_loan_emi_payment.loan_emi_id = fin_loan_emi.loan_emi_id AND fin_loan.file_id = fin_customer_cheque_details.file_id AND fin_loan_emi.loan_id = fin_loan.loan_id) AND cheque_no NOT IN (SELECT cheque_no FROM fin_loan_emi_payment_cheque_return, fin_loan_emi, fin_loan 
 WHERE fin_loan_emi_payment_cheque_return.loan_emi_id = fin_loan_emi.loan_emi_id  AND fin_loan.file_id = fin_customer_cheque_details.file_id AND fin_loan_emi.loan_id = fin_loan.loan_id)";
		$result=dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false;
	}
	
}

function getEmiNoFromEmiId($emi_id)
{
	if(checkForNumeric($emi_id))
	{
		$loan_id=getLoanIdFromEmiId($emi_id);
		
		$sql="SELECT loan_emi_id FROM fin_loan_emi WHERE loan_id = $loan_id ORDER BY loan_emi_id";
		
		$result=dbQuery($sql);
		$resultArray = dbResultToArray($result);
		
		for($i=0;$i<count($resultArray);$i++)
		{
			if($emi_id==$resultArray[$i][0])
			return $i+1;
		}
		
	}
	
}

?>