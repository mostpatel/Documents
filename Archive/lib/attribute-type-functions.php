<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");


	
function listAttributesTypes()
{
	
	try
	{
		$sql="SELECT attribute_type_id, attribute_type
			  FROM ems_attribute_type";
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



function insertAttributeType($type, $super_cat_id_array, $cat_id_array, $sub_cat_id_array)
{
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(validateForNull($type) && !checkDuplicateAttributeType($type))
		{
		
		$sql="INSERT INTO 
				ems_attribute_type (attribute_type)
				VALUES ('$type')";
		$result=dbQuery($sql);
		$attr_type_id = dbInsertId($sql);
		
		
		if(!empty($super_cat_id_array))
		{
		foreach($super_cat_id_array as $super_cat_id)
		{
		$sql="INSERT INTO 
				ems_rel_attr_type_sup_cat (attribute_type_id, super_cat_id)
				VALUES ($attr_type_id, $super_cat_id)";
		$result=dbQuery($sql);
		}
		}
		
		if(!empty($cat_id_array))
		{
		foreach($cat_id_array as $cat_id)
		{
		$sql="INSERT INTO 
				ems_rel_attr_type_cat (attribute_type_id, cat_id)
				VALUES ($attr_type_id, $cat_id)";
		$result=dbQuery($sql);
		}
		}
		
		if(!empty($sub_cat_id_array))
		{
		foreach($sub_cat_id_array as $sub_cat_id)
		{
		$sql="INSERT INTO 
				ems_rel_attr_type_sub_cat (attribute_type_id, sub_cat_id)
				VALUES ($attr_type_id, $sub_cat_id)";
		$result=dbQuery($sql);
		}
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

function updateRelatedCatIdsForAttributeTypeId($attr_type_id, $super_cat_id_array, $cat_id_array, $sub_cat_id_array)
{
	try
	{
		
		
		if(!empty($super_cat_id_array))
		{
		foreach($super_cat_id_array as $super_cat_id)
		{
		$sql="INSERT INTO 
				ems_rel_attr_type_sup_cat (attribute_type_id, super_cat_id)
				VALUES ($attr_type_id, $super_cat_id)";
		$result=dbQuery($sql);
		}
		}
		
		if(!empty($cat_id_array))
		{
		foreach($cat_id_array as $cat_id)
		{
		$sql="INSERT INTO 
				ems_rel_attr_type_cat (attribute_type_id, cat_id)
				VALUES ($attr_type_id, $cat_id)";
		$result=dbQuery($sql);
		}
		}
		
		if(!empty($sub_cat_id_array))
		{
		foreach($sub_cat_id_array as $sub_cat_id)
		{
		$sql="INSERT INTO 
				ems_rel_attr_type_sub_cat (attribute_type_id, sub_cat_id)
				VALUES ($attr_type_id, $sub_cat_id)";
		$result=dbQuery($sql);
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



function checkDuplicateAttributeType($type,$id=false)
{
	if(validateForNull($type))
	{
		$sql="SELECT attribute_type_id
			  FROM ems_attribute_type
			  WHERE attribute_type='$type'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND attribute_type_id!=$id";		  
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


function deleteAttributeType($id)
{
	
	try
	{
		if(!checkifAttributeTypeInUse($id))
		{
		$sql="DELETE FROM ems_attribute_type
		      WHERE attribute_type_id=$id";
		
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

function checkifAttributeTypeInUse($id)

{ 
	return false;
}
	
		
	

function updateAttributeType($id,$type)
{
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(validateForNull($type) && checkForNumeric($id) && !checkDuplicateAttributeType($type,$id))
		{
		$sql="UPDATE ems_attribute_type
			  SET attribute_type='$type'
			  WHERE attribute_type_id=$id";
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

function updateAttributeTypeWithResprctiveCategories($id, $type, $super_cat_id_array, $cat_id_array, $sub_cat_id_array)
{
	
	if(checkForNumeric($id))
	{
	  
	  deleteSuperCatIdsForAttributeTypeId($id);
	  deleteCatIdsForAttributeTypeId($id);
	  deleteSubCatIdsForAttributeTypeId($id);
	  
	  updateAttributeType($id, $type);
	  updateRelatedCatIdsForAttributeTypeId($id, $super_cat_id_array, $cat_id_array, $sub_cat_id_array);
	
	
	return "success";
	}
	return "error";
}	


function getAttributeTypeById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT attribute_type_id, attribute_type, single_multiple
			  FROM ems_attribute_type
			  WHERE attribute_type_id=$id";
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


function getSubCatFromAttributeTypeId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT sub_cat_id
			  FROM ems_rel_attr_type_sub_cat
			  WHERE attribute_type_id = $id";
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


?>