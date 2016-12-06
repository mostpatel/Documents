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
		$sql="SELECT sub_cat_id, sub_cat_name, sub_cat_img_path, youtube_link, sub_cat_description, cat_id
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



function insertSubCategory($name, $subCat_img, $spec_sheet, $youtube_link, $sub_cat_description, $sub_cat_other_details,$sub_cat_tabluar_details, $cat_id_array)
     {
		 
		try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		
		$sub_cat_other_details = mysql_real_escape_string($sub_cat_other_details);
		$sub_cat_tabluar_details = mysql_real_escape_string($sub_cat_tabluar_details);
		if(validateForNull($name) && !checkDuplicateSubCategory($name) && checkForNumeric($cat_id_array[0]))
		{
			
		$img_path = UploadImagee($subCat_img, SRV_ROOT.'images/category/', $max_width=2500 , $max_height=2500, $prefix=false);
		$spec_sheet_path = UploadImagee($spec_sheet, SRV_ROOT.'images/excel/', $max_width=2500 , $max_height=2500, $prefix=false);
		
		if(!validateForNull($img_path))
		{
		 	 $img_path ="NULL";
		}
		if(!validateForNull($spec_sheet_path))
		{
		 	 $spec_sheet_path ="NULL";
		}
		if(!validateForNull($youtube_link))
		{
		 	 $youtube_link ="NULL";
		}
		if(!validateForNull($sub_cat_description))
		{
		 	 $sub_cat_description ="NULL";
		}
			
			$sql="INSERT INTO 
				ems_subCategory (sub_cat_name, sub_cat_img_path, youtube_link, sub_cat_description, sub_cat_other_details, sub_cat_tabluar_details, cat_id)
				VALUES ('$name', '$img_path', '$youtube_link', '$sub_cat_description', '$sub_cat_other_details', '$sub_cat_tabluar_details', NULL)";
			
		$result=dbQuery($sql);
		
		$sub_cat_id=dbInsertId();
		updateCategoryToSubCategory($cat_id_array,$sub_cat_id);
		if(!validateForNull($spec_sheet_path))
		{
		 	 insertSpecDataFromExcel($spec_sheet_path, $sub_cat_id);
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



function checkDuplicateSubCategory($name,$id=false)
{
	return false;
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

function deleteImageOfASubCategory($id)
{
	
	try
	{
		
		$sql="UPDATE ems_subCategory
			  SET sub_cat_img_path = NULL
			  WHERE sub_cat_id=$id";
		dbQuery($sql);
		return "success";
		
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
	

function updateSubCategory($id, $name, $subCat_img, $spec_sheet, $youtube_link, $sub_cat_description, $sub_cat_other_details,$sub_cat_tabluar_details, $cat_id_array)
{
	
	
	try
	{
		$name=clean_data($name);
		$mrp=clean_data($mrp);
		$name = ucwords(strtolower($name));
		
		if(validateForNull($name) && checkForNumeric($id,$cat_id_array[0]) && !checkDuplicateSubCategory($name,$id))
		{
		
		if($subCat_img['size']==0)
		{
			$sql="UPDATE ems_subCategory
			  SET sub_cat_name='$name', youtube_link='$youtube_link', sub_cat_description='$sub_cat_description', sub_cat_other_details='$sub_cat_other_details', sub_cat_tabluar_details='$sub_cat_tabluar_details'
			  WHERE sub_cat_id=$id";
		
		}
		else
		{
			$img_path = UploadImagee($subCat_img, SRV_ROOT.'images/category/', $max_width=2500 , $max_height=2500, $prefix=false);
		
		    $sql="UPDATE ems_subCategory
			  SET sub_cat_name='$name', sub_cat_img_path='$img_path', youtube_link='$youtube_link', sub_cat_description='$sub_cat_description', sub_cat_other_details='$sub_cat_other_details', sub_cat_tabluar_details='$sub_cat_tabluar_details'
			  WHERE sub_cat_id=$id";
			  
			
			 
		}
		dbQuery($sql);
		updateCategoryToSubCategory($cat_id_array,$id);
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
		$sql="SELECT sub_cat_id, sub_cat_name, sub_cat_img_path, youtube_link, sub_cat_description, sub_cat_other_details, sub_cat_tabluar_details, cat_id
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

function getsubCategoryByCategoryId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT ems_subCategory.sub_cat_id, sub_cat_name, sub_cat_img_path, youtube_link, sub_cat_description, sub_cat_other_details, sub_cat_tabluar_details, ems_rel_subcat_Cat.cat_id
			  FROM ems_rel_subcat_Cat, ems_subCategory
			  WHERE ems_rel_subcat_Cat.cat_id=$id AND ems_rel_subcat_Cat.sub_cat_id = ems_subCategory.sub_cat_id";
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





function getCategoryBySubCategoryId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT ems_rel_subcat_Cat.cat_id, cat_name
			  FROM ems_rel_subcat_Cat, ems_category
			  WHERE sub_cat_id=$id AND ems_rel_subcat_Cat.cat_id = ems_category.cat_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		return $resultArray;
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}

function getCatIdsBySubCategoryId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT ems_rel_subcat_Cat.cat_id
			  FROM ems_rel_subcat_Cat
			  WHERE sub_cat_id=$id ";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		$return_array=array();
		foreach($resultArray as $re)
		{
			$return_array[] = $re['cat_id'];
		}	
		return $return_array;
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

function updateCategoryToSubCategory($cat_id_array,$sub_cat_id)
{
	
	if(checkForNumeric($sub_cat_id))
	{
		$sql="DELETE FROM ems_rel_subcat_Cat WHERE sub_cat_id = $sub_cat_id";
		dbQuery($sql);
		
		
		foreach($cat_id_array as $cat_id)
		{
			if(checkForNumeric($cat_id))
			{
				$sql="INSERT INTO ems_rel_subcat_Cat (sub_cat_id, cat_id) VALUES ($sub_cat_id, $cat_id)";
				dbQuery($sql);
			}
			
		}
		
	return "success";	
	}
	return "error";
	
}



	
?>