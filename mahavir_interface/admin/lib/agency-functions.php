<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");

		
function listAgencies(){
	
	try
	{
		$sql="SELECT agency_id, agency_name, agency_prefix, agency_contact_name, agency_contact_no, agency_address, auto_pay, rasid_counter
		      FROM fin_agency ORDER BY agency_name";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray; 
	}
	catch(Exception $e)
	{
	}
	
}	
function getTotalNoOfAgencies(){
	
	try
	{
		$sql="SELECT count(agency_id) FROM fin_agency ORDER BY agency_name";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0]; 
	}
	catch(Exception $e)
	{
	}
	
}	
function insertAgency($name,$prefix,$auto_pay,$contact_person,$contact_no,$address){
	
	try
	{
		$name=clean_data($name);
		$prefix=clean_data($prefix);
		$contact_person=clean_data($contact_person);
		$address=clean_data($address);
		if(validateForNull($name,$prefix) && !checkForDuplicateAgency($name,$prefix,$contact_person,$contact_no,$address) && checkForAlphaNumeric($prefix) && strlen($prefix)<5)
		{
			if(!checkForNumeric($contact_no))
			$contact_no=0;	
			
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		$name = ucwords(strtolower($name));
		$contact_person = ucwords(strtolower($contact_person));
		$prefix=strtoupper($prefix);
		
		
		
		if($contact_person=="" || $contact_person==null)
		{
			$contact_person="NA";
			}
		if(!checkForNumeric($contact_no))
		{
			$contact_no=0;
		}
		if($address=="" || $address==null)
		{
			$address="NA";
			}		
		$sql="INSERT INTO fin_agency  					    
		     (agency_name ,agency_prefix, agency_contact_name, agency_contact_no, agency_address, auto_pay, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$name', '$prefix', '$contact_person', '$contact_no', '$address', $auto_pay, $admin_id, $admin_id, NOW(), NOW())";
		$result=dbQuery($sql);	  
		return "success";
		}
		else
		{return "error";}
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteAgency($id){
	
	try
	{
		if(!checkIfAgencyInUse($id))
		{
		$sql="DELETE FROM fin_agency 
			   WHERE agency_id=$id";
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

function updateAgency($id,$name,$prefix,$auto_pay,$rasid_counter,$contact_person,$contact_no,$address){
	
	try
	{
		if(checkForNumeric($id) && validateForNull($name,$prefix) && !checkForDuplicateAgency($name,$prefix,$contact_person,$contact_no,$address,$id))
		{
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		$name = ucwords(strtolower($name));
		$contact_person = ucwords(strtolower($contact_person));
		$prefix=strtoupper($prefix);
		
		$sql="UPDATE fin_agency  					    
		      SET agency_name =  '$name', agency_prefix = '$prefix', agency_contact_name = '$contact_person', agency_contact_no = '$contact_no', agency_address = '$address', auto_pay=$auto_pay,  last_updated_by = $admin_id, date_modified = NOW()
			  WHERE agency_id=$id";
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

function getAgencyById($id){
	
	try
	{
		$sql="SELECT 
		      agency_id, agency_name ,agency_prefix, agency_contact_name, agency_contact_no, agency_address, auto_pay, rasid_counter
			  FROM fin_agency 
			  WHERE agency_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0];
			
		}	  
	}
	catch(Exception $e)
	{
	}	
}	

function checkForDuplicateAgency($name,$prefix,$contact_person,$contact_no,$address,$id=false)
{
	try
	{
		if(validateForNull($prefix))
		{
		$prefix=clean_data($prefix);
		$sql="SELECT 
		      agency_id
			  FROM fin_agency 
			  WHERE agency_prefix = '$prefix'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND agency_id!=$id";		  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			
			return true;
			
		}	
		else
		{
			return false;
			} 
		}
		else
		return false;
	}
	catch(Exception $e)
	{
	}	
	
	
	}	
	
function checkIfAgencyInUse($id)
{
	$sql="SELECT agency_id
		FROM fin_file
		WHERE agency_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;	
	}	

function getAgencyPrefixFromAgencyId($id)
{
	$sql="SELECT agency_prefix FROM fin_agency Where
			agency_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];		
}	

function getRasidnoForAgencyId($id)
{
	$sql="SELECT agency_prefix,rasid_counter FROM fin_agency Where
			agency_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0].$resultArray[0][1];		
}	

function getRasidCounterForAgencyId($id)
{
	$sql="SELECT rasid_counter FROM fin_agency Where
			agency_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];		
}	

function incrementRasidCounterForAgency($id)
{
	$r=getRasidCounterForAgencyId($id);
	$r++;
	$sql="UPDATE fin_agency
	      SET rasid_counter=$r
		  WHERE agency_id=$id";
	dbQuery($sql);	
}

function getAllAutoPaidAgencies()
{
	$sql="SELECT agency_id FROM fin_agency WHERE auto_pay=1";
	$rsult=dbQuery($sql);
	$rsultArray=dbResultToArray($rsult);
	if(dbNumRows($rsult)>0)
	return $rsultArray;
	else return false;
	}
	
function resetAllRasidCounters()
{
		$sql="UPDATE fin_agency SET rasid_reset_date=NOW(), rasid_counter=1";
		dbQuery($sql);
		return "success";
		}
	
function resetRasidCounterForAgency($agency_id)
{
	$sql="UPDATE fin_agency SET rasid_reset_date=NOW(), rasid_counter=1 WHERE agency_id=$agency_id";
		dbQuery($sql);
		return "success";
	}
function getRasidResetDateAgnecy()
{
	$sql="SELECT rasid_reset_date FROM fin_agency";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
	}				
?>