<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
require_once("fuel-type-functions.php");
require_once("vehicle-purchase-functions.php");	
require_once("vehicle-functions.php");		
require_once("account-purchase-functions.php");
require_once("account-ledger-functions.php");
require_once("delivery-challan-functions.php");

function listVehicleModels(){
	
	try
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
		$sql="SELECT model_id, model_name, edms_vehicle_model.vehicle_company_id, company_name , cubic_capacity,fuel_type_id,no_of_cylinders,seating_capacity,unladen_weight,gross_weight,axle_wt_fr,axle_wt_rr,no_tyres_fr,no_tyres_rr,tyre_type_fr,tyre_type_rr,edms_vehicle_type.vehicle_type_id,vehicle_type,wheelbase,mrp
			  FROM edms_vehicle_model,edms_vehicle_company , edms_vehicle_type
			  WHERE edms_vehicle_model.vehicle_company_id = edms_vehicle_company.vehicle_company_id
			   AND edms_vehicle_type.vehicle_type_id = edms_vehicle_model.vehicle_type_id
			   AND our_company_id = $oc_id
			  ORDER BY model_name";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;	  
	}
	catch(Exception $e)
	{
	}
	
}	


function insertVehicleModel($name,$vehicle_company_id,$cubic_capacity,$fuel_type_id,$no_of_cylinders,$seating_capacity,$unladen_weight,$gross_wt,$axle_wt_fr,$axle_wt_rr,$no_tyres_fr,$no_tyres_rr,$tyre_type_fr,$tyre_type_rr,$vehicle_type_id,$wheelbase,$mrp){
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		$duplicate=checkDuplicateVehicleModel($name,$vehicle_company_id);
		if(validateForNull($name,$tyre_type_fr,$tyre_type_rr,$seating_capacity) && checkForNumeric($vehicle_company_id,$cubic_capacity,$fuel_type_id,$no_of_cylinders,$unladen_weight,$gross_wt,$axle_wt_fr,$axle_wt_rr,$no_tyres_fr,$no_tyres_rr,$vehicle_type_id,$wheelbase,$mrp) && !$duplicate)
		{
			$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
			$sql="INSERT INTO 
				edms_vehicle_model(model_name, vehicle_company_id,cubic_capacity,fuel_type_id,no_of_cylinders,seating_capacity,unladen_weight,gross_weight,axle_wt_fr,axle_wt_rr,no_tyres_fr,no_tyres_rr,tyre_type_fr,tyre_type_rr,vehicle_type_id,wheelbase,mrp, our_company_id)
				VALUES ('$name',$vehicle_company_id , $cubic_capacity,$fuel_type_id,$no_of_cylinders,'$seating_capacity',$unladen_weight,$gross_wt,$axle_wt_fr,$axle_wt_rr,$no_tyres_fr,$no_tyres_rr,'$tyre_type_fr','$tyre_type_rr',$vehicle_type_id,$wheelbase,$mrp,$oc_id)";
		$result=dbQuery($sql);
		return dbInsertId();
		}
		else if($duplicate!==false)
		{
			return $duplicate;
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

function deleteVehicleModel($id){
	
	try
	{
		if(!checkifVehicleModelInUse($id))
		{
		$sql="DELETE FROM edms_vehicle_model
		      WHERE model_id=$id";
		dbQuery($sql);
		return "success";
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

function updateVehicleModel($id,$name,$vehicle_company_id,$cubic_capacity,$fuel_type_id,$no_of_cylinders,$seating_capacity,$unladen_weight,$gross_wt,$axle_wt_fr,$axle_wt_rr,$no_tyres_fr,$no_tyres_rr,$tyre_type_fr,$tyre_type_rr,$vehicle_type_id,$wheelbase,$mrp){
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		$duplicate=checkDuplicateVehicleModel($name,$vehicle_company_id,$id);
		if(validateForNull($name,$tyre_type_fr,$tyre_type_rr,$seating_capacity) && checkForNumeric($vehicle_company_id,$cubic_capacity,$fuel_type_id,$no_of_cylinders,$unladen_weight,$gross_wt,$axle_wt_fr,$axle_wt_rr,$no_tyres_fr,$no_tyres_rr,$vehicle_type_id,$wheelbase,$mrp) && !$duplicate)
		{
		$sql="UPDATE edms_vehicle_model
			  SET model_name='$name', vehicle_company_id=$vehicle_company_id , cubic_capacity = $cubic_capacity,fuel_type_id = $fuel_type_id,no_of_cylinders = $no_of_cylinders ,seating_capacity = '$seating_capacity',unladen_weight = $unladen_weight,gross_weight = $gross_wt,axle_wt_fr = $axle_wt_fr,axle_wt_rr = $axle_wt_rr,no_tyres_fr = $no_tyres_fr,no_tyres_rr = $no_tyres_rr,tyre_type_fr = '$tyre_type_fr',tyre_type_rr = '$tyre_type_rr',vehicle_type_id = $vehicle_type_id,wheelbase = $wheelbase,mrp = $mrp
			  WHERE model_id=$id";
		dbQuery($sql);
		return "success";
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

function getVehicleModelById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT model_id, model_name, edms_vehicle_model.vehicle_company_id, company_name , cubic_capacity,fuel_type_id,no_of_cylinders,seating_capacity,unladen_weight,gross_weight,axle_wt_fr,axle_wt_rr,no_tyres_fr,no_tyres_rr,tyre_type_fr,tyre_type_rr,edms_vehicle_type.vehicle_type_id,vehicle_type,wheelbase,mrp, our_company_id
			  FROM edms_vehicle_model,edms_vehicle_company, edms_vehicle_type
			  WHERE edms_vehicle_model.vehicle_company_id = edms_vehicle_company.vehicle_company_id
			  AND edms_vehicle_type.vehicle_type_id = edms_vehicle_model.vehicle_type_id
			  AND model_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
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

function getModelNameById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT  model_name
			  FROM edms_vehicle_model
			  WHERE model_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}

function checkDuplicateVehicleModel($name,$vehicle_company_id,$id=false)
{
	
	if(validateForNull($name))
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
		$sql="SELECT model_id
			  FROM edms_vehicle_model
			  WHERE our_company_id = $oc_id 
			  AND model_name='$name'
			  AND vehicle_company_id=$vehicle_company_id";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND model_id!=$id";		  
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
			} 
		else
		{
			return false;
			}
	}
}	

function checkifVehicleModelInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT model_id
	      FROM edms_vehicle
		  Where model_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
}	

function getModelsFromCompanyID($id)
{
	
	if(checkForNumeric($id))
		{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];	
		$sql="SELECT model_id, model_name
			  FROM edms_vehicle_model
			  WHERE vehicle_company_id=$id
			  AND our_company_id = $oc_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		}
}

function addVehicleToModelOpening($vehicle_id,$model_id)
{
	if(checkForNumeric($model_id,$vehicle_id))
	{
		$sql="INSERT INTO edms_vehicle_model_opening_vehicle (model_id,vehicle_id) VALUES ($model_id,$vehicle_id)";
		dbQuery($sql);
		return true;
	}
	return false;	
	
}

function deleteVehicleToModelOpening($vehicle_id)
{
	
	if(checkForNumeric($vehicle_id))
	{
		$sql="DELETE FROM edms_vehicle_model_opening_vehicle WHERE vehicle_id = $vehicle_id";
		dbQuery($sql);
		return true;
	}	
	return false;
}

function GetOpeningBalanceAndQuantityForModel($model_id)
{
	if(checkForNumeric($model_id))
	{
		$sql="SELECT COUNT(edms_vehicle_model_opening_vehicle.vehicle_id) as opening_quantity, SUM(basic_price) as opening_balance , SUM(basic_price)/COUNT(edms_vehicle_model_opening_vehicle.vehicle_id) as opening_rate FROM edms_vehicle_model_opening_vehicle, edms_vehicle WHERE edms_vehicle_model_opening_vehicle.vehicle_id = edms_vehicle.vehicle_id AND edms_vehicle.model_id = $model_id GROUP BY edms_vehicle.model_id";
		
		$result=dbQuery($sql);
		$resultArray = dbResultToArray($result);
		return $resultArray[0];	
	}
	return false;
}

function getOpeningVehiclesForModel($model_id)
{
	if(checkForNumeric($model_id))
	{
		$sql="SELECT vehicle_id FROM edms_vehicle_model_opening_vehicle WHERE model_id = $model_id";
		$result = dbQuery($sql);	
		$resultArray = dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
	}	
	
}

function AddOpeningBalanceToModel($model_id_array,$vehicle_engine_no_array,$vehicle_chasis_no_array,$vehicle_color_id_array,$model_year_array,$basic_price_array,$cng_cylinder_no_array,$cng_kit_no_array,$godown_id_array)
{
	
	$total_amount = checkForVehiclesInArray($model_id_array,$vehicle_engine_no_array,$vehicle_chasis_no_array,$vehicle_color_id_array,$model_year_array,$basic_price_array,$godown_id_array);
	
	

	if($total_amount && checkForNumeric($total_amount) && $total_amount>=0)
	{
		$tot_purchase_amount = 0;
		$vehicle_id_array = array();
		
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
			
			$godown_id=$godown_id_array[$i];
			
			
	
			if(checkForNumeric($model_id,$vehicle_color_id,$model_year,$basic_price,$godown_id) && validateForNull($vehicle_chasis_no,$vehicle_engine_no))
			{
				
				
				$vehicle_id=insertVehicle($model_id,$vehicle_engine_no,$vehicle_chasis_no,$vehicle_color_id,$model_year,1,$basic_price,array(),array(),array(),$godown_id,NULL,'NA','1970-01-01',$cng_cylinder_no,$cng_kit_no,1);
				$vehicle_added = addVehicleToModelOpening($vehicle_id,$model_id);
				
			}
		}
	
	return "success";	
	}

return "error";	
}	


function updateOpeningBalanceToModel($model_id_array,$vehicle_engine_no_array,$vehicle_chasis_no_array,$vehicle_color_id_array,$model_year_array,$basic_price_array,$cng_cylinder_no_array,$cng_kit_no_array,$godown_id_array,$vehicle_id_array,$condition_array,$vehicle_reg_no_array,$service_book_no_array)
{
	
	
	$total_amount = checkForVehiclesInArray($model_id_array,$vehicle_engine_no_array,$vehicle_chasis_no_array,$vehicle_color_id_array,$model_year_array,$basic_price_array,$godown_id_array,$service_book_no_array,$condition_array,$vehicle_reg_no_array);
	
	
	
	if($total_amount && checkForNumeric($total_amount) && $total_amount>=0)
	{
		$tot_purchase_amount = 0;
		
		
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
			$godown_id=$godown_id_array[$i];
			$reg_no=$vehicle_reg_no_array[$i];
			$condition=$condition_array[$i];
			$service_book=$service_book_no_array[$i];
			
			
			if(checkForNumeric($model_id,$vehicle_color_id,$model_year,$basic_price,$godown_id) && validateForNull($vehicle_chasis_no,$vehicle_engine_no,$service_book)  && ($condition==1 || validateForNull($reg_no)))
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
					echo $model_id;
					updateVehicle($vehicle_id,$model_id,$vehicle_engine_no,$vehicle_chasis_no,$vehicle_color_id,$model_year,$condition,$basic_price,array(),array(),array(),$godown_id,NULL,$reg_no,'1970-01-01',$cng_cylinder_no,$cng_kit_no,$vehicle['is_purchased'],$vehicle['is_sold_by_customer'],$battery_make_id,$vehicle['battery_no'],$vehicle['key_no'],$service_book);
					$vehicle_added = true;
				}
				else
				{
				$vehicle_id=insertVehicle($model_id,$vehicle_engine_no,$vehicle_chasis_no,$vehicle_color_id,$model_year,1,$basic_price,array(),array(),array(),$godown_id,NULL,'NA','1970-01-01',$cng_cylinder_no,$cng_kit_no,1,0,NULL,"NA","NA",$service_book);
				$vehicle_added = addVehicleToModelOpening($vehicle_id,$model_id);
				}
			}
		}

	return "success";	
	}

return "error";	
}	

function deleteVehicleFromOpeningVehicles($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		
	$delivery_challan = getDeliveryChallanByVehicleId($vehicle_id);
	
	if($delivery_challan)	
	return false;
	else
	{
		deleteVehicleToModelOpening($vehicle_id);
		deleteVehicle($vehicle_id);
		return true;
	}	
	}
	return false;
}

?>