<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listRefrences()
{
	
	try
	{
		$sql="SELECT refrence_id, refrence_name
			  FROM ems_refrence_name";
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



function insertRefrence($name){
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		$duplcate = checkDuplicateRefrence($name);
		if(validateForNull($name) && !checkDuplicateRefrence($name))
		{
			$sql="INSERT INTO 
				ems_refrence_name (refrence_name)
				VALUES ('$name')";
	
		$result=dbQuery($sql);
		
		$refrence_id=dbInsertId();
		
		return $refrence_id;
		}
		else if(checkForNumeric($duplcate))
		return $duplcate;
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}



function checkDuplicateRefrence($name,$id=false)
{
	if(validateForNull($name))
	{
		$sql="SELECT refrence_id
			  FROM ems_refrence_name
			  WHERE refrence_name='$name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND refrence_id!=$id";		  
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


function deleteRefrence($id){
	
	try
	{
		if(!checkifRefrenceInUse($id))
		{
		$sql="DELETE FROM ems_superCategory
		      WHERE super_cat_id=$id";
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

function checkifRefrenceInUse($id)
{
	
	if(checkForNumeric($id))
	{
	$sql="SELECT enquiry_form_id
	      FROM ems_enquiry_form
		  Where refrence_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
	
}		
	




function getRefrenceById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT refrence_id, refrence_name
			  FROM ems_refrence_name
			  WHERE refrence_id=$id";
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


function getRefrenceForEnquiryId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT ems_refrence_name.refrence_id, refrence_name
			  FROM ems_refrence_name, ems_enquiry_form
			  WHERE ems_enquiry_form.enquiry_form_id=$id AND ems_enquiry_form.refrence_id = ems_refrence_name.refrence_id";
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