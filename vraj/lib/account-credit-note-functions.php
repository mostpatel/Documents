<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("account-head-functions.php");
require_once("account-period-functions.php");
require_once("account-ledger-functions.php");
require_once("inventory-item-barcode-functions.php");
require_once("account-functions.php");
require_once("customer-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");

function getAllCreditNotes()
{
	$sql="SELECT credit_note_id,credit_note_ref_type,credit_note_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,created_by,last_updated_by,date_added,date_modified, retail_tax,vch_no
			  FROM edms_ac_credit_note";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
	}

function getCreditNoteForEMIPaymentId($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT credit_note_id,credit_note_ref_type,credit_note_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,created_by,last_updated_by,date_added,date_modified, retail_tax,vch_no
			  FROM edms_ac_credit_note
			  WHERE auto_id=$id AND auto_rasid_type=2";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 
		
		}
	}	

function getCreditNoteForFileClosureId($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT credit_note_id,credit_note_ref_type,credit_note_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,created_by,last_updated_by,date_added,date_modified, retail_tax,vch_no
			  FROM edms_ac_credit_note
			  WHERE auto_id=$id AND auto_rasid_type=4";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 
		
		}
	}		

function getCreditNoteForPenaltyId($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT credit_note_id,credit_note_ref_type,credit_note_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,created_by,last_updated_by,date_added,date_modified, retail_tax,vch_no
			  FROM edms_ac_credit_note
			  WHERE auto_id=$id AND auto_rasid_type=3";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 
		
		}
	}		
	
function getCreditNoteById($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT credit_note_id,credit_note_ref_type,credit_note_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,created_by,last_updated_by,date_added,date_modified, retail_tax,vch_no
			  FROM edms_ac_credit_note
			  WHERE credit_note_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return "error"; 
		
		}
	}
	
function getCreditNoteWithInventoryById($id)
{
	if(checkForNumeric($id))
	{
		$sale = getCreditNoteById($id);
		if($sale!="error")
		{
				
			
		}
		else return "error";
	}
	
	
}	

function insertCreditNote($amount,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$auto_rasid_type=0,$auto_id=0,$credit_note_ref="NA",$credit_note_ref_type=0,$retail_tax=0,$oc_id=NULL,$vch_no=NULL) // $to_ledger should start with C for customer or L for ledger to_ledger: credit, from_ledger: debit
{
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$current_company=getCompanyForLedger($to_ledger);
				if($current_company[1]==0)
				{
					if(!is_numeric($oc_id))
					$oc_id = $current_company[0];
					
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				}
				
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($to_customer);
		$customer_id=$customer['customer_id'];
		if(!is_numeric($oc_id))
		$oc_id=getCompanyIdFromCustomerId($customer_id);
		
				
		$accounts_settings=getAccountsSettingsForOC($oc_id);
				
		
		}	
		
	if( (!(checkForNumeric($to_ledger) || checkForNumeric($to_customer))) || !checkForNumeric($oc_id) )
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
	$vch_counter = getTransCounterForOCID($oc_id,3);
	if(!checkForNumeric($vch_no))
	{
	$vch_no = $vch_counter;	
	}
	if(checkForNumeric($amount,$from_ledger,$admin_id,$vch_no) && $from_ledger>0 && validateForNull($trans_date,$delivery_date))
	{
		
			$sql="INSERT INTO edms_ac_credit_note (credit_note_ref_type,credit_note_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,remarks,created_by,last_updated_by,date_added,date_modified, retail_tax,vch_no)
			VALUES ($credit_note_ref_type,'$credit_note_ref',$amount,$from_ledger,$to_ledger,$to_customer,$oc_id,$auto_rasid_type,$auto_id,'$trans_date','$delivery_date','$remarks',$admin_id,$admin_id,NOW(),NOW(), $retail_tax,'$vch_no')";
			
			$result=dbQuery($sql);
			$credit_note_id = dbInsertId();
			$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if($vch_counter==$vch_no)
			incrementTransCounterForOCID($oc_id,3);
			
			if(strtotime($trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($to_ledger) && $to_ledger>0)
				{
					debitAccountingLedger($to_ledger,$amount);
					creditAccountingLedger($from_ledger,$amount);
				}
				else if(checkForNumeric($to_customer) && $to_customer>0)
				{
					
					creditAccountingLedger($from_ledger,$amount);
					debitAccountingCustomer($to_customer,$amount);
				}
			}	
			
			
			return $credit_note_id;
	}
	return "error";	
}

