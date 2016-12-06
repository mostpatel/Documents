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
require_once("account-debit-note-functions.php");
require_once("tax-functions.php");
require_once("delivery-challan-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");

function checkForNSItemsInArrayDebitNote($item_id_array,$amount_array,$discount_array,$tax_group_id_array,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL)
{
	$total_amount=0;
	$has_items=false;
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			$item_id=$item_id_array[$i];
			$amount = $amount_array[$i];
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
			
			
			if(checkForNumeric($item_id,$amount,$discount,$tax_group_id) && $item_id>0 && $amount>=0 && $discount>=0 && $tax_group_id>=0 && ((TAX_CLASS==1 && checkForNumeric($tax_class_id,$sales_ledger_id) && $tax_class_id>=0 && $sales_ledger_id>0) || TAX_CLASS==0))
			{
				
				
				$amount = $amount - ($amount*($discount/100));
				
				$total_amount = $total_amount + $amount;
				
				if($tax_group_id>0)
				{
				$tax_group = getTaxGroupByID($tax_group_id);
				if($tax_group['in_out']==2)
				{
					$total_tax_percent = getTotalTaxPercentForTaxGroup($tax_group_id);
					$total_tax_amount = ($amount*($total_tax_percent/100));
					$total_amount = $total_amount + $total_tax_amount;	
				}
				}
				$has_items = $total_amount;
			}	
			
		}
				
	}
	return $has_items;
	
}

function updateNonStockItemDebitNote($debit_note_id,$item_id_array,$amount_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$tax_group_array,$debit_note_ref="NA",$debit_note_ref_type=0)
{
	if(checkForNumeric($debit_note_id))
	{
		$nett_amount = checkForNSItemsInArrayDebitNote($item_id_array,$amount_array,$discount_array,$tax_group_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	{
	deleteNonStockItemsForDebitNote($debit_note_id);	
	updateDebitNote($debit_note_id,$nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,$debit_note_ref,$debit_note_ref_type);
	insertNonStocksToDebitNote($item_id_array,$amount_array,$discount_array,$debit_note_id,$tax_group_array);
	return "success";
	}
	return "error";
		
		
	}
}



function insertNonStockDebitNote($item_id_array,$amount_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$tax_group_array,$debit_note_ref="NA",$debit_note_ref_type=0)
{
	
	
	$nett_amount = checkForNSItemsInArrayDebitNote($item_id_array,$amount_array,$discount_array,$tax_group_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>0)
	{
	$debit_note_id = addDebitNote($nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$debit_note_ref,$debit_note_ref_type);
	if(checkForNumeric($debit_note_id))
	{
	insertNonStocksToDebitNote($item_id_array,$amount_array,$discount_array,$debit_note_id,$tax_group_array);
	}
	else
	return "error";
	
	return $debit_note_id;
	}
	return "error";
}

function insertNonStockToDebitNote($item_id,$amount,$discount,$debit_note_id,$tax_group_id,$sales_ledger_id = NULL,$tax_class_id = NULL)
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
	
	if(checkForNumeric($amount,$discount,$tax_group_id) &&  $item_id>0  && $discount>=0  && $tax_group_id>=0 && (is_numeric($item_id) || is_numeric($expense_id))  && (TAX_CLASS==0 || (TAX_CLASS==1 && checkForNumeric($sales_ledger_id,$tax_class_id))))
			{
				
			
				$nett_amount = $amount - $amount*($discount/100);
				
				if($tax_group_id>0)
				{
				$tax_group = getTaxGroupByID($tax_group_id);
				if($tax_group['in_out']==2)
				{
					$total_tax_percent = getTotalTaxPercentForTaxGroup($tax_group_id);
					$total_tax_amount = ($nett_amount*($total_tax_percent/100));
					$nett_amount = $nett_amount + $total_tax_amount;	
					
				}
				}
				
				$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
				
				if($tax_class_id==0)
				$tax_class_id="NULL";
				$sql="INSERT INTO edms_ac_debit_note_nonstock (item_id,amount,discount,net_amount,debit_note_id,created_by,last_updated_by,date_added,date_modified,tax_class_id,ledger_id,expense_id) VALUES ($item_id,$amount,$discount,$nett_amount,$debit_note_id,$admin_id,$admin_id,NOW(),NOW(),$tax_class_id,$sales_ledger_id,$expense_id)";
				dbQuery($sql);
				return dbInsertId();
			}	
		return false;		
}

function deleteNonStockItemsForDebitNote($debit_note_id)
{
	if(checkForNumeric($debit_note_id))
	{
		deleteTaxForDebitNote($debit_note_id);
		$sql="DELETE FROM edms_ac_debit_note_nonstock WHERE debit_note_id = $debit_note_id";
		dbQuery($sql);
		return "success";
	}	
	return "error";
}

function insertNonStocksToDebitNote($item_id_array,$amount_array,$discount_array,$debit_note_id,$tax_group_array,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL)
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
				$sale=getDebitNoteById($sales_id);
				$sales_ledger_id = $sale['to_ledger_id'];
				$tax_class_id="NULL";
			}
			
						
			if(checkForNumeric($amount,$discount,$debit_note_id,$tax_group_id) && $item_id>0 && $amount>=0 && $discount>=0 &&  $tax_group_id>=0 && (is_numeric($item_id) || is_numeric($expense_id))  && (TAX_CLASS==0 || (TAX_CLASS==1 && checkForNumeric($sales_ledger_id,$tax_class_id))))
			{
				$net_amount = $amount - $amount*($discount/100);
				
				$debit_note_item_id=insertNonStockToDebitNote($item_id,$amount,$discount,$debit_note_id,$tax_group_id,$sales_ledger_id,$tax_class_id);
				
				if($tax_group_id>0)
				insertTaxToDebitNote($debit_note_id,$debit_note_item_id,$tax_group_id,$net_amount,true);
			}	
			
		}
		
		return "success";
				
	}
	
	return "error";
	
}
	
?>