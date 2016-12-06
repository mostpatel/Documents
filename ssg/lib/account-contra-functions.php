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

function getAllContras()
{
	$sql="SELECT contra_id,amount,from_ledger_id,to_ledger_id,trans_date,remarks,oc_id,agency_id,created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_contra";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
	}
function getContraById($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT contra_id,amount,from_ledger_id,to_ledger_id,trans_date,remarks,oc_id,agency_id,created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_contra
			  WHERE contra_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return "error"; 
		
		}
	}

function addContra($amount,$trans_date,$to_ledger,$from_ledger,$remarks) //$from_ledger should start with C for customer or L for ledger 
{
	$admin_id=$_SESSION['adminSession']['admin_id'];
	
        if(substr($from_ledger, 0, 1) == 'L') // if the pament is done to a general account ledger
		{
		$from_ledger=str_replace('L','',$from_ledger);	
		$from_ledger=intval($from_ledger);
		
		
			$from_ledger_type=getLedgerHeadType($from_ledger); // returns 0 if bank or cash else returns 1
		
		if($from_ledger_type==1)
		return "ledger_error";
		
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
		if(substr($to_ledger, 0, 1) == 'L')
		{
		$to_ledger=str_replace('L','',$to_ledger);	
		$to_ledger=intval($to_ledger);
		
		$to_ledger_type=getLedgerHeadType($to_ledger); // returns 0 if bank or cash else returns 1
		
		if($to_ledger_type==1)
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
	
	if(checkForNumeric($amount,$to_ledger,$from_ledger,$admin_id) && validateForNull($trans_date))
	{
		
			if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}
			$sql="INSERT INTO fin_ac_contra (amount,from_ledger_id,to_ledger_id,trans_date,remarks,oc_id,agency_id,created_by,last_updated_by,date_added,date_modified)
			VALUES ($amount,$from_ledger,$to_ledger,'$trans_date','$remarks',$oc_id,$agency_id,$admin_id,$admin_id,NOW(),NOW())";
			
			$result=dbQuery($sql);
			
			$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if(strtotime($trans_date)>=strtotime($ac_starting_date))
			{
					creditAccountingLedger($from_ledger,$amount);
					debitAccountingLedger($to_ledger,$amount);
			}	
			
			return "success";
	}
	return "error";	
}

function removeContra($id)
{
	if(checkForNumeric($id))
	{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$old_payment=getContraById($id); // get the payment details
		$old_amount=$old_payment['amount'];
		$old_trans_date=$old_payment['trans_date'];
		$old_from_ledger_id=$old_payment['from_ledger_id'];
		$old_to_ledger_id=$old_payment['to_ledger_id'];
		
	
		
		$sql="DELETE FROM fin_ac_contra where contra_id=$id";
		dbQuery($sql);
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if(strtotime($old_trans_date)>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
				
				creditAccountingLedger($old_to_ledger_id,$old_amount);
				debitAccountingLedger($old_from_ledger_id,$old_amount);
			}	
		return "success";
		}
		return "error";
	}
	
function updateContra($id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks)
{
	$admin_id=$_SESSION['adminSession']['admin_id'];
	$old_payment=getContraById($id);
	
	$old_amount=$old_payment['amount'];
	$old_trans_date=$old_payment['trans_date'];
	$old_from_ledger_id=$old_payment['from_ledger_id'];
	$old_to_ledger_id=$old_payment['to_ledger_id'];
	
	
		
	  if(substr($from_ledger, 0, 1) == 'L') // if the pament is done to a general account ledger
		{
		$from_ledger=str_replace('L','',$from_ledger);	
		$from_ledger=intval($from_ledger);
		
			$from_ledger_type=getLedgerHeadType($from_ledger); // returns 0 if bank or cash else returns 1
		
		if($from_ledger_type==1)
		return "ledger_error";
		
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
		  if(substr($to_ledger, 0, 1) == 'L') // if the pament is done to a general account ledger
		{
		$to_ledger=str_replace('L','',$to_ledger);	
		$to_ledger=intval($to_ledger);
		$to_ledger_type=getLedgerHeadType($to_ledger); // returns 0 if bank or cash else returns 1
		if($to_ledger_type==1)
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
	
		
	if(checkForNumeric($amount,$to_ledger,$from_ledger,$admin_id,$id) && $amount>=0 && validateForNull($trans_date))
	{
			if(isset($trans_date) && validateForNull($trans_date))
			{
			$trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}
			$sql="UPDATE fin_ac_contra SET amount=$amount, from_ledger_id=$from_ledger, to_ledger_id=$to_ledger, trans_date='$trans_date', remarks='$remarks', last_updated_by=$admin_id, date_modified=NOW()
			WHERE contra_id=$id";
			$result=dbQuery($sql);
			
			
			$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if(strtotime($old_trans_date)>=strtotime($ac_starting_date))
			{
				
				creditAccountingLedger($old_to_ledger_id,$old_amount);
				debitAccountingLedger($old_from_ledger_id,$old_amount);
			}	
			
			
			if(strtotime($trans_date)>=strtotime($ac_starting_date))
			{
				
				creditAccountingLedger($from_ledger,$amount);
				debitAccountingLedger($to_ledger,$amount);
			}	
	return "success";
	}
	
	return "error";	
	
	}	

	

function getCreditContrasForLedgerIdMonthWiseBetweenDates($from_ledger,$from=NULL,$to=NULL) // ledgers only cash and banks, ledger_id should start with l for ledger 
{

	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
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
	
	if(checkForNumeric($from_ledger))
	{
	$sql="SELECT contra_id,SUM(amount),from_ledger_id,to_ledger_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_contra WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$from_ledger ";
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


function getTotalCreditContrasForLedgerIdMonthWiseBetweenDates($from_ledger,$month_id,$year,$from=NULL,$to=NULL) // ledgers only cash and banks, ledger_id should start with l for ledger 
{

	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
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
	
	if(checkForNumeric($from_ledger)  && checkForNumeric($month_id,$year))
	{
	$sql="SELECT contra_id,SUM(amount) as total_amount,from_ledger_id,to_ledger_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_contra WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$from_ledger ";
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


function getDebitContrasForLedgerIdMonthWiseBetweenDates($to_ledger,$from=NULL,$to=NULL) // ledgers only cash and banks, ledger_id should start with l for ledger 
{

	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
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
	
	if(checkForNumeric($to_ledger))
	{
	$sql="SELECT contra_id,SUM(amount),from_ledger_id,to_ledger_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_contra WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$to_ledger ";
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
	
function getTotalDebitContrasForLedgerIdMonthWiseBetweenDates($to_ledger,$month_id,$year,$from=NULL,$to=NULL) // ledgers only cash and banks, ledger_id should start with l for ledger 
{

	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
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
	
	if(checkForNumeric($to_ledger) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT contra_id,SUM(amount) as total_amount,from_ledger_id,to_ledger_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_contra WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$to_ledger ";
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
		
function getCreditContraAmountForLedgerIdUptoDate($from_ledger,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{

	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	
	if(checkForNumeric($from_ledger,$head_type))
	{
	$sql="SELECT contra_id,SUM(amount),from_ledger_id,to_ledger_id
			  FROM fin_ac_contra WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}
	
function getNetContraAmountForLedgerIdUptoDate($from_ledger,$to=NULL)
{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
	$to=date('Y-m-d',strtotime($to));
	}
	
	if(checkForNumeric($from_ledger,$head_type))
	{
	$sql="SELECT SUM(amount)
			  FROM fin_ac_contra WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id"; 
	
	$sql=$sql." UNION ALL SELECT SUM(amount)
			  FROM fin_ac_contra WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id";  	
	 		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[1][0]-$resultArray[0][0];
	else
	return 0; 	
	}
	return 0;
	
	}		

function getDebitContraAmountForLedgerIdUptoDate($to_ledger,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{

	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	
	if(checkForNumeric($to_ledger,$head_type))
	{
	$sql="SELECT contra_id,SUM(amount),from_ledger_id,to_ledger_id
			  FROM fin_ac_contra WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$to_ledger GROUP BY to_ledger_id";  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}	

	

function getContrasForLedgerIdForMonth($from_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
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
	
	if(checkForNumeric($from_ledger,$head_type) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT contra_id,amount,from_ledger_id,to_ledger_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM fin_ac_contra WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." (to_ledger_id=$from_ledger OR from_ledger_id=$from_ledger) ";		
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

function getContrasForLedgerIdBetweenDates($from_ledger,$from=NULL,$to=NULL)
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
	
	if(checkForNumeric($from_ledger,$head_type)  || $from_ledger==-1)
	{
	$sql="SELECT contra_id,amount,from_ledger_id,to_ledger_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified, remarks
			  FROM fin_ac_contra WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($from_ledger!=-1)
	{	  
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." (to_ledger_id=$from_ledger OR from_ledger_id=$from_ledger) ";	
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