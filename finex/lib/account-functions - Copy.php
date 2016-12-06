<?php
require_once("cg.php");
require_once("city-functions.php");
require_once("loan-functions.php");
require_once("file-functions.php");
require_once("customer-functions.php");
require_once("account-ledger-functions.php");
require_once("account-head-functions.php");
require_once("account-payment-functions.php");
require_once("ledgers-group-functions.php");
require_once("account-receipt-functions.php");
require_once("account-jv-functions.php");
require_once("account-contra-functions.php");
require_once("account-combined-agency-functions.php");
require_once("common.php");
require_once("bd.php");

function getBooksStartingDateForAgency($agency_id)
{
	if(checkForNumeric($agency_id))
	{
		$sql="SELECT ac_starting_date FROM fin_ac_settings WHERE agency_id=$agency_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;	
	}
	return false;
	
}

function getBooksStartingDateForOC($oc_id) // our_company
{
	if(checkForNumeric($oc_id))
	{
		$sql="SELECT ac_starting_date FROM fin_ac_settings WHERE our_company_id=$oc_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;	
	}
	return false;
	
}

function getBooksStartingDateForCA($ca_id) // combined agency
{
	if(checkForNumeric($ca_id))
	{
		$sql="SELECT ac_starting_date FROM fin_ac_combined_agency WHERE combined_agency_id=$ca_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;	
	}
	return false;
	
}

function getAccountSettingsForAgency($agency_id)
{
	if(checkForNumeric($agency_id))
	{
		$sql="SELECT * FROM fin_ac_settings WHERE agency_id=$agency_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false;	
	}
	return false;
	
	}
	
function getAccountSettingsForOC($oc_id)
{
	if(checkForNumeric($oc_id))
	{
		$sql="SELECT * FROM fin_ac_settings WHERE our_company_id=$oc_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false;	
	}
	return false;
	
}

function getCurrentBalanceForLedger($ledger_id)
{
	if(checkForNumeric($ledger_id))
	{
		$sql="SELECT current_balance, current_balance_cd FROM fin_ac_ledgers WHERE ledger_id=$ledger_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false;
		}
}

function getCurrentBalanceForCustomer($ledger_id)
{
	if(checkForNumeric($ledger_id))
	{
		$sql="SELECT current_balance, current_balance_cd FROM fin_customer WHERE customer_id=$ledger_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false;
		}
}

function replaceCustomerWithLedger($customer_id,$ledger_id)
{
	if(checkForNumeric($customer_id,$ledger_id))
	{
		
		$sql="UPDATE fin_ac_payment SET from_ledger_id=$ledger_id, from_customer_id=NULL, auto_rasid_type=0, auto_id=0 WHERE from_customer_id=$customer_id";
			dbQuery($sql);
			
		$sql="UPDATE fin_ac_receipt SET to_ledger_id=$ledger_id, to_customer_id=NULL,  auto_rasid_type=0, auto_id=0  WHERE to_customer_id=$customer_id";
			dbQuery($sql);	
		
		$sql="UPDATE fin_ac_jv INNER JOIN fin_ac_jv_cd ON fin_ac_jv.jv_id = fin_ac_jv_cd.jv_id SET to_ledger_id=$ledger_id, to_customer_id=NULL, auto_rasid_type=0, auto_id=0  WHERE to_customer_id=$customer_id";
			dbQuery($sql);	
		
		$sql="UPDATE fin_ac_jv INNER JOIN fin_ac_jv_cd ON fin_ac_jv.jv_id = fin_ac_jv_cd.jv_id SET from_ledger_id=$ledger_id, from_customer_id=NULL, auto_rasid_type=0, auto_id=0  WHERE from_customer_id=$customer_id";
			dbQuery($sql);		
		
			
		
		}
	
	} 

function debitAccountingLedger($ledger_id,$amount) // cash or bank
{
	if(checkForNumeric($ledger_id,$amount))
	{
		$current_balance_array=getCurrentBalanceForLedger($ledger_id);
		$current_balance=$current_balance_array[0];
		$current_balance_cd=$current_balance_array[1];
		
		if($current_balance_cd==0) // if current balance is debit
		{
			$current_balance=$current_balance+$amount;
		}
		else if($current_balance_cd==1)
		{
			if($current_balance>$amount)
			{
				$current_balance=$current_balance-$amount;
				}
			else if($amount>$current_balance)	
			{
				$current_balance=$amount-$current_balance;
				$current_balance_cd=0; // debit
				}
			else if($current_balance==$amount)
			{
				$current_balance_cd=0;
				$current_balance=0;
				}	
		}	
		$sql="UPDATE fin_ac_ledgers SET current_balance = $current_balance , current_balance_cd = $current_balance_cd
		      WHERE ledger_id=$ledger_id";
		dbQuery($sql);
		return true;
		}
		else return false;
}	
function creditAccountingLedger($ledger_id,$amount) // cash or bank
{
	if(checkForNumeric($ledger_id,$amount))
	{
		$current_balance_array=getCurrentBalanceForLedger($ledger_id);
		$current_balance=$current_balance_array[0];
		$current_balance_cd=$current_balance_array[1];
		if($current_balance_cd==1)
		{
			$current_balance=$current_balance+$amount;
		}
		else if($current_balance_cd==0)
		{
			if($current_balance>$amount)
			{
				$current_balance=$current_balance-$amount;
				}
			else if($amount>$current_balance)	
			{
				$current_balance=$amount-$current_balance;
				$current_balance_cd=1;
				}
			else if($current_balance==$amount)
			{
				$current_balance_cd=0;
				$current_balance=0;
				}	
		}	
		$sql="UPDATE fin_ac_ledgers SET current_balance=$current_balance , current_balance_cd= $current_balance_cd
		      WHERE ledger_id=$ledger_id";
		dbQuery($sql);
		return true;
		}
		else return false;
}	

function debitAccountingCustomer($customer_id,$amount) // cash or bank
{
	
	if(checkForNumeric($customer_id,$amount))
	{
		$current_balance_array=getCurrentBalanceForCustomer($customer_id);
		$current_balance=$current_balance_array[0];
		$current_balance_cd=$current_balance_array[1];
		if($current_balance_cd==0) // if current balance is debit
		{
			$current_balance=$current_balance+$amount;
		}
		else if($current_balance_cd==1)
		{
			if($current_balance>$amount)
			{
				$current_balance=$current_balance-$amount;
				}
			else if($amount>$current_balance)	
			{
				$current_balance=$amount-$current_balance;
				$current_balance_cd=0; // debit
				}
			else if($current_balance==$amount)
			{
				$current_balance_cd=0;
				$current_balance=0;
				}	
		}	
		$sql="UPDATE fin_customer SET current_balance=$current_balance , current_balance_cd= $current_balance_cd
		      WHERE customer_id=$customer_id";
		dbQuery($sql);
		return true;
		}
		else return false;
}	
function creditAccountingCustomer($customer_id,$amount) // cash or bank
{
	
	if(checkForNumeric($customer_id,$amount))
	{
		$current_balance_array=getCurrentBalanceForCustomer($customer_id);
		$current_balance=$current_balance_array[0];
		$current_balance_cd=$current_balance_array[1];
		
		if($current_balance_cd==1)
		{
			$current_balance=$current_balance+$amount;
		}
		else if($current_balance_cd==0)
		{
			if($current_balance>$amount)
			{
				$current_balance=$current_balance-$amount;
				}
			else if($amount>$current_balance)	
			{
				$current_balance=$amount-$current_balance;
				$current_balance_cd=1;
				}
			else if($current_balance==$amount)
			{
				$current_balance_cd=0;
				$current_balance=0;
				}	
		}	
		
		$sql="UPDATE fin_customer SET current_balance=$current_balance , current_balance_cd= $current_balance_cd
		      WHERE customer_id=$customer_id";
		dbQuery($sql);
		return true;
		}
		else return false;
}	

