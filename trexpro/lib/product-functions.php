<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
		
function listProducts(){
	
	try
	{
		$sql="SELECT product_id, product_name
		      FROM edms_product
			  ORDER BY product_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfProducts()
{
	$sql="SELECT count(product_id)
		      FROM edms_product
			  ORDER BY product_name";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertProduct($product_name){
	
	try
	{
		$product_name=clean_data($product_name);
		$product_name = ucwords(strtolower($product_name));
		if(validateForNull($product_name) && !checkForDuplicateProduct($product_name))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_product
		      (product_name, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$product_name', $admin_id, $admin_id, NOW(), NOW())";
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

function insertProductIfNotDuplicate($product_name,$bd2=false){
	
	try
	{
		$product_name=clean_data($product_name);
		$product_name = ucwords(strtolower($product_name));
		$duplicate = checkForDuplicateProduct($product_name,false,$bd2);
		if(validateForNull($product_name) && !$duplicate)
		{
		if($bd2)
		$admin_id = DEFAULT_ADMIN_ID;
		else	
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_product
		      (product_name, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$product_name', $admin_id, $admin_id, NOW(), NOW())";
		dbQuery($sql,$bd2);	  
		return dbInsertId($bd2);
		}
		else if(checkForNumeric($duplicate))
		return $duplicate;
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteProduct($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfProductInUse($id))
		{
		$sql="DELETE FROM edms_product
		      WHERE product_id=$id";
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

function updateProduct($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateProduct($type,$id))
		{
			
		$sql="UPDATE edms_product
		      SET product_name='$type'
			  WHERE product_id=$id";
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

function getProductById($id){
	
	try
	{
		$sql="SELECT product_id, product_name
		      FROM edms_product
			  WHERE product_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getProductNameById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT product_id, product_name
		      FROM edms_product
			  WHERE product_id=$id";
			 
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	
		}
	}
	catch(Exception $e)
	{
	}
	
}	

function getProductIdByName($name){
	
	try
	{
		$name = clean_data($name);
		if(validateForNull($name))
		{
		$sql="SELECT product_id
		      FROM edms_product
			  WHERE product_name='$name'";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];	 
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateProduct($product_name,$id=false,$bd2=false)
{
	    if(validateForNull($product_name))
		{
		$sql="SELECT product_id
		      FROM edms_product
			  WHERE product_name='$product_name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND product_id!=$id";		  	  
		$result=dbQuery($sql,$bd2);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}	
function checkIfProductInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT product_id FROM
			edms_lr_product
			WHERE product_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>