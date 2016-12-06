
<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("vehicle-functions.php");
require_once("vehicle-model-functions.php");
require_once("tax-functions.php");
require_once("account-purchase-functions.php");
require_once("account-jv-functions.php");
require_once("account-ledger-functions.php");
require_once("delivery-challan-functions.php");
require_once("purchase-sales-jv-functions.php");
require_once("common.php");
require_once("bd.php");


function checkForVehiclesInArray($model_id_array,$vehicle_engine_no_array,$vehicle_chasis_no_array,$vehicle_color_id_array,$model_year_array,$basic_price_array,$godown_id_array,$service_book_array,$condition_array,$reg_no_array)
{
	$total_amount=0;
	$has_vehicles=false;
	if(is_array($model_id_array) && count($model_id_array)>0)
	{
		for($i=0;$i<count($model_id_array);$i++)
		{
			$model_id=$model_id_array[$i];
			$vehicle_engine_no=$vehicle_engine_no_array[$i];
			$vehicle_chasis_no=$vehicle_chasis_no_array[$i];
			$vehicle_color_id=$vehicle_color_id_array[$i];
			$condition=$condition_array[$i];
			$service_book=$service_book_array[$i];
			$reg_no=$reg_no_array[$i];
			$model_year=$model_year_array[$i];
			
			$basic_price=$basic_price_array[$i];
			$godown_id=$godown_id_array[$i];
			
			
			if(checkForNumeric($model_id,$vehicle_color_id,$model_year,$basic_price,$godown_id,$condition) && validateForNull($vehicle_chasis_no,$vehicle_engine_no,$service_book) && $basic_price>=0 && ($condition==1 || validateForNull($reg_no)))
			{
				$total_amount = $total_amount + $basic_price;
				$has_vehicles = $total_amount;
			}	
			
		}
				
	}
	return $has_vehicles;
	
	}

