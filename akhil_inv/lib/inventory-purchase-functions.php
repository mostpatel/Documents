<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("inventory-item-functions.php");
require_once("inventory-item-barcode-functions.php");
require_once("item-type-functions.php");
require_once("item-unit-functions.php");
require_once("item-manufacturer-functions.php");
require_once("tax-functions.php");
require_once("godown-functions.php");
require_once("account-ledger-functions.php");
require_once("account-purchase-functions.php");
require_once("nonstock-purchase-functions.php");
require_once("inventory-sales-functions.php");
require_once("tax-functions.php");
require_once("delivery-challan-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");

function checkForItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$godown_id_array,$tax_group_id_array,$unit_id_array=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL)
{
	$total_amount=0;
	$has_items=false;
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			$item_id=$item_id_array[$i];
			$rate=$rate_array[$i];
			$quantity=$quantity_array[$i];
			$discount=$discount_array[$i];
			$godown_id = $godown_id_array[$i];
			$tax_group_id = $tax_group_id_array[$i];
			$unit_id = $unit_id_array[$i];
			$saled_ledger_id = $sales_ledger_id_array[$i];
			$tax_class_id = $tax_class_id_array[$i];
			
			if(checkForNumeric($item_id,$rate,$quantity,$discount,$godown_id,$tax_group_id) && $godown_id>0 && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0 && ((TAX_CLASS==1 && checkForNumeric($tax_class_id,$saled_ledger_id) && $tax_class_id>=0 && $saled_ledger_id>0) || TAX_CLASS==0))
			{
				
				
				$amount = ($rate - ($rate*($discount/100)))*$quantity;
				
				$total_amount = $total_amount + $amount;
				
				if($tax_group_id>0)
				{
				$tax_group = getTaxGroupByID($tax_group_id);
				if($tax_group['in_out']==2)
				{
					$total_tax_percent = getTotalTaxPercentForTaxGroup($tax_group_id);
					$total_tax_amount = ($amount*($total_tax_percent/100));
				//	$total_amount = $total_amount + $total_tax_amount;	
				} 
				}
				$has_items = $total_amount;
			}	
			
		}
				
	}
	return $has_items;
	
}

function checkForItemsInArrayItemwise($item_id_array,$rate_array,$quantity_array,$discount_array,$godown_id_array,$tax_group_id_array,$unit_id_array=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL)
{
	$total_amount=0;
	$has_items=array();
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			$item_id=$item_id_array[$i];
			$rate=$rate_array[$i];
			$quantity=$quantity_array[$i];
			$discount=$discount_array[$i];
			$godown_id = $godown_id_array[$i];
			$tax_group_id = $tax_group_id_array[$i];
			$unit_id = $unit_id_array[$i];
			$saled_ledger_id = $sales_ledger_id_array[$i];
			$tax_class_id = $tax_class_id_array[$i];
			
		
			
			if(checkForNumeric($item_id,$rate,$quantity,$discount,$godown_id,$tax_group_id) && $godown_id>0 && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0 && ((TAX_CLASS==1 && checkForNumeric($tax_class_id,$saled_ledger_id) && $tax_class_id>=0 && $saled_ledger_id>0) || TAX_CLASS==0))
			{
				
				
				$amount = ($rate - ($rate*($discount/100)))*$quantity;
				
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
				if(isset($has_items[$item_id]))
				$has_items[$item_id] = $has_items[$item_id] + $quantity ;
				else
				$has_items[$item_id] = $quantity;
			}	
			
		}
				
	}
	return $has_items;
	
	}


