<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("account-head-functions.php");
require_once("account-functions.php");
require_once("customer-functions.php");
require_once("account-period-functions.php");
require_once("account-ledger-functions.php");
require_once("tax-functions.php");
require_once("common.php");
require_once("bd.php");

function getAllDebitNotes()
{
	$sql="SELECT debit_note_id,debit_note_ref_type,debit_note_ref,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date, delivery_date,remarks,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_debit_note";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
}

function getAllDebitNotesByType($type_id=NULL)
{
	
	$sql="SELECT debit_note_id,debit_note_ref_type,debit_note_ref,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date, delivery_date,remarks,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_debit_note";
	if(validateForNull($type_id) && checkForNumeric($type_id) && $type_id>=0)
	$sql=$sql." WHERE auto_rasid_type=$type_id";		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return false; 
}

function getDebitNoteById($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT debit_note_id,debit_note_ref_type,debit_note_ref,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date, delivery_date,remarks,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_debit_note
			  WHERE debit_note_id=$id";
		 
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return "error"; 
		
		}
	}
			
function getDebitNoteForLoanId($loan_id)
{
	
	if(checkForNumeric($loan_id))
	{
		$sql="SELECT debit_note_id,debit_note_ref_type,debit_note_ref,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date, delivery_date,remarks,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_debit_note
			  WHERE auto_id=$loan_id AND auto_rasid_type=1";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 
		
		}
	}	

function addDebitNote($amount,$trans_date, $delivery_date,$to_ledger,$from_ledger,$remarks,$auto_rasid_type=0,$auto_id=0,$debit_note_ref="NA",$debit_note_ref_type=0) //$from_ledger should start with C for customer or L for ledger, from_ledger: debit and to_ledger: credit
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
		
		
		$oc_id=getCompanyIdFromCustomerId($customer['customer_id']);
		
		}	
		
	if( (!(checkForNumeric($from_ledger) || checkForNumeric($from_customer))) || !checkForNumeric($oc_id) || !checkForNumeric($to_ledger) ) // check for proper ledger and customer id, agency or oc_id
	{
		return "ledger_error";
		}
	if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}	
	if(isset($delivery_date) && validateForNull($delivery_date))
			{
		    $delivery_date = str_replace('/', '-', $delivery_date);
			$delivery_date=date('Y-m-d',strtotime($delivery_date));
			}			
	$ac_starting_date = $accounts_settings['ac_starting_date'];		
	
	if(strtotime($trans_date)<strtotime($ac_starting_date)) // payment date should be greater than books starting date
	{
		return "date_error";
	}	
	
	if(checkForNumeric($amount,$to_ledger,$admin_id) && $to_ledger>0 && validateForNull($trans_date,$delivery_date))
	{
			
			$sql="INSERT INTO edms_ac_debit_note (debit_note_ref_type,debit_note_ref,amount,from_ledger_id,from_customer_id,to_ledger_id,oc_id,auto_rasid_type,auto_id,trans_date, delivery_date,remarks,created_by,last_updated_by,date_added,date_modified)
			VALUES ($debit_note_ref_type,'$debit_note_ref',$amount,$from_ledger,$from_customer,$to_ledger,$oc_id,$auto_rasid_type,$auto_id,'$trans_date','$delivery_date','$remarks',$admin_id,$admin_id,NOW(),NOW())";
			
			$result=dbQuery($sql);
			$debit_note_id=dbInsertId();
			$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if(strtotime($trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($from_ledger) && $from_ledger>0)
				{
					debitAccountingLedger($to_ledger,$amount);
					creditAccountingLedger($from_ledger,$amount);
				}
				else if(checkForNumeric($from_customer) && $from_customer>0)
				{
				    debitAccountingLedger($to_ledger,$amount);
					creditAccountingCustomer($from_customer,$amount);
				}
			}	
			
			return $debit_note_id;
	}
	return "error";	
}

