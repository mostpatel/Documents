<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("inventory-item-functions.php");
require_once("item-type-functions.php");
require_once("item-unit-functions.php");
require_once("item-manufacturer-functions.php");
require_once("inventory-item-barcode-functions.php");
require_once("inventory-sales-functions.php");
require_once("tax-functions.php");
require_once("godown-functions.php");
require_once("account-ledger-functions.php");
require_once("account-credit-note-functions.php");
require_once("nonstock-credit-note-functions.php");
require_once("tax-functions.php");
require_once("delivery-challan-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");
require_once("our-company-function.php");
function checkForCreditNoteItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_id_array,$trans_type=NULL,$trans_id_if_update=NULL,$unit_id_array=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL)
{
	$total_amount=0;
	$has_items=false;
	if(checkIfDuplicateBarcodesInArray($item_id_array))
	return "barcode_transaction_error";
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			$item_id=$item_id_array[$i];
			
			if(!is_numeric($item_id) && is_array($item_id) && checkForNumeric($item_id[0]))
			{
				$barcode = $item_id[1];
				$item_id=$item_id[0];
			}
			
			
			$rate=$rate_array[$i];
			$quantity=$quantity_array[$i];
			$discount=$discount_array[$i];
			$tax_group_id = $tax_group_id_array[$i];
			
			$unit_id = $unit_id_array[$i];
			$sales_ledger_id = $sales_ledger_id_array[$i];
			$tax_class_id = $tax_class_id_array[$i];
		
			
			if(checkForNumeric($item_id,$rate,$quantity,$discount,$tax_group_id) && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0 && $tax_group_id>=0 && ((TAX_CLASS==1 && checkForNumeric($tax_class_id,$sales_ledger_id) && $tax_class_id>=0 && $sales_ledger_id>0) || TAX_CLASS==0))
			{
				$item=  getInventoryItemById($item_id);
				if($item['use_barcode']==0)
				{
					
				}
				else
				$quantity=1;
				
				$amount = ($rate - $rate*($discount/100))*$quantity;
				
				$total_amount = $total_amount + $amount;
			
				if(USE_BARCODE==1)
				{
					if(validateForNull($barcode))
					{
						$latest_transaction=getLatestTransactionForBarcode($barcode);
						
						if($latest_transaction && is_array($latest_transaction) && is_numeric($latest_transaction['trans_type']))	
						{
							$latest_trans_type = $latest_transaction['trans_type'];
							
							if($latest_trans_type%2==0)
							{
								if($trans_type%2==0)
								{
									
									if(!is_numeric($trans_id_if_update) || ($trans_id_if_update!=$latest_transaction['trans_id'] && $trans_type!=$latest_trans_type))
									{
										if(!is_numeric($trans_id_if_update))	
										return "barcode_transaction_error";	
										else 
										{
											$used_barcodes=getUsedBarcodeForTransaction($trans_id_if_update,$trans_type);
											if(in_array($barcode,$used_barcodes))
											{
												
											}
											else
											return "barcode_transaction_error";	
										}
									}
								}
							}
							else if($latest_trans_type%2==1)
							{
								if($trans_type%2==1)
								{
									if(!is_numeric($trans_id_if_update) || ($trans_id_if_update!=$latest_transaction['trans_id'] && $trans_type!=$latest_trans_type))
								{
										if(!is_numeric($trans_id_if_update))	
										return "barcode_transaction_error";	
										else 
										{
											$used_barcodes=getUsedBarcodeForTransaction($trans_id_if_update,$trans_type);
											if(in_array($barcode,$used_barcodes))
											{
												
											}
											else
											return "barcode_transaction_error";	
										}
									}
								}
							}
						}
					}
				}
				
			
				if($tax_group_id>0)
				{
				$tax_group = getTaxGroupByID($tax_group_id);
				if($tax_group['in_out']==3) // IN SALE type tax
				{
					$total_tax_percent = getTotalTaxPercentForTaxGroup($tax_group_id);
					$base_amount = GetBaseAmountFromMRP($total_tax_percent,$amount);
					$total_tax_amount = $amount - $base_amount;
					$total_amount = $amount - $total_tax_amount;	
				}
				
				}
				
				$has_items = $total_amount;
			}	
			
		}
				
	}
	
	return $has_items;
	
	}
	
