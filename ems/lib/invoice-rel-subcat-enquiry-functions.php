<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");



function checkForProductsInInvoiceArray($sub_cat_array, $mrp_array, $quntity_array)
{
	$has_elements=false;
	
	
	for($i=0; $i<count($sub_cat_array); $i++)
	{
		
		$sub_cat_id = $sub_cat_array[$i];
		$mrp = $mrp_array[$i];
		$quntity_id = $quntity_array[$i];
		
		if(checkForNumeric($sub_cat_id, $mrp, $quntity_id) && $sub_cat_id>0 && $mrp>0)
		{
		
		$has_elements=true;
		}
	
	
	}
		
	return $has_elements;
}


function insertInvoiceArrayElements($in_customer_id, $sub_cat_array, $mrp_array, $quntity_array) // insert multiple products to enquiry
{
	
	if(checkForProductsInInvoiceArray($sub_cat_array,$mrp_array,$quntity_array))
	{
	
	for($i=0; $i<count($sub_cat_array); $i++)
	{
		$sub_cat_id = $sub_cat_array[$i];
		$mrp = $mrp_array[$i];
		$quntity_id = $quntity_array[$i];
		
		if(checkForNumeric($sub_cat_id, $mrp, $quntity_id, $in_customer_id) && $in_customer_id>0 && $sub_cat_id>0 && $mrp>0 )
		{
		$result=insertInvoiceRelSubCatEnquiry($in_customer_id, $sub_cat_id, $mrp, $quntity_id);
		
		}
			
	 }
	}
	
	return "success";
	
}
	

function insertInvoiceRelSubCatEnquiry($in_customer_id, $sub_cat_id, $mrp, $quntity_id)
{
	
	
	try
	{
		
	if(checkForNumeric($in_customer_id, $sub_cat_id,$mrp,$quntity_id) && $in_customer_id>0 && $sub_cat_id>0 && validateForNull($sub_cat_id,$mrp,$quntity_id))
	{
	
	
	
    $admin_id=$_SESSION['EMSadminSession']['admin_id'];
			
	$sql="INSERT INTO 
		  ems_invoice_rel_subCategory_enquiry_form (in_customer_id, sub_cat_id, invoice_price, invoice_quantity_id)
		  VALUES ('$in_customer_id', $sub_cat_id, '$mrp', $quntity_id)";
	
			
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


function getInvoiceRelSubCatEnquiryFromInCustomerId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT ems_in_rel_id, invoice_price, invoice_quantity_id, sub_cat_id, in_customer_id
			  FROM ems_invoice_rel_subCategory_enquiry_form
			  WHERE in_customer_id=$id";
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