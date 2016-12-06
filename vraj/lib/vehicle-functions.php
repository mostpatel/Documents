<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("delivery-challan-functions.php");
require_once("vehicle-invoice-functions.php");
require_once("customer-functions.php");
require_once("vehicle-sale-cert-functions.php");
require_once("account-ledger-functions.php");
require_once("vehicle-model-functions.php");
require_once("job-card-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listVehiclesForCustomer($customer_id){
	
	try
	{
		if(checkForNumeric($customer_id))
		{
		$sql="SELECT vehicle_id,model_id, vehicle_reg_no, vehicle_reg_date, vehicle_engine_no, vehicle_chasis_no, cng_cylinder_no, cng_kit_no, vehicle_color_id, vehicle_model, vehicle_condition, godown_id, is_purchased, is_sold_by_customer, basic_price, fitness_exp_date, permit_exp_date, battery_make_id, battery_no, key_no, service_book,  battery_service_book_no, service_no, ledger_id,created_by, last_updated_by, date_added, date_modified, customer_id, opening_balance, opening_cd, opening_balance_extra, opening_cd_extra, extra_ledger_id FROM edms_vehicle WHERE customer_id = $customer_id";
	$result=dbQuery($sql);
	$resultArray = dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return false;	
		}
	}
	catch(Exception $e)
	{
	}
	
}	

function listVehicleIdsForCustomer($customer_id){
	
	try
	{
		if(checkForNumeric($customer_id))
		{
		$sql="SELECT vehicle_id FROM edms_vehicle WHERE customer_id = $customer_id";
	$result=dbQuery($sql);
	$resultArray = dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
	$returnArray = array();
	foreach($resultArray as $vehicle)
	$returnArray[]=$vehicle[0];
	return $returnArray;
	}
	else
	return false;	
		}
	}
	catch(Exception $e)
	{
	}
	
}	

function getVehicleRegNoStringForCustomer($customer_id)
{
	try
	{
		if(checkForNumeric($customer_id))
		{
		$sql="SELECT GROUP_CONCAT(vehicle_reg_no) as reg_no_strin FROM edms_vehicle WHERE customer_id = $customer_id GROUP BY customer_id";
	$result=dbQuery($sql);
	$resultArray = dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
	return $resultArray[0][0];
	}
	else
	return false;	
		}
	}
	catch(Exception $e)
	{
	}
	
}


