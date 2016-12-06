<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listProductDesc(){
	
	try
	{
		$sql="SELECT product_desc_id, product_desc
		      FROM edms_product_desc
			  ORDER BY product_desc";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfProductDesc()
{
	$sql="SELECT count(product_desc_id)
		      FROM edms_product_desc
			  ORDER BY product_desc";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertProductDesc($product_desc){
	
	try
	{
		$product_desc=clean_data($product_desc);
		//$product_desc = ucwords(strtolower($product_desc));
		if(validateForNull($product_desc) && !checkForDuplicateProductDesc($product_desc))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_product_desc
		      (product_desc)
			  VALUES
			  ('$product_desc')";
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

function deleteProductDesc($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfProductDescInUse($id))
		{
		$sql="DELETE FROM edms_product_desc
		      WHERE product_desc_id=$id";
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

function updateProductDesc($id,$type){
	
	try
	{
		$type=clean_data($type);
	//	$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateProductDesc($type,$id))
		{
			
		$sql="UPDATE edms_product_desc
		      SET product_desc='$type'
			  WHERE product_desc_id=$id";
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

function getProductDescById($id){
	
	try
	{
		$sql="SELECT product_desc_id, product_desc
		      FROM edms_product_desc
			  WHERE product_desc_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getProductDescNameById($id){
	
	try
	{
		$sql="SELECT product_desc_id, product_desc
		      FROM edms_product_desc
			  WHERE product_desc_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateProductDesc($product_desc,$id=false)
{
	    if(validateForNull($product_desc))
		{
		$sql="SELECT product_desc_id
		      FROM edms_product_desc
			  WHERE product_desc='$product_desc'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND product_desc_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfProductDescInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT vehicle_id FROM
			edms_vehicle
			WHERE product_desc_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>