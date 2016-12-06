<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
		
function listReceiptTypes(){
	
	try
	{
		$sql="SELECT receipt_type_id, receipt_type
		      FROM edms_receipt_type
			  ORDER BY receipt_type_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfReceiptTypes()
{
	$sql="SELECT count(receipt_type_id)
		      FROM edms_receipt_type
			  ORDER BY receipt_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertReceiptType($receipt_type){
	
	try
	{
		$receipt_type=clean_data($receipt_type);
		$receipt_type = ucwords(strtolower($receipt_type));
		if(validateForNull($receipt_type) && !checkForDuplicateReceiptType($receipt_type))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_receipt_type
		      (receipt_type, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$receipt_type', $admin_id, $admin_id, NOW(), NOW())";
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

function deleteReceiptType($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfReceiptTypeInUse($id) && $id>100)
		{
		$sql="DELETE FROM edms_receipt_type
		      WHERE receipt_type_id=$id";
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

function updateReceiptType($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateReceiptType($type,$id) && $id>100)
		{
			
		$sql="UPDATE edms_receipt_type
		      SET receipt_type='$type'
			  WHERE receipt_type_id=$id";
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

function getReceiptTypeById($id){
	
	try
	{
		$sql="SELECT receipt_type_id, receipt_type
		      FROM edms_receipt_type
			  WHERE receipt_type_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getReceiptTypeNameById($id){
	
	try
	{
		$sql="SELECT receipt_type_id, receipt_type
		      FROM edms_receipt_type
			  WHERE receipt_type_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateReceiptType($receipt_type,$id=false)
{
	    if(validateForNull($receipt_type))
		{
		$sql="SELECT receipt_type_id
		      FROM edms_receipt_type
			  WHERE receipt_type='$receipt_type'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND receipt_type_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfReceiptTypeInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT auto_rasid_type FROM
			edms_ac_payment
			WHERE auto_rasid_type=$id
		UNION ALL 
		SELECT auto_rasid_type FROM
			edms_ac_receipt
			WHERE auto_rasid_type=$id	";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>