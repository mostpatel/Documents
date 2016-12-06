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
require_once("account-sales-functions.php");
require_once("nonstock-sales-functions.php");
require_once("tax-functions.php");
require_once("delivery-challan-functions.php");
require_once("account-delivery-challan-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");

function checkForSalesItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_id_array,$challan_id=NULL,$trans_type=NULL,$trans_id_if_update=NULL,$unit_id_array=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL)
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
			$item=  getInventoryItemById($item_id);
			$rate=$rate_array[$i];
			if($item['use_barcode']==0)
			$quantity=$quantity_array[$i];
			else
			$quantity=1;
			$discount=$discount_array[$i];
			$tax_group_id = $tax_group_id_array[$i];
		    $rate=$rate_array[$i];
			$quantity=$quantity_array[$i];
			
			$unit_id = $unit_id_array[$i];
			$saled_ledger_id = $sales_ledger_id_array[$i];
			$tax_class_id = $tax_class_id_array[$i];
			
			
			if(is_numeric($challan_id) && $item_id>0 && (!is_numeric($rate) || $rate<0) && (!is_numeric($quantity) || $quantity<0))
			return false;
			
			if(checkForNumeric($item_id,$rate,$quantity,$discount,$tax_group_id) && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0 && $tax_group_id>=0 && ((TAX_CLASS==1 && checkForNumeric($tax_class_id,$saled_ledger_id) && $tax_class_id>0 && $saled_ledger_id>0) || TAX_CLASS==0))
			{
				
				$item=getInventoryItemById($item_id);
				
				if($item['use_barcode']==0)
				{}
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
									
									if(is_numeric($challan_id) && $latest_trans_type==4 && is_numeric($latest_transaction['trans_id']) && $latest_transaction['trans_id']==$challan_id)
									{
										
									}
									else if(!is_numeric($trans_id_if_update) || ($trans_id_if_update!=$latest_transaction['trans_id'] && $trans_type!=$latest_trans_type))
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
					$total_amount = $total_amount - $total_tax_amount;	
				}
				
				}
				
				$has_items = $total_amount;
			}	
			
		}
				
	}
	return $has_items;
	
}

	
function checkForACDeliveryChallanItemsInArray($item_id_array,$quantity_array,$trans_type=NULL,$trans_id_if_update=NULL)
{
	
	$total_items=0;
	$has_items=false;
	if(checkIfDuplicateBarcodesInArray($item_id_array))
	return "barcode_transaction_error";
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			$item_id=$item_id_array[$i];
			$quantity=$quantity_array[$i];
			
			if(!is_numeric($item_id) && is_array($item_id) && checkForNumeric($item_id[0]))
			{
				$barcode = $item_id[1];
				$item_id=$item_id[0];
			}
			
			
			if(checkForNumeric($item_id,$quantity) && $item_id>0  && $quantity>0)
			{
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
				
				$total_items = $total_items + 1;
				$has_items = $total_items;
			}	
			
		}
				
	}
	return $has_items;
	
}	

function ConvertItemNameArrayInToIdArray($item_name_array,$is_purchase=0)
{
	$item_id_array = array();
	foreach($item_name_array as $item_name){
		
		$item_id=getItemIdFromFullItemName($item_name,$is_purchase);
		
		if(checkForNumeric($item_id) || (is_array($item_id) && checkForNumeric($item_id[0])))
		$item_id_array[]=$item_id;
		else
		$item_id_array[]="";
	}
	return $item_id_array;
	
	}	
	