function insertInventoryNonStockCreditNote($item_id_array,$rate_array,$quantity_array,$discount_array,$item_id_ns_array,$amount_ns_array,$discount_ns_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$tax_group_ns_array,$credit_note_ref="NA",$credit_note_ref_type=0,$unit_id_array=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL,$ns_sales_ledger_id_array=NULL,$ns_tax_class_id_array=NULL,$form_no=NULL,$form_date=NULL)
{
    $item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
	$item_id_ns_array = ConvertItemNameArrayInToIdArray($item_id_ns_array);
	
	$nett_amount = checkForCreditNoteItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_array,3,NULL,$unit_id_array,$sales_ledger_id_array,$tax_class_id_array);
	$nett_amount_ns = checkForCreditNoteItemsNSInArray($item_id_ns_array,$amount_ns_array,$discount_ns_array,$tax_group_ns_array,0,NULL,NULL,$ns_sales_ledger_id_array,$ns_tax_class_id_array);
	
	if($nett_amount=="barcode_transaction_error")
	return "barcode_transaction_error";
	
	if(($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0) || ($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0))
	{
	$total_nett_amount=0;	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount;
	if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount_ns;
	
		if(TAX_CLASS==1)
	{
		$from_ledger=NULL;
		$i=0;
		
		while((!checkForNumeric($from_ledger) || $from_ledger==-1))
		{
			if(is_numeric($sales_ledger_id_array[$i]))
			$from_ledger = $sales_ledger_id_array[$i];
		     
			 $i++;
			
			if((is_numeric($from_ledger) && $from_ledger>0) || $i>count($sales_ledger_id_array))
			break;
		}	
		
		if(!checkForNumeric($from_ledger) || $from_ledger==-1)
		{
			
			$i=0;
			while((!checkForNumeric($from_ledger) || $from_ledger==-1))
			{
				if(is_numeric($ns_sales_ledger_id_array[$i]))
			    $from_ledger = $ns_sales_ledger_id_array[$i];
				
				$i++;
				
				
				if((is_numeric($from_ledger) && $from_ledger>0) || $i>count($sales_ledger_id_array))
			    break;
			
			}	
			
		}
		
		if(!checkForNumeric($from_ledger))
		return "error";
		
	}
	
	
	$credit_note_id = insertCreditNote($total_nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$credit_note_ref,$credit_note_ref_type);
	if(checkForNumeric($credit_note_id))
	{
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)	
	insertInventoryItemsToCreditNote($item_id_array,$rate_array,$quantity_array,$discount_array,$credit_note_id,$tax_group_array,$godown_id_array,0,$unit_id_array,$sales_ledger_id_array,$tax_class_id_array);
	if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
	insertNonStocksToCreditNote($item_id_ns_array,$amount_ns_array,$discount_ns_array,$credit_note_id,$tax_group_ns_array,0,NULL,NULL,$ns_sales_ledger_id_array,$ns_tax_class_id_array);
	
	$tax_form_id=getTaxFormIdForTransId($credit_note_id,3);
	if(checkForNumeric($tax_form_id))
	insertTransTaxForm('NULL','NULL',$credit_note_id,'NULL',$tax_form_id,$form_no,$form_date);
	
	}
	else
	return "error";
	
	return $credit_note_id;
	}
	return "error";
}	

function insertInventoryItemCreditNote($item_id_array,$rate_array,$quantity_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$credit_note_ref="NA",$credit_note_ref_type=0)
{
	$nett_amount = checkForCreditNoteItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	{
	$credit_note_id = insertCreditNote($nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$credit_note_ref,$credit_note_ref_type);
	if(checkForNumeric($credit_note_id))
	insertInventoryItemsToCreditNote($item_id_array,$rate_array,$quantity_array,$discount_array,$credit_note_id,$tax_group_array,$godown_id_array);
	else
	return "error";

	return $credit_note_id;
	}
	return "error";
}


