<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("account-head-functions.php");
require_once("account-functions.php");
require_once("customer-functions.php");
require_once("account-period-functions.php");
require_once("account-payment-details-functions.php");
require_once("account-ledger-functions.php");
require_once("account-jv-functions.php");
require_once("common.php");
require_once("bd.php");
function getReconciliationDateForPaymentId($id)
{
	$sql="SELECT reconciliation_date FROM edms_ac_payment WHERE payment_id = $id AND reconciliation_date!='1970-01-01'";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	return false;
}
function getAllPayments()
{
	$sql="SELECT payment_id,payment_ref_type,payment_ref,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_payment";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
}

function getPaymentsForVehicleForCustomerId($customer_id)
{
	if(checkForNumeric($customer_id))
	{
		$vehicle_ids_array = listVehicleIdsForCustomer($customer_id);
		if(!$vehicle_ids_array)
		return "error";
		
		$vehicle_ids_string = implode(",",$vehicle_ids_array);
		$sql="SELECT payment_id,payment_ref,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified,remarks
			  FROM edms_ac_payment
			  WHERE auto_id IN ($vehicle_ids_string) AND auto_rasid_type=4";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 	  
		
	}
}

function getAllNormalPaymentsForCustomer($customer_id) // type = 0 or type >100
{
	$sql="SELECT payment_id,payment_ref,amount,to_ledger_id,from_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified,remarks
			  FROM edms_ac_payment WHERE (auto_rasid_type=0 OR auto_rasid_type>100) AND from_customer_id=$customer_id";
			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
	}	

function getKasarPayments() // type = 5
{
	$oc_id = $_SESSION['edmsAdminSession']['oc_id'];
	$kasar_ledger_id=getKasarLedgerIdForOC($oc_id);
	
	$sql="SELECT payment_id,payment_ref,amount,to_ledger_id,from_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified,remarks
			  FROM edms_ac_payment WHERE auto_rasid_type=5 AND from_ledger_id = $kasar_ledger_id ";
			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
}	

function getKasarPaymentForCashSale($cash_sale_id)
{
	if(checkForNumeric($cash_sale_id))
	{
	$sql="SELECT payment_id,payment_ref,amount,to_ledger_id,from_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified,remarks
			  FROM edms_ac_payment WHERE auto_rasid_type=5 AND auto_id = $cash_sale_id";
			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray[0];
	else
	return false; 	
	}
	return false;
}

function getPaymentsForVehicleId($id) // type = 4 
{
	if(checkForNumeric($id))
	{
		$sql="SELECT payment_id,payment_ref,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified,remarks
			  FROM edms_ac_payment
			  WHERE auto_id=$id AND auto_rasid_type=4";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 	  
		
	}
}

function getPaymentById($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT payment_id,payment_ref_type,payment_ref,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_payment
			  WHERE payment_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return "error"; 
		
		}
	}
			


function updatePaymentForVehicleId($vehicle_id,$payment_id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks)
{
	
	if(checkForNumeric($payment_id,$vehicle_id))
		{
			
			$result=updatePayment($payment_id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks);
			updateAutoIdForPaymentId($payment_id,$vehicle_id);
			return $result;
		}
	
	return "error";
}

function addPaymentForCustomer($amount,$trans_date,$to_ledger,$from_ledger,$remarks,$customer_id,$auto_rasid_type) //type = 0 or type > 100
{
	if(checkForNumeric($customer_id))
	{
    $payment_id=addPayment($amount,$trans_date,$to_ledger,$from_ledger,$remarks,$auto_rasid_type,$customer_id);
	if(checkForNumeric($payment_id))
	addJV($amount,$trans_date,"C".$customer_id,$from_ledger,$remarks,6,$payment_id); // jv type 6
	return $payment_id;
	}
	return false;
}	

function updatePaymentForCustomer($payment_id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks,$customer_id,$auto_rasid_type) // type >100
{
	if(checkForNumeric($customer_id))
	{
	
    updatePayment($payment_id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks);
	updateAutoRasidTypeForPaymentId($payment_id,$auto_rasid_type);
	if(checkForNumeric($payment_id))
	{
	  $paymentForCustomerJv=getPaymentForCustomerJvForPaymentId($payment_id);
	
	  removeJV($paymentForCustomerJv['jv_id']);
		
	  addJV($amount,$trans_date,"C".$customer_id,$from_ledger,$remarks,6,$payment_id);
	}
	return true;
	}
	return false;
}	
	