function getCashLedgerIdForAgency($agency_id)
{
	if(checkForNumeric($agency_id))
	{	
	$cash_head_id=getCashHeadId();
	$ca_id=getCombinedAgencyIdForAgencyId($agency_id); // returns id or false
	
		if(checkForNumeric($ca_id))
		{
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);	
		}
	$sql="SELECT ledger_id FROM fin_ac_ledgers WHERE head_id=$cash_head_id AND";
	if(!$ca_id)
	$sql=$sql." agency_id=$agency_id";
	else if(checkForNumeric($ca_id))
	{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
	} 
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	{
		createCashLedgerForAgnecy($agency_id);
		if($result)
		{
		return getCashLedgerIdForAgency($agency_id);
		}
		
		}
	}
	return false;
}

function getCashLedgerIdForOC($oc_id)
{
	if(checkForNumeric($oc_id))
	{
	$cash_head_id=getCashHeadId();
	$ca_id=getCombinedAgencyIdForOCId($oc_id); // returns id or false
		if(checkForNumeric($ca_id))
		{
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);	
		}
	$sql="SELECT ledger_id FROM fin_ac_ledgers WHERE head_id=$cash_head_id AND ";
	if(!$ca_id)
	$sql=$sql." oc_id=$oc_id";
	else if(checkForNumeric($ca_id))
	{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
	} 
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	{
		
		$result=createCashLedgerForOC($oc_id);
		if($result)
		{
		return getCashLedgerIdForOC($oc_id);
		}
		}
	}
	return false;	
}

function getAdvanceInterestLedgerIdForOC($oc_id)
{
	if(checkForNumeric($oc_id))
	{
	
	if(defined('AUTO_INTEREST_NAME'))
	{
	 $ledger_name = AUTO_INTEREST_NAME;	
	}
	else
	$ledger_name = 'Auto Interest';
			
	if(defined('AUTO_INTEREST_TYPE') && AUTO_INTEREST_TYPE==1)
	{
		$unsecured_loans_head_id = AUTO_INTEREST_HEAD;
	}
	else
	{
	$unsecured_loans_head_id=getUnsecuredLoansId();
	}
	$ca_id=getCombinedAgencyIdForOCId($oc_id); // returns id or false
		if(checkForNumeric($ca_id))
		{
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);	
		}
	$sql="SELECT ledger_id FROM fin_ac_ledgers WHERE head_id=$unsecured_loans_head_id  AND ledger_name='".$ledger_name."' AND ";
	if(!$ca_id)
	$sql=$sql." oc_id=$oc_id";
	else if(checkForNumeric($ca_id))
	{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
	} 
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	{
		
		$result=createAutoInterestLedgerForOC($oc_id);
		if($result)
		{
		return getAdvanceInterestLedgerIdForOC($oc_id);
		}
		}
	}
	return false;	
}

function getAdvanceInterestLedgerIdForAgency($agency_id)
{
	if(checkForNumeric($agency_id))
	{
		
	if(defined('AUTO_INTEREST_NAME'))
	{
	 $ledger_name = AUTO_INTEREST_NAME;	
	}
	else
	$ledger_name = 'Auto Interest';
			
	if(defined('AUTO_INTEREST_TYPE') && AUTO_INTEREST_TYPE==1)
	{
		$unsecured_loans_head_id = AUTO_INTEREST_HEAD;
	}
	else
	{
	$unsecured_loans_head_id=getUnsecuredLoansId();
	}
	$ca_id=getCombinedAgencyIdForAgencyId($agency_id); // returns id or false
		if(checkForNumeric($ca_id))
		{
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);	
		}
	$sql="SELECT ledger_id FROM fin_ac_ledgers WHERE head_id=$unsecured_loans_head_id  AND ledger_name='".$ledger_name."' AND ";
	if(!$ca_id)
	$sql=$sql." agency_id=$agency_id";
	else if(checkForNumeric($ca_id))
	{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
	} 
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	{
		$result=createAutoInterestLedgerForAgency($agency_id);
		if($result)
		{
		return getAdvanceInterestLedgerIdForAgency($agency_id);
		}
		}
	}
	return false;	
}

function getIncomeLedgerIdForOC($oc_id)
{
	if(checkForNumeric($oc_id))
	{
			
	$income_head_id=getIndirectIncomeId();
	$ca_id=getCombinedAgencyIdForOCId($oc_id); // returns id or false
		if(checkForNumeric($ca_id))
		{
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);	
		}
	$sql="SELECT ledger_id FROM fin_ac_ledgers WHERE head_id=$income_head_id  AND ledger_name='Finance Income' AND ";
		if(!$ca_id)
	$sql=$sql." oc_id=$oc_id";
	else if(checkForNumeric($ca_id))
	{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
	} 
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	{
		
		$result=createIncomeLedgerForOC($oc_id);
		if($result)
		{
		return getIncomeLedgerIdForOC($oc_id);
		}
		}
	}
	return false;	
}

function getIncomeLedgerIdForAgency($agency_id)
{
	if(checkForNumeric($agency_id))
	{
	$income_head_id=getIndirectIncomeId();
	$ca_id=getCombinedAgencyIdForAgencyId($agency_id); // returns id or false
		if(checkForNumeric($ca_id))
		{
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);	
		}
	$sql="SELECT ledger_id FROM fin_ac_ledgers WHERE head_id=$income_head_id AND  ledger_name='Finance Income' AND ";
	if(!$ca_id)
	$sql=$sql." agency_id=$agency_id";
	else if(checkForNumeric($ca_id))
	{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
	} 
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	{
		$result=createIncomeLedgerForAgency($agency_id);
		if($result)
		{
		return getIncomeLedgerIdForAgency($agency_id);
		}
		}
	}
	return false;	
}

function getAmountToBeCreditedForLoanId($loan_id) //FOR LOAN AMOUNT after the books starting date
{
	
	if(checkForNumeric($loan_id))
	{
		
		$file_id=getFileIdFromLoanId($loan_id);
		$agency_company_type_array=getAgencyOrCompanyIdFromFileId($file_id);
		$agency_company_type=$agency_company_type_array[0];
		$agency_company_type_id=$agency_company_type_array[1];
		if($agency_company_type=="agency")
		{
			$accounts_settings=getAccountSettingsForAgency($agency_company_type_id);
			$booksStartingDate=getBooksStartingDateForAgency($agency_company_type_id);
			$mercantile=$accounts_settings['mercantile'];
		}
		else
		{
			$accounts_settings=getAccountSettingsForOC($agency_company_type_id);
			$booksStartingDate=getBooksStartingDateForOC($agency_company_type_id);
			$mercantile=$accounts_settings['mercantile'];
		}
		
		$loan=getLoanById($loan_id);
		
		if(strtotime($loan['loan_approval_date'])>=strtotime($booksStartingDate))	
		{
			return $loan['loan_amount'];
		}	
		else return 0;
		
	}
}

function getInterestToBeCreditedForLoanId($loan_id) // after the books starting date
{
	
	if(checkForNumeric($loan_id))
	{
		
		$file_id=getFileIdFromLoanId($loan_id);
		$agency_company_type_array=getAgencyOrCompanyIdFromFileId($file_id);
		$agency_company_type=$agency_company_type_array[0];
		$agency_company_type_id=$agency_company_type_array[1];
		if($agency_company_type=="agency")
		{
			$accounts_settings=getAccountSettingsForAgency($agency_company_type_id);
			$booksStartingDate=getBooksStartingDateForAgency($agency_company_type_id);
			$mercantile=$accounts_settings['mercantile'];
		}
		else
		{
			$accounts_settings=getAccountSettingsForOC($agency_company_type_id);
			$booksStartingDate=getBooksStartingDateForOC($agency_company_type_id);
			$mercantile=$accounts_settings['mercantile'];
		}
		
		$loan=getLoanById($loan_id);
		
		if(strtotime($loan['loan_approval_date'])>=strtotime($booksStartingDate))	
		{
			return getTotalInterestForLoan($loan_id);
		}	
		else return 0;
		
	}
}