function updateInventoryItemCreditNote($credit_note_id,$item_id_array,$rate_array,$quantity_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$credit_note_ref="NA",$credit_note_ref_type=0)
{
	if(checkForNumeric($credit_note_id))
	{
		$nett_amount = checkForCreditNoteItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	{
		
	deleteInventoryItemsForCreditNote($credit_note_id);	
	updateCreditNote($credit_note_id,$nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$credit_note_ref,$credit_note_ref_type);
	insertInventoryItemsToCreditNote($item_id_array,$rate_array,$quantity_array,$discount_array,$credit_note_id,$tax_group_array,$godown_id_array);

	return "success";
	}
	return "error";
		
		
	}
}

function updateInventoryNonStockItemCreditNote($credit_note_id,$item_id_array,$rate_array,$quantity_array,$discount_array,$item_id_ns_array,$amount_ns_array,$discount_ns_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$tax_group_ns_array,$credit_note_ref="NA",$credit_note_ref_type=0,$unit_id_array=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL,$ns_sales_ledger_id_array=NULL,$ns_tax_class_id_array=NULL,$form_no=NULL,$form_date=NULL)
{
	if(checkForNumeric($credit_note_id))
	{
		 $item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
	     $item_id_ns_array = ConvertItemNameArrayInToIdArray($item_id_ns_array);
		$nett_amount = checkForCreditNoteItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_array,3,$credit_note_id,$unit_id_array,$sales_ledger_id_array,$tax_class_id_array);
	$nett_amount_ns = checkForCreditNoteItemsNSInArray($item_id_ns_array,$amount_ns_array,$discount_ns_array,$tax_group_ns_array,0,NULL,NULL,$ns_sales_ledger_id_array,$ns_tax_class_id_array);
	if($nett_amount=="barcode_transaction_error")
	return "barcode_transaction_error";
	if(USE_BARCODE==1)
	{
		$new_item_id_array = array_filter($item_id_array);
		
		$used_barcode_transactions = getUsedBarcodeForTransaction($credit_note_id,3);
		$used_barcode_transactions_to_match_array = array();
		$i=0;
		$transaction_date = $trans_date;
		if(isset($transaction_date) && validateForNull($transaction_date))
			{
		    $transaction_date = str_replace('/', '-', $transaction_date);
			$transaction_date=date('Y-m-d',strtotime($transaction_date));
			}	
		foreach($used_barcode_transactions as $used_barcode_transaction)
		{
			$used_barcode_transactions_to_match_array[$i][]=$used_barcode_transaction['item_id'];
			$used_barcode_transactions_to_match_array[$i][]=$used_barcode_transaction['barcode'];
			$i++;
			$corresponding_key = -1;
			$corresponding_key= array_search(array($used_barcode_transaction['item_id'],$used_barcode_transaction['barcode']),$item_id_array);
			
			$succedding_transaction = getSucceddingTransactionForBarcode($used_barcode_transaction['barcode'],$used_barcode_transaction['trans_id'],$used_barcode_transaction['trans_type']);
			
			$succedding_transaction_date = date('Y-m-d',strtotime($succedding_transaction['trans_date']));
			
			if(strtotime($transaction_date)>strtotime($succedding_transaction_date))
			{
			return "succedding_transaction_error";
			}
			if(!$corresponding_key || $corresponding_key<0)
			{
				
				return "barcode_item_used_error";
			}
			else if($corresponding_key>0)
			{	
				$rate=$rate_array[$corresponding_key];
				
				$quantity=$quantity_array[$corresponding_key];
				
				$discount=$discount_array[$corresponding_key];
				
				$tax_group_id = $tax_group_array[$corresponding_key];
				
				if(checkForNumeric($rate,$quantity,$discount,$tax_group_id) && $rate>=0 && $discount>=0 && $quantity>0 && $tax_group_id>=0)
				{
					
				}
				else
				{
					
				return "barcode_item_used_error";
				}
			}
		}
		
		
	}
	
	if(($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0) || ($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0))
	{
	
	$total_nett_amount=0;	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount;
	if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount_ns;	
		
	deleteInventoryItemsForCreditNote($credit_note_id,0);	
	deleteNonStockItemsForCreditNote($credit_note_id);
	
		if(TAX_CLASS==1)
	{
		$from_ledger=NULL;
		$i=0;
		
		while((!checkForNumeric($from_ledger) || $from_ledger==-1))
		{
			if(is_numeric($sales_ledger_id_array[$i]))
			$from_ledger = $sales_ledger_id_array[$i];
		     
			 $i++;
			
			if((is_numeric($from_ledger) && $from_ledger>0) || $i>count($sales_ledger_id_array))
			break;
		}	
		
		if(!checkForNumeric($from_ledger) || $from_ledger==-1)
		{
			
			$i=0;
			while((!checkForNumeric($from_ledger) || $from_ledger==-1))
			{
				if(is_numeric($ns_sales_ledger_id_array[$i]))
			    $from_ledger = $ns_sales_ledger_id_array[$i];
				
				$i++;
				
				
				if((is_numeric($from_ledger) && $from_ledger>0) || $i>count($sales_ledger_id_array))
			    break;
			
			}	
			
		}
		
		if(!checkForNumeric($from_ledger))
		return "error";
		
	}
	
	updateCreditNote($credit_note_id,$total_nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$credit_note_ref,$credit_note_ref_type);
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)	
	insertInventoryItemsToCreditNote($item_id_array,$rate_array,$quantity_array,$discount_array,$credit_note_id,$tax_group_array,$godown_id_array,0,$unit_id_array,$sales_ledger_id_array,$tax_class_id_array);
	if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
	insertNonStocksToCreditNote($item_id_ns_array,$amount_ns_array,$discount_ns_array,$credit_note_id,$tax_group_ns_array,0,NULL,NULL,$ns_sales_ledger_id_array,$ns_tax_class_id_array);
	$tax_form_id=getTaxFormIdForTransId($credit_note_id,3);
	deleteTransTaxFormByTransId($credit_note_id,3);
	if(checkForNumeric($tax_form_id))
	insertTransTaxForm('NULL','NULL',$credit_note_id,'NULL',$tax_form_id,$form_no,$form_date);
	return "success";
	}
	return "error";
		
		
	}
}

