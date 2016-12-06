<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
require_once("sub-category-functions.php");
require_once("attribute-type-functions.php");
require_once("attribute-name-functions.php");


	
function getSuperCatIdsByAttributeTypeId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT super_cat_id
			  FROM ems_rel_attr_type_sup_cat
			  WHERE attribute_type_id=$id";
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		$returnArray = array();
		foreach($resultArray as $re)
		{
			$returnArray[] = $re[0];
		}	
		return $returnArray;
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}

function deleteSuperCatIdsForAttributeTypeId($id)
{
	
	try
	{
		if(1==1)   // Check if it has Attribute Name or it's been in use
		{
		$sql="DELETE FROM ems_rel_attr_type_sup_cat
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


function getCatIdsByAttributeTypeId($id)
{
	
	try
	{
		
		if(checkForNumeric($id))
		{
		$sql="SELECT cat_id
			  FROM ems_rel_attr_type_cat
			  WHERE attribute_type_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		$returnArray = array();
		foreach($resultArray as $re)
		{
			
			$returnArray[] = $re[0];
		}	
		return $returnArray;
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}

function deleteCatIdsForAttributeTypeId($id)
{
	
	try
	{
		if(1==1)   // Check if it has Attribute Name or it's been in use
		{
		$sql="DELETE FROM ems_rel_attr_type_cat
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


function getSubCatIdsByAttributeTypeId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT sub_cat_id
			  FROM ems_rel_attr_type_sub_cat
			  WHERE attribute_type_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		$returnArray = array();
		foreach($resultArray as $re)
		{
			$returnArray[] = $re[0];
		}	
		return $returnArray;
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}

function deleteSubCatIdsForAttributeTypeId($id)
{
	
	try
	{
		if(1==1)   // Check if it has Attribute Name or it's been in use
		{
		$sql="DELETE FROM ems_rel_attr_type_sub_cat
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



function getSuperCatIdsByAttributeTypeIdAndAttributeNameId($type_id, $name_id)
{
	
	try
	{
		if(checkForNumeric($type_id, $name_id))
		{
		$sql="SELECT super_cat_id
			  FROM ems_rel_attr_name_sup_cat
			  WHERE attribute_type_id=$type_id AND attribute_name_id=$name_id";
		
		
		$result=dbQuery($sql);
		
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		$returnArray = array();
		foreach($resultArray as $re)
		{
			$returnArray[] = $re[0];
		}	
		return $returnArray;
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}


function getCatIdsByAttributeTypeIdAndAttributeNameId($type_id, $name_id)
{
	
	try
	{
		if(checkForNumeric($type_id, $name_id))
		{
		$sql="SELECT cat_id
			  FROM ems_rel_attr_name_cat
			  WHERE attribute_type_id=$type_id AND attribute_name_id=$name_id";
		
		
		$result=dbQuery($sql);
		
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		$returnArray = array();
		foreach($resultArray as $re)
		{
			$returnArray[] = $re[0];
		}	
		return $returnArray;
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}


function getSubCatIdsByAttributeTypeIdAndAttributeNameId($type_id, $name_id)
{
	
	try
	{
		if(checkForNumeric($type_id, $name_id))
		{
		$sql="SELECT sub_cat_id
			  FROM ems_rel_attr_name_sub_cat
			  WHERE attribute_type_id=$type_id AND attribute_name_id=$name_id";
		
		
		$result=dbQuery($sql);
		
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		$returnArray = array();
		foreach($resultArray as $re)
		{
			$returnArray[] = $re[0];
		}	
		return $returnArray;
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}


function getAttributeTypeIdsForCatId($cat_id)
{
	
	try
	{
		if(checkForNumeric($cat_id))
		{
		$sql="SELECT attribute_type_id
			  FROM ems_rel_attr_type_cat
			  WHERE cat_id = $cat_id";
		
		
		$result=dbQuery($sql);
		
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		$returnArray = array();
		foreach($resultArray as $re)
		{
			$returnArray[] = $re[0];
		}	
		return $returnArray;
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}


function getAttributeTypeIdsForSuperCatId($super_cat_id)
{
	
	try
	{
		if(checkForNumeric($super_cat_id))
		{
		$sql="SELECT attribute_type_id
			  FROM ems_rel_attr_type_sup_cat
			  WHERE super_cat_id = $super_cat_id";
		
		
		$result=dbQuery($sql);
		
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		$returnArray = array();
		foreach($resultArray as $re)
		{
			$returnArray[] = $re[0];
		}	
		return $returnArray;
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}

function getAttributeTypeIntersection($sub_cat_id_array,$super_cat_id_array,$cat_id_array)
{
	$merge_array=array();
	if(is_array($sub_cat_id_array) && count($sub_cat_id_array)>0)
	{
		foreach($sub_cat_id_array as $sub_cat_id)
		{
				$merge_array[] = getAttributeTypeIdsForASubCat($sub_cat_id);
			
		}	
	}
	if(is_array($cat_id_array) && count($cat_id_array)>0)
	{
		foreach($cat_id_array as $cat_id)
		{
				$merge_array[] = getAttributeTypeIdsForCatId($cat_id);
			
		}	
	}
	if(is_array($super_cat_id_array) && count($super_cat_id_array)>0)
	{
		foreach($super_cat_id_array as $super_cat_id)
		{
				$merge_array[] = getAttributeTypeIdsForSuperCatId($super_cat_id);
			
		}	
	}
	$isect = array();
	if(is_array($merge_array) && count($merge_array)>1)
	$isect = call_user_func_array("array_intersect", $merge_array);
	else if(is_array($merge_array) && count($merge_array)==1)
	$isect = $merge_array[0];
	
	return $isect;
	
}



function getAttributeTypeUnion($sub_cat_id_array,$super_cat_id_array,$cat_id_array)
{
	$merge_array=array();
	if(is_array($sub_cat_id_array) && count($sub_cat_id_array)>0)
	{
		foreach($sub_cat_id_array as $sub_cat_id)
		{
			
				$merge_array[] = getAttributeTypeIdsForASubCat($sub_cat_id);
			
		}	
	}
	
	if(is_array($cat_id_array) && count($cat_id_array)>0)
	{
		foreach($cat_id_array as $cat_id)
		{
				$merge_array[] = getAttributeTypeIdsForCatId($cat_id);
			
		}	
	}
	if(is_array($super_cat_id_array) && count($super_cat_id_array)>0)
	{
		foreach($super_cat_id_array as $super_cat_id)
		{
				$merge_array[] = getAttributeTypeIdsForSuperCatId($super_cat_id);
			
		}	
	}
	$union = array();
	foreach($merge_array as $m_array)
	{
		foreach($m_array as $att)
		$union[]=$att;
		
	}	
	return array_unique($union);
	
}



function getAttributeTypeIdsForASubCat($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
			
			$catId = getCategoryIdBySubCategoryId($id);
			$attributeTypeIdsForCat = getAttributeTypeIdsForCatId($catId);
			
		    if(!$attributeTypeIdsForCat)
			$attributeTypeIdsForCat = array();
			
			$superCatId = getSuperCategoryIdBySubCategoryId($id);
			
			$attributeTypeIdsForSuperCat = getAttributeTypeIdsForSuperCatId($superCatId);
		
			if(!$attributeTypeIdsForSuperCat)
			$attributeTypeIdsForSuperCat = array();
			
		
			
			$sql="SELECT attribute_type_id
			  FROM ems_rel_attr_type_sub_cat
			  WHERE sub_cat_id = $id";
			  
			
		
		
		   $result=dbQuery($sql);
		
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		$returnArray = array();
		foreach($resultArray as $re)
		{
			$returnArray[] = $re[0];
		}	
		
		return array_unique(array_merge( $returnArray, $attributeTypeIdsForCat, $attributeTypeIdsForSuperCat ) );
		}
		else
		{
			return array_unique(array_merge( $attributeTypeIdsForCat, $attributeTypeIdsForSuperCat ) );
		}
		}
	}
		
	catch(Exception $e)
	{
	}
}


function getAttributeNamesFromTypeIdAndCatSubCatSuperCatIdArrays($sub_cat_id_array, $super_cat_id_array, $cat_id_array, $type_id)
{
	
	
    $sub_cat_id_str = implode(",", $sub_cat_id_array);
	$super_cat_id_str = implode(",", $super_cat_id_array);
	$cat_id_str = implode(",", $cat_id_array);
	
	try
	{
		if(validateForNull($sub_cat_id_str) || validateForNull($cat_id_str) || validateForNull($super_cat_id_str))
		{
		if(validateForNull($sub_cat_id_str))
		{
			$cat_str = getCategoryStringFromSubCatString($sub_cat_id_str);
			$super_cat_str = getSuperCategoryStringFromSubCatString($sub_cat_id_str);
	        $sql="SELECT ems_attribute_name.attribute_name_id, attribute_name
			      FROM ems_rel_attr_name_sub_cat, ems_attribute_name
				  WHERE (ems_rel_attr_name_sub_cat.attribute_type_id=$type_id AND sub_cat_id IN ($sub_cat_id_str)) 
				  AND ems_rel_attr_name_sub_cat.attribute_name_id = ems_attribute_name.attribute_name_id
				  ";
			if(validateForNull($cat_str) || validateForNull($cat_str))	  
			{
			$sql=$sql."	  UNION  ";
			$sql=$sql."	
				  
				  SELECT ems_attribute_name.attribute_name_id, attribute_name
			      FROM ems_rel_attr_name_cat, ems_attribute_name
				  WHERE (ems_rel_attr_name_cat.attribute_type_id=$type_id AND cat_id IN ($cat_str)) 
				  AND ems_rel_attr_name_cat.attribute_name_id = ems_attribute_name.attribute_name_id"; 
			}
			if(validateForNull($super_cat_str) || validateForNull($super_cat_str))	
			{  
			$sql=$sql."	  UNION  ";	   
			 $sql=$sql."	  
				  
				  SELECT ems_attribute_name.attribute_name_id, attribute_name
			      FROM ems_rel_attr_name_sup_cat, ems_attribute_name
				  WHERE (ems_rel_attr_name_sup_cat.attribute_type_id=$type_id AND super_cat_id IN ($super_cat_str)) 
				  AND ems_rel_attr_name_sup_cat.attribute_name_id = ems_attribute_name.attribute_name_id"; 
			}
			
			if(validateForNull($cat_id_str) || validateForNull($super_cat_id_str))	  
			$sql=$sql."	  UNION  ";
		}
		if(validateForNull($cat_id_str))
		{
			$sql=$sql."	
				  
				  SELECT ems_attribute_name.attribute_name_id, attribute_name
			      FROM ems_rel_attr_name_cat, ems_attribute_name
				  WHERE (ems_rel_attr_name_cat.attribute_type_id=$type_id AND cat_id IN ($cat_id_str)) 
				  AND ems_rel_attr_name_cat.attribute_name_id = ems_attribute_name.attribute_name_id"; 
			if(validateForNull($super_cat_id_str))	  	  
			$sql=$sql."	  UNION  ";
		}
		if(validateForNull($super_cat_id_str))
		{
		    $sql=$sql."	  
				  
				  SELECT ems_attribute_name.attribute_name_id, attribute_name
			      FROM ems_rel_attr_name_sup_cat, ems_attribute_name
				  WHERE (ems_rel_attr_name_sup_cat.attribute_type_id=$type_id AND super_cat_id IN ($super_cat_id_str)) 
				  AND ems_rel_attr_name_sup_cat.attribute_name_id = ems_attribute_name.attribute_name_id"; 
		}
		
		$result=dbQuery($sql);
		
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		return $resultArray;
		}
		else
		return false;
		}
		return false;
		
	}
	
	catch(Exception $e)
	{
	}
		
		
}

function getCategoryStringFromSubCatString($sub_cat_str)
{
	if(validateForNull($sub_cat_str))
	{
		$sub_cat_array = explode(",",$sub_cat_str);
		$cat_string = "";
		$i=0;
		foreach($sub_cat_array as $sub_cat_id)
		{
			
			$cat_string = $cat_string.getCategoryIdBySubCategoryId($sub_cat_id);
			if($i!=count($sub_cat_array)-1)
			$cat_string = $cat_string.",";
			$i++;
		}
		return $cat_string;
	}
}

function getSuperCategoryStringFromSubCatString($sub_cat_str)
{
	if(validateForNull($sub_cat_str))
	{
		$sub_cat_array = explode(",",$sub_cat_str);
		$cat_string = "";
		$i=0;
		foreach($sub_cat_array as $sub_cat_id)
		{
			
			$cat_string = $cat_string.getSuperCategoryIdBySubCategoryId($sub_cat_id);
			if($i!=count($sub_cat_array)-1)
			$cat_string = $cat_string.",";
			$i++;
		}
		return $cat_string;
	}
}


function getAttributeNamesFromTypeIdAndSubCatId($sub_cat_id, $type_id)
{
	
	try
	{
		
		if(checkForNumeric($sub_cat_id, $type_id))
		{
			$catId = getCategoryIdBySubCategoryId($sub_cat_id);
			$superCatId = getSuperCategoryIdBySubCategoryId($sub_cat_id);
			
			$sql="SELECT ems_attribute_name.attribute_name_id, attribute_name
			      FROM ems_rel_attr_name_sub_cat, ems_attribute_name
				  WHERE ems_rel_attr_name_sub_cat.attribute_type_id=$type_id AND sub_cat_id=$sub_cat_id AND ems_rel_attr_name_sub_cat.attribute_name_id = ems_attribute_name.attribute_name_id ";
			if(checkForNumeric($catId))	  
			$sql=$sql."	  UNION
				  
				  SELECT ems_attribute_name.attribute_name_id, attribute_name
			      FROM ems_rel_attr_name_cat, ems_attribute_name
				  WHERE ems_rel_attr_name_cat.attribute_type_id=$type_id AND cat_id=$catId AND ems_rel_attr_name_cat.attribute_name_id = ems_attribute_name.attribute_name_id";
			if(checkForNumeric($superCatId))	  
			$sql=$sql."	  UNION
				  
				  SELECT ems_attribute_name.attribute_name_id, attribute_name
			      FROM ems_rel_attr_name_sup_cat, ems_attribute_name
				  WHERE ems_rel_attr_name_sup_cat.attribute_type_id=$type_id AND super_cat_id=$superCatId AND ems_rel_attr_name_sup_cat.attribute_name_id = ems_attribute_name.attribute_name_id";
			
			
		
		
		
		$result=dbQuery($sql);
		
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		return $resultArray;
		}
		else
		return false;
		}
		return false;
	}
	
	catch(Exception $e)
	{
	}
	
}

function getAttributesFromSubCatId($sub_cat_id)
{
	
	try
	{
		$return_array=array();
		if(checkForNumeric($sub_cat_id))
		{
			
			 $attribute_type_array = getAttributeTypeIdsForASubCat($sub_cat_id);
			 
		
			 foreach($attribute_type_array as $attribute_type)
			 {
				$attribute_name_array_for_a_type = getAttributeNamesFromTypeIdAndSubCatId($sub_cat_id, $attribute_type); 
				
				if($attribute_name_array_for_a_type && is_array($attribute_name_array_for_a_type) && count($attribute_name_array_for_a_type)>0)
				{
					$attribute_type_array = getAttributeTypeById($attribute_type);
					$return_array[$attribute_type]['attribute_type']=$attribute_type_array;	
					$return_array[$attribute_type]['attribute_name']=$attribute_name_array_for_a_type;	
				}
			 }
			return $return_array;
			
			
		}
		return false;
}
	
	catch(Exception $e)
	{
	}
	
}



function getAttributesFromCatSubCatAndSuperCatIdArray($sub_cat_id_array,$super_cat_id_array,$cat_id_array)
{
	
	try
	{
		$return_array=array();
		if(is_array($sub_cat_id_array))
		{
			
			
			 $attribute_type_array = getAttributeTypeUnion($sub_cat_id_array,$super_cat_id_array,$cat_id_array);
			
			
			 foreach($attribute_type_array as $attribute_type)
			 {
				 foreach($sub_cat_id_array as $sub_cat_id)
				  {
				$attribute_name_array_for_a_type = getAttributeNamesFromTypeIdAndSubCatId($sub_cat_id, $attribute_type); 
				if($attribute_name_array_for_a_type && is_array($attribute_name_array_for_a_type) && count($attribute_name_array_for_a_type)>0)
				{
					$attribute_type_array = getAttributeTypeById($attribute_type);
					$return_array[$attribute_type]['attribute_type']=$attribute_type_array;	
					$return_array[$attribute_type]['attribute_name']=$attribute_name_array_for_a_type;	
				}
				  }
			 }
			return $return_array;
			
			
		}
		return false;
		
		
		
	}
	
	catch(Exception $e)
	{
	}
	
}






function getAttributeTypesFromCatSuperCatAndSubCatIds($sub_cat_id, $super_cat_id=NULL,$cat_id=NULL)
{
	
	try
	{
		
		if(checkForNumeric($sub_cat_id))
		{
			$sql="SELECT attribute_type_id
			  FROM ems_rel_attr_type_sub_cat
			  WHERE sub_cat_id=$sub_cat_id";
			
			$sql="SELECT attribute_type_id
			  FROM ems_rel_attr_type_sup_cat
			  WHERE super_cat_id=$super_cat_id 
			  UNION 
			  SELECT attribute_type_id
			  FROM ems_rel_attr_type_cat
			  WHERE cat_id=$cat_id
			  UNION
			  SELECT attribute_type_id
			  FROM ems_rel_attr_type_sub_cat
			  WHERE sub_cat_id=$sub_cat_id";
		}
		else if($super_cat_id=NULL && checkForNumeric($cat_id, $sub_cat_id))
		{
		$sql="SELECT attribute_type_id
			  FROM ems_rel_attr_type_cat
			  WHERE cat_id=$cat_id
			  UNION
			  SELECT attribute_type_id
			  FROM ems_rel_attr_type_sub_cat
			  WHERE sub_cat_id=$sub_cat_id";
		}
		
		else if($cat_id=NULL && checkForNumeric($super_cat_id, $sub_cat_id))
		{
		$sql="SELECT attribute_type_id
			  FROM ems_rel_attr_type_sup_cat
			  WHERE super_cat_id=$super_cat_id
			  UNION
			  SELECT attribute_type_id
			  FROM ems_rel_attr_type_sub_cat
			  WHERE sub_cat_id=$sub_cat_id";
		}
		
		else if($super_cat_id=NULL && $cat_id=NULL && checkForNumeric($sub_cat_id))
		{
		
		}
		
		$result=dbQuery($sql);
		
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		$returnArray = array();
		foreach($resultArray as $re)
		{
			$returnArray[] = $re[0];
		}	
		return $returnArray;
		}
		else
		return false;
		
	}
	
	catch(Exception $e)
	{
	}
	
}

function getAttributeTypesAndNamesFromCatSuperCatAndSubCatIds($sub_cat_id, $super_cat_id=NULL,$cat_id=NULL)
{
	
	try
	{
		
		if(checkForNumeric($sub_cat_id))
		{
			$sql="SELECT ems_attribute_type.attribute_type_id, attribute_type, GROUP_CONCAT(attribute_name ORDER BY attribute_name)
			  FROM ems_rel_attr_type_sub_cat,ems_rel_attr_name_sub_cat,ems_attribute_type, ems_attribute_name
			  WHERE ems_rel_attr_type_sub_cat.sub_cat_id=$sub_cat_id AND ems_rel_attr_type_sub_cat.attribute_type_id = ems_attribute_type.attribute_type_id AND ems_attribute_type.attribute_type_id = ems_attribute_name.attribute_type_id AND ems_attribute_type.attribute_type_id = ems_rel_attr_name_sub_cat.attribute_type_id GROUP BY ems_attribute_type.attribute_type_id";
			
			$sql="SELECT attribute_type_id
			  FROM ems_rel_attr_type_sup_cat
			  WHERE super_cat_id=$super_cat_id 
			  UNION 
			  SELECT attribute_type_id
			  FROM ems_rel_attr_type_cat
			  WHERE cat_id=$cat_id
			  UNION
			  SELECT attribute_type_id
			  FROM ems_rel_attr_type_sub_cat
			  WHERE sub_cat_id=$sub_cat_id";
		}
		else if($super_cat_id=NULL && checkForNumeric($cat_id, $sub_cat_id))
		{
		$sql="SELECT attribute_type_id
			  FROM ems_rel_attr_type_cat
			  WHERE cat_id=$cat_id
			  UNION
			  SELECT attribute_type_id
			  FROM ems_rel_attr_type_sub_cat
			  WHERE sub_cat_id=$sub_cat_id";
		}
		
		else if($cat_id=NULL && checkForNumeric($super_cat_id, $sub_cat_id))
		{
		$sql="SELECT attribute_type_id
			  FROM ems_rel_attr_type_sup_cat
			  WHERE super_cat_id=$super_cat_id
			  UNION
			  SELECT attribute_type_id
			  FROM ems_rel_attr_type_sub_cat
			  WHERE sub_cat_id=$sub_cat_id";
		}
		
		else if($super_cat_id=NULL && $cat_id=NULL && checkForNumeric($sub_cat_id))
		{
		
		}
		
		$result=dbQuery($sql);
		
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		$returnArray = array();
		foreach($resultArray as $re)
		{
			$returnArray[] = $re[0];
		}	
		return $returnArray;
		}
		else
		return false;
		
	}
	
	catch(Exception $e)
	{
	}
	
}


function getAttributeNamesFromCatSuperCatAndSubCatIds($super_cat_id=NULL, $cat_id, $sub_cat_id)
{
	
	try
	{
		if($super_cat_id!=NULL && $cat_id!=NULL && checkForNumeric($super_cat_id, $cat_id, $sub_cat_id))
		{
			$sql="SELECT attribute_type_id, attribute_name_id
			  FROM ems_rel_attr_name_sup_cat
			  WHERE super_cat_id=$super_cat_id 
			  UNION 
			  SELECT attribute_type_id, attribute_name_id
			  FROM ems_rel_attr_name_cat
			  WHERE cat_id=$cat_id
			  UNION
			  SELECT attribute_type_id, attribute_name_id
			  FROM ems_rel_attr_name_sub_cat
			  WHERE sub_cat_id=$sub_cat_id";
		}
		else if($super_cat_id=NULL && checkForNumeric($cat_id, $sub_cat_id))
		{
		$sql="SELECT attribute_type_id, attribute_name_id
			  FROM ems_rel_attr_name_cat
			  WHERE cat_id=$cat_id
			  UNION
			  SELECT attribute_type_id, attribute_name_id
			  FROM ems_rel_attr_name_sub_cat
			  WHERE sub_cat_id=$sub_cat_id";
		}
		
		else if($cat_id=NULL && checkForNumeric($super_cat_id, $sub_cat_id))
		{
		$sql="SELECT attribute_type_id, attribute_name_id
			  FROM ems_rel_attr_name_sup_cat
			  WHERE super_cat_id=$super_cat_id 
			  UNION
			  SELECT attribute_type_id, attribute_name_id
			  FROM ems_rel_attr_name_sub_cat
			  WHERE sub_cat_id=$sub_cat_id";
		}
		
		else if($super_cat_id=NULL && $cat_id=NULL && checkForNumeric($sub_cat_id))
		{
		$sql="SELECT attribute_type_id, attribute_name_id
			  FROM ems_rel_attr_name_sub_cat
			  WHERE sub_cat_id=$sub_cat_id";
		}
		
		$result=dbQuery($sql);
		
		$resultArray=dbResultToArray($result);
		
		
	}
	
	catch(Exception $e)
	{
	}
	
}



?>