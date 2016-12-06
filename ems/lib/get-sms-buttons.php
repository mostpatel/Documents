<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
	
function getSMSButtons()
{
	
	try
	{
		
		$sql="SELECT custom_sms_id, sms_button_name FROM ems_custom_sms";
		$result=dbQuery($sql);
		//print_r($result);
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

function getSMSMessageById($id)
{
	
	try
	{
		
		$sql="SELECT sms_content 
		FROM ems_custom_sms
		WHERE custom_sms_id = $id";
		$result=dbQuery($sql);
		//print_r($result);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	
	}
	catch(Exception $e)
	{
	}
	
}



?>