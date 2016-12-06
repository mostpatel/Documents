<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("account-head-functions.php");
require_once("account-period-functions.php");
require_once("account-ledger-functions.php");
require_once("account-functions.php");
require_once("customer-functions.php");
require_once("common.php");
require_once("bd.php");

function getAllSales()
{
	$sql="SELECT sales_id,sales_ref_type,sales_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,created_by,last_updated_by,date_added,date_modified, retail_tax, invoice_no, remarks
			  FROM edms_ac_sales";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
}
	
function getSaleById($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT sales_id,sales_ref_type,sales_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,created_by,last_updated_by,date_added,date_modified, retail_tax, invoice_no, remarks
			  FROM edms_ac_sales
			  WHERE sales_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return "error"; 
		
		}
	}
	
function getSaleWithInventoryById($id)
{
	if(checkForNumeric($id))
	{
		$sale = getSaleById($id);
		if($sale!="error")
		{
				
			
		}
		else return "error";
	}
	
	
}	

function insertSale($amount,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$auto_rasid_type=0,$auto_id=0,$sales_ref="NA",$sales_ref_type=0,$retail_tax=0,$invoice_no=NULL) // $to_ledger should start with C for customer or L for ledger to_ledger: credit, from_ledger: debit
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
	
	
	if(checkForNumeric($amount,$from_ledger,$admin_id) && $from_ledger>0 && validateForNull($trans_date,$delivery_date))
	{
		
		if($invoice_no=="" || !validateForNull($invoice_no))
			$invoice_no="NA";
				
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
			
			$oc_prefix = getPrefixFromOCId($oc_id);
			$or_invoice_no = $invoice_no;
			$invoice_no = $oc_prefix.$invoice_no;
		
			$sql="INSERT INTO edms_ac_sales (sales_ref_type,sales_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,remarks,created_by,last_updated_by,date_added,date_modified, retail_tax, invoice_no)
			VALUES ($sales_ref_type,'$sales_ref',$amount,$from_ledger,$to_ledger,$to_customer,$oc_id,$auto_rasid_type,$auto_id,'$trans_date','$delivery_date','$remarks',$admin_id,$admin_id,NOW(),NOW(), $retail_tax, '$invoice_no')";
			
			$result=dbQuery($sql);
			$sales_id = dbInsertId();
			$ac_starting_date = $accounts_settings['ac_starting_date'];
			
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
			$invoice_counter=getInvoiceCounterForOCID($oc_id);
			if($or_invoice_no!="NA" && $invoice_counter==$or_invoice_no)		
			incrementInvoiceNoForOCID($oc_id);
			
			return $sales_id;
	}
	return "error";	
}