function deleteCreditNote($id)
{
	if(checkForNumeric($id))
	{
		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$old_payment=getCreditNoteById($id);
		$old_amount=$old_payment['amount'];
		$old_trans_date=$old_payment['trans_date'];
		$old_to_ledger_id=$old_payment['to_ledger_id'];
		$old_to_customer_id=$old_payment['to_customer_id'];
		$old_from_ledger_id=$old_payment['from_ledger_id'];
		
		
	    $oc_id=$old_payment['oc_id'];
		if(USE_BARCODE==1)
		{
			$used_barcode_tansactions = getUsedBarcodeForTransactionItemWise($id,3);
			$used_barcode_tansactions_item_id_array = array_keys($used_barcode_tansactions);
			
			if(is_array($used_barcode_tansactions) && count($used_barcode_tansactions)>0 && checkForNumeric($used_barcode_tansactions_item_id_array[0]))
			return "barcode_in_use_error";
		}
	 if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
		}
		deleteTaxForCreditNote($id);
		$sql="DELETE FROM edms_ac_credit_note where credit_note_id=$id";
		dbQuery($sql);
		
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
					
					creditAccountingCustomer($old_to_customer_id,$old_amount);
					debitAccountingLedger($old_from_ledger_id,$old_amount);
				}
			}	
		
		return "success";
		}
		return "error";
	}

function updateCreditNote($id,$amount,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$credit_note_ref="NA",$credit_note_ref_type=0, $retail_tax=0,$oc_id=NULL,$vch_no=NULL) // $to_ledger should start with C for customer or L for ledger
{
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	    $old_payment=getCreditNoteById($id);
		$old_amount=$old_payment['amount'];
		$old_trans_date=$old_payment['trans_date'];
		$old_to_ledger_id=$old_payment['to_ledger_id'];
		$old_to_customer_id=$old_payment['to_customer_id'];
		$old_from_ledger_id=$old_payment['from_ledger_id'];
	if(!is_numeric($oc_id))	
	$oc_id=$old_payment['oc_id'];
	
	if(!checkForNumeric($retail_tax))
	$retail_tax=0;
	
	 if(checkForNumeric($oc_id) && validateForNull($oc_id))
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
		
	if( (!(checkForNumeric($to_ledger) || checkForNumeric($to_customer))) || !checkForNumeric($oc_id) )
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
	
		
	if(checkForNumeric($amount,$from_ledger,$admin_id,$id) && validateForNull($trans_date,$delivery_date))
	{
			
			$sql="UPDATE edms_ac_credit_note SET credit_note_ref = '$credit_note_ref', credit_note_ref_type = $credit_note_ref_type, amount=$amount, from_ledger_id=$from_ledger, to_ledger_id=$to_ledger, to_customer_id=$to_customer, trans_date='$trans_date', delivery_date='$delivery_date', remarks='$remarks', last_updated_by=$admin_id, date_modified=NOW(), retail_tax = $retail_tax ";
			if(checkForNumeric($vch_no))
			$sql=$sql." ,vch_no='$vch_no'  ";
			$sql=$sql."
			WHERE credit_note_id=$id";

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
					
					creditAccountingCustomer($old_to_customer_id,$old_amount);
					debitAccountingLedger($old_from_ledger_id,$old_amount);
				}
			}	
			
				if(strtotime($trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($to_ledger) && $to_ledger>0)
				{
					debitAccountingLedger($to_ledger,$amount);
					creditAccountingLedger($from_ledger,$amount);
				}
				else if(checkForNumeric($to_customer) && $to_customer>0)
				{
					
					creditAccountingLedger($from_ledger,$amount);
					debitAccountingCustomer($to_customer,$amount);
				}
			}	
			
			return "success";
	}
	return "error";	
	
	}	
	

