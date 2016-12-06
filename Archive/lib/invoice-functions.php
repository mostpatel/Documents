<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("customer-functions.php");
require_once("enquiry-functions.php");
require_once("rel-subcat-enquiry-functions.php");
require_once("invoice-customer-functions.php");
require_once("invoice-rel-subcat-enquiry-functions.php");
require_once("common.php");
require_once("bd.php");



	

function insertInvoice($name, $sub_cat_id, $mrp, $qunatity_id, $mobile, $email="NA", $address, $city_id, $customer_id, $enquiry_id, $invoice_date)
{
	$invoice_date=str_replace('/','-',$invoice_date);
	$invoice_date=date('Y-m-d',strtotime($invoice_date));
	
	
	try
	{
	
	if(validateForNull($sub_cat_id, $name, $mrp, $qunatity_id, $customer_id, $enquiry_id, $invoice_date))
	{
	$admin_id=$_SESSION['EMSadminSession']['admin_id'];
	
			
	$in_customer_id = insertInvoiceCustomer($name, $email, $mobile, $address, $city_id, $customer_id, $invoice_date);
	
    insertInvoiceArrayElements($in_customer_id, $sub_cat_id, $mrp, $qunatity_id );
			
	return $in_customer_id;
	}
	else
	return "error";
	}
		
	
	catch(Exception $e)
	{
		
	}
	
}


function insertDirectInvoice($name, $sub_cat_id, $mrp, $qunatity_id, $mobile, $email="NA", $address, $city_id, $customer_id, $enquiry_id, $invoice_date)
{
	$invoice_date=str_replace('/','-',$invoice_date);
	$invoice_date=date('Y-m-d',strtotime($invoice_date));
	
	
	try
	{
	
	
	$admin_id=$_SESSION['EMSadminSession']['admin_id'];
	
	$customer_id = insertCustomer($name, $email, $mobile);
	insertCustomerExtraDetails($dob="1970-01-01", $address, $city_id, $customer_id );
			
	$in_customer_id = insertInvoiceCustomer($name, $email, $mobile, $address, $city_id, $customer_id, $invoice_date);
	
    insertInvoiceArrayElements($in_customer_id, $sub_cat_id, $mrp, $qunatity_id );
			
	return $in_customer_id;
	}
		
	
	catch(Exception $e)
	{
		
	}
	
}












	
?>