function insertInventoryItemToCreditNote($item_id,$rate,$quantity,$discount,$credit_note_id,$godown_id,$tax_group_id,$warranty=0,$barcode,$unit_id=NULL,$sales_ledger_id = NULL,$tax_class_id = NULL)
{
	
	if(!validateForNull($warranty))
	$warranty=0;
	if(checkForNumeric($item_id,$rate,$quantity,$discount,$credit_note_id,$godown_id,$tax_group_id,$warranty) && $tax_group_id>=0 && $godown_id>0 && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0 && (TAX_CLASS==0 || (TAX_CLASS==1 && checkForNumeric($sales_ledger_id,$tax_class_id))))
			{
				
				if($item['use_barcode']==1 && !validateForNull($barcode))
				return false;
				
				if($item['use_barcode']==1 && !checkForNumeric(getBarcodeTransactionFromBarcode($barcode,$item_id))) 
				return false;
				if($item['use_barcode']==1)
				$quantity=1;
				
				if(!checkForNumeric($unit_id) && $unit_id<0)
				$unit_id=getNotAvailableItemUnit();
				
				$base_rate_quantity_array=ConvertRateAndQuantityForItemAndItemUnitId($rate,$quantity,$item_id,$unit_id);
				
				$base_rate = $base_rate_quantity_array[0];
				$base_quantity = $base_rate_quantity_array[1];
				
				$amount = $quantity * $rate;
				$nett_amount = $amount - $amount*($discount/100);
				
				if($tax_group_id>0)
				{
				$tax_group = getTaxGroupByID($tax_group_id);
				if($tax_group['in_out']==3)
				{
					$total_tax_percent = getTotalTaxPercentForTaxGroup($tax_group_id);
					$base_amount = GetBaseAmountFromMRP($total_tax_percent,$nett_amount); 
					$nett_amount = $base_amount;
					
				}
				}
				
				$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
				
				if($tax_class_id==0)
				$tax_class_id="NULL";
				
				$sql="INSERT INTO edms_ac_credit_note_item (item_id,rate,quantity,discount,amount,net_amount,credit_note_id,godown_id,warranty,created_by,last_updated_by,date_added,date_modified,ledger_id,tax_class_id) VALUES ($item_id,$base_rate,$base_quantity,$discount,$amount,$nett_amount,$credit_note_id,$godown_id,$warranty,$admin_id,$admin_id,NOW(),NOW(),$sales_ledger_id,$tax_class_id)";
				dbQuery($sql);
				$sales_item_id= dbInsertId();
				insertTransItemUnit(NULL,NULL,$sales_item_id,NULL,NULL,$unit_id,$item_id,$rate,$quantity);
				insertInventoryBarcodeTransaction($credit_note_id,$sales_item_id,3,$item_id,$quantity,$barcode);
				return $sales_item_id;
			}	
		return false;	
	
}

