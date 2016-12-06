<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("vehicle-functions.php");
require_once("vehicle-model-functions.php");
require_once "vehicle-purchase-functions.php";
require_once("tax-functions.php");
require_once("account-sales-functions.php");
require_once("vehicle-invoice-functions.php");
require_once("account-ledger-functions.php");
require_once("account-jv-functions.php");
require_once("common.php");
require_once("bd.php");

function insertVehicleSale($sale_date,$to_ledger,$from_ledger,$remarks,$delivery_challan_id,$amount,$tax_group,$invoice_no,$retail_tax,$sales_jv,$loan_amount,$loan_jv_debit_ledger,$exchange=0,$to_ledger_id=false,$from_ledger_id=false,$remarks=false,$model_id_array=false,$vehicle_engine_no_array=false,$vehicle_chasis_no_array=false,$vehicle_color_id_array=false,$model_year_array=false,$basic_price_array=false,$cng_cylinder_no_array=false,$cng_kit_no_array=false,$godown_id_array=false,$tax_group_array=false,$service_book_array=false,$condition_array=false,$reg_no_array=false)
{
		
	$total_amount = checkForVehiclesInArray($model_id_array,$vehicle_engine_no_array,$vehicle_chasis_no_array,$vehicle_color_id_array,$model_year_array,$basic_price_array,$godown_id_array,$service_book_array,$condition_array,$reg_no_array);
	
	
	if(checkForNumeric($amount,$delivery_challan_id,$invoice_no,$retail_tax) && validateForNull($from_ledger,$to_ledger) && $amount>=0 && !checkForDuplicateVehicleInvoice($delivery_challan_id,$invoice_no) && ($exchange==0 || (checkForNumeric($total_amount) && $total_amount>=0)))
	{
		
		$vehicle_id = getVehicleIdFromDeliveryChallan($delivery_challan_id);
	
		$vehicle = getVehicleById($vehicle_id);
		$godown_id = $vehicle['godown_id'];
		$model_id = $vehicle['model_id'];
		
		$sales_id = insertSale($amount,$sale_date,$sale_date,$to_ledger,$from_ledger,$remarks,1,0,"NA",0,$retail_tax); // auto rasid type = 1 for vehicle sale
		
		 insertVehicleModelToSale($model_id,$amount,1,0,$sales_id,$godown_id);
		 insertTaxToVehicleSale($sales_id,$vehicle_id,$tax_group,$amount);	
			
			if(checkForNumeric($sales_id))
			{
				if($exchange==1)
				{
					$purchase_id=insertVehiclePurchases($sale_date,$to_ledger_id,$from_ledger_id,$remarks,$model_id_array,$vehicle_engine_no_array,$vehicle_chasis_no_array,$vehicle_color_id_array,$model_year_array,$basic_price_array,$cng_cylinder_no_array,$cng_kit_no_array,$godown_id_array,$tax_group_array,$service_book_array,$condition_array,$reg_no_array,array(),1,$sales_id);
					if($purchase_id && checkForNumeric($purchase_id))
					{
					$exchange_vehicles = getVehiclesForVehiclePurchaseId($purchase_id);
					$exchange_vehicle_id = $exchange_vehicles[0]['vehicle_id'];
					}
					else
					{
					deleteSale($sales_id);
					return "error";
					}
				}
				else
				$exchange_vehicle_id="NULL";
				
				$invoice_id=insertInvoiceForVehicle($delivery_challan_id,$sale_date,$invoice_no,$sales_id,$exchange,$exchange_vehicle_id);
		
				insertSalesJvsForVehicle($vehicle_id,$to_ledger,$sale_date,$sales_jv,$sales_id);
				
				if(checkForNumeric($loan_amount,$loan_jv_debit_ledger) && $loan_amount>0)
				InsertLoanJvForVehicleId($vehicle_id,$loan_jv_debit_ledger,$sale_date,'C'.$vehicle['customer_id'],$sales_id,$loan_amount);
				
				return $invoice_id;	
			}
		
		return "error";		
	}
		return "error";	
	
	
}

