<?php
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('file-functions.php');
require_once('bank-functions.php');
require_once('customer-functions.php');
require_once('agency-functions.php');
require_once('our-company-function.php');
require_once('guarantor-functions.php');
require_once('loan-functions.php');
require_once('EMI-functions.php');
require_once('vehicle-functions.php');
require_once('city-functions.php');
require_once('area-functions.php');
require_once('account-functions.php');
require_once('account-ledger-functions.php');
require_once('account-receipt-functions.php');
require_once('account-payment-functions.php');
require_once('account-jv-functions.php');
require_once('account-head-functions.php');

// $oc_id and file_id 

function addNewCustomer($agency_id, $agreement_no, $file_number, $broker_id, $customer_name, $customer_address, $customer_city_id, $customer_area_id, $customer_pincode,$customer_contact_no,$customer_human_proof_type_id,$customer_proofno,$customer_proofImg,$customer_proofImgScan,$guarantor_name,$guarantor_address,$guarantor_city_id,$guarantor_area_id,$guarantor_pincode,$guarantor_contact_no,$guarantor_human_proof_type_id,$guarantor_proofno,$guarantor_proofImg,$guarantor_proofImgScan,$amount,$loan_amount_type,$duration,$loan_type,$loan_scheme,$roi,$emi,$loan_approval_date,$loan_starting_date,$bank_name_array,$branch_name_array,$cheque_amount_array,$cheque_date_array,$cheque_no_array,$axin_no_array,$ledger_id_array=0,$cash_amount=0,$agency_loan_amount=0,$agency_emi=0,$agency_duration=0,$duration_unit=1,$secondary_customer_name="",$secondary_guarantor_name="",$secondary_customer_address="",$secondary_guarantor_address="")
{

	
	if(!isset($customer_pincode) || !checkForNumeric($customer_pincode))
	{
		$customer_pincode=0;
		}
	if(!isset($guarantor_pincode) || !checkForNumeric($guarantor_pincode))
	{
		$guarantor_pincode=0;
		}	
		
	if($loan_amount_type==2)
	{
	
	if($loan_amount_type==2 && !checkLoanDetailsVsLoanAmount($amount,$bank_name_array,$branch_name_array,$cheque_amount_array,$cheque_date_array,$cheque_no_array,$axin_no_array,$ledger_id_array,$cash_amount))
	return "error";	
	}
	if(validateForNull($agreement_no,$file_number) && $agency_id!=-1 && checkForNumeric($customer_city_id,$amount,$roi,$loan_amount_type,$broker_id) && $customer_city_id>0   && validateForNull($customer_name,$customer_address,$loan_approval_date,$loan_starting_date,$customer_area_id))
	{
	
		$file_id=insertFile($agency_id,$agreement_no,$file_number,$_SESSION['adminSession']['oc_id'],$broker_id);
		
		if(checkForNumeric($file_id,$customer_city_id) && $file_id>0 && $customer_city_id>0  && validateForNull($customer_name,$customer_address))
		{
				
			
		
			$customer_id=insertCustomer($customer_name,$customer_address,$customer_city_id,$customer_area_id,$customer_pincode,$file_id,$customer_contact_no,$customer_human_proof_type_id,$customer_proofno,$customer_proofImg,$customer_proofImgScan,$secondary_customer_name,$secondary_customer_address);
			
				
			
			if(checkForNumeric($customer_id) && validateForNull($guarantor_name,$guarantor_address) && checkForNumeric($guarantor_city_id))
			{
				$guarantor_id=insertGuarantor($guarantor_name,$guarantor_address,$guarantor_city_id,$guarantor_area_id,$guarantor_pincode,$file_id,$customer_id,$guarantor_contact_no,$guarantor_human_proof_type_id,$guarantor_proofno,$guarantor_proofImg,$guarantor_proofImgScan,$secondary_guarantor_name,$secondary_guarantor_address);
			}
			
			
			
			$loan_id=insertLoan($amount,$loan_amount_type,$duration,$loan_type,$loan_scheme,$roi,$emi,$loan_approval_date,$loan_starting_date,$file_id,$customer_id,$agency_loan_amount,$agency_emi,$agency_duration,$duration_unit);
		
		
			if(checkForNumeric($loan_id) && $loan_amount_type==2)
			{
				
				insertLoanChequeArray($loan_id,$bank_name_array,$branch_name_array,$cheque_amount_array,$cheque_date_array,$cheque_no_array,$axin_no_array,$ledger_id_array,$cash_amount);
				return $file_id;
				}
			else if($loan_amount_type==1 && checkForNumeric($loan_id))
			{
				return $file_id;	
				}	
			$sql="DELETE FROM fin_file WHERE file_id=$file_id";
			dbQuery($sql);	
			return "error";	
			
		}
	}
	else
	{
		return "error";
	}	
	
	
}