function insertVehicle($model_id,$vehicle_engine_no,$vehicle_chasis_no,$vehicle_color_id,$model_year,$condition,$basic_price,$proof_type_id_array,$proof_no_array,$proof_img_array,$godown_id=NULL,$customer_id=NULL,$vehicle_reg_no="NA",$vehicle_reg_date="01/01/1970",$cng_cylinder_no="NA",$cng_kit_no="NA",$is_purchased=0,$is_sold_customer=0,$battery_make_id=NULL,$battery_no="NA",$key_no="NA",$service_book="NA",$fitness_exp_date="01/01/1970",$permit_exp_date="01/01/1970",$battery_service_no="NA",$service_no="NA",$ledger_id="NULL",$opening_balance=0,$opening_cd=0,$extra_ledger_id="NULL",$opening_balance_extra=0,$opening_cd_extra=0){
	try
	{
		
		$vehicle_reg_no=clean_data($vehicle_reg_no);
		
		if($vehicle_reg_no!="NA")
		$vehicle_reg_no=stripVehicleno($vehicle_reg_no);
		
		$vehicle_reg_date=clean_data($vehicle_reg_date);
		$vehicle_engine_no=clean_data($vehicle_engine_no);
		$vehicle_chasis_no=clean_data($vehicle_chasis_no);
		$fitness_exp_date=clean_data($fitness_exp_date);
		$permit_exp_date=clean_data($permit_exp_date);
		$model_id=clean_data($model_id);
		$vehicle_color_id=clean_data($vehicle_color_id);
		$model_year=clean_data($model_year);
		$condition=clean_data($condition);
		$basic_price=clean_data($basic_price);
		$cng_cylinder_no=clean_data($cng_cylinder_no);
		$cng_kit_no=clean_data($cng_kit_no);
		$godown_id=clean_data($godown_id);
		$is_purchased=clean_data($is_purchased);
		$is_sold_customer=clean_data($is_sold_customer);
		$battery_make_id=clean_data($battery_make_id);
		$battery_no=clean_data($battery_no);
		$key_no=clean_data($key_no);
		$service_book=clean_data($service_book);
		$customer_id=clean_data($customer_id);
		$battery_service_no=clean_data($battery_service_no);
		$service_no=clean_data($service_no);
		
		if(!validateForNull($vehicle_reg_no))
		$vehicle_reg_no="NA";
		if($is_purchased==0 || $is_purchased==2)
		{
		if(!validateForNull($vehicle_chasis_no))
		$vehicle_chasis_no="NA";
		
		if(!validateForNull($vehicle_engine_no))
		$vehicle_engine_no="NA";
		}
		if(!validateForNull($cng_cylinder_no))
		$cng_cylinder_no="NA";
		
		if(!validateForNull($cng_kit_no))
		$cng_kit_no="NA";
		
		if(!validateForNull($battery_no))
		$battery_no="NA";
		
		if(!validateForNull($battery_service_no))
		$battery_service_no="NA";
		
		if(!validateForNull($service_no))
		$service_no="NA";
		
		if(!validateForNull($key_no))
		$key_no="NA";
		
		if(!validateForNull($service_book))
		$service_book="NA";
		
		if(!validateForNull($godown_id))
		$godown_id="NULL";
		
		if(!validateForNull($customer_id))
		$customer_id="NULL";
		
		if(checkForNumeric($model_year) && $model_year==-1)
		$model_year=1970;
		
		if(!validateForNull($battery_make_id))
		$battery_make_id="NULL";
		
		if(!validateForNull($vehicle_color_id))
		$vehicle_color_id="NULL";
		
		if(!validateForNull($is_purchased))
		$is_purchased=0;
		
		if(!validateForNull($is_sold_customer))
		$is_sold_customer=0;
		
		if(!validateForNull($opening_balance))
		$opening_balance=0;
		
		if(!validateForNull($opening_cd))
		$opening_cd=0;
		
		if(!validateForNull($vehicle_reg_date))
		$vehicle_reg_date="01/01/1970";
		
		if(!validateForNull($permit_exp_date))
		$permit_exp_date="01/01/1970";
		
		if(!validateForNull($fitness_exp_date))
		$fitness_exp_date="01/01/1970";
		
		if(!checkForNumeric($ledger_id) || $ledger_id==-1)
		$ledger_id="NULL";
		
		
		
		if(!validateForNull($opening_balance_extra))
		$opening_balance_extra=0;
		
		if(!validateForNull($opening_cd_extra))
		$opening_cd_extra=0;
		
		if(!checkForNumeric($extra_ledger_id) || $extra_ledger_id==-1)
		{
		$extra_ledger_id="NULL";
		$opening_balance_extra=0;
		$opening_cd_extra=0;
		}
		$ledger = getLedgerById($extra_ledger_id);	
			$extra_ledger_opening_balance = $ledger['opening_balance'];	
			$extra_ledger_opening_cd = $ledger['opening_cd'];
			$extra_ledger_current_balance = $ledger['current_balance'];
			$extra_ledger_current_cd = $ledger['current_balance_cd'];
			
		
			
		if(checkForNumeric($model_id,$model_year,$condition,$basic_price,$opening_balance,$opening_cd) && validateForNull($vehicle_engine_no,$vehicle_reg_date,$vehicle_chasis_no,$model_year,$vehicle_reg_no,$cng_cylinder_no,$cng_kit_no,$battery_no,$key_no,$service_book,$godown_id,$customer_id,$battery_make_id,$is_purchased,$is_sold_customer,$fitness_exp_date,$permit_exp_date,$vehicle_color_id,$battery_service_no,$service_no,$ledger_id)  && !checkForDuplicateVehicle($vehicle_chasis_no,$vehicle_engine_no,$vehicle_reg_no))
		{
			
			if(!checkForNumeric($vehicle_reg_no[0],$vehicle_reg_no[1]) && ($vehicle_reg_no[2]=='0'))
			{
				$vehicle_reg_no=substr($vehicle_reg_no,0,2).substr($vehicle_reg_no,3);
			}
			
			$vehicle_reg_no=strtoupper($vehicle_reg_no);	
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			
			$vehicle_reg_date = str_replace('/', '-', $vehicle_reg_date);
			$vehicle_reg_date=date('Y-m-d',strtotime($vehicle_reg_date));
			
			$fitness_exp_date = str_replace('/', '-', $fitness_exp_date);
			$fitness_exp_date=date('Y-m-d',strtotime($fitness_exp_date));
			
			$permit_exp_date = str_replace('/', '-', $permit_exp_date);
			$permit_exp_date=date('Y-m-d',strtotime($permit_exp_date));
			
			$sql="INSERT INTO edms_vehicle
			      (model_id, vehicle_reg_no, vehicle_reg_date, vehicle_engine_no, vehicle_chasis_no, cng_cylinder_no, cng_kit_no, vehicle_color_id, vehicle_model, vehicle_condition, godown_id, is_purchased, is_sold_by_customer, basic_price, fitness_exp_date, permit_exp_date, battery_make_id, battery_no, key_no, service_book, battery_service_book_no, service_no, ledger_id, created_by, last_updated_by, date_added, date_modified, customer_id,opening_balance, opening_cd, opening_balance_extra, opening_cd_extra,extra_ledger_id)
				  VALUES
				  ($model_id,'$vehicle_reg_no','$vehicle_reg_date','$vehicle_engine_no','$vehicle_chasis_no','$cng_cylinder_no','$cng_kit_no',$vehicle_color_id,'$model_year',$condition,$godown_id,$is_purchased,$is_sold_customer,$basic_price,'$fitness_exp_date','$permit_exp_date',$battery_make_id,'$battery_no','$key_no','$service_book','$battery_service_no','$service_no', $ledger_id,$admin_id,$admin_id,NOW(),NOW(),$customer_id,$opening_balance,$opening_cd,$opening_balance_extra,$opening_cd_extra,$extra_ledger_id)";
			
			dbQuery($sql);
			$vehicle_id=dbInsertId();
			addVehicleProof($vehicle_id,$vehicle_reg_no,$proof_type_id_array,$proof_no_array,$proof_img_array,$scanImg);
			
			$customer=getCustomerDetailsByCustomerId($customer_id);
			$customer_opening_balance = $customer['opening_balance'];
			$customer_opening_cd = $customer['opening_cd'];
			$customer_current_balance = $customer['current_balance'];
			$customer_current_balance_cd = $customer['current_balance_cd'];
			
			if(checkForNumeric($extra_ledger_id) && $extra_ledger_id>0)
			{
				
			$ledger = getLedgerById($extra_ledger_id);	
			$extra_ledger_opening_balance = $ledger['opening_balance'];	
			$extra_ledger_opening_cd = $ledger['opening_cd'];
			$extra_ledger_current_balance = $ledger['current_balance'];
			$extra_ledger_current_cd = $ledger['current_balance_cd'];
				
				if($extra_ledger_opening_cd==$opening_cd_extra)
				{
					$new_opening_balance_extra = $extra_ledger_opening_balance + $opening_balance_extra;
					$new_opening_cd_extra = $opening_cd_extra;
				}
				else
				{ 
					if($extra_ledger_opening_balance>$opening_balance_extra)
					{
					$new_opening_balance_extra = $extra_ledger_opening_balance - $opening_balance_extra;
					$new_opening_cd_extra = $extra_ledger_opening_cd;
					}
					else
					{
					$new_opening_balance_extra = $opening_balance_extra-$extra_ledger_opening_balance;
					$new_opening_cd_extra = $opening_cd_extra;
					}
				}
				
				if($extra_ledger_current_cd==$opening_cd_extra)
				{
					$new_current_balance_extra = $extra_ledger_current_balance + $opening_balance_extra;
					$new_current_balance_cd_extra = $opening_cd_extra;
				}
				else
				{ 
					if($extra_ledger_current_balance>$opening_balance_extra)
					{
					$new_current_balance_extra = $extra_ledger_current_balance - $opening_balance_extra;
					$new_current_balance_cd_extra = $extra_ledger_current_cd;
					}
					else
					{
					$new_current_balance_extra = $opening_balance_extra-$extra_ledger_current_balance;
					$new_current_balance_cd_extra = $opening_cd_extra;
					}
				}
				
				setLedgerOpeningBalance($new_opening_balance_extra,$new_opening_cd_extra,$extra_ledger_id);
				setLedgerCurrentBalance($new_current_balance_extra,$new_current_balance_cd_extra,$extra_ledger_id);
			}
		
			if($customer_opening_cd==$opening_cd)
			{
				$new_opening_balance = $customer_opening_balance + $opening_balance;
				$new_opening_cd = $opening_cd;
			}
			else
			{ 
				if($customer_opening_balance>$opening_balance)
				{
				$new_opening_balance = $customer_opening_balance - $opening_balance;
				$new_opening_cd = $customer_opening_cd;
				}
				else
				{
				$new_opening_balance = $opening_balance-$customer_opening_balance;
				$new_opening_cd = $opening_cd;
				}
			}
			
			if($customer_current_balance_cd==$opening_cd)
			{
				$new_current_balance = $customer_current_balance + $opening_balance;
				$new_current_balance_cd = $opening_cd;
			}
			else
			{ 
				if($customer_current_balance>$opening_balance)
				{
				$new_current_balance = $customer_current_balance - $opening_balance;
				$new_current_cd = $customer_current_balance_cd;
				}
				else
				{
				$new_current_balance = $opening_balance-$customer_current_balance;
				$new_current_cd = $opening_cd;
				}
			}
			setOpeningBalanceForCustomer($customer_id,$new_opening_balance,$new_opening_cd);
			setCurrentBalanceForCustomer($customer_id,$new_current_balance,$new_current_balance_cd);
				
			return $vehicle_id;
		}
		else
		{return "error";}
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteVehicle($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfVehicleInUse($id))
		{
			$sql="DELETE FROM edms_vehicle WHERE vehicle_id = $id";	
			dbQuery($sql);
			return "success";
		}
		return "error"; 
		
	}
	catch(Exception $e)
	{
	}
	
}	

