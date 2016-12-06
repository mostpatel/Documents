<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("customer-functions.php");
require_once("enquiry-functions.php");
require_once("rel-subcat-enquiry-functions.php");
require_once("quotation-customer-functions.php");
require_once("quotation-rel-subcat-enquiry-functions.php");
require_once("common.php");
require_once("bd.php");



	

function insertQuotation($name, $sub_cat_id, $mrp, $qunatity_id, $mobile, $email="NA", $address, $city_id, $customer_id, $enquiry_id, $quotation_date)
{
	$quotation_date=str_replace('/','-',$quotation_date);
	$quotation_date=date('Y-m-d',strtotime($quotation_date));
	
	
	try
	{
	
	$admin_id=$_SESSION['EMSadminSession']['admin_id'];
	
			
	$quo_customer_id = insertQuotationCustomer($name, $email, $mobile, $address, $city_id, $customer_id, $quotation_date);
	
    insertQuotationArrayElements($quo_customer_id, $sub_cat_id, $mrp, $qunatity_id );
			
	return $quo_customer_id;
	}
		
	
	catch(Exception $e)
	{
		
	}
	
}

?>