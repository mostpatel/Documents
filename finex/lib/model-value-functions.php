<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("model-value-functions.php");
require_once("vehicle-model-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listModelValues(){
	
	try
	{
		$sql="SELECT model_value_id,fin_model_value.model_id,value,from_year,dep_percent,created_by,last_updated_by,date_added,date_modified, model_name, company_name
			  FROM fin_model_value INNER JOIN fin_vehicle_model ON fin_vehicle_model.model_id = fin_model_value.model_id INNER JOIN fin_vehicle_company
			  ON fin_vehicle_model.vehicle_company_id = fin_vehicle_company.vehicle_company_id ORDER BY date_added";
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



function insertVehicleModelValue($model_id,$value,$from_year,$dep_percent,$roi_array,$duration_array){
	try
	{
		$value=clean_data($value);
		$from_year=clean_data($from_year);
		$dep_percent=clean_data($dep_percent);
		
		if(checkForNumeric($model_id,$from_year,$value,$dep_percent) && !checkDuplicateVehicleModelValue($model_id) && !checkRoiDurationArrays($roi_array,$duration_array))
		{
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$sql="INSERT INTO 
				fin_model_value(model_id,value,from_year,dep_percent,created_by,last_updated_by,date_added,date_modified)
				VALUES ($model_id,$value,$from_year,$dep_percent,$admin_id,$admin_id,NOW(),NOW())";
		$result=dbQuery($sql);
		$model_value_id=dbInsertId();
		insertRelArrayModelLoanSlab($model_value_id,$roi_array,$duration_array);
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

function deleteVehicleModelValue($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="DELETE FROM fin_model_value
		      WHERE model_value_id=$id";
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

function updateVehicleModelValue($id,$model_id,$value,$from_year,$dep_percent,$roi_array,$duration_array){
	
	try
	{
		$value=clean_data($value);
		$from_year=clean_data($from_year);
		$dep_percent=clean_data($dep_percent);
	
		if(checkForNumeric($model_id,$from_year,$value,$dep_percent) && !checkDuplicateVehicleModelValue($model_id,$id))
		{
			$admin_id=$_SESSION['adminSession']['admin_id'];
		$sql="UPDATE fin_model_value
			  SET `model_id`=$model_id,`value`=$value,`from_year`=$from_year,`dep_percent`=$dep_percent,`last_updated_by`=$admin_id,`date_modified`=NOW()
			  WHERE model_value_id=$id";
		dbQuery($sql);
		deleteRelModelLoanSlabForModelValueId($id);
		insertRelArrayModelLoanSlab($id,$roi_array,$duration_array);
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

function getModelValueById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT model_value_id,fin_model_value.model_id,value,from_year,dep_percent,created_by,last_updated_by,date_added,date_modified, model_name, company_name
			  FROM fin_model_value INNER JOIN fin_vehicle_model ON fin_vehicle_model.model_id = fin_model_value.model_id INNER JOIN fin_vehicle_company
			  ON fin_vehicle_model.vehicle_company_id = fin_vehicle_company.vehicle_company_id 
			  WHERE model_value_id=$id";
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

function getModelValueByModelId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT *
			  FROM fin_model_value
			  WHERE model_id=$id";
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

function checkDuplicateVehicleModelValue($model_id,$id=false)
{
	if(checkForNumeric($model_id))
	{
		$sql="SELECT model_id
			  FROM fin_model_value
			  WHERE model_id='$model_id'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND model_value_id!=$id";		  
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

function listAllLoanPercentSlabs()
{
	$sql="SELECT * FROM fin_loan_percent_slabs ORDER BY to_percent";
	$result = dbQuery($sql);
	$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray; //duplicate found
			} 
	
}

function checkRoiDurationArrays($roi_array,$duration_array)
{
	$array_error = false;
	if(is_array($roi_array) && is_array($duration_array))
	{
		foreach($roi_array as $roi)
		if(!checkForNumeric($roi))
		$array_error = true;
		
		foreach($duration_array as $roi)
		if(!checkForNumeric($roi))
		$array_error = true;
	}
	return $array_error;
}
function insertRelArrayModelLoanSlab($model_value_id,$roi_array,$duration_array)
{
	if(checkForNumeric($model_value_id) && !checkRoiDurationArrays($roi_array,$duration_array))
	{
		foreach($roi_array as $slab_id => $roi)
		insertRelModelLoanSlab($model_value_id,$slab_id,$roi,$duration_array[$slab_id]);
	}
}

function insertRelModelLoanSlab($model_value_id,$slab_id,$roi,$duration)
{
	if(checkForNumeric($model_value_id,$slab_id,$roi,$duration))
	{
		$sql="INSERT INTO fin_rel_model_loan_slabs(model_value_id,slab_id,min_roi,max_duration) VALUES ($model_value_id,$slab_id,$roi,$duration)";
		$result = dbQuery($sql);
	}
	else
	return "error";
}

function deleteRelModelLoanSlabForModelValueId($model_value_id)
{
	if(checkForNumeric($model_value_id))
	{
		$sql="DELETE FROM fin_rel_model_loan_slabs WHERE model_value_id = $model_value_id";
		dbQuery($sql);
	}
}

function getRelModelLoanSlabForModelValueId($model_value_id)
{
	if(checkForNumeric($model_value_id))
	{
		$sql="SELECT `model_loan_slab_id`, `model_value_id`, fin_loan_percent_slabs.`slab_id`, `min_roi`, `max_duration`, from_percent, to_percent FROM `fin_rel_model_loan_slabs`  INNER JOIN fin_loan_percent_slabs ON fin_loan_percent_slabs.slab_id = fin_rel_model_loan_slabs.slab_id WHERE model_value_id = $model_value_id";
		$result = dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray; //duplicate found
		} 
	}
}

function getDepreciationChartForModelValueId($model_value_id,$no_of_years)
{
	if(checkForNumeric($model_value_id))
	{
		$model_value = getModelValueById($model_value_id);
		$value = $model_value['value'];
		$dep_percent = $model_value['dep_percent'];
		$from_year = $model_value['from_year'];
		$dep_array=array();
		for($i=1;$i<=$no_of_years;$i++)
		{
			$dep_value = ($value*(1-($dep_percent/100)));
			$dep_array[$from_year] = $dep_value;
			$value = $dep_value;
			$from_year--;
		}
		
		return $dep_array;
	}
}
?>