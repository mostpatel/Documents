<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("lead-functions.php");
require_once("rel-subcat-enquiry-attributes-functions.php");
require_once("common.php");
require_once("bd.php");


function listRelSubCatEnquiry()
{
	
	try
	{
		$sql="SELECT rel_subCat_enquiry_form_id, enquiry_form_id, sub_cat_id, customer_price, quantity_id
			  FROM ems_rel_subCategory_enquiry_form";
	    
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

function checkForProductsInArray($sub_cat_array, $mrp_array, $quntity_array)
{
	
	$has_elements=false;
	
	
	
	for($i=0; $i<count($sub_cat_array); $i++)
	{
		
		
		$sub_cat_id = $sub_cat_array[$i];
		$mrp = $mrp_array[$i];
		$quntity_id = $quntity_array[$i];
		
		
		
		if(checkForNumeric($sub_cat_id, $mrp, $quntity_id) && $sub_cat_id>0 && $mrp>=0)
		{
		
		 
		$has_elements=true;
		
		}
	
	
	}
		
	return $has_elements;
}


 function insertArrayElements($enquiry_form_id, $sub_cat_array, $mrp_array, $unit_id_array, $quntity_array,$attribute_name_subCatIdArray_attrTypeIdArray_array)          // insert multiple products to enquiry
{
	
	
	
	 
	$total_mrp=0;
	for($i=0; $i<count($sub_cat_array); $i++)
	{
		$sub_cat_id = $sub_cat_array[$i];
		$mrp = $mrp_array[$i];
		$unit_id = $unit_id_array[$i];
		
		$quntity_id = $quntity_array[$i];
		
		 if(QUANTITY_BOX==1) 
	  {
		  
		$quntity_id = insertQuantity($quntity_id);
	  }
		$attribute_name_id_array_array = $attribute_name_subCatIdArray_attrTypeIdArray_array[$sub_cat_id];
		if(checkForNumeric($sub_cat_id, $mrp, $unit_id, $quntity_id,$enquiry_form_id) && $enquiry_form_id>0 && $sub_cat_id>0 && $mrp>=0)
		{
		$result=insertRelSubCatEnquiry($enquiry_form_id, $sub_cat_id, $mrp, $unit_id, $quntity_id);
	
		insertRelSubCatEnquiryAttributeFinal($enquiry_form_id, $sub_cat_id, $attribute_name_id_array_array);
	
		$total_mrp = $total_mrp + $mrp;
		}
			
	 }
	
	return $total_mrp;
	
}
	

function insertRelSubCatEnquiry($enquiry_form_id, $sub_cat_id, $mrp, $unit_id, $quntity_id)
{
	
	
	try
	{
	if(checkForNumeric($enquiry_form_id, $sub_cat_id) && $enquiry_form_id>0 && $sub_cat_id>0)
	{
	$admin_id=$_SESSION['EMSadminSession']['admin_id'];
			
	$sql="INSERT INTO 
		  ems_rel_subCategory_enquiry_form (enquiry_form_id, sub_cat_id, customer_price, product_unit_id, quantity_id)
		  VALUES ($enquiry_form_id, $sub_cat_id, $mrp, $unit_id, $quntity_id)";
	   
			
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

function deleteRelSubCatFromEnquiryId($id)
{
	
	try
	{
		
		$sql="DELETE FROM ems_rel_subCategory_enquiry_form
		      WHERE enquiry_form_id=$id";
		
		dbQuery($sql);
		return "success";
		
	}
	catch(Exception $e)
	{
	}
	
}

function updateRelSubCatDetails($enquiry_form_id, $sub_cat_id, $mrp, $unit_id, $quntity_id, $attribute_name_id_array_array)
{
	if(defined('SHOW_QUANTITY') && SHOW_QUANTITY==0) 
	 {
		
		$element_in_mrp_array = count($sub_cat_id);
		$quntity_id=array();
		for($i=0;$i<$element_in_mrp_array;$i++)
		{
			$quntity_id[]=1;
		}
	  }
	if(checkForProductsInArray($sub_cat_id,$mrp,$quntity_id))
	{
	deleteRelSubCatFromEnquiryId($enquiry_form_id);
	deleteRelSubCatEnquiryAttributes($enquiry_form_id);
	
	
	
	$total_mrp = insertArrayElements($enquiry_form_id, $sub_cat_id, $mrp, $unit_id, $quntity_id, $attribute_name_id_array_array);
	
	
	updateTotalMRPForEnquiry($enquiry_form_id, $total_mrp);
	
	
	return "success";
	}
	return "error";
}





function deleteRelSubCatEnquiry($id){
	
	try
	{
		
		$sql="DELETE FROM ems_rel_subCategory_enquiry_form
		      WHERE rel_subCat_enquiry_form_id=$id";
		dbQuery($sql);
		return "success";
		
	}
	catch(Exception $e)
	{
	}
	
}	
	
	

function updateRelSubCatEnquiry($id, $enquiry_form_id, $sub_cat_id)
{
	
	try
	{
		
		if(validateForNull($name, $mobile) && checkForNumeric($sub_cat_id))
		{
		$sql="UPDATE ems_rel_subCategory_enquiry_form
			  SET enquiry_form_id='$enquiry_form_id', sub_cat_id=$sub_cat_id
			  WHERE rel_subCat_enquiry_form_id=$id";
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



function getRelSubCatEnquiryFromEnquiryId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT rel_subCat_enquiry_form_id, sub_cat_id, customer_price, quantity_id
			  FROM ems_rel_subCategory_enquiry_form
			  WHERE enquiry_form_id=$id";
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

function getSubCatFromEnquiryId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT rel_subCat_enquiry_form_id, ems_subCategory.sub_cat_id, sub_cat_name, customer_price, quantity_id, product_unit_id
			  FROM ems_rel_subCategory_enquiry_form, ems_subCategory
			  WHERE enquiry_form_id=$id AND  ems_subCategory.sub_cat_id = ems_rel_subCategory_enquiry_form.sub_cat_id";
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