function removeDebitNote($id)
{
	if(checkForNumeric($id))
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$old_payment=getDebitNoteById($id); // get the payment details
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
		deleteTaxForDebitNote($id);
		$sql="DELETE FROM edms_ac_debit_note where debit_note_id=$id";
		dbQuery($sql);
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
		if(strtotime($old_trans_date)>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
				if(checkForNumeric($old_from_ledger_id) && $old_from_ledger_id>0)
				{	
					creditAccountingLedger($old_to_ledger_id,$old_amount);
					debitAccountingLedger($old_from_ledger_id,$old_amount);
				}
				else if(checkForNumeric($old_from_customer_id) && $old_from_customer_id>0)
				{
					creditAccountingLedger($old_to_ledger_id,$old_amount);
					debitAccountingCustomer($old_from_customer_id,$old_amount);
				} 
				
			}
			
		return "success";
		}
		return "error";
	}
	
function updateDebitNote($id,$amount,$trans_date, $delivery_date,$to_ledger,$from_ledger,$remarks,$debit_note_ref="NA",$debit_note_ref_type=0)
{
	
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	$old_payment=getDebitNoteById($id);
	
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
if(isset($delivery_date) && validateForNull($delivery_date))
			{
		    $delivery_date = str_replace('/', '-', $delivery_date);
			$delivery_date=date('Y-m-d',strtotime($delivery_date));
			}				
	$ac_starting_date = $accounts_settings['ac_starting_date'];		
	
	if(strtotime($trans_date)<strtotime($ac_starting_date)) // payment date should be greater than books starting date
	{
		return "date_error";
	}	
	
		
	if(checkForNumeric($amount,$to_ledger,$admin_id,$id) && validateForNull($trans_date,$delivery_date))
	{
			
			$sql="UPDATE edms_ac_debit_note SET debit_note_ref_type = $debit_note_ref_type, debit_note_ref = '$debit_note_ref', amount=$amount, from_ledger_id=$from_ledger, to_ledger_id=$to_ledger, from_customer_id=$from_customer, trans_date='$trans_date', delivery_date='$delivery_date', remarks='$remarks', last_updated_by=$admin_id, date_modified=NOW()
			WHERE debit_note_id=$id";
			
			$result=dbQuery($sql);
			
			$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if(strtotime($old_trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($old_from_ledger_id) && $old_from_ledger_id>0)
				{
					
					creditAccountingLedger($old_to_ledger_id,$old_amount);
					debitAccountingLedger($old_from_ledger_id,$old_amount);
				}
				else if(checkForNumeric($old_from_customer_id) && $old_from_customer_id>0)
				{
					
					creditAccountingLedger($old_to_ledger_id,$old_amount);
					debitAccountingCustomer($old_from_customer_id,$old_amount);
				}
			}	
			
			
			if(strtotime($trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($from_ledger) && $from_ledger>0)
				{
					debitAccountingLedger($to_ledger,$amount);
					creditAccountingLedger($from_ledger,$amount);
				}
				else if(checkForNumeric($from_customer) && $from_customer>0)
				{
				
					debitAccountingLedger($to_ledger,$amount);
					creditAccountingCustomer($from_customer,$amount);
				}
			}	
			
	return "success";
	}
	
	return "error";	
	
}
	