function updateVehicle($id,$model_id,$vehicle_engine_no,$vehicle_chasis_no,$vehicle_color_id,$model_year,$condition,$basic_price,$proof_type_id_array,$proof_no_array,$proof_img_array,$godown_id=NULL,$customer_id=NULL,$vehicle_reg_no="NA",$vehicle_reg_date="01/01/1970",$cng_cylinder_no="NA",$cng_kit_no="NA",$is_purchased=0,$is_sold_customer=0,$battery_make_id=NULL,$battery_no="NA",$key_no="NA",$service_book="NA",$fitness_exp_date="01/01/1970",$permit_exp_date="01/01/1970",$battery_service_no="NA",$service_no="NA",$ledger_id="NULL",$opening_balance=0,$opening_cd=0,$extra_ledger_id="NULL",$opening_balance_extra=0,$opening_cd_extra=0){
	try
	{
		
		$vehicle_reg_no=clean_data($vehicle_reg_no);
		if($vehicle_reg_no!="NA")
		$vehicle_reg_no=stripVehicleno($vehicle_reg_no);
		
		$vehicle_reg_date=clean_data($vehicle_reg_date);
		$vehicle_engine_no=clean_data($vehicle_engine_no);
		$vehicle_chasis_no=clean_data($vehicle_chasis_no);
		$fitness_exp_date=clean_data($fitness_exp_date);
		$permit_exp_date=clean_data($permit_exp_date);
		$model_id=clean_data($model_id);
		$vehicle_color_id=clean_data($vehicle_color_id);
		$model_year=clean_data($model_year);
		$condition=clean_data($condition);
		$basic_price=clean_data($basic_price);
		$cng_cylinder_no=clean_data($cng_cylinder_no);
		$cng_kit_no=clean_data($cng_kit_no);
		$godown_id=clean_data($godown_id);
		$is_purchased=clean_data($is_purchased);
		$is_sold_customer=clean_data($is_sold_customer);
		$battery_make_id=clean_data($battery_make_id);
		$battery_no=clean_data($battery_no);
		$key_no=clean_data($key_no);
		$service_book=clean_data($service_book);
		$customer_id=clean_data($customer_id);
		$battery_service_no=clean_data($battery_service_no);
		$service_no=clean_data($service_no);
		
		if(!validateForNull($vehicle_reg_no))
		$vehicle_reg_no="NA";
		
		if(!validateForNull($cng_cylinder_no))
		$cng_cylinder_no="NA";
		
		if(!validateForNull($cng_kit_no))
		$cng_kit_no="NA";
		
		if(!validateForNull($battery_no))
		$battery_no="NA";
		
		if(!validateForNull($key_no))
		$key_no="NA";
		
		if(checkForNumeric($model_year) && $model_year==-1)
		$model_year=1970;
		
		if(!validateForNull($service_book))
		$service_book="NA";
		
		if(!validateForNull($godown_id))
		$godown_id="NULL";
		
		if(!validateForNull($customer_id))
		$customer_id="NULL";
		
		if(!validateForNull($battery_make_id))
		$battery_make_id="NULL";
		
		if(!validateForNull($vehicle_color_id))
		$vehicle_color_id="NULL";
		
		if(!validateForNull($is_purchased))
		$is_purchased=0;
		
		if(!validateForNull($is_sold_customer))
		$is_sold_customer=0;
		
		if(!validateForNull($opening_balance))
		$opening_balance=0;
		
		if(!validateForNull($opening_cd))
		$opening_cd=0;
		
		if(!validateForNull($vehicle_reg_date))
		$vehicle_reg_date="01/01/1970";
		
		if(!validateForNull($permit_exp_date))
		$permit_exp_date="01/01/1970";
		
		if(!validateForNull($fitness_exp_date))
		$fitness_exp_date="01/01/1970";
		
		if(!validateForNull($battery_service_no))
		$battery_service_no="NA";
		
		if(!validateForNull($service_no))
		$service_no="NA";
		
		
		if(!checkForNumeric($ledger_id) || $ledger_id==-1)
		$ledger_id="NULL";
		
	
		if(!validateForNull($opening_balance_extra))
		$opening_balance_extra=0;
		
		if(!validateForNull($opening_cd_extra))
		$opening_cd_extra=0;
	
		if(!checkForNumeric($extra_ledger_id) || $extra_ledger_id==-1)
		{
		$extra_ledger_id="NULL";
		$opening_balance_extra=0;
		$opening_cd_extra=0;
		}
		
		
		if(checkForNumeric($model_id,$model_year,$condition,$basic_price,$opening_balance,$opening_cd) && validateForNull($vehicle_engine_no,$vehicle_reg_date,$vehicle_chasis_no,$model_year,$vehicle_reg_no,$cng_cylinder_no,$cng_kit_no,$battery_no,$key_no,$service_book,$godown_id,$customer_id,$battery_make_id,$is_purchased,$is_sold_customer,$fitness_exp_date,$permit_exp_date,$vehicle_color_id,$service_no,$battery_service_no)  && !checkForDuplicateVehicle($vehicle_chasis_no,$vehicle_engine_no,$vehicle_reg_no,$id))
		{
			
			if(!checkForNumeric($vehicle_reg_no[0],$vehicle_reg_no[1]) && ($vehicle_reg_no[2]=='0'))
			{
				$vehicle_reg_no=substr($vehicle_reg_no,0,2).substr($vehicle_reg_no,3);
			}
			$customer_id = getCustomerIDFromVehicleId($id);
			$old_vehicle = getVehicleById($id);
			$old_vehicle_opening_balance = $old_vehicle['opening_balance'];
			$old_vehicle_opening_cd = $old_vehicle['opening_cd'];
			
			$old_vehicle_opening_balance_extra = $old_vehicle['opening_balance_extra'];
			$old_vehicle_opening_cd_extra = $old_vehicle['opening_cd_extra'];
			$old_vehicle_extra_ledger_id = $old_vehicle['extra_ledger_id'];
			
			
			
			$vehicle_reg_no=strtoupper($vehicle_reg_no);	
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			
			$vehicle_reg_date = str_replace('/', '-', $vehicle_reg_date);
			$vehicle_reg_date=date('Y-m-d',strtotime($vehicle_reg_date));
			
			$fitness_exp_date = str_replace('/', '-', $fitness_exp_date);
			$fitness_exp_date=date('Y-m-d',strtotime($fitness_exp_date));
			
			$permit_exp_date = str_replace('/', '-', $permit_exp_date);
			$permit_exp_date=date('Y-m-d',strtotime($permit_exp_date));
			
			$sql="UPDATE edms_vehicle
			      SET model_id = $model_id, vehicle_reg_no = '$vehicle_reg_no', vehicle_reg_date = '$vehicle_reg_date', vehicle_engine_no = '$vehicle_engine_no', vehicle_chasis_no = '$vehicle_chasis_no', cng_cylinder_no = '$cng_cylinder_no', cng_kit_no = '$cng_kit_no' ,vehicle_color_id = $vehicle_color_id, vehicle_model = '$model_year', vehicle_condition = $condition, godown_id = $godown_id, is_purchased = $is_purchased , is_sold_by_customer = $is_sold_customer, basic_price = $basic_price, fitness_exp_date = '$fitness_exp_date', permit_exp_date = '$permit_exp_date', battery_no = '$battery_no', battery_make_id = $battery_make_id, key_no = '$key_no', service_book = '$service_book', battery_service_book_no = '$battery_service_no' , service_no = '$service_no', ledger_id = $ledger_id,  last_updated_by = $admin_id, date_modified = NOW(), opening_balance = $opening_balance , opening_cd = $opening_cd, opening_balance_extra = $opening_balance_extra, opening_cd_extra = $opening_cd_extra, extra_ledger_id = $extra_ledger_id
				 WHERE vehicle_id=$id";
			dbQuery($sql);
			addVehicleProof($id,$vehicle_reg_no,$proof_type_id_array,$proof_no_array,$proof_img_array,$scanImg);
			
			$customer=getCustomerDetailsByCustomerId($customer_id);
			
			$customer_opening_balance = $customer['opening_balance'];
			$customer_opening_cd = $customer['opening_cd'];
			$customer_current_balance = $customer['current_balance'];
			$customer_current_balance_cd = $customer['current_balance_cd'];
			
			if(checkForNumeric($old_vehicle_extra_ledger_id) && $old_vehicle_extra_ledger_id>0)
			{
				
			$old_ledger = getLedgerById($old_vehicle_extra_ledger_id);	
			$old_extra_ledger_opening_balance = $old_ledger['opening_balance'];	
			$old_extra_ledger_opening_cd = $old_ledger['opening_cd'];
			$old_extra_ledger_current_balance = $old_ledger['current_balance'];
			$old_extra_ledger_current_cd = $old_ledger['current_balance_cd'];	
			
			
			if($old_extra_ledger_opening_cd==$old_vehicle_opening_cd_extra)
			{
				if($old_extra_ledger_opening_balance>=$old_vehicle_opening_balance_extra)
				{
					$new_old_extra_ledger_opening_balance = $old_extra_ledger_opening_balance - $old_vehicle_opening_balance_extra;
					$new_old_extra_ledger_opening_cd = $old_extra_ledger_opening_cd;
					
				}
				else
				{
					$new_old_extra_ledger_opening_balance = $old_vehicle_opening_balance_extra - $old_extra_ledger_opening_balance;
					$new_old_extra_ledger_opening_cd = 1-$old_vehicle_opening_cd_extra;
				}
			}	
			else
			{
				
					$new_old_extra_ledger_opening_balance = $old_extra_ledger_opening_balance + $old_vehicle_opening_balance_extra;
					$new_old_extra_ledger_opening_cd = $old_extra_ledger_opening_cd;
									
			}
			
			if($old_extra_ledger_current_cd==$old_vehicle_opening_cd_extra)
				{
					if($old_extra_ledger_current_balance>=$old_vehicle_opening_balance_extra)
					{
					$new_old_extra_ledger_current_balance = $old_extra_ledger_current_balance - $old_vehicle_opening_balance_extra;
					$new_old_extra_ledger_current_cd = $old_extra_ledger_current_cd;
					}
					else
					{
					$new_old_extra_ledger_current_balance = $old_vehicle_opening_balance_extra-$old_extra_ledger_current_balance;
					$new_old_extra_ledger_current_cd = 1-$old_vehicle_opening_cd_extra;
					}
					
					
				}
				else
				{ 
					$new_old_extra_ledger_current_balance = $new_old_extra_ledger_current_balance + $old_vehicle_opening_balance_extra;
					$new_old_extra_ledger_current_cd = $old_extra_ledger_current_cd;
				}
			
			setLedgerOpeningBalance($new_old_extra_ledger_opening_balance,$new_old_extra_ledger_opening_cd,$old_vehicle_extra_ledger_id);
				setLedgerCurrentBalance($new_old_extra_ledger_current_balance,$new_old_extra_ledger_current_cd,$old_vehicle_extra_ledger_id);
			 	
			}
			
			if(checkForNumeric($extra_ledger_id) && $extra_ledger_id>0)
			{
				
			$ledger = getLedgerById($extra_ledger_id);	
			$extra_ledger_opening_balance = $ledger['opening_balance'];	
			$extra_ledger_opening_cd = $ledger['opening_cd'];
			$extra_ledger_current_balance = $ledger['current_balance'];
			$extra_ledger_current_cd = $ledger['current_balance_cd'];
				
				if($extra_ledger_opening_cd==$opening_cd_extra)
				{
					$new_opening_balance_extra = $extra_ledger_opening_balance + $opening_balance_extra;
					$new_opening_cd_extra = $opening_cd_extra;
				}
				else
				{ 
					if($extra_ledger_opening_balance>$opening_balance_extra)
					{
					$new_opening_balance_extra = $extra_ledger_opening_balance - $opening_balance_extra;
					$new_opening_cd_extra = $extra_ledger_opening_cd;
					}
					else
					{
					$new_opening_balance_extra = $opening_balance_extra - $extra_ledger_opening_balance;
					$new_opening_cd_extra = $opening_cd_extra;
					}
				}
				
				if($extra_ledger_current_cd==$opening_cd_extra)
				{
					$new_current_balance_extra = $extra_ledger_current_balance + $opening_balance_extra;
					$new_current_balance_cd_extra = $opening_cd_extra;
				}
				else
				{ 
					if($extra_ledger_current_balance>$opening_balance_extra)
					{
					$new_current_balance_extra = $extra_ledger_current_balance - $opening_balance_extra;
					$new_current_balance_cd_extra = $extra_ledger_current_cd;
					}
					else
					{
					$new_current_balance_extra = $opening_balance_extra-$extra_ledger_current_balance;
					$new_current_balance_cd_extra = $opening_cd_extra;
					}
				}
				
				setLedgerOpeningBalance($new_opening_balance_extra,$new_opening_cd_extra,$extra_ledger_id);
				setLedgerCurrentBalance($new_current_balance_extra,$new_current_balance_cd_extra,$extra_ledger_id);
			}
			
			if($customer_opening_cd==$old_vehicle_opening_cd)
			{
				if($customer_opening_balance>$old_vehicle_opening_balance)
				{
				$customer_opening_balance= $customer_opening_balance-$old_vehicle_opening_balance;
				$customer_opening_cd = $customer_opening_cd;
				}
				else
				{
				$customer_opening_balance= $old_vehicle_opening_balance-$customer_opening_balance;
				$customer_opening_cd = 1 - $old_vehicle_opening_cd;
				}
			}
			else
			{
				$customer_opening_balance = $customer_opening_balance + $old_vehicle_opening_balance;
			}		
			
			if($customer_current_balance_cd==$old_vehicle_opening_cd)
			{
				if($customer_current_balance>$old_vehicle_opening_balance)
				{
				$customer_current_balance= $customer_current_balance-$old_vehicle_opening_balance;
				$customer_current_cd = $customer_current_balance_cd;
				}
				else
				{
				$customer_current_balance= $old_vehicle_opening_balance-$customer_current_balance;
				$customer_current_cd = 1 - $old_vehicle_opening_cd;
				}
			}
			else
			{
				$customer_current_balance = $customer_current_balance + $old_vehicle_opening_balance;
			}			
			
			if($customer_opening_cd==$opening_cd)
			{
				$new_opening_balance = $customer_opening_balance + $opening_balance;
				$new_opening_cd = $opening_cd;
			}
			else
			{ 
				if($customer_opening_balance>$opening_balance)
				{
				$new_opening_balance = $customer_opening_balance - $opening_balance;
				$new_opening_cd = $customer_opening_cd;
				}
				else
				{
				$new_opening_balance = $opening_balance-$customer_opening_balance;
				$new_opening_cd = $opening_cd;
				}
			}
			
			if($customer_current_balance_cd==$opening_cd)
			{
				$new_current_balance = $customer_current_balance + $opening_balance;
				$new_current_balance_cd = $opening_cd;
				
			}
			else
			{ 
			
				if($customer_current_balance>$opening_balance)
				{
				$new_current_balance = $customer_current_balance - $opening_balance;
				$new_current_cd = $customer_current_balance_cd;
				}
				else
				{
				$new_current_balance = $opening_balance-$customer_current_balance;
				$new_current_cd = $opening_cd;
				}
			}
			setOpeningBalanceForCustomer($customer_id,$new_opening_balance,$new_opening_cd);
			setCurrentBalanceForCustomer($customer_id,$new_current_balance,$new_current_balance_cd);
			return "success";
		}
		else
		{return "error";}
	}
	catch(Exception $e)
	{
	}
	
}	