function deleteInventoryItemsForCreditNote($credit_note_id,$check_barcode_in_use=1)
{
	if(checkForNumeric($credit_note_id))
	{
		deleteTaxForCreditNote($credit_note_id);
		
		if(USE_BARCODE==1)
		{
			deleteInventoryBarcodeTransactionByTransId($credit_note_id,3,$check_barcode_in_use);
		}
		$sql="DELETE FROM edms_ac_credit_note_item WHERE credit_note_id = $credit_note_id";
		dbQuery($sql);
		return "success";
	}	
	return "error";
}

function insertInventoryItemsToCreditNote($item_id_array,$rate_array,$quantity_array,$discount_array,$credit_note_id,$tax_group_array,$godown_id_array,$warranty=0,$unit_id_array=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL)
{
	if(!validateForNull($warranty))
	$warranty=0;
	
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			$item_id=$item_id_array[$i];
			
		    if(!is_numeric($item_id) && is_array($item_id) && checkForNumeric($item_id[0]))
			{
				$barcode = $item_id[1];
				$item_id=$item_id[0];
			}
			
			
			$rate=$rate_array[$i];
			$quantity=$quantity_array[$i];
			$discount=$discount_array[$i];
			$tax_group_id = $tax_group_array[$i];
			$godown_id = $godown_id_array[$i];
			if(checkForNumeric($unit_id_array[$i]) && is_array($unit_id_array))
			$unit_id = $unit_id_array[$i];
			else
			$unit_id = getNotAvailableItemUnit();
			if(TAX_CLASS==1)
			{
			$sales_ledger_id = $sales_ledger_id_array[$i];
			$tax_class_id = $tax_class_id_array[$i];
			}
			else
			{
				$sale=getCreditNoteById($credit_note_id);
				$sales_ledger_id = $sale['from_ledger_id'];
				$tax_class_id="NULL";
			}
			
			
			if(checkForNumeric($item_id,$rate,$quantity,$discount,$credit_note_id,$godown_id,$warranty) && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0 && $tax_group_id>=0 && (TAX_CLASS==0 || (TAX_CLASS==1 && checkForNumeric($sales_ledger_id,$tax_class_id))))
			{
				$net_amount = (($rate - $rate*($discount/100))*$quantity);
				
				if($tax_group_id>0)
				{
				$tax_group = getTaxGroupByID($tax_group_id);
				if($tax_group['in_out']==3)
				{
					$total_tax_percent = getTotalTaxPercentForTaxGroup($tax_group_id);
					$base_amount = GetBaseAmountFromMRP($total_tax_percent,$net_amount); 
					$net_amount = $base_amount;
					
				}
				}
				
				
				$credit_note_item_id=insertInventoryItemToCreditNote($item_id,$rate,$quantity,$discount,$credit_note_id,$godown_id,$tax_group_id,$warranty,$barcode,$unit_id,$sales_ledger_id,$tax_class_id);
				
				if($tax_group_id>0)
				insertTaxToCreditNote($credit_note_id,$credit_note_item_id,$tax_group_id,$net_amount);
			}	
			
		}
		return "success";
				
	}
	
	return "error";
	
}
	
