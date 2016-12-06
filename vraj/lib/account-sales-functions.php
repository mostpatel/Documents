<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("account-head-functions.php");
require_once("account-period-functions.php");
require_once("account-ledger-functions.php");
require_once("invoice-counter-functions.php");
require_once("account-delivery-challan-functions.php");
require_once("inventory-item-barcode-functions.php");
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

function getLastAddedSale()
{
	$sql="SELECT sales_id,sales_ref_type,sales_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,created_by,last_updated_by,date_added,date_modified, retail_tax, invoice_no, remarks
			  FROM edms_ac_sales ORDER BY sales_id DESC LIMIT 0,1";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	return "error"; 
}
	
function getSaleById($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT sales_id,sales_ref_type,sales_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,our_company_name,auto_rasid_type,auto_id,trans_date,delivery_date,edms_ac_sales.created_by,edms_ac_sales.last_updated_by,edms_ac_sales.date_added,edms_ac_sales.date_modified, retail_tax, invoice_no, remarks, invoice_note
			  FROM edms_ac_sales
			  INNER JOIN edms_our_company ON edms_ac_sales.oc_id = edms_our_company.our_company_id 
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

function insertSale($amount,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$auto_rasid_type=0,$auto_id=0,$sales_ref="NA",$sales_ref_type=0,$retail_tax=NULL,$invoice_no=NULL,$invoice_note="",$delivery_note="",$terms_of_payment="",$supp_ref_no="",$other_references="",$buyer_order_no="",$order_dated="",$despatch_doc_no="",$despatch_dated="",$despatch_through="",$destination="",$terms_of_delivery="",$oc_id=NULL,$consignee_address="") // $to_ledger should start with C for customer or L for ledger to_ledger: credit, from_ledger: debit
{
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		 
		$led=getLedgerById($to_ledger);
		
		$current_company=getCompanyForLedger($from_ledger);
		
				if($current_company[1]==0)
				{
					if(!is_numeric($oc_id))
					$oc_id = $current_company[0];
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				}
			
			
		$tax_invoice_type = getTaxInvoiceTypeForOcId($oc_id);
		if(($retail_tax==$tax_invoice_type['invoice_type_id'] && validateForNull($led['sales_no']) && ($led['sales_no']==0 || $led['sales_no']=="NA")) && TIN_FOR_TAX==1)
		return false;
		$to_customer="NULL";
		
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($to_customer);
		$customer_id=$customer['customer_id'];
		
		if(!is_numeric($oc_id) || CUSTOMER_MULTI_COMPANY==0)
		{
		$current_company=getCompanyForLedger($from_ledger);
		$oc_id = $current_company[0];
		}
		
		
		$tax_invoice_type = getTaxInvoiceTypeForOcId($oc_id);
		if(($retail_tax==$tax_invoice_type['invoice_type_id'] && validateForNull($customer['tin_no']) && ($customer['tin_no']==0 || $customer['tin_no']=="NA")) && TIN_FOR_TAX==1)
		return "error";
		
				
		$accounts_settings=getAccountsSettingsForOC($oc_id);
				
		
		}	
		
		if(!validateForNull($retail_tax) || $retail_tax<1)
		{	
		$invoice_type=getRetailInvoiceTypeForOcId($oc_id);
		$retail_tax = $invoice_type['invoice_type_id'];
		}	
		
		if(!validateForNull($invoice_note))
		$invoice_note="";
		$invoice_note = clean_data($invoice_note);
	if((!(checkForNumeric($to_ledger) || checkForNumeric($to_customer))) || !checkForNumeric($oc_id) )
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
			
			$invoice_type = getInvoiceTypeById($retail_tax);
			$invoice_prefix = $invoice_type['invoice_prefix'];
			$or_invoice_no = $invoice_no;
			$invoice_no = $invoice_prefix.$invoice_no;
			
			$sql="INSERT INTO edms_ac_sales (sales_ref_type,sales_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,remarks,invoice_note,created_by,last_updated_by,date_added,date_modified, retail_tax, invoice_no)
			VALUES ($sales_ref_type,'$sales_ref',$amount,$from_ledger,$to_ledger,$to_customer,$oc_id,$auto_rasid_type,$auto_id,'$trans_date','$delivery_date','$remarks','$invoice_note',$admin_id,$admin_id,NOW(),NOW(), $retail_tax, '$invoice_no')";
		    
			$result=dbQuery($sql);
			$sales_id = dbInsertId();
			if($delivery_challan_id && checkForNumeric($delivery_challan['delivery_challan_id']))
			$delivery_challan_id = $delivery_challan['delivery_challan_id'];
			else
			$delivery_challan_id = "NULL";
			insertSalesInfo($delivery_note,$terms_of_payment,$supp_ref_no,$other_references,$buyer_order_no,$order_dated,$despatch_doc_no,$despatch_dated,$despatch_through,$destination,$terms_of_delivery,$sales_id,$consignee_address,$delivery_challan_id);
			
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
			
			$invoice_counter=getInvoiceCounterForOCID($retail_tax,$oc_id);
			if($or_invoice_no!="NA" && $invoice_counter==$or_invoice_no)
			{		
			
			incrementInvoiceNoForOCID($retail_tax,$oc_id);
			
			}
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
		$delivery_challan = getACDeliveryChallanBySalesId($id);
	    $receipt_amount = getReceiptAmountForSalesId($sales_id);
		if($receipt_amount>0)
		return "receipt_generated_error";
	if(USE_BARCODE==1)
		{
			$used_barcode_tansactions = getUsedBarcodeForTransactionItemWise($id,2);
			$used_barcode_tansactions_item_id_array = array_keys($used_barcode_tansactions);
			
			if(is_array($used_barcode_tansactions) && count($used_barcode_tansactions)>0 && checkForNumeric($used_barcode_tansactions_item_id_array[0]))
			return "barcode_in_use_error";
		}
	
    if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
	}
		
	if($delivery_challan && is_numeric($delivery_challan['delivery_challan_id']))
	{
		$delivery_challan_id = $delivery_challan['delivery_challan_id'];
		$sql="UPDATE edms_ac_sales_item SET sales_id = NULL, rate=0, discount=0,amount =0 , net_amount = 0 WHERE delivery_challan_id = $delivery_challan_id";
		dbQuery($sql);
		
		$sql="UPDATE edms_ac_sales_nonstock SET sales_id = NULL, discount=0,amount =0 , net_amount = 0  WHERE delivery_challan_id = $delivery_challan_id";
		dbQuery($sql);
	}	
		deleteTaxForSale($id);
		deleteInventoryBarcodeTransactionByTransId($id,2);
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
function updateSale($id,$amount,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$sales_ref="NA",$sales_ref_type=0, $retail_tax=NULL,$invoice_note="",$delivery_note="",$terms_of_payment="",$supp_ref_no="",$other_references="",$buyer_order_no="",$order_dated="",$despatch_doc_no="",$despatch_dated="",$despatch_through="",$destination="",$terms_of_delivery="",$oc_id=NULL,$consignee_address="") // $to_ledger should start with C for customer or L for ledger
{
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	    $old_payment=getSaleById($id);
		$old_amount=$old_payment['amount'];
		$old_trans_date=$old_payment['trans_date'];
		$old_to_ledger_id=$old_payment['to_ledger_id'];
		$old_to_customer_id=$old_payment['to_customer_id'];
		$old_from_ledger_id=$old_payment['from_ledger_id'];
		
		if(!is_numeric($oc_id))
	    $oc_id=$old_payment['oc_id'];
	
	
	
	
	 if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
		}
	
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$led=getLedgerById($from_ledger);
		$current_company=getCompanyForLedger($from_ledger);
				if($current_company[1]==0)
				{
					$oc_id = $current_company[0];
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				}
				
		$tax_invoice_type = getTaxInvoiceTypeForOcId($oc_id);
		if(($retail_tax==$tax_invoice_type['invoice_type_id'] && validateForNull($led['sales_no']) && ($led['sales_no']==0 || $led['sales_no']=="NA")) && TIN_FOR_TAX==1)
		return false;
		$to_customer="NULL";
		
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($to_customer);
		$current_company=getCompanyForLedger($from_ledger);
				if($current_company[1]==0)
				{
					$oc_id = $current_company[0];
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				}
		$tax_invoice_type = getTaxInvoiceTypeForOcId($oc_id);
		if(($retail_tax==$tax_invoice_type['invoice_type_id'] && validateForNull($customer['tin_no']) && ($customer['tin_no']==0 || $customer['tin_no']=="NA")) && TIN_FOR_TAX==1)
		return false;
		
		}	
	
	if(!checkForNumeric($retail_tax))
	{
		$invoice_type = getRetailInvoiceTypeForOcId($oc_id);
		$retail_tax = $invoice_type['invoice_type_id']; 
	}	
		
	if(!validateForNull($invoice_note))
	$invoice_note="";	
	$invoice_note = clean_data($invoice_note);
	
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
			
			$sql="UPDATE edms_ac_sales SET sales_ref = '$sales_ref', sales_ref_type = $sales_ref_type, amount=$amount, from_ledger_id=$from_ledger, to_ledger_id=$to_ledger, to_customer_id=$to_customer, trans_date='$trans_date', delivery_date='$delivery_date', remarks='$remarks', invoice_note = '$invoice_note', last_updated_by=$admin_id, date_modified=NOW(), retail_tax = $retail_tax
			WHERE sales_id=$id";

			$result=dbQuery($sql);
			$delivery_challan=getACDeliveryChallanBySalesId($id);
			if($delivery_challan_id && checkForNumeric($delivery_challan['delivery_challan_id']))
			$delivery_challan_id = $delivery_challan['delivery_challan_id'];
			else
			$delivery_challan_id = "NULL";
			insertSalesInfo($delivery_note,$terms_of_payment,$supp_ref_no,$other_references,$buyer_order_no,$order_dated,$despatch_doc_no,$despatch_dated,$despatch_through,$destination,$terms_of_delivery,$id,$consignee_address,$delivery_challan_id);
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger))  && $head_type!=2 && $head_type!=3)
	{
	$sql="SELECT i.sales_id,";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
	$sql=$sql."SUM(net_amount) as total_amount";
	else
	$sql=$sql."i.amount as total_amount";
	$sql=$sql.",SUM(i.amount + IF(total_tax IS NOT NULL,total_tax,0)) AS net_amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
			  FROM ";
			  if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
			  {
			  $sql=$sql."(SELECT edms_ac_sales.sales_id, edms_ac_sales_item.amount, edms_ac_sales_item.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_sales.created_by, edms_ac_sales.last_updated_by, edms_ac_sales.date_added, edms_ac_sales.date_modified FROM edms_ac_sales , edms_ac_sales_item WHERE edms_ac_sales.sales_id = edms_ac_sales_item.sales_id AND edms_ac_sales.oc_id = $oc_id AND ";
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
			  $sql=$sql." UNION ALL SELECT edms_ac_sales.sales_id, edms_ac_sales_nonstock.amount, edms_ac_sales_nonstock.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_sales.created_by, edms_ac_sales.last_updated_by, edms_ac_sales.date_added, edms_ac_sales.date_modified FROM edms_ac_sales , edms_ac_sales_nonstock WHERE edms_ac_sales.sales_id = edms_ac_sales_nonstock.sales_id AND edms_ac_sales.oc_id = $oc_id AND ";
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
			  $sql=$sql." edms_ac_sales AS i ";
			  $sql=$sql." LEFT JOIN (SELECT  SUM( tax_amount ),  AS total_tax
FROM edms_ac_sales_tax f
GROUP BY f.sales_id
)h ON i.sales_id = h.sales_id WHERE i.oc_id = $oc_id ";	  
	
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger))  && $head_type!=2 && $head_type!=3)
	{
	$sql="SELECT edms_ac_sales.sales_id,SUM(net_amount),from_ledger_id,to_ledger_id,to_customer_id
			  FROM edms_ac_sales, edms_ac_sales_item WHERE edms_ac_sales.sales_id = edms_ac_sales_item.sales_id AND oc_id = $oc_id AND
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
	$sql=" UNION ALL SELECT edms_ac_sales.sales_id,SUM(net_amount),from_ledger_id,to_ledger_id,to_customer_id
			  FROM edms_ac_sales, edms_ac_sales_nonstock WHERE edms_ac_sales.sales_id = edms_ac_sales_nonstock.sales_id AND oc_id = $oc_id AND
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
	{
	$total=0;	
	if(is_numeric($resultArray[0][1]))	
	$total = $total + $resultArray[0][1];
	if(is_numeric($resultArray[1][1]))	
	$total = $total + $resultArray[1][1];
	return $total;
	}
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)  && $head_type!=2 && $head_type!=3)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT i.sales_id, ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
	$sql=$sql." SUM(net_amount) AS amount ";
	if($head_type==1 || $head_type==0 || !isset($head_type))
	$sql=$sql." amount + IF(total_tax IS NOT NULL,total_tax,0) AS amount ";
	$sql=$sql." ,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
			  FROM ";
			  if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
			  {
			  $sql=$sql."(SELECT edms_ac_sales.sales_id, edms_ac_sales_item.amount, edms_ac_sales_item.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_sales.created_by, edms_ac_sales.last_updated_by, edms_ac_sales.date_added, edms_ac_sales.date_modified FROM edms_ac_sales , edms_ac_sales_item WHERE edms_ac_sales.sales_id = edms_ac_sales_item.sales_id AND edms_ac_sales.oc_id = $oc_id AND ";
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
			  $sql=$sql." UNION ALL SELECT edms_ac_sales.sales_id, edms_ac_sales_nonstock.amount, edms_ac_sales_nonstock.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_sales.created_by, edms_ac_sales.last_updated_by, edms_ac_sales.date_added, edms_ac_sales.date_modified FROM edms_ac_sales , edms_ac_sales_nonstock WHERE edms_ac_sales.sales_id = edms_ac_sales_nonstock.sales_id AND edms_ac_sales.oc_id = $oc_id AND ";
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
			  $sql=$sql." edms_ac_sales AS i ";
			  $sql=$sql." LEFT JOIN (SELECT f.sales_id, SUM( tax_amount ) AS total_tax
FROM edms_ac_sales_tax f
GROUP BY f.sales_id
)h ON i.sales_id = h.sales_id WHERE i.oc_id = $oc_id AND  DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY i.sales_id";	  			  

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
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)  && $head_type!=2 && $head_type!=3)  || $to_ledger==-1)
	{
	$sql="SELECT i.sales_id, ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
	$sql=$sql." SUM(net_amount) AS amount ";
	if($head_type==1 || $head_type==0 || !isset($head_type))
	$sql=$sql." amount + IF(total_tax IS NOT NULL,total_tax,0) AS amount ";
	$sql=$sql.",from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified, remarks
			  FROM ";
			  if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
			  {
			  $sql=$sql."(SELECT edms_ac_sales.sales_id, edms_ac_sales_item.amount, edms_ac_sales_item.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_sales.created_by, edms_ac_sales.last_updated_by, edms_ac_sales.date_added, edms_ac_sales.date_modified FROM edms_ac_sales , edms_ac_sales_item WHERE edms_ac_sales.sales_id = edms_ac_sales_item.sales_id AND edms_ac_sales.oc_id = $oc_id AND ";
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
			  $sql=$sql." UNION ALL SELECT edms_ac_sales.sales_id, edms_ac_sales_nonstock.amount, edms_ac_sales_nonstock.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_sales.created_by, edms_ac_sales.last_updated_by, edms_ac_sales.date_added, edms_ac_sales.date_modified FROM edms_ac_sales , edms_ac_sales_nonstock WHERE edms_ac_sales.sales_id = edms_ac_sales_nonstock.sales_id AND edms_ac_sales.oc_id = $oc_id AND ";
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
			  $sql=$sql." edms_ac_sales AS i ";
			  $sql=$sql."  LEFT JOIN (SELECT f.sales_id, SUM( tax_amount ) AS total_tax
FROM edms_ac_sales_tax f
GROUP BY f.sales_id
)h ON i.sales_id = h.sales_id WHERE i.oc_id = $oc_id  ";	  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
	}