function getVehicleById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
			$sql="SELECT vehicle_id,edms_vehicle.model_id,model_name, vehicle_reg_no, vehicle_reg_date, vehicle_engine_no, vehicle_chasis_no, cng_cylinder_no, cng_kit_no, edms_vehicle.vehicle_color_id, vehicle_color, vehicle_model, vehicle_condition, edms_vehicle.godown_id, godown_name, is_purchased, is_sold_by_customer, basic_price, fitness_exp_date, permit_exp_date, battery_make_id, battery_no, key_no, service_book, battery_service_book_no, service_no, ledger_id, edms_vehicle.created_by, edms_vehicle.last_updated_by, edms_vehicle.date_added, edms_vehicle.date_modified, customer_id, opening_balance, opening_cd, opening_balance_extra, opening_cd_extra, extra_ledger_id FROM edms_vehicle 
			LEFT JOIN edms_vehicle_model ON edms_vehicle.model_id = edms_vehicle_model.model_id
			LEFT JOIN edms_vehicle_color ON edms_vehicle.vehicle_color_id = edms_vehicle_color.vehicle_color_id 
			LEFT JOIN edms_godown ON edms_vehicle.godown_id = edms_godown.godown_id WHERE vehicle_id = $id";
	$result=dbQuery($sql);
	$resultArray = dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0];
	else
	return false;	
		}
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateVehicle($chasis_no,$engine_no,$reg_no,$id=false)
{
	$sql="SELECT vehicle_id FROM edms_vehicle
	      WHERE is_sold_by_customer=0 AND ( 1!=1 ";
	if($reg_no!="NA" && $reg_no!="na")  
	$sql=$sql." OR vehicle_reg_no='$reg_no'";
	if($chasis_no!="NA" && $chasis_no!="na")
	$sql=$sql."	 
		 OR vehicle_chasis_no='$chasis_no'";
	if($engine_no!="NA" && $engine_no!="na")
	$sql=$sql."	 
		 OR vehicle_engine_no='$engine_no'";	 
	if($id==false)
		$sql=$sql." )";
		else
		$sql=$sql." ) AND vehicle_id!=$id";	
		
		
			  
		$result=dbQuery($sql);	
		
		if(dbNumRows($result)>0)
		{
			
			return true; //duplicate found
			} 
		else
		{
			return false;
			}	 	  
	}
function listVehicleProofTypes(){
	
	$sql="SELECT vehicle_document_type_id, vehicle_document_type
	      FROM edms_vehicle_document_type";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;	  
	}	

function addVehicleProof($vehicle_id,$vehicle_name,$human_proof_type_id_array,$proof_no_array,$proof_img_array,$scanImgArray)
{
	try
	{
		$vehicle_name=clean_data($vehicle_name);
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		
		if(is_array($human_proof_type_id_array)) // if more than one proof submitted
		{
			
			$len=count($human_proof_type_id_array);
			for($i=0;$i<$len;$i++)
			{
								
								$human_proof_type_id=$human_proof_type_id_array[$i];
								if($human_proof_type_id>0 && (checkForImagesInArray($proof_img_array['name'][$i]) || ($proof_no_array[$i]!=null && $proof_no_array[$i]!="")))
								{
								$proof_no=$proof_no_array[$i];
								$proof_no=clean_data($proof_no);
								$sql="INSERT INTO edms_vehicle_document
								     (vehicle_document_type_id, vehicle_document_no, vehicle_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ($human_proof_type_id, '$proof_no', $vehicle_id, $admin_id, $admin_id, NOW(), NOW() )";
								  
								  $result=dbQuery($sql);
								  $proof_id=dbInsertId();
								  
							    addImagesToVehicleProof($vehicle_id,$vehicle_name,$human_proof_type_id,$proof_id,$proof_img_array,$i);
								if($scanImgArray!=false && isset($scanImgArray[$i]) && is_array($scanImgArray[$i]))
								{
									
									foreach($scanImgArray[$i] as $scanImage)
									{
										
										
										insertImageToVehicleProof($scanImage,$proof_id);
										}
									}
									
								}
							   
				
			}
			
		}
		else // if only one proof submitted
		{
			if($human_proof_type_id_array>0 && (checkForImagesInArray($proof_img_array['name'][$i]) || ($proof_no_array[$i]!=null && $proof_no_array[$i]!="")))
								{
			$proof_no_array=clean_data($proof_no_array);						
			$sql="INSERT INTO edms_vehicle_proof
								     (vehicle_document_type_id, vehicle_document_no, vehicle_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ($human_proof_type_id_array, '$proof_no_array', $vehicle_id, $admin_id, $admin_id, NOW(), NOW() )";
								  
								  $result=dbQuery($sql);
								  $proof_id=dbInsertId();
								  addImagesToVehicleProof($vehicle_id,$vehicle_name,$human_proof_type_id_array,$proof_id,$proof_img_array,0);
								}
		}
	}
	catch(Exception $e)
	{
		
	}
	
}	

function addImagesToVehicleProof($vehicle_id,$vehicle_name,$human_proof_type_id,$proof_id,$proof_img_array,$i){
	
	if(is_array($proof_img_array['name'][$i])) // if proof has more than one image
								  {
									  $images_for_a_proof=count($proof_img_array['name'][$i]);
									  for($j=0;$j<$images_for_a_proof;$j++)
									  {
										  if($proof_img_array['name'][$i][$j]!="" &&  $proof_img_array['name'][$i][$j]!=null)
										  {
										   $imagee['name'] = $proof_img_array['name'][$i][$j];
										   $imagee['type'] = $proof_img_array['type'][$i][$j];
										   $imagee['tmp_name'] = $proof_img_array['tmp_name'][$i][$j];
										   $imagee['error'] = $proof_img_array['error'][$i][$j];
										   $imagee['size'] = $proof_img_array['size'][$i][$j];
										   
										   $imageName=addProofImageVehicle($vehicle_name,$vehicle_id,$human_proof_type_id,$imagee);
							   
							    			insertImageToVehicleProof($imageName,$proof_id);
										  }
										   
									  }
								  }
								  else // if proof has only one image
								  {
									  		if($proof_img_array['name'][$i]!="" &&  $proof_img_array['name'][$i]!=null)
										  {
									       $imagee['name'] = $proof_img_array['name'][$i];
										   $imagee['type'] = $proof_img_array['type'][$i];
										   $imagee['tmp_name'] = $proof_img_array['tmp_name'][$i];
										   $imagee['error'] = $proof_img_array['error'][$i];
										   $imagee['size'] = $proof_img_array['size'][$i];
										   
										   $imageName=addProofImageVehicle($vehicle_name,$vehicle_id,$human_proof_type_id,$imagee);
							   
							  				insertImageToVehicleProof($imageName,$proof_id);
										  }
									  
								  }
	
	}
	
function insertImageToVehicleProof($imageName,$proof_id)
{
	 $admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	$imageName=clean_data($imageName);
	if(validateForNull($imageName) && checkForNumeric($proof_id))
	{
	 $sql="INSERT INTO edms_vehicle_document_img
							   		 (vehicle_document_img_href, vehicle_document_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ('$imageName', $proof_id, $admin_id, $admin_id, NOW(), NOW())";
									 
									 dbQuery($sql);
	}
	
}	

function deleteVehicleProof($proof_id)
{
	if(checkForNumeric($proof_id))
	{
	$sql="DELETE FROM edms_vehicle_document
			WHERE vehicle_document_id=$proof_id";
	dbQuery($sql);
	return "success";	
	}
	else
	{
		return "error";
		}
	}

function deleteVehicleProofImage($proof_image_id)
{
	
	$sql="DELETE FROM edms_vehicle_document_img
		  WHERE vehicle_document_img_id=$proof_id";
	dbQuery($sql);	
	return "success";
}

function getRegNoFromVehicleID($id)
{
	$sql="SELECT vehicle_reg_no
	      FROM edms_vehicle
		  WHERE vehicle_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray[0][0];
		}	  
}





function getVehicleProofimgByProofId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT vehicle_document_img_id,vehicle_document_img_href FROM edms_vehicle_document_img WHERE vehicle_document_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
		
		
			return $resultArray;
			}	  
		else
		{
			return "error";
			}		  
		}
	
	}