function getPrincipalAmountToBeCreditedForEMIPaymentId($payment_id) //FOR PAYMENT AMOUNT after the books starting date
{
	
	if(checkForNumeric($payment_id))
	{
		
		$amount=getTotalAmountForPaymentId($payment_id);
		$emis_paid=getTotalEmisPaidForEMIPaymentId($payment_id);
		
		$payment=getPaymentDetailsForEmiPaymentId($payment_id);
		$loan_id=getLoanIdFromEmiPaymentId($payment_id);
		$file_id=getFileIdFromLoanId($loan_id);
		
		$interestPerEMI=getInterestPerEMIForLoan($loan_id);
		$agency_company_type_array=getAgencyOrCompanyIdFromFileId($file_id);
		$agency_company_type=$agency_company_type_array[0];
		$agency_company_type_id=$agency_company_type_array[1];
		if($agency_company_type=="agency")
		{
			$accounts_settings=getAccountSettingsForAgency($agency_company_type_id);
			$booksStartingDate=getBooksStartingDateForAgency($agency_company_type_id);
			$mercantile=$accounts_settings['mercantile'];
		}
		else
		{
			$accounts_settings=getAccountSettingsForOC($agency_company_type_id);
			$booksStartingDate=getBooksStartingDateForOC($agency_company_type_id);
			$mercantile=$accounts_settings['mercantile'];
		}
		
		if(strtotime($payment['payment_date'])>=strtotime($booksStartingDate))	
		{
			return $amount-($emis_paid*$interestPerEMI);
		}	
		else return 0;
		
	}
}

function getAllTransactionsForLedgerIdMonthWise($id,$transaction_type_array=NULL,$from=NULL,$to=NULL)
{

	$month_year_array=getMonthYearArrayFromDates($from,$to);
	
	$return_array=array();
	foreach($month_year_array as $month_year)
	{
	
		$month=$month_year['month'];
		$year=$month_year['year'];
		$month_year=$month_year['month_year'];
	
		
	
	if((validateForNull($transaction_type_array) && in_array(1,$transaction_type_array)) || !validateForNull($transaction_type_array))
	$payments=getTotalPaymentForLedgerIdForMonth($id,$month,$year,$from,$to);
	
	
	
	if((validateForNull($transaction_type_array) && in_array(2,$transaction_type_array)) || !validateForNull($transaction_type_array))
	$receipts=getTotalReceiptForLedgerIdForMonth($id,$month,$year,$from,$to);
	
	
	
	if(substr($id, 0, 1) == 'L')
	{
		$ledger_id=str_replace('L','',$id);
		$ledger_id=intval($ledger_id);
		$head_type=getLedgerHeadType($ledger_id);
		}
	else if(substr($id, 0, 1) == 'C')
	{
		$head_type=1;
		}	
	
	
	
	if($head_type==0)
	{
		if((validateForNull($transaction_type_array) && in_array(4,$transaction_type_array)) || !validateForNull($transaction_type_array))
		$debit_contras=getTotalDebitContrasForLedgerIdMonthWiseBetweenDates($id,$month,$year,$from,$to);
		$credit_contras=getTotalCreditContrasForLedgerIdMonthWiseBetweenDates($id,$month,$year,$from,$to);
		$net_amount=$receipts-$payments+$debit_contras-$credit_contras;
		$debit_amount=$receipts+$debit_contras;
		$credit_amount=$payments+$credit_contras;
		$return_array[$month_year]=array($debit_amount,$credit_amount,$net_amount,$month,$year);
		}
	else if($head_type==1)
	{
		if((validateForNull($transaction_type_array) && in_array(3,$transaction_type_array)) || !validateForNull($transaction_type_array))
		$credit_jvs=getTotalCreditJVsForLedgerIdMonthWiseBetweenDates($id,$month,$year,$from,$to);
		$debit_jvs=getTotalDebitJVsForLedgerIdMonthWiseBetweenDates($id,$month,$year,$from,$to);
		$net_amount=-$receipts+$payments+$debit_jvs-$credit_jvs;
		$debit_amount=$payments+$debit_jvs;
		$credit_amount=$receipts+$credit_jvs;
		$return_array[$month_year]=array($debit_amount,$credit_amount,$net_amount,$month,$year);
		}
		
	}
	return $return_array;	
		
	}
	
	
function getAllTransactionsForLedgerId($id,$transaction_type_array=NULL,$from=NULL,$to=NULL,$sort_date=1)
{
	
	if((validateForNull($transaction_type_array) && in_array(1,$transaction_type_array)) || !validateForNull($transaction_type_array))
	$payments=getPaymentsForLedgerIdBetweenDates($id,$from,$to);
	
	
	if((validateForNull($transaction_type_array) && in_array(2,$transaction_type_array)) || !validateForNull($transaction_type_array))
	{
	$receipts=getReceiptsForLedgerIdBetweenDates($id,$from,$to);
	}
	
	if($id!=-1)
	{
	if(substr($id, 0, 1) == 'L')
	{
		$ledger_id=str_replace('L','',$id);
		$ledger_id=intval($ledger_id);
		$head_type=getLedgerHeadType($ledger_id);
		}
	else if(substr($id, 0, 1) == 'C')
	{
		$head_type=1;
		}	
	}
	else
	$head_type=-1;
	if($head_type==0)
	{
		
		if((validateForNull($transaction_type_array) && in_array(4,$transaction_type_array)) || !validateForNull($transaction_type_array))
		$contras=getContrasForLedgerIdBetweenDates($id,$from,$to);
		if(!is_array($payments)) $payments=array();
		if(!is_array($receipts)) $receipts=array();
		if(!is_array($contras)) $contras=array();
		$returnArray=array_merge($receipts,$payments,$contras);
		if($sort_date==1)
		uasort($returnArray,'TransDateComparator');
		
		return array($returnArray,$head_type);
		}
	else if($head_type==1)
	{
		
		if((validateForNull($transaction_type_array) && in_array(3,$transaction_type_array)) || !validateForNull($transaction_type_array))
		$jvs=getJVsForLedgerIdBetweenDates($id,$from,$to);
		if(!is_array($payments)) $payments=array();
		if(!is_array($receipts)) $receipts=array();
		if(!is_array($contras)) $contras=array();
		if(!is_array($jvs)) $jvs=array();
		$returnArray=array_merge($receipts,$payments,$jvs);
		if($sort_date==1)
		uasort($returnArray,'TransDateComparator');
		return array($returnArray,$head_type);
		}
	else if($id==-1)
	{
		
		if((validateForNull($transaction_type_array) && in_array(4,$transaction_type_array)) || !validateForNull($transaction_type_array))
		$contras=getContrasForLedgerIdBetweenDates($id,$from,$to);
		if((validateForNull($transaction_type_array) && in_array(3,$transaction_type_array)) || !validateForNull($transaction_type_array))
		$jvs=getJVsForLedgerIdBetweenDates($id,$from,$to);
		
		if(!is_array($payments)) $payments=array();
		if(!is_array($receipts)) $receipts=array();
		if(!is_array($contras)) $contras=array();
		if(!is_array($jvs)) $jvs=array();
		$returnArray=array_merge($receipts,$payments,$jvs,$contras);
		if($sort_date==1)
		uasort($returnArray,'TransDateComparator');
		return array($returnArray,-1);
		
	}	
	return false;	
		
	}	
	