function insertTaxToDebitNoteVehicle($debit_note_id,$vehicle_id,$tax_group,$basic_price)
{
	
	
	if(checkForNumeric($debit_note_id,$vehicle_id,$tax_group) && $tax_group>0)
	{
		$taxes = listTaxsFromTaxGroupId($tax_group);
		
		foreach($taxes as $tax)
		{
		
		$tax_id = $tax['tax_id'];
		$tax_percent = $tax['tax_percent'];
		$tax_ledger_id = getTaxLedgerForTaxID($tax_id);
		$tax_amount = $basic_price * ($tax_percent/100);
		$tax= getTaxByID($tax_id);
		$tax_in_out = $tax['in_out'];
		
		if($tax_in_out==2) // if include in debit_note add tax amount in the debit_note making tax amount 0
		{	
		$tax_amount=0;
		}
		
		$sql="INSERT INTO edms_ac_debit_note_tax (debit_note_id, vehicle_id, tax_group_id, tax_amount, tax_id) VALUES ($debit_note_id,$vehicle_id,$tax_group,$tax_amount,$tax_id)";
		dbQuery($sql);	
		
		
	$debit_note = getDebitNoteById($debit_note_id);
	
	 $oc_id=$debit_note['oc_id'];
	
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
	}
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
	if(strtotime($debit_note['trans_date'])>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
		if(is_numeric($debit_note['from_ledger_id']))
		{
			$from_id = $debit_note['from_ledger_id'];
			
			if($tax_in_out==1) // output tax
			{
			creditAccountingLedger($tax_ledger_id,$tax_amount);
			debitAccountingLedger($from_id,$tax_amount);
			}
			else if($tax_in_out==0) // input tax
			{
			debitAccountingLedger($tax_ledger_id,$tax_amount);
			creditAccountingLedger($from_id,$tax_amount);	
			}
		}
		else
		{
			$from_id = $debit_note['from_customer_id'];
			if($tax_in_out==1)
			{
			creditAccountingLedger($tax_ledger_id,$tax_amount);
			debitAccountingCustomer($from_id,$tax_amount);
			}
			else if($tax_in_out==0)
			{
			debitAccountingLedger($tax_ledger_id,$tax_amount);
			creditAccountingCustomer($from_id,$tax_amount);	
			}
		}	
	}
		}
		return true;
	}	
	return false;
	
}

function insertTaxToDebitNote($debit_note_id,$debit_note_item_id,$tax_group,$net_amount,$nonstock=false) // non stock if true, debit_note_item_id is debit_note_non_stock_id
{
	$vehicle_id = "NULL";
	if($nonstock)
	{
	$debit_note_non_stock_id = $debit_note_item_id;
	$debit_note_item_id = "NULL";
	}
	else
	$debit_note_non_stock_id = "NULL";
	
	if(checkForNumeric($debit_note_id,$tax_group,$net_amount) && $tax_group>0 && (checkForNumeric($debit_note_item_id) || checkForNumeric($debit_note_non_stock_id)))
	{
		$taxes = listTaxsFromTaxGroupId($tax_group);
		
		foreach($taxes as $tax)
		{
		
		$tax_id = $tax['tax_id'];
		$tax_percent = $tax['tax_percent'];
		$tax_ledger_id = getTaxLedgerForTaxID($tax_id);
		$tax_amount = $net_amount * ($tax_percent/100);
		$tax= getTaxByID($tax_id);
		$tax_in_out = $tax['in_out'];
		
		if($tax_in_out==2) // if include in debit_note add tax amount in the debit_note making tax amount 0
		{	
		$tax_amount=0;
		}
		
		$sql="INSERT INTO edms_ac_debit_note_tax (debit_note_id, vehicle_id, debit_note_item_id, debit_note_non_stock_id, tax_group_id, tax_amount, tax_id) VALUES ($debit_note_id,$vehicle_id,$debit_note_item_id,$debit_note_non_stock_id,$tax_group,$tax_amount,$tax_id)";
		dbQuery($sql);	
		
		
	$debit_note = getDebitNoteById($debit_note_id);
	
	 $oc_id=$debit_note['oc_id'];
	
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
	}
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
	if(strtotime($debit_note['trans_date'])>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
		if(is_numeric($debit_note['from_ledger_id']))
		{
			$from_id = $debit_note['from_ledger_id'];
			
			if($tax_in_out==1) // output tax
			{
			creditAccountingLedger($tax_ledger_id,$tax_amount);
			debitAccountingLedger($from_id,$tax_amount);
			}
			else if($tax_in_out==0) // input tax
			{
			debitAccountingLedger($tax_ledger_id,$tax_amount);
			creditAccountingLedger($from_id,$tax_amount);	
			}
		}
		else
		{
			$from_id = $debit_note['from_customer_id'];
			if($tax_in_out==1)
			{
			creditAccountingLedger($tax_ledger_id,$tax_amount);
			debitAccountingCustomer($from_id,$tax_amount);
			}
			else if($tax_in_out==0)
			{
			debitAccountingLedger($tax_ledger_id,$tax_amount);
			creditAccountingCustomer($from_id,$tax_amount);	
			}
		}	
	}
		}
		return true;
	}	
	return false;
	
}