function deleteSale($id)
{
	if(checkForNumeric($id))
	{
		if(checkIfSaleInUse($id))
		return "use_error";
		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$old_payment=getSaleById($id);
		$old_amount=$old_payment['amount'];
		$old_trans_date=$old_payment['trans_date'];
		$old_to_ledger_id=$old_payment['to_ledger_id'];
		$old_to_customer_id=$old_payment['to_customer_id'];
		$old_from_ledger_id=$old_payment['from_ledger_id'];
	    $oc_id=$old_payment['oc_id'];
	
    if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
		}
		deleteTaxForSale($id);
		
		$sql="DELETE FROM edms_ac_sales where sales_id=$id";
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

function checkIfSaleInUse($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$sale = getSaleById($sales_id);
		$auto_rasid_type = $sale['auto_rasid_type']; // 3 = job_card, 2 = item sales_auto_rasid_type
		
		if($auto_rasid_type==2)
		{
		$sql="SELECT receipt_id FROM edms_ac_receipt WHERE auto_rasid_type = 5 AND auto_id = $sales_id ";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		}
		else if($auto_rasid_type==3)
		{
		$job_card_id = 	$sale['auto_id'];
		$sql="SELECT receipt_id FROM edms_ac_receipt WHERE auto_rasid_type = 3 AND auto_id = $job_card_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		}
		return false;
	}
	return true;
}
function updateSale($id,$amount,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$sales_ref="NA",$sales_ref_type=0, $retail_tax=0) // $to_ledger should start with C for customer or L for ledger
{
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	    $old_payment=getSaleById($id);
		$old_amount=$old_payment['amount'];
		$old_trans_date=$old_payment['trans_date'];
		$old_to_ledger_id=$old_payment['to_ledger_id'];
		$old_to_customer_id=$old_payment['to_customer_id'];
		$old_from_ledger_id=$old_payment['from_ledger_id'];
		
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
			
			$sql="UPDATE edms_ac_sales SET sales_ref = '$sales_ref', sales_ref_type = $sales_ref_type, amount=$amount, from_ledger_id=$from_ledger, to_ledger_id=$to_ledger, to_customer_id=$to_customer, trans_date='$trans_date', delivery_date='$delivery_date', remarks='$remarks', last_updated_by=$admin_id, date_modified=NOW(), retail_tax = $retail_tax
			WHERE sales_id=$id";

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
	

function insertTaxToVehicleSale($sales_id,$vehicle_id,$tax_group,$basic_price)
{
	
	
	if(checkForNumeric($sales_id,$vehicle_id,$tax_group) && $tax_group>0)
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
		
		
		
		
		$sql="INSERT INTO edms_ac_sales_tax (sales_id, vehicle_id,  tax_group_id, tax_amount, tax_id) VALUES ($sales_id,$vehicle_id,$tax_group,$tax_amount,$tax_id)";
		dbQuery($sql);	
		
	$sales = getSaleById($sales_id);
    $oc_id=$sales['oc_id'];
	
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
	}
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
	if(strtotime($sales['trans_date'])>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
				if(is_numeric($sales['to_ledger_id']) || is_numeric($sales['to_customer_id']))
				{
					$to_id = $sales['to_ledger_id'];
					
					
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
					$to_id = $sales['to_customer_id'];
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

function insertTaxToSale($sales_id,$sale_item_id,$tax_group,$net_amount,$nonstock=false)
{
	
	$vehicle_id = "NULL";
	if($nonstock)
	{
	$sales_non_stock_id = $sale_item_id;
	$sale_item_id = "NULL";
	}
	else
	$sales_non_stock_id = "NULL";
	
	if(checkForNumeric($sales_id,$tax_group) && $tax_group>0 && (checkForNumeric($sale_item_id) || checkForNumeric($sales_non_stock_id)))
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
		
		
		
		
		$sql="INSERT INTO edms_ac_sales_tax (sales_id, vehicle_id, sales_item_id, sales_non_stock_id, tax_group_id, tax_amount, tax_id) VALUES ($sales_id,$vehicle_id,$sale_item_id,$sales_non_stock_id,$tax_group,$tax_amount,$tax_id)";
		dbQuery($sql);	
	 
	$sales = getSaleById($sales_id);
    $oc_id=$sales['oc_id'];
	
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
	}
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
	if(strtotime($sales['trans_date'])>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
				if(is_numeric($sales['to_ledger_id']) || is_numeric($sales['to_customer_id']))
				{
					$to_id = $sales['to_ledger_id'];
					
					
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
					$to_id = $sales['to_customer_id'];
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


function updateTaxToVehicleSale($sales_id,$vehicle_id,$tax_group,$basic_price)
{
	
	$result=deleteTaxForSale($sales_id);

	if($result)
	{
	insertTaxToVehicleSale($sales_id,$vehicle_id,$tax_group,$basic_price);
	return true;
	}
	return false;
}

function deleteTaxForSale($sales_id)
{
	
	if(checkForNumeric($sales_id))
	{
		$taxes = getTaxForVehicleSaleId($sales_id);
		
		$sql="DELETE FROM edms_ac_sales_tax WHERE sales_id = $sales_id";
		dbQuery($sql);	
		
	$sales = getSaleById($sales_id);
	
	 $oc_id=$sales['oc_id'];
	
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
	}
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
	if(strtotime($sales['trans_date'])>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
	foreach($taxes as $tax)
	{
		$tax_in_out = $tax['in_out'];
		$tax_id = $tax['tax_id'];
		$tax_ledger_id = getTaxLedgerForTaxID($tax_id); 
		$tax_amount = $tax['tax_amount'];			
		if(is_numeric($sales['to_ledger_id']) || is_numeric($sales['to_customer_id']))
		{
			$to_id = $sales['to_ledger_id'];
			
			
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
			$to_id = $sales['to_customer_id'];
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

function getTaxForVehicleSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$sql="SELECT sales_id, vehicle_id,  edms_tax_grp.tax_group_id, tax_group_name, SUM(tax_amount) as tax_amount, edms_tax.tax_id, CONCAT(IF(edms_tax.in_out>0,'OUT','IN'), ' ', tax_name) as tax_name_in_out, tax_name, edms_tax.in_out, tax_percent FROM edms_ac_sales_tax, edms_tax_grp, edms_tax WHERE edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id AND edms_tax.tax_id = edms_ac_sales_tax.tax_id AND edms_ac_sales_tax.sales_id = $sales_id GROUP BY tax_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;	
		}
	return false;
}

function getTotalTaxForSalesId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$sql="SELECT  SUM(tax_amount) as tax_amount FROM edms_ac_sales_tax, edms_tax_grp, edms_tax WHERE edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id AND edms_tax.tax_id = edms_ac_sales_tax.tax_id AND edms_ac_sales_tax.sales_id = $sales_id GROUP BY sales_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;	
		}
	return false;
	
	
}
function getSalesForLedgerIdMonthWise($to_ledger,$from=NULL,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
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
	
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger))  && $head_type!=2 && $head_type!=3)
	{
	$sql="SELECT sale_id,SUM(amount),from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_sales WHERE ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  	  
	$sql=$sql." from_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
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
	
function getTotalSaleAmountForLedgerIdUptoDate($to_ledger,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
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
	
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger))  && $head_type!=2 && $head_type!=3)
	{
	$sql="SELECT sales_id,SUM(amount),from_ledger_id,to_ledger_id,to_customer_id
			  FROM edms_ac_sales WHERE ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  	  
	$sql=$sql." from_ledger_id=$to_ledger GROUP BY from_ledger_id";
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

function getSalesForLedgerIdForMonth($to_ledger,$month_id,$year,$from=NULL,$to=NULL)
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
	
	if((checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)  && $head_type!=2 && $head_type!=3)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_sales.sales_id, amount ";
	if($head_type==1 || $head_type==0 || !isset($head_type))
	$sql=$sql." + IF(total_tax IS NOT NULL,total_tax,0) AS amount ";
	$sql=$sql." ,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
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
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  	  
	$sql=$sql." from_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
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
	
		

function getSalesForLedgerIdBetweenDates($to_ledger,$from=NULL,$to=NULL)
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
	
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)  && $head_type!=2 && $head_type!=3))
	{
	$sql="SELECT edms_ac_sales.sales_id,amount ";
	if($head_type==1 || $head_type==0 || !isset($head_type) || $head_type==0)
	$sql=$sql." + IF(total_tax IS NOT NULL,total_tax,0) AS amount ";
	$sql=$sql.",from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
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
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  	  
	$sql=$sql." from_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
	}

function getTotalSaleForLedgerIdForMonth($to_ledger,$month_id,$year,$from=NULL,$to=NULL)
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
	
	if((checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)  && $head_type!=2 && $head_type!=3)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_sales.sales_id,SUM(amount) as total_amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_sales WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  	  
	$sql=$sql." from_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
	
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
	
function getTotalSaleForItemIdForMonth($item_id,$month_id,$year,$from=NULL,$to=NULL,$model=false)
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
			  FROM edms_ac_sales, edms_ac_sales_item WHERE edms_ac_sales.sales_id = edms_ac_sales_item.sales_id AND ";	  
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

function getSalesForItemIdMonthWise($item_id,$from=NULL,$to=NULL,$model=false) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
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
	$sql="SELECT SUM(edms_ac_sales_item.amount) as total_gross_amount , SUM(net_amount) as total_amount, SUM(net_amount)/SUM(quantity) as avg_rate, SUM(quantity) as quantity,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year
			  FROM edms_ac_sales_item INNER JOIN  edms_ac_sales ON edms_ac_sales.sales_id = edms_ac_sales_item.sales_id
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
		
function getTotalSaleForItemIdUptoDate($item_id,$to=NULL,$model=false,$godown_id=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
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
	$sql="SELECT edms_ac_sales_item.sales_item_id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate
			  FROM edms_ac_sales_item, edms_ac_sales WHERE edms_ac_sales_item.sales_id = edms_ac_sales.sales_id AND ";
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

function getSalesForItemIdForMonth($item_id,$month_id,$year,$from=NULL,$to=NULL,$model=false)
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
	$sql="SELECT edms_ac_sales_item.sales_item_id, edms_ac_sales.sales_id, edms_ac_sales_item.amount , net_amount, rate, quantity, discount, from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year
			  FROM edms_ac_sales_item INNER JOIN  edms_ac_sales ON edms_ac_sales.sales_id = edms_ac_sales_item.sales_id
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
	
		

function getSalesForItemIdBetweenDates($item_id,$from=NULL,$to=NULL,$model=false)
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
	$sql="SELECT edms_ac_sales_item.sales_item_id, edms_ac_sales.sales_id, edms_ac_sales_item.amount , net_amount, rate, quantity, discount, from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year
			  FROM edms_ac_sales_item INNER JOIN  edms_ac_sales ON edms_ac_sales.sales_id = edms_ac_sales_item.sales_id
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
?>