function insertInventoryNonStockSale($item_id_array,$rate_array,$quantity_array,$discount_array,$item_id_ns_array,$amount_ns_array,$discount_ns_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$tax_group_ns_array,$sales_ref="NA",$sales_ref_type=0,$invoice_no=NULL,$retail_tax=0,$invoice_note="",$item_description_array=NULL,$item_description_ns_array=NULL,$delivery_note="",$terms_of_payment="",$supp_ref_no="",$other_references="",$buyer_order_no="",$order_dated="",$despatch_doc_no="",$despatch_dated="",$despatch_through="",$destination="",$terms_of_delivery="",$oc_id=NULL,$challan_id=NULL,$consignee_address="",$unit_id_array=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL,$ns_sales_ledger_id_array=NULL,$ns_tax_class_id_array=NULL,$form_no=NULL,$form_date=NULL)
{

	$invoice_note = clean_data($invoice_note);
	$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
	$item_id_ns_array = ConvertItemNameArrayInToIdArray($item_id_ns_array);
	$nett_amount = checkForSalesItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_array,$challan_id,2,NULL,$unit_id_array,$sales_ledger_id_array,$tax_class_id_array);
	$nett_amount_ns = checkForSalesItemsNSInArray($item_id_ns_array,$amount_ns_array,$discount_ns_array,$tax_group_ns_array,0,NULL,NULL,$challan_id,$ns_sales_ledger_id_array,$ns_tax_class_id_array);
	
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
	
	$sales_id = insertSale($total_nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$sales_ref,$sales_ref_type,$retail_tax,$invoice_no,$invoice_note,$delivery_note,$terms_of_payment,$supp_ref_no,$other_references,$buyer_order_no,$order_dated,$despatch_doc_no,$despatch_dated,$despatch_through,$destination,$terms_of_delivery,$oc_id,$consignee_address);

	if(checkForNumeric($sales_id))
	{
		
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)	
	insertInventoryItemsToSale($item_id_array,$rate_array,$quantity_array,$discount_array,$sales_id,$tax_group_array,$godown_id_array,0,$item_description_array,$challan_id,$unit_id_array,$sales_ledger_id_array,$tax_class_id_array);

	if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
	insertNonStocksToSale($item_id_ns_array,$amount_ns_array,$discount_ns_array,$sales_id,$tax_group_ns_array,0,NULL,NULL,$item_description_ns_array,$challan_id,$ns_sales_ledger_id_array,$ns_tax_class_id_array);
	
	$tax_form_id=getTaxFormIdForTransId($sales_id,2);
	
	if(checkForNumeric($tax_form_id))
	insertTransTaxForm('NULL',$sales_id,'NULL','NULL',$tax_form_id,$form_no,$form_date);
	
	if(is_numeric($challan_id))
	{
		updateSaleToDeliveryChallan($sales_id,$challan_id);
		deleteInventoryItemsForACDeliveryChallan($challan_id);					
	}
	}
	else
	return "error";
	return $sales_id;
	}
	return "error";
}	

function insertInventoryItemSale($item_id_array,$rate_array,$quantity_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$sales_ref="NA",$sales_ref_type=0)
{
	$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);

	$nett_amount = checkForSalesItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	{
	$sales_id = insertSale($nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$sales_ref,$sales_ref_type);
	if(checkForNumeric($sales_id))
	insertInventoryItemsToSale($item_id_array,$rate_array,$quantity_array,$discount_array,$sales_id,$tax_group_array,$godown_id_array);
	else
	return "error";

	return $sales_id;
	}
	return "error";
}


function updateInventoryItemSale($sales_id,$item_id_array,$rate_array,$quantity_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$sales_ref="NA",$sales_ref_type=0,$invoice_note="")
{
	if(checkForNumeric($sales_id))
	{
		$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
		$nett_amount = checkForSalesItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	{
		
	
	$result=updateSale($sales_id,$nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$sales_ref,$sales_ref_type,$invoice_note);
	if($result=="success")
	{
	deleteInventoryItemsForSale($sales_id);	
	insertInventoryItemsToSale($item_id_array,$rate_array,$quantity_array,$discount_array,$sales_id,$tax_group_array,$godown_id_array);
	return "success";
	}
	}
	return "error";
		
		
	}
}

