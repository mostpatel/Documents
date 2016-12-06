<?php 
require_once("cg.php");
require_once("bd.php");
require_once("common.php");
		
function listAreas(){
	
	try
	{
		$sql="SELECT area_id,area_name,city_id
		      FROM ems_city_area";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray; 
	}
	catch(Exception $e)
	{
	}
}

function listAreasAlpha(){
	
	try
	{
		$sql="SELECT area_id,city_id,area_name
		      FROM ems_city_area
			  ORDER BY area_name";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray; 
	}
	catch(Exception $e)
	{
	}
}


function insertArea($name,$city_id){
	
	try
	{
		$name=clean_data($name);
		$name = ucfirst(strtolower($name));
		$duplicate=checkForDuplicateArea($name,$city_id);
		if(checkForNumeric($city_id) && validateForNull($name) && !$duplicate)
		{
			
			$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		$sql="INSERT INTO
		      ems_city_area (area_name, city_id)
			  VALUES
			  ('$name', $city_id)";
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

function deleteArea($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfAreaInUse($id))
		{
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		$sql="DELETE FROM
			  ems_city_area
			  WHERE area_id=$id";
		dbQuery($sql);	
		return  "success";
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

function updateArea($id,$name,$city_id){
	
	try
	{
		$name=clean_data($name);
		$name = ucfirst(strtolower($name));
		$duplicate=checkForDuplicateArea($name,$city_id,$id);
		if(checkForNumeric($city_id) && validateForNull($name) && checkForNumeric($id) && !$duplicate)
		{
			
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		$sql="UPDATE ems_city_area
			  SET area_name='$name', city_id=$city_id
			  WHERE area_id=$id";	  
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

function checkForDuplicateArea($name,$city_id,$id=false)
{
	try{
		$sql="SELECT area_id 
			  FROM 
			  ems_city_area 
			  WHERE area_name='$name'
			  AND city_id=$city_id";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND area_id!=$id";
			  
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
	catch(Exception $e)
	{
		
		}
	
	}

function getAreaByID($id)
{
	$sql="SELECT area_id,city_id, area_name
			  FROM 
			  ems_city_area 
			  WHERE area_id=$id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray[0];
		}
	else
	{
		return false;
		}
	}
function checkIfAreaInUse($id)
{
	
	
		$sql="SELECT area_id
	      FROM fin_customer
		  WHERE area_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	{
		return true;
		}
	
	$sql="SELECT area_id
	      FROM fin_guarantor
		  WHERE area_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	{
		return true;
		}	
		
		return false;
			
		  
	}	

function listAreasFromCityId($city_id)
{
	
	$sql="SELECT area_id, area_name
			  FROM 
			  ems_city_area 
			  WHERE city_id=$city_id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray;
		}
	else
	{
		return false;
		}
	
	}

function getAreaIdFromName($name)
{
	$sql="SELECT area_id
			  FROM 
			  ems_city_area 
			  WHERE area_name='$name'";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray[0][0];
		}
	else
	{
		return false;
		}
	}		
?>