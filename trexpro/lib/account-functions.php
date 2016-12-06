<?php
require_once("cg.php");
require_once("city-functions.php");
require_once("account-ledger-functions.php");
require_once("account-head-functions.php");
require_once("account-payment-functions.php");
require_once("account-receipt-functions.php");
require_once("account-purchase-functions.php");
require_once("account-sales-functions.php");
require_once("account-jv-functions.php");
require_once("account-debit-note-functions.php");
require_once("account-credit-note-functions.php");
require_once("tax-functions.php");
require_once("inventory-functions.php");
require_once("account-contra-functions.php");
require_once("account-combined-agency-functions.php");
require_once("common.php");
require_once("bd.php");

function getBooksStartingDateForAgency($agency_id)
{
	if(checkForNumeric($agency_id))
	{
		$sql="SELECT ac_starting_date FROM edms_ac_settings WHERE agency_id=$agency_id";
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
		$sql="SELECT ac_starting_date FROM edms_ac_settings WHERE our_company_id=$oc_id";
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
		$sql="SELECT ac_starting_date FROM edms_ac_combined_agency WHERE combined_agency_id=$ca_id";
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
		$sql="SELECT * FROM edms_ac_settings WHERE agency_id=$agency_id";
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
		$sql="SELECT * FROM edms_ac_settings WHERE our_company_id=$oc_id";
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
		$sql="SELECT current_balance, current_balance_cd FROM edms_ac_ledgers WHERE ledger_id=$ledger_id";
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
		$sql="SELECT current_balance, current_balance_cd FROM edms_customer WHERE customer_id=$ledger_id";
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
		
		$sql="UPDATE edms_ac_payment SET from_ledger_id=$ledger_id, from_customer_id=NULL, auto_rasid_type=0, auto_id=0 WHERE from_customer_id=$customer_id";
			dbQuery($sql);
			
		$sql="UPDATE edms_ac_receipt SET to_ledger_id=$ledger_id, to_customer_id=NULL,  auto_rasid_type=0, auto_id=0  WHERE to_customer_id=$customer_id";
			dbQuery($sql);	
		
		$sql="UPDATE edms_ac_jv INNER JOIN edms_ac_jv_cd ON edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id SET to_ledger_id=$ledger_id, to_customer_id=NULL, auto_rasid_type=0, auto_id=0  WHERE to_customer_id=$customer_id";
			dbQuery($sql);	
		
		$sql="UPDATE edms_ac_jv INNER JOIN edms_ac_jv_cd ON edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id SET from_ledger_id=$ledger_id, from_customer_id=NULL, auto_rasid_type=0, auto_id=0  WHERE from_customer_id=$customer_id";
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
		$sql="UPDATE edms_ac_ledgers SET current_balance = $current_balance , current_balance_cd = $current_balance_cd
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
		$sql="UPDATE edms_ac_ledgers SET current_balance=$current_balance , current_balance_cd= $current_balance_cd
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
		$sql="UPDATE edms_customer SET current_balance=$current_balance , current_balance_cd= $current_balance_cd
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
		
		$sql="UPDATE edms_customer SET current_balance=$current_balance , current_balance_cd= $current_balance_cd
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
	$sql="SELECT ledger_id FROM edms_ac_ledgers WHERE head_id=$cash_head_id AND";
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
	$sql="SELECT ledger_id FROM edms_ac_ledgers WHERE head_id=$cash_head_id AND ";
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

function getKasarLedgerIdForOC($oc_id)
{
	if(checkForNumeric($oc_id))
	{
	$cash_head_id=getDirectExpensesId();
	$ca_id=getCombinedAgencyIdForOCId($oc_id); // returns id or false
		if(checkForNumeric($ca_id))
		{
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);	
		}
	$sql="SELECT ledger_id FROM edms_ac_ledgers WHERE head_id=$cash_head_id AND ledger_name = 'Kasar' AND ";
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
		
		$result=createKasarLedgerForOC($oc_id);
		if($result)
		{
		return getKasarLedgerIdForOC($oc_id);
		}
		}
	}
	return false;	
}


function getOutSideJobLedgerIdForOC($oc_id)
{
	if(checkForNumeric($oc_id))
	{
	$cash_head_id=getDirectExpensesId();
	$ca_id=getCombinedAgencyIdForOCId($oc_id); // returns id or false
		if(checkForNumeric($ca_id))
		{
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$oc_id_array=$agency_oc_id_array[1];
			$oc_ids=implode(',',$oc_id_array);	
		}
	$sql="SELECT ledger_id FROM edms_ac_ledgers WHERE head_id=$cash_head_id AND  ledger_name='OutSide Job' AND ";
	if(!$ca_id)
	$sql=$sql." oc_id=$oc_id";
	else if(checkForNumeric($ca_id))
	{
	if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
	} 
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	{
		
		$result=createOutSideJobLedgerForOC($oc_id);
		if($result)
		{
		return getOutSideJobLedgerIdForOC($oc_id);
		}
		}
	}
	return false;	
}


function getAdvanceInterestLedgerIdForOC($oc_id)
{
	if(checkForNumeric($oc_id))
	{
			
	$unsecured_loans_head_id=getUnsecuredLoansId();
	$ca_id=getCombinedAgencyIdForOCId($oc_id); // returns id or false
		if(checkForNumeric($ca_id))
		{
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);	
		}
	$sql="SELECT ledger_id FROM edms_ac_ledgers WHERE head_id=$unsecured_loans_head_id  AND ledger_name='Auto Interest' AND ";
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
	$unsecured_loans_head_id=getUnsecuredLoansId();
	$ca_id=getCombinedAgencyIdForAgencyId($agency_id); // returns id or false
		if(checkForNumeric($ca_id))
		{
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);	
		}
	$sql="SELECT ledger_id FROM edms_ac_ledgers WHERE head_id=$unsecured_loans_head_id  AND ledger_name='Auto Interest' AND ";
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
	$sql="SELECT ledger_id FROM edms_ac_ledgers WHERE head_id=$income_head_id  AND ledger_name='Finance Income' AND ";
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
	$sql="SELECT ledger_id FROM edms_ac_ledgers WHERE head_id=$income_head_id AND  ledger_name='Finance Income' AND ";
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

	$month_year_array=getMonthYearArrayFromDates($from,$to);
	
	$return_array=array();
	foreach($month_year_array as $month_year)
	{
		
		$month=$month_year['month'];
		$year=$month_year['year'];
		$month_year=$month_year['month_year'];
	
		
	
	if((validateForNull($transaction_type_array) && in_array(1,$transaction_type_array)) || !validateForNull($transaction_type_array))
	$payments=getTotalPaymentForLedgerIdForMonth($id,$month,$year,$from,$to);
	else
	$payments=0;
	
	
	
	if((validateForNull($transaction_type_array) && in_array(5,$transaction_type_array)) || !validateForNull($transaction_type_array))
	{	
	$purchase=getTotalPurchaseForLedgerIdForMonth($id,$month,$year,$from,$to);
	$purchase_tax = getTotalPurchaseTaxesForLedgerIdForMonth($id,$month,$year,$from,$to);
	}
	else
	{
	$purchase=0;
	$purchase_tax = 0;
	}
	
	if((validateForNull($transaction_type_array) && in_array(6,$transaction_type_array)) || !validateForNull($transaction_type_array))
	{
		
		$sale=getTotalSaleForLedgerIdForMonth($id,$month,$year,$from,$to);
		$sales_tax = getTotalSalesTaxeForLedgerIdForMonth($id,$month,$year,$from,$to);
					
	}
	else
	{
	$sale=0;
	$sales_tax =0;
	}
	
	if((validateForNull($transaction_type_array) && in_array(7,$transaction_type_array)) || !validateForNull($transaction_type_array))
	{	
	$debit_note=getTotalDebitNoteForLedgerIdForMonth($id,$month,$year,$from,$to);
	$debit_note_tax = getTotalDebitNoteTaxesForLedgerIdForMonth($id,$month,$year,$from,$to);
	}
	else
	{
	$debit_note=0;
	$debit_note_tax = 0;
	}
	
	if((validateForNull($transaction_type_array) && in_array(8,$transaction_type_array)) || !validateForNull($transaction_type_array))
	{
		$credit_note=getTotalCreditNoteForLedgerIdForMonth($id,$month,$year,$from,$to);
		$credit_note_tax = getTotalCreditNoteTaxeForLedgerIdForMonth($id,$month,$year,$from,$to);
	}
	else
	{
	$credit_note=0 ;
	$credit_note_tax =0 ;
	}
	
	
	if((validateForNull($transaction_type_array) && in_array(2,$transaction_type_array)) || !validateForNull($transaction_type_array))
	$receipts=getTotalReceiptForLedgerIdForMonth($id,$month,$year,$from,$to);
	else
	$receipts=0;
	

	
	
	if($head_type==0)
	{
		if((validateForNull($transaction_type_array) && in_array(4,$transaction_type_array)) || !validateForNull($transaction_type_array))
		{
		$debit_contras=getTotalDebitContrasForLedgerIdMonthWiseBetweenDates($id,$month,$year,$from,$to);
		$credit_contras=getTotalCreditContrasForLedgerIdMonthWiseBetweenDates($id,$month,$year,$from,$to);
		}
		else
		{
		$debit_contras=0;
		$credit_contras=0;	
		}
		$net_amount=$receipts-$payments+$debit_contras-$credit_contras-$purchase+$sale-$purchase_tax+$sales_tax+$debit_note-$credit_note+$debit_note_tax-$credit_note_tax;
		$debit_amount=$receipts+$debit_contras+$sale+$sales_tax+$debit_note+$debit_note_tax;
		$credit_amount=$payments+$credit_contras+$purchase+$purchase_tax+$credit_note+$credit_note_tax;
		$return_array[$month_year]=array($debit_amount,$credit_amount,$net_amount,$month,$year);
		}
	else if($head_type==1)
	{
		if((validateForNull($transaction_type_array) && in_array(3,$transaction_type_array)) || !validateForNull($transaction_type_array))
		{
			
		$credit_jvs=getTotalCreditJVsForLedgerIdMonthWiseBetweenDates($id,$month,$year,$from,$to);
		$debit_jvs=getTotalDebitJVsForLedgerIdMonthWiseBetweenDates($id,$month,$year,$from,$to);
		}
		else
		{
		$debit_jvs=0;
		$credit_jvs=0;	
		}
		$net_amount=-$receipts+$payments+$debit_jvs-$credit_jvs-$purchase+$sale-$purchase_tax+$sales_tax+$debit_note-$credit_note+$debit_note_tax-$credit_note_tax;
		$debit_amount=$payments+$debit_jvs+$sale+$sales_tax+$debit_note+$debit_note_tax;
		$credit_amount=$receipts+$credit_jvs+$purchase+$purchase_tax+$credit_note+$credit_note_tax;
		$return_array[$month_year]=array($debit_amount,$credit_amount,$net_amount,$month,$year);
	}
	else if($head_type==2)
	{
		if((validateForNull($transaction_type_array) && in_array(3,$transaction_type_array)) || !validateForNull($transaction_type_array))
		{
		$credit_jvs=getTotalCreditJVsForLedgerIdMonthWiseBetweenDates($id,$month,$year,$from,$to);
		$debit_jvs=getTotalDebitJVsForLedgerIdMonthWiseBetweenDates($id,$month,$year,$from,$to);
		}
		else
		{
		$debit_jvs=0;
		$credit_jvs=0;	
		}
		
		$net_amount=-$receipts+$payments+$debit_jvs-$credit_jvs+$purchase-$sale+$purchase_tax-$sales_tax-$debit_note+$credit_note-$debit_note_tax+$credit_note_tax;
		$credit_amount=$receipts+$credit_jvs+$sale+$sales_tax+$debit_note+$debit_note_tax;
		$debit_amount=$payments+$debit_jvs+$purchase+$purchase_tax+$credit_note+$credit_note_tax;
		$return_array[$month_year]=array($debit_amount,$credit_amount,$net_amount,$month,$year);
	}
	else if($head_type==3)
	{
		if((validateForNull($transaction_type_array) && in_array(3,$transaction_type_array)) || !validateForNull($transaction_type_array))
		{
		$credit_jvs=getTotalCreditJVsForLedgerIdMonthWiseBetweenDates($id,$month,$year,$from,$to);
		$debit_jvs=getTotalDebitJVsForLedgerIdMonthWiseBetweenDates($id,$month,$year,$from,$to);
		}
		else
		{
		$debit_jvs=0;
		$credit_jvs=0;	
		}
		$net_amount=$debit_jvs-$credit_jvs+$purchase-$debit_note;
		$debit_amount=$purchase+$debit_jvs;
		$credit_amount=$credit_jvs + $debit_note;
		$return_array[$month_year]=array($debit_amount,$credit_amount,$net_amount,$month,$year);
		
	}
	else if($head_type==4)
	{
		if((validateForNull($transaction_type_array) && in_array(3,$transaction_type_array)) || !validateForNull($transaction_type_array))
		{
			
		$credit_jvs=getTotalCreditJVsForLedgerIdMonthWiseBetweenDates($id,$month,$year,$from,$to);
		$debit_jvs=getTotalDebitJVsForLedgerIdMonthWiseBetweenDates($id,$month,$year,$from,$to);
	
		}
		else
		{
		$debit_jvs=0;
		$credit_jvs=0;	
		}
		$net_amount=$debit_jvs-$credit_jvs-$sale+$credit_note;
		$debit_amount=$debit_jvs+$credit_note;
		$credit_amount=$credit_jvs+$sale;
		$return_array[$month_year]=array($debit_amount,$credit_amount,$net_amount,$month,$year);
		
	}
		
	}

	return $return_array;	
		
	}