function updateInventoryNonStockItemPurchase($purchase_id,$item_id_array,$rate_array,$quantity_array,$discount_array,$item_id_ns_array,$amount_ns_array,$discount_ns_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$tax_group_ns_array,$sales_ref="NA",$sales_ref_type=0,$unit_id_array=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL,$ns_sales_ledger_id_array=NULL,$ns_tax_class_id_array=NULL,$form_no=NULL,$form_date=NULL,$vch_no=NULL,$bill_no="",$bill_date='01/01/1970')
{
	if(checkForNumeric($purchase_id))
	{
		$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array,1);
	    $item_id_ns_array = ConvertItemNameArrayInToIdArray($item_id_ns_array);
		
		if(USE_BARCODE==1)
		{
			
			$used_barcode_transaction_item_wise=getUsedBarcodeForTransactionItemWise($purchase_id,1);
			$unused_barcode_transaction_item_wise = getUnUsedBarcodeForTransactionItemWise($purchase_id,1);
			
			$new_item_array_item_wise = checkForItemsInArrayItemwise($item_id_array,$rate_array,$quantity_array,$discount_array,$godown_id_array,$tax_group_array,$unit_id_array,$sales_ledger_id_array,$tax_class_id_array);
			
			foreach($used_barcode_transaction_item_wise as $item_id=>$transactions)
			{
			
				
				if(isset($new_item_array_item_wise[$item_id]))
				{
					
					if($new_item_array_item_wise[$item_id] < count($transactions))
					{
						return "barcode_item_error";
					}
				}
				else 
				return "barcode_item_error";
		
				
			}
			
			
		}
	
		$nett_amount = checkForItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$godown_id_array,$tax_group_array,$unit_id_array,$sales_ledger_id_array,$tax_class_id_array);
		$nett_amount_ns = checkForNSItemsInArray($item_id_ns_array,$amount_ns_array,$discount_ns_array,$tax_group_ns_array,$ns_sales_ledger_id_array,$ns_tax_class_id_array);
		
		

	
	
	if(($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0) || ($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0))
	{
	$total_nett_amount=0;	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount;
	if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount_ns;
	
		if(TAX_CLASS==1)
	{
		$to_ledger=NULL;
		$i=0;
		
		while((!checkForNumeric($to_ledger) || $to_ledger==-1))
		{
			if(is_numeric($sales_ledger_id_array[$i]))
			$to_ledger = $sales_ledger_id_array[$i];
		     
			 $i++;
			
			if((is_numeric($to_ledger) && $to_ledger>0) || $i>count($sales_ledger_id_array))
			break;
		}	
		
		if(!checkForNumeric($to_ledger) || $to_ledger==-1)
		{
			
			$i=0;
			while((!checkForNumeric($to_ledger) || $to_ledger==-1))
			{
				if(is_numeric($ns_sales_ledger_id_array[$i]))
			    $to_ledger = $ns_sales_ledger_id_array[$i];
				
				$i++;
				
				
				if((is_numeric($to_ledger) && $to_ledger>0) || $i>count($sales_ledger_id_array))
			    break;
			
			}	
			
		}
		
		if(!checkForNumeric($to_ledger))
		return "error";
		
	}
	
	
	
	
	$result=updatePurchase($purchase_id,$total_nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$sales_ref,$sales_ref_type,$bill_no,$bill_date);
	if($result=="success")
	{
	deleteInventoryItemsForPurchase($purchase_id,0); // second parameter 0 means barcode in use check off	
	deleteNonStockItemsForPurchase($purchase_id);	
	insertInventoryItemsToPurchase($item_id_array,$rate_array,$quantity_array,$discount_array,$purchase_id,$tax_group_array,$godown_id_array,$used_barcode_transaction_item_wise,$unused_barcode_transaction_item_wise,$unit_id_array,$sales_ledger_id_array,$tax_class_id_array);
	insertNonStocksToPurchase($item_id_ns_array,$amount_ns_array,$discount_ns_array,$purchase_id,$tax_group_ns_array,$ns_sales_ledger_id_array,$ns_tax_class_id_array);
	
	$tax_form_id=getTaxFormIdForTransId($purchase_id,1);
	deleteTransTaxFormByTransId($purchase_id,1);
	if(checkForNumeric($tax_form_id))
	insertTransTaxForm($purchase_id,'NULL','NULL','NULL',$tax_form_id,$form_no,$form_date);
	return "success";
	}
	
	}
	return "error";
		
		
	}
}

function updateInventoryItemPurchase($purchase_id,$item_id_array,$rate_array,$quantity_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$sales_ref="NA",$sales_ref_type=0)
{
	if(checkForNumeric($purchase_id))
	{
		$nett_amount = checkForItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$godown_id_array,$tax_group_array);
	
	
	if(($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0))
	{
	
	

	
	
	$result = updatePurchase($purchase_id,$nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$sales_ref,$sales_ref_type);
	if($result=="success")
	{
	deleteInventoryItemsForPurchase($purchase_id);	
	insertInventoryItemsToPurchase($item_id_array,$rate_array,$quantity_array,$discount_array,$purchase_id,$tax_group_array,$godown_id_array);
	return "success";
	}
	}
	return "error";
		
		
	}
}