function updateRasidNo()
{
	$sql="SELECT emi_payment_id,rasid_no,fin_loan_emi.loan_emi_id FROM fin_loan_emi_payment,fin_file,fin_loan_emi,fin_loan WHERE fin_loan_emi_payment.loan_emi_id = fin_loan_emi.loan_emi_id AND fin_loan_emi.loan_id = fin_loan.loan_id AND fin_loan.file_id = fin_file.file_id AND fin_file.agency_id=4";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	foreach($resultArray as $re)
	{
		$payment_id=$re[0];
		$rasid_no=$re[1];
		$rasid_no=str_replace("SCC","",$rasid_no);
		$rasid_no=intval($rasid_no);
		if(is_numeric($rasid_no))
		{
		$emi_id=$re[2];
		$ag_id_array=getAgnecyIdFromEmiId($emi_id);
		if(is_numeric($ag_id_array[0]))
		{
			$agency_id=$ag_id_array[0];
			$oc_id=null;
			$rasid_prefix=getAgencyPrefixFromAgencyId($agency_id);
			}
		else if(is_numeric($ag_id_array[1]))
		{
			$oc_id=$ag_id_array[1];
			$agency_id=null;
			$rasid_prefix=getPrefixFromOCId($oc_id);
			}
		$or_rasid_no=$rasid_no;	
		$rasid_no=$rasid_prefix.$rasid_no;
		$sql="UPDATE fin_loan_emi_payment SET rasid_no='$rasid_no' WHERE emi_payment_id=$payment_id";
		dbQuery($sql);
		}
	}
	}
function updateRasidIdentifier()
{
	$sql="SELECT GROUP_CONCAT(emi_payment_id) FROM fin_loan_emi_payment GROUP BY rasid_no,date_added ORDER BY emi_payment_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	foreach($resultArray as $re)
	{
		$payment_id_array=explode(",",$re[0]);
		
		if(count($payment_id_array)>1)
		{
			sort($payment_id_array);
			$parent_payment_id=$payment_id_array[0];
			
				$sql="UPDATE fin_loan_emi_payment SET rasid_identifier=0 WHERE emi_payment_id=$parent_payment_id";
				dbQuery($sql);
			for($i=1;$i<count($payment_id_array);$i++)
			{
				$payment_id=$payment_id_array[$i];
				$sql="UPDATE fin_loan_emi_payment SET rasid_identifier=$parent_payment_id WHERE emi_payment_id=$payment_id";
				dbQuery($sql);
				
				}
			
			}
		}
	}	

function updateLoanEndingDateForAllLoans()
{
	$sql="SELECT loan_id,loan_starting_date,loan_duration From fin_loan";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	foreach($resultArray as $loan)
	{
		$loan_id=$loan['loan_id'];
		$loan_starting_date=$loan['loan_starting_date'];
		$duration=$loan['loan_duration'];
		
		$actual_ending_date=getEndingDateForLoan($loan_starting_date,$duration);
		$sql="UPDATE fin_loan SET loan_ending_date='$actual_ending_date' WHERE loan_id=$loan_id";
		dbQuery($sql);
		}
}

function updateVehicleNo()
{
	$sql="SELECT vehicle_id,vehicle_reg_no From fin_vehicle";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	foreach($resultArray as $loan)
	{
		$vehicle_id=$loan['vehicle_id'];
		$reg_no=$loan['vehicle_reg_no'];
		$new_reg_no=stripVehicleno($reg_no);
		
		$sql="UPDATE fin_vehicle SET vehicle_reg_no='$new_reg_no' WHERE vehicle_id=$vehicle_id";
		dbQuery($sql);
		}
	}	