function getAllTransactionForLedgerGroupId($group_id,$transaction_type_array=NULL,$from=NULL,$to=NULL) // $group_id =-1 means customers
{
	$return_array = array();
	if($group_id>0)
	{
	$grp=getLedgerGroupByID($group_id);

          $areas=$grp['ledgers_id'];
			 if($areas!=null)
			 {
			 $area_id_array=explode(",",$areas);
			 }
			
		if(is_array($area_id_array) && count($area_id_array)>0)
		{	 
		foreach($area_id_array as $ledger_id)
		{
			$transaction_array =  getAllTransactionsForLedgerId('L'.$ledger_id,$transaction_type_array,$from,$to);
			if(is_array($transaction_array))
			$return_array['L'.$ledger_id] = $transaction_array;
		}	 
		
		return $return_array;
		}
	}
	else if($group_id==-1)
	{
		$current_company=getCurrentCompanyForUser($_SESSION['adminSession']['admin_id']);
		$oc_agency_id=$current_company[0];
		$company_type=$current_company[1];
		
		
		if($company_type==0)
		{
			$dealers=listFilesForOurCompany($oc_agency_id);
			}
		else if($company_type==1)
		{
			$dealers=listFilesForAgency($oc_agency_id);
			}	
		else if($company_type==2)
		{
			$dealers=listFilesForCombinedAgency($oc_agency_id);
			}	
		$no=0;
	
		foreach($dealers as $agencyDetails)
		{
			$file_id=$agencyDetails['file_id'];
			$customer_id = getCustomerIdByFileId($file_id);
			$transaction_array = getAllTransactionsForLedgerId('C'.$customer_id,$transaction_type_array,$from,$to);
			if(is_array($transaction_array))
			$return_array['C'.$customer_id] = $transaction_array;
		}
		return $return_array;
	}
	else if($group_id==-2)
	{
		$ledgers = listLedgers();
		foreach($ledgers as $ledger)
		{
			$ledger_id = $ledger['ledger_id'];
			$transaction_array =  getAllTransactionsForLedgerId('L'.$ledger_id,$transaction_type_array,$from,$to);
			if(is_array($transaction_array))
			$return_array['L'.$ledger_id] = $transaction_array;
		}	
		return $return_array;
	}
	else if($group_id==-3)
	{
		$current_company=getCurrentCompanyForUser($_SESSION['adminSession']['admin_id']);
		$oc_agency_id=$current_company[0];
		$company_type=$current_company[1];
		
		if($company_type==0)
		{
			$dealers=listFilesForOurCompany($oc_agency_id);
			}
		else if($company_type==1)
		{
			$dealers=listFilesForAgency($oc_agency_id);
			}	
		else if($company_type==2)
		{
			$dealers=listFilesForCombinedAgency($oc_agency_id);
			}	
		$no=0;
	
		foreach($dealers as $agencyDetails)
		{
			$file_id=$agencyDetails['file_id'];
			$customer_id = getCustomerIdByFileId($file_id);
			$transaction_array = getAllTransactionsForLedgerId('C'.$customer_id,$transaction_type_array,$from,$to);
			if(is_array($transaction_array))
			$return_array['C'.$customer_id] = $transaction_array;
		}
		

		
		$ledgers = listLedgers();
		foreach($ledgers as $ledger)
		{
			$ledger_id = $ledger['ledger_id'];
			$return_array['L'.$ledger_id] = getAllTransactionsForLedgerId('L'.$ledger_id,$transaction_type_array,$from,$to);
		}	
		return $return_array;
	}
	else if($group_id==-4)
	{
		return getAllTransactionsForLedgerId(-1,$transaction_type_array,$from,$to);
	}
	else if($group_id==-5)
	{
		$admin_id=$_SESSION['adminSession']['admin_id'];
	if(COMPANY_RESTRICTION==1)
	{
		$admin_agencies=getAgenciesForAdminId($admin_id);
	    $admin_companies=getOurCompaniesForAdminId($admin_id);
	}	
		$cash_head_id = getCashHeadId();
		$sql="SELECT ledger_id FROM fin_ac_ledgers WHERE head_id = $cash_head_id ";
		if(COMPANY_RESTRICTION==1)
	{
		if(is_array($admin_agencies) && count($admin_agencies)>0)
		{
			$admin_agencies_string = implode(',',$admin_agencies);
			$sql=$sql." AND (agency_id IN ($admin_agencies_string) ";
			
		}
	}	
	 if(COMPANY_RESTRICTION==1)
	{
		if(is_array($admin_companies) && count($admin_companies)>0)
		{
		if(is_array($admin_agencies) && count($admin_agencies)>0)
		{
			$sql=$sql." OR ";
			
		}
		else
		$sql=$sql." AND ";
			
			$admin_companies_string = implode(',',$admin_companies);
			$sql=$sql." our_company_id IN ($admin_companies_string) ";
			
			if(is_array($admin_agencies) && count($admin_agencies)>0)
			{
			$sql=$sql." ) ";
			}
			
		}
		else
		{
			if(is_array($admin_agencies) && count($admin_agencies)>0)
			{
			$sql=$sql." ) ";
			}	
		}
		
		
		
	}
		$result = dbQuery($sql);
		$area_id_array = dbResultToArray($result);
		foreach($area_id_array as $ledger_id)
		{
			$ledger_id= $ledger_id[0];
			$transaction_array =  getAllTransactionsForLedgerId('L'.$ledger_id,$transaction_type_array,$from,$to,0);
			if(is_array($transaction_array))
			$return_array['L'.$ledger_id] = $transaction_array;
		}	 
		
		return $return_array;
	}
	
		return false;
}	

function getAllTransactionsForLedgerIdForMonth($id,$month,$year,$transaction_type_array=NULL,$from=NULL,$to=NULL)
{
	
	if((validateForNull($transaction_type_array) && in_array(1,$transaction_type_array)) || !validateForNull($transaction_type_array))
	$payments=getPaymentsForLedgerIdForMonth($id,$month,$year,$from,$to);
	
	
	
	if((validateForNull($transaction_type_array) && in_array(2,$transaction_type_array)) || !validateForNull($transaction_type_array))
	$receipts=getReceiptsForLedgerIdForMonth($id,$month,$year,$from,$to);
	
	
	if(substr($id, 0, 1) == 'L')
	{
		$ledger_id=str_replace('L','',$id);
		$ledger_id=intval($ledger_id);
		$head_type=getLedgerHeadType($ledger_id);
		}
	else if(substr($id, 0, 1) == 'C')
	{
		$head_type=1;
		}	
	
	
	
	if($head_type==0)
	{
		if((validateForNull($transaction_type_array) && in_array(4,$transaction_type_array)) || !validateForNull($transaction_type_array))
		$contras=getContrasForLedgerIdForMonth($id,$month,$year,$from,$to);
		if(!is_array($payments)) $payments=array();
		if(!is_array($receipts)) $receipts=array();
		if(!is_array($contras)) $contras=array();
		$returnArray=array_merge($payments,$receipts,$contras);
		uasort($returnArray,'TransDateComparator');
		
		return array($returnArray,$head_type);
		}
	else if($head_type==1)
	{
		if((validateForNull($transaction_type_array) && in_array(3,$transaction_type_array)) || !validateForNull($transaction_type_array))
		$jvs=getJVsForLedgerIdForMonth($id,$month,$year,$from,$to);
		
		
	
		if(!is_array($payments)) $payments=array();
		if(!is_array($receipts)) $receipts=array();
		if(!is_array($contras)) $contras=array();
		$returnArray=array_merge($payments,$receipts,$jvs);
		uasort($returnArray,'TransDateComparator');
		return array($returnArray,$head_type);
		}
	return false;	
		
	}		