function insertVehiclePurchases($purchase_date,$to_ledger,$from_ledger,$remarks,$model_id_array,$vehicle_engine_no_array,$vehicle_chasis_no_array,$vehicle_color_id_array,$model_year_array,$basic_price_array,$cng_cylinder_no_array,$cng_kit_no_array,$godown_id_array,$tax_group_array,$service_book_array,$condition_array,$reg_no_array,$purchase_jvs_array,$exchange=0,$exchange_sales_id=0)
{
	
	$total_amount = checkForVehiclesInArray($model_id_array,$vehicle_engine_no_array,$vehicle_chasis_no_array,$vehicle_color_id_array,$model_year_array,$basic_price_array,$godown_id_array,$service_book_array,$condition_array,$reg_no_array);
	
	

$auto_rasid_type=1;
if($exchange==1)
$auto_rasid_type=4; // under_exchange

if(!checkForNumeric($exchange_sales_id))
$exchange_sales_id=0;
	if($total_amount && checkForNumeric($total_amount) && $total_amount>=0)
	{
		$tot_purchase_amount = 0;
		$vehicle_id_array = array();
		$purchase_id = addPurchase(0,$purchase_date,$purchase_date,$to_ledger,$from_ledger,$remarks,$auto_rasid_type,$exchange_sales_id); // auto rasid type = 1 for vehicle purchase
		
			for($i=0;$i<count($model_id_array);$i++)
		{
			$model_id=$model_id_array[$i];
			$vehicle_engine_no=$vehicle_engine_no_array[$i];
			$vehicle_chasis_no=$vehicle_chasis_no_array[$i];
			$vehicle_color_id=$vehicle_color_id_array[$i];
			$model_year=$model_year_array[$i];
			$basic_price=$basic_price_array[$i];
			$cng_cylinder_no=$cng_cylinder_no_array[$i];
			$cng_kit_no=$cng_kit_no_array[$i];
			$service_book=$service_book_array[$i];
			$reg_no=$reg_no_array[$i];
			$condition=$condition_array[$i];
			$purchase_jvs = $purchase_jvs_array[$i];
			$godown_id=$godown_id_array[$i];
			$tax_group = $tax_group_array[$i];
			
			if($condition==1)
			$reg_no="NA";
			
			if(checkForNumeric($model_id,$vehicle_color_id,$model_year,$basic_price,$godown_id,$condition) && validateForNull($vehicle_chasis_no,$vehicle_engine_no,$service_book) && ($condition==1 || validateForNull($reg_no)))
			{
				
				
				$vehicle_id=insertVehicle($model_id,$vehicle_engine_no,$vehicle_chasis_no,$vehicle_color_id,$model_year,$condition,$basic_price,array(),array(),array(),$godown_id,NULL,$reg_no,'1970-01-01',$cng_cylinder_no,$cng_kit_no,1,0,NULL,'NA','NA',$service_book);
				
				$model_added = insertVehicleModelToPurchase($model_id,$basic_price,1,0,$purchase_id,$godown_id);
				$vehicle_added = addVehicleToPurchase($vehicle_id,$purchase_id);
				
				if($vehicle_added && $model_added)
				{
					
					insertTaxToPurchaseVehicle($purchase_id,$vehicle_id,$tax_group,$basic_price);
					$total_tax_amount = 0;
					if(checkForNumeric($tax_group) & $tax_group>0)
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
						
							if($tax_in_out==2) // if include in purchase add tax amount in the purchase making tax amount 0
							{	
							$total_tax_amount=$total_tax_amount + $tax_amount;
							}
						}
							if($tax_in_out==2)
							{
							$basic_price = $basic_price+$total_tax_amount;	
							updateVehicleBasicPrice($vehicle_id,$basic_price);
							}
					}
				
					$vehicle_id_array[] = $vehicle_id;
					$tot_purchase_amount = $tot_purchase_amount + $basic_price;
					
					insertPurchaseJvsForVehicle($vehicle_id,$from_ledger,$purchase_date,$purchase_jvs,$purchase_id);
					
				}
				else
				{
					removePurchase($purchase_id);
					foreach($vehicle_id_array as $vehicle_id)
					{
						deleteVehicle($vehicle_id);
					}
					return "error";
				}
			}	
			
		} // for loop end	
		
	$purchase = getPurchaseById($purchase_id);
	if(is_numeric($purchase['from_ledger_id']))
	{
		$from_id = "L".$purchase['from_ledger_id'];
		}
	else
	$from_id = "C".$purchase['from_customer_id'];	
	
	updatePurchase($purchase_id,$tot_purchase_amount,$purchase['trans_date'],$purchase['delivery_date'],$purchase['to_ledger_id'],$from_id,$purchase['remarks']);
	
	return $purchase_id;	
	}

return "error";	
	
	
}


	
function addVehicleToPurchase($vehicle_id,$purchase_id)
{
	if(checkForNumeric($purchase_id,$vehicle_id))
	{
		$sql="INSERT INTO edms_ac_purchase_vehicle (purchase_id,vehicle_id) VALUES ($purchase_id,$vehicle_id)";
		dbQuery($sql);
		return true;
	}
	return false;	
	
}

function getPurchaseIdFromVehicleId($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT purchase_id FROM edms_ac_purchase_vehicle WHERE vehicle_id = $vehicle_id";
		$result=dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		
	}
	return false;	
	
}

function getVehiclesForVehiclePurchaseId($purchase_id)
{
	if(checkForNumeric($purchase_id))
	{
		$sql="SELECT purchase_id, edms_ac_purchase_vehicle.vehicle_id, edms_vehicle.model_id, model_name, vehicle_reg_no, vehicle_reg_date, vehicle_engine_no, vehicle_chasis_no, cng_cylinder_no, cng_kit_no, edms_vehicle.vehicle_color_id, vehicle_color, vehicle_model, vehicle_condition, edms_vehicle.godown_id, godown_name, is_purchased, is_sold_by_customer, basic_price, fitness_exp_date, permit_exp_date, battery_make_id, battery_no, key_no, service_book, edms_vehicle.created_by, edms_vehicle.last_updated_by, edms_vehicle.date_added, edms_vehicle.date_modified, customer_id
		FROM edms_ac_purchase_vehicle
		LEFT JOIN edms_vehicle ON  edms_ac_purchase_vehicle.vehicle_id = edms_vehicle.vehicle_id
		LEFT JOIN edms_vehicle_model ON  edms_vehicle_model.model_id = edms_vehicle.model_id
	    LEFT JOIN edms_vehicle_color ON edms_vehicle_color.vehicle_color_id = edms_vehicle.vehicle_color_id
		LEFT JOIN edms_godown ON  edms_godown.godown_id = edms_vehicle.godown_id
		WHERE  edms_ac_purchase_vehicle.purchase_id = $purchase_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		
	}
	
}