function insertTaxToVehicleCreditNote($credit_note_id,$vehicle_id,$tax_group,$basic_price)
{
	
	
	if(checkForNumeric($credit_note_id,$vehicle_id,$tax_group) && $tax_group>0)
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
		
		
		
		
		$sql="INSERT INTO edms_ac_credit_note_tax (credit_note_id, vehicle_id,  tax_group_id, tax_amount, tax_id) VALUES ($credit_note_id,$vehicle_id,$tax_group,$tax_amount,$tax_id)";
		dbQuery($sql);	
		
	$credit_note = getCreditNoteById($credit_note_id);
    $oc_id=$credit_note['oc_id'];
	
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
	}
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
	if(strtotime($credit_note['trans_date'])>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
				if(is_numeric($credit_note['to_ledger_id']) || is_numeric($credit_note['to_customer_id']))
				{
					$to_id = $credit_note['to_ledger_id'];
					
					
					if($tax_in_out==1) // output tax
					{
					creditAccountingLedger($tax_ledger_id,$tax_amount);
					debitAccountingLedger($to_id,$tax_amount);
					
					}
					else if($tax_in_out==0) // input tax
					{
					debitAccountingLedger($tax_ledger_id,$tax_amount);
					creditAccountingLedger($to_id,$tax_amount);
					}
				}
				else
				{
					$to_id = $credit_note['to_customer_id'];
					if($tax_in_out==1)
					{
					creditAccountingLedger($tax_ledger_id,$tax_amount);
					debitAccountingCustomer($to_id,$tax_amount);
					}
					else if($tax_in_out==0)
					{
					debitAccountingLedger($tax_ledger_id,$tax_amount);
					creditAccountingCustomer($to_id,$tax_amount);	
					}
				}	
			}
}

		return true;
	}	
	return false;
	
}

function insertTaxToCreditNote($credit_note_id,$sale_item_id,$tax_group,$net_amount,$nonstock=false)
{
	
	$vehicle_id = "NULL";
	if($nonstock)
	{
	$credit_note_non_stock_id = $sale_item_id;
	$sale_item_id = "NULL";
	}
	else
	$credit_note_non_stock_id = "NULL";
	
	if(checkForNumeric($credit_note_id,$tax_group) && $tax_group>0 && (checkForNumeric($sale_item_id) || checkForNumeric($credit_note_non_stock_id)))
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
		
		
		
		
		$sql="INSERT INTO edms_ac_credit_note_tax (credit_note_id, vehicle_id, credit_note_item_id, credit_note_non_stock_id, tax_group_id, tax_amount, tax_id) VALUES ($credit_note_id,$vehicle_id,$sale_item_id,$credit_note_non_stock_id,$tax_group,$tax_amount,$tax_id)";
		dbQuery($sql);	
		
	$credit_note = getCreditNoteById($credit_note_id);
    $oc_id=$credit_note['oc_id'];
	
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
	}
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
	if(strtotime($credit_note['trans_date'])>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
				if(is_numeric($credit_note['to_ledger_id']) || is_numeric($credit_note['to_customer_id']))
				{
					$to_id = $credit_note['to_ledger_id'];
					
					
					if($tax_in_out==1) // output tax
					{
					creditAccountingLedger($tax_ledger_id,$tax_amount);
					debitAccountingLedger($to_id,$tax_amount);
					
					}
					else if($tax_in_out==0) // input tax
					{
					debitAccountingLedger($tax_ledger_id,$tax_amount);
					creditAccountingLedger($to_id,$tax_amount);
					}
				}
				else
				{
					$to_id = $credit_note['to_customer_id'];
					if($tax_in_out==1)
					{
					creditAccountingLedger($tax_ledger_id,$tax_amount);
					debitAccountingCustomer($to_id,$tax_amount);
					}
					else if($tax_in_out==0)
					{
					debitAccountingLedger($tax_ledger_id,$tax_amount);
					creditAccountingCustomer($to_id,$tax_amount);	
					}
				}	
			}
}

		return true;
	}	
	return false;
	
}