function getAllTransactionsForLedgerIdOneQuery($id,$transaction_type_array=NULL,$from=NULL,$to=NULL)
{
	
$or_id = $id;
if(substr($id, 0, 1) == 'L')
	{
		$id=str_replace('L','',$id);
		$id=intval($id);
		$customer_id="NULL";
		$head_type=getLedgerHeadType($id);
		}
	else if(substr($id, 0, 1) == 'C')
	{
		$id=str_replace('C','',$id);
		$customer_id=intval($id);
		$id="NULL";
		$head_type=1;
		}	
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
if($head_type!=2)
{
	$sql="";
	if((validateForNull($transaction_type_array) && in_array(1,$transaction_type_array)) || !validateForNull($transaction_type_array))
	{
	$sql=$sql."SELECT payment_id as id, 1 as type,amount,from_ledger_id as debit_ledger_id ,to_ledger_id as credit_ledger_id ,from_customer_id as debit_customer_id, NULL as credit_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified,1 as other_ledger_id
			  FROM edms_ac_payment WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(checkForNumeric($id))  	  
	$sql=$sql." ( from_ledger_id=$id OR to_ledger_id = $id) ";
	else if(checkForNumeric($customer_id))
	$sql=$sql."   from_customer_id=$customer_id";
	}
	
	
	if((validateForNull($transaction_type_array) && in_array(2,$transaction_type_array)) || !validateForNull($transaction_type_array))
	{
	if($sql!="")
	$sql=$sql." UNION ALL ";
		
	$sql=$sql."SELECT receipt_id as id, 2 as type,amount,from_ledger_id as debit_ledger_id,to_ledger_id as credit_ledger_id, NULL as debit_customer_id,to_customer_id as credit_customer_id,oc_id,auto_rasid_type,auto_id, trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified,1 as other_ledger_id
			  FROM edms_ac_receipt WHERE ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(checkForNumeric($id))  	  
	$sql=$sql." ( from_ledger_id=$id OR to_ledger_id = $id) ";
	else if(checkForNumeric($customer_id))
	$sql=$sql."  to_customer_id=$customer_id";	  
	}
	
	

	
		if((validateForNull($transaction_type_array) && in_array(4,$transaction_type_array) && is_numeric($id)) || !validateForNull($transaction_type_array) && checkForNumeric($head_type) && is_numeric($id))
		{
		if($sql!="")
	$sql=$sql." UNION ALL ";
		
		$sql=$sql."SELECT contra_id as id, 4 as type,amount,to_ledger_id as debit_ledger_id,from_ledger_id as credit_ledger_id,NULL as debit_customer_id, NULL as credit_customer_id,oc_id,0 as auto_rasid_type,0 as auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified,1 as other_ledger_id
			  FROM edms_ac_contra WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(checkForNumeric($id))  	  
	$sql=$sql." ( from_ledger_id=$id OR to_ledger_id = $id) ";	  
		
		}
		
		
		
		
		if((validateForNull($transaction_type_array) && in_array(3,$transaction_type_array)) || !validateForNull($transaction_type_array))
		{
		if($sql!="")
	$sql=$sql." UNION ALL ";	
		$sql=$sql."SELECT edms_ac_jv.jv_id as id,3 as type,edms_ac_jv_cd.amount, to_ledger_id as debit_ledger_id,from_ledger_id as credit_ledger_id,to_customer_id as debit_customer_id,from_customer_id as credit_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified ,(
		IF(from_ledger_id IS NULL AND from_customer_id IS NULL,
		(SELECT IF(from_ledger_id IS NULL,CONCAT('C',from_customer_id),CONCAT('L',from_ledger_id)) FROM edms_ac_jv_cd as inner_jv_cd WHERE inner_jv_cd.jv_id = edms_ac_jv.jv_id AND (from_ledger_id IS NOT NULL OR from_customer_id IS NOT NULL) LIMIT 1), (SELECT IF(to_ledger_id IS NULL,CONCAT('C',to_customer_id),CONCAT('L',to_ledger_id)) FROM edms_ac_jv_cd as inner_jv_cd WHERE inner_jv_cd.jv_id = edms_ac_jv.jv_id AND (to_ledger_id IS NOT NULL OR to_customer_id IS NOT NULL) LIMIT 1))) as other_ledger_id
			  FROM edms_ac_jv,edms_ac_jv_cd WHERE  ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql = $sql." edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";	  
	if(checkForNumeric($id))  
	$sql=$sql." (from_ledger_id=$id OR to_ledger_id=$id) ";
	else if(checkForNumeric($customer_id))  
	$sql=$sql." (from_customer_id=$customer_id OR to_customer_id=$customer_id)";	  
		}
		
		if((validateForNull($transaction_type_array) && in_array(5,$transaction_type_array)) || !validateForNull($transaction_type_array))
		{
		if($sql!="")
	$sql=$sql." UNION ALL ";
		
		$sql=$sql."SELECT edms_ac_purchase.purchase_id as id,5 as type, amount ";
	if(!isset($head_type) || $head_type==0 || $head_type==1)
	$sql=$sql." + IF(total_tax IS NOT NULL,total_tax,0) AS amount ";
	$sql=$sql." ,to_ledger_id as debit_ledger_id,from_ledger_id as credit_ledger_id, NULL as debit_customer_id ,from_customer_id as credit_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified,1 as other_ledger_id
			  FROM edms_ac_purchase LEFT JOIN (SELECT f.purchase_id, SUM( tax_amount ) AS total_tax, e.tax_group_id, e.in_out
FROM edms_ac_purchase_tax f, edms_tax_grp e, edms_rel_tax_grp_tax g
WHERE f.tax_id = g.tax_id
AND g.tax_group_id = e.tax_group_id
GROUP BY f.purchase_id
)h ON edms_ac_purchase.purchase_id = h.purchase_id
WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(checkForNumeric($id))  	  
	$sql=$sql."  ( to_ledger_id=$id OR from_ledger_id = $id) ";
	else if(checkForNumeric($customer_id))
	$sql=$sql."   from_customer_id=$customer_id";	  
		  
		}
		
		if((validateForNull($transaction_type_array) && in_array(6,$transaction_type_array)) || !validateForNull($transaction_type_array))
		{
		if($sql!="")
	$sql=$sql." UNION ALL ";
	$sql=$sql."SELECT edms_ac_sales.sales_id as id, 6 as type, amount ";
	if(!isset($head_type)  || $head_type==0 || $head_type==1 )
	$sql=$sql." + IF(total_tax IS NOT NULL,total_tax,0) AS amount ";
	$sql=$sql." ,to_ledger_id as debit_ledger_id , from_ledger_id as credit_ledger_id,to_customer_id as debit_customer_id,NULL as credit_customer_id,oc_id,auto_rasid_type,auto_id, trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified,1 as other_ledger_id
			  FROM edms_ac_sales LEFT JOIN (SELECT f.sales_id, SUM( tax_amount ) AS total_tax, e.tax_group_id, e.in_out
FROM edms_ac_sales_tax f, edms_tax_grp e, edms_rel_tax_grp_tax g
WHERE f.tax_id = g.tax_id
AND g.tax_group_id = e.tax_group_id
GROUP BY f.sales_id
)h ON edms_ac_sales.sales_id = h.sales_id WHERE ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";	
	if(checkForNumeric($id))  	  
	$sql=$sql."  ( to_ledger_id=$id OR from_ledger_id = $id) ";
	else if(checkForNumeric($customer_id))
	$sql=$sql."  to_customer_id=$customer_id";	  	  
		
		}
		
		if((validateForNull($transaction_type_array) && in_array(7,$transaction_type_array)) || !validateForNull($transaction_type_array))
		{
		if($sql!="")
	$sql=$sql." UNION ALL ";	
		$sql=$sql."SELECT edms_ac_debit_note.debit_note_id as id, 7 as type, amount ";
	if(!isset($head_type) || $head_type==0 || $head_type==1)
	$sql=$sql." + IF(total_tax IS NOT NULL,total_tax,0) AS amount ";
	$sql=$sql." ,from_ledger_id as debit_ledger_id,to_ledger_id as credit_ledger_id, from_customer_id as debit_customer_id,NULL as credit_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified,1 as other_ledger_id
			  FROM edms_ac_debit_note LEFT JOIN (SELECT f.debit_note_id, SUM( tax_amount ) AS total_tax, e.tax_group_id, e.in_out
FROM edms_ac_debit_note_tax f, edms_tax_grp e, edms_rel_tax_grp_tax g
WHERE f.tax_id = g.tax_id
AND g.tax_group_id = e.tax_group_id
GROUP BY f.debit_note_id
)h ON edms_ac_debit_note.debit_note_id = h.debit_note_id
WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(checkForNumeric($id))  	  
	$sql=$sql." ( to_ledger_id=$id OR from_ledger_id = $id) ";
	else if(checkForNumeric($customer_id))
	$sql=$sql."  from_customer_id=$customer_id";	 	  
		}
		
		if((validateForNull($transaction_type_array) && in_array(8,$transaction_type_array)) || !validateForNull($transaction_type_array))
		{
	if($sql!="")
	$sql=$sql." UNION ALL ";	
		$sql=$sql."SELECT edms_ac_credit_note.credit_note_id as id,8 as type,amount  ";
	if($head_type==1 || $head_type==0 || !isset($head_type))
	$sql=$sql." + IF(total_tax IS NOT NULL,total_tax,0) AS amount ";
	$sql=$sql.",from_ledger_id as debit_ledger_id,to_ledger_id as credit_ledger_id,to_customer_id as credit_customer_id,NULL as debit_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified,1 as other_ledger_id
			  FROM edms_ac_credit_note LEFT JOIN (SELECT f.credit_note_id, SUM( tax_amount ) AS total_tax, e.tax_group_id, e.in_out
FROM edms_ac_credit_note_tax f, edms_tax_grp e, edms_rel_tax_grp_tax g
WHERE f.tax_id = g.tax_id
AND g.tax_group_id = e.tax_group_id
GROUP BY f.credit_note_id
)h ON edms_ac_credit_note.credit_note_id = h.credit_note_id WHERE ";	   
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(checkForNumeric($id))  	  
	$sql=$sql." ( to_ledger_id=$id OR from_ledger_id = $id) ";
	else if(checkForNumeric($customer_id))
	$sql=$sql."  to_customer_id=$customer_id";	 	  
		}
	$sql=$sql." ORDER BY trans_date";	
	

		$result = dbQuery($sql);
		
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
	return false;	
}
else if($head_type==2) // tax_leagder
{
	
	return 	getAllTransactionsForLedgerId($or_id,$transaction_type_array,$from,$to);
	}
}	

		
function getAllTransactionsForLedgerId($id,$transaction_type_array=NULL,$from=NULL,$to=NULL)
{
	

	if((validateForNull($transaction_type_array) && in_array(1,$transaction_type_array)) || !validateForNull($transaction_type_array))
	$payments=getPaymentsForLedgerIdBetweenDates($id,$from,$to);
	
	
	if((validateForNull($transaction_type_array) && in_array(2,$transaction_type_array)) || !validateForNull($transaction_type_array))
	{
	$receipts=getReceiptsForLedgerIdBetweenDates($id,$from,$to);
	}
	
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
		$contras=getContrasForLedgerIdBetweenDates($id,$from,$to);
		if(!is_array($payments)) $payments=array();
		if(!is_array($receipts)) $receipts=array();
		if(!is_array($contras)) $contras=array();
		$returnArray=array_merge($payments,$receipts,$contras);
		uasort($returnArray,'TransDateComparator');
		
		return array($returnArray,$head_type);
		}
	else
	{
		if((validateForNull($transaction_type_array) && in_array(3,$transaction_type_array)) || !validateForNull($transaction_type_array))
		$jvs=getJVsForLedgerIdBetweenDates($id,$from,$to);
		
		if((validateForNull($transaction_type_array) && in_array(5,$transaction_type_array)) || !validateForNull($transaction_type_array))
		{
		
	if($head_type!=2)		
	$purchases=getPurchasesForLedgerIdBetweenDates($id,$from,$to);
	else
	$purchase_tax = getPurchasesTaxesForLedgerIdBetweenDates($id,$from,$to);
	
		}
			
		
		if((validateForNull($transaction_type_array) && in_array(6,$transaction_type_array)) || !validateForNull($transaction_type_array))
		{
		if($head_type!=2)		
		$sales=getSalesForLedgerIdBetweenDates($id,$from,$to);
		else
		$sales_tax=getSalesTaxesForLedgerIdBetweenDates($id,$from,$to);
		}
	
		
		
		if((validateForNull($transaction_type_array) && in_array(7,$transaction_type_array)) || !validateForNull($transaction_type_array))
		{
		if($head_type!=2)		
		$debit_notes=getDebitNotesForLedgerIdBetweenDates($id,$from,$to);
		else
		$debit_note_tax = getDebitNotesTaxesForLedgerIdBetweenDates($id,$from,$to);
		}
		
		if((validateForNull($transaction_type_array) && in_array(8,$transaction_type_array)) || !validateForNull($transaction_type_array))
		{
		if($head_type!=2)		
		$credit_notes=getCreditNotesForLedgerIdBetweenDates($id,$from,$to);
		else
		$credit_note_tax = getCreditNoteTaxesForLedgerIdBetweenDates($id,$from,$to);
		}
		
		if($head_type!=2)	
		{
		
		if(!is_array($payments)) $payments=array();
		if(!is_array($receipts)) $receipts=array();
		if(!is_array($jvs)) $jvs=array();
		if(!is_array($purchases)) $purchases=array();
		if(!is_array($sales)) $sales=array();
		if(!is_array($debit_notes)) $debit_notes=array();
		if(!is_array($credit_notes)) $credit_notes=array();
		$returnArray=array_merge($payments,$receipts,$jvs,$purchases,$sales,$debit_notes,$credit_notes);
		uasort($returnArray,'TransDateComparator');
		}
		else
		{
		
		if(!is_array($payments)) $payments=array();
		if(!is_array($receipts)) $receipts=array();
		if(!is_array($jvs)) $jvs=array();
		if(!is_array($purchase_tax)) $purchase_tax=array();
		if(!is_array($sales_tax)) $sales_tax=array();
		if(!is_array($debit_note_tax)) $debit_note_tax=array();
		if(!is_array($credit_note_tax)) $credit_note_tax=array();
		$returnArray=array_merge($payments,$receipts,$jvs,$purchase_tax,$sales_tax,$debit_note_tax,$credit_note_tax);
		
		uasort($returnArray,'TransDateComparator');
		
		}
		return array($returnArray,$head_type);
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
		$purchases = getPurchasesForLedgerIdForMonth($id,$month,$year,$from,$to);
		$sales = getSalesForLedgerIdForMonth($id,$month,$year,$from,$to);
		$debit_notes = getDebitNotesForLedgerIdForMonth($id,$month,$year,$from,$to);
		$credit_notes = getCreditNotesForLedgerIdForMonth($id,$month,$year,$from,$to);
		if(!is_array($payments)) $payments=array();
		
		if(!is_array($receipts)) $receipts=array();
		if(!is_array($contras)) $contras=array();
		if(!is_array($purchases)) $purchases=array();
		if(!is_array($sales)) $sales=array();
		if(!is_array($debit_notes)) $debit_notes=array();
		if(!is_array($credit_notes)) $credit_notes=array();
		$returnArray=array_merge($payments,$receipts,$contras,$purchases,$sales,$debit_notes,$credit_notes);
	
		uasort($returnArray,'TransDateComparator');
		
		return array($returnArray,$head_type);
		}
	else if($head_type==2)
	{
		if((validateForNull($transaction_type_array) && in_array(3,$transaction_type_array)) || !validateForNull($transaction_type_array))
		$jvs=getJVsForLedgerIdForMonth($id,$month,$year,$from,$to);
		$purchases = getPurchasesTaxesForLedgerIdForMonth($id,$month,$year,$from,$to);
		$sales = getSalesTaxesForLedgerIdForMonth($id,$month,$year,$from,$to);
		$debit_notes = getDebitNotesTaxesForLedgerIdForMonth($id,$month,$year,$from,$to);
		$credit_notes = getCreditNoteTaxesForLedgerIdForMonth($id,$month,$year,$from,$to);
		
		if(!is_array($payments)) $payments=array();
		if(!is_array($receipts)) $receipts=array();
		if(!is_array($contras)) $contras=array();
		if(!is_array($purchases)) $purchases=array();
		if(!is_array($sales)) $sales=array();
		if(!is_array($debit_notes)) $debit_notes=array();
		if(!is_array($credit_notes)) $credit_notes=array();
		$returnArray=array_merge($payments,$receipts,$jvs,$purchases,$sales,$debit_notes,$credit_notes);
		
		uasort($returnArray,'TransDateComparator');
		return array($returnArray,$head_type);
	}
	else 
	{
		if((validateForNull($transaction_type_array) && in_array(3,$transaction_type_array)) || !validateForNull($transaction_type_array))
		
		$jvs=getJVsForLedgerIdForMonth($id,$month,$year,$from,$to);
		
		$purchases = getPurchasesForLedgerIdForMonth($id,$month,$year,$from,$to);
		$sales = getSalesForLedgerIdForMonth($id,$month,$year,$from,$to);
		$debit_notes = getDebitNotesForLedgerIdForMonth($id,$month,$year,$from,$to);
		$credit_notes = getCreditNotesForLedgerIdForMonth($id,$month,$year,$from,$to);
		
		if(!is_array($payments)) $payments=array();
		if(!is_array($receipts)) $receipts=array();
		if(!is_array($contras)) $contras=array();
		if(!is_array($purchases)) $purchases=array();
		if(!is_array($sales)) $sales=array();
		if(!is_array($debit_notes)) $debit_notes=array();
		if(!is_array($credit_notes)) $credit_notes=array();
		$returnArray=array_merge($payments,$receipts,$jvs,$purchases,$sales,$debit_notes,$credit_notes);
		
		uasort($returnArray,'TransDateComparator');
		return array($returnArray,$head_type);
	}
	return false;	
		
	}	
	
