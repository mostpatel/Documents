<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("account-head-functions.php");
require_once("account-functions.php");
require_once("file-functions.php");
require_once("customer-functions.php");
require_once("account-period-functions.php");
require_once("account-ledger-functions.php");
require_once("common.php");
require_once("bd.php");

function getAllPayments()
{
	$sql="SELECT payment_id,amount,from_ledger_id,to_ledger_id,from_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_payment";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
	}
function getPaymentById($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT payment_id,amount,from_ledger_id,to_ledger_id,from_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_payment
			  WHERE payment_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return "error"; 
		
		}
	}
			
function getPaymentForLoanId($loan_id)
{
	
	if(checkForNumeric($loan_id))
	{
		$sql="SELECT payment_id,amount,from_ledger_id,to_ledger_id,from_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_payment
			  WHERE auto_id=$loan_id AND auto_rasid_type=1";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 
		
		}
	}	

function addPayment($amount,$trans_date,$to_ledger,$from_ledger,$remarks,$auto_rasid_type=0,$auto_id=0) //$from_ledger should start with C for customer or L for ledger, from_ledger: debit and to_ledger: credit
{
	$admin_id=$_SESSION['adminSession']['admin_id'];

	if(substr($from_ledger, 0, 1) == 'L') // if the pament is done to a general account ledger
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$current_company=getCompanyForLedger($from_ledger);
				if($current_company[1]==0)
				{
					$oc_id = $current_company[0];
					$agency_id='NULL';
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				}
				else if($current_company[1]==1)
				{
					$agency_id = $current_company[0];
					$oc_id = 'NULL';
					$accounts_settings=getAccountsSettingsForAgency($agency_id);
				}
	}
	else if(substr($from_ledger, 0, 1) == 'C') // if payment is done to a customer
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($from_customer);
		$file_id=$customer['file_id'];
		
		$agency_company_type_array=getAgencyOrCompanyIdFromFileId($file_id);
		$agency_company_type=$agency_company_type_array[0];
		$agency_company_type_id=$agency_company_type_array[1];
		
				if($agency_company_type=="agency")
				{
					$agency_id=$agency_company_type_id;
					$oc_id="NULL";
					
					$accounts_settings=getAccountsSettingsForAgency($agency_id);
				}
				else
				{
				$oc_id=$agency_company_type_id;
					$agency_id="NULL";
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				}
		
		}	
		
	if( (!(checkForNumeric($from_ledger) || checkForNumeric($from_customer))) || (!(checkForNumeric($agency_id) || checkForNumeric($oc_id))) && checkForNumeric($to_ledger) ) // check for proper ledger and customer id, agency or oc_id
	{
		return "ledger_error";
		}
	if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}	
	$ac_starting_date = $accounts_settings['ac_starting_date'];		
	
	$period = getPeriodForUser($admin_id);
	
	$from_period_date = $period[0];
	$to_period_date = $period[1];
	
	if(strtotime($trans_date)<strtotime($ac_starting_date) || (PERIOD_RESTRICTION==1 && (strtotime($trans_date)<strtotime($from_period_date) || strtotime($trans_date)>strtotime($to_period_date) ))) // payment date should be greater than books starting date
	{
		return "date_error";
	}	
	
	if(checkForNumeric($amount,$to_ledger,$admin_id) && $to_ledger>0 && validateForNull($trans_date))
	{
			if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}
			$sql="INSERT INTO fin_ac_payment (amount,from_ledger_id,from_customer_id,to_ledger_id,agency_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified)
			VALUES ($amount,$from_ledger,$from_customer,$to_ledger,$agency_id,$oc_id,$auto_rasid_type,$auto_id,'$trans_date','$remarks',$admin_id,$admin_id,NOW(),NOW())";
			$result=dbQuery($sql);
			
			$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if(strtotime($trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($from_ledger) && $from_ledger>0)
				{
					creditAccountingLedger($to_ledger,$amount);
					debitAccountingLedger($from_ledger,$amount);
				}
				else if(checkForNumeric($from_customer) && $from_customer>0)
				{
				    creditAccountingLedger($to_ledger,$amount);
					debitAccountingCustomer($from_customer,$amount);
				}
			}	
			
			return "success";
	}
	return "error";	
}

function removePayment($id)
{
	if(checkForNumeric($id))
	{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$old_payment=getPaymentById($id); // get the payment details
		$old_amount=$old_payment['amount'];
		$old_trans_date=$old_payment['trans_date'];
		$old_from_ledger_id=$old_payment['from_ledger_id'];
		$old_from_customer_id=$old_payment['from_customer_id'];
		$old_to_ledger_id=$old_payment['to_ledger_id'];
		
		$agency_id=$old_payment['agency_id'];
	    $oc_id=$old_payment['oc_id'];
	
	if(checkForNumeric($agency_id) && validateForNull($agency_id))
	{
		$accounts_settings=getAccountsSettingsForAgency($agency_id);
	}
	else if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
		}
		
		$sql="DELETE FROM fin_ac_payment where payment_id=$id";
		dbQuery($sql);
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if(strtotime($old_trans_date)>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
				
				if(checkForNumeric($old_from_ledger_id) && $old_from_ledger_id>0)
				{
					
					debitAccountingLedger($old_to_ledger_id,$old_amount);
					creditAccountingLedger($old_from_ledger_id,$old_amount);
				}
				else if(checkForNumeric($old_from_customer_id) && $old_from_customer_id>0)
				{
					
					debitAccountingLedger($old_to_ledger_id,$old_amount);
					creditAccountingCustomer($old_from_customer_id,$old_amount);
				}
			}	
		return "success";
		}
		return "error";
	}
	