function updateTaxToVehicleCreditNote($credit_note_id,$vehicle_id,$tax_group,$basic_price)
{
	
	$result=deleteTaxForCreditNote($credit_note_id);

	if($result)
	{
	insertTaxToVehicleCreditNote($credit_note_id,$vehicle_id,$tax_group,$basic_price);
	return true;
	}
	return false;
}

function deleteTaxForCreditNote($credit_note_id)
{
	
	if(checkForNumeric($credit_note_id))
	{
		$taxes = getTaxForVehicleCreditNoteId($credit_note_id);
		
		$sql="DELETE FROM edms_ac_credit_note_tax WHERE credit_note_id = $credit_note_id";
		dbQuery($sql);	
		
	$credit_note = getCreditNoteById($credit_note_id);
	
	 $oc_id=$credit_note['oc_id'];
	
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
	}
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
	if(strtotime($credit_note['trans_date'])>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
	foreach($taxes as $tax)
	{
		$tax_in_out = $tax['in_out'];
		$tax_id = $tax['tax_id'];
		$tax_ledger_id = getTaxLedgerForTaxID($tax_id); 
		$tax_amount = $tax['tax_amount'];			
		if(is_numeric($credit_note['to_ledger_id']) || is_numeric($credit_note['to_customer_id']))
		{
			$to_id = $credit_note['to_ledger_id'];
			
			
			if($tax_in_out==1) // output tax
			{
			debitAccountingLedger($tax_ledger_id,$tax_amount);
			if(is_numeric($to_id))
			creditAccountingLedger($to_id,$tax_amount);
			
			}
			else if($tax_in_out==0) // input tax
			{
			debitAccountingLedger($tax_ledger_id,$tax_amount);
			if(is_numeric($to_id))
			creditAccountingLedger($to_id,$tax_amount);
			}
		}
		else
		{
			$to_id = $credit_note['to_customer_id'];
			if($tax_in_out==1)
			{
			debitAccountingLedger($tax_ledger_id,$tax_amount);
			creditAccountingCustomer($to_id,$tax_amount);
			}
			else if($tax_in_out==0)
			{
			creditAccountingLedger($tax_ledger_id,$tax_amount);
			debitAccountingCustomer($to_id,$tax_amount);	
			}
		}
	}
			}
	return true;
	
		}
		
	return false;
	
}

function getTaxForVehicleCreditNoteId($credit_note_id)
{
	if(checkForNumeric($credit_note_id))
	{
		$sql="SELECT credit_note_id, vehicle_id,  edms_tax_grp.tax_group_id, tax_group_name, SUM(tax_amount) as tax_amount, edms_tax.tax_id, CONCAT(IF(edms_tax.in_out>0,'OUT','IN'), ' ', tax_name) as tax_name_in_out, tax_name, edms_tax.in_out, tax_percent FROM edms_ac_credit_note_tax, edms_tax_grp, edms_tax WHERE edms_tax_grp.tax_group_id = edms_ac_credit_note_tax.tax_group_id AND edms_tax.tax_id = edms_ac_credit_note_tax.tax_id AND edms_ac_credit_note_tax.credit_note_id = $credit_note_id GROUP BY tax_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		
		}
	return false;
}

