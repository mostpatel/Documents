<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");



function checkForProductsInQuotationArray($sub_cat_array, $mrp_array, $quntity_array)
{
	
	$has_elements=false;
	
	for($i=0; $i<count($sub_cat_array); $i++)
	{
		
		$sub_cat_id = $sub_cat_array[$i];
		$mrp = $mrp_array[$i];
		$quntity_id = $quntity_array[$i];
		
		if(checkForNumeric($sub_cat_id, $mrp, $quntity_id) && $sub_cat_id>0)
		{
		
		$has_elements=true;
		}
	
	
	}
	
		return $has_elements;
}


function insertQuotationArrayElements($quo_customer_id, $sub_cat_array, $mrp_array, $quntity_array) // insert multiple products to enquiry
{
	
	
	if(checkForProductsInQuotationArray($sub_cat_array,$mrp_array,$quntity_array))
	{
	
	
	for($i=1; $i<count($sub_cat_array); $i++)
	{
		
		$sub_cat_id = $sub_cat_array[$i];
		$mrp = $mrp_array[$i];
		$quntity_id = $quntity_array[$i];
		
	
		if(checkForNumeric($sub_cat_id, $mrp, $quntity_id, $quo_customer_id) && $quo_customer_id>0 && $sub_cat_id>0)
		{
		$result=insertQuotationRelSubCatEnquiry($quo_customer_id, $sub_cat_id, $mrp, $quntity_id);
		}
			
	 }
	}
	
	return "success";
	
}
	

function insertQuotationRelSubCatEnquiry($quo_customer_id, $sub_cat_id, $mrp, $quntity_id)
{
	
	
	try
	{
		
	if(checkForNumeric($quo_customer_id, $sub_cat_id,$mrp,$quntity_id) && $quo_customer_id>0 && $sub_cat_id>0)
	{
	
	
	
    $admin_id=$_SESSION['EMSadminSession']['admin_id'];
			
	$sql="INSERT INTO 
		  ems_quotation_rel_subCategory_enquiry_form (quo_customer_id, sub_cat_id, quotation_price, quotation_quantity_id)
		  VALUES ($quo_customer_id, $sub_cat_id, '$mrp', $quntity_id)";
	
			
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


function getQuotationRelSubCatEnquiryFromInCustomerId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT ems_quo_rel_id, quotation_price, quotation_quantity_id, sub_cat_id, quo_customer_id
			  FROM ems_quotation_rel_subCategory_enquiry_form
			  WHERE quo_customer_id=$id";
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