function stripVehicleno($reg_no)
{
	$string=$reg_no;
preg_match('#[0-9]+$#', $string, $match);
$end_number=$match[0]; // Output: 8271
$pos = strrpos($string, $end_number);

    if($pos !== false)
    {
        $start_string = substr_replace($string, "", $pos, strlen($end_number));
    }


$new_number=$str = ltrim($end_number, '0');
$new_reg_no=$start_string.$new_number;
return $new_reg_no;
}

function getAvailabaleVehiclesForSale($edit_vehcile_delivery_challan_id=false)
{
	
	$sql="SELECT vehicle_id,model_id, vehicle_reg_no, vehicle_reg_date, vehicle_engine_no, vehicle_chasis_no, cng_cylinder_no, cng_kit_no, vehicle_color_id, vehicle_model, vehicle_condition, godown_id, is_purchased, is_sold_by_customer, basic_price, fitness_exp_date, permit_exp_date, battery_make_id, battery_no, key_no, service_book, created_by, last_updated_by, date_added, date_modified, customer_id FROM edms_vehicle WHERE (is_purchased=1 AND customer_id IS NULL) ";
	if(checkForNumeric($edit_vehcile_delivery_challan_id) && $edit_vehcile_delivery_challan_id>0)
	$sql=$sql." OR vehicle_id = $edit_vehcile_delivery_challan_id";
	$result=dbQuery($sql);
	$resultArray = dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return false;
}	