function getCreditNotesForLedgerIdMonthWise($to_ledger,$from=NULL,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)) && $head_type!=0 && $head_type!=2 && $head_type!=3)
	{
	$sql="SELECT sale_id,";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
	$sql=$sql."SUM(net_amount) as total_amount";
	else
	$sql=$sql."i.amount as total_amount";
	$sql=$sql.",from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date, trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM ";
			  if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
			  {
			  $sql=$sql."(SELECT edms_ac_credit_note.credit_note_id, edms_ac_credit_note_item.amount, edms_ac_credit_note_item.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_credit_note.created_by, edms_ac_credit_note.last_updated_by, edms_ac_credit_note.date_added, edms_ac_credit_note.date_modified,vch_no FROM edms_ac_credit_note , edms_ac_credit_note_item WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_item.credit_note_id AND edms_ac_credit_note.oc_id = $oc_id AND ";
			  if(isset($from) && validateForNull($from))
				$sql=$sql."trans_date>='$from' 
					  AND ";
				if(isset($to) && validateForNull($to))  
				$sql=$sql."trans_date<='$to'
					  AND ";
				if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  	  
				$sql=$sql." ledger_id=$to_ledger";
				else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
				$sql=$sql." to_ledger_id=$to_ledger";
				else if(!isset($head_type))
				$sql=$sql." to_customer_id=$to_customer";
			  $sql=$sql." UNION ALL SELECT edms_ac_credit_note.credit_note_id, edms_ac_credit_note_nonstock.amount, edms_ac_credit_note_nonstock.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_credit_note.created_by, edms_ac_credit_note.last_updated_by, edms_ac_credit_note.date_added, edms_ac_credit_note.date_modified,vch_no FROM edms_ac_credit_note , edms_ac_credit_note_nonstock WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_nonstock.credit_note_id AND edms_ac_credit_note.oc_id = $oc_id AND ";
			  if(isset($from) && validateForNull($from))
				$sql=$sql."trans_date>='$from' 
					  AND ";
				if(isset($to) && validateForNull($to))  
				$sql=$sql."trans_date<='$to'
					  AND ";
				if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  	  
				$sql=$sql." ledger_id=$to_ledger";
				else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
				$sql=$sql." to_ledger_id=$to_ledger";
				else if(!isset($head_type))
				$sql=$sql." to_customer_id=$to_customer";
				$sql=$sql." )i ";
			  }
			  else
			  $sql=$sql." edms_ac_credit_note AS i ";
			  $sql=$sql." LEFT JOIN (SELECT  SUM( tax_amount ),  AS total_tax
FROM edms_ac_credit_note_tax f
GROUP BY f.credit_note_id
)h ON i.credit_note_id = h.credit_note_id WHERE i.oc_id = $oc_id ";	  
	
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
	