function insertInventoryNonStockItemPurchase($item_id_array,$rate_array,$quantity_array,$discount_array,$item_id_ns_array,$amount_ns_array,$discount_ns_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$tax_group_ns_array,$purchase_ref="NA",$purchase_ref_type=0,$oc_id=NULL,$unit_id_array=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL,$ns_sales_ledger_id_array=NULL,$ns_tax_class_id_array=NULL,$form_no=NULL,$form_date=NULL,$vch_no=NULL,$bill_no="",$bill_date="01/01/1970")
{
	
	$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array,1);
	$item_id_ns_array = ConvertItemNameArrayInToIdArray($item_id_ns_array);
	$nett_amount = checkForItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$godown_id_array,$tax_group_array,$unit_id_array,$sales_ledger_id_array,$tax_class_id_array);
	$nett_amount_ns = checkForNSItemsInArray($item_id_ns_array,$amount_ns_array,$discount_ns_array,$tax_group_ns_array,$ns_sales_ledger_id_array,$ns_tax_class_id_array);
	
	if(($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0) || ($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0))
	{
	$total_nett_amount=0;	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount;
	if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount_ns;
	
	if(TAX_CLASS==1)
	{
		$to_ledger=NULL;
		$i=0;
		
		while((!checkForNumeric($to_ledger) || $to_ledger==-1))
		{
			if(is_numeric($sales_ledger_id_array[$i]))
			$to_ledger = $sales_ledger_id_array[$i];
		     
			 $i++;
			
			if((is_numeric($to_ledger) && $to_ledger>0) || $i>count($sales_ledger_id_array))
			break;
		}	
		
		if(!checkForNumeric($to_ledger) || $to_ledger==-1)
		{
			
			$i=0;
			while((!checkForNumeric($to_ledger) || $to_ledger==-1))
			{
				if(is_numeric($ns_sales_ledger_id_array[$i]))
			    $to_ledger = $ns_sales_ledger_id_array[$i];
				
				$i++;
				
				
				if((is_numeric($to_ledger) && $to_ledger>0) || $i>count($sales_ledger_id_array))
			    break;
			
			}	
			
		}
		
		if(!checkForNumeric($to_ledger))
		return "error";
		
	}
	
	
	$purchase_id = addPurchase($total_nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$purchase_ref,$purchase_ref_type,$oc_id,$vch_no,$bill_no,$bill_date);
	if(checkForNumeric($purchase_id))
	{
	insertInventoryItemsToPurchase($item_id_array,$rate_array,$quantity_array,$discount_array,$purchase_id,$tax_group_array,$godown_id_array,NULL,NULL,$unit_id_array,$sales_ledger_id_array,$tax_class_id_array);
	insertNonStocksToPurchase($item_id_ns_array,$amount_ns_array,$discount_ns_array,$purchase_id,$tax_group_ns_array,$ns_sales_ledger_id_array,$ns_tax_class_id_array);
	
	$tax_form_id=getTaxFormIdForTransId($purchase_id,1);
	
	if(checkForNumeric($tax_form_id))
	insertTransTaxForm($purchase_id,'NULL','NULL','NULL',$tax_form_id,$form_no,$form_date);
	}
	else
	return "error";
	
	return $purchase_id;
	}
	return "error";
}

function insertInventoryItemPurchase($item_id_array,$rate_array,$quantity_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$purchase_ref="NA",$purchase_ref_type=0)
{
	
	$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);

	$nett_amount = checkForItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$godown_id_array,$tax_group_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>0)
	{
	$purchase_id = addPurchase($nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$purchase_ref,$purchase_ref_type);
	if(checkForNumeric($purchase_id))
	{
	insertInventoryItemsToPurchase($item_id_array,$rate_array,$quantity_array,$discount_array,$purchase_id,$tax_group_array,$godown_id_array);
	}
	else
	return "error";
	
	return $purchase_id;
	}
	return "error";
}