function deletePaymentForCustomer($payment_id)
{
	if(checkForNumeric($payment_id))
	{
		 $paymentForCustomerJv=getPaymentForCustomerJvForPaymentId($payment_id);
	     removeJV($paymentForCustomerJv['jv_id']);
		 removePayment($payment_id);	
		 return "success";
	}
	return "error";
}

function getPaymentsForCustomerByCustomerId($id) // type > 100
{
	if(checkForNumeric($id))
	{
		$sql="SELECT payment_id,payment_ref,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_payment
			  WHERE auto_id=$id AND (auto_rasid_type>100 OR auto_rasid_type=0)";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 	  
		
	}
}

function addPayment($amount,$trans_date,$to_ledger,$from_ledger,$remarks,$auto_rasid_type=0,$auto_id=0,$payment_ref="NA",$payment_ref_type=0,$oc_id = NULL,$payment_mode_id=-1,$chq_date="01/01/1970",$chq_no="000000",$bank_name="NA",$branch_name="NA") //$from_ledger should start with C for customer or L for ledger, from_ledger: debit and to_ledger: credit 
// auto_rasid_type = 2 for financer payment
{
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];

	if(substr($from_ledger, 0, 1) == 'L') // if the pament is done to a general account ledger
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$current_company=getCompanyForLedger($from_ledger);
				if($current_company[1]==0)
				{
					if(!is_numeric($oc_id))
					
					$oc_id = $current_company[0];
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				}
				
	}
	else if(substr($from_ledger, 0, 1) == 'C') // if payment is done to a customer
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($from_customer);
		
		if(!is_numeric($oc_id))
		$oc_id=getCompanyIdFromCustomerId($customer['customer_id']);
		
		}	
	if(!validateForNull($payment_ref))
	$payment_ref="NA";
	
	if(!validateForNull($payment_ref_type))
	$payment_ref_type=0;	
	
	if( (!(checkForNumeric($from_ledger) || checkForNumeric($from_customer))) || !checkForNumeric($oc_id) || !checkForNumeric($to_ledger) ) // check for proper ledger and customer id, agency or oc_id
	{
		return "ledger_error";
	}
	$bank_accounts_head_id=getBankAccountsHeadId();
	$to_ledger_array=getLedgerById($to_ledger);
	$to_ledger_head_id = $to_ledger_array['head_id'];
	
	if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}	
	$ac_starting_date = $accounts_settings['ac_starting_date'];		
	
	if(strtotime($trans_date)<strtotime($ac_starting_date)) // payment date should be greater than books starting date
	{
		return "date_error";
	}	
	
	if(checkForNumeric($amount,$to_ledger,$admin_id) && $to_ledger>0 && validateForNull($trans_date))
	{
			
			$sql="INSERT INTO edms_ac_payment (payment_ref_type,payment_ref,amount,from_ledger_id,from_customer_id,to_ledger_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified)
			VALUES ($payment_ref_type,'$payment_ref',$amount,$from_ledger,$from_customer,$to_ledger,$oc_id,$auto_rasid_type,$auto_id,'$trans_date','$remarks',$admin_id,$admin_id,NOW(),NOW())";
			
			$result=dbQuery($sql);
			$payment_id = dbInsertId();
			$ac_starting_date = $accounts_settings['ac_starting_date'];
			if($bank_accounts_head_id==$to_ledger_head_id)
			{
				addPaymentDetails($payment_id,$payment_mode_id,$chq_date,$chq_no,$bank_name,$branch_name);
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
			
			return $payment_id;
	}
	return "error";	
}



function removePayment($id)
{
	if(checkForNumeric($id))
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$old_payment=getPaymentById($id); // get the payment details
		$old_amount=$old_payment['amount'];
		$old_trans_date=$old_payment['trans_date'];
		$old_from_ledger_id=$old_payment['from_ledger_id'];
		$old_from_customer_id=$old_payment['from_customer_id'];
		$old_to_ledger_id=$old_payment['to_ledger_id'];
		
	    $oc_id=$old_payment['oc_id'];
	
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
		}
		
		$sql="DELETE FROM edms_ac_payment where payment_id=$id";
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

