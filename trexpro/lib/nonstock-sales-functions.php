<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("inventory-item-functions.php");
require_once("inventory-sales-functions.php");
require_once("item-type-functions.php");
require_once("item-manufacturer-functions.php");
require_once("tax-functions.php");
require_once("godown-functions.php");
require_once("account-ledger-functions.php");
require_once("account-functions.php");
require_once("account-jv-functions.php");
require_once("account-sales-functions.php");
require_once("tax-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");

function checkForSalesItemsNSInArray($item_id_array,$amount_array,$discount_array,$tax_group_id_array,$ns_type,$out_side_labour_provider_array,$our_rate_array)
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
			
			if($ns_type==1)
			{
			$out_side_labour_provider = $out_side_labour_provider_array[$i];
			$our_rate = $our_rate_array[$i];
			}
			else
			{
			$out_side_labour_provider=0;
			}
			if(checkForNumeric($item_id,$amount,$discount,$tax_group_id,$out_side_labour_provider) && (($ns_type==1 && $out_side_labour_provider>0 && $our_rate>=0) || $ns_type==0) && $item_id>0 && $amount>=0 && $discount>=0  && $tax_group_id>=0)
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

function insertNonStockSale($item_id_array,$amount_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$tax_group_array,$sales_ref="NA",$sales_ref_type=0,$ns_type=0,$out_side_labour_provider_array=NULL,$our_rate_array=NULL) // ns_type=1 for outside labour
{
	$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
	$nett_amount = checkForSalesItemsNSInArray($item_id_array,$amount_array,$discount_array,$tax_group_array,$ns_type,$out_side_labour_provider_array,$our_rate_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	{
	$sales_id = insertSale($nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$sales_ref,$sales_ref_type);
	if(checkForNumeric($sales_id))
	{
	insertNonStocksToSale($item_id_array,$amount_array,$discount_array,$sales_id,$tax_group_array,$ns_type,$out_side_labour_provider_array,$our_rate_array);
	}
	else
	return "error";

	return $sales_id;
	}
	return "error";
}

function updateNonStockSale($sales_id,$item_id_array,$amount_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$tax_group_array,$sales_ref="NA",$sales_ref_type=0,$ns_type=0,$out_side_labour_provider_array=NULL,$our_rate_array=NULL)
{
	if(checkForNumeric($sales_id))
	{
		$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
		$nett_amount = checkForSalesItemsNSInArray($item_id_array,$amount_array,$discount_array,$tax_group_array,$ns_type,$out_side_labour_provider_array,$our_rate_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	{
	deleteNonStockItemsForSale($sales_id);	
	updateSale($sales_id,$nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$sales_ref,$sales_ref_type);
	insertNonStocksToSale($item_id_array,$amount_array,$discount_array,$sales_id,$tax_group_array,$ns_type,$out_side_labour_provider_array,$our_rate_array);

	return "success";
	}
	return "error";
		
		
	}
}

function insertNonStockToSale($item_id,$amount,$discount,$sales_id,$tax_group_id,$ns_type=0,$out_side_labour_provider=0,$our_rate=0)
{
	
	if(checkForNumeric($item_id,$amount,$discount,$sales_id,$tax_group_id,$ns_type) && (($ns_type==1 && checkForNumeric($out_side_labour_provider,$our_rate) && $out_side_labour_provider>0 && $our_rate>=0) || $ns_type==0) && $tax_group_id>=0  && $item_id>0 && $amount>=0 && $discount>=0)
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
				$sale=getSaleById($sales_id);
				$trans_date = $sale['trans_date'];
				$outside_job_ledger = getOutSideJobLedgerIdForOC($oc_id);
				
				$sql="INSERT INTO edms_ac_sales_nonstock (item_id,amount,discount,net_amount,sales_id,ns_type,created_by,last_updated_by,date_added,date_modified) VALUES ($item_id,$amount,$discount,$nett_amount,$sales_id,$ns_type,$admin_id,$admin_id,NOW(),NOW())";
				dbQuery($sql);
				$sales_non_stock_id = dbInsertId();
				
				if(checkForNumeric($sales_non_stock_id) && $ns_type==1)
				{
					addJV($our_rate,$trans_date,'L'.$outside_job_ledger,'L'.$out_side_labour_provider,'',3,$sales_non_stock_id);
				}
				
				return $sales_non_stock_id;
			}	
		return false;	
	
}

function deleteNonStockItemsForSale($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$non_stock_id_array = getNonStockIdsForSaleId($sales_id);
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
		deleteTaxForSale($sales_id);
		$sql="DELETE FROM edms_ac_sales_nonstock WHERE sales_id = $sales_id";
		dbQuery($sql);
		return "success";
	}	
	return "error";
}

function getNonStockIdsForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$sql="SELECT GROUP_CONCAT(sales_non_stock_id) FROM edms_ac_sales_nonstock WHERE sales_id=$sales_id GROUP BY sales_id";
		$result = dbQuery($sql);
		if(dbNumRows($result)>0)
		{
		$resultArray = dbResultToArray($result);
		$sales_non_stock_id_string = $resultArray[0][0];
		return explode(',',$sales_non_stock_id_string);
		}
		
	}
	return false;
}

function insertNonStocksToSale($item_id_array,$amount_array,$discount_array,$sales_id,$tax_group_array,$ns_type=0,$outside_labour_provider_array=NULL,$our_rate_array=NULL)
{
	
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			
			$item_id=$item_id_array[$i];
			$amount=$amount_array[$i];
			$discount=$discount_array[$i];
			$tax_group_id = $tax_group_array[$i];
			
			
			if($ns_type==1)
			{
			$outside_labour_provider = $outside_labour_provider_array[$i];
			$our_rate = $our_rate_array[$i];
			}
			else
			$outside_labour_provider=NULL;
			
			if(checkForNumeric($item_id,$amount,$discount,$sales_id,$ns_type) && (($ns_type==1 && $outside_labour_provider>0 && $our_rate>=0) || $ns_type==0) && $item_id>0 && $amount>=0 && $discount>=0  && $tax_group_id>=0)
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
				
				$sales_item_id=insertNonStockToSale($item_id,$amount,$discount,$sales_id,$tax_group_id,$ns_type,$outside_labour_provider,$our_rate);
				
				if($tax_group_id>0)
				insertTaxToSale($sales_id,$sales_item_id,$tax_group_id,$net_amount,true);
			}	
			
		}
		return "success";
				
	}
	
	return "error";
	
}
	

?>