<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listCategories()
{
	
	try
	{
		$sql="SELECT cat_id, cat_name, cat_img_path, cat_description, ems_category.super_cat_id, super_cat_name
			  FROM ems_category, ems_superCategory WHERE ems_category.super_cat_id = ems_superCategory.super_cat_id ORDER BY cat_name";
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



function insertCategory($name, $cat_img, $cat_description, $super_cat_id)
{
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		$cat_description=clean_data($cat_description);
		
		if(validateForNull($name) && !checkDuplicateCategory($name))
		{
			$img_path = UploadImagee($cat_img, SRV_ROOT.'images/category/', $max_width=2500 , $max_height=2500, $prefix=false);
			
			$sql="INSERT INTO 
				ems_category (cat_name, cat_img_path, cat_description, super_cat_id)
				VALUES ('$name', '$img_path', '$cat_description', $super_cat_id)";
			
			
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
	

function updateCategory($id,$name, $cat_img, $cat_description, $super_cat_id){
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		
		if(validateForNull($name) && checkForNumeric($id,$super_cat_id) && !checkDuplicateCategory($name,$id))
		{
			
		$sql="UPDATE ems_category
			  SET cat_name='$name', super_cat_id = $super_cat_id
			  WHERE cat_id=$id";
			 		  
		dbQuery($sql);
		if($cat_img['size']!=0)
		{
		$img_path = UploadImagee($cat_img, SRV_ROOT.'images/category/', $max_width=2500 , $max_height=2500, $prefix=false);
			$sql="UPDATE ems_category
			  SET cat_img_path='$img_path'
			  WHERE cat_id=$id";
			 		  
		dbQuery($sql);
		}
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
		$sql="SELECT cat_id, cat_name, cat_img_path, cat_description, super_cat_id
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