function getVehicleIDFromChasisNo($chasis_no)
{
	if(validateForNull($chasis_no))
	{
		
		$sql="SELECT vehicle_id FROM edms_vehicle WHERE vehicle_chasis_no='$chasis_no'";

		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);

		if(dbNumRows($result)>0)
		return $resultArray[0][0];
	}
	
}

function getVehicleIDFromEngineNo($engine_no)
{

	if(validateForNull($engine_no))
	{
		$sql="SELECT vehicle_id FROM edms_vehicle WHERE vehicle_engine_no='$engine_no'";
		$result = dbQuery($sql);

		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
	}
	
}

function getInStockVehicleIDFromChasisNo($chasis_no)
{
	if(validateForNull($chasis_no))
	{
		
		$sql="SELECT vehicle_id FROM edms_vehicle WHERE vehicle_chasis_no='$chasis_no' AND is_purchased = 1 ";

		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);

		if(dbNumRows($result)>0)
		return $resultArray[0][0];
	}
	
}

function getInStockVehicleIDFromEngineNo($engine_no)
{

	if(validateForNull($engine_no))
	{
		$sql="SELECT vehicle_id FROM edms_vehicle WHERE vehicle_engine_no='$engine_no' AND is_purchased = 1";
		$result = dbQuery($sql);

		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
	}
	
}

