<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("inventory-item-functions.php");
require_once("item-type-functions.php");
require_once("item-manufacturer-functions.php");
require_once("tax-functions.php");
require_once("godown-functions.php");
require_once("account-ledger-functions.php");
require_once("account-functions.php");
require_once("account-jv-functions.php");
require_once("account-credit-note-functions.php");
require_once("tax-functions.php");
require_once("delivery-challan-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");

function checkForCreditNoteItemsNSInArray($item_id_array,$amount_array,$discount_array,$tax_group_id_array,$ns_type,$out_side_labour_provider_array,$our_rate_array,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL)
{
	$total_amount=0;
	$has_items=false;
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			$item_id=$item_id_array[$i];
			$amount=$amount_array[$i];
			$discount=$discount_array[$i];
			$tax_group_id = $tax_group_id_array[$i];
			
			$sales_ledger_id = $sales_ledger_id_array[$i];
			$tax_class_id = $tax_class_id_array[$i];
		
			
			
			if(!checkForNumeric($item_id))
			{
				if(substr($item_id, 0, 1) == 'E')
				{
					$item_id=str_replace('E','',$item_id);
					$item_id=intval($item_id);
				}
				
			}
			
			if($ns_type==1)
			{
			$out_side_labour_provider = $out_side_labour_provider_array[$i];
			$our_rate = $our_rate_array[$i];
			}
			else
			{
			$out_side_labour_provider=0;
			}
			if(checkForNumeric($item_id,$amount,$discount,$tax_group_id,$out_side_labour_provider) && (($ns_type==1 && $out_side_labour_provider>0 && $our_rate>=0) || $ns_type==0) && $item_id>0 && $amount>=0 && $discount>=0  && $tax_group_id>=0 && ((TAX_CLASS==1 && checkForNumeric($tax_class_id,$sales_ledger_id) && $tax_class_id>=0 && $sales_ledger_id>0) || TAX_CLASS==0))
			{
				$amount = $amount - $amount*($discount/100);
				$total_amount = $total_amount + $amount;
				
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

function insertNonStockCreditNote($item_id_array,$amount_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$tax_group_array,$credit_note_ref="NA",$credit_note_ref_type=0,$ns_type=0,$out_side_labour_provider_array=NULL,$our_rate_array=NULL) // ns_type=1 for outside labour
{
		
	$nett_amount = checkForCreditNoteItemsNSInArray($item_id_array,$amount_array,$discount_array,$tax_group_array,$ns_type,$out_side_labour_provider_array,$our_rate_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	{
	$credit_note_id = insertCreditNote($nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$credit_note_ref,$credit_note_ref_type);
	if(checkForNumeric($credit_note_id))
	{
	insertNonStocksToCreditNote($item_id_array,$amount_array,$discount_array,$credit_note_id,$tax_group_array,$ns_type,$out_side_labour_provider_array,$our_rate_array);
	}
	else
	return "error";

	return $credit_note_id;
	}
	return "error";
}

function updateNonStockCreditNote($credit_note_id,$item_id_array,$amount_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$tax_group_array,$credit_note_ref="NA",$credit_note_ref_type=0,$ns_type=0,$out_side_labour_provider_array=NULL,$our_rate_array=NULL)
{
	if(checkForNumeric($credit_note_id))
	{
		$nett_amount = checkForCreditNoteItemsNSInArray($item_id_array,$amount_array,$discount_array,$tax_group_array,$ns_type,$out_side_labour_provider_array,$our_rate_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	{
	deleteNonStockItemsForCreditNote($credit_note_id);	
	updateCreditNote($credit_note_id,$nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$credit_note_ref,$credit_note_ref_type);
	insertNonStocksToCreditNote($item_id_array,$amount_array,$discount_array,$credit_note_id,$tax_group_array,$ns_type,$out_side_labour_provider_array,$our_rate_array);

	return "success";
	}
	return "error";
		
		
	}
}

function insertNonStockToCreditNote($item_id,$amount,$discount,$credit_note_id,$tax_group_id,$ns_type=0,$out_side_labour_provider=0,$our_rate=0,$sales_ledger_id = NULL,$tax_class_id = NULL)
{
	if(!checkForNumeric($item_id))
			{
				if(substr($item_id, 0, 1) == 'E')
				{
					$expense_id=str_replace('E','',$item_id);
					$expense_id=intval($expense_id);
				}
				
				$item_id="NULL";
				
			}
			else
			$expense_id = "NULL";
	
	if(checkForNumeric($amount,$discount,$credit_note_id,$tax_group_id,$ns_type) && (($ns_type==1 && checkForNumeric($out_side_labour_provider,$our_rate) && $out_side_labour_provider>0 && $our_rate>=0) || $ns_type==0) && $tax_group_id>=0  && $item_id>0 && $amount>=0 && $discount>=0 && (is_numeric($item_id) || is_numeric($expense_id))  && (TAX_CLASS==0 || (TAX_CLASS==1 && checkForNumeric($sales_ledger_id,$tax_class_id))))
			{
							
				
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
				$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
				$credit_note=getCreditNoteById($credit_note_id);
				$trans_date = $credit_note['trans_date'];
				$outside_job_ledger = getOutSideJobLedgerIdForOC($oc_id);
				
				if($tax_class_id==0)
				$tax_class_id="NULL";
				
				$sql="INSERT INTO edms_ac_credit_note_nonstock (item_id,amount,discount,net_amount,credit_note_id,ns_type,created_by,last_updated_by,date_added,date_modified,tax_class_id,ledger_id,expense_id) VALUES ($item_id,$amount,$discount,$nett_amount,$credit_note_id,$ns_type,$admin_id,$admin_id,NOW(),NOW(),$tax_class_id,$sales_ledger_id,$expense_id)";
				dbQuery($sql);
				$credit_note_non_stock_id = dbInsertId();
				
				if(checkForNumeric($credit_note_non_stock_id) && $ns_type==1)
				{
					addJV($our_rate,$trans_date,'L'.$outside_job_ledger,'L'.$out_side_labour_provider,'',3,$credit_note_non_stock_id);
				}
				
				return $credit_note_non_stock_id;
			}	
		return false;	
	
}

function deleteNonStockItemsForCreditNote($credit_note_id)
{
	if(checkForNumeric($credit_note_id))
	{
		$non_stock_id_array = getNonStockIdsForCreditNoteId($credit_note_id);
		if($non_stock_id_array && is_array($non_stock_id_array) && count($non_stock_id_array))
		{
			foreach($non_stock_id_array as $non_stock_id)
			{
				$jv=getOutSideLabourJVForNonStockId($non_stock_id);
				if($jv && is_array($jv))
				{
				$jv_id = $jv['jv_id'];
				if(is_numeric($jv_id))
				removeJV($jv_id);
				}
			}	
		}
		deleteTaxForCreditNote($credit_note_id);
		$sql="DELETE FROM edms_ac_credit_note_nonstock WHERE credit_note_id = $credit_note_id";
		dbQuery($sql);
		return "success";
	}	
	return "error";
}

function getNonStockIdsForCreditNoteId($credit_note_id)
{
	if(checkForNumeric($credit_note_id))
	{
		$sql="SELECT GROUP_CONCAT(credit_note_non_stock_id) FROM edms_ac_credit_note_nonstock WHERE credit_note_id=$credit_note_id GROUP BY credit_note_id";
		$result = dbQuery($sql);
		if(dbNumRows($result)>0)
		{
		$resultArray = dbResultToArray($result);
		$credit_note_non_stock_id_string = $resultArray[0][0];
		return explode(',',$credit_note_non_stock_id_string);
		}
		
	}
	return false;
}

function insertNonStocksToCreditNote($item_id_array,$amount_array,$discount_array,$credit_note_id,$tax_group_array,$ns_type=0,$outside_labour_provider_array=NULL,$our_rate_array=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL)
{
	
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			
			$item_id=$item_id_array[$i];
			$amount=$amount_array[$i];
			$discount=$discount_array[$i];
			$tax_group_id = $tax_group_array[$i];
			
			if(!checkForNumeric($item_id))
			{
				if(substr($item_id, 0, 1) == 'E')
				{
					$expense_id=str_replace('E','',$item_id);
					$expense_id=intval($expense_id);
				}
				
				$item_id="NULL";
				
			}
			else
			$expense_id = "NULL";
			
			if(TAX_CLASS==1)
			{
			$sales_ledger_id = $sales_ledger_id_array[$i];
			$tax_class_id = $tax_class_id_array[$i];
			}
			else
			{
				$sale=getCreditNoteById($sales_id);
				$sales_ledger_id = $sale['from_ledger_id'];
				$tax_class_id="NULL";
			}
			
			if($ns_type==1)
			{
			$outside_labour_provider = $outside_labour_provider_array[$i];
			$our_rate = $our_rate_array[$i];
			}
			else
			$outside_labour_provider=NULL;
			
			if(checkForNumeric($amount,$discount,$credit_note_id,$ns_type) && (($ns_type==1 && $outside_labour_provider>0 && $our_rate>=0) || $ns_type==0) && $item_id>0 && $amount>=0 && $discount>=0  && $tax_group_id>=0 &&  $tax_group_id>=0 && (is_numeric($item_id) || is_numeric($expense_id))  && (TAX_CLASS==0 || (TAX_CLASS==1 && checkForNumeric($sales_ledger_id,$tax_class_id))))
			{
				$net_amount = $amount - $amount*($discount/100);
				
				
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
				
				$credit_note_item_id=insertNonStockToCreditNote($item_id,$amount,$discount,$credit_note_id,$tax_group_id,$ns_type,$outside_labour_provider,$our_rate,$sales_ledger_id,$tax_class_id);
				
				if($tax_group_id>0)
				insertTaxToCreditNote($credit_note_id,$credit_note_item_id,$tax_group_id,$net_amount,true);
			}	
			
		}
		return "success";
				
	}
	
	return "error";
	
}
	

?>