function deleteTaxForDebitNote($debit_note_id)
{
	
	if(checkForNumeric($debit_note_id))
	{
		$taxes = getTaxForVehiclePurcahseId($debit_note_id);
		
		$sql="DELETE FROM edms_ac_debit_note_tax WHERE debit_note_id = $debit_note_id";
		dbQuery($sql);	
		
	$debit_note = getDebitNoteById($debit_note_id);
	
	 $oc_id=$debit_note['oc_id'];
	
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
	}
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
	if(strtotime($debit_note['trans_date'])>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
	foreach($taxes as $tax)
	{		
		$tax_in_out = $tax['in_out'];
		$tax_id = $tax['tax_id'];
		$tax_ledger_id = getTaxLedgerForTaxID($tax_id); 
		$tax_amount = $tax['tax_amount'];	
		if(is_numeric($debit_note['from_ledger_id']) || is_numeric($debit_note['from_customer_id']))
		{
			$from_id = $debit_note['from_ledger_id'];
			
			
			if($tax_in_out==1) // output tax
				{
				debitAccountingLedger($tax_ledger_id,$tax_amount);
				creditAccountingLedger($from_id,$tax_amount);
				}
				else if($tax_in_out==0) // input tax
				{
				creditAccountingLedger($tax_ledger_id,$tax_amount);
				debitAccountingLedger($from_id,$tax_amount);	
				}
		}
		else
		{
			$from_id = $debit_note['from_customer_id'];
			if($tax_in_out==1)
			{
			debitAccountingLedger($tax_ledger_id,$tax_amount);
			creditAccountingCustomer($from_id,$tax_amount);
			}
			else if($tax_in_out==0)
			{
			creditAccountingLedger($tax_ledger_id,$tax_amount);
			debitAccountingCustomer($from_id,$tax_amount);	
			}
		}
	}
			}
	return true;
	
		}
		
	return false;
	
}


function deleteTaxForVehicleDebitNote($vehicle_id,$debit_note) // $debit_note is the whole debit_note array
{
	
	if(checkForNumeric($vehicle_id))
	{
		$taxes = getTaxDetailsForVehicleId($vehicle_id);
		
		$sql="DELETE FROM edms_ac_debit_note_tax WHERE vehicle_id = $vehicle_id";
		dbQuery($sql);	
		
	
	
	 $oc_id=$debit_note['oc_id'];
	
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
	}
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
	if(strtotime($debit_note['trans_date'])>=strtotime($ac_starting_date)) // if transactio was done after books starting date
	{
		
	foreach($taxes as $tax)
	{		
		$tax_in_out = $tax['in_out'];
		$tax_id = $tax['tax_id'];
		$tax_ledger_id = getTaxLedgerForTaxID($tax_id); 
		$tax_amount = $tax['tax_amount'];	
		if(is_numeric($debit_note['from_ledger_id']) || is_numeric($debit_note['from_customer_id']))
		{
			$from_id = $debit_note['from_ledger_id'];
			
		
			if($tax_in_out==1) // output tax
				{
				debitAccountingLedger($tax_ledger_id,$tax_amount);
				creditAccountingLedger($from_id,$tax_amount);
				}
				else if($tax_in_out==0) // input tax
				{
				creditAccountingLedger($tax_ledger_id,$tax_amount);
				debitAccountingLedger($from_id,$tax_amount);	
				}
		}
		else
		{
			$from_id = $debit_note['from_customer_id'];
			if($tax_in_out==1)
			{
			debitAccountingLedger($tax_ledger_id,$tax_amount);
			creditAccountingCustomer($from_id,$tax_amount);
			}
			else if($tax_in_out==0)
			{
			creditAccountingLedger($tax_ledger_id,$tax_amount);
			debitAccountingCustomer($from_id,$tax_amount);	
			}
		}
	}
			}
	return true;
	
		}
		
	return false;
	
}

