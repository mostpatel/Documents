<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("lead-functions.php");
require_once("common.php");
require_once("bd.php");






function checkForAttributeTypesInArray($attribute_type_id_array, $attribute_name_id_array)
{
	$has_elements=false;
	for($i=0; $i<count($attribute_type_id_array); $i++)
	{
		$attribute_type_id = $attribute_type_id_array[$i];
		$attribute_name_id = $attribute_name_id_array[$i];
		
		
		if(checkForNumeric($attribute_type_id, $attribute_name_id) && $attribute_type_id>0 && $attribute_name_id>0)
		{
		$has_elements=true;
		}
	}
		return $has_elements;
}




	
function insertRelSubCatEnquiryAttributeFinal($enquiry_form_id, $sub_cat_id, $attribute_name_id_array_array)
{
	try
	{
	
	if(checkForNumeric($enquiry_form_id, $sub_cat_id) && $enquiry_form_id>0 && $sub_cat_id>0 && is_array($attribute_name_id_array_array) && count($attribute_name_id_array_array)>0)
	{
	  
	   foreach($attribute_name_id_array_array as $attribute_type_id => $attribute_name_id_array)
	   {
		  
	      insertRelSubCatEnquiryAttribute($enquiry_form_id, $sub_cat_id, $attribute_type_id, $attribute_name_id_array);
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


function insertRelSubCatEnquiryAttribute($enquiry_form_id, $sub_cat_id, $attribute_type_id, $attribute_name_id_array)
{
	try
	{
		if(checkForNumeric($enquiry_form_id, $sub_cat_id, $attribute_type_id) && $enquiry_form_id>0 && $sub_cat_id>0 && $attribute_type_id>0)
	{
	  
	   foreach($attribute_name_id_array as $attribute_name_id)
	   {
		  if(checkForNumeric($attribute_name_id) && $attribute_name_id>0) 
		  {
	      $sql="INSERT INTO 
		  ems_rel_subCat_enquiry_form_attributes (enquiry_form_id, sub_cat_id, attribute_type_id, attribute_name_id)
		  VALUES ($enquiry_form_id, $sub_cat_id, $attribute_type_id, $attribute_name_id)";
		  
		  
		 
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

function getAttributeTypesForASubCatOfAnEnquiry($sub_cat_id, $enquiry_form_id)
{
	
	try
	{
		if(checkForNumeric($sub_cat_id, $enquiry_form_id))
		{
		$sql="SELECT ems_rel_subCat_enquiry_form_attributes.attribute_type_id, GROUP_CONCAT(ems_attribute_name.attribute_name_id) as attribute_name_ids_string, GROUP_CONCAT(ems_attribute_name.attribute_name) as attribute_names_string, attribute_type
			  FROM ems_rel_subCat_enquiry_form_attributes, ems_attribute_name, ems_attribute_type
			  WHERE enquiry_form_id=$enquiry_form_id AND sub_cat_id=$sub_cat_id AND ems_rel_subCat_enquiry_form_attributes.attribute_name_id = ems_attribute_name.attribute_name_id AND ems_rel_subCat_enquiry_form_attributes.attribute_type_id = ems_attribute_type.attribute_type_id GROUP BY attribute_type_id";
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

function getAttributeNamesForASubCatOfAnEnquiryForAnAttributeType($sub_cat_id, $enquiry_form_id,$attribute_type_id)
{
	
	try
	{
		if(checkForNumeric($sub_cat_id, $enquiry_form_id,$attribute_type_id))
		{
		$sql="SELECT ems_rel_subCat_enquiry_form_attributes.attribute_type_id, GROUP_CONCAT(ems_attribute_name.attribute_name_id) as attribute_name_ids_string, GROUP_CONCAT(ems_attribute_name.attribute_name) as attribute_names_string, attribute_type
			  FROM ems_rel_subCat_enquiry_form_attributes, ems_attribute_name, ems_attribute_type
			  WHERE enquiry_form_id=$enquiry_form_id AND ems_attribute_type.attribute_type_id=$attribute_type_id AND sub_cat_id=$sub_cat_id AND ems_rel_subCat_enquiry_form_attributes.attribute_name_id = ems_attribute_name.attribute_name_id AND ems_rel_subCat_enquiry_form_attributes.attribute_type_id = ems_attribute_type.attribute_type_id GROUP BY attribute_type_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		return $resultArray[0];
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


function deleteRelSubCatEnquiryAttributes($enquiry_form_id){
	
	try
	{
		
		$sql="DELETE FROM ems_rel_subCat_enquiry_form_attributes
		      WHERE enquiry_form_id = $enquiry_form_id";
		dbQuery($sql);
		return "success";
		
	}
	catch(Exception $e)
	{
	}
	
}	





	
?>