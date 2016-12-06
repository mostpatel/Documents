<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");


	



function getCNGById($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT cng_id, cng_value
			  FROM ems_cng
			  WHERE cng_id=$id";
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

function getElectronicsById($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT electronics_id, electronics_value
			  FROM ems_cng
			  WHERE electronics_id=$id";
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