function updateAutoRasidTypeForPaymentId($receipt_id,$auto_rasid_type)
{
	if(checkForNumeric($receipt_id,$auto_rasid_type))
	{
		$sql="UPDATE edms_ac_payment SET auto_rasid_type = $auto_rasid_type WHERE payment_id = $receipt_id ";
		dbQuery($sql);
		return true;
	}
	return false;
}

function updateAutoIdForPaymentId($receipt_id,$auto_id)
{
	if(checkForNumeric($receipt_id,$auto_id))
	{
		$sql="UPDATE edms_ac_payment SET auto_id = $auto_id WHERE payment_id = $receipt_id ";
		dbQuery($sql);
		return true;
	}
	return false;
}
	
	
function updatePayment($id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks,$payment_ref="NA",$payment_ref_type=0,$oc_id=NULL,$payment_mode_id=-1,$chq_date="01/01/1970",$chq_no="000000",$bank_name="NA",$branch_name="NA")
{
	
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	$old_payment=getPaymentById($id);
	
	$old_amount=$old_payment['amount'];
	$old_trans_date=$old_payment['trans_date'];
	$old_from_ledger_id=$old_payment['from_ledger_id'];
	$old_from_customer_id=$old_payment['from_customer_id'];
	$old_to_ledger_id=$old_payment['to_ledger_id'];
	if(!is_numeric($oc_id))
	$oc_id=$old_payment['oc_id'];
	
	if(!validateForNull($payment_ref))
	$payment_ref="NA";
	
	if(!validateForNull($payment_ref_type))
	$payment_ref_type=0;
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
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
	
	if( (!(checkForNumeric($from_ledger) || checkForNumeric($from_customer))) || (!(checkForNumeric($oc_id))) ) // check for proper ledger and customer id as well as agency or oc id
	{
		return "ledger_error";
		}
	
if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}	
	$ac_starting_date = $accounts_settings['ac_starting_date'];		
	
	if(strtotime($trans_date)<strtotime($ac_starting_date)) // payment date should be greater than books starting date
	{
		return "date_error";
	}	
	
	$bank_accounts_head_id=getBankAccountsHeadId();
	$to_ledger_array=getLedgerById($to_ledger);
	$to_ledger_head_id = $to_ledger_array['head_id'];
		
	if(checkForNumeric($amount,$to_ledger,$admin_id,$id) && validateForNull($trans_date))
	{
			
			$sql="UPDATE edms_ac_payment SET payment_ref_type = $payment_ref_type, payment_ref = '$payment_ref', amount=$amount, from_ledger_id=$from_ledger, to_ledger_id=$to_ledger, from_customer_id=$from_customer, trans_date='$trans_date', remarks='$remarks', last_updated_by=$admin_id, date_modified=NOW()
			WHERE payment_id=$id";
			
			$result=dbQuery($sql);
			
			$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if($bank_accounts_head_id==$to_ledger_head_id)
			{
				addPaymentDetails($id,$payment_mode_id,$chq_date,$chq_no,$bank_name,$branch_name);
			}
			else
			removePaymentDetails($id);
			
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger) && $head_type!=3 && $head_type!=4))
	{
	$sql="SELECT payment_id,SUM(amount),from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_payment WHERE oc_id = $oc_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==2))  
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger) && $head_type!=3 && $head_type!=4))
	{
	$sql="SELECT payment_id,SUM(amount),from_ledger_id,to_ledger_id,from_customer_id
			  FROM edms_ac_payment WHERE  oc_id = $oc_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==2))  
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger) && $head_type!=3 && $head_type!=4 )) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT payment_id,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_payment WHERE  oc_id = $oc_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==2))  
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger) && $head_type!=3 && $head_type!=4)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT payment_id,SUM(amount) as total_amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_payment WHERE  oc_id = $oc_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." to_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==2))  
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
	 $current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	 $oc_id=$current_company[0];
		
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger) && $head_type!=3 && $head_type!=4 )  || $from_ledger==-1)
	{
	$sql="SELECT payment_id,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified, remarks
			  FROM edms_ac_payment WHERE oc_id = $oc_id AND ";
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
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==2))  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";  		
	}
	else
	{
	if(checkForNumeric($oc_id))
	$sql=$sql." oc_id IN ( ".$oc_id.")";	  
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