function updatefinLoanEMIAmount()
{
	$sql="SELECT loan_id,emi from fin_loan";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		foreach($resultArray as $re)
		{
			$emi=$re[1];
			$loan_id=$re[0];
			$sql="UPDATE fin_loan_emi SET emi_amount=$emi WHERE loan_id=$loan_id";
			dbQuery($sql);
			}
		
		}
	}
	
function updateCityAreaCustomer($admin_id,$id)
{
	$sql="SELECT customer_id,area_id,city_id FROM fin_customer WHERE created_by=$admin_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	foreach($resultArray as $re)
	{
		$customer_id=0;
		$area_id=0;
		$city_id=0;
		$customer_id=$re['customer_id'];
		$area_id=$re['area_id'];
		$city_id=$re['city_id'];
		
		if($city_id>0 && $customer_id>0 && $area_id>0)
		{
		$city=0;	
		$city=getCityByID($city_id);
			if($city!=0)
			{
				$city_name=$city['city_name'];
				$new_area_id=insertArea($city_name,$id);
				$sql="UPDATE  fin_customer SET city_id=$id, area_id=$new_area_id WHERE customer_id=$customer_id";
				$result=dbQuery($sql);
				}
		}
		}
	
}	

function deleteUnwantedBrokers()
{
	$sql="DELETE FROM fin_broker WHERE broker_id NOT IN (SELECT broker_id FROM fin_file)";
	dbQuery($sql);
	}	

function deleteUnWantedAreas()
{
	
$sql="DELETE FROM fin_city_area WHERE area_id NOT IN (SELECT area_id FROM fin_customer UNION SELECT area_id FROM fin_guarantor)";	
	dbQuery($sql);
}	

function deleteUnWantedCities()
{
	
$sql="DELETE FROM fin_city WHERE city_id NOT IN (SELECT city_id FROM fin_customer UNION SELECT city_id FROM fin_guarantor UNION SELECT city_id FROM fin_our_company UNION SELECT city_id FROM fin_vehicle_dealer)";	
	dbQuery($sql);
}	

function transferFilesFromOneAgencyToOther($from_agency_id,$to_agency_id)
{
	$from_prefix = getAgencyPrefixFromAgencyId($from_agency_id);
	$to_prefix = getAgencyPrefixFromAgencyId($to_agency_id);
	
	$sql="SELECT file_id ,file_number from fin_file Where agency_id = $from_agency_id";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $file)
	{
		$file_id = $file['file_id'];
		$file_number = $file['file_number'];
		$file_number = str_replace($from_prefix,$to_prefix,$file_number);
		
		$sql="UPDATE fin_file SET file_number = $file_number , agency_id = $to_agency_id WHERE file_id = $file_id";
		dbQuery($sql);
	}
}

function transferFilesFromOneOCToOther($from_agency_id,$to_agency_id)
{
	$from_prefix = getPrefixFromOCId($from_agency_id);
	$to_prefix = getPrefixFromOCId($to_agency_id);
	
	$sql="SELECT file_id ,file_number from fin_file Where oc_id = $from_agency_id";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $file)
	{
		$file_id = $file['file_id'];
		$file_number = $file['file_number'];
		$file_number = str_replace($from_prefix,$to_prefix,$file_number);
		$file_number=$file_number."/2";
		$sql="UPDATE fin_file SET file_number = $file_number , oc_id = $to_agency_id WHERE file_id = $file_id";
		dbQuery($sql);
	}
}


function checkLoanStartingDate()
{
	
	$sql="SELECT loan_id, loan_starting_date FROM fin_loan";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	foreach($resultArray as $loan)
	{
		
		$loan_id = $loan['loan_id'];
		$loan_starting_date = $loan['loan_starting_date'];
		
		$sql1="SELECT MIN(actual_emi_date) as actual_emi_date FROM fin_loan_emi WHERE loan_id = $loan_id GROUP BY loan_id";
		$result1 = dbQuery($sql1);
		$resultArray1 = dbResultToArray($result1);
		
		$actual_emi_date = $loan['actual_emi_date'];
		
		if(strtotime($loan_starting_date)!=strtotime($actual_emi_date))
		{
			
			$file_id=getFileIdFromLoanId($loan_id);
			$loan = getLoanDetailsByFileId($file_id);
			$duration  = $loan['loan_duration'];
			$duration_unit = $loan['duration_unit'];
			
			echo getFileNumberByFileId($file_id)."\n";
			
			// updateEMIsForLoan($loan_id,$duration,$loan_starting_date,false,$duration_unit);
		}
		
		}
}