function updateInventoryNonStockItemSale($sales_id,$item_id_array,$rate_array,$quantity_array,$discount_array,$item_id_ns_array,$amount_ns_array,$discount_ns_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$tax_group_ns_array,$sales_ref="NA",$sales_ref_type=0,$retail_tax=0,$invoice_note="",$item_description_array=NULL,$item_description_ns_array=NULL,$delivery_note="",$terms_of_payment="",$supp_ref_no="",$other_references="",$buyer_order_no="",$order_dated="",$despatch_doc_no="",$despatch_dated="",$despatch_through="",$destination="",$terms_of_delivery="",$challan_id=NULL,$consignee_address="",$unit_id_array=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL,$ns_sales_ledger_id_array=NULL,$ns_tax_class_id_array=NULL,$form_no=NULL,$form_date=NULL)
{
	
	
	
	if(checkForNumeric($sales_id))
	{
	
	$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
	$item_id_ns_array = ConvertItemNameArrayInToIdArray($item_id_ns_array);
	
	if(USE_BARCODE==1)
	{
		
		$old_new_items_clash=checkOldUsedItemsAndNewItemsForEditTransaction($item_id_array,$sales_id,$trans_date,2,$rate_array,$quantity_array,$discount_array,$tax_group_array);
		
		if($old_new_items_clash!="success")
		return $old_new_items_clash;
	}

	
	$nett_amount = checkForSalesItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_array,$challan_id,2,$sales_id,$unit_id_array,$sales_ledger_id_array,$tax_class_id_array);
	$nett_amount_ns = checkForSalesItemsNSInArray($item_id_ns_array,$amount_ns_array,$discount_ns_array,$tax_group_ns_array,0,NULL,NULL,$challan_id,$ns_sales_ledger_id_array,$ns_tax_class_id_array);
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
	

	$result=updateSale($sales_id,$total_nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,$sales_ref,$sales_ref_type,$retail_tax,$invoice_note,$delivery_note,$terms_of_payment,$supp_ref_no,$other_references,$buyer_order_no,$order_dated,$despatch_doc_no,$despatch_dated,$despatch_through,$destination,$terms_of_delivery,NULL,$consignee_address);
	
	if($result=="success")
	{
	deleteInventoryItemsForSale($sales_id,0);	
	deleteNonStockItemsForSale($sales_id);
		
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)	
	insertInventoryItemsToSale($item_id_array,$rate_array,$quantity_array,$discount_array,$sales_id,$tax_group_array,$godown_id_array,0,$item_description_array,$challan_id,$unit_id_array,$sales_ledger_id_array,$tax_class_id_array);
	if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
	insertNonStocksToSale($item_id_ns_array,$amount_ns_array,$discount_ns_array,$sales_id,$tax_group_ns_array,0,NULL,NULL,$item_description_ns_array,$challan_id,$ns_sales_ledger_id_array,$ns_tax_class_id_array);
	
	$tax_form_id=getTaxFormIdForTransId($sales_id,2);
	deleteTransTaxFormByTransId($sales_id,2);
	if(checkForNumeric($tax_form_id))
	insertTransTaxForm('NULL',$sales_id,'NULL','NULL',$tax_form_id,$form_no,$form_date);
	

	return "success";
	}
	}
	return "error";
		
		
	}
}

