<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");


		
function listAirports(){
	
	try
	{
		$sql="SELECT airport_id, airport_code, airport_name, city_name, country_code, country_name FROM trl_airports";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		
	}
	catch(Exception $e)
	{
	}
	
}	

function insertAirport($airport_code,$airport_name,$city_name,$country_code,$country_name){
	try
	{
		$airport_code=clean_data($airport_code);	
		$airport_name = clean_data($airport_name);
		$city_name = clean_data($city_name);
		$country_code = clean_data($country_code);
		$country_name = clean_data($country_name);
		$sql="INSERT INTO trl_airports (airport_code, airport_name, city_name, country_code, country_name) VALUES ('$airport_code','$airport_name','$city_name','$country_code','$country_name')";
		dbQuery($sql);
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteAirport($id){
	try
	{
		$sql="DELETE FROM trl_airports WHERE airport_id = $id";
		dbQuery($sql);
	}
	catch(Exception $e)
	{
	}
	
}	

function updateAirport($airport_code,$airport_name,$city_name,$country_code,$country_name){
	try
	{
		$sql="";
	}
	catch(Exception $e)
	{
	}
	
}	

function getAirportById($id){
	try
	{
		$sql="SELECT airport_id, airport_code, airport_name, city_name, country_code, country_name FROM trl_airports WHERE airport_id = $id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}		
?>