<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listCategories()
{
	
	try
	{
		$sql="SELECT cat_id, cat_name
			  FROM ems_category";
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
	
function getSubCategoryByCategory($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT sub_cat_id, sub_cat_name, cat_id
			  FROM ems_subCategory
			  WHERE cat_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
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



function insertCategory($name){
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && !checkDuplicateCategory($name))
		{
			$sql="INSERT INTO 
				ems_category (cat_name)
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



function checkDuplicateCategory($name,$id=false)
{
	if(validateForNull($name))
	{
		$sql="SELECT cat_id
			  FROM ems_category
			  WHERE cat_name='$name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND cat_id!=$id";		  
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


function deleteCategory($id){
	
	try
	{
		if(!checkifCategoryInUse($id))
		{
		$sql="DELETE FROM ems_category
		      WHERE cat_id=$id";
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

function checkifCategoryInUse($id)
{
	
	if(checkForNumeric($id))
	{
	$sql="SELECT sub_cat_id
	      FROM ems_subCategory
		  Where cat_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
	
}		
	

function updateCategory($id,$name){
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && checkForNumeric($id) && !checkDuplicateCategory($name,$id))
		{
		$sql="UPDATE ems_category
			  SET cat_name='$name'
			  WHERE cat_id=$id";
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



function getCategoryById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT cat_id, cat_name
			  FROM ems_category
			  WHERE cat_id=$id";
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

function getCategoryNameById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT cat_id, cat_name
			  FROM ems_category
			  WHERE cat_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}


function getCategoryBySuperCategory($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT cat_id, cat_name
			  FROM ems_category
			  WHERE cat_id=$id";
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