function getCustomerIDFromChasisNo($chasis_no)
{
	if(validateForNull($chasis_no))
	{
		
		$sql="SELECT customer_id FROM edms_vehicle WHERE vehicle_chasis_no='$chasis_no' AND customer_id IS NOT NULL";

		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);

		if(dbNumRows($result)==1)
		{
			
			return $resultArray[0][0];
		}
		else if(dbNumRows($result)>1)
		{
			return $resultArray;
		}
		else
		{
			return "error";
			}	
	}
	
}

function getCustomerIDFromEngineNo($engine_no)
{

	if(validateForNull($engine_no))
	{
		$sql="SELECT customer_id FROM edms_vehicle WHERE vehicle_engine_no='$engine_no' AND customer_id IS NOT NULL";
		$result = dbQuery($sql);

		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)==1)
		{
			
			return $resultArray[0][0];
		}
		else if(dbNumRows($result)>1)
		{
			return $resultArray;
		}
		else
		{
			return "error";
			}	
	}
	
}

function getVehicleIdFromRegNo($reg_no)
{
	$reg_no=clean_data($reg_no);
	$reg_no=stripVehicleno($reg_no);
	if(validateForNull($reg_no))
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
		$reg_no=clean_data($reg_no);
		$sql="SELECT edms_vehicle.vehicle_id
		      FROM edms_vehicle,edms_customer
			  WHERE vehicle_reg_no='$reg_no'
			  AND edms_customer.customer_id=edms_vehicle.customer_id
			  AND our_company_id=$oc_id";
			  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)==1)
		{
			
			return $resultArray[0][0];
		}
		else if(dbNumRows($result)>1)
		{
			return $resultArray;
		}
		else
		{
			return "error";
			}		  
		}
	}	