function getTaxForVehicleDebitNoteId($debit_note_id)
{
	if(checkForNumeric($debit_note_id))
	{
		$sql="SELECT debit_note_id, vehicle_id,  edms_tax_grp.tax_group_id, tax_group_name, SUM(tax_amount) as tax_amount, edms_tax.tax_id, CONCAT(IF(edms_tax.in_out>0,'OUT','IN'), ' ', tax_name) as tax_name_in_out, tax_name, edms_tax.in_out FROM edms_ac_debit_note_tax, edms_tax_grp, edms_tax WHERE edms_tax_grp.tax_group_id = edms_ac_debit_note_tax.tax_group_id AND edms_tax.tax_id = edms_ac_debit_note_tax.tax_id AND edms_ac_debit_note_tax.debit_note_id = $debit_note_id GROUP BY tax_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		
		}
	return false;
}

function getTaxDetailsForVehicleIdDebitNote($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT debit_note_id, vehicle_id,  edms_tax_grp.tax_group_id, tax_group_name, SUM(tax_amount) as tax_amount, edms_tax.tax_id, CONCAT(IF(edms_tax.in_out>0,'OUT','IN'), ' ', tax_name) as tax_name_in_out, tax_name, edms_tax.in_out FROM edms_ac_debit_note_tax, edms_tax_grp, edms_tax WHERE edms_tax_grp.tax_group_id = edms_ac_debit_note_tax.tax_group_id AND edms_tax.tax_id = edms_ac_debit_note_tax.tax_id AND edms_ac_debit_note_tax.vehicle_id = $vehicle_id GROUP BY tax_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		
		}
	return false;
}

function getTaxGroupForVehicleIdDebitNote($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT edms_ac_debit_note_tax.tax_group_id, in_out FROM edms_ac_debit_note_tax, edms_tax_grp WHERE edms_ac_debit_note_tax.tax_group_id = edms_tax_grp.tax_group_id AND vehicle_id = $vehicle_id GROUP BY vehicle_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false;
		
		}
	return false;
}

function getDebitNotesForLedgerIdMonthWiseBetweenDates($from_ledger,$from=NULL,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
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
	
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=2 && $head_type!=4))
	{
	$sql="SELECT edms_ac_debit_note.debit_note_id,SUM(amount) ,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_debit_note WHERE  ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==3)  	  
	$sql=$sql." to_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
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
		
function getTotalDebitNoteAmountForLedgerIdUptoDate($from_ledger,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
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
	
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=2 && $head_type!=4))
	{
	$sql="SELECT edms_ac_debit_note.debit_note_id,SUM(amount),from_ledger_id,to_ledger_id,from_customer_id
			  FROM edms_ac_debit_note WHERE  ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==3)  	  
	$sql=$sql." to_ledger_id=$from_ledger GROUP BY to_ledger_id";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
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

function getDebitNotesForLedgerIdForMonth($from_ledger,$month_id,$year,$from=NULL,$to=NULL)
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
	
	if((checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=2 && $head_type!=4)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_debit_note.debit_note_id, amount ";
	if($head_type==1 || $head_type==0 || !isset($head_type))
	$sql=$sql." + IF(total_tax IS NOT NULL,total_tax,0) AS amount ";
	$sql=$sql." ,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_debit_note LEFT JOIN (SELECT f.debit_note_id, SUM( tax_amount ) AS total_tax, e.tax_group_id, e.in_out
FROM edms_ac_debit_note_tax f, edms_tax_grp e, edms_rel_tax_grp_tax g
WHERE f.tax_id = g.tax_id
AND g.tax_group_id = e.tax_group_id
GROUP BY f.debit_note_id
)h ON edms_ac_debit_note.debit_note_id = h.debit_note_id WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==3)  	  
	$sql=$sql." to_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
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

function getTotalDebitNoteForLedgerIdForMonth($from_ledger,$month_id,$year,$from=NULL,$to=NULL)
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
	
	if((checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=2 && $head_type!=4)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_debit_note.debit_note_id,SUM(amount) as total_amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_debit_note WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==3)  	  
	$sql=$sql." to_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
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