function getLatestTransactionDateForLedgerId($id)
{
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
		
	$sql="  SELECT MAX(trans_date)
			  FROM edms_ac_payment WHERE ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id ";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer GROUP BY from_customer_id";	
	
	
	$sql=$sql." UNION ALL
			   SELECT MAX(trans_date)
			  FROM edms_ac_receipt WHERE ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$from_customer GROUP BY to_customer_id";	
	
	
				$sql=$sql." UNION ALL ";
				$sql=$sql." SELECT MAX(trans_date)
					  FROM edms_ac_jv, edms_ac_jv_cd WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";
			if(checkForNumeric($from_ledger))  
			$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";
			else if(checkForNumeric($from_customer))  
			$sql=$sql." from_customer_id=$from_customer GROUP BY from_customer_id";	  	
			$sql=$sql."
			 UNION ALL
			SELECT MAX(trans_date)
					  FROM edms_ac_jv , edms_ac_jv_cd WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";
			if(checkForNumeric($from_ledger))  
			$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id ";
			else if(checkForNumeric($from_customer)) 
			$sql=$sql." to_customer_id=$from_customer GROUP BY to_customer_id ";
			
			$sql=$sql." UNION ALL
					  SELECT MAX(trans_date)
					  FROM edms_ac_purchase WHERE ";	  
			if($head_type==3)		  
			$sql=$sql." to_ledger_id = $from_ledger GROUP BY to_ledger_id ";
			else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)
			$sql=$sql." from_ledger_id = $from_ledger GROUP BY from_ledger_id ";
			else
			$sql=$sql." from_customer_id = $from_customer GROUP BY from_customer_id ";
		
	
		
			$sql=$sql." UNION ALL
					  SELECT ";
					  
			$sql=$sql."  MAX(trans_date)
					  FROM edms_ac_sales WHERE ";	  
			if($head_type==4)		  
			$sql=$sql." from_ledger_id = $from_ledger GROUP BY from_ledger_id ";
			else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)
			$sql=$sql." to_ledger_id = $from_ledger GROUP BY to_ledger_id ";
			else
			$sql=$sql." to_customer_id = $from_customer GROUP BY to_customer_id ";
		
			$sql=$sql." UNION ALL
					  SELECT ";	  
			$sql=$sql."MAX(trans_date)
					  FROM edms_ac_debit_note WHERE ";	  
			if($head_type==3)		  
			$sql=$sql." to_ledger_id = $from_ledger GROUP BY to_ledger_id ";
			else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)
			$sql=$sql." from_ledger_id = $from_ledger GROUP BY from_ledger_id ";
			else
			$sql=$sql." from_customer_id = $from_customer GROUP BY from_customer_id ";
		
			$sql=$sql." UNION ALL
					  SELECT ";
				  
			$sql=$sql."MAX(trans_date)
					  FROM edms_ac_credit_note WHERE ";
			if($head_type==4)		  
			$sql=$sql." from_ledger_id = $from_ledger GROUP BY from_ledger_id ";
			else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)
			$sql=$sql." to_ledger_id = $from_ledger GROUP BY to_ledger_id ";
			else
			$sql=$sql." to_customer_id = $from_customer GROUP BY to_customer_id ";
			
			if(isset($head_type) && $head_type==0)
			{
				$sql=$sql." UNION ALL SELECT MAX(trans_date)
			  FROM edms_ac_contra WHERE ";
			if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
			$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id"; 
			$sql=$sql." UNION ALL SELECT  MAX(trans_date)
					  FROM edms_ac_contra WHERE ";
			if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
			$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id"; 
			}
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	$dates_array = array();
	if(dbNumRows($result)>0)
	{
		if(isset($resultArray[0][0]))
		$dates_array[]=$resultArray[0][0];
		if(isset($resultArray[1][0]))
		$dates_array[]=$resultArray[1][0];
		if(isset($resultArray[2][0]))
		$dates_array[]=$resultArray[2][0];
		if(isset($resultArray[3][0]))
		$dates_array[]=$resultArray[3][0];
		if(isset($resultArray[4][0]))
		$dates_array[]=$resultArray[4][0];
		if(isset($resultArray[5][0]))
		$dates_array[]=$resultArray[5][0];
		if(isset($resultArray[6][0]))
		$dates_array[]=$resultArray[6][0];
		if(isset($resultArray[7][0]))
		$dates_array[]=$resultArray[7][0];
		if(isset($resultArray[8][0]))
		$dates_array[]=$resultArray[8][0];
		if(isset($resultArray[9][0]))
		$dates_array[]=$resultArray[9][0];
		
		return max($dates_array);
	}
	else return getTodaysDate();
}		