function updateVehicleSale($sale_date,$to_ledger,$from_ledger,$remarks,$delivery_challan_id,$amount,$tax_group,$invoice_no,$retail_tax,$sales_jv,$loan_amount,$loan_jv_debit_ledger,$exchange=0,$to_ledger_id=false,$from_ledger_id=false,$remarks=false,$model_id_array=false,$vehicle_engine_no_array=false,$vehicle_chasis_no_array=false,$vehicle_color_id_array=false,$model_year_array=false,$basic_price_array=false,$cng_cylinder_no_array=false,$cng_kit_no_array=false,$godown_id_array=false,$tax_group_array=false,$service_book_array=false,$condition_array=false,$reg_no_array=false)
{
	
	if($amount && checkForNumeric($amount,$delivery_challan_id,$invoice_no,$retail_tax) && validateForNull($from_ledger,$to_ledger) && $amount>=0)
	{
		$invoice = getVehicleInvoiceByDeliveryChallanId($delivery_challan_id);
		$exchange_vehicle_id = getExchangeVehicleIdForVehicleInvoiceId($invoice['vehicle_invoice_id']);
		
	
		$sales_id = $invoice['sales_id'];
		$vehicle_id = getVehicleIdFromDeliveryChallan($delivery_challan_id);
		$vehicle = getVehicleById($vehicle_id);
		$godown_id = $vehicle['godown_id'];
		$model_id = $vehicle['model_id'];
	    deleteVehicleModelToSale($sales_id);
		updateSale($sales_id,$amount,$sale_date,$sale_date,$to_ledger,$from_ledger,$remarks,"NA",0,$retail_tax); // auto rasid type = 1 for vehicle sale
		insertVehicleModelToSale($model_id,$amount,1,0,$sales_id,$godown_id);
		updateTaxToVehicleSale($sales_id,$vehicle_id,$tax_group,$amount);	
				
				
				
				if(is_numeric($exchange_vehicle_id) && $exchange==0)
				{
				$exchange_vehicle = getVehicleById($exchange_vehicle_id);
				$exchange_vehicle_model = getVehicleModelById($exchange_vehicle['model_id']);
				$exchange_purchase_id = getPurchaseIdFromVehicleId($exchange_vehicle_id);
				$purchase_vehicle=getVehiclePurchaseById($exchange_vehicle_id);
			    $exchange_purchase = $purchase_vehicle[0];
				$exchange_vehicle_id = "NULL";
				$invoice_id=updateVehicleInvoice($delivery_challan_id,$sale_date,$invoice_no,$exchange,$exchange_vehicle_id);
				deleteVehiclePurchase($exchange_purchase_id);
				
				}
				else if($exchange==1 && is_numeric($exchange_vehicle_id))
				{
				
				$exchange_vehicle = getVehicleById($exchange_vehicle_id);
				
				$exchange_vehicle_model = getVehicleModelById($exchange_vehicle['model_id']);
				
				$exchange_purchase_id = getPurchaseIdFromVehicleId($exchange_vehicle_id);
				
				editVehiclePurchase($exchange_purchase_id,$sale_date,$to_ledger_id,$from_ledger_id,$remarks,array($exchange_vehicle_id),$model_id_array,$vehicle_engine_no_array,$vehicle_chasis_no_array,$vehicle_color_id_array,$model_year_array,$basic_price_array,$cng_cylinder_no_array,$cng_kit_no_array,$godown_id_array,$tax_group_array,$service_book_array,$condition_array,$reg_no_array);
				
				}
				else if($exchange==1 && !is_numeric($exchange_vehicle_id))
				{
				$purchase_id=insertVehiclePurchases($sale_date,$to_ledger_id,$from_ledger_id,$remarks,$model_id_array,$vehicle_engine_no_array,$vehicle_chasis_no_array,$vehicle_color_id_array,$model_year_array,$basic_price_array,$cng_cylinder_no_array,$cng_kit_no_array,$godown_id_array,$tax_group_array,$service_book_array,$condition_array,$reg_no_array,array(),1,$sales_id);
					if($purchase_id && checkForNumeric($purchase_id))
					{
					$exchange_vehicles = getVehiclesForVehiclePurchaseId($purchase_id);
					$exchange_vehicle_id = $exchange_vehicles[0]['vehicle_id'];
					}
					else
					{
					return "error";
					}	
					
				}
				
				$invoice_id=updateVehicleInvoice($delivery_challan_id,$sale_date,$invoice_no,$exchange,$exchange_vehicle_id);
				deleteSalesJvsForVehicle($vehicle_id);
				
				
				insertSalesJvsForVehicle($vehicle_id,$to_ledger,$sale_date,$sales_jv,$sales_id);
				
				if(checkForNumeric($loan_amount,$loan_jv_debit_ledger) && $loan_amount>0)
				{
				deleteLoanJvForVehicle($vehicle_id);
				InsertLoanJvForVehicleId($vehicle_id,$loan_jv_debit_ledger,$sale_date,'C'.$vehicle['customer_id'],$sales_id,$loan_amount);
				}
				else
				deleteLoanJvForVehicle($vehicle_id);
				
				return "success";	
	}
		return "error";	
	
	
}