function getTotalSaleForLedgerIdForMonth($to_ledger,$month_id,$year,$from=NULL,$to=NULL,$tax_inc=0)
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
	if((checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)  && $head_type!=2 && $head_type!=3)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT i.sales_id,";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
	$sql=$sql."SUM(net_amount) as total_amount";
	else
	$sql=$sql."i.amount as total_amount";
	$sql=$sql.",SUM(i.amount + IF(total_tax IS NOT NULL,total_tax,0)) AS net_amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified
			  FROM ";
			  if(isset($head_type) && checkForNumeric($head_type) && $head_type==4)  
			  {
			  $sql=$sql."(SELECT edms_ac_sales.sales_id, edms_ac_sales_item.amount, edms_ac_sales_item.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_sales.created_by, edms_ac_sales.last_updated_by, edms_ac_sales.date_added, edms_ac_sales.date_modified FROM edms_ac_sales , edms_ac_sales_item WHERE edms_ac_sales.sales_id = edms_ac_sales_item.sales_id AND edms_ac_sales.oc_id = $oc_id AND ";
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
			  $sql=$sql." UNION ALL SELECT edms_ac_sales.sales_id, edms_ac_sales_nonstock.amount, edms_ac_sales_nonstock.net_amount, ledger_id, from_ledger_id, to_ledger_id, to_customer_id, oc_id,auto_rasid_type,auto_id, trans_date, edms_ac_sales.created_by, edms_ac_sales.last_updated_by, edms_ac_sales.date_added, edms_ac_sales.date_modified FROM edms_ac_sales , edms_ac_sales_nonstock WHERE edms_ac_sales.sales_id = edms_ac_sales_nonstock.sales_id AND edms_ac_sales.oc_id = $oc_id AND ";
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
			  $sql=$sql." edms_ac_sales AS i ";
			  $sql=$sql."   LEFT JOIN (SELECT f.sales_id, SUM( tax_amount ) AS total_tax
FROM edms_ac_sales_tax f
GROUP BY f.sales_id
)h ON i.sales_id = h.sales_id WHERE i.oc_id = $oc_id AND ";
	
	
	$sql=$sql."  DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY month_year";	
		  
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($item_id) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT SUM(net_amount) as total_amount, SUM(net_amount)/SUM(quantity) as avg_rate, SUM(quantity) as quantity, CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year
			  FROM edms_ac_sales, edms_ac_sales_item WHERE edms_ac_sales.oc_id = $oc_id AND edms_ac_sales.sales_id = edms_ac_sales_item.sales_id AND ";	  
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($item_id))
	{
	$sql="SELECT SUM(edms_ac_sales_item.amount) as total_gross_amount , SUM(net_amount) as total_amount, SUM(net_amount)/SUM(quantity) as avg_rate, SUM(quantity) as quantity,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year
			  FROM edms_ac_sales_item INNER JOIN  edms_ac_sales ON edms_ac_sales.sales_id = edms_ac_sales_item.sales_id
WHERE edms_ac_sales.oc_id = $oc_id AND ";
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($item_id))
	{
	$sql="SELECT edms_ac_sales_item.sales_item_id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate
			  FROM edms_ac_sales_item LEFT JOIN edms_ac_sales ON edms_ac_sales.oc_id = $oc_id AND edms_ac_sales_item.sales_id = edms_ac_sales.sales_id AND edms_ac_sales_item.sales_id IS NOT NULL AND edms_ac_sales_item.delivery_challan_id IS NULL LEFT JOIN edms_ac_delivery_challan ON edms_ac_delivery_challan.oc_id = $oc_id AND edms_ac_sales_item.delivery_challan_id = edms_ac_delivery_challan.delivery_challan_id AND edms_ac_sales_item.delivery_challan_id IS NOT NULL WHERE  ";
	if(isset($from) && validateForNull($from))
	$sql=$sql." IF(edms_ac_sales_item.delivery_challan_id IS NOT NULL,edms_ac_delivery_challan.trans_date,edms_ac_sales.trans_date)>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." IF(edms_ac_sales_item.delivery_challan_id IS NOT NULL,edms_ac_delivery_challan.trans_date,edms_ac_sales.trans_date)<='$to'
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($item_id) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_sales_item.sales_item_id, edms_ac_sales.sales_id, edms_ac_sales_item.amount , net_amount, rate, quantity, discount, from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year
			  FROM edms_ac_sales_item INNER JOIN  edms_ac_sales ON edms_ac_sales.sales_id = edms_ac_sales_item.sales_id
WHERE edms_ac_sales.oc_id = $oc_id AND ";
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
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($item_id))
	{
	$sql="SELECT edms_ac_sales_item.sales_item_id, edms_ac_sales.sales_id, edms_ac_sales_item.amount , net_amount, rate, quantity, discount, from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year
			  FROM edms_ac_sales_item INNER JOIN  edms_ac_sales ON edms_ac_sales.sales_id = edms_ac_sales_item.sales_id
WHERE edms_ac_sales.oc_id = $oc_id AND ";
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
	
function insertSalesInfo($delivery_note,$terms_of_payment,$supp_ref_no,$other_references,$buyer_order_no,$order_dated,$despatch_doc_no,$despatch_dated,$despatch_through,$destination,$terms_of_delivery,$sales_id,$consignee_address,$delivery_challan_id=NULL)
{
	
	if(!validateForNull($delivery_note))
	$delivery_note="";
	else
	$delivery_note = clean_data($delivery_note);
	
	if(!validateForNull($consignee_address))
	$consignee_address="";
	else
	$consignee_address = clean_data($consignee_address);
	
	if(!validateForNull($terms_of_payment))
	$terms_of_payment="";
	else
	$terms_of_payment = clean_data($terms_of_payment);
	
	if(!validateForNull($supp_ref_no))
	$supp_ref_no="";
	else
	$supp_ref_no = clean_data($supp_ref_no);
	
	if(!validateForNull($other_references))
	$other_references="";
	else
	$other_references = clean_data($other_references);
	
	if(!validateForNull($buyer_order_no))
	$buyer_order_no="";
	else
	$buyer_order_no = clean_data($buyer_order_no);
	
	if(!validateForNull($order_dated))
	$order_dated="01/01/1970";
	
	
	if(!validateForNull($despatch_doc_no))
	$despatch_doc_no="";
	else
	$despatch_doc_no = clean_data($despatch_doc_no);
	
	if(!validateForNull($despatch_dated))
	$despatch_dated="01/01/1970";
	
	if(!validateForNull($despatch_through))
	$despatch_through="";
	else
	$despatch_through = clean_data($despatch_through);
	
	if(!validateForNull($destination))
	$destination="";
	else
	$destination = clean_data($destination);
	
	if(!validateForNull($terms_of_delivery))
	$terms_of_delivery="";
	else
	$terms_of_delivery = clean_data($terms_of_delivery);
	
		if(isset($order_dated) && validateForNull($order_dated))
			{
		    $order_dated = str_replace('/', '-', $order_dated);
			$order_dated=date('Y-m-d',strtotime($order_dated));
			}
	if(isset($despatch_dated) && validateForNull($despatch_dated))
			{
		    $despatch_dated = str_replace('/', '-', $despatch_dated);
			$despatch_dated=date('Y-m-d',strtotime($despatch_dated));
			}			
	
	$order_dated = clean_data($order_dated);
	$despatch_dated = clean_data($despatch_dated);
	if(!checkForNumeric($sales_id))
	$sales_id="NULL";
	if(!checkForNumeric($delivery_challan_id))
	$delivery_challan_id="NULL";
	
	if((checkForNumeric($sales_id) || checkForNumeric($delivery_challan_id)))
	{
		if(checkForNumeric($sales_id))
		$sql="DELETE FROM edms_ac_sales_info WHERE sales_id = $sales_id";
		if(checkForNumeric($delivery_challan_id))
		$sql="DELETE FROM edms_ac_sales_info WHERE delivery_challan_id = $delivery_challan_id";
		dbQuery($sql);
		$sql="INSERT INTO edms_ac_sales_info (delivery_note, terms_of_payment, supplier_ref_no, other_reference, buyers_order_no, order_date, despatch_doc_no, despatch_dated, despatched_through, destination, terms_of_delivery, consignee_address,sales_id,delivery_challan_id) VALUES ('$delivery_note','$terms_of_payment','$supp_ref_no','$other_references','$buyer_order_no','$order_dated','$despatch_doc_no','$despatch_dated','$despatch_through','$destination','$terms_of_delivery','$consignee_address',$sales_id,$delivery_challan_id)";
		
		dbQuery($sql);
		$sales_info_id = dbInsertId();
		return $sales_info_id;
	}
	else
	return false;
}

function getSalesInfoForSalesId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$sql="SELECT delivery_note, terms_of_payment, supplier_ref_no, other_reference, buyers_order_no, order_date, despatch_doc_no, despatch_dated, despatched_through, destination, terms_of_delivery, consignee_address, sales_id FROM edms_ac_sales_info WHERE sales_id = $sales_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		return $resultArray[0];
	}	
}	

function getSalesInfoForDeliveryChallanId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT delivery_note, terms_of_payment, supplier_ref_no, other_reference, buyers_order_no, order_date, despatch_doc_no, despatch_dated, despatched_through, destination, terms_of_delivery, consignee_address, sales_id FROM edms_ac_sales_info WHERE delivery_challan_id = $id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		return $resultArray[0];
	}	
}	
?>