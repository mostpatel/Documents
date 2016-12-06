<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


function listTaxTypes(){
	
	try
	{
		$sql="SELECT tax_id, tax_type, tax_value
			  FROM ems_tax";
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



function insertTax($tax_type, $tax_value){
	try
	{
		$tax_type=clean_data($tax_type);
		$tax_value=clean_data($tax_value);
		$tax_type = ucwords(strtolower($tax_type));
		
		if(validateForNull($tax_type, $tax_value))
		{
			$sql="INSERT INTO 
				ems_tax (tax_type, tax_value)
				VALUES ('$tax_type', $tax_value)";
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





function deleteTax($id){
	
	try
	{
		if(!checkifTaxTypeInUse($id))
		{
		$sql="DELETE FROM ems_tax
		      WHERE tax_id=$id";
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

function checkifTaxTypeInUse($id)
{
	
	return true;
}


	
function updateTax($id,$tax_type, $tax_value)
{
	
	try
	{
		$tax_type=clean_data($tax_type);
		$tax_type = ucwords(strtolower($tax_type));
		$tax_value=clean_data($tax_value);
		
		if(validateForNull($tax_type, $tax_value) && checkForNumeric($id))
		{
		$sql="UPDATE ems_tax
			  SET tax_type='$tax_type', tax_value='$tax_value'
			  WHERE tax_id=$id";
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

function getTaxById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT tax_id, tax_type, tax_value
			  FROM ems_tax
			  WHERE tax_id=$id";
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