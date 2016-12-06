<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("category-functions.php");
require_once("super-category-functions.php");
require_once("common.php");
require_once("bd.php");



function listSubCategories()
{
	
	try
	{
		$sql="SELECT sub_cat_id, sub_cat_name, subCategory_price, ems_subCategory.cat_id, ems_subCategory.super_cat_id
			  FROM ems_subCategory";
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



function insertSubCategory($name, $mrp, $super_cat_id, $cat_id)
{
	try
	{
		$name=clean_data($name);
		$mrp=clean_data($mrp);
		$name = ucwords(strtolower($name));
		
		if($super_cat_id==-1)
		{
		$super_cat_id="NULL";
		}
		if($cat_id==-1)
		{
		$cat_id="NULL";
		}
		if(!validateForNull($mrp))
		{
			$mrp=0;
		}
		
		
		
		if(validateForNull($name) && !checkDuplicateSubCategory($name))
		{
			$sql="INSERT INTO 
				ems_subCategory (sub_cat_name, subCategory_price, super_cat_id, cat_id)
				VALUES ('$name', $mrp, $super_cat_id, $cat_id)";
	       	
			
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



function checkDuplicateSubCategory($name,$id=false)
{
	if(validateForNull($name))
	{
		$sql="SELECT sub_cat_id
			  FROM ems_subCategory
			  WHERE sub_cat_name='$name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND sub_cat_id!=$id";		  
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


function deleteSubCategory($id){
	
	try
	{
		if(!checkifSubCategoryInUse($id))
		{
		$sql="DELETE FROM ems_subCategory
		      WHERE sub_cat_id=$id";
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



function checkifSubCategoryInUse($id)
{
	
	if(checkForNumeric($id))
	{
	$sql="SELECT rel_subCat_enquiry_form_id
	      FROM ems_rel_subCategory_enquiry_form
		  Where sub_cat_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
	
}			
	

function updateSubCategory($id, $name, $mrp, $super_cat_id, $cat_id){
	
	try
	{
		$name=clean_data($name);
		$mrp=clean_data($mrp);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && checkForNumeric($id, $super_cat_id, $cat_id, $mrp) && !checkDuplicateSubCategory($name,$id))
		{
		$sql="UPDATE ems_subCategory
			  SET sub_cat_name='$name', subCategory_price='$mrp', ems_subCategory.super_cat_id=$super_cat_id, ems_subCategory.cat_id=$cat_id
			  WHERE sub_cat_id=$id";
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



function getsubCategoryById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT sub_cat_id, sub_cat_name, subCategory_price, ems_subCategory.cat_id, ems_subCategory.super_cat_id
			  FROM ems_subCategory
			  WHERE sub_cat_id=$id";
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





function getCategoryBySubCategoryId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT ems_subCategory.cat_id
			  FROM ems_subCategory
			  WHERE sub_cat_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		 $catId= $resultArray[0]['cat_id'];
		 if(validateForNull($catId))
		 {
		 $finalResult = getCategoryById($catId);
		 return $finalResult;
		 }
		 else
		 return false;
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}


function getSuperCategoryBySubCategoryId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT ems_subCategory.super_cat_id
			  FROM ems_subCategory
			  WHERE sub_cat_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		 $superCatId= $resultArray[0]['super_cat_id'];
		 if(validateForNull($superCatId))
		 {
		 $finalResult = getSuperCategoryById($superCatId);
		 return $finalResult;
		 }
		 else
		 return false;
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}


function getCategoryIdBySubCategoryId($id)
{
	try
	{
		if(checkForNumeric($id))
		{
			$data = getCategoryBySubCategoryId($id);
			
			$catId = $data['cat_id'];
			
			return $catId;
	    }
		 else
		 return false;
		
	}
	catch(Exception $e)
	{
	}
	
}



function getSuperCategoryIdBySubCategoryId($id)
{
	try
	{
		if(checkForNumeric($id))
		{
			$data = getSuperCategoryBySubCategoryId($id);
			$superCatId = $data[0]['super_cat_id'];
			
			return $superCatId;
	    }
		 else
		 return false;
		
	}
	catch(Exception $e)
	{
	}
	
}

	
?>