/* $sql="SELECT customer_id ,(SELECT SUM(amount) FROM edms_ac_payment WHERE edms_ac_payment.from_customer_id = edms_customer.customer_id GROUP BY from_customer_id) AS  payment_amount, (SELECT SUM(amount) FROM edms_ac_receipt WHERE edms_ac_receipt.to_customer_id = edms_customer.customer_id GROUP BY to_customer_id) AS  receipt_amount, (SELECT SUM(IF(from_customer_id IS NULL,amount,-amount)) FROM edms_ac_jv WHERE (edms_ac_jv.to_customer_id = edms_customer.customer_id OR edms_ac_jv.from_customer_id = edms_customer.customer_id)) AS jv_amount FROM edms_customer";
$result = dbQuery($sql);
$resultArray = dbResultToArray($result);
print_r($resultArray); */

function getOpeningBalanceForLedgerArrayForDate($array_id,$date) // return opening balance on date for id, id should start with L or C
{
	
	$to=getPreviousDate($date); // return d-m-y
	
	$bank_head_id=getBankAccountsHeadId();
	$cash_head_id=getCashHeadId();
	$purchase_head_id = getPurchaseHeadId();
	$sales_head_id = getSalesHeadId();
	$tax_head_id = getTaxHeadId();
	
	$ledger_string = implode(",",$array_id);
	
	$sql="SELECT CONCAT('L',ledger_id) as id, ledger_name as name, head_id, IF(head_id = ".$bank_head_id." OR head_id =  ".$cash_head_id." , 0 , IF( head_id = ".$tax_head_id." , 2 , IF( head_id = ".$purchase_head_id." , 3 ,  IF(head_id = ".$sales_head_id." , 4 , 1)))) as head_type ,  IF(opening_cd=0, opening_balance , -opening_balance) as opening_balance,
	(SELECT IF(head_id = $cash_head_id OR head_id = $bank_head_id , -SUM(amount) , SUM(amount) ) FROM edms_ac_payment WHERE  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (to_ledger_id = ledger_id OR from_ledger_id = ledger_id) GROUP BY CASE WHEN head_id = $cash_head_id OR head_id = $bank_head_id THEN to_ledger_id ELSE from_ledger_id END )  as payment_amount,
	(SELECT IF(head_id != $cash_head_id AND head_id != $bank_head_id AND head_id != $tax_head_id AND head_id != $purchase_head_id AND head_id != $sales_head_id, -SUM(amount) , SUM(amount) ) FROM edms_ac_receipt WHERE  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (to_ledger_id = ledger_id OR from_ledger_id = ledger_id) GROUP BY CASE WHEN head_id = $cash_head_id OR head_id = $bank_head_id THEN from_ledger_id ELSE to_ledger_id END  ) as receipt_amount,
	(SELECT IF(head_id != $cash_head_id AND head_id != $bank_head_id, -SUM(edms_ac_jv_cd.amount) , 0 ) FROM edms_ac_jv_cd, edms_ac_jv WHERE   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id) AND from_ledger_id = ledger_id AND from_customer_id IS NULL AND to_ledger_id IS NULL AND to_customer_id IS NULL GROUP BY from_ledger_id) as credit_jv_amount,
	(SELECT IF(head_id != $cash_head_id AND head_id != $bank_head_id, SUM(edms_ac_jv_cd.amount) , 0 ) FROM edms_ac_jv_cd, edms_ac_jv WHERE   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id) AND to_ledger_id = ledger_id GROUP BY to_ledger_id)  as debit_jv_amount,
	(SELECT IF(head_id = $cash_head_id OR head_id = $bank_head_id, -SUM(amount) , 0 ) FROM edms_ac_contra WHERE   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql."  from_ledger_id = ledger_id GROUP BY from_ledger_id)  as credit_contra_amount,
	(SELECT IF(head_id = $cash_head_id OR head_id = $bank_head_id, SUM(amount) , 0 ) FROM  edms_ac_contra WHERE   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." to_ledger_id = ledger_id GROUP BY to_ledger_id) as debit_contra_amount,
	(SELECT IF(head_id != $purchase_head_id, -SUM(amount) , SUM(amount) ) FROM  edms_ac_purchase WHERE   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (to_ledger_id = ledger_id OR from_ledger_id  = ledger_id) GROUP BY CASE WHEN head_id = $purchase_head_id THEN to_ledger_id ELSE from_ledger_id END) as purchase_amount,
	(SELECT IF(head_id = $sales_head_id, -SUM(amount) , SUM(amount)) FROM  edms_ac_sales WHERE   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (to_ledger_id = ledger_id OR from_ledger_id  = ledger_id) GROUP BY CASE WHEN head_id = $sales_head_id THEN from_ledger_id ELSE to_ledger_id END) as sales_amount,
	(SELECT IF(head_id = $purchase_head_id, -SUM(amount) , SUM(amount) ) FROM  edms_ac_debit_note WHERE   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (to_ledger_id = ledger_id OR from_ledger_id  = ledger_id) GROUP BY CASE WHEN head_id = $purchase_head_id THEN to_ledger_id ELSE from_ledger_id END) as debit_note_amount,
	(SELECT IF(head_id != $sales_head_id, -SUM(amount) , SUM(amount)) FROM  edms_ac_credit_note WHERE   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (to_ledger_id = ledger_id OR from_ledger_id  = ledger_id) GROUP BY CASE WHEN head_id = $sales_head_id THEN from_ledger_id ELSE to_ledger_id END) as credit_note_amount,
	(SELECT  IF(head_id = $tax_head_id,SUM(-tax_amount),SUM(tax_amount))  FROM edms_ac_sales, edms_ac_sales_tax,edms_tax WHERE edms_ac_sales.sales_id = edms_ac_sales_tax.sales_id AND edms_ac_sales_tax.tax_id = edms_tax.tax_id AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (tax_ledger_id = ledger_id OR to_ledger_id = ledger_id) GROUP BY CASE WHEN head_id = $tax_head_id THEN tax_ledger_id ELSE to_ledger_id END) as sales_tax_amount,
	(SELECT  IF(head_id = $tax_head_id,SUM(-tax_amount),SUM(tax_amount))  FROM edms_ac_debit_note, edms_ac_debit_note_tax,edms_tax WHERE edms_ac_debit_note.debit_note_id = edms_ac_debit_note_tax.debit_note_id AND edms_ac_debit_note_tax.tax_id = edms_tax.tax_id AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (tax_ledger_id = ledger_id OR from_ledger_id = ledger_id) GROUP BY CASE WHEN head_id != $tax_head_id THEN from_ledger_id ELSE tax_ledger_id END) as debit_note_tax_amount,
	(SELECT  IF(head_id = $tax_head_id,SUM(tax_amount),SUM(-tax_amount))  FROM edms_ac_credit_note, edms_ac_credit_note_tax,edms_tax WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_tax.credit_note_id AND edms_ac_credit_note_tax.tax_id = edms_tax.tax_id AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (tax_ledger_id = ledger_id OR to_ledger_id = ledger_id) GROUP BY CASE WHEN head_id != $tax_head_id THEN to_ledger_id ELSE tax_ledger_id END) as credit_note_tax_amount,
	(SELECT  IF(head_id = $tax_head_id,SUM(tax_amount),SUM(-tax_amount)) FROM edms_ac_purchase, edms_ac_purchase_tax,edms_tax WHERE edms_ac_purchase.purchase_id = edms_ac_purchase_tax.purchase_id AND edms_ac_purchase_tax.tax_id = edms_tax.tax_id AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (tax_ledger_id = ledger_id OR from_ledger_id = ledger_id) GROUP BY CASE WHEN head_id != $tax_head_id THEN from_ledger_id ELSE tax_ledger_id END) as purchase_tax_amount
	
	 FROM edms_ac_ledgers WHERE ledger_id IN ($ledger_string)";
	

	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);

	return $resultArray;
	
	 
	
	
}