function insertInventoryItemToSale($item_id,$rate,$quantity,$discount,$sales_id,$godown_id,$tax_group_id,$warranty=0,$item_description="",$challan_id=NULL,$barcode=NULL,$unit_id=NULL,$sales_ledger_id = NULL,$tax_class_id = NULL)
{
	if(!validateForNull($warranty))
	$warranty=0;
	if(!validateForNull($item_description))
	$item_description="";
	if(!is_numeric($challan_id))
	$challan_id="NULL";
	
	if(checkForNumeric($item_id,$rate,$quantity,$discount,$sales_id,$godown_id,$tax_group_id,$warranty) && $tax_group_id>=0 && $godown_id>0 && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0 && (TAX_CLASS==0 || (TAX_CLASS==1 && checkForNumeric($sales_ledger_id,$tax_class_id))))
			{
				$item = getInventoryItemById($item_id);
				
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
				if($tax_class_id==0)
				$tax_class_id="NULL";
				$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
				
				
				$sql="INSERT INTO edms_ac_sales_item (item_id,rate,quantity,discount,amount,net_amount,sales_id,delivery_challan_id,godown_id,warranty,item_desc,created_by,last_updated_by,date_added,date_modified,ledger_id,tax_class_id) VALUES ($item_id,$base_rate,$base_quantity,$discount,$amount,$nett_amount,$sales_id,$challan_id,$godown_id,$warranty,'$item_description',$admin_id,$admin_id,NOW(),NOW(),$sales_ledger_id,$tax_class_id)";
				dbQuery($sql);
				$sales_item_id= dbInsertId();
				insertTransItemUnit(NULL,$sales_item_id,NULL,NULL,NULL,$unit_id,$item_id,$rate,$quantity);
				insertInventoryBarcodeTransaction($sales_id,$sales_item_id,2,$item_id,$quantity,$barcode);
				
				return $sales_item_id;
				
			}	
		return false;	
	
}

function updateSaleToDeliveryChallanItems($sales_item_id_array,$rate_array,$discount_array,$tax_group_array,$sale_id,$challan_id)
{
	
	if(is_array($sales_item_id_array) && count($sales_item_id_array)>0)
	{
		for($i=0;$i<count($sales_item_id_array);$i++)
		{
			$sales_item_id=$sales_item_id_array[$i];
			$rate=$rate_array[$i];
			$discount=$discount_array[$i];
			$tax_group_id = $tax_group_array[$i];
			
			
				
	
	if(checkForNumeric($sales_item_id,$rate,$discount,$tax_group_id,$sale_id,$challan_id) && $tax_group_id>=0 && $sales_item_id>0 && $rate>=0 && $discount>=0)
	{
		$sql="SELECT * FROM edms_ac_sales_item WHERE sales_item_id = $sales_item_id";
		$result=dbQuery($sql);
		$resultArray = dbResultToArray($result);
		$sales_item = $resultArray[0];
		$quantity = $sales_item['quantity'];
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
		
	    $sql="UPDATE edms_ac_sales_item SET rate = $rate , discount = $discount, amount = $amount, net_amount = $net_amount, sales_id = $sale_id WHERE sales_item_id = $sales_item_id";
		dbQuery($sql);
		
		
		}
		}
	return "success";	
	}
	return "error";
}

function insertInventoryItemToACDeliveryChallan($item_id,$quantity,$delivery_challan_id,$godown_id,$item_description="",$barcode="",$item_unit_id=NULL)
{
	
	if(!validateForNull($item_description))
	$item_description="";
	            $item = getInventoryItemById($item_id);
	            if($item['use_barcode']==1 && !validateForNull($barcode))
				return false;
				
				if($item['use_barcode']==1 && !checkForNumeric(getBarcodeTransactionFromBarcode($barcode,$item_id))) 
				return false;
				if($item['use_barcode']==1)
				$quantity=1;
		if(!checkForNumeric($item_unit_id) && $item_unit_id<0)
		$item_unit_id=getNotAvailableItemUnit();
		
		$base_rate_quantity_array=ConvertRateAndQuantityForItemAndItemUnitId(0,$quantity,$item_id,$item_unit_id);
		
		$base_rate = $base_rate_quantity_array[0];
		$base_quantity = $base_rate_quantity_array[1];
	if(checkForNumeric($item_id,$quantity,$delivery_challan_id,$godown_id) &&  $godown_id>0 && $item_id>0  && $quantity>0)
			{
							
				$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
				$sql="INSERT INTO edms_ac_sales_item (item_id,rate,quantity,discount,amount,net_amount,sales_id,delivery_challan_id,godown_id,warranty,item_desc,created_by,last_updated_by,date_added,date_modified) VALUES ($item_id,0,$base_quantity,0,0,0,NULL,$delivery_challan_id,$godown_id,0,'$item_description',$admin_id,$admin_id,NOW(),NOW())";
				dbQuery($sql);
				$sales_item_id= dbInsertId();
				insertTransItemUnit(NULL,$sales_item_id,NULL,NULL,NULL,$item_unit_id,$item_id,0,$quantity);
				insertInventoryBarcodeTransaction($delivery_challan_id,$sales_item_id,4,$item_id,$quantity,$barcode);
				return $sales_item_id;
			}	
		return false;	
	
}