function getTotalCreditNoteAmountForLedgerIdUptoDate($to_ledger,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)) && $head_type!=0 && $head_type!=2 && $head_type!=3)
	{
	$sql="SELECT edms_ac_credit_note.credit_note_id,SUM(net_amount),from_ledger_id,to_ledger_id,to_customer_id
			  FROM edms_ac_credit_note, edms_ac_credit_note_item WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_item.credit_note_id AND oc_id = $oc_id AND
			 ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  	  
	$sql=$sql." ledger_id=$to_ledger GROUP BY ledger_id";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." to_ledger_id=$to_ledger GROUP BY to_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer GROUP BY to_customer_id";			  
	$sql=" UNION ALL SELECT edms_ac_credit_note.credit_note_id,SUM(net_amount),from_ledger_id,to_ledger_id,to_customer_id
			  FROM edms_ac_credit_note, edms_ac_credit_note_nonstock WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_nonstock.credit_note_id AND oc_id = $oc_id AND
			 ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  	  
	$sql=$sql." ledger_id=$to_ledger GROUP BY ledger_id";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
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

function getCreditNotesForLedgerIdForMonth($to_ledger,$month_id,$year,$from=NULL,$to=NULL)
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger) && $head_type!=0 && $head_type!=2 && $head_type!=3)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT i.credit_note_id,  ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
	$sql=$sql." SUM(net_amount) AS amount ";
	if($head_type==1 || $head_type==0 || !isset($head_type))
	$sql=$sql." amount + IF(total_tax IS NOT NULL,total_tax,0) AS amount ";
	$sql=$sql."  ,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM ";
			  if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
			  {
			  $sql=$sql."(SELECT edms_ac_credit_note.credit_note_id, edms_ac_credit_note_item.amount, edms_ac_credit_note_item.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_credit_note.created_by, edms_ac_credit_note.last_updated_by, edms_ac_credit_note.date_added, edms_ac_credit_note.date_modified,vch_no FROM edms_ac_credit_note , edms_ac_credit_note_item WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_item.credit_note_id AND edms_ac_credit_note.oc_id = $oc_id AND ";
			  if(isset($from) && validateForNull($from))
				$sql=$sql."trans_date>='$from' 
					  AND ";
				if(isset($to) && validateForNull($to))  
				$sql=$sql."trans_date<='$to'
					  AND ";
				if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  	  
				$sql=$sql." ledger_id=$to_ledger";
				else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
				$sql=$sql." to_ledger_id=$to_ledger";
				else if(!isset($head_type))
				$sql=$sql." to_customer_id=$to_customer";
			  $sql=$sql." UNION ALL SELECT edms_ac_credit_note.credit_note_id, edms_ac_credit_note_nonstock.amount, edms_ac_credit_note_nonstock.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_credit_note.created_by, edms_ac_credit_note.last_updated_by, edms_ac_credit_note.date_added, edms_ac_credit_note.date_modified,vch_no FROM edms_ac_credit_note , edms_ac_credit_note_nonstock WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_nonstock.credit_note_id AND edms_ac_credit_note.oc_id = $oc_id AND ";
			  if(isset($from) && validateForNull($from))
				$sql=$sql."trans_date>='$from' 
					  AND ";
				if(isset($to) && validateForNull($to))  
				$sql=$sql."trans_date<='$to'
					  AND ";
				if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  	  
				$sql=$sql." ledger_id=$to_ledger";
				else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
				$sql=$sql." to_ledger_id=$to_ledger";
				else if(!isset($head_type))
				$sql=$sql." to_customer_id=$to_customer";
				$sql=$sql." )i ";
			  }
			  else
			  $sql=$sql." edms_ac_credit_note AS i ";
			  $sql=$sql."  LEFT JOIN (SELECT f.credit_note_id, SUM( tax_amount ) AS total_tax
FROM edms_ac_credit_note_tax f
GROUP BY f.credit_note_id
)h ON i.credit_note_id = h.credit_note_id WHERE i.oc_id = $oc_id  AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY i.credit_note_id";	
  			  
	$result=dbQuery($sql);
	
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
	}	
	
		