function tranferAllRasidsToAccounts($emi_payment_id,$to_payment_id,$upto_date=false)
{
	if(checkForNumeric($emi_payment_id,$to_payment_id))
	{
		$sql="SELECT emi_payment_id FROM fin_loan_emi_payment WHERE emi_payment_id>=$emi_payment_id AND emi_payment_id<=$to_payment_id AND rasid_identifier=0";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		foreach($resultArray as $re)
		{
			$i = $re[0];
			$payment=getPaymentDetailsForEmiPaymentId($i);	
			if($payment && is_array($payment))
			{
			$loan_emi_id = $payment['loan_emi_id'];	
			$loan_id = getLoanIdFromEmiId($loan_emi_id);
			$file_id=getFileIdFromLoanId($loan_id);
			$oc_agency_array=getAgencyOrCompanyIdFromFileId($file_id);	
				if($oc_agency_array[0]=="agency")
				{
				$oc_id =NULL;
				$agency_id = $oc_agency_array[1];
				$account_aettings = getAccountSettingsForAgency($agency_id);
				$cash_ledger = getCashLedgerIdForAgency($agency_id);
				$bank_ledger = $account_aettings['default_bank'];
				$books_starting_date=getBooksStartingDateForAgency($agency_id);
				$auto_interest_ledger=getAdvanceInterestLedgerIdForAgency($agency_id);
				$income_ledger=getIncomeLedgerIdForAgency($agency_id);	
				$prefix = getAgencyPrefixFromAgencyId($agency_id);
				$rasid_no = str_replace($prefix,'',$payment['rasid_no']);
				$rasid_no = intval($rasid_no);
				}
				else if($oc_agency_array[0]=="oc")
				{
				$agency_id =NULL;
				$oc_id = $oc_agency_array[1];	
				$account_aettings = getAccountSettingsForOC($oc_id);
				$cash_ledger = getCashLedgerIdForOC($oc_id);
				$bank_ledger = $account_aettings['default_bank'];
				$books_starting_date=getBooksStartingDateForOC($oc_id);
				$auto_interest_ledger=getAdvanceInterestLedgerIdForOC($oc_id);
				$income_ledger=getIncomeLedgerIdForOC($oc_id);	
				$prefix = getPrefixFromOCId($oc_id);
				$rasid_no = str_replace($prefix,'',$payment['rasid_no']);
				$rasid_no = intval($rasid_no);
				}
				$payment_amount = getTotalAmountForRasidNo($payment['rasid_no'],$loan_id,$payment['emi_payment_id']);
				if($payment['payment_mode']==1)
				{	
				editPayment($payment['emi_payment_id'],$payment['loan_emi_id'],$payment_amount,$payment['payment_mode'],$payment['payment_date'],$rasid_no,$payment['remarks'],$payment['remainder_date'],false,false,false,false,0,0,$payment['paid_by']);
				}
				else if($payment['payment_mode']==2)
				{
					
				$cheque_details = getChequeDetailsFromEmiPaymentId($payment['emi_payment_id']);	
				$bank_name=getBankNameByID($cheque_details['bank_id']);
				$branch_name = getBranchhById($cheque_details['branch_id']);
				
				editPayment($payment['emi_payment_id'],$payment['loan_emi_id'],$payment_amount,$payment['payment_mode'],$payment['payment_date'],$rasid_no,$payment['remarks'],$payment['remainder_date'],$bank_name,$branch_name,$cheque_details['cheque_no'],$cheque_details['cheque_date'],$cheque_details['cheque_return'],$bank_ledger,$payment['paid_by']);
				
				}
			}
		}	
		
	}
	
}