function deleteInventoryItemsForSale($sales_id,$check_for_barcode_in_use=1)
{
	if(checkForNumeric($sales_id))
	{
		deleteTaxForSale($sales_id);
		if(USE_BARCODE==1)
		deleteInventoryBarcodeTransactionByTransId($sales_id,2,$check_for_barcode_in_use);
		$sql="DELETE FROM edms_ac_sales_item WHERE sales_id = $sales_id";
		dbQuery($sql);
		return "success";
	}	
	return "error";
}

function deleteInventoryItemsForACDeliveryChallan($id,$check_for_barcode_in_use=1)
{
	if(checkForNumeric($id))
	{
		deleteTaxForSale($id);
		deleteInventoryBarcodeTransactionByTransId($id,4,$check_for_barcode_in_use);
		$sql="DELETE FROM edms_ac_sales_item WHERE delivery_challan_id = $id AND sales_id IS NULL";
		dbQuery($sql);
		return "success";
	}	
	return "error";
}

function insertInventoryItemsToSale($item_id_array,$rate_array,$quantity_array,$discount_array,$sales_id,$tax_group_array,$godown_id_array,$warranty=0,$item_description_array=NULL,$challan_id=NULL,$unit_id_array=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL)
{
	if(!validateForNull($warranty))
	$warranty=0;
	
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			$item_id=$item_id_array[$i];
			
			$rate=$rate_array[$i];
			$quantity=$quantity_array[$i];
			
			if(is_array($item_id))
			{
		    $barcode = $item_id[1];	
			$item_id = intval($item_id[0]);
			}
			$discount=$discount_array[$i];
			$tax_group_id = $tax_group_array[$i];
			$godown_id = $godown_id_array[$i];
			if(validateForNull($item_description_array[$i]) && is_array($item_description_array))
			$item_description = $item_description_array[$i];
			else
			$item_description = "";
			
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
				$sale=getSaleById($sales_id);
				$sales_ledger_id = $sale['from_ledger_id'];
				$tax_class_id="NULL";
			}
			
			if(checkForNumeric($item_id,$rate,$quantity,$discount,$sales_id,$godown_id,$warranty) && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0 && $tax_group_id>=0 && (TAX_CLASS==0 || (TAX_CLASS==1 && checkForNumeric($sales_ledger_id,$tax_class_id))))
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
				
				
				$sales_item_id=insertInventoryItemToSale($item_id,$rate,$quantity,$discount,$sales_id,$godown_id,$tax_group_id,$warranty,$item_description,$challan_id,$barcode,$unit_id,$sales_ledger_id,$tax_class_id);
				
				if($tax_group_id>0)
				insertTaxToSale($sales_id,$sales_item_id,$tax_group_id,$net_amount);
			}	
			
		}
		return "success";
				
	}
	
	return "error";
	
}