function insertInventoryItemToPurchase($item_id,$rate,$quantity,$discount,$purchase_id,$godown_id,$tax_group_id,$barcode_array,$unit_id=NULL,$sales_ledger_id = NULL,$tax_class_id = NULL)
{
	if(checkForNumeric($item_id,$rate,$quantity,$discount,$godown_id,$tax_group_id) && $godown_id>0 && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0 && $tax_group_id>=0 && (TAX_CLASS==0 || (TAX_CLASS==1 && checkForNumeric($sales_ledger_id,$tax_class_id))))
			{
				
				
				$amount = $quantity * $rate;
				$nett_amount = $amount - $amount*($discount/100);
				
				if(!checkForNumeric($unit_id) && $unit_id<0)
				$unit_id=getNotAvailableItemUnit();
				
				$base_rate_quantity_array=ConvertRateAndQuantityForItemAndItemUnitId($rate,$quantity,$item_id,$unit_id);
				
				$base_rate = $base_rate_quantity_array[0];
				$base_quantity = $base_rate_quantity_array[1];
				
				if($tax_group_id>0)
				{
				$tax_group = getTaxGroupByID($tax_group_id);
			/*	if($tax_group['in_out']==2)
				{
					$total_tax_percent = getTotalTaxPercentForTaxGroup($tax_group_id);
					$total_tax_amount = ($nett_amount*($total_tax_percent/100));
					$nett_amount = $nett_amount + $total_tax_amount;	
					
				} */
				}
				if($tax_class_id==0)
				$tax_class_id="NULL";
			
				$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
				
				
				$sql="INSERT INTO edms_ac_purchase_item (item_id,rate,quantity,discount,amount,net_amount,purchase_id,godown_id,created_by,last_updated_by,date_added,date_modified,ledger_id,tax_class_id) VALUES ($item_id,$base_rate,$base_quantity,$discount,$amount,$nett_amount,$purchase_id,$godown_id,$admin_id,$admin_id,NOW(),NOW(),$sales_ledger_id,$tax_class_id)";
				dbQuery($sql);
				$purchase_item_id = dbInsertId();
				insertTransItemUnit($purchase_item_id,NULL,NULL,NULL,NULL,$unit_id,$item_id,$rate,$quantity);
				if(USE_BARCODE==1)
				{
			
				
				insertInventoryBarcodeTransaction($purchase_id,$purchase_item_id,1,$item_id,$quantity,$barcode_array,$_SESSION['edmsAdminSession']['oc_id']);
				}
				return $purchase_item_id;
			}	
		return false;	
	
}

function deleteInventoryItemsForPurchase($purchase_id,$check_barcode_in_use=1)
{
	if(checkForNumeric($purchase_id))
	{
		deleteTaxForPurchase($purchase_id);
		
		if(USE_BARCODE==1)
		deleteInventoryBarcodeTransactionByTransId($purchase_id,1,$check_barcode_in_use);
		
		$sql="DELETE FROM edms_ac_purchase_item WHERE purchase_id = $purchase_id";
		dbQuery($sql);
		return "success";
	}	
	return "error";
}

function insertInventoryItemsToPurchase($item_id_array,$rate_array,$quantity_array,$discount_array,$purchase_id,$tax_group_array,$godown_id_array,$used_barcode_transaction_item_wise=NULL,$unused_barcode_transaction_item_wise=NULL,$unit_id_array=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL)
{
	
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			$item_id=$item_id_array[$i];
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
				$sale=getPurchaseById($purchase_id);
				$sales_ledger_id = $sale['to_ledger_id'];
				$tax_class_id="NULL";
			}
			
			
			
			if(checkForNumeric($item_id,$rate,$quantity,$discount,$purchase_id,$godown_id,$tax_group_id) && $godown_id>0 && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0 && $tax_group_id>=0 && (TAX_CLASS==0 || (TAX_CLASS==1 && checkForNumeric($sales_ledger_id,$tax_class_id))))
			{
				
				$net_amount = ( ($rate - $rate*($discount/100))*$quantity);
				
				if(USE_BARCODE==1)
				{
				
				    $needed_quantity=$quantity;
					$barcode_array=array();
					if(isset($used_barcode_transaction_item_wise[$item_id]) && is_array($used_barcode_transaction_item_wise[$item_id]) && count($used_barcode_transaction_item_wise[$item_id])>0)	
					{
						$no_of_used_barcodes = count($used_barcode_transaction_item_wise[$item_id]);
						for($n=0;$n<$no_of_used_barcodes;$n++)
						{
							$barcode_transaction = array_shift($used_barcode_transaction_item_wise[$item_id]);
							$barcode_array[] = $barcode_transaction['barcode'];
							$needed_quantity--;
						}	
						
					}
					if(isset($unused_barcode_transaction_item_wise[$item_id]) && is_array($unused_barcode_transaction_item_wise[$item_id]) && count($unused_barcode_transaction_item_wise[$item_id])>0)	
					{
						$no_of_unused_barcodes = count($unused_barcode_transaction_item_wise[$item_id]);
						for($n=0;$n<$no_of_unused_barcodes;$n++)
						{
							if($needed_quantity>0)
							{
							$barcode_transaction = array_shift($unused_barcode_transaction_item_wise[$item_id]);
							$barcode_array[] = $barcode_transaction['barcode'];
							$needed_quantity--;
							}
						}
					}	
				}
				else
				$barcode_array=NULL;
				
				if($needed_quantity>0)
				{
					for($l=0;$l<$needed_quantity;$l++)
					{
						$barcode_array[]=NULL;
					}
					
				}
					
				
				$purchase_item_id=insertInventoryItemToPurchase($item_id,$rate,$quantity,$discount,$purchase_id,$godown_id,$tax_group_id,$barcode_array,$unit_id,$sales_ledger_id,$tax_class_id);
				
				if($tax_group_id>0)
				insertTaxToPurchase($purchase_id,$purchase_item_id,$tax_group_id,$net_amount);
				
				
				
			}	
			
		}
		
		return "success";
				
	}
	
	return "error";
	
}