function getDebitNotesForLedgerIdBetweenDates($from_ledger,$from=NULL,$to=NULL)
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
	
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=2 && $head_type!=4))
	{
	$sql="SELECT edms_ac_debit_note.debit_note_id, amount ";
	if($head_type==1 || $head_type==0 || !isset($head_type))
	$sql=$sql." + IF(total_tax IS NOT NULL,total_tax,0) AS amount ";
	$sql=$sql." ,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
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
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==3)  	  
	$sql=$sql." to_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
}	

function getTotalDebitNoteForItemIdForMonth($item_id,$month_id,$year,$from=NULL,$to=NULL,$model=false) // model = true, item_id = model_id
{

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
	
	if(checkForNumeric($item_id) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT SUM(net_amount) as total_amount, SUM(net_amount)/SUM(quantity) as avg_rate, SUM(quantity) as quantity, CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year
			  FROM edms_ac_debit_note_item, edms_ac_debit_note WHERE edms_ac_debit_note_item.debit_note_id = edms_ac_debit_note.debit_note_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(!$model)	  
	$sql=$sql." item_id=$item_id ";
	else if($model)
	$sql=$sql." model_id=$item_id ";
	$sql=$sql." AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY month_year"; 
			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray[0];
	else
	return 0; 	
	}
	return 0;
}	

function getDebitNotesForItemIdBetweenDates($item_id,$from=NULL,$to=NULL,$model=false)
{
	
	
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
	
	if(checkForNumeric($item_id))
	{
	$sql="SELECT edms_ac_debit_note_item.debit_note_item_id, edms_ac_debit_note.debit_note_id, edms_ac_debit_note_item.amount , net_amount, rate, quantity, discount, from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year
			  FROM edms_ac_debit_note_item INNER JOIN  edms_ac_debit_note ON edms_ac_debit_note.debit_note_id = edms_ac_debit_note_item.debit_note_id
WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(!$model)	  
	$sql=$sql." item_id=$item_id ";
	else if($model)
	$sql=$sql." model_id=$item_id "; 
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
}	

function getDebitNotesForItemIdForMonth($item_id,$month_id,$year,$from=NULL,$to=NULL,$model=false)
{
	
	
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
	
	if(checkForNumeric($item_id,$month_id,$year))
	{
	$sql="SELECT edms_ac_debit_note_item.debit_note_item_id, edms_ac_debit_note.debit_note_id, edms_ac_debit_note_item.amount , net_amount, rate, quantity, discount, from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year
			  FROM edms_ac_debit_note_item INNER JOIN  edms_ac_debit_note ON edms_ac_debit_note.debit_note_id = edms_ac_debit_note_item.debit_note_id
WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(!$model)	  
	$sql=$sql." item_id=$item_id ";
	else if($model)
	$sql=$sql." model_id=$item_id ";
	
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

function getDebitNotesForItemIdMonthWiseBetweenDates($item_id,$from=NULL,$to=NULL,$model=false) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{

	
	
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
	
	if(checkForNumeric($item_id))
	{
	

	$sql="SELECT SUM(edms_ac_debit_note_item.amount) as total_gross_amount , SUM(net_amount) as total_amount, SUM(net_amount)/SUM(quantity) as avg_rate, SUM(quantity) as quantity,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year
			  FROM edms_ac_debit_note_item INNER JOIN  edms_ac_debit_note ON edms_ac_debit_note.debit_note_id = edms_ac_debit_note_item.debit_note_id
WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(!$model)	  
	$sql=$sql." item_id=$item_id ";
	else if($model)
	$sql=$sql." model_id=$item_id ";
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
	
function getTotalDebitNoteForItemIdUptoDate($item_id,$to=NULL,$model=false,$godown_id=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
	
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	
	if(checkForNumeric($item_id))
	{
	$sql="SELECT edms_ac_debit_note_item.debit_note_item_id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate
			  FROM edms_ac_debit_note_item, edms_ac_debit_note WHERE edms_ac_debit_note_item.debit_note_id = edms_ac_debit_note.debit_note_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";	  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0];
	else
	return 0; 	
	}
	return 0;
	}	


?>