function getOpeningBalanceForLedgerForDate($id,$date) // return opening balance on date for id, id should start with L or C
{
	
	$to=getPreviousDate($date); // return d-m-y
    
	$opening_balance_array=getOpeningBalanceForLedgerCustomer($id);
	
	if($opening_balance_array[1]==1)
	$opening_balance=-$opening_balance_array[0];
	else
	$opening_balance=$opening_balance_array[0];
	
	
	
	if(substr($id, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$id);
		$from_ledger=intval($from_ledger);
		$head_type=getLedgerHeadType($from_ledger);
		$from_customer=false;
		}
	else if(substr($id, 0, 1) == 'C')
	{
		$from_customer=str_replace('C','',$id);
		$from_customer=intval($from_customer);
		$from_ledger=false;

		}	
		
	
	$sql="  SELECT ";
	if(isset($head_type) && $head_type==0)
	$sql=$sql."-"; 
	$sql=$sql."sum(amount)
			  FROM fin_ac_payment WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id ";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer GROUP BY from_customer_id";	
	
	
	$sql=$sql." UNION ALL
			  SELECT ";
	if(!isset($head_type) || $head_type==1)
	$sql=$sql."-"; 		  
	$sql=$sql."sum(amount)
			  FROM fin_ac_receipt WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$from_customer GROUP BY to_customer_id";	
	
	if(!isset($head_type) || $head_type==1)
	{
		
		$sql=$sql." UNION ALL ";
				$sql=$sql." SELECT  -sum(fin_ac_jv_cd.amount)
					  FROM fin_ac_jv , fin_ac_jv_cd WHERE  ";
			if(isset($to) && validateForNull($to))  
			$sql=$sql."trans_date<='$to'
				  AND ";
			$sql=$sql." fin_ac_jv.jv_id = fin_ac_jv_cd.jv_id AND ";	  
			if(checkForNumeric($from_ledger))  
			$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";
			else if(checkForNumeric($from_customer))  
			$sql=$sql." from_customer_id=$from_customer GROUP BY from_customer_id";	  	
			$sql=$sql."
			 UNION ALL
			SELECT  SUM(fin_ac_jv_cd.amount) 
					  FROM fin_ac_jv,fin_ac_jv_cd WHERE ";	  
			if(isset($to) && validateForNull($to))  
			$sql=$sql."trans_date<='$to'
				  AND ";
			$sql=$sql." fin_ac_jv.jv_id = fin_ac_jv_cd.jv_id AND ";	  
			if(checkForNumeric($from_ledger))  
			$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id ";
			else if(checkForNumeric($from_customer)) 
			$sql=$sql." to_customer_id=$from_customer GROUP BY to_customer_id ";	
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	
	$net_amount=0;
	$net_amount=$net_amount+$opening_balance;
	if(dbNumRows($result)>0)
	{
		if(isset($resultArray[0][0]))
		$net_amount=$net_amount+$resultArray[0][0];
		if(isset($resultArray[1][0]))
		$net_amount=$net_amount+$resultArray[1][0];
		if(isset($resultArray[2][0]))
		$net_amount=$net_amount+$resultArray[2][0];
		if(isset($resultArray[3][0]))
		$net_amount=$net_amount+$resultArray[3][0];
		}
	
	return $net_amount;
	}
	else if($head_type==0)
	{
		
		$sql=$sql." UNION ALL SELECT -SUM(amount)
			  FROM fin_ac_contra WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id"; 
	
	$sql=$sql." UNION ALL SELECT SUM(amount)
			  FROM fin_ac_contra WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id";  	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	$net_amount=0;
	$net_amount=$net_amount+$opening_balance;
	if(dbNumRows($result)>0)
	{
		if(isset($resultArray[0][0]))
		$net_amount=$net_amount+$resultArray[0][0];
		if(isset($resultArray[1][0]))
		$net_amount=$net_amount+$resultArray[1][0];
		if(isset($resultArray[2][0]))
		$net_amount=$net_amount+$resultArray[2][0];
		if(isset($resultArray[3][0]))
		$net_amount=$net_amount+$resultArray[3][0];
		}
	return $net_amount;	
		}
	
}

function getOpeningBalanceForLedgerForPlSheet($id,$from,$to) // return opening balance on date for id, id should start with L or C
{
	
	$to=getPreviousDate($to); // return d-m-y
	
	$opening_balance_array=getOpeningBalanceForLedgerCustomer($id);
	
	if($opening_balance_array[1]==1)
	$opening_balance=-$opening_balance_array[0];
	else
	$opening_balance=$opening_balance_array[0];
	
	
	
	if(substr($id, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$id);
		$from_ledger=intval($from_ledger);
		$head_type=getLedgerHeadType($from_ledger);
		$from_customer=false;
		}
	else if(substr($id, 0, 1) == 'C')
	{
		$from_customer=str_replace('C','',$id);
		$from_customer=intval($from_customer);
		$from_ledger=false;

		}	
		
	
	$sql="  SELECT ";
	if(isset($head_type) && $head_type==0)
	$sql=$sql."-"; 
	$sql=$sql."sum(amount)
			  FROM fin_ac_payment WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND ";	  
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id ";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer GROUP BY from_customer_id";	
	
	
	$sql=$sql." UNION ALL
			  SELECT ";
	if(!isset($head_type) || $head_type==1)
	$sql=$sql."-"; 		  
	$sql=$sql."sum(amount)
			  FROM fin_ac_receipt WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND ";	  
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$from_customer GROUP BY to_customer_id";	
	
	if(!isset($head_type) || $head_type==1)
	{
		
		$sql=$sql." UNION ALL SELECT  -sum(fin_ac_jv_cd.amount)
				  FROM fin_ac_jv,fin_ac_jv_cd WHERE ";
		if(isset($to) && validateForNull($to))  
		$sql=$sql."trans_date<='$to'
			  AND ";
		if(isset($from) && validateForNull($from))  
		$sql=$sql."trans_date>='$from'
			  AND ";	
		$sql=$sql." fin_ac_jv.jv_id = fin_ac_jv_cd.jv_id AND ";	    
		if(checkForNumeric($from_ledger) && ($head_type==1 || $head_type==3 || $head_type==4))  
		$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";
		else if(checkForNumeric($from_customer))  
		$sql=$sql." from_customer_id=$from_customer GROUP BY from_customer_id";	  	
		
		
		
		$sql=$sql."
		 UNION ALL
		SELECT  SUM(fin_ac_jv_cd.amount) 
				  FROM fin_ac_jv,fin_ac_jv_cd WHERE ";	  
		if(isset($to) && validateForNull($to))  
		$sql=$sql."trans_date<='$to'
			  AND ";
		if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND ";	 	
		  $sql=$sql." fin_ac_jv.jv_id = fin_ac_jv_cd.jv_id AND ";  
		if(checkForNumeric($from_ledger)  && ($head_type==1 || $head_type==3 || $head_type==4))  
		$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id ";
		else if(checkForNumeric($from_customer)) 
		$sql=$sql." to_customer_id=$from_customer GROUP BY to_customer_id ";	
		
	
	
	$result=dbQuery($sql);
	
	$resultArray=dbResultToArray($result);
	
	
	$net_amount=0;
	$net_amount=$net_amount+$opening_balance;
	if(dbNumRows($result)>0)
	{
		if(isset($resultArray[0][0]))
		$net_amount=$net_amount+$resultArray[0][0];
		if(isset($resultArray[1][0]))
		$net_amount=$net_amount+$resultArray[1][0];
		if(isset($resultArray[2][0]))
		$net_amount=$net_amount+$resultArray[2][0];
		if(isset($resultArray[3][0]))
		$net_amount=$net_amount+$resultArray[3][0];
		}
	
	return $net_amount;
	}
	else if($head_type==0)
	{
		
		$sql=$sql." UNION ALL SELECT -SUM(amount)
			  FROM fin_ac_contra WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND ";	  
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id"; 
	
	$sql=$sql." UNION ALL SELECT SUM(amount)
			  FROM fin_ac_contra WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id";  	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	$net_amount=0;
	$net_amount=$net_amount+$opening_balance;
	if(dbNumRows($result)>0)
	{
		if(isset($resultArray[0][0]))
		$net_amount=$net_amount+$resultArray[0][0];
		if(isset($resultArray[1][0]))
		$net_amount=$net_amount+$resultArray[1][0];
		if(isset($resultArray[2][0]))
		$net_amount=$net_amount+$resultArray[2][0];
		if(isset($resultArray[3][0]))
		$net_amount=$net_amount+$resultArray[3][0];
		}
	return $net_amount;	
		}
	
}	