function tranferAllRasidsToAccountsFromPaymentDate($payment_date)
{
	if(validateForNull($payment_date))
	{
		$sql="SELECT emi_payment_id FROM fin_loan_emi_payment WHERE payment_date >= '$payment_date'  AND rasid_identifier=0";
		if($upto_date)
		$sql=$sql." AND payment_date<'$upto_date'";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		foreach($resultArray as $re)
		{
			$i = $re[0];
			$payment=getPaymentDetailsForEmiPaymentId($i);	
			if($payment && is_array($payment))
			{
			$loan_emi_id = $payment['loan_emi_id'];	
			$loan_id = getLoanIdFromEmiId($loan_emi_id);
			$file_id=getFileIdFromLoanId($loan_id);
			$oc_agency_array=getAgencyOrCompanyIdFromFileId($file_id);	
				if($oc_agency_array[0]=="agency")
				{
				$oc_id =NULL;
				$agency_id = $oc_agency_array[1];
				$account_aettings = getAccountSettingsForAgency($agency_id);
				$cash_ledger = getCashLedgerIdForAgency($agency_id);
				$bank_ledger = $account_aettings['default_bank'];
				$books_starting_date=getBooksStartingDateForAgency($agency_id);
				$auto_interest_ledger=getAdvanceInterestLedgerIdForAgency($agency_id);
				$income_ledger=getIncomeLedgerIdForAgency($agency_id);	
				$prefix = getAgencyPrefixFromAgencyId($agency_id);
				$rasid_no = str_replace($prefix,'',$payment['rasid_no']);
				$rasid_no = intval($rasid_no);
				}
				else if($oc_agency_array[0]=="oc")
				{
				$agency_id =NULL;
				$oc_id = $oc_agency_array[1];	
				$account_aettings = getAccountSettingsForOC($oc_id);
				$cash_ledger = getCashLedgerIdForOC($oc_id);
				$bank_ledger = $account_aettings['default_bank'];
				$books_starting_date=getBooksStartingDateForOC($oc_id);
				$auto_interest_ledger=getAdvanceInterestLedgerIdForOC($oc_id);
				$income_ledger=getIncomeLedgerIdForOC($oc_id);	
				$prefix = getPrefixFromOCId($oc_id);
				$rasid_no = str_replace($prefix,'',$payment['rasid_no']);
				$rasid_no = intval($rasid_no);
				}
				$payment_amount = getTotalAmountForRasidNo($payment['rasid_no'],$loan_id,$payment['emi_payment_id']);
				if($payment['payment_mode']==1)
				{	
				editPayment($payment['emi_payment_id'],$payment['loan_emi_id'],$payment_amount,$payment['payment_mode'],$payment['payment_date'],$rasid_no,$payment['remarks'],$payment['remainder_date'],false,false,false,false,0,0,$payment['paid_by']);
				}
				else if($payment['payment_mode']==2)
				{
					
				$cheque_details = getChequeDetailsFromEmiPaymentId($payment['emi_payment_id']);	
				$bank_name=getBankNameByID($cheque_details['bank_id']);
				$branch_name = getBranchhById($cheque_details['branch_id']);
				
				editPayment($payment['emi_payment_id'],$payment['loan_emi_id'],$payment_amount,$payment['payment_mode'],$payment['payment_date'],$rasid_no,$payment['remarks'],$payment['remainder_date'],$bank_name,$branch_name,$cheque_details['cheque_no'],$cheque_details['cheque_date'],$cheque_details['cheque_return'],$bank_ledger,$payment['paid_by']);
				
				}
			}
		}	
		
	}
	
}