function getTaxGroupIdForSalesID($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$sql="SELECT tax_group_id FROM edms_ac_sales_tax WHERE sales_id=$sales_id GROUP BY sales_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		}
	return false;
	
}

function deleteVehicleSale($invoice_id)
{
	if(checkForNumeric($invoice_id))
	{
		$invoice=getVehicleInvoiceById($invoice_id);
		$sales_id=getSalesIdFromInvoice($invoice_id);	
		deleteVehicleInvoiceById($invoice_id);
		deleteVehicleModelToSale($sales_id);
		deleteTaxForSale($sales_id);
		deleteSalesJvsForVehicle($invoice['vehicle_id']);
		deleteLoanJvForVehicle($invoice['vehicle_id']);
		deleteSale($sales_id);
		return "success";
	}
	return "error";
	
}

function insertVehicleModelToSale($model_id,$rate,$quantity,$discount,$sales_id,$godown_id)
{
	if(checkForNumeric($model_id,$rate,$quantity,$discount,$sales_id,$godown_id) && $godown_id>0 && $model_id>0 && $rate>=0 && $discount>=0 && $quantity>0)
			{
				
				$amount = $quantity * $rate;
				$nett_amount = $amount - $amount*($discount/100);
				
				$item_id = "NULL";
				$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
				
				
				$sql="INSERT INTO edms_ac_sales_item (item_id,model_id,rate,quantity,discount,amount,net_amount,sales_id,godown_id,created_by,last_updated_by,date_added,date_modified) VALUES ($item_id,$model_id,$rate,$quantity,$discount,$amount,$nett_amount,$sales_id,$godown_id,$admin_id,$admin_id,NOW(),NOW())";
				dbQuery($sql);
				return dbInsertId();
			}	
		return false;	
	
}

function deleteVehicleModelToSale($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$sql="DELETE FROM edms_ac_sales_item WHERE sales_id = $sales_id";
		dbQuery($sql);
		return true;
	}	
	return false;
}

function getRemainingBalanceForVehicleId($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT IF(opening_cd=0,opening_balance,-opening_balance) as amount FROM edms_vehicle WHERE vehicle_id = $vehicle_id";
	// Loan jv 
		$sql=$sql." UNION ALL
		SELECT IF(from_ledger_id IS  NULL OR auto_rasid_type=5,-edms_ac_jv.amount,edms_ac_jv.amount) as amount
			  FROM edms_ac_jv, edms_ac_jv_cd
			  WHERE auto_id=$vehicle_id AND edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id
			   AND (auto_rasid_type=4 OR auto_rasid_type=5) AND (from_ledger_id IS NOT NULL OR from_customer_id IS NOT NULL) GROUP BY edms_ac_jv.jv_id";
	// vehicle recipts
		$sql=$sql." UNION ALL
		SELECT  -amount
			  FROM edms_ac_receipt
			  WHERE auto_id=$vehicle_id
			   AND auto_rasid_type=4 AND to_customer_id IS NOT NULL ";	   
		// vehicle payments
		$sql=$sql." UNION ALL
		SELECT  amount
			  FROM edms_ac_payment
			  WHERE auto_id=$vehicle_id
			   AND auto_rasid_type=4 AND from_customer_id IS NOT NULL ";
		// vehicle sale amount	   	   	   
		$sql=$sql." UNION ALL SELECT amount FROM edms_ac_sales, edms_vehicle_invoice WHERE edms_ac_sales.sales_id = edms_vehicle_invoice.sales_id AND vehicle_id = $vehicle_id";
		
		$sql=$sql." UNION ALL SELECT -basic_price FROM edms_vehicle, edms_vehicle_invoice WHERE edms_vehicle_invoice.exchange_vehicle_id = edms_vehicle.vehicle_id AND edms_vehicle_invoice.vehicle_id = $vehicle_id ";
		
		$sql=$sql." UNION ALL SELECT SUM(tax_amount) as tax_amount FROM edms_ac_sales_tax, edms_tax_grp, edms_tax , edms_vehicle_invoice WHERE edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id AND edms_tax.tax_id = edms_ac_sales_tax.tax_id AND edms_ac_sales_tax.sales_id = edms_vehicle_invoice.sales_id AND edms_ac_sales_tax.vehicle_id = $vehicle_id GROUP BY edms_ac_sales_tax.tax_id";	 
		  
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$total=0;
			foreach($resultArray as $re)
			{
				
				$total = $total + $re[0];
			}
			return $total;
		}
		else
		return 0;
			   
	}
	
	
}

?>