function getAllVehiclePurchases()
{
	return getAllPurchasesByType(1);	
}

function getNoOfVehiclesForPurchaseId($purchase_id)
{
	if(checkForNumeric($purchase_id))
	{
		$sql="SELECT COUNT(purchase_vehicle_id) FROM edms_ac_purchase_vehicle WHERE purchase_id = $purchase_id GROUP BY purchase_id";
		$result=dbQuery($sql);
		$resultArray = dbResultToArray($result);
		return $resultArray[0][0];
	}
	
}

function getVehiclePurchaseById($purchase_id)
{
	
	$purchase=getPurchaseById($purchase_id);	
	$vehicles = getVehiclesForVehiclePurchaseId($purchase_id);
	$tax = getTaxForVehiclePurcahseId($purchase_id);
	return array($purchase,$vehicles,$tax);
}	

function deleteVehiclePurchase($purchase_id)
{
	if(checkForNumeric($purchase_id))
	{
			$vehicles = getVehiclesForVehiclePurchaseId($purchase_id);
			
			foreach($vehicles as $vehicle)
			{
				$vehicle_id = $vehicle['vehicle_id'];
				$delivery_challan = getDeliveryChallanByVehicleId($vehicle_id);
				if($delivery_challan)
				return "delivery_challan_error";
			}
			deleteVehicleModelToPurchase($purchase_id);
			removePurchase($purchase_id);
			foreach($vehicles as $vehicle)
			{
				$vehicle_id = $vehicle['vehicle_id'];
				deletePurchaseJvsForVehicle($vehicle_id);
				deleteVehicle($vehicle_id);
			}
			
			
			
			return "success";
			
	}	
	return "error";
}

