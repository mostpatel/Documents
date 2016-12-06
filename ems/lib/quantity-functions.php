<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listQuantities(){
	
	try
	{
		$sql="SELECT quantity_id, quantity
			  FROM ems_subCategory_quantity";
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



function insertQuantity($quantity){
	try
	{
		$quantity=clean_data($quantity);
		$quantity = ucwords(strtolower($quantity));
		$duplicate=checkDuplicateQuantity($quantity);
		if(validateForNull($quantity) && !$duplicate)
		{
			$sql="INSERT INTO 
				ems_subCategory_quantity (quantity)
				VALUES ('$quantity')";
			
		$result=dbQuery($sql);
		return dbInsertId();
		}
		else if(is_numeric($duplicate))
		{
		 return $duplicate;
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




function checkDuplicateQuantity($quantity,$id=false)
{
	if(validateForNull($name))
	{
		$sql="SELECT quantity_id
			  FROM ems_subCategory_quantity
			  WHERE quantity='$quantity'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND quantity_id!=$id";		  
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



function deleteQuantity($id){
	
	try
	{
		if(!checkifQuantityInUse($id))
		{
		$sql="DELETE FROM ems_subCategory_quantity
		      WHERE quantity_id=$id";
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



function checkifQuantityInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT rel_subCat_enquiry_form_id
	      FROM ems_rel_subCategory_enquiry_form
		  Where quantity_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
}		
	

function updateQuantity($id,$quantity){
	
	try
	{
		$quantity=clean_data($quantity);
		$quantity = ucwords(strtolower($quantity));
		if(validateForNull($quantity) && checkForNumeric($id) && !checkDuplicateQuantity($quantity,$id))
		{
		$sql="UPDATE ems_subCategory_quantity
			  SET quantity='$quantity'
			  WHERE quantity_id=$id";
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


function getQuantityById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT quantity_id, quantity
			  FROM ems_subCategory_quantity
			  WHERE quantity_id=$id";
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


?>