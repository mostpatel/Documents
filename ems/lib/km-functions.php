<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function insertKM($km, $enquiry_form_id, $follow_up_id)
{
	try
	{
		$km=clean_data($km);
		
		if(checkForNumeric($km) && (checkForNumeric($enquiry_form_id) || checkForNumeric($follow_up_id)) && $km>0)
		{
			$sql="INSERT INTO ems_km(km, enquiry_form_id, follow_up_id)
				VALUES ($km, $enquiry_form_id, $follow_up_id)";
			
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



		


function deleteKMForEnquiryId($enquiry_form_id){
	
	try
	{
		
		$sql="DELETE FROM ems_km
		      WHERE enquiry_form_id=$enquiry_form_id";
		dbQuery($sql);
		return "success";
		
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteKMForFollowUpId($follow_up_id){
	
	try
	{
		
		$sql="DELETE FROM ems_km
		      WHERE follow_up_id=$follow_up_id";
		dbQuery($sql);
		return "success";
		
	}
	catch(Exception $e)
	{
	}
	
}	


	

function updateKM($id,$name){
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && checkForNumeric($id) && !checkDuplicateSuperCategory($name,$id))
		{
		$sql="UPDATE ems_superCategory
			  SET super_cat_name='$name'
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


function getKMByEnquiryID($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT km_id, km
			  FROM ems_km
			  WHERE enquiry_form_id=$id";
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

function getKMByFollowUpID($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT km_id, km
			  FROM ems_km
			  WHERE follow_up_id=$id";
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