function getCustomerIdFromRegNo($reg_no)
{
	$reg_no=clean_data($reg_no);
	$reg_no=stripVehicleno($reg_no);
	if(validateForNull($reg_no))
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
		$reg_no=clean_data($reg_no);
		$sql="SELECT edms_customer.customer_id
		      FROM edms_vehicle,edms_customer
			  WHERE vehicle_reg_no='$reg_no'
			  AND edms_customer.customer_id=edms_vehicle.customer_id
			  AND our_company_id=$oc_id";
			  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)==1)
		{
			
			return $resultArray[0][0];
		}
		else if(dbNumRows($result)>1)
		{
			return $resultArray;
		}
		else
		{
			return "error";
			}		  
		}
	}			

function updateVehicleBasicPrice($vehicle_id,$basic_price)
{
	if(checkForNumeric($vehicle_id,$basic_price))
	{
		$sql="UPDATE edms_vehicle SET basic_price = $basic_price WHERE vehicle_id = $vehicle_id ";
		dbQuery($sql);
		return "success";	
	}
	return "error";
	
}

function checkIfVehicleInUse($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$jobcard = listJobCardsForVehicle($vehicle_id);
		if($jobcard)
		return true;
		
		$delivery_challan = getDeliveryChallanByVehicleId($vehicle_id);
		if($delivery_challan)
		return true;
		
		$delivery_challan = getVehicleInvoiceByVehicleId($vehicle_id);
		if($delivery_challan)
		return true;
		
		$delivery_challan = getSaleCertByVehicleId($vehicle_id);
		if($delivery_challan)
		return true;
		
		return false;
	}	
}

function getVehicleProofById($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT edms_vehicle_document.vehicle_document_id,edms_vehicle_document.vehicle_document_type_id,vehicle_document_type,vehicle_document_no
		      FROM edms_vehicle_document,edms_vehicle_document_type
			  WHERE vehicle_id=$vehicle_id
			  AND edms_vehicle_document.vehicle_document_type_id=edms_vehicle_document_type.vehicle_document_type_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		
		if(dbNumRows($result)>0)
		{
			
		
		
			return $resultArray;
			}	  
		else
		{
			return "error";
			}		  
			  
		
		}
	
	
	}

?>