function getInventoryItemForPurchaseId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_purchase_item.purchase_item_id,edms_ac_purchase_item.item_id,rate,quantity,discount,amount,net_amount,edms_ac_purchase_item.purchase_id,godown_id,created_by,last_updated_by,date_added,date_modified, edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount,GROUP_CONCAT(barcode) as barcode, GROUP_CONCAT(barcode_transaction_id) as barcode_transaction_id, edms_ac_purchase_item.tax_class_id, edms_ac_purchase_item.ledger_id FROM edms_ac_purchase_item 
		LEFT JOIN edms_ac_purchase_tax ON edms_ac_purchase_item.purchase_item_id = edms_ac_purchase_tax.purchase_item_id 
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_purchase_tax.tax_group_id
		LEFT JOIN edms_barcode_transactions ON edms_ac_purchase_item.purchase_item_id = edms_barcode_transactions.trans_item_id AND trans_type = 1
		  WHERE edms_ac_purchase_item.purchase_id = $sales_id GROUP BY edms_ac_purchase_item.purchase_item_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				
				$sales_item_id = $re['purchase_item_id'];
				$tax = getTaxForPurchaseItemId($sales_item_id);
				$return_array[$i]['purchase_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getNonStockItemForPurchaseId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT  edms_ac_purchase_nonstock.purchase_non_stock_id,item_id,discount,amount,net_amount,edms_ac_purchase_nonstock.purchase_id,created_by,last_updated_by,date_added,date_modified, edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount, edms_ac_purchase_nonstock.ledger_id, edms_ac_purchase_nonstock.tax_class_id  FROM edms_ac_purchase_nonstock 
		LEFT JOIN edms_ac_purchase_tax ON edms_ac_purchase_nonstock.purchase_non_stock_id = edms_ac_purchase_tax.purchase_non_stock_id 
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_purchase_tax.tax_group_id  WHERE edms_ac_purchase_nonstock.purchase_id = $sales_id GROUP BY edms_ac_purchase_nonstock.purchase_non_stock_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['purchase_non_stock_id'];
				$tax = getTaxForPurchaseItemId($sales_item_id,true);
				$return_array[$i]['purchase_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getTaxForPurchaseItemId($sales_item_id,$non_stock=false)
{
	if(checkForNumeric($sales_item_id))
	{
		$sql="SELECT purchase_id, edms_tax_grp.tax_group_id, tax_group_name, SUM(tax_amount) as tax_amount, edms_tax.tax_id, CONCAT(IF(edms_tax.in_out>0,'OUT','IN'), ' ', tax_name) as tax_name_in_out, tax_name, edms_tax.in_out, tax_percent FROM edms_ac_purchase_tax, edms_tax_grp, edms_tax WHERE edms_tax_grp.tax_group_id = edms_ac_purchase_tax.tax_group_id AND edms_tax.tax_id = edms_ac_purchase_tax.tax_id AND
		";
		if(!$non_stock)
		$sql=$sql." edms_ac_purchase_tax.purchase_item_id = $sales_item_id GROUP BY tax_id";
		else if($non_stock)
		$sql=$sql." edms_ac_purchase_tax.purchase_non_stock_id = $sales_item_id GROUP BY tax_id";
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