function getInventoryItemForCreditNoteId($credit_note_id)
{
	if(checkForNumeric($credit_note_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_credit_note_item.credit_note_item_id,edms_ac_credit_note_item.item_id,rate,quantity,discount,amount,net_amount,edms_ac_credit_note_item.credit_note_id,godown_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount,barcode_transaction_id,barcode,edms_ac_credit_note_item.ledger_id,edms_ac_credit_note_item.tax_class_id FROM edms_ac_credit_note_item LEFT JOIN edms_ac_credit_note_tax ON edms_ac_credit_note_item.credit_note_item_id = edms_ac_credit_note_tax.credit_note_item_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_credit_note_tax.tax_group_id
		LEFT JOIN edms_barcode_transactions ON edms_ac_credit_note_item.credit_note_item_id = edms_barcode_transactions.trans_item_id AND trans_type = 3
		 WHERE edms_ac_credit_note_item.credit_note_id = $credit_note_id GROUP BY edms_ac_credit_note_item.credit_note_item_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$credit_note_item_id = $re['credit_note_item_id'];
				$tax = getTaxForCreditNoteItemId($credit_note_item_id);
				$return_array[$i]['credit_note_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getInventoryItemRegularForCreditNoteId($credit_note_id)
{
	if(checkForNumeric($credit_note_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_credit_note_item.credit_note_item_id,item_id,rate,quantity,discount,amount,net_amount,edms_ac_credit_note_item.credit_note_id,godown_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount, edms_ac_credit_note_item.tax_class_id, edms_ac_credit_note_item.ledger_id, barcode_transaction_id,barcode FROM edms_ac_credit_note_item 
		LEFT JOIN edms_ac_credit_note_tax ON edms_ac_credit_note_item.credit_note_item_id = edms_ac_credit_note_tax.credit_note_item_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_credit_note_tax.tax_group_id WHERE edms_ac_credit_note_item.credit_note_id = $credit_note_id AND warranty=0  GROUP BY edms_ac_credit_note_item.credit_note_item_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$credit_note_item_id = $re['credit_note_item_id'];
				$tax = getTaxForCreditNoteItemId($credit_note_item_id);
				$return_array[$i]['credit_note_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	


function getInventoryItemWarrantyForCreditNoteId($credit_note_id)
{
	if(checkForNumeric($credit_note_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_credit_note_item.credit_note_item_id,item_id,rate,quantity,discount,amount,net_amount,edms_ac_credit_note_item.credit_note_id,godown_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_credit_note_item
		LEFT JOIN edms_ac_credit_note_tax ON edms_ac_credit_note_item.credit_note_item_id = edms_ac_credit_note_tax.credit_note_item_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_credit_note_tax.tax_group_id WHERE edms_ac_credit_note_item.credit_note_id = $credit_note_id AND warranty=1  GROUP BY edms_ac_credit_note_item.credit_note_item_id ";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$credit_note_item_id = $re['credit_note_item_id'];
				$tax = getTaxForCreditNoteItemId($credit_note_item_id);
				$return_array[$i]['credit_note_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	


function getNonStockItemForCreditNoteId($credit_note_id)
{
	if(checkForNumeric($credit_note_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_credit_note_nonstock.credit_note_non_stock_id,item_id,discount,amount,net_amount,edms_ac_credit_note_nonstock.credit_note_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount, edms_ac_credit_note_nonstock.ledger_id, edms_ac_credit_note_nonstock.tax_class_id FROM edms_ac_credit_note_nonstock 
		LEFT JOIN edms_ac_credit_note_tax ON edms_ac_credit_note_nonstock.credit_note_non_stock_id = edms_ac_credit_note_tax.credit_note_non_stock_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_credit_note_tax.tax_group_id  
		WHERE edms_ac_credit_note_nonstock.credit_note_id = $credit_note_id GROUP BY edms_ac_credit_note_nonstock.credit_note_non_stock_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$credit_note_item_id = $re['credit_note_non_stock_id'];
				$tax = getTaxForCreditNoteItemId($credit_note_item_id,true);
				$return_array[$i]['credit_note_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	


function getNonStockItemOurForCreditNoteId($credit_note_id)
{
	if(checkForNumeric($credit_note_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_credit_note_nonstock.credit_note_non_stock_id,item_id,discount,amount,net_amount,edms_ac_credit_note_nonstock.credit_note_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount, edms_ac_credit_note_nonstock.tax_class_id, edms_ac_credit_note_nonstock.ledger_id FROM edms_ac_credit_note_nonstock
		LEFT JOIN edms_ac_credit_note_tax ON edms_ac_credit_note_nonstock.credit_note_non_stock_id = edms_ac_credit_note_tax.credit_note_non_stock_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_credit_note_tax.tax_group_id  WHERE edms_ac_credit_note_nonstock.credit_note_id = $credit_note_id  AND ns_type=0 GROUP BY edms_ac_credit_note_nonstock.credit_note_non_stock_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$credit_note_item_id = $re['credit_note_non_stock_id'];
				$tax = getTaxForCreditNoteItemId($credit_note_item_id,true);
				$return_array[$i]['credit_note_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getNonStockItemOutSideJobForCreditNoteId($credit_note_id)
{
	if(checkForNumeric($credit_note_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_credit_note_nonstock.credit_note_non_stock_id,item_id,discount,amount,net_amount, edms_ac_credit_note_nonstock.credit_note_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_credit_note_nonstock
		LEFT JOIN edms_ac_credit_note_tax ON edms_ac_credit_note_nonstock.credit_note_non_stock_id = edms_ac_credit_note_tax.credit_note_non_stock_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_credit_note_tax.tax_group_id  WHERE edms_ac_credit_note_nonstock.credit_note_id = $credit_note_id  AND ns_type=1 GROUP BY edms_ac_credit_note_nonstock.credit_note_non_stock_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$credit_note_item_id = $re['credit_note_non_stock_id'];
				$tax = getTaxForCreditNoteItemId($credit_note_item_id,true);
				$return_array[$i]['credit_note_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getTaxForCreditNoteItemId($credit_note_item_id,$non_stock=false)
{
	if(checkForNumeric($credit_note_item_id))
	{
		$sql="SELECT credit_note_id, edms_tax_grp.tax_group_id, tax_group_name, SUM(tax_amount) as tax_amount, edms_tax.tax_id, CONCAT(IF(edms_tax.in_out>0,'OUT','IN'), ' ', tax_name) as tax_name_in_out, tax_name, edms_tax.in_out, tax_percent FROM edms_ac_credit_note_tax, edms_tax_grp, edms_tax WHERE edms_tax_grp.tax_group_id = edms_ac_credit_note_tax.tax_group_id AND edms_tax.tax_id = edms_ac_credit_note_tax.tax_id AND
		";
		if(!$non_stock)
		$sql=$sql." edms_ac_credit_note_tax.credit_note_item_id = $credit_note_item_id GROUP BY tax_id";
		else if($non_stock)
		$sql=$sql." edms_ac_credit_note_tax.credit_note_non_stock_id = $credit_note_item_id GROUP BY tax_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		
		}
	return false;
}

?>