function updatePayment($id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks)
{
	
	$admin_id=$_SESSION['adminSession']['admin_id'];
	
	$old_payment=getPaymentById($id);
	
	$old_amount=$old_payment['amount'];
	$old_trans_date=$old_payment['trans_date'];
	$old_from_ledger_id=$old_payment['from_ledger_id'];
	$old_from_customer_id=$old_payment['from_customer_id'];
	$old_to_ledger_id=$old_payment['to_ledger_id'];
	
	$agency_id=$old_payment['agency_id'];
	$oc_id=$old_payment['oc_id'];
	
	if(checkForNumeric($agency_id) && validateForNull($agency_id))
	{
		$accounts_settings=getAccountsSettingsForAgency($agency_id);
	}
	else if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
		}
		
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
	if( (!(checkForNumeric($from_ledger) || checkForNumeric($from_customer))) || (!(checkForNumeric($agency_id) || checkForNumeric($oc_id))) ) // check for proper ledger and customer id as well as agency or oc id
	{
		return "ledger_error";
		}
	
if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}	
	$ac_starting_date = $accounts_settings['ac_starting_date'];		
	
	$period = getPeriodForUser($admin_id);
	$from_period_date = $period[0];
	$to_period_date = $period[1];
	if(strtotime($trans_date)<strtotime($ac_starting_date) || (PERIOD_RESTRICTION==1 && (strtotime($trans_date)<strtotime($from_period_date) || strtotime($trans_date)>strtotime($to_period_date) ))) // payment date should be greater than books starting date
	{
		return "date_error";
	}	
	
		
	if(checkForNumeric($amount,$to_ledger,$admin_id,$id) && validateForNull($trans_date))
	{
			if(isset($trans_date) && validateForNull($trans_date))
			{
			$trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}
			$sql="UPDATE fin_ac_payment SET amount=$amount, from_ledger_id=$from_ledger, to_ledger_id=$to_ledger, from_customer_id=$from_customer, trans_date='$trans_date', remarks='$remarks', last_updated_by=$admin_id, date_modified=NOW()
			WHERE payment_id=$id";
			
			$result=dbQuery($sql);
			
			$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if(strtotime($old_trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($old_from_ledger_id) && $old_from_ledger_id>0)
				{
					
					debitAccountingLedger($old_to_ledger_id,$old_amount);
					creditAccountingLedger($old_from_ledger_id,$old_amount);
				}
				else if(checkForNumeric($old_from_customer_id) && $old_from_customer_id>0)
				{
					
					debitAccountingLedger($old_to_ledger_id,$old_amount);
					creditAccountingCustomer($old_from_customer_id,$old_amount);
				}
			}	
			
			
			if(strtotime($trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($from_ledger) && $from_ledger>0)
				{
					creditAccountingLedger($to_ledger,$amount);
					debitAccountingLedger($from_ledger,$amount);
				}
				else if(checkForNumeric($from_customer) && $from_customer>0)
				{
				
					creditAccountingLedger($to_ledger,$amount);
					debitAccountingCustomer($from_customer,$amount);
				}
			}	
			
	return "success";
	}
	
	return "error";	
	
	}	

	

function getPaymentsForLedgerIdMonthWiseBetweenDates($from_ledger,$from=NULL,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{

	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
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
	
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)))
	{
	$sql="SELECT payment_id,SUM(amount),from_ledger_id,to_ledger_id,from_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_payment WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";
	$sql=$sql." GROUP BY month_year";	  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
	}
		
function getTotalPaymentAmountForLedgerIdUptoDate($from_ledger,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{

	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)))
	{
	$sql="SELECT payment_id,SUM(amount),from_ledger_id,to_ledger_id,from_customer_id
			  FROM fin_ac_payment WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer GROUP BY from_customer_id";	  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}	

function getPaymentsForLedgerIdForMonth($from_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
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
	
	if((checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger))) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT payment_id,amount,from_ledger_id,to_ledger_id,from_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_payment WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";
	
	$sql=$sql." AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year";		  
	$result=dbQuery($sql);
	
	$resultArray=dbResultToArray($result);
	
	
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
}	

function getTotalPaymentForLedgerIdForMonth($from_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
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
	
	if((checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger))) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT payment_id,SUM(amount) as total_amount,from_ledger_id,to_ledger_id,from_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_payment WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";
	
	$sql=$sql." AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY month_year";	  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
}			

function getPaymentsForLedgerIdBetweenDates($from_ledger,$from=NULL,$to=NULL)
{
	if($from_ledger!=-1)
	{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	}
	else
	{
	    $current_company=getCurrentCompanyForUser($_SESSION['adminSession']['admin_id']);
		$oc_agency_id=$current_company[0];
		$company_type=$current_company[1];	
		
		if($company_type==0)
		{
			$oc_ids=$oc_agency_id;
			$agency_ids=NULL;
			}
		else if($company_type==1)
		{			$oc_ids=NULL;
			$agency_ids=$oc_agency_id;
			}	
		else if($company_type==2)
		{
			
			
		 $agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);	
		}
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
	
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)) || $from_ledger==-1)
	{
	$sql="SELECT payment_id,amount,from_ledger_id,to_ledger_id,from_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified,remarks
			  FROM fin_ac_payment WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($from_ledger!=-1)
	{	  
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";
	}
	else
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
	return $resultArray;
	else
	return array();
	}
	return array();
}			

?>