function getOpeningBalanceForCustomerArrayForDate($array_id,$date) // return opening balance on date for id, id should start with L or C
{
	
	$to=getPreviousDate($date); // return d-m-y
	
	$bank_head_id=getBankAccountsHeadId();
	$cash_head_id=getCashHeadId();
	$purchase_head_id = getPurchaseHeadId();
	$sales_head_id = getSalesHeadId();
	$tax_head_id = getTaxHeadId();
	$sundry_debtors_head_id = getSundryDebtorsId();
	$ledger_string = implode(",",$array_id);
	
	$sql="SELECT CONCAT('C',customer_id) as id, customer_name as name, $sundry_debtors_head_id as head_id, 1,  IF(opening_cd=0,opening_balance,-opening_balance) as opening_balance,
	(SELECT  SUM(amount)  FROM edms_ac_payment WHERE  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (from_customer_id = customer_id) GROUP BY   from_customer_id ) as payment_amount,
	(SELECT  -SUM(amount)  FROM edms_ac_receipt WHERE  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (to_customer_id = customer_id) GROUP BY to_customer_id  ) as receipt_amount,
	(SELECT  -SUM(edms_ac_jv_cd.amount) FROM edms_ac_jv_cd, edms_ac_jv WHERE   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id) AND from_customer_id = customer_id GROUP BY from_customer_id) as credit_jv_amount,
	(SELECT  SUM(edms_ac_jv_cd.amount)  FROM edms_ac_jv_cd, edms_ac_jv WHERE   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id) AND to_customer_id = customer_id GROUP BY to_customer_id) as debit_jv_amount,
	(SELECT  -SUM(amount)  FROM  edms_ac_purchase WHERE   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql."  from_customer_id  = customer_id GROUP BY  from_customer_id ) as purchase_amount,
	(SELECT  SUM(amount) FROM  edms_ac_sales WHERE   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (to_customer_id = customer_id ) GROUP BY to_customer_id ) as sales_amount,
	(SELECT  SUM(amount)  FROM  edms_ac_debit_note WHERE   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (from_customer_id  = customer_id) GROUP BY from_customer_id ) as debit_note_amount,
	(SELECT -SUM(amount)  FROM  edms_ac_credit_note WHERE   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (to_customer_id = customer_id ) GROUP BY to_customer_id) as credit_note_amount,
	(SELECT  SUM(tax_amount) FROM edms_ac_sales, edms_ac_sales_tax,edms_tax WHERE edms_ac_sales.sales_id = edms_ac_sales_tax.sales_id AND edms_ac_sales_tax.tax_id = edms_tax.tax_id AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." ( to_customer_id = customer_id) GROUP BY  to_customer_id) as sales_tax_amount,
	(SELECT  SUM(tax_amount) FROM edms_ac_debit_note, edms_ac_debit_note_tax,edms_tax WHERE edms_ac_debit_note.debit_note_id = edms_ac_debit_note_tax.debit_note_id AND edms_ac_debit_note_tax.tax_id = edms_tax.tax_id AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (from_customer_id = customer_id) GROUP BY from_customer_id) as debit_note_tax_amount,
	(SELECT  SUM(-tax_amount) FROM edms_ac_credit_note, edms_ac_credit_note_tax,edms_tax WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_tax.credit_note_id AND edms_ac_credit_note_tax.tax_id = edms_tax.tax_id AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." (to_customer_id = customer_id) GROUP BY to_customer_id) as credit_note_tax_amount,
	(SELECT  SUM(-tax_amount) FROM edms_ac_purchase, edms_ac_purchase_tax,edms_tax WHERE edms_ac_purchase.purchase_id = edms_ac_purchase_tax.purchase_id AND edms_ac_purchase_tax.tax_id = edms_tax.tax_id AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." ( from_customer_id = customer_id) GROUP BY from_customer_id) as purchase_tax_amount
	
	 FROM edms_customer WHERE customer_id IN ($ledger_string)";
	

	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return $resultArray;
	
	 
	
	
}

function getOpeningBalanceForLedgerForDate($id,$date) // return opening balance on date for id, id should start with L or C
{
	
	$to=getPreviousDate($date); // return d-m-y
	
	/* $opening_balance_array=getOpeningBalanceForLedgerCustomer($id);
	
	if($opening_balance_array[1]==1)
	$opening_balance=-$opening_balance_array[0];
	else
	$opening_balance=$opening_balance_array[0];
	 */
	$opening_balance = 0;
	
	if(substr($id, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$id);
		$from_ledger=intval($from_ledger);
		$head_type=getLedgerHeadType($from_ledger);
		$from_customer=false;
		
		$sql="SELECT IF(opening_cd=0,opening_balance,-opening_balance) FROM edms_ac_ledgers WHERE ledger_id = $from_ledger ";
		
		}
	else if(substr($id, 0, 1) == 'C')
	{
		$from_customer=str_replace('C','',$id);
		$from_customer=intval($from_customer);
		$from_ledger=false;
		$sql="SELECT IF(opening_cd=0,opening_balance,-opening_balance) FROM edms_customer WHERE customer_id = $from_customer ";
		}	
	
	if($head_type==0 || $head_type==1 || !isset($head_type)) // only for normal and banking/cash ledger and customer
	{
	
	$sql=$sql." UNION ALL  SELECT ";
	if(isset($head_type) && $head_type==0)
	$sql=$sql."-"; 
	$sql=$sql."sum(amount)
			  FROM edms_ac_payment WHERE ";
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
			  FROM edms_ac_receipt WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$from_customer GROUP BY to_customer_id";	
	
	}
		
	
		if($head_type==1 || $head_type==3 || $head_type==4 || !isset($head_type)) // if head is normal or purchase or sales ledger include jv
		{
				
				$sql=$sql." UNION ALL ";
				$sql=$sql." SELECT  -sum(edms_ac_jv_cd.amount)
					  FROM edms_ac_jv , edms_ac_jv_cd WHERE  ";
			if(isset($to) && validateForNull($to))  
			$sql=$sql."trans_date<='$to'
				  AND ";
			$sql=$sql." edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";	  
			if(checkForNumeric($from_ledger))  
			$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";
			else if(checkForNumeric($from_customer))  
			$sql=$sql." from_customer_id=$from_customer GROUP BY from_customer_id";	  	
			$sql=$sql."
			 UNION ALL
			SELECT  SUM(edms_ac_jv_cd.amount) 
					  FROM edms_ac_jv,edms_ac_jv_cd WHERE ";	  
			if(isset($to) && validateForNull($to))  
			$sql=$sql."trans_date<='$to'
				  AND ";
			$sql=$sql." edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";	  
			if(checkForNumeric($from_ledger))  
			$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id ";
			else if(checkForNumeric($from_customer)) 
			$sql=$sql." to_customer_id=$from_customer GROUP BY to_customer_id ";	
		}
	
	if($head_type!=2 || !isset($head_type)) // if head is not of tax type include purchase and sales
	{
		if($head_type==3 || $head_type==1 ||  $head_type==0 || !isset($head_type)) // if purchase or normal or customer ledger
		{		
			$sql=$sql." UNION ALL
					  SELECT ";
			if(!isset($head_type) || $head_type!=3)		  
			$sql=$sql."-";		  
			$sql=$sql."SUM(amount)
					  FROM edms_ac_purchase WHERE ";	  
			if(isset($to) && validateForNull($to))  
			$sql=$sql."trans_date<='$to'
				  AND ";
			if($head_type==3)		  
			$sql=$sql." to_ledger_id = $from_ledger GROUP BY to_ledger_id ";
			else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))
			$sql=$sql." from_ledger_id = $from_ledger GROUP BY from_ledger_id ";
			else
			$sql=$sql." from_customer_id = $from_customer GROUP BY from_customer_id ";
		}
	
		if($head_type==4 || $head_type==1 ||  $head_type==0 || !isset($head_type)) // if sales or normal or customer ledger
		{	
			$sql=$sql." UNION ALL
					  SELECT ";
			if(isset($head_type) && $head_type==4)			  
			$sql=$sql."-";		  
			$sql=$sql."SUM(amount)
					  FROM edms_ac_sales WHERE ";	  
			if(isset($to) && validateForNull($to))  
			$sql=$sql."trans_date<='$to'
				  AND ";
			if($head_type==4)		  
			$sql=$sql." from_ledger_id = $from_ledger GROUP BY from_ledger_id ";
			else if(isset($head_type) && checkForNumeric($head_type) &&  ($head_type==1 || $head_type==0))
			$sql=$sql." to_ledger_id = $from_ledger GROUP BY to_ledger_id ";
			else
			$sql=$sql." to_customer_id = $from_customer GROUP BY to_customer_id ";
		}
		
		if($head_type==3 || $head_type==1 || !isset($head_type)) // if purchase or normal or customer ledger
		{		
			$sql=$sql." UNION ALL
					  SELECT ";
			if(isset($head_type) && $head_type==3)		  
			$sql=$sql."-";		  
			$sql=$sql."SUM(amount)
					  FROM edms_ac_debit_note WHERE ";	  
			if(isset($to) && validateForNull($to))  
			$sql=$sql."trans_date<='$to'
				  AND ";
			if($head_type==3)		  
			$sql=$sql." to_ledger_id = $from_ledger GROUP BY to_ledger_id ";
			else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)
			$sql=$sql." from_ledger_id = $from_ledger GROUP BY from_ledger_id ";
			else
			$sql=$sql." from_customer_id = $from_customer GROUP BY from_customer_id ";
		}
	
		if($head_type==4 || $head_type==1 || !isset($head_type)) // if sales or normal or customer ledger
		{	
			$sql=$sql." UNION ALL
					  SELECT ";
			if(!isset($head_type) || $head_type!=4)			  
			$sql=$sql."-";		  
			$sql=$sql."SUM(amount)
					  FROM edms_ac_credit_note WHERE ";	  
			if(isset($to) && validateForNull($to))  
			$sql=$sql."trans_date<='$to'
				  AND ";
			if($head_type==4)		  
			$sql=$sql." from_ledger_id = $from_ledger GROUP BY from_ledger_id ";
			else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)
			$sql=$sql." to_ledger_id = $from_ledger GROUP BY to_ledger_id ";
			else
			$sql=$sql." to_customer_id = $from_customer GROUP BY to_customer_id ";
		}
	
	 
	 if($head_type==2 || $head_type==1 || $head_type==0 || !isset($head_type)) // include tax if normal or tax ledger
	 {
	 // taxation purchase
	 if($head_type==1 ||  $head_type==0 || !isset($head_type))
		$sql=$sql." UNION ALL ";
	$sql=$sql."
			  SELECT ";  
	if($head_type==2)			  
	$sql=$sql."-";		  
	$sql=$sql." SUM(-tax_amount)
			  FROM edms_ac_purchase, edms_ac_purchase_tax,edms_tax WHERE
			  edms_ac_purchase.purchase_id = edms_ac_purchase_tax.purchase_id
			  AND edms_ac_purchase_tax.tax_id = edms_tax.tax_id AND
			   ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($head_type==2)
	$sql=$sql." tax_ledger_id = $from_ledger GROUP BY tax_ledger_id ";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))	  	  
	$sql=$sql." from_ledger_id = $from_ledger GROUP BY from_ledger_id ";
	else
	$sql=$sql." from_customer_id = $from_customer GROUP BY from_customer_id ";
	
	 // taxation sales
	$sql=$sql." UNION ALL
			  SELECT ";	
	if($head_type==2)			  
	$sql=$sql."-";			  	  
	$sql=$sql." SUM(tax_amount)
			  FROM edms_ac_sales, edms_ac_sales_tax,edms_tax WHERE
			  edms_ac_sales.sales_id = edms_ac_sales_tax.sales_id
			  AND edms_ac_sales_tax.tax_id = edms_tax.tax_id AND
			   ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($head_type==2)
	$sql=$sql." tax_ledger_id = $from_ledger GROUP BY tax_ledger_id ";
	else if(isset($head_type) && checkForNumeric($head_type) &&  ($head_type==1 || $head_type==0))	   
	$sql=$sql." to_ledger_id = $from_ledger  GROUP BY to_ledger_id ";
	else
	$sql=$sql." to_customer_id = $from_customer GROUP BY to_customer_id ";
	
	 // taxation debit note
	
	$sql=$sql." UNION ALL ";
	$sql=$sql."
			  SELECT ";  
	if($head_type==2)			  
	$sql=$sql."-";		  
	$sql=$sql."SUM(tax_amount)
			  FROM edms_ac_debit_note, edms_ac_debit_note_tax,edms_tax WHERE
			  edms_ac_debit_note.debit_note_id = edms_ac_debit_note_tax.debit_note_id
			  AND edms_ac_debit_note_tax.tax_id = edms_tax.tax_id AND
			   ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($head_type==2)
	$sql=$sql." tax_ledger_id = $from_ledger GROUP BY tax_ledger_id ";
	else if(isset($head_type) && checkForNumeric($head_type) &&  ($head_type==1 || $head_type==0))	  	  
	$sql=$sql." from_ledger_id = $from_ledger GROUP BY from_ledger_id ";
	else
	$sql=$sql." from_customer_id = $from_customer GROUP BY from_customer_id ";
	
	 // taxation sales
	$sql=$sql." UNION ALL
			  SELECT ";	
	if($head_type==2)			  
	$sql=$sql."-";			  	  
	$sql=$sql." SUM(-tax_amount)
			  FROM edms_ac_credit_note, edms_ac_credit_note_tax,edms_tax WHERE
			  edms_ac_credit_note.credit_note_id = edms_ac_credit_note_tax.credit_note_id
			  AND edms_ac_credit_note_tax.tax_id = edms_tax.tax_id AND
			   ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($head_type==2)
	$sql=$sql." tax_ledger_id = $from_ledger GROUP BY tax_ledger_id ";
	else if(isset($head_type) && checkForNumeric($head_type) &&  ($head_type==1 || $head_type==0))	   
	$sql=$sql." to_ledger_id = $from_ledger  GROUP BY to_ledger_id ";
	else
	$sql=$sql." to_customer_id = $from_customer GROUP BY to_customer_id ";
	
	 }
	}
	
	if(!isset($head_type) || $head_type!=0)
	{
		
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
		if(isset($resultArray[4][0]))
		$net_amount=$net_amount+$resultArray[4][0];
		if(isset($resultArray[5][0]))
		$net_amount=$net_amount+$resultArray[5][0];
		if(isset($resultArray[6][0]))
		$net_amount=$net_amount+$resultArray[6][0];
		if(isset($resultArray[7][0]))
		$net_amount=$net_amount+$resultArray[7][0];
		if(isset($resultArray[8][0]))
		$net_amount=$net_amount+$resultArray[8][0];
		if(isset($resultArray[9][0]))
		$net_amount=$net_amount+$resultArray[9][0];
		if(isset($resultArray[10][0]))
		$net_amount=$net_amount+$resultArray[10][0];
		if(isset($resultArray[11][0]))
		$net_amount=$net_amount+$resultArray[11][0];
		if(isset($resultArray[12][0]))
		$net_amount=$net_amount+$resultArray[12][0];
		if(isset($resultArray[13][0]))
		$net_amount=$net_amount+$resultArray[13][0];
		if(isset($resultArray[14][0]))
		$net_amount=$net_amount+$resultArray[14][0];
		}
	
	return $net_amount;
	}
	else if($head_type==0)
	{
		
		$sql=$sql." UNION ALL SELECT -SUM(amount)
			  FROM edms_ac_contra WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id"; 
	
	$sql=$sql." UNION ALL SELECT SUM(amount)
			  FROM edms_ac_contra WHERE ";
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
		if(isset($resultArray[4][0]))
		$net_amount=$net_amount+$resultArray[4][0];
		if(isset($resultArray[5][0]))
		$net_amount=$net_amount+$resultArray[5][0];
		if(isset($resultArray[6][0]))
		$net_amount=$net_amount+$resultArray[6][0];
		if(isset($resultArray[7][0]))
		$net_amount=$net_amount+$resultArray[7][0];
		if(isset($resultArray[8][0]))
		$net_amount=$net_amount+$resultArray[8][0];
		if(isset($resultArray[9][0]))
		$net_amount=$net_amount+$resultArray[9][0];
		if(isset($resultArray[10][0]))
		$net_amount=$net_amount+$resultArray[10][0];
		if(isset($resultArray[11][0]))
		$net_amount=$net_amount+$resultArray[11][0];
		if(isset($resultArray[12][0]))
		$net_amount=$net_amount+$resultArray[12][0];
		if(isset($resultArray[13][0]))
		$net_amount=$net_amount+$resultArray[13][0];
		if(isset($resultArray[14][0]))
		$net_amount=$net_amount+$resultArray[14][0];
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
		
	if($head_type==0 || $head_type==1 || !isset($head_type)) // if purchase or normal or customer ledger
	{
	$sql="  SELECT ";
	if(isset($head_type) && $head_type==0)
	$sql=$sql."-"; 
	$sql=$sql."sum(amount)
			  FROM edms_ac_payment WHERE ";
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
			  FROM edms_ac_receipt WHERE ";	  
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
	}
	
	if($head_type==3) // if purchase or normal or customer ledger
		{		
			$sql=$sql."
					  SELECT "; 
			$sql=$sql."SUM(amount)
					  FROM edms_ac_purchase WHERE ";	  
			if(isset($to) && validateForNull($to))  
			$sql=$sql."trans_date<='$to'
				  AND ";
			if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND ";	 	  
			if($head_type==3)		  
			$sql=$sql." to_ledger_id = $from_ledger GROUP BY to_ledger_id ";
			else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)
			$sql=$sql." from_ledger_id = $from_ledger GROUP BY from_ledger_id ";
			else
			$sql=$sql." from_customer_id = $from_customer GROUP BY from_customer_id ";
			
			$sql=$sql." UNION ALL
					  SELECT "; 
			$sql=$sql."-SUM(amount)
					  FROM edms_ac_debit_note WHERE ";	  
			if(isset($to) && validateForNull($to))  
			$sql=$sql."trans_date<='$to'
				  AND ";
			if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND ";	 	  
			if($head_type==3)		  
			$sql=$sql." to_ledger_id = $from_ledger GROUP BY to_ledger_id ";
			else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)
			$sql=$sql." from_ledger_id = $from_ledger GROUP BY from_ledger_id ";
			else
			$sql=$sql." from_customer_id = $from_customer GROUP BY from_customer_id ";
		}
	
		if($head_type==4) // if sales or normal or customer ledger
		{	
			$sql=$sql."
					  SELECT ";	  
			$sql=$sql."-";		  
			$sql=$sql."SUM(amount)
					  FROM edms_ac_sales WHERE ";	  
			if(isset($to) && validateForNull($to))  
			$sql=$sql."trans_date<='$to'
				  AND ";
			if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND ";	 	  
			if($head_type==4)		  
			$sql=$sql." from_ledger_id = $from_ledger GROUP BY from_ledger_id ";
			else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)
			$sql=$sql." to_ledger_id = $from_ledger GROUP BY to_ledger_id ";
			else
			$sql=$sql." to_customer_id = $from_customer GROUP BY to_customer_id ";
			
			$sql=$sql." UNION ALL
					  SELECT ";	  		  
			$sql=$sql."SUM(amount)
					  FROM edms_ac_credit_note WHERE ";	  
			if(isset($to) && validateForNull($to))  
			$sql=$sql."trans_date<='$to'
				  AND ";
			if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND ";	 	  
			if($head_type==4)		  
			$sql=$sql." from_ledger_id = $from_ledger GROUP BY from_ledger_id ";
			else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)
			$sql=$sql." to_ledger_id = $from_ledger GROUP BY to_ledger_id ";
			else
			$sql=$sql." to_customer_id = $from_customer GROUP BY to_customer_id ";
		}
		if(!isset($head_type) || $head_type==1 || $head_type==3 || $head_type==4)
		{
			
			$sql=$sql." UNION ALL SELECT  -sum(edms_ac_jv_cd.amount)
				  FROM edms_ac_jv,edms_ac_jv_cd WHERE ";
		if(isset($to) && validateForNull($to))  
		$sql=$sql."trans_date<='$to'
			  AND ";
		if(isset($from) && validateForNull($from))  
		$sql=$sql."trans_date>='$from'
			  AND ";	
		$sql=$sql." edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";	    
		if(checkForNumeric($from_ledger) && ($head_type==1 || $head_type==3 || $head_type==4))  
		$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";
		else if(checkForNumeric($from_customer))  
		$sql=$sql." from_customer_id=$from_customer GROUP BY from_customer_id";	  	
		
		
		
		$sql=$sql."
		 UNION ALL
		SELECT  SUM(edms_ac_jv_cd.amount) 
				  FROM edms_ac_jv,edms_ac_jv_cd WHERE ";	  
		if(isset($to) && validateForNull($to))  
		$sql=$sql."trans_date<='$to'
			  AND ";
		if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND ";	 	
		  $sql=$sql." edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";  
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
			if(isset($resultArray[4][0]))
			$net_amount=$net_amount+$resultArray[4][0];
			if(isset($resultArray[5][0]))
			$net_amount=$net_amount+$resultArray[5][0];
			}
		
		return $net_amount;
		}
		else if($head_type==0)
		{
			
			$sql=$sql." UNION ALL SELECT -SUM(amount)
				  FROM edms_ac_contra WHERE ";
		if(isset($to) && validateForNull($to))  
		$sql=$sql."trans_date<='$to'
			  AND ";
		if(isset($from) && validateForNull($from))  
		$sql=$sql."trans_date>='$from'
			  AND ";	  
		if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
		$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id"; 
		
		$sql=$sql." UNION ALL SELECT SUM(amount)
				  FROM edms_ac_contra WHERE ";
		if(isset($to) && validateForNull($to))  
		$sql=$sql."trans_date<='$to'
			  AND ";
		if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
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
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	$cash_head_id=getCashHeadId();
	$purchase_head_id = getPurchaseHeadId();
	$sales_head_id = getSalesHeadId();
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
			  FROM edms_ac_payment WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND "; 
	if(($head_id==$cash_head_id || $head_id==$bank_head_id))  	  
	$sql=$sql." to_ledger_id IN ($ids_string)  ";
	else
	$sql=$sql." from_ledger_id  IN ($ids_string)  ";
	
	
	$sql=$sql." UNION ALL
			  SELECT ";
	if(($head_id!=$cash_head_id &&  $head_id!=$bank_head_id))
	$sql=$sql."-";		  
	$sql=$sql."SUM(amount)
			  FROM edms_ac_receipt WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND "; 	  
	if(($head_id==$cash_head_id || $head_id==$bank_head_id))  		  
	$sql=$sql." from_ledger_id IN ($ids_string)  ";
	else 
	$sql=$sql." to_ledger_id IN ($ids_string)  ";

  if(($head_id==$cash_head_id || $head_id==$bank_head_id)) 
	{
		
				$sql=$sql." UNION ALL SELECT -SUM(amount)
					  FROM edms_ac_contra WHERE ";
			if(isset($to) && validateForNull($to))  
			$sql=$sql."trans_date<='$to'
				  AND ";
			if(isset($from) && validateForNull($from))  
			$sql=$sql."trans_date>='$from'
				  AND "; 	  
			if(validateForNull($ids_string))  	  
			$sql=$sql." from_ledger_id IN ($ids_string)  "; 
			
			$sql=$sql." UNION ALL SELECT SUM(amount)
					  FROM edms_ac_contra WHERE ";
			if(isset($to) && validateForNull($to))  
			$sql=$sql."trans_date<='$to'
				  AND ";
			if(isset($from) && validateForNull($from))  
			$sql=$sql."trans_date>='$from'
				  AND "; 	  
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
	else // if not cash or bank account
	{
		
		$sql=$sql." UNION ALL SELECT -SUM(edms_ac_jv_cd.amount) 
			  FROM edms_ac_jv, edms_ac_jv_cd WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND "; 	  
	$sql=$sql." edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";	  	  
	if(validateForNull($ids_string))  
	$sql=$sql." from_ledger_id IN ($ids_string) ";	  	
	$sql=$sql."
	 UNION ALL
	SELECT SUM(edms_ac_jv_cd.amount) as debit_amount
			  FROM edms_ac_jv,edms_ac_jv_cd WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND "; 	
	$sql=$sql." edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";	    
	if(validateForNull($ids_string))  
	$sql=$sql." to_ledger_id IN ($ids_string)  ";
	
	 if(($head_id==$sales_head_id))
	 {
	// sales
	$sql=$sql."
	 UNION ALL
	SELECT -SUM(amount) as debit_amount
			  FROM edms_ac_sales WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND "; 
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND "; 	  	  
	$sql=$sql." from_ledger_id IN ($ids_string)  ";	
	
// credit note
	$sql=$sql."
	 UNION ALL
	SELECT SUM(amount) as debit_amount
			  FROM edms_ac_credit_note WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND "; 
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND "; 	  	  
	$sql=$sql." from_ledger_id IN ($ids_string)  ";	
	
	 }
	 
	  if(($head_id==$purchase_head_id))
	 {
	// purchase
	$sql=$sql."
	 UNION ALL
	SELECT SUM(amount) as debit_amount
			  FROM edms_ac_purchase WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND "; 	  	  
	$sql=$sql." to_ledger_id IN ($ids_string)  ";	
	
	// debit_note
	$sql=$sql."
	 UNION ALL
	SELECT -SUM(amount) as debit_amount
			  FROM edms_ac_debit_note WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($from) && validateForNull($from))  
	$sql=$sql."trans_date>='$from'
		  AND "; 	  	  
	$sql=$sql." to_ledger_id IN ($ids_string)  ";	
	
	}
	 

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
		if(isset($resultArray[4][0]))
		$net_amount=$net_amount+$resultArray[4][0];
		if(isset($resultArray[5][0]))
		$net_amount=$net_amount+$resultArray[5][0];
		if(isset($resultArray[6][0]))
		$net_amount=$net_amount+$resultArray[6][0];
		if(isset($resultArray[7][0]))
		$net_amount=$net_amount+$resultArray[7][0];
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
	$purchase_head_id = getPurchaseHeadId();
	$sales_head_id = getSalesHeadId();
	
	$ledger_id_array=getLedgerIdsForHeadId($head_id);
	
	$tax_head_id = getTaxHeadId();
	
	if(empty($ledger_id_array))
	return 0;
	
	
	$opening_balance=getOpeningBalanceForLedgerArray($ledger_id_array); // net opening balance for $id array of ledgers
	
	
	$ids_string=implode(',',$ledger_id_array);
	
	$sql="SELECT ";
	if(($head_id==$cash_head_id || $head_id==$bank_head_id))
	$sql=$sql."-";
	$sql=$sql."SUM(amount) as amount
			  FROM edms_ac_payment WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(($head_id==$cash_head_id || $head_id==$bank_head_id))  	  
	$sql=$sql." to_ledger_id IN ($ids_string)  ";
	else
	$sql=$sql." from_ledger_id  IN ($ids_string)  ";
	
	
	$sql=$sql." UNION ALL
			  SELECT ";
	if(($head_id!=$cash_head_id &&  $head_id!=$bank_head_id))
	$sql=$sql."-";		  
	$sql=$sql."SUM(amount)
			  FROM edms_ac_receipt WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(($head_id==$cash_head_id || $head_id==$bank_head_id))  		  
	$sql=$sql." from_ledger_id IN ($ids_string)  ";
	else 
	$sql=$sql." to_ledger_id IN ($ids_string)  ";

  if(($head_id==$cash_head_id || $head_id==$bank_head_id)) 
	{
		
		$sql=$sql." UNION ALL SELECT -SUM(amount)
			  FROM edms_ac_contra WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($ids_string))  	  
	$sql=$sql." from_ledger_id IN ($ids_string)  "; 
	
	$sql=$sql." UNION ALL SELECT SUM(amount)
			  FROM edms_ac_contra WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($ids_string))  
	$sql=$sql." to_ledger_id IN ($ids_string)  ";  	
	}