function getOpeningBalanceForHeadIdForDateForPlSheet($head_id,$from,$to) // $from should be of form Y-m-d
{
	
		$to=getPreviousDate($to); // return d-m-y
	
	$ledger_id_array=getLedgerIdsForHeadId($head_id);	
	$admin_id=$_SESSION['adminSession']['admin_id'];
	$cash_head_id=getCashHeadId();
	$bank_head_id=getBankAccountsHeadId();
	
	$current_company_array=getCurrentCompanyForUser($admin_id);
	$current_company_id=$current_company_array[0];
	$company_type=$current_company_array[1];
	
	if($company_type==0) // our_company
	{
		$book_startin_date=getBooksStartingDateForOC($current_company_id);
		}
	else if($company_type==1) // agency
	{
		$book_startin_date=getBooksStartingDateForAgency($current_company_id);
		}
	else if($company_type==2) // combined agency
	{
		
		$book_startin_date=getBooksStartingDateForCA($current_company_id);
	}
	
	if(strtotime($book_startin_date)<strtotime($from))
	$opening_balance=0;
	else
	$opening_balance=getOpeningBalanceForLedgerArray($ledger_id_array); // net opening balance for $id array of ledgers
	
	if(empty($ledger_id_array))
	return 0;
	
	$ids_string=implode(',',$ledger_id_array);
	
	$sql="SELECT ";
	if(($head_id==$cash_head_id || $head_id==$bank_head_id))
	$sql=$sql."-";
	$sql=$sql."SUM(amount) as amount
			  FROM fin_ac_payment WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND "; 
	if(($head_id==$cash_head_id || $head_id==$bank_head_id))  	  
	$sql=$sql." to_ledger_id IN ($ids_string) ";
	else
	$sql=$sql." from_ledger_id  IN ($ids_string) ";
	
	
	$sql=$sql." UNION ALL
			  SELECT ";
	if(($head_id!=$cash_head_id &&  $head_id!=$bank_head_id))
	$sql=$sql."-";		  
	$sql=$sql."SUM(amount)
			  FROM fin_ac_receipt WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND "; 	  
	if(($head_id==$cash_head_id || $head_id==$bank_head_id))  		  
	$sql=$sql." from_ledger_id IN ($ids_string) ";
	else 
	$sql=$sql." to_ledger_id IN ($ids_string) ";

  if(($head_id==$cash_head_id || $head_id==$bank_head_id)) 
	{
		
		$sql=$sql." UNION ALL SELECT -SUM(amount)
			  FROM fin_ac_contra WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND "; 	  
	if(validateForNull($ids_string))  	  
	$sql=$sql." from_ledger_id IN ($ids_string) "; 
	
	$sql=$sql." UNION ALL SELECT SUM(amount)
			  FROM fin_ac_contra WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND "; 	  
	if(validateForNull($ids_string))  
	$sql=$sql." to_ledger_id IN ($ids_string) ";  	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	$net_amount=0;
	$net_amount=$net_amount+$opening_balance;
	if(dbNumRows($result)>0)
	{
		if(isset($resultArray[0][0]))
		$net_amount=$net_amount+$resultArray[0][0];
		if(isset($resultArray[1][0]))
		$net_amount=$net_amount+$resultArray[1][0];
		if(isset($resultArray[2][0]))
		$net_amount=$net_amount+$resultArray[2][0];
		if(isset($resultArray[3][0]))
		$net_amount=$net_amount+$resultArray[3][0];
		}
	return array($net_amount);	
		}
	else // if not cash or bank account
	{
		
		$sql=$sql." UNION ALL SELECT -SUM(fin_ac_jv_cd.amount) 
			  FROM fin_ac_jv, fin_ac_jv_cd WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND "; 	  
	$sql=$sql." fin_ac_jv.jv_id = fin_ac_jv_cd.jv_id AND ";	  	  
	if(validateForNull($ids_string))  
	$sql=$sql." from_ledger_id IN ($ids_string) ";	  	
	$sql=$sql."
	 UNION ALL
	SELECT SUM(fin_ac_jv_cd.amount) as debit_amount
			  FROM fin_ac_jv,fin_ac_jv_cd WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND "; 	
	$sql=$sql." fin_ac_jv.jv_id = fin_ac_jv_cd.jv_id AND ";	    
	if(validateForNull($ids_string))  
	$sql=$sql." to_ledger_id IN ($ids_string)  ";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);

	
	$net_amount=0;
	$net_amount=$net_amount+$opening_balance;
	if(dbNumRows($result)>0)
	{
		if(isset($resultArray[0][0]))
		$net_amount=$net_amount+$resultArray[0][0];
		if(isset($resultArray[1][0]))
		$net_amount=$net_amount+$resultArray[1][0];
		if(isset($resultArray[2][0]))
		$net_amount=$net_amount+$resultArray[2][0];
		if(isset($resultArray[3][0]))
		$net_amount=$net_amount+$resultArray[3][0];
		}
		
	return array($net_amount);
	}
	 
	
}

// it gets u the computed opening balance upto specified date
function getOpeningBalanceForHeadIdForDate($head_id,$date) // head_id should be numeric and date is compalsory
{
	$to=getPreviousDate($date);
	
	$cash_head_id=getCashHeadId();
	$bank_head_id=getBankAccountsHeadId();
	$ledger_id_array=getLedgerIdsForHeadId($head_id);
	
	
	if(empty($ledger_id_array))
	return 0;
	$opening_balance=getOpeningBalanceForLedgerArray($ledger_id_array); // net opening balance for $id array of ledgers
	
	
	$ids_string=implode(',',$ledger_id_array);
	
	$sql="SELECT ";
	if(($head_id==$cash_head_id || $head_id==$bank_head_id))
	$sql=$sql."-";
	$sql=$sql."SUM(amount) as amount
			  FROM fin_ac_payment WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(($head_id==$cash_head_id || $head_id==$bank_head_id))  	  
	$sql=$sql." to_ledger_id IN ($ids_string) ";
	else
	$sql=$sql." from_ledger_id  IN ($ids_string) ";
	
	
	$sql=$sql." UNION ALL
			  SELECT ";
	if(($head_id!=$cash_head_id &&  $head_id!=$bank_head_id))
	$sql=$sql."-";		  
	$sql=$sql."SUM(amount)
			  FROM fin_ac_receipt WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(($head_id==$cash_head_id || $head_id==$bank_head_id))  		  
	$sql=$sql." from_ledger_id IN ($ids_string) ";
	else 
	$sql=$sql." to_ledger_id IN ($ids_string) ";

  if(($head_id==$cash_head_id || $head_id==$bank_head_id)) 
	{
		
		$sql=$sql." UNION ALL SELECT -SUM(amount)
			  FROM fin_ac_contra WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($ids_string))  	  
	$sql=$sql." from_ledger_id IN ($ids_string) "; 
	
	$sql=$sql." UNION ALL SELECT SUM(amount)
			  FROM fin_ac_contra WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($ids_string))  
	$sql=$sql." to_ledger_id IN ($ids_string) ";  	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	$net_amount=0;
	$net_amount=$net_amount+$opening_balance;
	if(dbNumRows($result)>0)
	{
		if(isset($resultArray[0][0]))
		$net_amount=$net_amount+$resultArray[0][0];
		if(isset($resultArray[1][0]))
		$net_amount=$net_amount+$resultArray[1][0];
		if(isset($resultArray[2][0]))
		$net_amount=$net_amount+$resultArray[2][0];
		if(isset($resultArray[3][0]))
		$net_amount=$net_amount+$resultArray[3][0];
		}
	return array($net_amount);	
		}
	else // if not cash or bank account
	{
		
		$sql=$sql." UNION ALL SELECT -SUM(fin_ac_jv_cd.amount) 
			  FROM fin_ac_jv,fin_ac_jv_cd WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." fin_ac_jv.jv_id = fin_ac_jv_cd.jv_id AND ";	  
	if(validateForNull($ids_string))  
	$sql=$sql." from_ledger_id IN ($ids_string)  ";	  	
	$sql=$sql."
	 UNION ALL
	SELECT SUM(fin_ac_jv_cd.amount) as debit_amount
			  FROM fin_ac_jv,fin_ac_jv_cd WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." fin_ac_jv.jv_id = fin_ac_jv_cd.jv_id AND ";	  
	if(validateForNull($ids_string))  
	$sql=$sql." to_ledger_id IN ($ids_string) ";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	
	$net_amount=0;
	$net_amount=$net_amount+$opening_balance;
	if(dbNumRows($result)>0)
	{
		if(isset($resultArray[0][0]))
		$net_amount=$net_amount+$resultArray[0][0];
		if(isset($resultArray[1][0]))
		$net_amount=$net_amount+$resultArray[1][0];
		if(isset($resultArray[2][0]))
		$net_amount=$net_amount+$resultArray[2][0];
		if(isset($resultArray[3][0]))
		$net_amount=$net_amount+$resultArray[3][0];
		}
		
	return array($net_amount);
	}
	
	
}