function getCreditNotesForLedgerIdBetweenDates($to_ledger,$from=NULL,$to=NULL)
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
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger) && $head_type!=0 && $head_type!=2 && $head_type!=3)  || $to_ledger==-1)
	{
	$sql="SELECT i.credit_note_id, ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
	$sql=$sql." SUM(net_amount) AS amount ";
	if($head_type==1 || $head_type==0 || !isset($head_type))
	$sql=$sql." amount + IF(total_tax IS NOT NULL,total_tax,0) AS amount ";
	$sql=$sql.",from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified, remarks,vch_no
			  FROM ";
			  if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
			  {
			  $sql=$sql."(SELECT edms_ac_credit_note.credit_note_id, edms_ac_credit_note_item.amount, edms_ac_credit_note_item.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_credit_note.created_by, edms_ac_credit_note.last_updated_by, edms_ac_credit_note.date_added, edms_ac_credit_note.date_modified,vch_no FROM edms_ac_credit_note , edms_ac_credit_note_item WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_item.credit_note_id AND edms_ac_credit_note.oc_id = $oc_id AND ";
			  if(isset($from) && validateForNull($from))
				$sql=$sql."trans_date>='$from' 
					  AND ";
				if(isset($to) && validateForNull($to))  
				$sql=$sql."trans_date<='$to'
					  AND ";
					  if($to_ledger!=-1)
	{	
				if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  	  
				$sql=$sql." ledger_id=$to_ledger";
				else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
				$sql=$sql." to_ledger_id=$to_ledger";
				else if(!isset($head_type))
				$sql=$sql." to_customer_id=$to_customer";
	}
		else
	{
	if(checkForNumeric($oc_id))
	$sql=$sql." oc_id IN ( ".$oc_id.")";	  
	} 
			  $sql=$sql." UNION ALL SELECT edms_ac_credit_note.credit_note_id, edms_ac_credit_note_nonstock.amount, edms_ac_credit_note_nonstock.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_credit_note.created_by, edms_ac_credit_note.last_updated_by, edms_ac_credit_note.date_added, edms_ac_credit_note.date_modified,vch_no FROM edms_ac_credit_note , edms_ac_credit_note_nonstock WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_nonstock.credit_note_id AND edms_ac_credit_note.oc_id = $oc_id AND ";
			  if(isset($from) && validateForNull($from))
				$sql=$sql."trans_date>='$from' 
					  AND ";
				if(isset($to) && validateForNull($to))  
				$sql=$sql."trans_date<='$to'
					  AND ";
					  if($to_ledger!=-1)
	{	
				if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  	  
				$sql=$sql." ledger_id=$to_ledger";
				else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
				$sql=$sql." to_ledger_id=$to_ledger";
				else if(!isset($head_type))
				$sql=$sql." to_customer_id=$to_customer";
	}
		else
	{
	if(checkForNumeric($oc_id))
	$sql=$sql." oc_id IN ( ".$oc_id.")";	  
	} 
				$sql=$sql." )i ";
			  }
			  else
			  $sql=$sql." edms_ac_credit_note AS i ";
			  $sql=$sql." LEFT JOIN (SELECT f.credit_note_id, SUM( tax_amount ) AS total_tax
FROM edms_ac_credit_note_tax f
GROUP BY f.credit_note_id
)h ON i.credit_note_id = h.credit_note_id WHERE i.oc_id = $oc_id ";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
	}

function getTotalCreditNoteForLedgerIdForMonth($to_ledger,$month_id,$year,$from=NULL,$to=NULL)
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger) && $head_type!=0 && $head_type!=2 && $head_type!=3)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT i.credit_note_id,";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
	$sql=$sql."SUM(net_amount) as total_amount";
	else
	$sql=$sql."i.amount as total_amount";
	$sql=$sql.",from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM ";
			  if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
			  {
			  $sql=$sql."(SELECT edms_ac_credit_note.credit_note_id, edms_ac_credit_note_item.amount, edms_ac_credit_note_item.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_credit_note.created_by, edms_ac_credit_note.last_updated_by, edms_ac_credit_note.date_added, edms_ac_credit_note.date_modified,vch_no FROM edms_ac_credit_note , edms_ac_credit_note_item WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_item.credit_note_id AND edms_ac_credit_note.oc_id = $oc_id AND ";
			  if(isset($from) && validateForNull($from))
				$sql=$sql."trans_date>='$from' 
					  AND ";
				if(isset($to) && validateForNull($to))  
				$sql=$sql."trans_date<='$to'
					  AND ";
				if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  	  
				$sql=$sql." ledger_id=$to_ledger";
				else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
				$sql=$sql." to_ledger_id=$to_ledger";
				else if(!isset($head_type))
				$sql=$sql." to_customer_id=$to_customer";
			  $sql=$sql." UNION ALL SELECT edms_ac_credit_note.credit_note_id, edms_ac_credit_note_nonstock.amount, edms_ac_credit_note_nonstock.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_credit_note.created_by, edms_ac_credit_note.last_updated_by, edms_ac_credit_note.date_added, edms_ac_credit_note.date_modified,vch_no FROM edms_ac_credit_note , edms_ac_credit_note_nonstock WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_nonstock.credit_note_id AND edms_ac_credit_note.oc_id = $oc_id AND ";
			  if(isset($from) && validateForNull($from))
				$sql=$sql."trans_date>='$from' 
					  AND ";
				if(isset($to) && validateForNull($to))  
				$sql=$sql."trans_date<='$to'
					  AND ";
				if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  	  
				$sql=$sql." ledger_id=$to_ledger";
				else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
				$sql=$sql." to_ledger_id=$to_ledger";
				else if(!isset($head_type))
				$sql=$sql." to_customer_id=$to_customer";
				$sql=$sql." )i ";
			  }
			  else
			  $sql=$sql." edms_ac_credit_note AS i ";
			  $sql=$sql."  LEFT JOIN (SELECT f.credit_note_id, SUM( tax_amount ) AS total_tax