/*	$result=dbQuery($sql);
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
	} */
	 else  // if not cash or bank account
	{
		
		$sql=$sql." UNION ALL SELECT -SUM(edms_ac_jv_cd.amount) 
			  FROM edms_ac_jv,edms_ac_jv_cd WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";	  
	if(validateForNull($ids_string))  
	$sql=$sql." from_ledger_id IN ($ids_string)  ";	  	
	$sql=$sql."
	 UNION ALL
	SELECT SUM(edms_ac_jv_cd.amount) as debit_amount
			  FROM edms_ac_jv,edms_ac_jv_cd WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";	  
	if(validateForNull($ids_string))  
	$sql=$sql." to_ledger_id IN ($ids_string) ";
	}
	
	$sql=$sql." UNION ALL
			  SELECT ";
	if($head_id!=$purchase_head_id)		  
	$sql=$sql."-";		  
	$sql=$sql."SUM(amount)
			  FROM edms_ac_purchase WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($head_id==$purchase_head_id)		  
	$sql=$sql." to_ledger_id IN ($ids_string)  ";
	else
	$sql=$sql." from_ledger_id IN ($ids_string)  ";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	$sql=$sql." UNION ALL
			  SELECT ";
    if($head_id==$sales_head_id)			  
	$sql=$sql."-";		  
	$sql=$sql."SUM(amount)
			  FROM edms_ac_sales WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($head_id==$sales_head_id)		  
	$sql=$sql." from_ledger_id IN ($ids_string)  ";
	else
	$sql=$sql." to_ledger_id IN ($ids_string) ";
	
	 
	 // taxation purchase
	$sql=$sql." UNION ALL
			  SELECT ";  
	if($head_id==$tax_head_id)
	$sql=$sql."-";				  
	$sql=$sql." SUM(-tax_amount)
			  FROM edms_ac_purchase, edms_ac_purchase_tax,edms_tax WHERE
			  edms_ac_purchase.purchase_id = edms_ac_purchase_tax.purchase_id
			  AND edms_ac_purchase_tax.tax_id = edms_tax.tax_id AND
			   ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($head_id==$tax_head_id)
	$sql=$sql." tax_ledger_id IN ($ids_string)  ";
	else	  	  
	$sql=$sql." from_ledger_id IN ($ids_string)  ";
	
	 // taxation sales
	$sql=$sql." UNION ALL
			  SELECT ";	
	if($head_id==$tax_head_id)
	$sql=$sql."-";			  	  
	$sql=$sql." SUM(tax_amount)
			  FROM edms_ac_sales, edms_ac_sales_tax,edms_tax WHERE
			  edms_ac_sales.sales_id = edms_ac_sales_tax.sales_id
			  AND edms_ac_sales_tax.tax_id = edms_tax.tax_id AND
			   ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($head_id==$tax_head_id)
	$sql=$sql." tax_ledger_id IN ($ids_string)  ";
	else	  
	$sql=$sql." to_ledger_id IN ($ids_string) ";
	
	$sql=$sql." UNION ALL
			  SELECT ";
	if($head_id==$purchase_head_id)		  
	$sql=$sql."-";		  
	$sql=$sql."SUM(amount)
			  FROM edms_ac_debit_note WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($head_id==$purchase_head_id)		  
	$sql=$sql." to_ledger_id IN ($ids_string)  ";
	else
	$sql=$sql." from_ledger_id IN ($ids_string)  ";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	$sql=$sql." UNION ALL
			  SELECT ";
    if($head_id!=$sales_head_id)			  
	$sql=$sql."-";		  
	$sql=$sql."SUM(amount)
			  FROM edms_ac_credit_note WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($head_id==$sales_head_id)		  
	$sql=$sql." from_ledger_id IN ($ids_string)  ";
	else
	$sql=$sql." to_ledger_id IN ($ids_string) ";
	
	 
	 // taxation debit_note
	$sql=$sql." UNION ALL
			  SELECT ";  
	if($head_id!=$tax_head_id)
	$sql=$sql."-";				  
	$sql=$sql." SUM(tax_amount)
			  FROM edms_ac_debit_note, edms_ac_debit_note_tax,edms_tax WHERE
			  edms_ac_debit_note.debit_note_id = edms_ac_debit_note_tax.debit_note_id
			  AND edms_ac_debit_note_tax.tax_id = edms_tax.tax_id AND
			   ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($head_id==$tax_head_id)
	$sql=$sql." tax_ledger_id IN ($ids_string)  ";
	else	  	  
	$sql=$sql." from_ledger_id IN ($ids_string)  ";
	
	 // taxation credit note
	$sql=$sql." UNION ALL
			  SELECT ";	
	if($head_id!=$tax_head_id)
	$sql=$sql."-";			  	  
	$sql=$sql." SUM(-tax_amount)
			  FROM edms_ac_credit_note, edms_ac_credit_note_tax,edms_tax WHERE
			  edms_ac_credit_note.credit_note_id = edms_ac_credit_note_tax.credit_note_id
			  AND edms_ac_credit_note_tax.tax_id = edms_tax.tax_id AND
			   ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($head_id==$tax_head_id)
	$sql=$sql." tax_ledger_id IN ($ids_string)  ";
	else	  
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
		if(isset($resultArray[4][0]))
		$net_amount=$net_amount+$resultArray[4][0];
		if(isset($resultArray[5][0]))
		$net_amount=$net_amount+$resultArray[5][0];
		if(isset($resultArray[6][0]))
		$net_amount=$net_amount+$resultArray[6][0];
		if(isset($resultArray[7][0]))
		$net_amount=$net_amount+$resultArray[7][0];
		if(isset($resultArray[8][0]))
		$net_amount=$net_amount+$resultArray[8][0];
		if(isset($resultArray[9][0]))
		$net_amount=$net_amount+$resultArray[9][0];
		if(isset($resultArray[10][0]))
		$net_amount=$net_amount+$resultArray[10][0];
		if(isset($resultArray[11][0]))
		$net_amount=$net_amount+$resultArray[11][0];
		if(isset($resultArray[12][0]))
		$net_amount=$net_amount+$resultArray[12][0];
		if(isset($resultArray[13][0]))
		$net_amount=$net_amount+$resultArray[13][0];
		
		}
		
	return array($net_amount);
	
	
	
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
			  FROM edms_ac_payment WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($ids_string))  	  
	$sql=$sql." from_customer_id IN ($ids_string) ";	
	
	
	$sql=$sql." UNION ALL
			  SELECT -SUM(amount)
			  FROM edms_ac_receipt WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";	
	if(validateForNull($ids_string))  	  	  
	$sql=$sql." to_customer_id IN ($ids_string) ";	
	
 
		
		$sql=$sql." UNION ALL SELECT -SUM(edms_ac_jv_cd.amount) 
			  FROM edms_ac_jv,edms_ac_jv_cd WHERE ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";	  
	if(validateForNull($ids_string))  
	$sql=$sql." from_customer_id IN ($ids_string) ";	  	
	
	$sql=$sql."
	 UNION ALL
	SELECT SUM(edms_ac_jv_cd.amount) as debit_amount
			  FROM edms_ac_jv,edms_ac_jv_cd WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";	  
	if(validateForNull($ids_string))  
	$sql=$sql." to_customer_id IN ($ids_string) ";	
	
	// sales
	$sql=$sql."
	 UNION ALL
	SELECT SUM(amount) as debit_amount
			  FROM edms_ac_sales WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($ids_string))  
	$sql=$sql." to_customer_id IN ($ids_string) ";	
	
	$sql=$sql." UNION ALL
			  SELECT ";  
	$sql=$sql." -SUM(amount)
			  FROM edms_ac_purchase WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." from_customer_id IN ($ids_string) ";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	 
	 // taxation purchase
	$sql=$sql." UNION ALL
			  SELECT ";  	  
	$sql=$sql." SUM(-tax_amount)
			  FROM edms_ac_purchase, edms_ac_purchase_tax,edms_tax WHERE
			  edms_ac_purchase.purchase_id = edms_ac_purchase_tax.purchase_id
			  AND edms_ac_purchase_tax.tax_id = edms_tax.tax_id AND
			   ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." from_customer_id IN ($ids_string) ";
	
	 // taxation sales
	$sql=$sql." UNION ALL
			  SELECT ";			  	  
	$sql=$sql." SUM(tax_amount)
			  FROM edms_ac_sales, edms_ac_sales_tax,edms_tax WHERE
			  edms_ac_sales.sales_id = edms_ac_sales_tax.sales_id
			  AND edms_ac_sales_tax.tax_id = edms_tax.tax_id AND
			   ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." to_customer_id IN ($ids_string) ";
	
	
	// sales
	$sql=$sql."
	 UNION ALL
	SELECT -SUM(amount) as debit_amount
			  FROM edms_ac_credit_note WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($ids_string))  
	$sql=$sql." to_customer_id IN ($ids_string) ";	
	
	$sql=$sql." UNION ALL
			  SELECT ";  
	$sql=$sql." SUM(amount)
			  FROM edms_ac_debit_note WHERE ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." from_customer_id IN ($ids_string) ";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	 
	 // taxation debit_note
	$sql=$sql." UNION ALL
			  SELECT ";  	  
	$sql=$sql." SUM(tax_amount)
			  FROM edms_ac_debit_note, edms_ac_debit_note_tax,edms_tax WHERE
			  edms_ac_debit_note.debit_note_id = edms_ac_debit_note_tax.debit_note_id
			  AND edms_ac_debit_note_tax.tax_id = edms_tax.tax_id AND
			   ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	$sql=$sql." from_customer_id IN ($ids_string) ";
	
	 // taxation credit note
	$sql=$sql." UNION ALL
			  SELECT ";			  	  
	$sql=$sql." SUM(-tax_amount)
			  FROM edms_ac_credit_note, edms_ac_credit_note_tax,edms_tax WHERE
			  edms_ac_credit_note.credit_note_id = edms_ac_credit_note_tax.credit_note_id
			  AND edms_ac_credit_note_tax.tax_id = edms_tax.tax_id AND
			   ";	  
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
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
		if(isset($resultArray[4][0]))
		$net_amount=$net_amount+$resultArray[4][0];
		if(isset($resultArray[5][0]))
		$net_amount=$net_amount+$resultArray[5][0];
		if(isset($resultArray[6][0]))
		$net_amount=$net_amount+$resultArray[6][0];
		if(isset($resultArray[7][0]))
		$net_amount=$net_amount+$resultArray[7][0];
		if(isset($resultArray[8][0]))
		$net_amount=$net_amount+$resultArray[8][0];
		if(isset($resultArray[9][0]))
		$net_amount=$net_amount+$resultArray[9][0];
		if(isset($resultArray[10][0]))
		$net_amount=$net_amount+$resultArray[10][0];
		if(isset($resultArray[11][0]))
		$net_amount=$net_amount+$resultArray[11][0];
		if(isset($resultArray[12][0]))
		$net_amount=$net_amount+$resultArray[12][0];
		if(isset($resultArray[13][0]))
		$net_amount=$net_amount+$resultArray[13][0];
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

