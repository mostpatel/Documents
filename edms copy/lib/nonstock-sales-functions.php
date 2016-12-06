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
require_once("delivery-challan-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");

function checkForSalesItemsNSInArray($item_id_array,$amount_array,$discount_array,$tax_group_id_array,$ns_type,$out_side_labour_provider_array,$our_rate_array,$challan_id=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL)
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
			
			$item=getInventoryItemById($item_id);
			
			
			if($item['tax_calculation']==1)
			{
			$tax_class_id=1000;
			$sales_ledger_id = 1000;
			$tax_group_id =0;	
				
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
			
			
			if(is_numeric($challan_id) && $item_id>0 && (!is_numeric($amount) || $amount<0))
			return false;
			
			
			
			
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

function insertNonStockToSale($item_id,$amount,$discount,$sales_id,$tax_group_id,$ns_type=0,$out_side_labour_provider=0,$our_rate=0,$item_desc="",$challan_id="NULL",$sales_ledger_id = NULL,$tax_class_id = NULL)
{
	if(!validateForNull($item_desc))
	$item_desc="";
	if(!is_numeric($challan_id))
	$challan_id="NULL";
	
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
	
	if(checkForNumeric($amount,$discount,$sales_id,$tax_group_id,$ns_type) && (($ns_type==1 && checkForNumeric($out_side_labour_provider,$our_rate) && $out_side_labour_provider>0 && $our_rate>=0) || $ns_type==0) && $tax_group_id>=0  && $item_id>0 && $amount>=0 && $discount>=0 && (is_numeric($item_id) || is_numeric($expense_id))  && (TAX_CLASS==0 || (TAX_CLASS==1 && checkForNumeric($sales_ledger_id,$tax_class_id))))
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
				
				if($tax_class_id==0)
				$tax_class_id="NULL";
				
				$sql="INSERT INTO edms_ac_sales_nonstock (item_id,amount,discount,net_amount,sales_id,delivery_challan_id,ns_type,item_desc,created_by,last_updated_by,date_added,date_modified,tax_class_id,ledger_id,expense_id) VALUES ($item_id,$amount,$discount,$nett_amount,$sales_id,$challan_id,$ns_type,'$item_desc',$admin_id,$admin_id,NOW(),NOW(),$tax_class_id,$sales_ledger_id,$expense_id)";
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

function insertNonStockToACDeliveryChallan($item_id,$delivery_challan_id,$item_desc="")
{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	if(!validateForNull($item_desc))
	$item_desc="";
	if(checkForNumeric($item_id,$delivery_challan_id)  && $item_id>0)
			{
									
				$sql="INSERT INTO edms_ac_sales_nonstock (item_id,amount,discount,net_amount,sales_id,delivery_challan_id,ns_type,item_desc,created_by,last_updated_by,date_added,date_modified) VALUES ($item_id,0,0,0,NULL,$delivery_challan_id,0,'$item_desc',$admin_id,$admin_id,NOW(),NOW())";
				dbQuery($sql);
				$sales_non_stock_id = dbInsertId();
				
				
				
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

function deleteNonStockItemsForACDeliveryChallan($id)
{
	if(checkForNumeric($id))
	{
		
		$sql="DELETE FROM edms_ac_sales_nonstock WHERE delivery_challan_id = $id AND sales_id IS NULL";
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

function insertNonStocksToSale($item_id_array,$amount_array,$discount_array,$sales_id,$tax_group_array,$ns_type=0,$outside_labour_provider_array=NULL,$our_rate_array=NULL,$item_description_array=NULL,$challan_id=NULL,$sales_ledger_id_array=NULL,$tax_class_id_array=NULL)
{
	
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			
			$item_id=$item_id_array[$i];
			$amount=$amount_array[$i];
			$discount=$discount_array[$i];
			$tax_group_id = $tax_group_array[$i];
			if(validateForNull($item_description_array) && is_array($item_description_array))
			$item_description = $item_description_array[$i];
			else
			$item_description = "";
			
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
				$sale=getSaleById($sales_id);
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
			
			if(checkForNumeric($amount,$discount,$sales_id,$ns_type) && (($ns_type==1 && $outside_labour_provider>0 && $our_rate>=0) || $ns_type==0) && $item_id>0 && $amount>=0 && $discount>=0  && $tax_group_id>=0 && (is_numeric($item_id) || is_numeric($expense_id))  && (TAX_CLASS==0 || (TAX_CLASS==1 && checkForNumeric($sales_ledger_id,$tax_class_id))))
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
				
				
				$item =  getInventoryItemById($item_id);
				$tax_wise_amount_percent=getTaxwiseAmountPercentForSaleIdStockItems($sales_id);
				
				if($item['tax_calculation']==1)
				{
				foreach($tax_wise_amount_percent as $tax_group_id=>$value)
				{
					
						$per = 0;
						$per = $value[2];
						$new_net_amount = $net_amount * $per;
					
						$new_amount = $amount *$per;
							
						if(!checkForNumeric($tax_group_id))
						$tax_group_id=0;
						$sales_ledger_id = $value[3];
						$tax_class_id = $value[4];
					$sales_item_id=insertNonStockToSale($item_id,$new_amount,$discount,$sales_id,$tax_group_id,$ns_type,$outside_labour_provider,$our_rate,$item_description,$challan_id,$sales_ledger_id,$tax_class_id);
					
					if(checkForNumeric($tax_group_id) && $tax_group_id>0)
					{
						insertTaxToSale($sales_id,$sales_item_id,$tax_group_id,$new_net_amount,true);
					}
				}
				}
				else
				{
				
				$sales_item_id=insertNonStockToSale($item_id,$amount,$discount,$sales_id,$tax_group_id,$ns_type,$outside_labour_provider,$our_rate,$item_description,$challan_id,$sales_ledger_id,$tax_class_id);
					
				if($tax_group_id>0)
				insertTaxToSale($sales_id,$sales_item_id,$tax_group_id,$net_amount,true);
					
				}
				
			}	
			
		}
		return "success";
				
	}
	
	return "error";
	
}

function updateSaleToDeliveryChallanNSItems($sales_item_id_array,$rate_array,$discount_array,$tax_group_array,$sale_id,$challan_id)
{
	
	if(is_array($sales_item_id_array) && count($sales_item_id_array)>0)
	{
		for($i=0;$i<count($sales_item_id_array);$i++)
		{
			$sales_item_id=$sales_item_id_array[$i];
			$rate=$rate_array[$i];
			$discount=$discount_array[$i];
			$tax_group_id = $tax_group_array[$i];
			$amount = $rate;
			
				
	
	if(checkForNumeric($sales_item_id,$rate,$discount,$tax_group_id,$sale_id,$challan_id) && $tax_group_id>=0 && $sales_item_id>0 && $rate>=0 && $discount>=0)
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
		
	    $sql="UPDATE edms_ac_sales_nonstock SET rate = $rate , discount = $discount, amount = $amount, net_amount = $net_amount, sales_id = $sale_id WHERE sales_non_stock_id = $sales_item_id";
		dbQuery($sql);
		
		
		}
		}
	return "success";	
	}
	return "error";
}




function insertNonStocksToACDeliveryChallan($item_id_array,$delivery_challan_id,$item_description_array=NULL)
{
	
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			
			$item_id=$item_id_array[$i];
			
			if(validateForNull($item_description_array) && is_array($item_description_array))
			$item_description = $item_description_array[$i];
			else
			$item_description = "";
			
			
			
			if(checkForNumeric($item_id,$delivery_challan_id) && $item_id>0 )
			{
				
				
				$sales_item_id=insertNonStockToACDeliveryChallan($item_id,$delivery_challan_id,$item_description);
				
				
			}	
			
		}
		return "success";
				
	}
	
	return "error";
	
}
	

?>