FROM edms_ac_credit_note_tax f
GROUP BY f.credit_note_id
)h ON i.credit_note_id = h.credit_note_id WHERE i.oc_id = $oc_id AND ";
	
	
	$sql=$sql."  DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY month_year";	  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
}	
	
function getTotalCreditNoteForItemIdForMonth($item_id,$month_id,$year,$from=NULL,$to=NULL,$model=false)
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($item_id) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT SUM(net_amount) as total_amount, SUM(net_amount)/SUM(quantity) as avg_rate, SUM(quantity) as quantity, CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year
			  FROM edms_ac_credit_note, edms_ac_credit_note_item WHERE edms_ac_credit_note.oc_id = $oc_id AND edms_ac_credit_note.credit_note_id = edms_ac_credit_note_item.credit_note_id AND ";	  
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
	$sql=$sql."  AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY month_year";	  			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0];
	else
	return 0; 	
	}
	return 0;
	}						

function getCreditNotesForItemIdMonthWise($item_id,$from=NULL,$to=NULL,$model=false) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($item_id))
	{
	$sql="SELECT SUM(edms_ac_credit_note_item.amount) as total_gross_amount , SUM(net_amount) as total_amount, SUM(net_amount)/SUM(quantity) as avg_rate, SUM(quantity) as quantity,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,vch_no
			  FROM edms_ac_credit_note_item INNER JOIN  edms_ac_credit_note ON edms_ac_credit_note.credit_note_id = edms_ac_credit_note_item.credit_note_id
WHERE edms_ac_credit_note.oc_id = $oc_id AND ";
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
		
function getTotalCreditNoteForItemIdUptoDate($item_id,$to=NULL,$model=false,$godown_id=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($item_id))
	{
	$sql="SELECT edms_ac_credit_note_item.credit_note_item_id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate
			  FROM edms_ac_credit_note_item, edms_ac_credit_note WHERE edms_ac_credit_note.oc_id = $oc_id AND edms_ac_credit_note_item.credit_note_id = edms_ac_credit_note.credit_note_id AND ";
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

function getCreditNotesForItemIdForMonth($item_id,$month_id,$year,$from=NULL,$to=NULL,$model=false)
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($item_id) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_credit_note_item.credit_note_item_id, edms_ac_credit_note.credit_note_id, edms_ac_credit_note_item.amount , net_amount, rate, quantity, discount, from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,vch_no
			  FROM edms_ac_credit_note_item INNER JOIN  edms_ac_credit_note ON edms_ac_credit_note.credit_note_id = edms_ac_credit_note_item.credit_note_id
WHERE edms_ac_credit_note.oc_id = $oc_id AND ";
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
	
		

function getCreditNotesForItemIdBetweenDates($item_id,$from=NULL,$to=NULL,$model=false)
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($item_id))
	{
	$sql="SELECT edms_ac_credit_note_item.credit_note_item_id, edms_ac_credit_note.credit_note_id, edms_ac_credit_note_item.amount , net_amount, rate, quantity, discount, from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,vch_no
			  FROM edms_ac_credit_note_item INNER JOIN  edms_ac_credit_note ON edms_ac_credit_note.credit_note_id = edms_ac_credit_note_item.credit_note_id
WHERE edms_ac_credit_note.oc_id = $oc_id AND ";
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
?>