function editVehiclePurchase($purchase_id,$purchase_date,$to_ledger,$from_ledger,$remarks,$vehicle_id_array,$model_id_array,$vehicle_engine_no_array,$vehicle_chasis_no_array,$vehicle_color_id_array,$model_year_array,$basic_price_array,$cng_cylinder_no_array,$cng_kit_no_array,$godown_id_array,$tax_group_array,$service_book_array,$condition_array,$reg_no_array,$purchase_jvs_array)
{
	$tot_purchase_amount = 0;
	
	$total_amount = checkForVehiclesInArray($model_id_array,$vehicle_engine_no_array,$vehicle_chasis_no_array,$vehicle_color_id_array,$model_year_array,$basic_price_array,$godown_id_array,$service_book_array,$condition_array,$reg_no_array);
	
	if(!checkForNumeric($total_amount) || $total_amount<0)
	return "error";
	
	$old_purchase = getPurchaseById($purchase_id);
	updatePurchase($purchase_id,$tot_purchase_amount,$purchase_date,$purchase_date,$to_ledger,$from_ledger,'');
	deleteVehicleModelToPurchase($purchase_id);
	
	for($i=0;$i<count($model_id_array);$i++)
		{
			$vehicle_id = $vehicle_id_array[$i];
			$model_id=$model_id_array[$i];
			$vehicle_engine_no=$vehicle_engine_no_array[$i];
			$vehicle_chasis_no=$vehicle_chasis_no_array[$i];
			$vehicle_color_id=$vehicle_color_id_array[$i];
			$model_year=$model_year_array[$i];
			$basic_price=$basic_price_array[$i];
			$cng_cylinder_no=$cng_cylinder_no_array[$i];
			$cng_kit_no=$cng_kit_no_array[$i];
			$service_book=$service_book_array[$i];
			$reg_no=$reg_no_array[$i];
			$condition=$condition_array[$i];
			$purchase_jvs = $purchase_jvs_array[$i];
			$godown_id=$godown_id_array[$i];
			$tax_group = $tax_group_array[$i];
			
			if($condition==1)
			$reg_no="NA";
			
			if(checkForNumeric($model_id,$vehicle_color_id,$model_year,$basic_price,$godown_id,$condition) && validateForNull($vehicle_chasis_no,$vehicle_engine_no,$service_book)  && ($condition==1 || validateForNull($reg_no)))
			{
				
				if(checkForNumeric($vehicle_id))
				{
					$vehicle=getVehicleById($vehicle_id);
					if(getDeliveryChallanByVehicleId($vehicle_id))
					{
						
						$model_id=$vehicle['model_id'];
						$vehicle_engine_no=$vehicle['vehicle_engine_no'];
						$vehicle_chasis_no=$vehicle['vehicle_chasis_no'];
						$vehicle_color_id=$vehicle['vehicle_color_id'];
						$model_year=$vehicle['vehicle_model'];
						$cng_cylinder_no=$vehicle['cng_cylinder_no'];
						$cng_kit_no=$vehicle['cng_kit_no'];
						$battery_make_id=$vehicle['battery_make_id'];
						
						if(!validateForNull($battery_make_id))
						$battery_make_id=NULL;
					}
					
					updateVehicle($vehicle_id,$model_id,$vehicle_engine_no,$vehicle_chasis_no,$vehicle_color_id,$model_year,$condition,$basic_price,array(),array(),array(),$godown_id,NULL,$reg_no,'1970-01-01',$cng_cylinder_no,$cng_kit_no,$vehicle['is_purchased'],$vehicle['is_sold_by_customer'],$battery_make_id,$vehicle['battery_no'],$vehicle['key_no'],$service_book);
					deleteTaxForVehiclePurchase($vehicle_id,$old_purchase);
					$model_added = insertVehicleModelToPurchase($model_id,$basic_price,1,0,$purchase_id,$godown_id);
					$vehicle_added = true;
				}
				else
				{
				$vehicle_id=insertVehicle($model_id,$vehicle_engine_no,$vehicle_chasis_no,$vehicle_color_id,$model_year,$condition,$basic_price,array(),array(),array(),$godown_id,NULL,$reg_no,'1970-01-01',$cng_cylinder_no,$cng_kit_no,1,0,NULL,'NA','NA',$service_book);
				$vehicle_added = addVehicleToPurchase($vehicle_id,$purchase_id);
				$model_added = insertVehicleModelToPurchase($model_id,$basic_price,1,0,$purchase_id,$godown_id);
				}
			
			if($vehicle_added && $model_added)
				{
					
					insertTaxToPurchaseVehicle($purchase_id,$vehicle_id,$tax_group,$basic_price);
					$total_tax_amount = 0;
					if(checkForNumeric($tax_group) & $tax_group>0)
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
						
							if($tax_in_out==2) // if include in purchase add tax amount in the purchase making tax amount 0
							{	
							$total_tax_amount=$total_tax_amount + $tax_amount;
							}
						}
							if($tax_in_out==2)
							{
							$basic_price = $basic_price+$total_tax_amount;	
							updateVehicleBasicPrice($vehicle_id,$basic_price);
							}
					}
					
					$tot_purchase_amount = $tot_purchase_amount + $basic_price;
					deletePurchaseJvsForVehicle($vehicle_id);
					insertPurchaseJvsForVehicle($vehicle_id,$from_ledger,$purchase_date,$purchase_jvs,$purchase_id);
				}
				
			}
		}
		
		updatePurchase($purchase_id,$tot_purchase_amount,$purchase_date,$purchase_date,$to_ledger,$from_ledger,'');
		
		return "success";
}

function insertVehicleModelToPurchase($model_id,$rate,$quantity,$discount,$purchase_id,$godown_id)
{
	if(checkForNumeric($model_id,$rate,$quantity,$discount,$godown_id)  && $model_id>0 && $rate>=0 && $discount>=0 && $quantity>0)
			{
				
				$amount = $quantity * $rate;
				$nett_amount = $amount - $amount*($discount/100);
				
				$item_id = "NULL";
				$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
				
				
				$sql="INSERT INTO edms_ac_purchase_item (item_id,model_id,rate,quantity,discount,amount,net_amount,purchase_id,godown_id,created_by,last_updated_by,date_added,date_modified) VALUES ($item_id,$model_id,$rate,$quantity,$discount,$amount,$nett_amount,$purchase_id,$godown_id,$admin_id,$admin_id,NOW(),NOW())";
				dbQuery($sql);
				return dbInsertId();
			}	
		return false;	
	
}

