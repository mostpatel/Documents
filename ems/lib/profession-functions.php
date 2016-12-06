<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listProfessions(){
	
	try
	{
		$sql="SELECT profession_id, profession
			  FROM ems_profession";
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



function insertProfession($profession){
	try
	{
		$profession=clean_data($profession);
		$profession = ucwords(strtolower($profession));
		if(validateForNull($profession) && !checkDuplicateProfession($profession))
		{
			$sql="INSERT INTO 
				ems_profession (profession)
				VALUES ('$profession')";
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



function checkDuplicateProfession($profession,$id=false)
{
	if(validateForNull($profession))
	{
		$sql="SELECT profession_id
			  FROM ems_profession
			  WHERE profession='$profession'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND profession_id!=$id";		  
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


function deleteProfession($id){
	
	try
	{
		if(!checkifProfessionInUse($id))
		{
		$sql="DELETE FROM ems_profession
		      WHERE profession_id=$id";
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



function checkifProfessionInUse($id)
{
	
	if(checkForNumeric($id))
	{
	$sql="SELECT extra_details_id
	      FROM ems_customer_extra_details
		  Where profession_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
	
}			
		
	

function updateProfession($id,$profession)
{
	
	try
	{
		$profession=clean_data($profession);
		$profession = ucwords(strtolower($profession));
		if(validateForNull($profession) && checkForNumeric($id) && !checkDuplicateProfession($profession,$id))
		{
		$sql="UPDATE ems_profession
			  SET profession ='$profession'
			  WHERE profession_id=$id";
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


function getProfessionById($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT profession_id, profession
			  FROM ems_profession
			  WHERE profession_id=$id";
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

function getCustomersByProfessionId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT 
			  FROM 
			  WHERE profession_id=$id";
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