function getOpeningBalanceForCustomersForDate($date) // head_id should be numeric and date is compalsory
{
	$to=getPreviousDate($date);
	
	$cash_head_id=getCashHeadId();
	$bank_head_id=getBankAccountsHeadId();
	$ledger_id_array=listCustomerIDs();
	
	if(empty($ledger_id_array))
	return 0;
	$opening_balance=getOpeningBalanceForCustomerArray($ledger_id_array); // net opening balance for $id array of ledgers
	

	$ids_string=implode(',',$ledger_id_array);
	
	$sql="SELECT SUM(amount)
			  FROM fin_ac_payment WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($ids_string))  	  
	$sql=$sql." from_customer_id IN ($ids_string) ";	
	
	
	$sql=$sql." UNION ALL
			  SELECT -SUM(amount)
			  FROM fin_ac_receipt WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";	
	if(validateForNull($ids_string))  	  	  
	$sql=$sql." to_customer_id IN ($ids_string) ";	
	
 
		
		$sql=$sql." UNION ALL SELECT -SUM(fin_ac_jv_cd.amount) 
			  FROM fin_ac_jv,fin_ac_jv_cd WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." fin_ac_jv.jv_id = fin_ac_jv_cd.jv_id AND ";	  
	if(validateForNull($ids_string))  
	$sql=$sql." from_customer_id IN ($ids_string) ";	  	
	
	$sql=$sql."
	 UNION ALL
	SELECT SUM(fin_ac_jv_cd.amount) as debit_amount
			  FROM fin_ac_jv,fin_ac_jv_cd WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." fin_ac_jv.jv_id = fin_ac_jv_cd.jv_id AND ";	  
	if(validateForNull($ids_string))  
	$sql=$sql." to_customer_id IN ($ids_string) ";	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	$net_amount=0;
	$net_amount=$net_amount+$opening_balance;
	if(dbNumRows($result)>0)
	{
		if(isset($resultArray[0][0]))
		$net_amount=$net_amount+$resultArray[0][0];
		if(isset($resultArray[1][0]))
		$net_amount=$net_amount+$resultArray[1][0];
		if(isset($resultArray[2][0]))
		$net_amount=$net_amount+$resultArray[2][0];
		if(isset($resultArray[3][0]))
		$net_amount=$net_amount+$resultArray[3][0];
	}
	
	return array($net_amount);
}	


function TransDateComparator($a,$b){
	$aEMIDate=$a['trans_date'];
	$bEMIDate=$b['trans_date'];
	$aEMIDate = str_replace('/', '-', $aEMIDate);
	$aEMIDate=date('Y-m-d',strtotime($aEMIDate));
	$bEMIDate = str_replace('/', '-', $bEMIDate);
	$bEMIDate=date('Y-m-d',strtotime($bEMIDate));
if (strtotime($aEMIDate) < strtotime($bEMIDate)) return -1;
if (strtotime($aEMIDate) > strtotime($bEMIDate)) return 1;
return 0;
}

function getFirstPageBalanceSheet($to)
{
	$to=getNextDate($to); // return Y-m-d	
			
	$main_heads=listHeads();
    $return_array=array();
	$i=0;
	$pl_sheet_balance=0;
	foreach($main_heads as $head)
	{
		$head_id=$head['head_id'];
		$pl_sheet_head=checkifHeadisInPLSheet($head_id);
		
		
		
		$opening_balance_head=0;
		$opening_balance_head_array=getOpeningBalanceForHeadIdForDate($head_id,$to);
		$opening_balance_head=$opening_balance_head_array[0];
		$child_head_array=array();
		$j=0;

		$child_heads = getSubHeadsOfHead($head_id);
		if($child_heads)
		{
		foreach($child_heads as $child_head)
		{
			$child_head_id=$child_head['head_id'];
			$opening_balance_child=0;
			$opening_balance_child_array=getOpeningBalanceForHeadIdForDate($child_head_id,$to);
			$opening_balance_child=$opening_balance_child_array[0];
			if($child_head_id==13)
			{
			$customer_opening_balance_array=getOpeningBalanceForCustomersForDate($to);
			$customer_opening_balance=$customer_opening_balance_array[0];
			$opening_balance_child=$opening_balance_child+$customer_opening_balance;
			}
			$child_head[]=$opening_balance_child;
		    $child_head['opening_balance']=$opening_balance_child;
			$child_head_array[]=$child_head;
			$opening_balance_head=$opening_balance_head+$opening_balance_child;
		}
		}
		$head['opening_balance']=$opening_balance_head;
		$head['child_heads']=$child_head_array;
		
		if($pl_sheet_head)
		{
		$pl_sheet_balance=$pl_sheet_balance+$opening_balance_head;
		}
		else
		$return_array[]=$head;
	}
	
	$pl_sheet_ledger_id = getProfitAndLossLedgerId();
	if(is_numeric($pl_sheet_ledger_id))
	$opening_balance_pl_ledger=getOpeningBalanceForLedgerForDate('L'.$pl_sheet_ledger_id,$to);
	else
	$opening_balance_pl_ledger=0;
	$return_array['profit_loss']['head_id']=0;
	$return_array['profit_loss']['head_name']='Profit And Loss';
	$return_array['profit_loss']['opening_balance']=$pl_sheet_balance+$opening_balance_pl_ledger;
	return $return_array;
}