function tranferAllLoansToAccountsFromPaymentDate($payment_date,$upto_date=false)
{
	if(validateForNull($payment_date))
	{
		$sql="SELECT loan_id FROM fin_loan WHERE loan_approval_date >= '$payment_date'";
		if($upto_date)
		$sql=$sql." AND loan_approval_date<'$upto_date'";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		
		foreach($resultArray as $re)
		{
			$loan_id = $re[0];
			$loan=getLoanById($loan_id);
			
			$loan_cheque = getLoanChequeByLoanId($loan_id);
			$total_collection=getTotalCollectionForLoan($loan_id);
			if($loan && is_array($loan))
			{
			
			$file_id=getFileIdFromLoanId($loan_id);
		
			$oc_agency_array=getAgencyOrCompanyIdFromFileId($file_id);	
				if($oc_agency_array[0]=="agency")
				{
				$oc_id =NULL;
				$agency_id = $oc_agency_array[1];
				$account_aettings = getAccountSettingsForAgency($agency_id);
				$cash_ledger = getCashLedgerIdForAgency($agency_id);
				$bank_ledger = $account_aettings['default_bank'];
				$books_starting_date=getBooksStartingDateForAgency($agency_id);
				$auto_interest_ledger=getAdvanceInterestLedgerIdForAgency($agency_id);
				$income_ledger=getIncomeLedgerIdForAgency($agency_id);	
				
				}
				else if($oc_agency_array[0]=="oc")
				{
				$agency_id =NULL;
				$oc_id = $oc_agency_array[1];	
				$account_aettings = getAccountSettingsForOC($oc_id);
				$cash_ledger = getCashLedgerIdForOC($oc_id);
				$bank_ledger = $account_aettings['default_bank'];
				$books_starting_date=getBooksStartingDateForOC($oc_id);
				$auto_interest_ledger=getAdvanceInterestLedgerIdForOC($oc_id);
				$income_ledger=getIncomeLedgerIdForOC($oc_id);	
				
				}
				
				if($loan['loan_amount_type']==1)
				{
						
				addPayment($loan['loan_amount'],$loan['loan_approval_date'],$cash_ledger,'C'.$loan['customer_id'],'Loan Amount Given by cash',1,$loan_id);
				
				
				$total_interest=$total_collection-$loan['loan_amount'];	
					
				addJV($total_interest,date('d/m/Y',strtotime($loan['loan_approval_date'])),'C'.$loan['customer_id'],'L'.$auto_interest_ledger,'Interest JV',1,$loan_id); // interestJv
				}
				else if($loan['loan_amount_type']==2)
				{
					
			    addPayment($loan_cheque['loan_cheque_amount'],$loan['loan_approval_date'],$bank_ledger,'C'.$loan['customer_id'],'Loan Amount Given by cheque',1,$loan_id);
				
				if($loan_cheque['cash_amomunt']>0)
				addPayment($loan_cheque['cash_amount'],$loan['loan_approval_date'],$cash_ledger,'C'.$loan['customer_id'],'Loan Amount Given by cash',1,$loan_id);
				
				
				$total_interest=$total_collection-$loan['loan_amount'];	
					
				addJV($total_interest,date('d/m/Y',strtotime($loan['loan_approval_date'])),'C'.$loan['customer_id'],'L'.$auto_interest_ledger,'Interest JV',1,$loan_id); // interestJv
				
				}
			}
		}	
		
	}
	
}

function updateFileNumbersForMangalDeep()
{
$sql = "SELECT file_id,file_number FROM fin_file";
$result = dbQuery($sql);
$resultArray = dbResultToArray($result);
foreach($resultArray as $re)
{	
$file_id = $re[0];
$file_number = $re[1];
$slash_position=strripos($file_number,'/');
$file_no = substr($file_number,$slash_position);
$file_no_prefix = substr($file_number,0,2);
$new_file_number = $file_no_prefix.$file_no;

//$sql="UPDATE fin_file SET file_number = '$new_file_number' WHERE file_id = $file_id";
//dbQuery($sql);
}
}

function updateFileNumberShivaniAuto()
{
$sql="SELECT fin_file.file_id FROM fin_file,fin_loan WHERE fin_file.file_id = fin_loan.file_id ORDER BY loan_approval_date";	
$result = dbQuery($sql);
$resultArray = dbResultToArray($result);
$i=1;
foreach($resultArray as $re)
{	
$file_id = $re[0];
$file_number = "SA".$i;
$file_agreement_no = $i;
$sql="UPDATE fin_file SET file_number = '$file_number' , file_agreement_no = '$file_agreement_no' WHERE file_id = $file_id";
dbQuery($sql);
$i++;
}
}

function updateAreaAndCity($area_id,$new_city_id)
{
	$sql="UPDATE fin_city_area SET city_id = $new_city_id WHERE area_id = $area_id";
	dbQuery($sql);
	
	$sql="UPDATE fin_customer SET city_id = $new_city_id WHERE area_id = $area_id";
	dbQuery($sql);
	
	$sql="UPDATE fin_guarantor SET city_id = $new_city_id WHERE area_id = $area_id";
	dbQuery($sql);
}

?>