function getFirstPageBalanceSheet($to,$from)
{
	
			
	$to=getNextDate($to); // return Y-m-d	
	
	$main_heads=listHeads();
    $return_array=array();
	$i=0;
	$pl_sheet_balance=0;
	
	$opening_stock = getClosingStockForDate(getPreviousDate($from));	
	$closing_stock=getClosingStockForDate($to);
	
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
		
		
		
		if($head_id==2)
		{
		$opening_balance_head = $opening_balance_head + $closing_stock;
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
	$pl_sheet_balance = $pl_sheet_balance + $opening_stock - $closing_stock;
	$return_array['profit_loss']['head_id']=0;
	$return_array['profit_loss']['head_name']='Profit And Loss';
	$return_array['profit_loss']['opening_balance']=$pl_sheet_balance;
		
	return $return_array;
}


function getProfitAndLossSheet($from,$to) // $from and $to of form dd/mm/yyyy
{
	
	if(isset($to) && validateForNull($to))
			{
		    $to = str_replace('/', '-', $to);
			$to=date('Y-m-d',strtotime($to));
			}
	
	if(isset($from) && validateForNull($from))
			{
		    $from = str_replace('/', '-', $from);
			$from=date('Y-m-d',strtotime($from));
			}	
	$to=getNextDate($to); // return Y-m-d
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
	$purchase=$return_array[4]['opening_balance'];
	$sales=$return_array[5]['opening_balance'];
	
	
	
	$closing_stock = getClosingStockForDate($to);
	$opening_stock = getClosingStockForDate(getPreviousDate($from));
		
	$closing_stock_details=array();
	$closing_stock_details['head_id']="closePL";
	$closing_stock_details['head_name']="Closing Stock";
	$closing_stock_details['opening_balance']=$closing_stock;
	
	$opening_stock_details=array();
	$opening_stock_details['head_id']="openPL";
	$opening_stock_details['head_name']="Opening Stock";
	$opening_stock_details['opening_balance']=$opening_stock;
	
	$return_array[]=$opening_stock_details;	
	$return_array[]=$closing_stock_details;

	
	
	$gross_profit=$direct_exp+$direct_income+$purchase+$sales-$closing_stock+$opening_stock;
	$sub_total=0;
	$total=0;
	$net_profit=$gross_profit+$indirect_income+$indirect_exp;
	
	if($direct_income<=0)
	$sub_total=$sub_total+$direct_income;
	
	if($sales<=0)
	$sub_total=$sub_total+$sales;
	
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
	
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	$period = getPeriodForUser($admin_id);
	$from=date('d/m/Y',strtotime($period[0]));
	$to=date('d/m/Y',strtotime($period[1]));
	$head=getHeadById($head_id);
	$to=getNextDate($to);
	
    $return_array=array();
	$i=0;
		$current_assets_head_id = getCurrentAssetsId();
		$sundry_debtors_id=getSundryDebtorsId();
		$opening_balance_head=0;
		
		$opening_balance_head_array=getOpeningBalanceForHeadIdForDate($head_id,$to);
		$opening_balance_head=$opening_balance_head_array[0];
		
		if($sundry_debtors_id==$head_id)
		{
		$opening_balance_customers_array=getOpeningBalanceForCustomersForDate($to); // used as closing_balance
		$opening_balance_customers=$opening_balance_customers_array[0];
		}
		
		$opening_balance_head=$opening_balance_head+$opening_balance_customers;
		
		$child_head_array=array();
		$ledgers_array=array();
		$j=0;
		
		$child_heads = getSubHeadsOfHead($head_id);
		
		$stock_in_hand_head = getStockInHandId();
		if($child_heads)
		{
		
		foreach($child_heads as $child_head)
		{
			$child_head_id=$child_head['head_id'];
			
			$opening_balance_child=0;
			
			$opening_balance_child_array=getOpeningBalanceForHeadIdForDate($child_head_id,$to);
			
			
			$opening_balance_child=$opening_balance_child_array[0];
			if($child_head_id==$sundry_debtors_id)
			{
			$customer_opening_balance_array=getOpeningBalanceForCustomersForDate($to);
			
			$customer_opening_balance=$customer_opening_balance_array[0];
			$opening_balance_child=$opening_balance_child+$customer_opening_balance;
			}
			if($stock_in_hand_head == $child_head_id)
			{
				
					$closing_stock = getClosingStockForDate($to);
					$opening_balance_child=$opening_balance_child+$closing_stock;
			}
			$child_head[]=$opening_balance_child;
		    $child_head['opening_balance']=$opening_balance_child;
			$child_head_array[]=$child_head;
			$opening_balance_head=$opening_balance_head+$opening_balance_child;
		}
		}
		
		$ledgers = listDirectLedgersWithBankCashForHeadId($head_id);
		$customers = listDirectCustomerForHeadId($head_id);
		$ob=0;
		if($ledgers)
		{
			$ledgers_opening_balance_array=getOpeningBalanceForLedgerArrayForDate($ledgers,$to);
		}
		if($customers)
		{
			$customers_opening_balance_array=getOpeningBalanceForCustomerArrayForDate($customers,$to);	
				
		}
		
		if(!is_array($ledgers_opening_balance_array))
		$ledgers_opening_balance_array = array();
		if(!is_array($customers_opening_balance_array))
		$customers_opening_balance_array = array();
		
		$ledgers_customers = array_merge($ledgers_opening_balance_array,$customers_opening_balance_array);
		
		$ledgers_array[]=array();
		
		if($ledgers_array)
		{
			foreach($ledgers_customers as $ledgers_customer)
			{
				
				$ledger_id=$ledgers_customer['id'];
				
				$opening_balance_ledger=0;
				$opening_balance_ledger=getOpeningBalanceForLedgerForDate($ledger_id,$to);
				
		//		$ledgers_customer[]=$opening_balance_ledger;
		//	    $ledgers_customer['opening_balance']=$opening_balance_ledger;
				$ob=$ob+$opening_balance_ledger;
				$ledgers_array[]=$ledgers_customer;
			}
		} 
		
		if($stock_in_hand_head == $head_id)
		{
				
				$closing_stock = getClosingStockForDate($to);
				$closing_stock_details=array();
				$closing_stock_details['head_id']="closePL";
				$closing_stock_details['head_name']="Closing Stock";
				$closing_stock_details['opening_balance']=$closing_stock;
				
				$child_head_array[] = $closing_stock_details;
				$return_array[]=$opening_stock_details;	
				$return_array[]=$closing_stock_details;
				$opening_balance_head = $opening_balance_head + $closing_stock; 

			
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
	
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	$period = getPeriodForUser($admin_id);
	$from=$period[0]; // Y-m-d
	$to=date('d/m/Y',strtotime($period[1]));
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