function getProfitAndLossSheet($from,$to) // $from and $to of form dd/mm/yyyy
{
	$to=getNextDate($to); // return Y-m-d
	
	
	if(isset($from) && validateForNull($from))
			{
		    $from = str_replace('/', '-', $from);
			$from=date('Y-m-d',strtotime($from));
			}	
	

	
	$main_heads=listPlSheetHeads();
    $return_array=array();
	$i=0;
	
	foreach($main_heads as $head)
	{
		$head_id=$head['head_id'];
		$opening_balance_head=0;
		$opening_balance_head_array=getOpeningBalanceForHeadIdForDateForPlSheet($head_id,$from,$to);
		$opening_balance_head=$opening_balance_head_array[0];
		$child_head_array=array();
		$j=0;
		$child_heads = getSubHeadsOfHead($head_id);
		if($child_heads)
		{
		foreach($child_heads as $child_head)
		{
			$child_head_id=$child_head['head_id'];
			$opening_balance_child=0;
			$opening_balance_child_array=getOpeningBalanceForHeadIdForDateForPlSheet($child_head_id,$from,$to);
			$opening_balance_child=$opening_balance_child_array[0];
			$child_head[]=$opening_balance_child;
		    $child_head['opening_balance']=$opening_balance_child;
			$child_head_array[]=$child_head;
			$opening_balance_head=$opening_balance_head+$opening_balance_child;
		}
		}
		$head['opening_balance']=$opening_balance_head;
		$head['child_heads']=$child_head_array;
		$return_array[]=$head;
	}
	
	$direct_income=$return_array[0]['opening_balance'];
	$indirect_income=$return_array[1]['opening_balance'];
	$direct_exp=$return_array[2]['opening_balance'];
	$indirect_exp=$return_array[3]['opening_balance'];
	
	$gross_profit=$direct_exp+$direct_income;
	$sub_total=0;
	$total=0;
	$net_profit=$gross_profit+$indirect_income+$indirect_exp;
	
	if($direct_income<=0)
	$sub_total=$sub_total+$direct_income;
	
	if($direct_exp<0)
	$sub_total=$sub_total+$direct_exp;
	
	if($gross_profit>0)
	$sub_total=$sub_total-$gross_profit;
	
	if($sub_total<0)
	$sub_total=-$sub_total;
	
	if($gross_profit<0)
	$total=$total+$gross_profit;
	
	if($indirect_income<=0)
	$total=$total+$indirect_income;
	
	if($indirect_exp<0)
	$total=$total+$indirect_exp;
	
	if($net_profit>0)
	$total=$total-$net_profit;
	
	if($total<0)
	$total=-$total;
	
	
	
	$return_array['gross_profit']=$gross_profit; // should be negative if profit and positive if loss
	$return_array['subtotal']=$sub_total; // always positive
	$return_array['nett_profit']=$net_profit; // should be negative if profit and positive if loss
	$return_array['total']=$total; // always positive
	
	return $return_array;
}

/**
 * returns an array to create second page of balance sheet which may contain subheads and/or ledgers of head id.
 *
 * @param  head_id    head_id for which second page of balance sheet should be created 
 * @return array form : array([0] => head (head details, child heads and ledgers))
 */ 
function getSecondPageBalanceSheet($head_id)
{
	
	$admin_id=$_SESSION['adminSession']['admin_id'];
	$period = getPeriodForUser($admin_id);
	$from=date('d/m/Y',strtotime($period[0]));
	$to=date('d/m/Y',strtotime($period[1]));
	$head=getHeadById($head_id);
	$to=getNextDate($to);
	
    $return_array=array();
	$i=0;
	
		$sundry_debtors_id=getSundryDebtorsId();
		$opening_balance_head=0;
		$opening_balance_head_array=getOpeningBalanceForHeadIdForDate($head_id,$to);
		$opening_balance_head=$opening_balance_head_array[0];
		
		if($sundry_debtors_id==$head_id)
		{
		$opening_balance_customers_array=getOpeningBalanceForCustomersForDate($to);
		$opening_balance_customers=$opening_balance_customers_array[0];
		}
		
		$opening_balance_head=$opening_balance_head+$opening_balance_customers;
		
		$child_head_array=array();
		$ledgers_array=array();
		$j=0;
		$child_heads = getSubHeadsOfHead($head_id);
		if($child_heads)
		{
		foreach($child_heads as $child_head)
		{
			$child_head_id=$child_head['head_id'];
			$opening_balance_child=0;
			$opening_balance_child_array=getOpeningBalanceForHeadIdForDate($child_head_id,$to);
			$opening_balance_child=$opening_balance_child_array[0];
			if($child_head_id==13)
			{
			$customer_opening_balance_array=getOpeningBalanceForCustomersForDate($to);
			$customer_opening_balance=$customer_opening_balance_array[0];
			$opening_balance_child=$opening_balance_child+$customer_opening_balance;
			}
			$child_head[]=$opening_balance_child;
		    $child_head['opening_balance']=$opening_balance_child;
			$child_head_array[]=$child_head;
			$opening_balance_head=$opening_balance_head+$opening_balance_child;
		}
		}
		$ledgers_customers=listDirectCustomerAndLedgersWithBankCashForHeadId($head_id);
		$ob=0;
		
		if($ledgers_customers)
		{
		foreach($ledgers_customers as $ledgers_customer)
		{
			$ledger_id=$ledgers_customer['id'];
			$opening_balance_ledger=0;
			$opening_balance_ledger=getOpeningBalanceForLedgerForDate($ledger_id,$to);
			
			$ledgers_customer[]=$opening_balance_ledger;
		    $ledgers_customer['opening_balance']=$opening_balance_ledger;
			$ob=$ob+$opening_balance_ledger;
			$ledgers_array[]=$ledgers_customer;
		}
		}
		
		$head['opening_balance']=$opening_balance_head;
		$head['child_heads']=$child_head_array;
		$head['ledgers']=$ledgers_array;
		$return_array=$head;
	return $return_array;
}

/**
 * returns an array to create second page of Profit and loss sheet which may contain subheads and/or ledgers of head id.
 *
 * @param  head_id    head_id for which second page of balance sheet should be created 
 * @return array form : array([0] => head (head details, child heads and ledgers))
 */ 
function getSecondPagePLSheet($head_id)
{
	
	$admin_id=$_SESSION['adminSession']['admin_id'];
	$period = getPeriodForUser($admin_id);
	$from=$period[0]; // Y-m-d
	$to = date('d/m/Y',strtotime($period[1]));
	$head=getHeadById($head_id);
	$to=getNextDate($to); // return Y-m-d
	
    $return_array=array();
	$i=0;
	
	$opening_balance_head=0;
	
	$opening_balance_head_array=getOpeningBalanceForHeadIdForDateForPlSheet($head_id,$from,$to);
	$opening_balance_head=$opening_balance_head_array[0];
	$opening_balance_head=$opening_balance_head+$opening_balance_customers;

		$child_head_array=array();
		$ledgers_array=array();
		$j=0;
		$child_heads = getSubHeadsOfHead($head_id);
		
		if($child_heads)
		{
		foreach($child_heads as $child_head)
		{
			$child_head_id=$child_head['head_id'];
			$opening_balance_child=0;
			$opening_balance_child_array=getOpeningBalanceForHeadIdForDateForPlSheet($child_head_id,$from,$to);
			
			$opening_balance_child=$opening_balance_child_array[0];
			$child_head[]=$opening_balance_child;
		    $child_head['opening_balance']=$opening_balance_child;
			$child_head_array[]=$child_head;
			$opening_balance_head=$opening_balance_head+$opening_balance_child;
		}
		}
		$ledgers_customers=listDirectCustomerAndLedgersWithBankCashForHeadId($head_id);
		$ob=0;
		if($ledgers_customers)
		{
		foreach($ledgers_customers as $ledgers_customer)
		{
			$ledger_id=$ledgers_customer['id'];
			$opening_balance_ledger=0;
			$opening_balance_ledger=getOpeningBalanceForLedgerForPlSheet($ledger_id,$from,$to);
			
			$ledgers_customer[]=$opening_balance_ledger;
		    $ledgers_customer['opening_balance']=$opening_balance_ledger;
			$ob=$ob+$opening_balance_ledger;
			$ledgers_array[]=$ledgers_customer;
		}
		}
		
		$head['opening_balance']=$opening_balance_head;
		$head['child_heads']=$child_head_array;
		$head['ledgers']=$ledgers_array;
		$return_array=$head;
	    return $return_array;
}

?>