function deleteVehicleModelToPurchase($purchase_id)
{
	if(checkForNumeric($purchase_id))
	{
		$sql="DELETE FROM edms_ac_purchase_item WHERE purchase_id = $purchase_id";
		dbQuery($sql);
		return true;
	}	
	return false;
}

function getRemainingPurchaseBalanceForVehicleId($vehicle_id)
{
	
	
	if(checkForNumeric($vehicle_id))
	{
		
		$purchase_id=getPurchaseIdFromVehicleId($vehicle_id);
		
		if(!$purchase_id)
		{
		$vehicle = getVehicleById($vehicle_id);
		$from_ledger_id = $vehicle['extra_ledger_id'];
		}
		else if(checkForNumeric($purchase_id))
		{
		$purchase = getPurchaseById($purchase_id);
		$from_ledger_id = $purchase['from_ledger_id'];
		$from_customer_id = $purchase['from_customer_id'];	
		}
		else
		return 0;
		
		if(!is_numeric($from_ledger_id))	
		return 0;
		
		$sql="SELECT IF(opening_cd_extra=0,opening_balance_extra,-opening_balance_extra) as amount FROM edms_vehicle WHERE vehicle_id = $vehicle_id ";
		if(is_numeric($from_ledger_id))
		$sql=$sql."AND extra_ledger_id = $from_ledger_id ";
	// Loan jv 
		$sql=$sql." UNION ALL
		SELECT IF(to_ledger_id = $from_ledger_id OR auto_rasid_type=5,edms_ac_jv_cd.amount,-edms_ac_jv_cd.amount) as amount
			  FROM edms_ac_jv,edms_ac_jv_cd
			  WHERE auto_id=$vehicle_id AND edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id
			   AND (auto_rasid_type=2 OR auto_rasid_type=5)";
	// vehicle recipts
		$sql=$sql." UNION ALL
		SELECT  -amount
			  FROM edms_ac_receipt
			  WHERE auto_id=$vehicle_id
			   AND auto_rasid_type=4";
		if($from_ledger_id && is_numeric($from_ledger_id))
		$sql=$sql." AND to_ledger_id = $from_ledger_id ";
		else if($from_customer_id && is_numeric($from_customer_id))
		$sql=$sql." AND to_customer_id = $from_customer_id ";	   	   
		// vehicle payments
		$sql=$sql." UNION ALL
		SELECT  amount
			  FROM edms_ac_payment
			  WHERE auto_id=$vehicle_id
			   AND auto_rasid_type=4  ";
		if($from_ledger_id && is_numeric($from_ledger_id))
		$sql=$sql." AND from_ledger_id = $from_ledger_id ";
		else if($from_customer_id && is_numeric($from_customer_id))
		$sql=$sql." AND from_customer_id = $from_customer_id ";	 	   
		// vehicle sale amount	   	   	   
		$sql=$sql." UNION ALL SELECT -amount FROM edms_ac_purchase, edms_ac_purchase_vehicle WHERE edms_ac_purchase.purchase_id = edms_ac_purchase_vehicle.purchase_id AND vehicle_id = $vehicle_id ";
		$sql=$sql." UNION ALL SELECT -SUM(tax_amount) as tax_amount FROM edms_ac_purchase_tax, edms_tax_grp, edms_tax , edms_ac_purchase_vehicle WHERE edms_tax_grp.tax_group_id = edms_ac_purchase_tax.tax_group_id AND edms_tax.tax_id = edms_ac_purchase_tax.tax_id AND edms_ac_purchase_tax.purchase_id = edms_ac_purchase_vehicle.purchase_id AND edms_ac_purchase_tax.vehicle_id = $vehicle_id GROUP BY edms_ac_purchase_tax.tax_id ";	 
		 
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