function insertInventoryItemsToACDeliveryChallan($item_id_array,$quantity_array,$delivery_challan_id,$godown_id_array,$item_description_array=NULL,$item_unit_array=NULL)
{
	
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			$item_id=$item_id_array[$i];
			if(is_array($item_id))
			{
		    $barcode = $item_id[1];	
			$item_id = intval($item_id[0]);
			$quantity=1;
			}
			else
			$quantity=$quantity_array[$i];
			$godown_id = $godown_id_array[$i];
			if(validateForNull($item_description_array[$i]) && is_array($item_description_array))
			$item_description = $item_description_array[$i];
			else
			$item_description = "";
			if(is_array($item_unit_array) && is_numeric($item_unit_array[$i]) && $item_unit_array>0)
			$item_unit = $item_unit_array[$i];
			else
			$item_unit = "NULL";
			if(checkForNumeric($item_id,$quantity,$delivery_challan_id,$godown_id) && $item_id>0  && $quantity>0 )
			{
				
				
				$sales_item_id=insertInventoryItemToACDeliveryChallan($item_id,$quantity,$delivery_challan_id,$godown_id,$item_description,$barcode,$item_unit);
				
				
			}	
			
		}
	
		return "success";
				
	}
	
	return "error";
	
}
	
function getInventoryItemForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_item.sales_item_id,edms_ac_sales_item.item_id,rate,quantity,discount,amount,net_amount,edms_ac_sales_item.sales_id,godown_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, edms_tax_grp.display_name, in_out, SUM(tax_amount) as tax_amount, item_desc,barcode, barcode_transaction_id, edms_ac_sales_item.tax_class_id, edms_ac_sales_item.ledger_id FROM edms_ac_sales_item 
		LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_item.sales_item_id = edms_ac_sales_tax.sales_item_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id 
		LEFT JOIN edms_barcode_transactions ON edms_ac_sales_item.sales_item_id = edms_barcode_transactions.trans_item_id AND trans_type = 2
		WHERE edms_ac_sales_item.sales_id = $sales_id GROUP BY edms_ac_sales_item.sales_item_id";	
		
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_item_id'];
				$tax = getTaxForSaleItemId($sales_item_id);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getInventoryItemForDeliveryChallanId($sales_id) // trans_type = 4 for barcode transaction
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_item.sales_item_id,edms_ac_sales_item.item_id,rate,quantity,discount,amount,net_amount,edms_ac_sales_item.sales_id,godown_id,created_by,last_updated_by,date_added,date_modified,item_desc,barcode, barcode_transaction_id FROM edms_ac_sales_item LEFT OUTER JOIN edms_barcode_transactions ON edms_ac_sales_item.sales_item_id = edms_barcode_transactions.trans_item_id AND trans_type = 4  WHERE edms_ac_sales_item.delivery_challan_id = $sales_id";	

		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_item_id'];
				$return_array[$i]['sales_item_details'] = $re;
				$i++;
			}
			return $return_array;
		}
		else
		return false;
	}
	
}	

function getTaxwiseAmountForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT DISTINCT edms_ac_sales_item.sales_item_id, net_amount as amount,edms_tax_grp.tax_group_id,display_name FROM edms_ac_sales_item LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_item.sales_item_id = edms_ac_sales_tax.sales_item_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id WHERE edms_ac_sales_item.sales_id = $sales_id
		UNION ALL
		SELECT DISTINCT edms_ac_sales_nonstock.sales_non_stock_id, net_amount as amount,edms_tax_grp.tax_group_id,display_name FROM edms_ac_sales_nonstock LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_nonstock.sales_non_stock_id = edms_ac_sales_tax.sales_non_stock_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id WHERE edms_ac_sales_nonstock.sales_id = $sales_id 
		 ";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
			foreach($resultArray as $re)
			{
				if(array_key_exists($re['tax_group_id'],$return_array))
				{
				$return_array[$re['tax_group_id']][1] = $return_array[$re['tax_group_id']][1] + $re['amount'];
				}
				else
				{
				$return_array[$re['tax_group_id']][0] = $re['display_name'];
				$return_array[$re['tax_group_id']][1] = $re['amount'];
				}
			}
			return $return_array;
		}
	}
}

function getIndividualTaxWiseAmountForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT DISTINCT sales_tax_id,tax_amount as amount,edms_tax.tax_id,display_name,tax_percent FROM edms_ac_sales_item LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_item.sales_item_id = edms_ac_sales_tax.sales_item_id
		INNER JOIN edms_tax ON edms_tax.tax_id = edms_ac_sales_tax.tax_id WHERE edms_ac_sales_item.sales_id = $sales_id
		
		UNION ALL
		SELECT DISTINCT sales_tax_id,tax_amount as amount,edms_tax.tax_id,display_name,tax_percent FROM edms_ac_sales_nonstock LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_nonstock.sales_non_stock_id = edms_ac_sales_tax.sales_non_stock_id
		INNER JOIN edms_tax ON edms_tax.tax_id = edms_ac_sales_tax.tax_id WHERE edms_ac_sales_nonstock.sales_id = $sales_id
		ORDER BY  tax_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
			foreach($resultArray as $re)
			{
				if(array_key_exists($re['tax_id'],$return_array))
				{
				$return_array[$re['tax_id']][1] = $return_array[$re['tax_id']][1] + $re['amount'];
				}
				else
				{
				$return_array[$re['tax_id']][0] = $re['display_name'];
				$return_array[$re['tax_id']][1] = $re['amount'];
				$return_array[$re['tax_id']][2] = $re['tax_percent'];
				}
			}
			return $return_array;
		}
	}
}

function getInventoryItemRegularForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_item.sales_item_id,item_id,rate,quantity,discount,amount,net_amount,edms_ac_sales_item.sales_id,godown_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_sales_item 
		LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_item.sales_item_id = edms_ac_sales_tax.sales_item_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id
		LEFT OUTER JOIN edms_barcode_transactions ON edms_ac_sales_item.sales_item_id = edms_barcode_transactions.trans_item_id AND trans_type = 2 
		 WHERE edms_ac_sales_item.sales_id = $sales_id AND warranty=0
		  
		GROUP BY edms_ac_sales_item.sales_item_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_item_id'];
				$tax = getTaxForSaleItemId($sales_item_id);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getInventoryItemGeneralRegularForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_item.sales_item_id,edms_ac_sales_item.item_id,rate,quantity,discount,amount,net_amount,edms_ac_sales_item.sales_id,godown_id,edms_ac_sales_item.created_by,edms_ac_sales_item.last_updated_by,edms_ac_sales_item.date_added,edms_ac_sales_item.date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_sales_item 
		LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_item.sales_item_id = edms_ac_sales_tax.sales_item_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id
		LEFT JOIN edms_inventory_item ON edms_ac_sales_item.item_id = edms_inventory_item.item_id
		LEFT OUTER JOIN edms_barcode_transactions ON edms_ac_sales_item.sales_item_id = edms_barcode_transactions.trans_item_id AND trans_type = 2 
		 WHERE edms_ac_sales_item.sales_id = $sales_id AND warranty=0 AND (item_type_id IS NULL OR item_type_id = 2) GROUP BY edms_ac_sales_item.sales_item_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_item_id'];
				$tax = getTaxForSaleItemId($sales_item_id);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getInventoryItemLubRegularForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_item.sales_item_id,edms_ac_sales_item.item_id,rate,quantity,discount,amount,net_amount,edms_ac_sales_item.sales_id,godown_id,edms_ac_sales_item.created_by,edms_ac_sales_item.last_updated_by,edms_ac_sales_item.date_added,edms_ac_sales_item.date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_sales_item 
		LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_item.sales_item_id = edms_ac_sales_tax.sales_item_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id
		LEFT JOIN edms_inventory_item ON edms_ac_sales_item.item_id = edms_inventory_item.item_id 
		LEFT OUTER JOIN edms_barcode_transactions ON edms_ac_sales_item.sales_item_id = edms_barcode_transactions.trans_item_id AND trans_type = 2 WHERE edms_ac_sales_item.sales_id = $sales_id AND warranty=0 AND (item_type_id = 1) GROUP BY edms_ac_sales_item.sales_item_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_item_id'];
				$tax = getTaxForSaleItemId($sales_item_id);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	


function getInventoryItemWarrantyForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_item.sales_item_id,item_id,rate,quantity,discount,amount,net_amount,edms_ac_sales_item.sales_id,godown_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_sales_item
		LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_item.sales_item_id = edms_ac_sales_tax.sales_item_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id
		LEFT OUTER JOIN edms_barcode_transactions ON edms_ac_sales_item.sales_item_id = edms_barcode_transactions.trans_item_id AND trans_type = 2  WHERE edms_ac_sales_item.sales_id = $sales_id AND warranty=1  GROUP BY edms_ac_sales_item.sales_item_id ";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_item_id'];
				$tax = getTaxForSaleItemId($sales_item_id);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}




function getNonStockItemForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_nonstock.sales_non_stock_id,item_id,discount,amount,net_amount,edms_ac_sales_nonstock.sales_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount,item_desc, edms_ac_sales_nonstock.ledger_id, edms_ac_sales_nonstock.tax_class_id FROM edms_ac_sales_nonstock 
		LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_nonstock.sales_non_stock_id = edms_ac_sales_tax.sales_non_stock_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id  
		WHERE edms_ac_sales_nonstock.sales_id = $sales_id GROUP BY edms_ac_sales_nonstock.sales_non_stock_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_non_stock_id'];
				$tax = getTaxForSaleItemId($sales_item_id,true);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getNonStockItemForDeliveryChallanId($id)
{
	if(checkForNumeric($id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_nonstock.sales_non_stock_id,item_id,discount,amount,net_amount,edms_ac_sales_nonstock.sales_id,created_by,last_updated_by,date_added,date_modified ,item_desc FROM edms_ac_sales_nonstock 
		
		WHERE edms_ac_sales_nonstock.delivery_challan_id = $id ";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_non_stock_id'];
				$return_array[$i]['sales_item_details'] = $re;
				$i++;
			}
			return $return_array;
		}
	}
	
}	


function getNonStockItemOurForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_nonstock.sales_non_stock_id,item_id,discount,amount,net_amount,edms_ac_sales_nonstock.sales_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_sales_nonstock
		LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_nonstock.sales_non_stock_id = edms_ac_sales_tax.sales_non_stock_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id  WHERE edms_ac_sales_nonstock.sales_id = $sales_id  AND ns_type=0 GROUP BY edms_ac_sales_nonstock.sales_non_stock_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_non_stock_id'];
				$tax = getTaxForSaleItemId($sales_item_id,true);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getNonStockItemOutSideJobForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_nonstock.sales_non_stock_id,item_id,discount,amount,net_amount, edms_ac_sales_nonstock.sales_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_sales_nonstock
		LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_nonstock.sales_non_stock_id = edms_ac_sales_tax.sales_non_stock_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id  WHERE edms_ac_sales_nonstock.sales_id = $sales_id  AND ns_type=1 GROUP BY edms_ac_sales_nonstock.sales_non_stock_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_non_stock_id'];
				$tax = getTaxForSaleItemId($sales_item_id,true);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getTaxForSaleItemId($sales_item_id,$non_stock=false)
{
	if(checkForNumeric($sales_item_id))
	{
		$sql="SELECT sales_id, edms_tax_grp.tax_group_id, tax_group_name, SUM(tax_amount) as tax_amount, edms_tax.tax_id, CONCAT(IF(edms_tax.in_out>0,'OUT','IN'), ' ', tax_name) as tax_name_in_out, tax_name, edms_tax.in_out, tax_percent FROM edms_ac_sales_tax, edms_tax_grp, edms_tax WHERE edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id AND edms_tax.tax_id = edms_ac_sales_tax.tax_id AND
		";
		if(!$non_stock)
		$sql=$sql." edms_ac_sales_tax.sales_item_id = $sales_item_id GROUP BY tax_id";
		else if($non_stock)
		$sql=$sql." edms_ac_sales_tax.sales_non_stock_id = $sales_item_id GROUP BY tax_id";
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