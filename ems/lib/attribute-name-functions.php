<?php 
require_once("cg.php");
require_once("attribute-type-functions.php");
require_once("common.php");
require_once("bd.php");



function listAttributeNames()
{
	
	try
	{
		$sql="SELECT attribute_name_id, attribute_name, attribute_type_id
			  FROM ems_attribute_name";
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



function insertAttributeName($name, $attribute_type_id, $super_cat_id_array, $cat_id_array, $sub_cat_id_array)
{
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		
		
		
		
		
		if(validateForNull($name) && !checkDuplicateAttributeName($name, $attribute_type_id))
		{
			$sql="INSERT INTO 
				ems_attribute_name (attribute_name, attribute_type_id)
				VALUES ('$name', $attribute_type_id)";
		   
			
		$result=dbQuery($sql);
		$attr_name_id = dbInsertId($sql);
		
		
		
		
		if(!empty($super_cat_id_array))
		{
		
				foreach($super_cat_id_array as $super_cat_id)
		{
		$sql="INSERT INTO 
				ems_rel_attr_name_sup_cat (attribute_name_id, super_cat_id, attribute_type_id)
				VALUES ($attr_name_id, $super_cat_id, $attribute_type_id)";
		
		$result=dbQuery($sql);
		}
		}
		
		if(!empty($cat_id_array))
		{
		foreach($cat_id_array as $cat_id)
		{
		$sql="INSERT INTO 
				ems_rel_attr_name_cat (attribute_name_id, cat_id, attribute_type_id)
				VALUES ($attr_name_id, $cat_id, $attribute_type_id)";
		$result=dbQuery($sql);
		}
		}
		
		if(!empty($sub_cat_id_array))
		{
		foreach($sub_cat_id_array as $sub_cat_id)
		{
		$sql="INSERT INTO 
				ems_rel_attr_name_sub_cat (attribute_name_id, sub_cat_id, attribute_type_id)
				VALUES ($attr_name_id, $sub_cat_id, $attribute_type_id)";
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



function checkDuplicateAttributeName($name, $attribute_type_id, $id=false)
{
	if(validateForNull($name))
	{
		$sql="SELECT attribute_name_id
			  FROM ems_attribute_name
			  WHERE attribute_name='$name' AND attribute_type_id=$attribute_type_id";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND attribute_name_id!=$id";		  
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


function deleteAttributeName($id)
{
	
	try
	{
		if(!checkifAttributeNameInUse($id))
		{
		$sql="DELETE FROM ems_attribute_name
		      WHERE attribute_name_id=$id";
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



function checkifAttributeNameInUse($id)
{
	
	return false;
	
}			
	

function updateAttributeName($id, $name, $attribute_type_id)
{
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		
		if(validateForNull($name) && checkForNumeric($id, $attribute_name_id) && !checkDuplicateAttributeName($name,$id))
		{
		$sql="UPDATE ems_attribute_name
			  SET attribute_name='$name', attribute_type_id='$attribute_type_id'
			  WHERE attribute_name_id=$id";
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



function getAttributeNameById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT attribute_name_id, attribute_name, attribute_type_id
			  FROM ems_attribute_name
			  WHERE attribute_name_id=$id";
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



function getAttributeNameByAttributeTypeId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT attribute_name_id, attribute_name, attribute_type_id
			  FROM ems_attribute_name
			  WHERE attribute_type_id=$id";
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