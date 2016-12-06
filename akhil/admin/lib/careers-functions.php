<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");


	
function listCareers()
{
	
	try
	{
		$sql="SELECT career_id, position_name, qualification, description, gender, no
			  FROM trl_careers";
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


function insertCareer($position_name, $qualification, $description, $gender, $no)
{
	try
	{
		$position_name=clean_data($position_name);
		$position_name = ucwords(strtolower($position_name));
		
		$qualification=clean_data($qualification);
		$qualification = ucwords(strtolower($qualification));
		
		$description=clean_data($description);
		
		
		if(validateForNull($position_name, $qualification) && checkForNumeric($gender, $no))
		{
			$sql="INSERT INTO 
			   trl_careers (position_name, qualification, description, gender, no)
				VALUES ('$position_name', '$qualification', '$description', $gender, $no)";
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




function deleteCareer($id)
{
	
	try
	{
		if(1==1) 
		{
		$sql="DELETE FROM trl_careers
		      WHERE career_id=$id";
		
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





?>