<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listSuperCategories(){
	
	try
	{
		$sql="SELECT super_cat_id, super_cat_name
			  FROM ems_superCategory";
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



function insertSuperCategory($name){
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && !checkDuplicateSuperCategory($name))
		{
			$sql="INSERT INTO 
				ems_superCategory (super_cat_name)
				VALUES ('$name')";
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



function checkDuplicateSuperCategory($name,$id=false)
{
	if(validateForNull($name))
	{
		$sql="SELECT super_cat_id
			  FROM ems_superCategory
			  WHERE super_cat_name='$name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND super_cat_id!=$id";		  
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


function deleteSuperCategory($id){
	
	try
	{
		if(!checkifSuperCategoryInUse($id))
		{
		$sql="DELETE FROM ems_superCategory
		      WHERE super_cat_id=$id";
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

function checkifSuperCategoryInUse($id)
{
	
	if(checkForNumeric($id))
	{
	$sql="SELECT sub_cat_id
	      FROM ems_subCategory
		  Where super_cat_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
	
}		
	

function updateSuperCategory($id,$name){
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && checkForNumeric($id) && !checkDuplicateSuperCategory($name,$id))
		{
		$sql="UPDATE ems_superCategory
			  SET super_cat_name='$name'
			  WHERE super_cat_id=$id";
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


function getSuperCategoryById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT super_cat_id, super_cat_name
			  FROM ems_superCategory
			  WHERE super_cat_id=$id";
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