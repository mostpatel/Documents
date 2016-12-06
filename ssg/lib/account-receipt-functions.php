<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("account-head-functions.php");
require_once("account-period-functions.php");
require_once("account-ledger-functions.php");
require_once("account-functions.php");
require_once("file-functions.php");
require_once("customer-functions.php");
require_once("common.php");
require_once("bd.php");

function getAllReceipts()
{
	$sql="SELECT receipt_id,amount,from_ledger_id,to_ledger_id,to_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,remarks,trans_date,created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_receipt";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
	}

function getReceiptForEMIPaymentId($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT receipt_id,amount,from_ledger_id,to_ledger_id,to_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,remarks,trans_date,created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_receipt
			  WHERE auto_id=$id AND auto_rasid_type=2";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 
		
		}
	}	

function getReceiptForFileClosureId($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT receipt_id,amount,from_ledger_id,to_ledger_id,to_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,remarks,trans_date,created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_receipt
			  WHERE auto_id=$id AND auto_rasid_type=4";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 
		
		}
	}		

function getReceiptForPenaltyId($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT receipt_id,amount,from_ledger_id,to_ledger_id,to_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,remarks,trans_date,created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_receipt
			  WHERE auto_id=$id AND auto_rasid_type=3";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 
		
		}
	}		
	
function getReceiptById($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT receipt_id,amount,from_ledger_id,to_ledger_id,to_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,remarks,trans_date,created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_receipt
			  WHERE receipt_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return "error"; 
		
		}
	}

function insertReceipt($amount,$trans_date,$to_ledger,$from_ledger,$remarks,$auto_rasid_type=0,$auto_id=0) // $to_ledger should start with C for customer or L for ledger to_ledger: credit, from_ledger: debit
{
	$admin_id=$_SESSION['adminSession']['admin_id'];
	
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$current_company=getCompanyForLedger($to_ledger);
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
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($to_customer);
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
		
	if( (!(checkForNumeric($to_ledger) || checkForNumeric($to_customer))) || (!(checkForNumeric($agency_id) || checkForNumeric($oc_id))) )
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
		
	if(checkForNumeric($amount,$from_ledger,$admin_id) && $from_ledger>0 && validateForNull($trans_date))
	{
			if(isset($trans_date) && validateForNull($trans_date))
			{
		$trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}
			$sql="INSERT INTO fin_ac_receipt (amount,from_ledger_id,to_ledger_id,to_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified)
			VALUES ($amount,$from_ledger,$to_ledger,$to_customer,$agency_id,$oc_id,$auto_rasid_type,$auto_id,'$trans_date','$remarks',$admin_id,$admin_id,NOW(),NOW())";
			$result=dbQuery($sql);
			
			$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if(strtotime($trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($to_ledger) && $to_ledger>0)
				{
					creditAccountingLedger($to_ledger,$amount);
					debitAccountingLedger($from_ledger,$amount);
				}
				else if(checkForNumeric($to_customer) && $to_customer>0)
				{
					
					debitAccountingLedger($from_ledger,$amount);
						
					creditAccountingCustomer($to_customer,$amount);
				}
			}	
			
			
			return "success";
	}
	return "error";	
}

function deleteReceipt($id)
{
	if(checkForNumeric($id))
	{
		
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$old_payment=getReceiptById($id);
		$old_amount=$old_payment['amount'];
		$old_trans_date=$old_payment['trans_date'];
		$old_to_ledger_id=$old_payment['to_ledger_id'];
		$old_to_customer_id=$old_payment['to_customer_id'];
		$old_from_ledger_id=$old_payment['from_ledger_id'];
		
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
		
		$sql="DELETE FROM fin_ac_receipt where receipt_id=$id";
		dbQuery($sql);
		
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
					
					debitAccountingCustomer($old_to_customer_id,$old_amount);
					creditAccountingLedger($old_from_ledger_id,$old_amount);
				}
			}	
		
		return "success";
		}
		return "error";
	}

function updateReceipt($id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks) // $to_ledger should start with C for customer or L for ledger
{
	$admin_id=$_SESSION['adminSession']['admin_id'];
	
	$old_payment=getReceiptById($id);
		$old_amount=$old_payment['amount'];
		$old_trans_date=$old_payment['trans_date'];
		$old_to_ledger_id=$old_payment['to_ledger_id'];
		$old_to_customer_id=$old_payment['to_customer_id'];
		$old_from_ledger_id=$old_payment['from_ledger_id'];
		
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
	
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		
		}	
		
	if( (!(checkForNumeric($to_ledger) || checkForNumeric($to_customer))) || (!(checkForNumeric($agency_id) || checkForNumeric($oc_id))) )
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
	
		
	if(checkForNumeric($amount,$from_ledger,$admin_id,$id) && validateForNull($trans_date))
	{
		
			$sql="UPDATE fin_ac_receipt SET amount=$amount, from_ledger_id=$from_ledger, to_ledger_id=$to_ledger, to_customer_id=$to_customer, trans_date='$trans_date', remarks='$remarks', last_updated_by=$admin_id, date_modified=NOW()
			WHERE receipt_id=$id";
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
					
					debitAccountingCustomer($old_to_customer_id,$old_amount);
					creditAccountingLedger($old_from_ledger_id,$old_amount);
				}
			}	
			
				if(strtotime($trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($to_ledger) && $to_ledger>0)
				{
					creditAccountingLedger($to_ledger,$amount);
					debitAccountingLedger($from_ledger,$amount);
				}
				else if(checkForNumeric($to_customer) && $to_customer>0)
				{
					
					debitAccountingLedger($from_ledger,$amount);
					creditAccountingCustomer($to_customer,$amount);
				}
			}	
			
			return "success";
	}
	return "error";	
	
	}	
	
function getReceiptsForLedgerIdMonthWise($to_ledger,$from=NULL,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
	
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
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
	
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)))
	{
	$sql="SELECT receipt_id,SUM(amount),from_ledger_id,to_ledger_id,to_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,trans_date, trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_receipt WHERE ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
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
	
function getTotalReceiptAmountForLedgerIdUptoDate($to_ledger,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
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
	
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)))
	{
	$sql="SELECT receipt_id,SUM(amount),from_ledger_id,to_ledger_id,to_customer_id
			  FROM fin_ac_receipt WHERE ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$to_ledger GROUP BY from_ledger_id";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." to_ledger_id=$to_ledger GROUP BY to_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer GROUP BY to_customer_id";			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}	

function getReceiptsForLedgerIdForMonth($to_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
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
	
	if((checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger))) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT receipt_id,amount,from_ledger_id,to_ledger_id,to_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,trans_date, trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_receipt WHERE ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
	$sql=$sql."  AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year";	  			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
	}	
	
function getTotalReceiptForLedgerIdForMonth($to_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
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
	
	if((checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger))) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT receipt_id,SUM(amount) as total_amount,from_ledger_id,to_ledger_id,to_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,trans_date, trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_receipt WHERE ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
	$sql=$sql."  AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY month_year";	  			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}		

function getReceiptsForLedgerIdBetweenDates($to_ledger,$from=NULL,$to=NULL)
{
	if($to_ledger!=-1)
	{
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
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
	
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger))  || $to_ledger==-1)
	{
	$sql="SELECT receipt_id,amount,from_ledger_id,to_ledger_id,to_customer_id,agency_id,oc_id,auto_rasid_type,auto_id,trans_date, trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified, remarks
			  FROM fin_ac_receipt WHERE ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($to_ledger!=-1)
	{	  
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && $head_type==1)  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
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