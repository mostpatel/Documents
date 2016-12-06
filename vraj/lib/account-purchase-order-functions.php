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

function getAllPurchaseOrders($from=NULL,$to=NULL)
{
	
	if(isset($from) && validateForNull($from))
    {
	$from = str_replace('/', '-', $from);
	$from = date('Y-m-d',strtotime($from));
	}
	
	if(isset($to) && validateForNull($to))
	{
	$to = str_replace('/', '-', $to);
	$to=date('Y-m-d',strtotime($to));
	}
	
	$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
	$sql="SELECT purchase_order_id,purchase_order_ref_type,purchase_order_ref,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date, delivery_date,remarks,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_purchase_order WHERE oc_id = $our_company_id";
			  if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to' ";	 
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
}

function getRecentlyAddedPurchaseOrders()
{
	$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
	$sql="SELECT purchase_order_id,purchase_order_ref_type,purchase_order_ref,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date, delivery_date,remarks,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_purchase_order WHERE  oc_id = $our_company_id ORDER BY date_added DESC LIMIT 0,25";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
}


function getAllPurchaseOrdersByType($type_id=NULL)
{
	
	$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
	$sql="SELECT purchase_order_id,purchase_order_ref_type,purchase_order_ref,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date, delivery_date,remarks,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_purchase_order WHERE oc_id = $our_company_id ";
	if(validateForNull($type_id) && checkForNumeric($type_id) && $type_id>=0)
	$sql=$sql." AND auto_rasid_type = $type_id";		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return false; 
}

function getPurchaseOrderById($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT purchase_order_id,purchase_order_ref_type,purchase_order_ref,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date, delivery_date,remarks,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_purchase_order
			  WHERE purchase_order_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return "error"; 
		
		}
	}
		

function addPurchaseOrder($amount,$trans_date, $delivery_date,$to_ledger,$from_ledger,$remarks,$auto_rasid_type=0,$auto_id=0,$purchase_order_ref="NA",$purchase_order_ref_type=0,$oc_id=NULL) //$from_ledger should start with C for customer or L for ledger, from_ledger: debit and to_ledger: credit
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
		$accounts_settings=getAccountsSettingsForOC($oc_id);
		}	
		
	if( (!(checkForNumeric($from_ledger) || checkForNumeric($from_customer))) || !checkForNumeric($oc_id)) // check for proper ledger and customer id, agency or oc_id
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
	
	if(!validateForNull($delivery_date))
	$delivery_date = $trans_date;
	if(strtotime($trans_date)<strtotime($ac_starting_date)) // payment date should be greater than books starting date
	{
		return "date_error";
	}	
	if(!checkForNumeric($to_ledger) || $to_ledger<1)
	$to_ledger="NULL";
	if(checkForNumeric($amount,$admin_id)  && validateForNull($trans_date,$delivery_date))
	{
			
			$sql="INSERT INTO edms_ac_purchase_order (purchase_order_ref_type,purchase_order_ref,amount,from_ledger_id,from_customer_id,to_ledger_id,oc_id,auto_rasid_type,auto_id,trans_date, delivery_date,remarks,created_by,last_updated_by,date_added,date_modified)
			VALUES ($purchase_order_ref_type,'$purchase_order_ref',$amount,$from_ledger,$from_customer,$to_ledger,$oc_id,$auto_rasid_type,$auto_id,'$trans_date','$delivery_date','$remarks',$admin_id,$admin_id,NOW(),NOW())";
			
			$result=dbQuery($sql);
			$purchase_order_id=dbInsertId();
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
			
			return $purchase_order_id;
	}
	return "error";	
}

