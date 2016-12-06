<?php 
require_once("cg.php");
require_once("common.php");
require_once("job-card-description-functions.php");
require_once("inventory-sales-functions.php");
require_once("inventory-item-functions.php");
require_once("nonStock-sales-functions.php");
require_once("account-sales-functions.php");
require_once("job-card-functions.php");
require_once("job-card-work-done-functions.php");
require_once("job-card-remarks-functions.php");
require_once("service-check-functions.php");
require_once("service-type-functions.php");
require_once("service-check-value-functions.php");
require_once("bd.php");
		
	
function addNewJobCard($job_card_no,$jb_date_time,$service_type_id,$free_service_no,$date_of_sale,$kms_covered,$estimate_cost,$bay_in,$delivery_promise,$technician_id,$vehicle_id,$customer_id,$job_description_array,$actual_work_done_array,$remarks_array,$service_check_array,$item_id_array,$rate_array,$quantity_array,$discount_array,$trans_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$ns_item_id_array,$ns_amount_array,$ns_discount_array,$ns_tax_group_array,$oj_item_id_array,$oj_amount_array,$oj_discount_array,$oj_tax_group_array,$oj_our_rate_array,$oj_outside_labour_provider_array,$war_item_id_array,$war_rate_array,$war_quantity_array,$war_discount_array,$war_tax_group_array,$war_godown_id_array,$sales_ref="NA",$sales_ref_type=0){
	
	try
	{
		
		$job_card_no=clean_data($job_card_no);
		$jb_date_time=clean_data($jb_date_time);
		$service_type_id=clean_data($service_type_id);
		$free_service_no=clean_data($free_service_no);
		$date_of_sale=clean_data($date_of_sale);
		$kms_covered=clean_data($kms_covered);
		$estimate_cost=clean_data($estimate_cost);
		$bay_in=clean_data($bay_in);
		$bay_out=clean_data($bay_out);
		$delivery_promise=clean_data($delivery_promise);
		$actual_delivery=clean_data($actual_delivery);
		$technician_id=clean_data($technician_id);
		$vehicle_id=clean_data($vehicle_id);
		$customer_id=clean_data($customer_id);
		
		if($service_type_id!=2 && $service_type_id!=5)
		{
		$free_service_no=0;	
		}
		
		
		
		if(validateForNull($job_card_no,$jb_date_time,$bay_in,$delivery_promise,$trans_date) && checkForNumeric($service_type_id,$free_service_no,$technician_id,$vehicle_id,$customer_id) && $technician_id>0)
		{
		$item_id_array=ConvertItemNameArrayInToIdArray($item_id_array);
		$ns_item_id_array=ConvertItemNameArrayInToIdArray($ns_item_id_array);
		$oj_item_id_array=ConvertItemNameArrayInToIdArray($oj_item_id_array);
		$war_item_id_array=ConvertItemNameArrayInToIdArray($war_item_id_array);
		$items_amount = checkForSalesItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_array);
		$non_stock_amount = checkForSalesItemsNSInArray($ns_item_id_array,$ns_amount_array,$ns_discount_array,$ns_tax_group_array);
		$outside_job_amount = checkForSalesItemsNSInArray($oj_item_id_array,$oj_amount_array,$oj_discount_array,$oj_tax_group_array);
		
	
		$total_amount = $items_amount + $non_stock_amount + $outside_job_amount;
		
		if($total_amount>=0)
		{
		$job_card_id=insertJobCard($job_card_no,$jb_date_time,$service_type_id,$free_service_no,$date_of_sale,$kms_covered,$estimate_cost,$bay_in,$delivery_promise,$technician_id,$vehicle_id,$customer_id,$job_description_array,$actual_work_done_array,$remarks_array,$service_check_array);	  
		
		
		
		if(checkForNumeric($job_card_id))
		{
		
		
		$sales_id = insertSale($total_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,3,$job_card_id,$sales_ref,$sales_ref_type);
		
		if(checkForNumeric($sales_id))
		{
		
			
		insertInventoryItemsToSale($item_id_array,$rate_array,$quantity_array,$discount_array,$sales_id,$tax_group_array,$godown_id_array,0);	
		insertInventoryItemsToSale($war_item_id_array,$war_rate_array,$war_quantity_array,$war_discount_array,$sales_id,$war_tax_group_array,$war_godown_id_array,1);	
	    insertNonStocksToSale($ns_item_id_array,$ns_amount_array,$ns_discount_array,$sales_id,$ns_tax_group_array,0);
		
		insertNonStocksToSale($oj_item_id_array,$oj_amount_array,$oj_discount_array,$sales_id,$oj_tax_group_array,1,$oj_outside_labour_provider_array,$oj_our_rate_array); // out_side_labour
	    
		
		insertSalesToJobCardId($job_card_id,$sales_id,NULL);
		}
		}
		else
		{
		deleteJobCard($job_card_id);
		return "error";
		}
		}
		
		return $job_card_id;
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function updateWholeJobCard($job_card_id,$job_card_no,$jb_date_time,$service_type_id,$free_service_no,$date_of_sale,$kms_covered,$estimate_cost,$bay_in,$delivery_promise,$technician_id,$job_description_array,$actual_work_done_array,$remarks_array,$service_check_array,$item_id_array,$rate_array,$quantity_array,$discount_array,$trans_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$ns_item_id_array,$ns_amount_array,$ns_discount_array,$ns_tax_group_array,$oj_item_id_array,$oj_amount_array,$oj_discount_array,$oj_tax_group_array,$oj_our_rate_array,$oj_outside_labour_provider_array,$war_item_id_array,$war_rate_array,$war_quantity_array,$war_discount_array,$war_tax_group_array,$war_godown_id_array,$sales_ref="NA",$sales_ref_type=0){
	
	try
	{
		
		$job_card_no=clean_data($job_card_no);
		$jb_date_time=clean_data($jb_date_time);
		$service_type_id=clean_data($service_type_id);
		$free_service_no=clean_data($free_service_no);
		$date_of_sale=clean_data($date_of_sale);
		$kms_covered=clean_data($kms_covered);
		$estimate_cost=clean_data($estimate_cost);
		$bay_in=clean_data($bay_in);
		$bay_out=clean_data($bay_out);
		$delivery_promise=clean_data($delivery_promise);
		$actual_delivery=clean_data($actual_delivery);
		$technician_id=clean_data($technician_id);
		
		if($service_type_id!=2 && $service_type_id!=5)
		{
		$free_service_no=0;	
		}
		
		
		
		if(validateForNull($job_card_no,$jb_date_time,$bay_in,$delivery_promise,$trans_date) && checkForNumeric($service_type_id,$free_service_no,$technician_id) && $technician_id>0)
		{
		$item_id_array=ConvertItemNameArrayInToIdArray($item_id_array);
		$ns_item_id_array=ConvertItemNameArrayInToIdArray($ns_item_id_array);
		$oj_item_id_array=ConvertItemNameArrayInToIdArray($oj_item_id_array);
		$war_item_id_array=ConvertItemNameArrayInToIdArray($war_item_id_array);
		
		$items_amount = checkForSalesItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_array);
		$non_stock_amount = checkForSalesItemsNSInArray($ns_item_id_array,$ns_amount_array,$ns_discount_array,$ns_tax_group_array);
		$outside_job_amount = checkForSalesItemsNSInArray($oj_item_id_array,$oj_amount_array,$oj_discount_array,$oj_tax_group_array);
		
	
		$total_amount = $items_amount + $non_stock_amount + $outside_job_amount;
		
			if($total_amount>=0)
			{	
			$result=updateJobCard($job_card_id,$job_card_no,$jb_date_time,$service_type_id,$free_service_no,$date_of_sale,$kms_covered,$estimate_cost,$bay_in,$delivery_promise,$technician_id,$job_description_array,$actual_work_done_array,$remarks_array,$service_check_array);
			  
			if($result=="success")
			{
			$sales_id = getSalesIdFromjobCardId($job_card_id);
			
			if(checkForNumeric($sales_id))
			{
				
			 updateSale($sales_id,$total_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,$sales_ref,$sales_ref_type);
			
		
			deleteInventoryItemsForSale($sales_id);
			deleteNonStockItemsForSale($sales_id);
				
			insertInventoryItemsToSale($item_id_array,$rate_array,$quantity_array,$discount_array,$sales_id,$tax_group_array,$godown_id_array,0);	
			insertInventoryItemsToSale($war_item_id_array,$war_rate_array,$war_quantity_array,$war_discount_array,$sales_id,$war_tax_group_array,$war_godown_id_array,1);	
			insertNonStocksToSale($ns_item_id_array,$ns_amount_array,$ns_discount_array,$sales_id,$ns_tax_group_array,0);
			
			insertNonStocksToSale($oj_item_id_array,$oj_amount_array,$oj_discount_array,$sales_id,$oj_tax_group_array,1,$oj_outside_labour_provider_array,$oj_our_rate_array); // out_side_labour
			
			}
			return "success";
		}
		else
		{
		deleteJobCard($job_card_id);
		return "error";
		}
		}
		
		return $job_card_id;
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function insertItemsFromTempTable()
{
	$sql="SELECT part_number_id, part_number,item_name,mrp,for_vehicle FROM edms_tem_part_number where useful=1";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	$i=0;
	foreach($resultArray as $re)
	{
		echo $i++;
		insertInventoryItem($re['item_name'],$re['for_vehicle'],NULL,NULL,67,$re['part_number'],0,$re['mrp'],0,0,'',2,0,NULL,NULL);
		
	}
	
}

?>