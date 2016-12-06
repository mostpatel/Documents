<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listUnits(){
	
	try
	{
		$sql="SELECT unit_id, unit_name
			  FROM ems_product_unit";
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



function insertProductUnit($unit){
	try
	{
		$unit=clean_data($unit);
		$unit = ucwords(strtolower($unit));
		if(validateForNull($unit) && !checkDuplicateUnit($unit))
		{
			$sql="INSERT INTO 
				ems_product_unit (unit_name)
				VALUES ('$unit')";

		$result=dbQuery($sql);
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



function checkDuplicateUnit($unit,$id=false)
{
	if(validateForNull($unit))
	{
		$sql="SELECT unit_id
			  FROM ems_product_unit

			  WHERE unit_name='$unit'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND unit_id!=$id";		  
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


function deleteUnit($id){
	
	try
	{
		if(!checkifunitInUse($id))
		{
		$sql="DELETE FROM ems_product_unit

		      WHERE unit_id=$id";
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



function checkifunitInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT rel_subCat_enquiry_form_id
	      FROM ems_rel_subCategory_enquiry_form
		  Where unit_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
}		
	

function updateunit($id,$unit){
	
	try
	{
		$unit=clean_data($unit);
		$unit = ucwords(strtolower($unit));
		if(validateForNull($unit) && checkForNumeric($id) && !checkDuplicateunit($unit,$id))
		{
		$sql="UPDATE ems_product_unit

			  SET unit='$unit'
			  WHERE unit_id=$id";
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


function getUnitById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT unit_id, unit_name
			  FROM ems_product_unit

			  WHERE unit_id=$id";
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


?>