function removePurchaseOrder($id)
{
	if(checkForNumeric($id))
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$old_payment=getPurchaseOrderById($id); // get the payment details
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
		deleteTaxForPurchaseOrder($id);
		
		$sql="DELETE FROM edms_ac_purchase_order where purchase_order_id=$id";
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
	
function updatePurchaseOrder($id,$amount,$trans_date, $delivery_date,$to_ledger,$from_ledger,$remarks,$purchase_order_ref="NA",$purchase_order_ref_type=0,$oc_id=NULL)
{
	
	if(!checkForNumeric($purchase_order_ref_type))
	$purchase_order_ref_type=0;
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	$old_payment=getPurchaseOrderById($id);
	
	$old_amount=$old_payment['amount'];
	$old_trans_date=$old_payment['trans_date'];
	$old_from_ledger_id=$old_payment['from_ledger_id'];
	$old_from_customer_id=$old_payment['from_customer_id'];
	$old_to_ledger_id=$old_payment['to_ledger_id'];
	if(!is_numeric($oc_id))
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
	
		
	if(!checkForNumeric($to_ledger) || $to_ledger<1)
	$to_ledger="NULL";	
	
	if(!validateForNull($delivery_date))
	$delivery_date = $trans_date;
	
	if(checkForNumeric($amount,$admin_id,$id) && validateForNull($trans_date,$delivery_date))
	{
			
			$sql="UPDATE edms_ac_purchase_order SET purchase_order_ref_type = $purchase_order_ref_type, purchase_order_ref = '$purchase_order_ref', amount=$amount, from_ledger_id=$from_ledger, to_ledger_id=$to_ledger, from_customer_id=$from_customer, trans_date='$trans_date', delivery_date='$delivery_date', remarks='$remarks', last_updated_by=$admin_id, date_modified=NOW()
			WHERE purchase_order_id=$id";
			
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
	
function insertTaxToPurchaseOrderVehicle($purchase_order_id,$vehicle_id,$tax_group,$basic_price)
{
	
	
	if(checkForNumeric($purchase_order_id,$vehicle_id,$tax_group) && $tax_group>0)
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
		
		if($tax_in_out==2) // if include in purchase_order add tax amount in the purchase_order making tax amount 0
		{	
		$tax_amount=0;
		}
		
		$sql="INSERT INTO edms_ac_purchase_order_tax (purchase_order_id, vehicle_id, tax_group_id, tax_amount, tax_id) VALUES ($purchase_order_id,$vehicle_id,$tax_group,$tax_amount,$tax_id)";
		dbQuery($sql);	
		
		
	$purchase_order = getPurchaseOrderById($purchase_order_id);
	
	 $oc_id=$purchase_order['oc_id'];
	
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
	}
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
	if(strtotime($purchase_order['trans_date'])>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
		if(is_numeric($purchase_order['from_ledger_id']))
		{
			$from_id = $purchase_order['from_ledger_id'];
			
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
			$from_id = $purchase_order['from_customer_id'];
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

function insertTaxToPurchaseOrder($purchase_order_id,$purchase_order_item_id,$tax_group,$net_amount,$nonstock=false) // non stock if true, purchase_order_item_id is purchase_order_non_stock_id
{
	$vehicle_id = "NULL";
	if($nonstock)
	{
	$purchase_order_non_stock_id = $purchase_order_item_id;
	$purchase_order_item_id = "NULL";
	}
	else
	$purchase_order_non_stock_id = "NULL";
	
	if(checkForNumeric($purchase_order_id,$tax_group,$net_amount) && $tax_group>0 && (checkForNumeric($purchase_order_item_id) || checkForNumeric($purchase_order_non_stock_id)))
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
		
		if($tax_in_out==2) // if include in purchase_order add tax amount in the purchase_order making tax amount 0
		{	
		$tax_amount=0;
		}
		
		$sql="INSERT INTO edms_ac_purchase_order_tax (purchase_order_id, vehicle_id, purchase_order_item_id, purchase_order_non_stock_id, tax_group_id, tax_amount, tax_id) VALUES ($purchase_order_id,$vehicle_id,$purchase_order_item_id,$purchase_order_non_stock_id,$tax_group,$tax_amount,$tax_id)";
		dbQuery($sql);	
		
		
	$purchase_order = getPurchaseOrderById($purchase_order_id);
	
	 $oc_id=$purchase_order['oc_id'];
	
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
	}
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
	if(strtotime($purchase_order['trans_date'])>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
		if(is_numeric($purchase_order['from_ledger_id']))
		{
			$from_id = $purchase_order['from_ledger_id'];
			
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
			$from_id = $purchase_order['from_customer_id'];
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

function deleteTaxForPurchaseOrder($purchase_order_id)
{
	
	if(checkForNumeric($purchase_order_id))
	{
		$taxes = getTaxForVehiclePurcahseId($purchase_order_id);
		
		$sql="DELETE FROM edms_ac_purchase_order_tax WHERE purchase_order_id = $purchase_order_id";
		dbQuery($sql);	
		
	$purchase_order = getPurchaseOrderById($purchase_order_id);
	
	 $oc_id=$purchase_order['oc_id'];
	
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
	}
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
	if(strtotime($purchase_order['trans_date'])>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
	foreach($taxes as $tax)
	{		
		$tax_in_out = $tax['in_out'];
		$tax_id = $tax['tax_id'];
		$tax_ledger_id = getTaxLedgerForTaxID($tax_id); 
		$tax_amount = $tax['tax_amount'];	
		if(is_numeric($purchase_order['from_ledger_id']) || is_numeric($purchase_order['from_customer_id']))
		{
			$from_id = $purchase_order['from_ledger_id'];
			
			
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
			$from_id = $purchase_order['from_customer_id'];
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


function deleteTaxForVehiclePurchaseOrder($vehicle_id,$purchase_order) // $purchase_order is the whole purchase_order array
{
	
	if(checkForNumeric($vehicle_id))
	{
		$taxes = getTaxDetailsForVehicleId($vehicle_id);
		
		$sql="DELETE FROM edms_ac_purchase_order_tax WHERE vehicle_id = $vehicle_id";
		dbQuery($sql);	
		
	
	
	 $oc_id=$purchase_order['oc_id'];
	
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
	}
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
	if(strtotime($purchase_order['trans_date'])>=strtotime($ac_starting_date)) // if transactio was done after books starting date
	{
		
	foreach($taxes as $tax)
	{		
		$tax_in_out = $tax['in_out'];
		$tax_id = $tax['tax_id'];
		$tax_ledger_id = getTaxLedgerForTaxID($tax_id); 
		$tax_amount = $tax['tax_amount'];	
		if(is_numeric($purchase_order['from_ledger_id']) || is_numeric($purchase_order['from_customer_id']))
		{
			$from_id = $purchase_order['from_ledger_id'];
			
		
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
			$from_id = $purchase_order['from_customer_id'];
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


function getPurchaseOrdersForLedgerIdMonthWiseBetweenDates($from_ledger,$from=NULL,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
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
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=2 && $head_type!=4))
	{
	$sql="SELECT edms_ac_purchase_order.purchase_order_id,SUM(amount) ,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_purchase_order WHERE edms_ac_purchase_order.oc_id = $oc_id AND ";
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
		
function getTotalPurchaseOrderAmountForLedgerIdUptoDate($from_ledger,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
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
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger) && $head_type!=2 && $head_type!=4))
	{
	$sql="SELECT edms_ac_purchase_order.purchase_order_id,SUM(amount),from_ledger_id,to_ledger_id,from_customer_id
			  FROM edms_ac_purchase_order WHERE edms_ac_purchase_order.oc_id = $oc_id AND ";
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

function getPurchaseOrdersForLedgerIdForMonth($from_ledger,$month_id,$year,$from=NULL,$to=NULL)
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
	if((checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=2 && $head_type!=4)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_purchase_order.purchase_order_id, amount ";
	if($head_type==1 || $head_type==0 || !isset($head_type))
	$sql=$sql." + IF(total_tax IS NOT NULL,total_tax,0) AS amount ";
	$sql=$sql." ,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_purchase_order LEFT JOIN (SELECT f.purchase_order_id, SUM( tax_amount ) AS total_tax
FROM edms_ac_purchase_order_tax f GROUP BY f.purchase_order_id
)h ON edms_ac_purchase_order.purchase_order_id = h.purchase_order_id WHERE edms_ac_purchase_order.oc_id = $oc_id AND ";
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

function getTotalPurchaseOrderForLedgerIdForMonth($from_ledger,$month_id,$year,$from=NULL,$to=NULL,$tax_inc=0)
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
	if((checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=2 && $head_type!=4)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_purchase_order.purchase_order_id,SUM(amount) as total_amount,SUM(amount + IF(total_tax IS NOT NULL,total_tax,0)) AS net_amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_purchase_order LEFT JOIN (SELECT f.purchase_order_id, SUM( tax_amount ) AS total_tax
FROM edms_ac_purchase_order_tax f GROUP BY f.purchase_order_id
)h ON edms_ac_purchase_order.purchase_order_id = h.purchase_order_id WHERE edms_ac_purchase_order.oc_id = $oc_id AND ";
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
	{
		if($tax_inc==0)
	return $resultArray[0][1];
	else
	return $resultArray[0][2];
	}
	else
	return 0; 	
	}
	return 0;
}			

function getPurchaseOrdersForLedgerIdBetweenDates($from_ledger,$from=NULL,$to=NULL)
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
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=2 && $head_type!=4)  || $from_ledger==-1)
	{
	$sql="SELECT edms_ac_purchase_order.purchase_order_id, amount ";
	if($head_type==1 || $head_type==0 || !isset($head_type))
	$sql=$sql." + IF(total_tax IS NOT NULL,total_tax,0) AS amount ";
	$sql=$sql." ,amount + IF(total_tax IS NOT NULL,total_tax,0) AS net_amount ";
	$sql=$sql." ,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified, remarks
			  FROM edms_ac_purchase_order LEFT JOIN (SELECT f.purchase_order_id, SUM( tax_amount ) AS total_tax
FROM edms_ac_purchase_order_tax f
GROUP BY f.purchase_order_id
)h ON edms_ac_purchase_order.purchase_order_id = h.purchase_order_id
WHERE  edms_ac_purchase_order.oc_id = $oc_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($from_ledger!=-1)
	{	  
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==3)  	  
	$sql=$sql." to_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";
	}
	else
	{
	 if(checkForNumeric($oc_id))
	$sql=$sql." oc_id IN (".$oc_id.")";	  
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

function getTotalPurchaseOrderForItemIdForMonth($item_id,$month_id,$year,$from=NULL,$to=NULL,$model=false) // model = true, item_id = model_id
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
			  FROM edms_ac_purchase_order_item, edms_ac_purchase_order WHERE edms_ac_purchase_order.oc_id = $oc_id AND edms_ac_purchase_order_item.purchase_order_id = edms_ac_purchase_order.purchase_order_id AND ";
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

function getPurchaseOrdersForItemIdBetweenDates($item_id,$from=NULL,$to=NULL,$model=false)
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
	$sql="SELECT edms_ac_purchase_order_item.purchase_order_item_id, edms_ac_purchase_order.purchase_order_id, edms_ac_purchase_order_item.amount , net_amount, rate, quantity, discount, from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, remarks
			  FROM edms_ac_purchase_order_item INNER JOIN  edms_ac_purchase_order ON edms_ac_purchase_order.purchase_order_id = edms_ac_purchase_order_item.purchase_order_id
WHERE edms_ac_purchase_order.oc_id = $oc_id AND ";
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

function getPurchaseOrdersForItemIdForMonth($item_id,$month_id,$year,$from=NULL,$to=NULL,$model=false)
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
	if(checkForNumeric($item_id,$month_id,$year))
	{
	$sql="SELECT edms_ac_purchase_order_item.purchase_order_item_id, edms_ac_purchase_order.purchase_order_id, edms_ac_purchase_order_item.amount , net_amount, rate, quantity, discount, from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year
			  FROM edms_ac_purchase_order_item INNER JOIN  edms_ac_purchase_order ON edms_ac_purchase_order.purchase_order_id = edms_ac_purchase_order_item.purchase_order_id
WHERE edms_ac_purchase_order.oc_id = $oc_id AND ";
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

function getPurchaseOrdersForItemIdMonthWiseBetweenDates($item_id,$from=NULL,$to=NULL,$model=false) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
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
	

	$sql="SELECT SUM(edms_ac_purchase_order_item.amount) as total_gross_amount , SUM(net_amount) as total_amount, SUM(net_amount)/SUM(quantity) as avg_rate, SUM(quantity) as quantity,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year
			  FROM edms_ac_purchase_order_item INNER JOIN  edms_ac_purchase_order ON edms_ac_purchase_order.purchase_order_id = edms_ac_purchase_order_item.purchase_order_id
WHERE edms_ac_purchase_order.oc_id = $oc_id AND ";
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
	
function getTotalPurchaseOrderForItemIdUptoDate($item_id,$to=NULL,$model=false,$godown_id=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
	
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($item_id))
	{
	$sql="SELECT edms_ac_purchase_order_item.purchase_order_item_id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate
			  FROM edms_ac_purchase_order_item, edms_ac_purchase_order WHERE edms_ac_purchase_order.oc_id = $oc_id AND edms_ac_purchase_order_item.purchase_order_id = edms_ac_purchase_order.purchase_order_id AND ";
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


function getTotalPurchaseOrderForItemIdBetweenDates($item_id,$from=NULL,$to=NULL,$model=false,$godown_id=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
	
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($item_id))
	{
	$sql="SELECT edms_ac_purchase_order_item.purchase_order_item_id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate
			  FROM edms_ac_purchase_order_item, edms_ac_purchase_order WHERE edms_ac_purchase_order.oc_id = $oc_id AND edms_ac_purchase_order_item.purchase_order_id = edms_ac_purchase_order.purchase_order_id AND ";
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