<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listInsuranceCompanies()
{
	
	try
	{
		$sql="SELECT insure_com_id, insure_com_name
			  FROM ems_insurance_company";
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



function insertInsuranceCompany($name)
{
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && !checkDuplicateInsuranceCompany($name))
		{
			$sql="INSERT INTO 
				ems_insurance_company (insure_com_name)
				VALUES ('$name')";
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



function checkDuplicateInsuranceCompany($name,$id=false)
{
	if(validateForNull($name))
	{
		$sql="SELECT insure_com_id
			  FROM ems_insurance_company
			  WHERE insure_com_name='$name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND insure_com_id!=$id";		  
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


function deleteInsuranceCompany($id){
	
	try
	{
		if(!checkifInsuranceCompanyInUse($id))
		{
		$sql="DELETE FROM ems_insurance_company
		      WHERE insure_com_id=$id";
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

function checkifInsuranceCompanyInUse($id)
{
	
	
	return false;
	
	
}		
	

function updateInsuranceCompany($id,$name)
{
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		
		if(validateForNull($name) && checkForNumeric($id) && !checkDuplicateInsuranceCompany($name,$id))
		{
		$sql="UPDATE ems_insurance_company
			  SET insure_com_name ='$name'
			  WHERE insure_com_id=$id";
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


function getInsuranceCompanyById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT insure_com_id, insure_com_name
			  FROM ems_insurance_company
			  WHERE insure_com_id=$id";
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